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

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class PreviewController extends CController
{
   
   

    /**
     * Lists all ApplicationType models.
     * @return mixed
     */
    public function actionWidget()
    { 
        $this->layout = 'preview';
        $id = $_REQUEST['id'];
        $model = User::findOne($id);
        return $this->render('widget', ['model' => $model]);
    }
    
}
