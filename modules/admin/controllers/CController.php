<?php
namespace app\modules\admin\controllers;
use yii\web\Controller;
class CController extends Controller
{
    public function beforeAction($event)
    {
        $this->layout = "/../../modules/admin/views/layouts/main";
        return parent::beforeAction($event);
    }
}