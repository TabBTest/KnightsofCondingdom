<?php

namespace app\helpers;

use app\models\User;
use app\models\TenantInfo;
use app\models\VendorMembership;
use app\models\AppConfig;
class TenantHelper {
    
    static public function isDefaultTenant(){
        $domainUrl = $_SERVER['HTTP_HOST'];
        if(\Yii::$app->params['defaultSiteURL'] == $domainUrl)
            return true;
        return false;
    }
    static public function getTenantUrl(){
        $domainUrl = $_SERVER['HTTP_HOST'];
        if(\Yii::$app->params['defaultSiteURL'] == $domainUrl)
            return 'default';
        return $domainUrl;
    }
    
    static public function get_domain($url)
    {
        $pieces = parse_url('//'.$url);
        //var_dump($pieces);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }
    
    static public function getSubDomain(){
        $domain = self::get_domain($_SERVER['SERVER_NAME']);
        $fullUrl = $_SERVER['SERVER_NAME'];
        $subdomain = str_replace('.'.$domain, '', $fullUrl);
        return ($subdomain);
    }
    
    static public function getVendorSubdomain($userId){
        $subdomain = TenantInfo::getTenantValue($userId, TenantInfo::CODE_SUBDOMAIN);
        return $subdomain.'.'.self::get_domain($_SERVER['SERVER_NAME']);
    }

    static public function getVendorImageFromUrl()
    {
        $url = $_SERVER['HTTP_HOST'];
        $urlPartial = explode(".", $url);
        $subdomain = array_shift($urlPartial);
        $userId = TenantInfo::findOne(['code' => TenantInfo::CODE_SUBDOMAIN, 'val' => $subdomain])->userId;
        $imageFile = User::findOne($userId)->imageFile;
        return $imageFile;
    }

    static public function getVendorNameFromUrl()
    {
        $url = $_SERVER['HTTP_HOST'];
        $urlPartial = explode(".", $url);
        $subdomain = array_shift($urlPartial);
        $userId = TenantInfo::findOne(['code' => TenantInfo::CODE_SUBDOMAIN, 'val' => $subdomain])->userId;
        $businessName = User::findOne($userId)->businessName;
        return $businessName;
    }
    
    static function doMembershipPayment($userId){
        $respInfo = array();
        $membershipPrice = UtilityHelper::getAppConfig(AppConfig::MONTHLY_MEMBERSHIP_FEE, User::MEMBERSHIP_PRICE);
        $startDate = false;
        $endDate = false;
    
        $user = User::findOne($userId);
    
        $lastActiveMembership = VendorMembership::getLastActiveMembership($user->id);
        
        
        $newStartDateTime = strtotime('now');
        if($lastActiveMembership !== false){
            $newStartDateTime = strtotime('+1 day', strtotime($lastActiveMembership->endDate));            
        }
        
        $startDate = date('Y-m-d', $newStartDateTime);
        $endDate = date('Y-m-d', (strtotime('+1 month',$newStartDateTime)));
        
        $paymentInfo = array();
        $error = false;
        try{    
            $totalCharge  = floatval($membershipPrice);
            $amount = $totalCharge * 100;
            $transactionId = '';
            $isSuccess = false;
            
            \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);

            $charge = \Stripe\Charge::create(
                array(
                    "amount" => $amount,
                    "currency" => "usd",
                    "customer" => $user->stripeId, // obtained with Stripe.js
                    "description" => "Vendor Membership - '.$startDate.' - '.$endDate.' : Charge for Vendor ID: ".$user->id,
                )  );
            
            //echo $charge;
            $chargeArray = $charge->__toArray(true);
            if($chargeArray['status'] == 'succeeded'){
                           
                    $transactionId = $chargeArray['id'];
                    $error = false;
                    //for($x = 0 ; $x < 50 ; $x++)
                
                    $userMemberShip = new VendorMembership();
                    $userMemberShip->vendorId = $user->id;
                    $userMemberShip->startDate = $startDate;
                    $userMemberShip->endDate = $endDate;
                    $userMemberShip->transactionId = $transactionId;
                    $userMemberShip->amount = $totalCharge;
                    $userMemberShip->cardLast4 = $user->cardLast4;
                
                    if($userMemberShip->save()){
                        return true;
                        ;//NotificationHelper::sendAdminNotificationOfMembershipPurchase($userMemberShip, $totalCharge, $postParams['membershipType']);
                    }                                    
            }
    
            
    
    
        }catch (\Stripe\Error\Card $e){
            $error = $e->getJsonBody()['error']['message'];
        }
        return false;
    }
    static public function getCloseReason(){
        if(TenantHelper::isDefaultTenant() === false){
            $subdomain = TenantHelper::getSubDomain();
            $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
            if($tenantInfo){
                $userVendor = User::findOne($tenantInfo->userId);
                if($userVendor->isStoreOpen == 0 && $userVendor->storeCloseReason != null && $userVendor->storeCloseReason != ''){
                    return $userVendor->storeCloseReason;
                }
            }
        
        }
        return '';
    }
    
