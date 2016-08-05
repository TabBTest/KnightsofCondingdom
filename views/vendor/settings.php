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
      $("[data-key='REDIRECT_URL']").hide();
}

$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").click(function() {
    if(this.checked){
        $("[data-key='REDIRECT_URL']").show();
    }
    else{
        $("[data-key='REDIRECT_URL']").hide();
    }
    
});

if ($("select[name='TenantCode[HAS_DELIVERY]']").val() == 0) {    
    $("[data-key='DELIVERY_CHARGE']").hide();
    $("[data-key='DELIVERY_MINIMUM_AMOUNT']").hide();
    
}

$("select[name='TenantCode[HAS_DELIVERY]']").on('change', function() {
    if($(this).val() == 1){
        $("[data-key='DELIVERY_CHARGE']").show();
        $("[data-key='DELIVERY_MINIMUM_AMOUNT']").show();
    }
    else{
        $("[data-key='DELIVERY_CHARGE']").hide();
        $("[data-key='DELIVERY_MINIMUM_AMOUNT']").hide();
    }
});
JS;


$this->registerJs($pageJs, $this::POS_READY);
?>



<?php echo $this->render('//partials/_show_message', []);?>

<ul class="nav nav-tabs">
    
    <li class="<?php echo $_REQUEST['view'] == 'settings' ? 'active' : ''?>"><a data-toggle="tab" href="#tab-settings">Settings</a></li>
    <li class="<?php echo $_REQUEST['view'] == 'operating-hours' ? 'active' : ''?>"><a data-toggle="tab" href="#operating-hours">Hours of Operation</a></li>
    <li class="<?php echo $_REQUEST['view'] == 'info' ? 'active' : ''?>"><a data-toggle="tab" href="#tab-profile">Restaurant Info</a></li>
    <li class="<?php echo $_REQUEST['view'] == 'billing' ? 'active' : ''?>"><a data-toggle="tab" href="#billing-info">Billing Info</a></li>
    <?php if(Yii::$app->user->identity->role == User::ROLE_VENDOR){?>
    <li class="<?php echo $_REQUEST['view'] == 'history' ? 'active' : ''?>"><a data-toggle="tab" href="#billing-history">Billing History</a></li>
    <?php }?>
</ul>

<div class="tab-content">
    <?= $this->render('//partials/_vendor_settings', ['model' => $model]) ?>
    <?= $this->render('//partials/_operating-hours', ['model' => $model]) ?>
    <?= $this->render('//partials/_profile', ['model' => $model, 'show' => false]) ?>
    <?= $this->render('//partials/_billing', ['model' => $model]) ?>
    <?php if(Yii::$app->user->identity->role == User::ROLE_VENDOR){?>
    <?= $this->render('billing/index', ['transactions' =>  VendorMembership::getVendorMemberships($model->id, 20, 1), 'url' => '/vendor/viewpage', 'userId' => $model->id]);?>
    <?php } ?>
</div>
