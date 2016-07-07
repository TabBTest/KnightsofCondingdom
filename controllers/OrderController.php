<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Orders;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class OrderController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'viewpage',  'viewpagearchive', 'confirm', 'start', 'pickup', 'mark-paid', 'archive'],
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
        $orders = Orders::getVendorOrders(\Yii::$app->user->id, 20, 1, []);
        $archivedOrders = Orders::getVendorArchivedOrders(\Yii::$app->user->id, 20, 1, []);
        return $this->render('index', ['orders' => $orders, 'archivedOrders' => $archivedOrders, 'userId' => \Yii::$app->user->id, 'url' => '/order/viewpage', 'urlArchive' => '/order/viewpagearchive']);
    }
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $orders = Orders::getVendorOrders($userId, 20, $page, $_REQUEST['filter']);
        return $this->renderPartial('_list', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }
    
    public function actionViewpagearchive(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $orders = Orders::getVendorArchivedOrders($userId, 20, $page, $_REQUEST['filter']);
        return $this->renderPartial('_archive_list', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }
    
    
    public function actionConfirm(){
        $orderId = $_REQUEST['id'];
        $order = Orders::findOne($orderId);
        $order->confirmedDateTime=date('Y-m-d H:i:s', strtotime('now'));
        $order->status = Orders::STATUS_PENDING;
        $order->save();
        die;        
    }
    public function actionStart(){
        $orderId = $_REQUEST['id'];
        $order = Orders::findOne($orderId);
        $order->startDateTime=date('Y-m-d H:i:s', strtotime('now'));
        $order->save();
        die;
    }
    public function actionPickup(){
        $orderId = $_REQUEST['id'];
        $order = Orders::findOne($orderId);
        $order->pickedUpDateTime=date('Y-m-d H:i:s', strtotime('now'));
        $order->status = Orders::STATUS_PROCESSED;
        $order->save();
        die;
    }
    public function actionMarkPaid(){
        $orderId = $_REQUEST['id'];
        $order = Orders::findOne($orderId);
        $order->isPaid = 1;
        $order->save();
        die;
    }
    public function actionArchive(){
        $orderId = $_REQUEST['id'];
        $order = Orders::findOne($orderId);
        $order->isArchived = 1;
        $order->save();
        die;
    }
}
