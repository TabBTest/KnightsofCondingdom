<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\User;
use app\helpers\TenantHelper;
$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

$jsSelectState = <<<JS
$('#select-state').val('$model->state');
JS;

$jsValidateSubdomain = <<<'JS'
if (!$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").is(":checked")) {
    console.log('kamote');
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled",true);
}

$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").click(function() {
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled", !this.checked)
});
JS;

$this->registerJs('Stripe.setPublishableKey(\'' . \Yii::$app->params['stripe_publishable_key'] . '\');', $this::POS_READY);
$this->registerJs($jsSelectState, $this::POS_READY);
$this->registerJs($jsValidateSubdomain, $this::POS_READY);
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
