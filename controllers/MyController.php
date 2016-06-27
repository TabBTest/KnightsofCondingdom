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
use app\models\User;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class MyController extends CController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['profile', 'index', 'save', 'save-billing'],
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

    public function actionProfile(){
        $model = User::findOne(\Yii::$app->user->id);
        //reuse
        return $this->render('profile', ['model' => $model]);
    }
    /**
     * Lists all ApplicationType models.
     * @return mixed
     */
    /*
    public function actionSave(){
        $userId = \Yii::$app->user->id;
        $model = User::findOne(\Yii::$app->user->id);
        if(count($_POST) > 0){

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
                $model->name = $userData['name'];
                $model->streetAddress = $userData['streetAddress'];
                $model->city = $userData['city'];
                $model->state = $userData['state'];
                $model->email = $userData['email'];
                $model->phoneNumber = $userData['phoneNumber'];
                $message = 'Profile Saved Successfully';

                if($_POST['password'] != ''){
                    if($_POST['password'] == $_POST['confirmPassword']){
                        $model->password = $_POST['password'];
                        $model->confirmPassword = $_POST['password'];
                    }else{
                        \Yii::$app->getSession()->setFlash('warning', 'Password did not matched');
                    }
                }

                if($model->save()){

                    \Yii::$app->getSession()->setFlash('success', $message);
                    return $this->redirect('/vendor/settings');
                }
            }


        }
        return $this->redirect('/vendor/settings');
    }
    
    public function actionSaveBilling(){
        $userId = \Yii::$app->user->id;
        $model = User::findOne(\Yii::$app->user->id);
        if(count($_POST) > 0){
    
            $userData = $_POST['User'];
            $hasDuplicate = false;
    
                //we register it already
                $model->billingName = $userData['billingName'];
                $model->billingStreetAddress = $userData['billingStreetAddress'];
                $model->billingCity = $userData['billingCity'];
                $model->billingState = $userData['billingState'];
                $model->billingPhoneNumber = $userData['billingPhoneNumber'];


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
                    return $this->redirect('/vendor/settings');
                }
    
    
        }
        return $this->redirect('/vendor/settings');
    }
    */
}
