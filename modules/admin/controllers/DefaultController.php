<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\helpers\NotificationHelper;
use app\models\UserResetPassword;
use app\modules\admin\models\LoginForm;

class DefaultController extends Controller
{
    
    public function beforeAction($event)
    {
        $this->layout = "/../../modules/admin/views/layouts/main";
        return parent::beforeAction($event);
    }
    
    public function actionIndex()
    {
        return $this->actionLogin();
    }
   
    public function actionForget(){
        if(count($_POST) != 0){
            $resp = array();
            $users = User::find()->where("username in ('".$_POST['username']."')")->all();
            if(count($users) != 0){
                //we need to add check if the password has been reset more than 3 times today
                
                $userReset = UserResetPassword::find()->where("user_id = ".$users[0]->id." and date_requested = '".date('Y-m-d', strtotime('now'))."'")->all();
                //var_dump(count($userReset));
                if(count($userReset) >= 3){
                    NotificationHelper::notifyAdminOFMoreThan3Reset($users[0], $userReset);
                    return $this->render('forget', ['error' => 'Password can only be reset 3 times in a day']);
                }else{
                    //we should add a record here
                    $userPassword = new UserResetPassword();
                    $userPassword->ip_address = $_SERVER['REMOTE_ADDR'];
                    $userPassword->user_id = $users[0]->id;
                    $userPassword->save();
                    NotificationHelper::sendPasswordRecovery($users[0], $userPassword->id);
                    $resp['status'] = 1;
                    return $this->render('forget', ['message' => 'Recovery Email Sent']);
                }
            }else{
                $resp['status'] = 0;
                return $this->render('forget', ['error' => 'Account is invalid']);
            }
            
         
        }
        return $this->render('forget');
    }
    public function actionReset(){
        if(count($_POST) != 0){
            $password = $_POST['password'];
            $key = $_POST['key'];
            if($_POST['password'] != $_POST['confirmPassword']){
                return $this->render('reset', ['key'=>$key, 'error' => 'Password does not match']);
            }
            
            $users = User::find()->where("md5(id) = '".$key."'")->all();
            if(count($users) == 0){
                $hasError = 'User is not existing';
                return $this->render('reset', ['key'=>$key, 'error' => $hasError]);
            }else{
                //we change the password
                $user = $users[0];
                $user->password = $_POST['password'];
                $user->save('false', ['password']);
                
                //var_dump($_POST['password'].'  ==  '.$user->password);
                //die;
                $loginForm = new LoginForm();
                $resp = $loginForm->simulateLogin($user);
                if($resp !== false){
                    $this->redirect(\Yii::$app->urlManager->createUrl("/home"));
                }else{
                    $this->redirect(\Yii::$app->urlManager->createUrl("/site"));
                }
            }
        }else{
            $users = User::find()->where("md5(id) = '".$_GET['key']."'")->all();
            $hasError = false;
            if(count($users) == 0){
                $hasError = 'Account is not valid';
            }
            return $this->render('reset', ['key'=>$_GET['key'], 'error' => $hasError]);
        }
    }
    public function actionLogin()
    {
    	
        if (!\Yii::$app->user->isGuest) {        	
            $this->redirect(\Yii::$app->urlManager->createUrl("/admin/home"));
        }
		
        $model = new LoginForm();
		$model->attributes = Yii::$app->request->post();
        if ($model->login()) {
            /*
             if($model->getUser()->active == 1){
                 return $this->goBack('/admin/home');
			     
             }else{
                 //we should simulate logout
                 Yii::$app->user->logout();
                 return $this->render('login', [
                     'model' => $model, 'error2' => "Account is not activated", 'inactive' => true, 'id' => $model->getUser()->id
                 ]);
             }
             */
             return $this->goBack('/admin/home');
        } else {
	        
	        
	        if(\Yii::$app->request->isPost)
	        {
	            return $this->render('login', [
                    'model' => $model, 'error' => "Invalid Login Credentials"
                ]);
	        }
	        else{
	            return $this->render('login', [
	                'model' => $model,
	            ]);
	        }
	          
	        
        }
    }

    public function actionLogout()
    {	
        
        Yii::$app->user->logout();       
        return $this->render('login');
        //return $this->goHome();
	}        
}
