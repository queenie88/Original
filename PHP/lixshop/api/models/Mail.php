<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\web\HttpException;
use api\models\order\Order;
use common\models\Appointment;
use common\models\Attachment;

class Mail extends Model
{
    public $order_id;
    public $appointment_id;

    public function sendOrderMessage()
    {
        if (is_null($this->order_id)) {
            return;
        }
        $order = Order::findOne($this->order_id);
        $address = $order->userName . " " . $order->city . $order->county . $order->street;
        $tel = " 电话: " . $order->tel_number;

        //controller代码 
        $mail = Yii::$app->mailer->compose('@app/mail/order', ['order' => $order]) 
            ->setTo('1916555871@qq.com') 
            ->setSubject('新订单通知 ' . $address . " 价格： " . $order->real_amount . "元" . $tel) 
            ->send(); 
        return $order;
    }

    public function sendAppointmentMessage()
    {
        if (is_null($this->appointment_id)) {
            return;
        }

        $appointment = Appointment::findOne($this->appointment_id);
        $address = $appointment->userName . " " . $appointment->city . $appointment->county . $appointment->street;
        $tel = " 电话: " . $appointment->tel_number;
        //controller代码 
        $mail = Yii::$app->mailer->compose('@app/mail/appointment', ['appointment' => $appointment]);
        $mail->setTo('1916555871@qq.com');
        $mail->setSubject('新团购预约 ' . $address . $tel);
        if ($appointment->enter_type == 2) {
            foreach ($appointment->images as $img) {
                $mail->attach($img['url']);
            }
        }
        $mail->send();
        return $appointment;
    }
}
