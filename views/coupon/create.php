<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VendorCoupons */

$this->title = 'Create Vendor Coupons';
$this->params['breadcrumbs'][] = ['label' => 'Vendor Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-coupons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
