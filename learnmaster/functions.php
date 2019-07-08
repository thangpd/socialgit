<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * Global functions in LearnMaster plugin
 */



/**
 * Get instance of LearnMaster App plugin
 * @return \lema\core\App
 */
function lema(){
    return \lema\core\App::getInstance();
}

/**
 * Get lema instructor profile url
 * @param $id
 * @return mixed
 */
function lema_instructor_profile_url($id)
{
    if (lema_is_ready()) {
        $instructor = new \lema\models\Instructor($id);
        return $instructor->getProfileUrl();
    }
    return false;
}

/**
 * Get lema student profile url
 * @param $id
 * @return mixed
 */
function lema_student_profile_url($id)
{
    if (lema_is_ready()) {
        $instructor = new \lema\models\Student($id);
        return $instructor->getProfileUrl();
    }
    return false;

}
/**
 * Get lema learning page url
 * @param $id
 * @return mixed
 */
// function lema_learning_url($id)
// {
//     $instructor = new \lema\models\Learning($id);
//     return $instructor->getUrl();
// }


/**
 * @param $str
 */
function lema_do_shortcode($str) {
    if (lema_is_ready()) {
        return lema()->shortcodeManager->doShortcode($str);
    }
    return false;

}


/**
 * Minify Css files
 * @param $files
 * @param $name
 * @return bool|string
 */
function lema_minify_css($files, $name)
{
    if (lema_is_ready()) {
        return lema()->resourceManager->releaseStyle($files, $name);
    }
    return false;

}

/**
 * Minify script files
 * @param $files
 * @param $name
 * @return bool|string
 */
function lema_minify_js($files, $name)
{
    if (lema_is_ready()) {
        return lema()->resourceManager->releaseScript($files, $name);
    }
    return false;

}

/**
 * @param $html
 * @return mixed
 */
function lema_minify_html($html)
{
    if (lema_is_ready()) {
        return lema()->helpers->general->minifyHtml($html);
    }
    return false;

}


/**
 * @param $name
 * @param $value
 * @param int $exp
 */
function lema_set_cache($name, $value, $exp = 86400)
{
    if (lema_is_ready()) {
        return lema()->cache->set($name, $value, $exp);
    }
    return false;

}

/**
 * @param $name
 * @param null $default
 * @return mixed|null
 */
function lema_get_cache($name, $default = null)
{
    if (lema_is_ready()) {
        return lema()->cache->get($name, $default);
    }
    return false;

}

/**
 * @return bool
 */
function lema_is_ready()
{
    return lema()->isReady();
}


/**
 *
 * Allow other ext/plugin can sell any item via lema payment gateways
 *
 * @param string $payment_gateway_id the payment gateway id . Default is 'lema-paypal-gateway'
 * @param string $item_name Name of item
 * @param float $item_price Price of item
 * @param integer $item_quantity Number of quantity
 * @param string $success_url The URL payment gateway will comeback after pay succeed
 * @param string  $cancel_url The URL payment gateway will comeback after pay failed
 * @return string | false  A approval URI will return if everything works properly. If not, it will return false
 */
function lema_pay($payment_gateway_id, $item_name, $item_price, $item_quantity, $success_url, $cancel_url)
{
    if (lema_is_ready()) {
        $paymentGateways = lema()->helpers->general->getEnabledPaymentGateways();
        $payment = $paymentGateways[$payment_gateway_id];
        if (!empty($payment)) {
            /** @var \lema\core\interfaces\PaymentGatewayInterface $payment */
            $payment->setCancelUrl($cancel_url);
            $payment->setSuccessUrl($success_url);
            return $payment->payAnything($item_name, $item_price ,$item_quantity);
        }
    }
    return false;
}


/**
 * Verify a payment is valid
 * @param string $payment_gateway_id The payment gateway_id
 * @param string $token The token return after payment succeed
 * @param string $payer_id The payer ID who made this payment
 * @param float $total_amount Total of the order that buyer have to pay
 * @return bool|mixed Transaction ID if this transaction is valid. False if not
 */
function lema_paypal_verify($payment_gateway_id, $token, $payer_id, $total_amount)
{
    if (lema_is_ready()) {
        $params = [
            'token' => $token,
            'PayerID' => $payer_id,
            'total' => $total_amount
        ];
        $paymentGateways = lema()->helpers->general->getEnabledPaymentGateways();
        $payment = $paymentGateways[$payment_gateway_id];
        if (!empty($payment)) {
            /** @var \lema\core\interfaces\PaymentGatewayInterface $payment */
            return $payment->verifyPayment($params);
        }
    }
    return false;
}

add_action( 'plugins_loaded', function(){
	load_plugin_textdomain( 'lema', false, basename( dirname( __FILE__ ) ) .DIRECTORY_SEPARATOR.'languages' );
}, 9 );
