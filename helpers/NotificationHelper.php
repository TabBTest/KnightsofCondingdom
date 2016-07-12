<?php
namespace app\helpers;

use app\models\VendorPromotion;
use app\models\PromotionUserStatus;
use app\models\User;
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
    
    static public function notifyUserOfAccount($user, $randomPassword){
        $params = [];
        $params['name'] = $user->name;
        $params['tempPassword'] = $randomPassword;
        $params['loginUrl'] = self::getContextUrl().'/site/login';
        $message = \Yii::$app->mailer->compose('customer-register-success',$params)
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
    
    static public function notifyUserOfAccountReset($user, $randomPassword){
        $params = [];
        $params['name'] = $user->name;
        $params['tempPassword'] = $randomPassword;
        $params['loginUrl'] = self::getContextUrl().'/site/login';
        $message = \Yii::$app->mailer->compose('customer-reset-success',$params)
        ->setTo($user->email)
        ->setFrom(\Yii::$app->params['adminEmail'])
        ->setSubject('Account Reset');
    
        $email = $message->send();
        if($email){
            return true;
        }
        return false;
    }
    
    static public function sendPromotion($promo){
        if($promo->promoType == VendorPromotion::TYPE_EMAIL){
            
            $emails = [];
            $promoUsers = PromotionUserStatus::findAll(['vendorPromotionId' => $promo->id]);
            foreach($promoUsers as $promoUser){
                $user = User::findOne($promoUser->userId);
                $emails[] = $user->email;
            }
            
            //$unsub = "Click <a href='/unsub?id=".."'>here</a> to unsubscribe from the mailing list";
                 
            $message = \Yii::$app->mailer->compose()->setHtmlBody($promo->html)
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setSubject($promo->subject);
            
            
            $message->bcc = $emails;
            PromotionUserStatus::updateAll(['status' => PromotionUserStatus::STATUS_PROCESSING], ['vendorPromotionId' => $promo->id]);
            $email = $message->send();
            if($email){
                PromotionUserStatus::updateAll(['status' => PromotionUserStatus::STATUS_SENT], ['vendorPromotionId' => $promo->id]);
                return true;
            }
            PromotionUserStatus::updateAll(['status' => PromotionUserStatus::STATUS_FAILED], ['vendorPromotionId' => $promo->id]);
            return false;
        }else if($promo->promoType == VendorPromotion::TYPE_SMS){
            
            $users = [];
            $promoUsers = PromotionUserStatus::findAll(['vendorPromotionId' => $promo->id]);
            foreach($promoUsers as $promoUser){
                $user = User::findOne($promoUser->userId);
                $users[] = $user;
            }
            
            
            foreach($users as $user){
//                 var_dump($user->phoneNumber);
//                 die;
                //$user->phoneNumber = '15304773451';
                if($user->phoneNumber != ''){
                    //notify SMS
                    $cellPhone = str_replace(array("+1", "(", ")", " ", "-", "+"), "", $user->phoneNumber);
                    try{
                        $sid =  \Yii::$app->params['twilio.sid'] ; // "ACcb406d1ac7721f12fa4958ab18803345"; // Your Account SID from www.twilio.com/user/account
                        $token = \Yii::$app->params['twilio.token']; //  "c8508c7fdc8b72ad51e2b89cc5351655"; // Your Auth Token from www.twilio.com/user/account
                
                        //$client = new \Twilio\Rest\Client($sid, $token);
                        /*
                         $message = $client->account->messages->create(
                             '15005550006', // From a valid Twilio number
                             '13057090915', // Text this number
                             array(
                                 'Body' => $body
                             )
                         );
                         */
                        PromotionUserStatus::updateAll(['status' => PromotionUserStatus::STATUS_PROCESSING], ['vendorPromotionId' => $promo->id, 'userId' => $user->id]);
                        
                        $from = \Yii::$app->params['twilio.phone'] ; //'15005550006';
                        $to = $cellPhone ;//'13057090915';
                        self::send_sms($sid, $token, $to, $from, $promo->html );
                        //echo 'SMS Sent: '.$cellPhone;
                        
                        PromotionUserStatus::updateAll(['status' => PromotionUserStatus::STATUS_SENT], ['vendorPromotionId' => $promo->id, 'userId' => $user->id]);
                        
                        
                    }catch (Exception $e){
                        //var_dump($e->getMessage( ));
                        PromotionUserStatus::updateAll(['status' => PromotionUserStatus::STATUS_FAILED], ['vendorPromotionId' => $promo->id, 'userId' => $user->id]);
                    }
                
                
                }
            }
        }
        return true;
    }
    
    static public function send_sms( $sid, $token, $to, $from, $body ) {
        // resource url & authentication
        $uri = 'https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/SMS/Messages';
        $auth = $sid . ':' . $token;
    
        // post string (phone number format= +15554443333 ), case matters
        $fields =
        '&To=' .  urlencode( $to ) .
        '&From=' . urlencode( $from ) .
        '&Body=' . urlencode( $body );
    
        // start cURL
        $res = curl_init();
         
        // set cURL options
        curl_setopt( $res, CURLOPT_URL, $uri );
        curl_setopt( $res, CURLOPT_POST, 3 ); // number of fields
        curl_setopt( $res, CURLOPT_POSTFIELDS, $fields );
        curl_setopt( $res, CURLOPT_USERPWD, $auth ); // authenticate
        curl_setopt( $res, CURLOPT_RETURNTRANSFER, true ); // don't echo
         
        // send cURL
        $result = curl_exec( $res );
        //var_dump($result);
        return $result;
    }
}