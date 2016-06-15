<?php
namespace app\controllers;
use yii\web\Controller;
use app\helpers\TenantHelper;
use app\models\TenantInfo;
class CController extends Controller
{
    public function beforeAction($event)
    {
        if(TenantHelper::isDefaultTenant() === false){
            $tenantInfo = TenantInfo::isValidSubDomain(TenantHelper::getSubDomain()) ;
            if($tenantInfo === false)
                return $this->redirect('http://'.\Yii::$app->params['defaultSiteURL']);
            else{
                if(TenantInfo::getTenantValue($tenantInfo->userId, TenantInfo::CODE_SUBDOMAIN_REDIRECT) == 1){
                    $redirectUrl =  TenantInfo::getTenantValue($tenantInfo->userId, TenantInfo::CODE_REDIRECT_URL);
                    if($redirectUrl !== ''){
                        $redirectUrl = str_replace(['http://', 'https://'], '', $redirectUrl);
                        $redirectUrl = 'http://'.$redirectUrl;
                        return $this->redirect($redirectUrl);
                    }else{
                        return $this->redirect('http://'.\Yii::$app->params['defaultSiteURL']);
                    }
                }
            }
        } 
        return parent::beforeAction($event);
    }
}