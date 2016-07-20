<?php
namespace app\modules\admin\controllers;
use yii\web\Controller;
use app\models\User;
class CController extends Controller
{
    public function beforeAction($event)
    {
        if(\Yii::$app->user->identity != null && \Yii::$app->user->identity->role != User::ROLE_ADMIN){
            //
            \Yii::$app->user->logout();    
            return $this->redirect('/admin');
        }
        $this->layout = "/../../modules/admin/views/layouts/main";
        return parent::beforeAction($event);
    }
}