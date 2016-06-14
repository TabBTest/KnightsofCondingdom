<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\helpers\UtilityHelper;
use app\helpers\NotificationHelper;
use app\helpers\TenantHelper;
use app\models\TenantInfo;

class SiteController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
//         var_dump(TenantHelper::get_domain($_SERVER['SERVER_NAME']));
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = User::findOne(Yii::$app->user->id);
            var_dump($user->isPasswordReset);
            if($user->isPasswordReset == 1){
                return $this->redirect('/site/change-password');
            }else
                return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword(){
        if(count($_POST) > 0){
            if($_POST['confirmPassword'] == $_POST['password'] && $_POST['password'] != ''){
                $user = User::findOne(Yii::$app->user->id);
                $user->password = UtilityHelper::cryptPass($_POST['password']);
                $user->isPasswordReset = 0;
                $user->save();
                return $this->redirect('/site/');
            }else{
                \Yii::$app->getSession()->setFlash('error', 'Password does not match');
            }
        }
        return $this->render('change-password');
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    
    public function actionRegVendor(){
        $model = new User();
        
       
        if(count($_POST) > 0){
            
            $userData = $_POST['User'];
            $user = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_VENDOR]);
            if($user){
                 \Yii::$app->getSession()->setFlash('error', 'Email should be unique');
            }else{
                //we register it already
                $randomPassword = UtilityHelper::generateRandomPassword();
                $user = new User();
                $user->name = $userData['name'];
                $user->address = $userData['address'];
                $user->email = $userData['email'];
                $user->phoneNumber = $userData['phoneNumber'];
                $user->billingName = $userData['billingName'];
                $user->billingAddress = $userData['billingAddress'];
                $user->password = $randomPassword;
                $user->confirmPassword = $randomPassword;
                $user->role = User::ROLE_VENDOR;
                $user->isPasswordReset = 1;
                if($user->save()){
                    TenantInfo::addCustomSubdomain($user);
                    
                    \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
                    
                    // Get the credit card details submitted by the form
                    $token = $_POST['stripeToken'];
                    
                    // Create a Customer
                    $customer = \Stripe\Customer::create(array(
                      "source" => $token,
                      "description" => "Vendor ID: ".$user->id)
                    );
                    $user->stripeId = $customer->id;
                    $user->save();
                    
                    NotificationHelper::notifyVendorOfAccount($user, $randomPassword);
                    
                    return $this->redirect('/site/login');
                }
                var_dump($user->errors);
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
    
    public function actionRegCustomer(){
        $model = new User();
    
         
        if(count($_POST) > 0){
    
            $userData = $_POST['User'];
            $user = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_VENDOR]);
            $tenantInfo = TenantInfo::isValidSubDomain(TenantHelper::getSubDomain());
            
            $customers = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_CUSTOMER, 'vendorId' => $tenantInfo->userId]);
            if($user || $customers){
                \Yii::$app->getSession()->setFlash('error', 'Email should be unique');
            }else{
                //we register it already
                
                $randomPassword = UtilityHelper::generateRandomPassword();
                $user = new User();
                $user->name = $userData['name'];
                $user->address = $userData['address'];
                $user->email = $userData['email'];
                $user->phoneNumber = $userData['phoneNumber'];
                $user->billingName = $userData['billingName'];
                $user->billingAddress = $userData['billingAddress'];
                $user->password = $randomPassword;
                $user->confirmPassword = $randomPassword;
                $user->role = User::ROLE_CUSTOMER;
                $user->vendorId = $tenantInfo->userId;
                $user->isPasswordReset = 1;
                if($user->save()){
                    
                    \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
    
                    // Get the credit card details submitted by the form
                    $token = $_POST['stripeToken'];
    
                    // Create a Customer
                    $customer = \Stripe\Customer::create(array(
                        "source" => $token,
                        "description" => "Customer ID: ".$user->id)
                    );
                    $user->stripeId = $customer->id;
                    $user->save();
    
                    NotificationHelper::notifyUserOfAccount($user, $randomPassword);
    
                    return $this->redirect('/site/login');
                }
                var_dump($user->errors);
            }
        }
        return $this->render('register-customer', [
            'model' => $model,
        ]);
    }
    
    
    public function actionForgetPassword(){
        if(count($_POST) > 0){
            $email = $_POST['email'];
            $user = User::findOne(['email' => $email, 'role' => User::ROLE_VENDOR]);
            if($user){
                $randomPassword = UtilityHelper::generateRandomPassword();
                $user->password = UtilityHelper::cryptPass($randomPassword);
                $user->isPasswordReset = 1;
                $user->save();
                NotificationHelper::notifyVendorOfAccountReset($user, $randomPassword);
                \Yii::$app->getSession()->setFlash('success', 'Account Reset Successfully');
            }else{
                \Yii::$app->getSession()->setFlash('error', 'Email is not existing');
            }
        }
        return $this->render('forget-password', [
            
        ]);
    }
    
    public function actionProducts(){
        return $this->render('products', [
            
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
