<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\User;
use app\helpers\TenantHelper;
$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

$pageJs = <<<JS
$('#select-order-now-button').on('click', function() {
	$('#order-now-button-field').prop("value", $('input[name=order-now-button-select]:checked').val());
});

$('#select-state').val('$model->state');

if (!$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").is(":checked")) {
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled",true);
}

$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").click(function() {
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled", !this.checked)
});

if (!$("input[name='TenantCode[HAS_DELIVERY]']").is(":checked")) {
    $("input[name='TenantCode[DELIVERY_CHARGE]']").prop("disabled",true);
}

$("input[name='TenantCode[HAS_DELIVERY]']").click(function() {
    $("input[name='TenantCode[DELIVERY_CHARGE]']").prop("disabled", !this.checked)
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
    <li><a data-toggle="tab" href="#tab-profile">Profile</a></li>
    <li><a data-toggle="tab" href="#billing-info">Billing Info</a></li>
</ul>

<div class="tab-content">
    <?php echo $this->render('//partials/_vendor_settings', ['model' => $model]);?>
    <?php echo $this->render('//partials/_profile', ['model' => $model, 'show' => false]);?>
    <?php echo $this->render('//partials/_billing', ['model' => $model]);?>
    
</div>
