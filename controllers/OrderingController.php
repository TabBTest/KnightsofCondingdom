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
use app\models\Orders;
use app\models\OrderDetails;
use app\models\VendorMenuItem;
use app\helpers\UtilityHelper;
use app\helpers\TenantHelper;
use app\models\TenantInfo;
use app\models\MenuCategories;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class OrderingController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                    'actions' => ['menu', 'summary'],
                    'allow' => true,
                    'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'summary', 'save', 'history', 'viewpage', 'details'],
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
        $user = User::findOne(\Yii::$app->user->id);
        $userVendor = User::findOne($user->vendorId);
        $vendorMenu = User::getVendorDefaultMenu($userVendor);
        
        $vendorCategories = MenuCategories::find()->where('vendorId = '.$user->vendorId.' order by sorting asc')->all();
        
        return $this->render('index', ['menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
    }
    
    public function actionMenu()
    {
        
        $subdomain = TenantHelper::getSubDomain();
        $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
        
        $userVendor = User::findOne($tenantInfo->userId);
        $vendorMenu = User::getVendorDefaultMenu($userVendor);
    
        $vendorCategories = MenuCategories::find()->where('vendorId = '.$tenantInfo->userId.' order by sorting asc')->all();
        
        return $this->render('index', ['menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
    }
    
    
    
    public function actionSummary(){
        
        $orders = [];
        foreach($_POST['Orders'] as $menuItemId => $quantity){
            if($quantity > 0)
                $orders[] = ['menuItemId' => $menuItemId, 'quantity' => $quantity];
        }
        
        return $this->renderPartial('summary', ['orders' => $orders]);
    }
    
    public function actionDetails(){
    
        $id = $_REQUEST['id'];
        $orderDetails = OrderDetails::findAll(['orderId' => $id]);
        return $this->renderPartial('details', ['orders' => $orderDetails]);
    }
    
    public function actionSave(){
    
        $orders = [];
        if(count($_POST) > 0){
            $user = User::findOne(\Yii::$app->user->id);
            $userVendor = User::findOne($user->vendorId);
            
            try{
                

                $finalAmount = 0;
                //we charge the customer cc, if its ok, then we create the order
                $customerOrdersMetaData = [];
                $index = 1;
                foreach($_POST['Orders'] as $menuItemId => $quantity){
                    $vendorMenuItem = VendorMenuItem::findOne($menuItemId);
                    $totalAmount = intval($quantity) * $vendorMenuItem->amount;
                    $finalAmount  += $totalAmount;
                
                    $customerOrdersMetaData['order menu '.$index++] = ['name' => $vendorMenuItem->name, 'quantity' => $quantity, 'amount' => $vendorMenuItem->amount, 'totalAmount' => $totalAmount];
                }
                $finalAmount = number_format($finalAmount, 2, '.', '');
                \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
                $amount = $finalAmount * 100;
                $charge = \Stripe\Charge::create(
                    array(
                        "amount" => $amount,
                        "currency" => "usd",
                        "customer" => $user->stripeId, // obtained with Stripe.js
                        "description" => "Order Charge for Customer ID: ".$user->id,
                       // 'metadata' => $customerOrdersMetaData
                    )  );
                
                //echo $charge;
                $chargeArray = $charge->__toArray(true);
                if($chargeArray['status'] == 'succeeded'){
    
                    $order = new Orders();
                    $order->transactionId = $chargeArray['id'];
                    $order->status = Orders::STATUS_NEW;
                    $order->customerId = \Yii::$app->user->id;
                    $order->vendorId = $user->vendorId;
                    $order->cardLast4 = $user->cardLast4;
                    if($order->save()){
                        
                        $ch = \Stripe\Charge::retrieve($order->transactionId);
                        $ch->metadata = ['Order ID' => $order->id];
                        $ch->save();
                        
                        foreach($_POST['Orders'] as $menuItemId => $quantity){
                    
                            $vendorMenuItem = VendorMenuItem::findOne($menuItemId);
                            $orderDetails = new OrderDetails();
                            $orderDetails->orderId = $order->id;
                            $orderDetails->vendorMenuItemId = $vendorMenuItem->id;
                            $orderDetails->name = $vendorMenuItem->name;
                            $orderDetails->amount = $vendorMenuItem->amount;
                            $orderDetails->quantity = intval($quantity);
                            $orderDetails->totalAmount = intval($quantity) * $vendorMenuItem->amount;
                    
                            $orderDetails->save();
                    
                        }
                        \Yii::$app->getSession()->setFlash('success', 'Orders Submitted Successfully');
                    }
                }else{
                    \Yii::$app->getSession()->setFlash('error', 'Orders Submitted Successfully');
                }
            
            }catch (\Stripe\Error\Card $e){
                $error = $e->getJsonBody()['error']['message'];
                \Yii::$app->getSession()->setFlash('error', $error);
            }
            
            
        }
        return $this->redirect('/ordering');
    }
    
    public function actionHistory(){
    
        return $this->render('history', []);
    }
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $orders = Orders::getCustomerOrders($userId, 20, $page);    
        return $this->renderPartial('_history', ['orders' => $orders, 'currentPage' => $page]);
    }
}
