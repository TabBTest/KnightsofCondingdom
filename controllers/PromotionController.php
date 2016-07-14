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
use app\models\Promotion;
use app\models\PromotionUserStatus;
use app\helpers\UtilityHelper;

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
                        'actions' => ['index', 'send', 'view-page-email', 'view-page-sms', 'view', 'get-customers', 'view-customers'],
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
    public function actionGetCustomers(){
        $userId =  \Yii::$app->user->id;
        $page = 1;
        $customers = User::getVendorCustomers($userId, 20, $page,  ['isActive' => 1,'isOptIn' => 1]);
        return $this->renderPartial('user', ['customers' => $customers, 'currentPage' => $page, 'type' => $_REQUEST['type']]);
    }
    public function actionViewCustomers(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $customers = User::getVendorCustomers($userId, 20, $page, array_merge($_REQUEST['filter'], ['isActive' => 1,'isOptIn' => 1]));
        return $this->renderPartial('_user_list', ['customers' => $customers, 'currentPage' => $page]);
    }
    /**
     * Lists all ApplicationType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userId =  \Yii::$app->user->id;
        $emailList = VendorPromotion::getPromoEmails($userId, 20, 1);
        $smsList = VendorPromotion::getPromoSms($userId, 20, 1);
        return $this->render('index', ['vendorId' =>$userId, 'emailList' => $emailList, 'smsList' => $smsList, 'url' => '/promotions/view-page-email', 'urlSms' => '/promotions/view-page-sms']);
    }
    public function actionView(){
        $id = $_REQUEST['id'];
        $vendorPromo = VendorPromotion::findOne(['id' => $id]);
        echo $vendorPromo->html;
        die;
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
            $promo->promoType = $_POST['type'];
            
            if($to == 0){
                $promo->sendToType = VendorPromotion::SEND_TO_SELF;
                $promo->save();
                
                //self
                $user = User::findOne(\Yii::$app->user->id);
                
                $promoUser = new PromotionUserStatus();
                $promoUser->vendorPromotionId = $promo->id;
                $promoUser->userId = $user->id;
                $promoUser->status = PromotionUserStatus::STATUS_IN_QUEUE;
                $promoUser->save();
                
                                
                //NotificationHelper::sendPromotion($promo);
                
                UtilityHelper::runCommand("promotion/send", $promo->id);
            }else{
                $promo->sendToType = VendorPromotion::SEND_TO_CUSTOMERS;
                $promo->save();
                
                $userList = $_POST['userList'];
                if($userList == 'ALL'){
                    //to all customers
                    $users = User::findAll(['isActive' => 1,'isOptIn' => 1,  'vendorId' =>  \Yii::$app->user->id]);
                    
                    foreach($users as $user){
                        $promoUser = new PromotionUserStatus();
                        $promoUser->vendorPromotionId = $promo->id;
                        $promoUser->userId = $user->id;
                        $promoUser->status = PromotionUserStatus::STATUS_IN_QUEUE;
                        $promoUser->save();
                    }      
                }else{
                    $userIds = explode(',', $userList);
                    foreach($userIds as $userId){
                        $promoUser = new PromotionUserStatus();
                        $promoUser->vendorPromotionId = $promo->id;
                        $promoUser->userId = $userId;
                        $promoUser->status = PromotionUserStatus::STATUS_IN_QUEUE;
                        $promoUser->save();
                    }
                }
                //NotificationHelper::sendPromotion($promo);
                UtilityHelper::runCommand("promotion/send", $promo->id);
            }
        }
        $resp = [];
        $resp['status'] = 1;
        echo json_encode($resp);
        die;
    }
}
