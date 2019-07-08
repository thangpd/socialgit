<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\extensions\paypal;


use lema\core\Extension;
use lema\core\interfaces\PaymentGatewayInterface;

use lema\core\RuntimeException;
use lema\models\OrderModel;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\PayPalAPI\SetExpressCheckoutResponseType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

class PaypalExtension extends Extension implements PaymentGatewayInterface
{
    const EXTENSION_ID              = 'paypal';
    const VERSION                   = '1.0.1';
    const GATEWAY_ID                = 'lema-paypal-gateway';
    const GATEWAY_NAME              = 'Pay via Paypal';
    const PP_EXPRESS_URL            = 'https://api.paypal.com/nvp';
    const PP_EXPRESS_SANBOX_URL     = 'https://api.sandbox.paypal.com/nvp';
    const PP_SIGN_URL               = 'https://api-3t.paypal.com/nvp';
    const PP_SIGN_SANBOX_URL        = 'https://api-3t.sandbox.paypal.com/nvp';

    private static $service         = null;
    /**
     * @return string
     */
    private function getExpressUrl()
    {
        if (LEMA_DEBUG) {
            return self::PP_EXPRESS_URL;
        } else {
            return self::PP_EXPRESS_SANBOX_URL;
        }
    }

    /**
     * @return string
     */
    private function getSignUrl()
    {
        return LEMA_DEBUG ? self::PP_SIGN_SANBOX_URL : self::PP_SIGN_URL;
    }

    /**
     * Success url. This URL will return after payment succeed
     * @var string
     */
    private $successUrl             = '';
    /**
     * Cancel url. Return after payment cancelled
     * @var string
     */
    private $cancelUrl              = '';

    /**
     * Current $order
     * @var OrderModel
     */
    private $order                   = null;

    /**
     * Start Learn master extension
     * @return mixed
     */
    public function run()
    {
        /**
         * Register payment gateway to list of lema gateways
         */
        lema()->hook->listenFilter('lema_payment_gateways', [$this, 'registerPaymentGateway']);

    }

    /**
     * @return null|PayPalAPIInterfaceServiceService
     */
    private function getService(&$sandbox = false)
    {
        if (empty(self::$service)) {
            $user = get_option('lema_paypal');
            if (!is_array($user)) {
                $user = unserialize($user);
            }
            if (!empty($user)) {
                $config = array(
                    'mode' => (isset($user['sandbox']) && $user['sandbox']) ? 'sandbox' : 'live',
                    'acct1.UserName' => $user['email'],
                    'acct1.Password' => lema()->helpers->security->decrypt($user['password'], lema()->config->lema_hash_salt),
                    'acct1.Signature' => $user['signature']
                );
                self::$service =  new PayPalAPIInterfaceServiceService($config);
            }
        }
        $sandbox = (isset($user['sandbox']) && $user['sandbox']);
        return self::$service;

    }
    /**
     * Prepare and get approve link for this payment
     * @return string
     */
    private function _prepare()
    {
        $approvalUrl = '';
        $sandbox =  false;
        $service  = $this->getService($sandbox);


        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = lema()->config->lema_currency;
        $orderTotal->value = $this->order->subtotal;
        /** Tax amount */
        $taxValue =  $this->order->tax_value;
        if (!is_numeric($taxValue)) {
            $taxValue = 0.0;
        }
        $taxTotal = new BasicAmountType(lema()->config->lema_currency, $taxValue);


        $itemDetails = [];
        foreach ($this->order->getItems() as $item) {
            $courseName = $item->getCourse()->post->post_title;
            /**  Item */
            $itemAmount = new BasicAmountType(lema()->config->lema_currency, $item->subtotal);
            /** Details of item */
            $itemDetail = new PaymentDetailsItemType();
            $itemDetail->Name = $courseName;
            $itemDetail->Amount = $itemAmount;
            $itemDetail->Quantity = $item->quantity;
            $itemDetail->ItemCategory =  'Physical';
            $itemDetails[] = $itemDetail;
        }




        /** Payment details */
        $PaymentDetails= new PaymentDetailsType();
        $PaymentDetails->PaymentDetailsItem = $itemDetails;
        $PaymentDetails->OrderTotal = $orderTotal;
        $PaymentDetails->PaymentAction = 'Sale';
        $PaymentDetails->ItemTotal = $orderTotal;
        $PaymentDetails->TaxTotal = $taxTotal;

        /** Payment request details */
        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $PaymentDetails;
        $setECReqDetails->CancelURL = ($this->cancelUrl);
        $setECReqDetails->ReturnURL = ($this->successUrl);
        $setECReqDetails->ReqConfirmShipping = 0;
        $setECReqDetails->NoShipping = 1;

        /** @var  $setECReqType */
        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        /** Config paypal service*/
        /** @var SetExpressCheckoutResponseType $setECResponse */
        $setECResponse = $service->SetExpressCheckout($setECReq);
        if($setECResponse->Ack == 'Success')
        {
            $token = $setECResponse->Token;
            update_post_meta($this->order->getId(), 'lema_payment_token', $token);
            //$this->order->save();
            $checkoutUrl = $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' : 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
            $approvalUrl = $checkoutUrl . $token . '&useraction=commit';

        }
        else {
            lema()->logger->error($setECResponse->Errors);
            $message = [];
            foreach ($setECResponse->Errors as $error) {
                $message[] = $error->LongMessage;
            }
            throw new RuntimeException(implode("\n", $message));
        }


        return $approvalUrl;
    }

