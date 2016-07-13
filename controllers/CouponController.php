<?php

namespace app\controllers;

use Yii;
use app\models\VendorCoupons;
use app\models\VendorCouponsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Orders;
use app\models\VendorCouponOrders;

/**
 * CouponController implements the CRUD actions for VendorCoupons model.
 */
class CouponController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                    'actions' => ['check'],
                    'allow' => true,
                    'roles' => ['?'],
                    ],
                    
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'archive', 'check', 'orders', 'viewpage'],
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
    public function actionOrders()
    {
        $orders = VendorCouponOrders::getOrders($_REQUEST['id'], 20, 1, []);
        return $this->render('orders', ['orders' => $orders,  'vendorCouponId' => $_REQUEST['id'], 'url' => '/coupon/viewpage']);
    }
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $vendorCouponId = $_REQUEST['vendorCouponId'];
        $orders = VendorCouponOrders::getOrders($vendorCouponId, 20, $page, $_REQUEST['filter']);
        return $this->renderPartial('_list', ['orders' => $orders, 'currentPage' => $page, 'vendorCouponId' => $vendorCouponId]);
    }
    
    public function actionCheck(){
        $code = $_REQUEST['code'];
        $vendorId = $_REQUEST['vendorId'];
        $resp = [];
        $vendorCoupon = VendorCoupons::isValidCoupon($code, $vendorId);
        $resp['status'] = 0;
        if($vendorCoupon){
            $resp['status'] = 1;
        }
        echo json_encode($resp);
        die;
    }
    /**
     * Lists all VendorCoupons models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorCouponsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VendorCoupons model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VendorCoupons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorCoupons();
        $model->vendorId = \Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Coupon Saved Successfully');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VendorCoupons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Coupon Saved Successfully');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    
    public function actionArchive()
    {
        //$this->findModel($id)->delete();
        $id = $_POST['id'];
        $archiveVal = $_POST['archive'];
        $model = $this->findModel($id);//->delete();
        $model->isArchived = intval($archiveVal);
        $model->save();
        $message = 'Coupon Un-archived Successfully';
        if($archiveVal == 0){
            $message = 'Coupon Archived Successfully';
        }
        \Yii::$app->getSession()->setFlash('success', $message);
        return $this->redirect(['index']);
    }
    
    /**
     * Finds the VendorCoupons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorCoupons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorCoupons::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
