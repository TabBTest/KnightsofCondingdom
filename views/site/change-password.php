<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
 <div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
 </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('success')){?>
 <div class="">
<div class="alert alert-success">
    <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
</div>
 </div>
<?php } ?>

    <p>Change Password:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'change-pw-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
        
        

        <div class="form-group field-loginform-email required">
        <label for="loginform-email" class="col-lg-2 control-label">New Password</label>
        <div class="col-lg-3"><input type="password"  name="password" class="form-control" required></div>
        <div class="col-lg-7"></div>
        </div>
        
        <div class="form-group field-loginform-email required">
        <label for="loginform-email" class="col-lg-2 control-label">Confirm New Password</label>
        <div class="col-lg-3"><input type="password"  name="confirmPassword" class="form-control" required></div>
        <div class="col-lg-7"></div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Reset', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
