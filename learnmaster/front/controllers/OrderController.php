<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\front\controllers;


use lema\core\components\cart\Cart;
use lema\core\interfaces\FrontControllerInterface;
use lema\core\interfaces\PaymentGatewayInterface;
use lema\core\RuntimeException;
use lema\models\CourseModel;
use lema\models\OrderItemModel;
use lema\models\OrderModel;
use lema\models\Student;

class OrderController extends FrontController implements FrontControllerInterface
{

    /**
     * @return string
     * @throws RuntimeException
     */
    public function successOrder()
    {
        $status = false;
        if (!empty($_GET['token'])) {
            if (is_user_logged_in()) {
                $token = $_GET['token'];
                $order = OrderModel::getOrderByPayment($token);

                if (!empty($order)) {
                    if ($order->lema_order_user_id != get_current_user_id()) {
                        throw new RuntimeException(__('Invalid order. Please try again', 'lema'));
                    }
                    //Check
                    $paymentGateways = lema()->helpers->general->getEnabledPaymentGateways();
                    if (!empty($paymentGateways)) {
                        /** @var PaymentGatewayInterface $payment */
                        $payment = $paymentGateways[$order->lema_payment_method];
                        if (!empty($payment)) {
                            $payment->setOrder($order);
                            $transactionId = $payment->verifyPayment($_GET);
                            if($transactionId) {
                                $status = true;
                                Cart::getInstance()->destroy();
                                update_post_meta($order->getId(), 'order_status', OrderModel::ORDER_STATUS_COMPLETED);
                                update_post_meta($order->getId(), 'lema_payment_transaction_id', $transactionId);
                                do_action('lema_course_order_succeed', $order);
                                //send mail
                                $student = new Student($order->lema_order_user_id);
                                do_action(
                                    \lema\core\components\Hook::LEMA_SEND_MAIL,
                                    \lema\core\components\Mailer::MAIL_STUDENT_ENROLL_PAID,
                                    __('Successfully enrolled courses', 'lema'),
                                    $student->user->user_email,
                                    [
                                        'order' => $order,
                                        'student' => $student
                                    ]
                                );
                                return $this->render('success', ['order' => $order, 'status' => $status, 'student' => $student]);
                            }
                        }
                    }
                } else {
                    throw new RuntimeException(__('Invalid order', 'lema'));
                }
            } else {
                throw new RuntimeException(__('Please login first', 'lema'));
            }
        }
        throw new RuntimeException(__('Invalid request', 'lema'));
    }
    /**
     * Cancel order action
     * @return string
     */
    public function cancelOrder()
    {
        if (!empty($_GET['token'])) {
            if (is_user_logged_in()) {
                $token = $_GET['token'];
                $order = OrderModel::getOrderByPayment($token);
                if (empty($order) || $order->lema_order_user_id != get_current_user_id()) {
                    throw new RuntimeException(__('Invalid order. Please try again', 'lema'));
                }
                if (!empty($order)) {
                    //$order->order_status = OrderModel::ORDER_STATUS_CANCELLED;
                    Cart::getInstance()->destroy();
                    update_post_meta($order->getId(), 'order_status', OrderModel::ORDER_STATUS_CANCELLED);
                    do_action('lema_course_order_failed', $order);
                    return $this->render('cancel', ['order' => $order]);
                }
            } else {
                echo (__('Please login first', 'lema'));
            }
        } else {
            echo (__('Invalid request', 'lema'));
        }
    }

    /**
     * Enroll a free course
     * If course price is zero, create new order with completed status
     * then redirect user to course dashboard
     */
    public function enrollFreeCourse()
    {
        if (isset($_GET['course_id'])) {
            /** @var CourseModel $course */
            $course = CourseModel::findOne($_GET['course_id']);
            if (!empty($course) && $course->getPrice() == 0) {
                //Check order is already existed
                if (is_user_logged_in()) {
                    $dashboardUrl = $course->getDashboardUrl();
                    $userId = get_current_user_id();
                    /** @var Student $student */
                    $student = new Student($userId);
                    if (!$student->checkEnrolled($course->getId())) {
                        $order = new OrderModel();
                        $orderId = $order->save();
                        if ($orderId) {
                            update_post_meta($orderId, 'lema_order_user_id', $userId);
                            update_post_meta($orderId, 'order_status', OrderModel::ORDER_STATUS_COMPLETED);
                            $item = new OrderItemModel();
                            $item->course_id = $course->getId();
                            $item->quantity = 1;
                            $item->order_id = $orderId;
                            $item->save();
                            wp_update_post([
                                'ID' => $orderId,
                                'title' => '#Order ' . $orderId
                            ]);
                            do_action(
                                \lema\core\components\Hook::LEMA_SEND_MAIL,
                                \lema\core\components\Mailer::MAIL_STUDENT_ENROLL_FREE,
                                __('Success to register the course', 'lema'),
                                $student->user->user_email,
                                [
                                    'order' => $order,
                                    'course' => $course,
                                    'student' => $student
                                ]
                            );
                        } else {
                            throw new RuntimeException(__('Can not enroll to this course at this time. Please try again later', 'lema'));
                        }

                    }
                    return $this->render('enroll', [
                        'dashboardUrl' => $dashboardUrl,
                        'course' => $course
                    ]);
                } else {
                    $loginUrl = wp_login_url(lema()->page->getPageUrl(lema()->config->getUrlConfigs('lema-enroll')) .'/' . $_GET['course_id']);
                    return lema()->helpers->general->baseRedirect($loginUrl);
                }
            }
        }
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'pages' => [
                'front' => [
                    'lema-cancel-order' => ['cancelOrder',[
                        'db' => false,
                        'title' => __('Cancel order', 'lema')
                    ]],
                    'lema-success-order' => ['successOrder',[
                        'db' => false,
                        'title' => __('Thank you!', 'lema')
                    ]],
                    lema()->config->getUrlConfigs('lema-enroll') . '/[(\d+) as course_id]' => ['enrollFreeCourse',[
                        'db' => false,
                        'title' => __('Enroll the course!', 'lema')
                    ]],
                ]
            ]
        ];
    }
}