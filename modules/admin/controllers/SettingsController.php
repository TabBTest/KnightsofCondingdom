<?php

namespace app\modules\admin\controllers;

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
use app\models\AppConfig;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class SettingsController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index',],
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
    public function actionIndex()
    {
        $message = false;
        if(count($_POST) != 0){
            //we save it
            $appConfigs = $_POST['AppConfig'];
//            var_dump($appConfigs);
            foreach($appConfigs as $code => $val){
                $appConfig = AppConfig::findOne(['code'=>$code]);
                if($appConfig != null){
                    $appConfig->val = $val;
                    $appConfig->save(); 
                }
            }
            $message = 'Application Settings Saved Successfully';
        }
        return $this->render('index', [
            'message' => $message,            
        ]);
    }


}
