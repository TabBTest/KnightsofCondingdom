<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Orders;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class SalesController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'viewpage'],
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
        $fromDate = date('Y-m-01', strtotime('now'));
        $toDate = date('Y-m-d', strtotime('now'));
        $params = [];
        $params['vendorId'] = \Yii::$app->user->id; 
        $params['fromDate'] = $fromDate;
        $params['toDate'] = $toDate;
        $orders = Orders::getSalesOrders( 20, 1, $params);
        
        $fromDateDisplay = date('m-01-Y', strtotime('now'));
        $toDateDisplay = date('m-d-Y', strtotime('now'));
        
        return $this->render('index', ['fromDateDisplay' => $fromDateDisplay, 'toDateDisplay' => $toDateDisplay, 'orders' => $orders, 'userId' => \Yii::$app->user->id, 'url' => '/sales/viewpage']);
    }
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $params = $_REQUEST['filter'];
        $froms = explode('-',$params['fromDate']);
        $tos = explode('-',$params['toDate']);
        $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        
        $orders = Orders::getSalesOrders(20, $page, $params);
        return $this->renderPartial('_list', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }
    
}
