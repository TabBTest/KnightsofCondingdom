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
use app\models\VendorMenuItemAddOns;
use app\models\AppConfig;
use app\models\VendorCoupons;
use app\models\VendorCouponOrders;
use app\models\VendorAppConfigOverride;

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
                    'actions' => ['menu', 'summary', 'add-item', 'item-order-summary', 'add-order'],
                    'allow' => true,
                    'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'summary', 'save', 'history', 'viewpage', 'details', 'menu', 'summary', 'add-item', 'item-order-summary', 'add-order'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                   $this->redirect('/site/login');
                }
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
        
        $vendorCategories = MenuCategories::find()->where('isArchived = 0 and vendorId = '.$user->vendorId.' order by sorting asc')->all();
        
        return $this->render('index', ['menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
    }
    
    public function actionMenu()
    {
        
        $subdomain = TenantHelper::getSubDomain();
        $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
        
        $userVendor = User::findOne($tenantInfo->userId);
         
        return $this->render('index', ['vendor' => $userVendor]);
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
        $order = Orders::findOne($id);
        $orderDetails = OrderDetails::findAll(['orderId' => $id]);
        return $this->renderPartial('details', ['orders' => $orderDetails, 'orderInfo' => $order]);
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
                foreach($_POST['Orders'] as  $orderKey => $menuItemId){
                    $quantity = $_POST['OrdersQuantity'][$orderKey];                
                    $vendorMenuItem = VendorMenuItem::findOne($menuItemId);
                    $totalAmount = intval($quantity) * $vendorMenuItem->amount;
                    $finalAmount  += $totalAmount;
                
                    $customerOrdersMetaData['order menu '.$index++] = ['name' => $vendorMenuItem->name, 'quantity' => $quantity, 'amount' => $vendorMenuItem->amount, 'totalAmount' => $totalAmount];
                    
                    if(isset($_POST['AddOns'][$orderKey])){
                        foreach($_POST['AddOns'][$orderKey] as $addOnId => $elem){
                            $menuItemAddOn = VendorMenuItemAddOns::findOne($addOnId);
                            $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
                            $finalAmount += $totalAddonAmount;
                            
                            $customerOrdersMetaData['add on '.$index++] = ['name' => $menuItemAddOn->name, 'quantity' => $quantity, 'amount' => $menuItemAddOn->amount, 'totalAmount' => $totalAddonAmount];
                        }
                    }
                }
                
                $subdomain = TenantHelper::getSubDomain();
                $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
                
                
                $discount = false;
                $couponDiscountDisplay = false;
                $vendorCoupon = false;
                if(isset($_POST['couponCode']) && $_POST['couponCode'] != ''){
                    $couponCode = $_POST['couponCode'];
                    $vendorCoupon = VendorCoupons::isValidCoupon($couponCode, $tenantInfo->userId);
                
                    if($vendorCoupon->discountType == VendorCoupons::TYPE_AMOUNT){
                        $couponDiscountDisplay = 'Coupon Discount ($'.UtilityHelper::formatAmountForDisplay($vendorCoupon->discount).')';
                        $discount = floatval($vendorCoupon->discount);
                
                    }else if($vendorCoupon->discountType == VendorCoupons::TYPE_PERCENTAGE){
                        $couponDiscountDisplay = 'Coupon Discount ('.UtilityHelper::formatAmountForDisplay($vendorCoupon->discount).'%)';
                        $discount = $finalAmount * (floatval($vendorCoupon->discount) / 100);
                    }
                
                    if($discount !== false){
                        if($finalAmount >= $discount){
                            $finalAmount = $finalAmount - $discount;
                        }else{
                            $finalAmount = 0;
                        }
                    }
                }
                
                //we need to add here the sales tax
                $salesTax = 0;
                $salesTaxPercent = 0;
                $salesTaxAmount = 0;
                if($tenantInfo){
                    $salesTaxInfo = TenantInfo::findOne(['userId' => $tenantInfo->userId, 'code' => TenantInfo::CODE_SALES_TAX]);
                    if($salesTaxInfo && $salesTaxInfo->val > 0){
                        $salesTax = 1 + (floatval($salesTaxInfo->val) / 100);
                        $salesTaxPercent = $salesTaxInfo->val;
                        
                    }
                }
                
                $totalFinalAmount = $finalAmount * $salesTax;
                $salesTaxAmount = $totalFinalAmount - $finalAmount;
                $customerOrdersMetaData['sales tax'] = ['name' => 'sales tax', 'amount' => $salesTaxAmount];
                
                
                $adminFee = floatval(VendorAppConfigOverride::getVendorOverride($tenantInfo->userId, AppConfig::ADMIN_FEE));
                $totalFinalAmount += $adminFee;
                
                $deliveryFee = 0;
                if(isset($_POST['isDelivery']) && $_POST['isDelivery'] == 1){
                    $deliveryFee = TenantHelper::getDeliveryAmount();
                }
                
                $totalFinalAmount += $deliveryFee;
                
                
                //still need to include delivery
                //still need to check if cash payment
                $paymentType = $_POST['paymentType'];
                
                $order = new Orders();
                
                $order->status = Orders::STATUS_NEW;
                $order->customerId = \Yii::$app->user->id;
                $order->vendorId = $user->vendorId;
                
                if(isset($_POST['isDelivery']) && $_POST['isDelivery'] == 1){
                    $order->isDelivery = 1;
                }
               
                $notes = '';
                if(isset($_POST['notes'])){
                    $notes = $_POST['notes'];
                }
                $order->notes = $notes;
                
                if($paymentType == Orders::PAYMENT_TYPE_CARD){
                    $finalAmount = number_format($totalFinalAmount, 2, '.', '');
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
                        $order->transactionId = $chargeArray['id'];
                        $order->cardLast4 = $user->cardLast4;
                        $order->paymentType = Orders::PAYMENT_TYPE_CARD;
                        $order->isPaid = 1;
                        $paymentGatewayFee = ($finalAmount * 0.029) + 0.3;
                        $order->paymentGatewayFee = $paymentGatewayFee;
                        
                    }
                }else if($paymentType == Orders::PAYMENT_TYPE_CASH){
                    $order->isPaid = 0;
                    $order->paymentType = Orders::PAYMENT_TYPE_CASH;
                }
               
                    
                    if($order->save()){
                        if($paymentType == Orders::PAYMENT_TYPE_CARD){
                            $ch = \Stripe\Charge::retrieve($order->transactionId);
                            $ch->metadata = ['Order ID' => $order->id];
                            $ch->save();
                        }
                        
                        foreach($_POST['Orders'] as  $orderKey => $menuItemId){
                            $quantity = $_POST['OrdersQuantity'][$orderKey];   
                    
                            $vendorMenuItem = VendorMenuItem::findOne($menuItemId);
                            $orderDetails = new OrderDetails();
                            $orderDetails->orderId = $order->id;
                            $orderDetails->vendorMenuItemId = $vendorMenuItem->id;
                            $orderDetails->name = $vendorMenuItem->name;
                            $orderDetails->amount = $vendorMenuItem->amount;
                            $orderDetails->quantity = intval($quantity);
                            $orderDetails->totalAmount = intval($quantity) * $vendorMenuItem->amount;
                            $orderDetails->type = OrderDetails::TYPE_MENU_ITEM;
                            $notes = '';
                            if(isset($_POST['OrdersNotes'][$orderKey])){
                                $notes = $_POST['OrdersNotes'][$orderKey];
                            }
                            $orderDetails->notes = $notes;
                            $orderDetails->save();
                            
                            if(isset($_POST['AddOnsExclusive'][$orderKey])){
                                    $menuItemAddOn = VendorMenuItemAddOns::findOne($_POST['AddOnsExclusive'][$orderKey]);
                            
                                    $orderDetails = new OrderDetails();
                                    $orderDetails->orderId = $order->id;
                                    $orderDetails->vendorMenuItemId = 0;
                                    $orderDetails->name = $menuItemAddOn->name;
                                    $orderDetails->amount = $menuItemAddOn->amount;
                                    $orderDetails->quantity = intval($quantity);
                                    $orderDetails->totalAmount = intval($quantity) * $menuItemAddOn->amount;
                                    $orderDetails->type = OrderDetails::TYPE_MENU_ITEM_ADD_ON;
                                    $orderDetails->save();
                               
                            }
                            
                            if(isset($_POST['AddOns'][$orderKey])){
                                foreach($_POST['AddOns'][$orderKey] as $addOnId => $elem){
                                    $menuItemAddOn = VendorMenuItemAddOns::findOne($addOnId);
                                    
                                    $orderDetails = new OrderDetails();
                                    $orderDetails->orderId = $order->id;
                                    $orderDetails->vendorMenuItemId = 0;
                                    $orderDetails->name = $menuItemAddOn->name;
                                    $orderDetails->amount = $menuItemAddOn->amount;
                                    $orderDetails->quantity = intval($quantity);
                                    $orderDetails->totalAmount = intval($quantity) * $menuItemAddOn->amount;
                                    $orderDetails->type = OrderDetails::TYPE_MENU_ITEM_ADD_ON;
                                    $orderDetails->save();                                    
                                }
                            }
                    
                        }
                        
                        if($discount){
                            $orderDetails = new OrderDetails();
                            $orderDetails->orderId = $order->id;
                            $orderDetails->vendorMenuItemId = 0;
                            $orderDetails->name = $couponDiscountDisplay;
                            $orderDetails->amount = $discount;
                            $orderDetails->quantity = 1;
                            $orderDetails->totalAmount = $discount;
                            $orderDetails->type = OrderDetails::TYPE_COUPON;
                            $orderDetails->save();
                        
                            $vendorCouponOrder = new VendorCouponOrders();
                            $vendorCouponOrder->orderId = $order->id;
                            $vendorCouponOrder->vendorCouponId = $vendorCoupon->id;
                            $vendorCouponOrder->save();
                        }
                        
                        //add for the sales tax
                        $orderDetails = new OrderDetails();
                        $orderDetails->orderId = $order->id;
                        $orderDetails->vendorMenuItemId = 0;
                        $orderDetails->name = 'Sales Tax ('.$salesTaxPercent.'%)';
                        $orderDetails->amount = $salesTaxAmount;
                        $orderDetails->quantity = 1;
                        $orderDetails->totalAmount = $salesTaxAmount;
                        $orderDetails->type = OrderDetails::TYPE_SALES_TAX;
                        $orderDetails->save();
                        
                        if($deliveryFee > 0){
                            $orderDetails = new OrderDetails();
                            $orderDetails->orderId = $order->id;
                            $orderDetails->vendorMenuItemId = 0;
                            $orderDetails->name = 'Delivery Fee';
                            $orderDetails->amount = $deliveryFee;
                            $orderDetails->quantity = 1;
                            $orderDetails->totalAmount = $deliveryFee;
                            $orderDetails->type = OrderDetails::TYPE_DELIVERY_CHARGE;
                            $orderDetails->save();
                        }
                        if($adminFee > 0){
                            $orderDetails = new OrderDetails();
                            $orderDetails->orderId = $order->id;
                            $orderDetails->vendorMenuItemId = 0;
                            $orderDetails->name = 'Web Fee';
                            $orderDetails->amount = $adminFee;
                            $orderDetails->quantity = 1;
                            $orderDetails->totalAmount = $adminFee;
                            $orderDetails->type = OrderDetails::TYPE_ADMIN_FEE;
                            $orderDetails->save();
                        }
                        
                        
                        \Yii::$app->getSession()->setFlash('success', 'Order submitted successfully.');

                        $redis = Yii::$app->redis;
                        $redis->executeCommand('PUBLISH', ['orders',
                                json_encode([
                                    'orderId' => $order->id,
                                    'vendorId' => $order->vendorId
                                ])
                        ]);
                    }
//                 }
                    else{
                     \Yii::$app->getSession()->setFlash('error', 'Error in processing order, please try again.');
                     }
            
            }catch (\Stripe\Error\Card $e){
                $error = $e->getJsonBody()['error']['message'];
                \Yii::$app->getSession()->setFlash('error', $error);
            }
            
            
        }
        return $this->redirect('/ordering/menu');
    }
    public function actionAddItem(){
        $menuItemId = $_REQUEST['menuItemId'];
        $vendorMenuItem = VendorMenuItem::findOne($menuItemId);
        

        $categoryExclusiveAddOns = VendorMenuItemAddOns::find()->where('menuCategoryId = ' . $vendorMenuItem->menuCategoryId . ' and isArchived = 0 and isExclusive = 1 order by sorting asc')->all();
        $categoryNonExclusiveAddOns =VendorMenuItemAddOns::find()->where('menuCategoryId = ' . $vendorMenuItem->menuCategoryId . ' and isArchived = 0 and isExclusive = 0 order by sorting asc')->all();
        
        $menuItemExclusiveAddOns = VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $vendorMenuItem->id . ' and isArchived = 0 and isExclusive = 1 order by sorting asc')->all();
        $menuItemNonExclusiveAddOns =VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $vendorMenuItem->id . ' and isArchived = 0 and isExclusive = 0 order by sorting asc')->all();
        
        
        
        return $this->renderPartial('add-item', ['item' => $vendorMenuItem, 'categoryExclusives' => $categoryExclusiveAddOns,
            'categoryExclusives' => $categoryExclusiveAddOns,
            'categoryNonExclusives' => $categoryNonExclusiveAddOns,
            'itemExclusives' => $menuItemExclusiveAddOns,
            'itemNonExclusives' => $menuItemNonExclusiveAddOns,
        ]);
    }
    public function actionItemOrderSummary(){
        return $this->renderPartial('item-order-summary', ['params' => $_POST]);
    }
    public function actionAddOrder(){
        
        $subdomain = TenantHelper::getSubDomain();
        $tenantInfo = TenantInfo::findOne(['val' => $subdomain, 'code' => TenantInfo::CODE_SUBDOMAIN]);
        $salesTax = 0;
        if($tenantInfo){
            $salesTaxInfo = TenantInfo::findOne(['userId' => $tenantInfo->userId, 'code' => TenantInfo::CODE_SALES_TAX]);
            if($salesTaxInfo && $salesTaxInfo->val > 0){
                $salesTax = 1 + (floatval($salesTaxInfo->val) / 100);
            }            
        }
        
        return $this->renderPartial('main-order-summary', ['params' => $_POST, 'salesTax' => $salesTax]);
    }
    public function actionHistory(){
        $userId = \Yii::$app->user->id;
        if(isset($_REQUEST['id'])){
            $userId = base64_decode($_REQUEST['id']);
        }
        
        $orders = Orders::getCustomerOrders($userId, 20, 1);
        return $this->render('history', ['orders'=>$orders, 'userId' => $userId, 'url' => '/ordering/viewpage']);
    }
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $orders = Orders::getCustomerOrders($userId, 20, $page);    
        return $this->renderPartial('_history', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }
}
