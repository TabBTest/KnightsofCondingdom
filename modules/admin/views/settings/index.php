<?php		
use app\models\AppConfig;
$appConfigs = AppConfig::find()->where('')->all();

$this->title = 'Application Settings';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(isset($message) && $message !== false){?>
    <div class="alert alert-success"><?php echo $message?></div>
<?php }?>
<h1>Application Settings</h1>

<form id='admin-settings-form' action="/admin/settings" method="POST" class="form-horizontal" onsubmit="return AdminSettings.validateSettings()">
<?php
foreach($appConfigs as $conf){
		$inputOptions = $conf->getInputOptions();
    ?>
    <div class="form-group field-applicationtype-keyword required">
        <label for="applicationtype-keyword" class="col-xs-4 control-label"><?php echo $conf->name?></label>
        <div class="col-xs-12 col-md-5">
            <input type="<?php echo $inputOptions['type']?>" class="form-control currency-val <?php echo AppConfig::getCustomClasses($conf->code)?>" maxlength="255" value="<?php echo $conf->val?>" name="AppConfig[<?php echo $conf->code?>]" 
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