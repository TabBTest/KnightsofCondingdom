<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\helpers\UtilityHelper;
use app\models\TestSite;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
use app\commands\NotificationController;
use app\helpers\NotificationHelper;
use app\models\ChecklistItems;
use app\models\Checklist;
use app\models\User;

class HomeController extends CController
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'test'],
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

    public function actionIndex()
    {               
    	return $this->render('index', []);
    }
       
    public function actionTest(){
        
    }
}
