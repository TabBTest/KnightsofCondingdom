<?php

namespace app\controllers;

use Yii;
use app\models\ApplicationType;
use app\models\ApplicationTypeSearch;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\ViewContextInterface;
use app\models\ApplicationTypeFormSetup;
use app\models\Candidates;
use yii\base\Application;
use app\models\TenantInfo;
use app\models\User;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class ProfileController extends CController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'save', 'save-billing', 'change-password', 'save-password'],
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

    public function actionChangePassword(){
        return $this->renderPartial('../partials/change-password', ['id' => ($_REQUEST['id'])]);
    }
    public function actionSavePassword(){
        $resp = [];
        $resp['status'] = 0;
        if($_POST['password'] != ''){
            $model = User::find()->where("md5(id) = '".$_POST['id']."'")->one();
            if($model){
                if($_POST['password'] == $_POST['confirmPassword']){
                    $model->password = $_POST['password'];
                    $model->confirmPassword = $_POST['password'];
                    if($model->save()){
                        $resp['status'] = 1;
                    }
                }
            }
        }
        
        echo json_encode($resp);
        die;
    }
    
    /**
     * Lists all ApplicationType models.
     * @return mixed
     */

    public function actionSave(){
        /*
        $userId = \Yii::$app->user->id;
        $model = User::findOne(\Yii::$app->user->id);
        $nextUrl = '/vendor/settings';
        if($model->role == User::ROLE_CUSTOMER){
            $nextUrl = '/my/profile';
        }
        */
        $nextUrl = '';
        if(count($_POST) > 0){
            $userId = $_POST['userId'];
            
            $model = User::findOne($userId);
            $nextUrl = '/vendor/settings';
            if($model->role == User::ROLE_CUSTOMER){
                $nextUrl = '/my/profile';
            }
            
            if(Yii::$app->session->get('role') != null && Yii::$app->session->get('role') == User::ROLE_ADMIN){
                $nextUrl = '/admin/vendors/settings?id='.$model->id;
                if($model->role == User::ROLE_CUSTOMER){
                    $nextUrl = '/admin/customers/profile?id='.$model->id;
                }
            }
            
            
            $userData = $_POST['User'];
            $hasDuplicate = false;

            if($model->role == User::ROLE_VENDOR){
                $user = User::findOne(['email' => $userData['email']]);
                if($user && $user->id != $userId){
                    $hasDuplicate = true;
                }
            }else{
                $user = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_VENDOR]);

                $customers = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_CUSTOMER, 'vendorId' => $model->vendorId]);
                if($user || ($customers && $customers->id != $userId)){
                    $hasDuplicate = true;
                }
            }


            if($hasDuplicate){
                 \Yii::$app->getSession()->setFlash('error', 'Email should be unique');
            }else{
                //we register it already
                $model->businessName = isset($userData['businessName']) ? $userData['businessName'] : '';
                
                $model->firstName = $userData['firstName'];
                $model->lastName = $userData['lastName'];
                
                $model->streetAddress = $userData['streetAddress'];
                $model->city = $userData['city'];
                $model->state = $userData['state'];
                $model->email = $userData['email'];
                $model->timezone = $userData['timezone'];
                $model->phoneAreaCode = $userData['phoneAreaCode'];
                $model->phone3 = $userData['phone3'];
                $model->phone4 = $userData['phone4'];
                
                
                if(Yii::$app->user->identity->role == User::ROLE_ADMIN){
                    $model->isActive = intval($userData['isActive']);
                }
                
                $message = 'Profile Saved Successfully';

                if($_POST['password'] != ''){
                    if($_POST['password'] == $_POST['confirmPassword']){
                        $model->password = $_POST['password'];
                        $model->confirmPassword = $_POST['password'];
                    }else{
                        \Yii::$app->getSession()->setFlash('warning', 'Password did not matched');
                    }
                }

                $imageForUpload = UploadedFile::getInstance($model, 'imageFile');

                if ($imageForUpload) {
                    $model->imageFile = $imageForUpload;
                    if (!$model->upload()) {
                        \Yii::$app->getSession()->setFlash('warning', 'An error occured while uploading your logo. Please try again.');
                    }
                }

                if($model->save()){

                    \Yii::$app->getSession()->setFlash('success', $message);
                    return $this->redirect($nextUrl);
                }
            }


        }
        return $this->redirect($nextUrl);
    }
    
    public function actionSaveBilling(){
        /*
        $userId = \Yii::$app->user->id;
        $model = User::findOne(\Yii::$app->user->id);
        
        $nextUrl = '/vendor/settings';
        if($model->role == User::ROLE_CUSTOMER){
            $nextUrl = '/my/profile';
        }
        */
        $nextUrl = '';
        if(count($_POST) > 0){
    
            $userId = $_POST['userId'];
            
            $model = User::findOne($userId);
            $nextUrl = '/vendor/settings';
            if($model->role == User::ROLE_CUSTOMER){
                $nextUrl = '/my/profile';
            }
            
            if(Yii::$app->session->get('role') != null && Yii::$app->session->get('role') == User::ROLE_ADMIN){
                $nextUrl = '/admin/vendors/settings?id='.$model->id;
                if($model->role == User::ROLE_CUSTOMER){
                    $nextUrl = '/admin/customers/profile?id='.$model->id;
                }
            }
            
            
            $userData = $_POST['User'];
            $hasDuplicate = false;
    
                //we register it already
                $model->billingName = $userData['billingName'];
                $model->billingStreetAddress = $userData['billingStreetAddress'];
                $model->billingCity = $userData['billingCity'];
                $model->billingState = $userData['billingState'];
                //$model->billingPhoneNumber = $userData['billingPhoneNumber'];
                $model->billingPhoneAreaCode = $userData['billingPhoneAreaCode'];
                $model->billingPhone3 = $userData['billingPhone3'];
                $model->billingPhone4 = $userData['billingPhone4'];

                \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
                
                // Get the credit card details submitted by the form
                $token = $_POST['stripeToken'];
                
                if($model->stripeId != null && $model->stripeId != ''){
                    // Create a Customer
                    $customer = \Stripe\Customer::retrieve($model->stripeId);
                    $customer->source = $token; // obtained with Stripe.js
                    $customer->save();
                }else{
                    //we create 
                    \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
                    $prefix = 'Vendor';
                    if($model->role == User::ROLE_CUSTOMER){
                        $prefix = 'Customer';
                    }
                    // Create a Customer
                    $customer = \Stripe\Customer::create(array(
                        "source" => $token,
                        "description" => $prefix." ID: ".$user->id)
                    );
                    $model->stripeId = $customer->id;
                    
                }
                
                
                if($model->save()){
                    $model->storeCCInfo();
                }
                
                $message = 'Billing Info Saved Successfully';
    
                if($model->save()){
    
                    \Yii::$app->getSession()->setFlash('success', $message);
                    return $this->redirect($nextUrl);
                }
    
    
        }
        return $this->redirect($nextUrl);
    }
}
