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
        
        return $this->render('index', ['menu' => $vendorMenu]);
    }
    
    public function actionSummary(){
        
        $orders = [];
        foreach($_POST['Orders'] as $menuItemId => $quantity){
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
            
            $order = new Orders();
            $order->status = Orders::STATUS_NEW;
            $order->customerId = \Yii::$app->user->id;
            $order->vendorId = $user->vendorId;
            if($order->save()){
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
