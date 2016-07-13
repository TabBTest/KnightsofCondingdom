<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VendorCoupons */

$this->title = 'Update Vendor Coupons: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-coupons-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
