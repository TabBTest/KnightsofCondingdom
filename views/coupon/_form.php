<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\VendorCoupons;

/* @var $this yii\web\View */
/* @var $model app\models\VendorCoupons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-coupons-form">

    <?php $form = ActiveForm::begin(); ?>
    <input type='hidden' name='VendorCoupons[vendorId]' value='<?php echo $model->vendorId?>'/>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'class' => 'form-control short-input']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discountType')->dropDownList(VendorCoupons::getCouponType(),['class' => 'form-control short-input']) ?>

    <?= $form->field($model, 'discount')->textInput(['class' => 'form-control short-input']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
