<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendors';
$this->params['breadcrumbs'][] = $this->title;

function extraLinks($model, $el)
{
    $rest = [
        [
            'label' => 'View Customers',
            'url' => '/admin/vendors/customers?id=' . ($model->id),
            'ico' => 'fa-list',
        ]
    ];

   
        array_push($rest, [
        'label' => 'Update Vendor Settings',
        'url' => '/admin/vendors/settings?id=' . $model->id,
        'ico' => 'fa-pencil'
            ]
        );
        array_push($rest, [
        'label' => 'View Payment History',
        'url' => '/admin/vendors/payments?id=' . $model->id,
        'ico' => 'fa-list-alt'
            ]
        );

    return $rest;
}

?>


<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'email:email',
           // 'password',
           // 'role',
            'name',
            // 'streetAddress',
            'phoneNumber',
            // 'billingName',
            // 'billingStreetAddress',
            // 'vendorId',
            // 'date_created',
            // 'date_updated',
            // 'stripeId',
            // 'isPasswordReset',
            // 'cardLast4',
            // 'cardExpiry',
            // 'city',
            // 'state',
            // 'billingCity',
            // 'billingState',
            // 'billingPhoneNumber',

            //['class' => 'yii\grid\ActionColumn'],
            ['label' => '',
            'format' => 'raw',
            'headerOptions' => ['class' => 'action-cell'],
            'value' => function ($model) {
                return UtilityHelper::buildActionWrapper('/admin/vendors', $model->id, false, extraLinks($model, $this), false , false);
            },
            ],
        ],
    ]); ?>
</div>
