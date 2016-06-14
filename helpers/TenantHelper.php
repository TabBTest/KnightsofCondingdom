<?php

namespace app\helpers;

use app\models\User;
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