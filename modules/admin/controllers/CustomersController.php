<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Orders;

/**
 * VendorController implements the CRUD actions for User model.
 */
class CustomersController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['orders', 'profile', 'order-view-page', 'index'],
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
    
    public function actionIndex(){
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_CUSTOMER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('/vendors/customers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionOrders(){
        $orders = Orders::getCustomerOrders($_REQUEST['id'], 20, 1);
        var_dump($_REQUEST['id']);
        return $this->render('/../../../views/ordering/history', ['orders' => $orders, 'url' => '/admin/customers/order-view-page', 'userId' => $_REQUEST['id']]);
    }
    
    public function actionOrderViewPage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $orders = Orders::getCustomerOrders($userId, 20, $page);
        return $this->renderPartial('/../../../views/ordering/_history', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }
    
    public function actionProfile(){
    
        $model = User::findOne(['role' => User::ROLE_CUSTOMER, 'id' => $_REQUEST['id']]);
    
        return $this->render('/../../../views/my/profile', ['model' => $model]);
    
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
