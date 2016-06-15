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
                        'actions' => ['settings', 'save-settings', 'profile'],
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
            foreach($codes as $code => $val){
                $tenantInfo = TenantInfo::findOne(['userId' => $userId, 'code' => $code]);
                if($tenantInfo == null){
                    $tenantInfo = new TenantInfo();
                    
                }
                
                $tenantInfo->code = $code;
                $tenantInfo->userId = $userId;
                $tenantInfo->val = $val;
                $tenantInfo->save();
            }
            \Yii::$app->getSession()->setFlash('success', 'Vendor Settings Saved Successfully');
        }
        return $this->redirect('/vendor');
    }
    
    public function actionProfile()
    {
    
        return $this->render('profile');
    }
}
