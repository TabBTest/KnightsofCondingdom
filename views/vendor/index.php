<?php 

use app\models\VendorMenuItem;
use app\models\TenantInfo;
$this->title = 'Profile Settings';
$this->params['breadcrumbs'][] = $this->title;
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
        <h1>Profile</h1>
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