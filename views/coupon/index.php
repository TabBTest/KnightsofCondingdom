<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\VendorCoupons;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VendorCouponsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coupons';
$this->params['breadcrumbs'][] = $this->title;


function extraLinks($model, $el)
{
    $ret = '<li><a class="" href="/coupon/update?id='.$model->id.'" data-next="1" data-id="'. $model->id .'">';
    $ret .= '<i class="fa fa-pencil" style="width:15px"></i>Edit</a></li>';
    if($model->isArchived == 0){
        $ret .= '<li><a class="coupon-archive" href="javascript: void(0);" data-next="1" data-id="'. $model->id .'">';
        $ret .= '<i class="fa fa-trash" style="width:15px"></i>Archive</a></li>';
    }else{
        $ret .= '<li><a class="coupon-archive" href="javascript: void(0);" data-next="0" data-id="'. $model->id .'">';
        $ret .= '<i class="fa fa-trash" style="width:15px"></i>Un-Archive</a></li>';
    }
    
    $ret .= '<li><a href="/coupon/orders?id='.$model->id.'" data-id="'. $model->id .'">';
    $ret .= '<i class="fa fa-eye" style="width:15px"></i>View Orders</a></li>';
    
    return $ret;
}

?>
<div class="vendor-coupons-index">
<?php echo $this->render('//partials/_show_message', []);?>
    <p>
        <?= Html::a('Create Coupons', ['/coupon/create'], ['class' => 'pull-right btn btn-success']) ?>
    </p>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'code',
            [
            'label' => 'Is Archived?',
            'filter' => Html::activeDropDownList($searchModel, 'isArchived', ['' => 'Show All', '1' => 'Archived', '0' => 'Un-archived'], ['class'=>'form-control']),
            'value' => function($model){
                return $model->isArchived == 1 ? 'Archived' : 'Un-acrhived';
            }
            ],
            
            [
            'label' => 'Type',
            'filter' => Html::activeDropDownList($searchModel, 'discountType', ['' => 'Show All', VendorCoupons::TYPE_PERCENTAGE => 'Percentage', VendorCoupons::TYPE_AMOUNT => 'Amount'], ['class'=>'form-control']),
            'value' => function($model){
                return $model->discountType == VendorCoupons::TYPE_PERCENTAGE ? 'Percentage' : 'Amount';
            }
            ],
            
            'discount',
            // 'date_created',

            //['class' => 'yii\grid\ActionColumn'],
            ['label' => '',
            'format' => 'raw',
            'headerOptions' => ['class' => 'action-cell'],
            'value' => function ($model) {
                return UtilityHelper::buildActionWrapper('/admin/vendors', $model->id, false, null, extraLinks($model, $this), false);
            },
            ],
        ],
    ]); ?>
</div>
<form action="/coupon/archive" method="post" id="form-archive-coupon">
    <input type='hidden' id='id' name='id' value=""/>
    <input type='hidden' id='archive' name='archive' value=""/>
</form>