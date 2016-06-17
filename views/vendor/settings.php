<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use app\helpers\TenantHelper;
$this->title = 'Profile Settings';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<'JS'
if (!$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").is(":checked")) {
    console.log('kamote');
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled",true);
}

$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").click(function() {
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled", !this.checked)
});
JS;

$this->registerJs($js, $this::POS_READY);
?>

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

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1>Settings</h1>
    </div>
</div>

<div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Widget</label>
            <textarea class='form-control' rows="5" cols="20">
<script type="text/javascript" id="foodapp-js" src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/js/sdk.js"></script>
<div class='foodzilla-widget-button' style='position: absolute; top: 10px; right: 10px; height: 20px; width: 100px; -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 14px;
    padding: 6px 12px;
    text-align: center;background-color: #5cb85c; '>Show Menu
</div>
<div class="foodzilla-widget" data-subdomain='<?php echo TenantHelper::getVendorSubdomain(\Yii::$app->user->id)?>' style='position: absolute; right: 10px; top: 50px; display: none'></div></textarea>
        </div>
    </div>

<form action='/vendor/save-settings' method='POST'>
    <?php
    $userId = \Yii::$app->user->id;
    ?>
<?php foreach(TenantInfo::getTenantCodes() as $codeKey => $codeDescription){
    ?>
    <?php if($codeKey == TenantInfo::CODE_SUBDOMAIN_REDIRECT){?>

    <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'><?php echo $codeDescription?></label>
            <input type='hidden' value='0' name='TenantCode[<?php echo $codeKey?>]'/>
            <input type='checkbox' style='margin-left: 10px' class='' value='1' name='TenantCode[<?php echo $codeKey?>]' <?php echo TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'checked' : ''?>/>
        </div>
    </div>
    <?php }else{?>
    <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'><?php echo $codeDescription?></label>
            <input class='form-control' type='text' name='TenantCode[<?php echo $codeKey?>]' value="<?php echo TenantInfo::getTenantValue($userId, $codeKey)?>"/>
        </div>
    </div>
    <?php }?>
  <?php }?>

    <div class='row form-group'>
        <div class='col-xs-12'>
            <button class='btn btn-success'>Save</button>
        </div>
    </div>
</form>
