<?php
namespace app\helpers;

class NotificationHelper {

    static public function getContextUrl(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'] ;
    }
  
    static public function notifyVendorOfAccount($user, $randomPassword){
        $params = [];
        $params['name'] = $user->name;
        $params['tempPassword'] = $randomPassword;
        $params['loginUrl'] = self::getContextUrl().'/site/login';
        $message = \Yii::$app->mailer->compose('vendor-register-success',$params)
        ->setTo($user->email)
        ->setFrom(\Yii::$app->params['adminEmail'])
        ->setSubject('Successful Registration');
              
        $email = $message->send();
        if($email){
            return true;
        }
        return false;
    }
    
    static public function notifyVendorOfAccountReset($user, $randomPassword){
        $params = [];
        $params['name'] = $user->name;
        $params['tempPassword'] = $randomPassword;
        $params['loginUrl'] = self::getContextUrl().'/site/login';
        $message = \Yii::$app->mailer->compose('vendor-reset-success',$params)
        ->setTo($user->email)
        ->setFrom(\Yii::$app->params['adminEmail'])
        ->setSubject('Account Reset');
    
        $email = $message->send();
        if($email){
            return true;
        }
        return false;
    }
}