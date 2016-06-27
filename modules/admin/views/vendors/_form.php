<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'streetAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phoneNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billingName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billingStreetAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendorId')->textInput() ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_updated')->textInput() ?>

    <?= $form->field($model, 'stripeId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isPasswordReset')->textInput() ?>

    <?= $form->field($model, 'cardLast4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cardExpiry')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billingCity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billingState')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billingPhoneNumber')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
