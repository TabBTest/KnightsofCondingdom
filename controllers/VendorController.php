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
use yii\helpers\VarDumper;
use app\models\VendorMembership;

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

        return $this->render('settings');
    }

    public function actionSaveSettings(){
        $userId = \Yii::$app->user->id;

        if(count($_POST) > 0){
            $codes = $_POST['TenantCode'];

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
        }
        return $this->redirect('/vendor/settings');
    }
    
    public function actionBilling(){
        return $this->render('billing/index', []);
        
    }
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $transactions = VendorMembership::getVendorMemberships($userId, 20, $page);
        return $this->renderPartial('billing/_list', ['transactions' => $transactions, 'currentPage' => $page]);
    }

//     public function actionProfile()
//     {

//         return $this->render('profile');
//     }
}
