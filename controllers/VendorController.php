<?php

namespace app\controllers;

use Yii;
use app\models\ApplicationType;
use app\models\ApplicationTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\ViewContextInterface;
use app\models\ApplicationTypeFormSetup;
use app\models\Candidates;
use yii\base\Application;
use app\models\TenantInfo;
use app\models\VendorMembership;
use app\models\User;
use app\models\VendorOperatingHours;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class VendorController extends CController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['settings', 'save-settings', 'profile', 'billing', 'view-page'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ApplicationType models.
     * @return mixed
     */
    public function actionSettings()
    {
        $model = User::findOne(\Yii::$app->user->id);

        return $this->render('settings', ['model' => $model]);
    }

    public function actionSaveSettings(){
        
        $nextUrl = '/vendor/settings';
        if(count($_POST) > 0){
            $userId = $_POST['userId'];
            $codes = $_POST['TenantCode'];
            if(Yii::$app->session->get('role') == User::ROLE_ADMIN){
                $nextUrl = '/admin/vendors/settings?id='.$userId;
            }
            if (TenantInfo::isUniqueSubdomain($userId, $codes['SUBDOMAIN'])) {
                foreach($codes as $code => $val){
                    $tenantInfo = TenantInfo::findOrCreate($userId, $code);
                    $tenantInfo->code = $code;
                    $tenantInfo->userId = $userId;
                    $tenantInfo->val = $val;
                    $tenantInfo->save();
                    \Yii::$app->getSession()->setFlash('success', 'Vendor Settings Saved Successfully');
                }
            } else {
                \Yii::$app->getSession()->setFlash('error', 'Subdomain is already taken. Please select a different subdomain.');
            }
            
            VendorOperatingHours::deleteAll(['vendorId' => $userId]);
            $startTimes = $_POST['startTime'];
            $endTimes = $_POST['endTime'];
            foreach($startTimes as $keyDay => $operatingHours){
                foreach($operatingHours as $index => $startTime){
                    if($startTime != ''){
                        $endTime = $endTimes[$keyDay][$index];
                        $vendorOperatingHour = new VendorOperatingHours();
                        $vendorOperatingHour->vendorId = $userId;
                        $vendorOperatingHour->day = intval($keyDay);
                        $vendorOperatingHour->startTime = $startTime;
                        $vendorOperatingHour->endTime = $endTime;
                        $vendorOperatingHour->save();
                    }
                }
            }
        }
       
        
        
        
        return $this->redirect($nextUrl);
    }
    
    public function actionBilling(){
        $transactions = VendorMembership::getVendorMemberships(\Yii::$app->user->id, 20, 1);
        return $this->render('billing/index', ['transactions' => $transactions, 'url' => '/vendor/viewpage', 'userId' => \Yii::$app->user->id]);
        
    }
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $transactions = VendorMembership::getVendorMemberships($userId, 20, $page);
        return $this->renderPartial('billing/_list', ['transactions' => $transactions, 'currentPage' => $page, 'userId' => $userId]);
    }

//     public function actionProfile()
//     {

//         return $this->render('profile');
//     }
}
