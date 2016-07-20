<?php		
use app\models\AppConfig;
use app\models\VendorAppConfigOverride;
$appConfigs = AppConfig::find()->where('')->all();

$this->title = 'Override Vendor Config';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(isset($message) && $message !== false){?>
    <div class="alert alert-success"><?php echo $message?></div>
<?php }?>

<?php echo $this->render('/../../../views/partials/_show_message', []);?>


<h1>Override Vendor Config</h1>

<form id='admin-settings-form' action="/admin/vendors/config?id=<?php echo $model->id?>" method="POST" class="form-horizontal" onsubmit="return AdminSettings.validateSettings()">
<input type='hidden' name='vendorId' value="<?php echo $model->id?>"/>
<?php
foreach($appConfigs as $conf){
		$inputOptions = $conf->getInputOptions();
		$val = VendorAppConfigOverride::getVendorOverride($model->id, $conf->code)
    ?>
    <div class="form-group field-applicationtype-keyword required">
        <label for="applicationtype-keyword" class="col-xs-4 control-label"><?php echo $conf->name?></label>
        <div class="col-xs-12 col-md-5">
            <input type="<?php echo $inputOptions['type']?>" class="form-control currency-val <?php echo AppConfig::getCustomClasses($conf->code)?>" maxlength="255" value="<?php echo $val?>" name="AppConfig[<?php echo $conf->code?>]" 
            style="<?php echo $inputOptions['width']?>">
            <div class="help-block"></div></div>
    </div>
<?php }?>

    <div class="form-group">
        <div class=" col-xs-12 col-md-offset-4 col-md-5">
            <button class="btn btn-success" type="submit">Save</button>
        </div>
    </div>

</form>