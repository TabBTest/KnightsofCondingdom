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
use app\models\User;
use app\models\VendorPromotion;
use app\helpers\NotificationHelper;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class PromotionController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'send'],
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
        
        return $this->render('index');
    }
    
    public function actionSend(){
        //var_dump($_REQUEST);
        if(count($_POST) > 0){
            $to = $_REQUEST['to'];
            $html = $_POST['promoHtml'];
            
            $promo = new VendorPromotion();
            $promo->vendorId = \Yii::$app->user->id;
            $promo->html = $html;
            $promo->subject = $_POST['subject'];
            $promo->save();
            
            if($to == 0){
                //self
                $user = User::findOne(\Yii::$app->user->id);
                NotificationHelper::sendPromotion($promo, [$user]);
            }else{
                //to all customers
                $users = User::findAll(['isActive' => 1, 'vendorId' =>  \Yii::$app->user->id]);
                NotificationHelper::sendPromotion($promo, $users);
            }
        }
        $resp = [];
        $resp['status'] = 1;
        echo json_encode($resp);
        die;
    }
}
