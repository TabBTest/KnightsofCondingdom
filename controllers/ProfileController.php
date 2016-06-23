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
use app\models\TenantInfo;
use app\models\User;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class ProfileController extends CController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'save'],
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
        $model = User::findOne(\Yii::$app->user->id);
        return $this->render('profile', ['model' => $model]);
    }

    public function actionSave(){
        $userId = \Yii::$app->user->id;
        $model = User::findOne(\Yii::$app->user->id);
        if(count($_POST) > 0){

            $userData = $_POST['User'];
            $hasDuplicate = false;

            if($model->role == User::ROLE_VENDOR){
                $user = User::findOne(['email' => $userData['email']]);
                if($user && $user->id != $userId){
                    $hasDuplicate = true;
                }
            }else{
                $user = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_VENDOR]);

                $customers = User::findOne(['email' => $userData['email'], 'role' => User::ROLE_CUSTOMER, 'vendorId' => $model->vendorId]);
                if($user || ($customers && $customers->id != $userId)){
                    $hasDuplicate = true;
                }
            }


            if($hasDuplicate){
                 \Yii::$app->getSession()->setFlash('error', 'Email should be unique');
            }else{
                //we register it already
                $model->name = $userData['name'];
                $model->streetAddress = $userData['streetAddress'];
                $model->city = $userData['city'];
                $model->state = $userData['state'];
                $model->email = $userData['email'];
                $model->phoneNumber = $userData['phoneNumber'];
                $message = 'Profile Saved Successfully';

                if($_POST['password'] != ''){
                    if($_POST['password'] == $_POST['confirmPassword']){
                        $model->password = $_POST['password'];
                        $model->confirmPassword = $_POST['password'];
                    }else{
                        \Yii::$app->getSession()->setFlash('warning', 'Password did not matched');
                    }
                }

                if($model->save()){

                    \Yii::$app->getSession()->setFlash('success', $message);
                    return $this->redirect('/profile');
                }
            }


        }
        return $this->redirect('/profile');
    }
}
