<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'role') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'streetAddress') ?>

    <?php // echo $form->field($model, 'phoneNumber') ?>

    <?php // echo $form->field($model, 'billingName') ?>

    <?php // echo $form->field($model, 'billingStreetAddress') ?>

    <?php // echo $form->field($model, 'vendorId') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_updated') ?>

    <?php // echo $form->field($model, 'stripeId') ?>

    <?php // echo $form->field($model, 'isPasswordReset') ?>

    <?php // echo $form->field($model, 'cardLast4') ?>

    <?php // echo $form->field($model, 'cardExpiry') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'billingCity') ?>

    <?php // echo $form->field($model, 'billingState') ?>

    <?php // echo $form->field($model, 'billingPhoneNumber') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
