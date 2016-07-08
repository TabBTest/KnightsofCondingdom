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

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class CustomerController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'viewpage', 'activate', 'deactivate'],
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
        
        return $this->render('index', []);
    }
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $customers = User::getVendorCustomers($userId, 20, $page, $_REQUEST['filter']);    
        return $this->renderPartial('_list', ['customers' => $customers, 'currentPage' => $page]);
    }
    
    public function actionActivate(){
        $id = $_REQUEST['id'];
        $user = User::findOne(base64_decode($id));
        if($user){
            $user->isActive = 1;
            $user->save();
        }
        die;
    }
    
    public function actionDeactivate(){
        $id = $_REQUEST['id'];
        $user = User::findOne(base64_decode($id));
        if($user){
            $user->isActive = 0;
            $user->save();
        }
        die;
    }
}
