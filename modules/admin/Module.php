<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();

        //$this->layoutPath = \Yii::getPathOfAlias('admin.views.layouts');
        //$this->layout = 'main';
        
         \Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/default/login'],            
        ]);
    }
}