    static public function getTimeToPickUp(){
        return ['1' => '0 - 15 mins',
            '2' => '15 - 30 mins',
            '3' => '30 - 45 mins',
            '4' => '45 - 60 mins',
            '5' => '1hr - 1hr 15 mins',
            '6' => '1hr 15 mins - 1hr 30 mins',
            '7' => '1hr 30 mins - 1hr 45 mins',
            '8' => '1hr 45 mins - 2hrs',
        ];
    }
    static public function isVendorStoreClose(){
        if(TenantHelper::isDefaultTenant() === false){
            $subdomain = TenantHelper::getSubDomain();
            $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
            if($tenantInfo){
                $userVendor = User::findOne($tenantInfo->userId);
                if($userVendor->isVendorStoreOpen() === false){
                    return true;
                }
            }
                    
        }
        return false;
    }
    static public function isVendorAllowDelivery($totalFinalAmount){
        if(TenantHelper::isDefaultTenant() === false){
            $subdomain = TenantHelper::getSubDomain();
            $subdomain = TenantHelper::getSubDomain();
            $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
            if($tenantInfo){
                $tenantInfo = TenantInfo::findOne(['userId' => $tenantInfo->userId, 'code' => TenantInfo::CODE_HAS_DELIVERY]);
                if($tenantInfo && $tenantInfo->val == 1){
                    $tenantInfoAmount = TenantInfo::findOne(['userId' => $tenantInfo->userId, 'code' => TenantInfo::CODE_DELIVERY_MINIMUM_AMOUNT]);
                    if($tenantInfoAmount && $tenantInfoAmount->val != ''){
                        $minAmount = floatval($tenantInfoAmount->val);
                        if($totalFinalAmount >= $minAmount){
                            return true;
                        }
                    }
                
                }    
            }
            
        
        }
        return false;
    }
    static public function getDeliveryAmount(){
        if(TenantHelper::isDefaultTenant() === false){
            $subdomain = TenantHelper::getSubDomain();
            $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
           
            
            if($tenantInfo){
                $tenantInfoAmount = TenantInfo::findOne(['userId' => $tenantInfo->userId, 'code' => TenantInfo::CODE_DELIVERY_CHARGE]);
                return floatval($tenantInfoAmount->val);
                    
                  
            }
        
        }
        return 0;
    }
    /*
    static public function getTenantDataInfo($tenantId = false){
        
        $resp = [];
        $resp[TenantInfo::INFO_IS_DEFAULT] = false;
        if($tenantId === false){
            $domainUrl = self::getTenantUrl();
            if($domainUrl == 'default'){
                $resp[TenantInfo::INFO_TENANT_LOGO] = '';
                $resp[TenantInfo::INFO_TENANT_LOGO_BLACK] = '';
                
                $resp[TenantInfo::INFO_TENANT_AGENCY_NAME] = '';
                $resp[TenantInfo::INFO_TENANT_SUPPORT_EMAIL] = \Yii::$app->params['supportEmail'];
                $resp[TenantInfo::INFO_TENANT_CONF_EMAIL] = \Yii::$app->params['success_confirmation_email_receiver'];
                $resp[TenantInfo::INFO_TENANT_CONTACT_PHONE] = \Yii::$app->params['phoneNumberLink'];
                $resp[TenantInfo::INFO_TENANT_DEFAULT_SYSTEM_EMAIL] = \Yii::$app->params['systemFromEmail'];
                $resp[TenantInfo::INFO_IS_DEFAULT] = true;
                
            }else{
                $user = ViewUserTenants::findOne(['tenantUrl' => $domainUrl, 'role' => User::ROLE_AGENCY]);
                if($user != null){
                    $tenantId = $user->tenantId;
                    
                }
            }
        }
        
        
        if($tenantId !== false && $tenantId > 0){
            $tenantCustomDatas = TenantCustomData::findAll(['tenantId' => $tenantId]);
            foreach($tenantCustomDatas as $data){
                if($data->tenantInfo !== false){
                    $resp[$data->tenantInfo->code] = $data->val;
                }
            }
            $user = ViewUserTenants::findOne(['user_id' => $tenantId, 'role' => User::ROLE_AGENCY]);
            $resp[TenantInfo::INFO_TENANT_AGENCY_NAME] = $user != null ? $user->agencyName : '';
        }
        return $resp;
    }
    
    static public function getTenantCustomData($tenantDataInfoCode){
        $tenantDataInfo = self::getTenantDataInfo();
        if(isset($tenantDataInfo[$tenantDataInfoCode]))
            return $tenantDataInfo[$tenantDataInfoCode];
        return '';
    }
    */
}