    /**
     * Setting paypal account
     */
    public function settings()
    {
        $data = [];
        if (is_user_logged_in() && is_admin()) {
            if (!empty($_POST['Paypal'])) {
                $paypal = ($_POST['Paypal']);
                $hashSalt = lema()->config->lema_hash_salt;
                $paypal['password'] = lema()->helpers->security->encrypt($paypal['password'], $hashSalt);
                update_option('lema_paypal', $paypal);
                $data['message'] = __('You changed have been saved', 'lema');
            }
            $paypal = get_option('lema_paypal');
            if (!is_array($paypal)) {
                $paypal = unserialize($paypal);
            }
            if (empty($paypal)) {
                $paypal['email'] = $paypal['password'] = $paypal['signature'] = $paypal['sandbox'] = '' ;
            } else {
                $paypal['password'] = lema()->helpers->security->decrypt($paypal['password'], lema()->config->lema_hash_salt);
            }
            return $this->render('_setting', array_merge($paypal, $data));
        }
    }

    /**
     * Register itself to list of lema payment gateways
     * @param array $gateways
     * @return array
     */
    public function registerPaymentGateway($gateways = []) {
        $gateways[$this->getPaymentGatewayId()] = &$this;
        return $gateways;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Set success redirect uri
     * After payment was succeed client need to redirect to success_url to
     * complete payment action
     * @param string $url
     * @return PaymentGatewayInterface
     */
    public function setSuccessUrl($url)
    {
        $this->successUrl = $url;
        return $this;
    }

    /**
     *
     * Set cancellation url
     * After payment was denied
     * return to this URI to cancel related order
     * @param $url
     * @return PaymentGatewayInterface
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;
        return $this;
    }

    /**
     * Set current order
     * @param OrderModel $order
     * @return PaymentGatewayInterface
     */
    public function setOrder(OrderModel $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Return this payment icon
     * @return mixed
     */
    public function getIcon()
    {
        return $this->getExtensionUrl() . 'assets/images/paypal.jpg';
    }

    /**
     * Get current gateway ID
     * @return string Gateway ID
     */
    public function getPaymentGatewayId()
    {
        return self::GATEWAY_ID;
    }

    /**
     * Get payment description
     * @return string
     */
    public function getDescription()
    {
        return __('Paypal is an electronic commerce (e-commerce) company that facilitates payments between parties through online funds transfers.', 'lema');
    }

    /**
     * Get form of this payment method
     * @return mixed
     */
    public function getPaymentForm()
    {
        $approvalUrl = $this->_prepare();
        $data = [
            'order' => $this->order
        ];
        $data['approvalUrl'] = $approvalUrl;
        // TODO: Implement getPaymentForm() method.
        return $this->render('form', $data, true);
    }

    /**
     * Get name of current gateway
     * @return string
     */
    public function getGatewayName()
    {
        return self::GATEWAY_NAME;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function verifyPayment($params = [])
    {
        if (!empty($params['token'])) {
            $token = $params['token'];
            $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
            $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
            $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;
            $service = $this->getService();
            //$getECResponse = $service->GetExpressCheckoutDetails($getExpressCheckoutReq);


            $orderTotal = new BasicAmountType();
            $orderTotal->currencyID = lema()->config->lema_currency;
            $orderTotal->value = isset($params['total']) ? $params['total'] : $this->order->total;
            $paymentDetails= new PaymentDetailsType();
            $paymentDetails->OrderTotal = $orderTotal;

            /*
             * Your URL for receiving Instant Payment Notification (IPN) about this transaction. If you do not specify this value in the request, the notification URL from your Merchant Profile is used, if one exists.
             */
            if(isset($_REQUEST['notifyURL']))
            {
                $paymentDetails->NotifyURL = $_REQUEST['notifyURL'];
            }
            $DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
            $DoECRequestDetails->PayerID = $params['PayerID'];
            $DoECRequestDetails->Token = $params['token'];
            $DoECRequestDetails->PaymentAction = 'Sale';
            $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

            $DoECRequest = new DoExpressCheckoutPaymentRequestType();
            $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;
            $DoECReq = new DoExpressCheckoutPaymentReq();
            $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

            try {
                $DoECResponse = $service->DoExpressCheckoutPayment($DoECReq);
                if (!empty($this->order)) {
                    update_post_meta($this->order->getId(), 'payal_payer_id', $params['PayerID']);
                    update_post_meta($this->order->getId(), 'payal_payment_info', $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo);
                }
                return $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID;
            } catch (\Exception $e) {
                lema()->logger->error($e);
                return false;
            }
            return false;
        }
    }

    /**
     * Get current version of extension
     * @return mixed
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Automatic check update version
     * @return mixed
     */
    public function checkVersion()
    {
        // TODO: Implement checkVersion() method.
    }

    /**
     * Run this function when plugin was activated
     * We need create something like data table, data roles, caps etc..
     * @return mixed
     */
    public function onActivate()
    {
        // TODO: Implement onActivate() method.
    }

    /**
     * Run this function when plugin was deactivated
     * We need clear all things when we leave.
     * Please be a polite man!
     * @return mixed
     */
    public function onDeactivate()
    {
        // TODO: Implement onDeactivate() method.
    }

    /**
     * Run if current version need to be upgraded
     * @param string $currentVersion
     * @return mixed
     */
    public function onUpgrade($currentVersion)
    {
        // TODO: Implement onUpgrade() method.
    }

    /**
     * Run when learn master was uninstalled
     * @return mixed
     */
    public function onUninstall()
    {
        // TODO: Implement onUninstall() method.
    }

    /**
     * @return string
     */
    public function getId()
    {
        return self::EXTENSION_ID;
    }

    /**
     * @param string $item_name
     * @param float $item_price
     * @param int $item_quantity
     * @return string
     * @throws RuntimeException
     */
    public function payAnything($item_name, $item_price, $item_quantity)
    {
        $approvalUrl = '';
        $sandbox =  false;
        $total =  $item_quantity * $item_price;
        $service  = $this->getService($sandbox);
        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = lema()->config->lema_currency;
        $orderTotal->value = $total;
        /** Tax amount */
        $taxValue =  0.0;
        $taxTotal = new BasicAmountType(lema()->config->lema_currency, $taxValue);


        $itemDetails = [];
        /**  Item */
        $itemAmount = new BasicAmountType(lema()->config->lema_currency, $item_price);
        /** Details of item */
        $itemDetail = new PaymentDetailsItemType();
        $itemDetail->Name = $item_name;
        $itemDetail->Amount = $item_price;
        $itemDetail->Quantity = $item_quantity;
        $itemDetail->ItemCategory =  'Physical';
        $itemDetails[] = $itemDetail;

        /** Payment details */
        $PaymentDetails= new PaymentDetailsType();
        $PaymentDetails->PaymentDetailsItem = $itemDetails;
        $PaymentDetails->OrderTotal = $orderTotal;
        $PaymentDetails->PaymentAction = 'Sale';
        $PaymentDetails->ItemTotal = $orderTotal;
        $PaymentDetails->TaxTotal = $taxTotal;

        /** Payment request details */
        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $PaymentDetails;
        $setECReqDetails->CancelURL = ($this->cancelUrl);
        $setECReqDetails->ReturnURL = ($this->successUrl);
        $setECReqDetails->ReqConfirmShipping = 0;
        $setECReqDetails->NoShipping = 1;

        /** @var  $setECReqType */
        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        /** Config paypal service*/
        /** @var SetExpressCheckoutResponseType $setECResponse */
        $setECResponse = $service->SetExpressCheckout($setECReq);
        if($setECResponse->Ack == 'Success')
        {
            $token = $setECResponse->Token;
            $checkoutUrl = $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' : 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
            $approvalUrl = $checkoutUrl . $token . '&useraction=commit';

        }
        else {
            lema()->logger->error($setECResponse->Errors);
            $message = [];
            foreach ($setECResponse->Errors as $error) {
                $message[] = $error->LongMessage;
            }
            throw new RuntimeException(implode("\n", $message));
        }
        return $approvalUrl;
    }
}