<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\User;
use app\helpers\TenantHelper;
use app\models\VendorMembership;
$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

$pageJs = <<<JS
$('#select-order-now-button').on('click', function() {
	$('#order-now-button-field').prop("value", $('input[name=order-now-button-select]:checked').val());
});

$('#select-state').val('$model->state');

if (!$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").is(":checked")) {
      $(".row[data-key='REDIRECT_URL']").hide();
}

$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").click(function() {
    if(this.checked){
        $(".row[data-key='REDIRECT_URL']").show();
    }
    else{
        $(".row[data-key='REDIRECT_URL']").hide();
    }
    
});

if (!$("input[name='TenantCode[HAS_DELIVERY]']").is(":checked")) {    
    $(".row[data-key='DELIVERY_CHARGE']").hide();
    $(".row[data-key='DELIVERY_MINIMUM_AMOUNT']").hide();
    
}

$("input[name='TenantCode[HAS_DELIVERY]']").click(function() {
    if(this.checked){
        $(".row[data-key='DELIVERY_CHARGE']").show();
        $(".row[data-key='DELIVERY_MINIMUM_AMOUNT']").show();
    }
    else{
        $(".row[data-key='DELIVERY_CHARGE']").hide();
        $(".row[data-key='DELIVERY_MINIMUM_AMOUNT']").hide();
    }
});
JS;

$this->registerJs('Stripe.setPublishableKey(\'' . \Yii::$app->params['stripe_publishable_key'] . '\');', $this::POS_READY);
$this->registerJs($pageJs, $this::POS_READY);
?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

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

<ul class="nav nav-tabs">
    
    <li class="active"><a data-toggle="tab" href="#tab-settings">Settings</a></li>
    <li><a data-toggle="tab" href="#tab-profile">Restaurant Info</a></li>
    <li><a data-toggle="tab" href="#billing-info">Billing Info</a></li>
    <?php if(Yii::$app->user->identity->role == User::ROLE_VENDOR){?>
    <li><a data-toggle="tab" href="#billing-history">Billing History</a></li>
    <?php }?>
</ul>

<div class="tab-content">
    <?php echo $this->render('//partials/_vendor_settings', ['model' => $model]);?>
    <?php echo $this->render('//partials/_profile', ['model' => $model, 'show' => false]);?>
    <?php echo $this->render('//partials/_billing', ['model' => $model]);?>
    <?php if(Yii::$app->user->identity->role == User::ROLE_VENDOR){?>
                    <?php echo $this->render('billing/index', ['transactions' =>  VendorMembership::getVendorMemberships($model->id, 20, 1), 'url' => '/vendor/viewpage', 'userId' => $model->id]);?>
    <?php }?>
    
    
</div>
