<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\VendorMembership;
use app\models\VendorMenu;
use app\models\MenuCategories;
use app\models\VendorAppConfigOverride;
use app\models\AppConfig;
use app\models\Orders;

/**
 * VendorController implements the CRUD actions for User model.
 */
class VendorsController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                       
                        'actions' => ['payable-summary','receivable-summary', 'receivable-summary-details','receivable-summary-details-viewpage', 'receivable','view-vendors-receivable', 'payable-summary-details','payable-summary-details-viewpage', 'payable','view-vendors-payable', 'receivable','overrides','view-vendors', 'index','config', 'customers', 'settings', 'payments', 'viewpage', 'menu'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_VENDOR;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionOverrides(){
        $vendors = User::getVendors(20, 1, ['isActive' => 1]);
        return $this->render('overrides', ['vendors' => $vendors]);
        
    }
    public function actionViewVendors(){
        $page = $_REQUEST['page'];
        $vendors = User::getVendors(20, $page, array_merge($_REQUEST['filter'], ['isActive' => 1]));
        return $this->renderPartial('_user_list', ['vendors' => $vendors, 'currentPage' => $page]);
    }
    
    public function actionReceivable(){
    
        $fromDateDisplay = date('m-01-Y', strtotime('now'));
        $toDateDisplay = date('m-d-Y', strtotime('now'));
    
        $vendors = User::getVendors(20, 1, ['isActive' => 1]);
        return $this->render('receivable', ['fromDateDisplay' => $fromDateDisplay, 'toDateDisplay' => $toDateDisplay, 'vendors' => $vendors]);
    
    }
    public function actionViewVendorsReceivable(){
        $page = $_REQUEST['page'];
        $vendors = User::getVendors(20, $page, array_merge($_REQUEST['filter'], ['isActive' => 1]));
    
        $params = $_REQUEST['filter'];
    
        return $this->renderPartial('_user_receivable_list', ['fromDate' => $params['fromDate'], 'toDate' => $params['toDate'], 'vendors' => $vendors, 'currentPage' => $page]);
    }
    
    public function actionPayable(){
        
        $fromDateDisplay = date('m-01-Y', strtotime('now'));
        $toDateDisplay = date('m-d-Y', strtotime('now'));
        
        $vendors = User::getVendors(20, 1, ['isActive' => 1]);
        return $this->render('payable', ['fromDateDisplay' => $fromDateDisplay, 'toDateDisplay' => $toDateDisplay, 'vendors' => $vendors]);
    
    }
    public function actionViewVendorsPayable(){
        $page = $_REQUEST['page'];
        $vendors = User::getVendors(20, $page, array_merge($_REQUEST['filter'], ['isActive' => 1]));
        
        $params = $_REQUEST['filter'];

        return $this->renderPartial('_user_payable_list', ['fromDate' => $params['fromDate'], 'toDate' => $params['toDate'], 'vendors' => $vendors, 'currentPage' => $page]);
    }
    public function actionPayableSummaryDetails(){
        
        $fromDateDisplay = $_REQUEST['fromDate'];
        $toDateDisplay = $_REQUEST['toDate'];
        
        $params = [];
        
        
        
        if($fromDateDisplay != ''){
            $froms = explode('-',$fromDateDisplay);
            $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($toDateDisplay != ''){
            $tos = explode('-',$toDateDisplay);
            $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
        
        $params['vendorId'] = $_REQUEST['id'];
        $orders = Orders::getSalesOrders( 20, 1, $params);
        
        $salesSummary = $this->renderPartial('_summary', ['orders' => Orders::getSalesOrders( 'ALL', 1, $params)]);
        
        
        return $this->render('payable-summary-details', ['salesSummary' => $salesSummary, 'fromDateDisplay' => $fromDateDisplay, 'toDateDisplay' => $toDateDisplay, 'orders' => $orders, 'userId' => $_REQUEST['id'], 'url' => '/admin/vendors/payable-summary-details-viewpage',  'urlSummary' => '/admin/vendors/payable-summary']);
    }
    
    public function actionPayableSummary(){
        $userId = $_REQUEST['userId'];
        $params = $_REQUEST['filter'];
                
        if($params['fromDate'] != ''){
            $froms = explode('-',$params['fromDate']);
            $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($params['toDate'] != ''){
            $tos = explode('-',$params['toDate']);
            $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
        
        return $this->renderPartial('_summary', ['orders' => Orders::getSalesOrders( 'ALL', 1, $params)]);
        
    }
    
    public function actionPayableSummaryDetailsViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $params = $_REQUEST['filter'];
                
        if($params['fromDate'] != ''){
            $froms = explode('-',$params['fromDate']);
            $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($params['toDate'] != ''){
            $tos = explode('-',$params['toDate']);
            $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
    
        $orders = Orders::getSalesOrders(20, $page, $params);
        return $this->renderPartial('_payable-summary-details-list', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }

    public function actionReceivableSummaryDetails(){
    
        $fromDateDisplay = $_REQUEST['fromDate'];
        $toDateDisplay = $_REQUEST['toDate'];
    
        $params = [];
    
    
    
        if($fromDateDisplay != ''){
            $froms = explode('-',$fromDateDisplay);
            $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($toDateDisplay != ''){
            $tos = explode('-',$toDateDisplay);
            $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
    
        $params['vendorId'] = $_REQUEST['id'];
        $orders = Orders::getSalesOrders( 20, 1, $params);
    
        $salesSummary = $this->renderPartial('_summary', ['orders' => Orders::getSalesOrders( 'ALL', 1, $params)]);
        
    
        return $this->render('receivable-summary-details', ['salesSummary'=>$salesSummary, 'fromDateDisplay' => $fromDateDisplay, 'toDateDisplay' => $toDateDisplay, 'orders' => $orders, 'userId' => $_REQUEST['id'], 'url' => '/admin/vendors/receivable-summary-details-viewpage', 'urlSummary' => '/admin/vendors/receivable-summary']);
    }
    
    public function actionReceivableSummary(){
        $userId = $_REQUEST['userId'];
        $params = $_REQUEST['filter'];
    
        if($params['fromDate'] != ''){
            $froms = explode('-',$params['fromDate']);
            $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($params['toDate'] != ''){
            $tos = explode('-',$params['toDate']);
            $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
        
        //reight now we jsut reuse
    
        return $this->renderPartial('_summary', ['orders' => Orders::getSalesOrders( 'ALL', 1, $params)]);
    
    }
    
    public function actionReceivableSummaryDetailsViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $params = $_REQUEST['filter'];
    
        if($params['fromDate'] != ''){
            $froms = explode('-',$params['fromDate']);
            $params['fromDate'] = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($params['toDate'] != ''){
            $tos = explode('-',$params['toDate']);
            $params['toDate'] = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
    
        $orders = Orders::getSalesOrders(20, $page, $params);
        return $this->renderPartial('_receivable-summary-details-list', ['orders' => $orders, 'currentPage' => $page, 'userId' => $userId]);
    }
    

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      //  $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionCustomers(){
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_CUSTOMER;
        $searchModel->vendorId = $_REQUEST['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('customers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionSettings(){
        
        $model = User::findOne(['role' => User::ROLE_VENDOR, 'id' => $_REQUEST['id']]);
        
        return $this->render('/../../../views/vendor/settings', ['model' => $model]);
        
    }
    
    public function actionConfig(){
    
        $model = User::findOne(['role' => User::ROLE_VENDOR, 'id' => $_REQUEST['id']]);
    
        if(count($_POST) != 0){
            $vendorId = $_POST['vendorId'];
            //we save it
            $appConfigs = $_POST['AppConfig'];
            //            var_dump($appConfigs);
            foreach($appConfigs as $code => $val){
                $origAppConfig = AppConfig::findOne(['code' => $code]);
                $appConfig = VendorAppConfigOverride::findOne(['vendorId' => $vendorId, 'code'=>$code]);
                if($appConfig == null){
                    $appConfig = new VendorAppConfigOverride();
                    $appConfig->vendorId = $vendorId;
                    $appConfig->code = $code;
                }
                
                    $appConfig->val = $val;
                    $appConfig->save();
                if($origAppConfig->val == $appConfig->val){
                    $appConfig->delete();
                }
            }
            \Yii::$app->getSession()->setFlash('success', 'Override Vendor Config Saved Successfully');
        }
        
        return $this->render('config', ['model' => $model]);
    
    }
    
    public function actionMenu(){
        $user = User::findOne($_REQUEST['id']);
        $vendorMenu = User::getVendorDefaultMenu($user);
        if($vendorMenu === false){
            $vendorMenu = new VendorMenu();
            $vendorMenu->name = 'Menu';
            $vendorMenu->vendorId = $user->id;
            $vendorMenu->isDefault = 1;
            $vendorMenu->save();
        }
        $vendorId = $user->id;
        
        $vendorCategories = MenuCategories::find()->where('isArchived = 0 and vendorId = '.$user->id.' order by sorting asc')->all();
        
        return $this->render('/../../../views/menu/index', ['vendorId' => $vendorId, 'menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
    }
    
    public function actionPayments(){
        $transactions = VendorMembership::getVendorMemberships($_REQUEST['id'], 20, 1);
        return $this->render('/../../../views/vendor/billing/index', ['transactions' => $transactions, 'url' => '/admin/vendors/viewpage', 'userId' => $_REQUEST['id']]);        
    }
    
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $transactions = VendorMembership::getVendorMemberships($userId, 20, $page);
        return $this->renderPartial('/../../../views/vendor/billing/_list', ['transactions' => $transactions, 'currentPage' => $page, 'userId' => $userId]);
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
