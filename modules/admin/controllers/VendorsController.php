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
                        'actions' => ['index','config', 'customers', 'settings', 'payments', 'viewpage', 'menu'],
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
            $vendorMenu->name = 'default menu';
            $vendorMenu->vendorId = $user->id;
            $vendorMenu->isDefault = 1;
            $vendorMenu->save();
        }
        
        $vendorCategories = MenuCategories::find()->where('isArchived = 0 and vendorId = '.$user->id.' order by sorting asc')->all();
        
        return $this->render('/../../../views/menu/index', ['menu' => $vendorMenu, 'vendorCategories' => $vendorCategories]);
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
