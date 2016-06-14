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
                        'actions' => ['index', 'viewpage', 'confirm', 'start', 'pickup'],
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
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $orders = Orders::getVendorOrders($userId, 20, $page);
        return $this->renderPartial('_list', ['orders' => $orders, 'currentPage' => $page]);
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
}
