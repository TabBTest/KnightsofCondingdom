<?php
namespace app\controllers;
use yii\web\Controller;
use app\helpers\TenantHelper;
use app\models\TenantInfo;
class CController extends Controller
{
    public function beforeAction($event)
    {
        if(TenantHelper::isDefaultTenant() === false && TenantInfo::isValidSubDomain(TenantHelper::getSubDomain()) === false){
            return $this->redirect('http://'.\Yii::$app->params['defaultSiteURL']);
        } 
        return parent::beforeAction($event);
    }
}