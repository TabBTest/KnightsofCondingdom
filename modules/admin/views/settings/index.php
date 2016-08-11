<?php
use app\models\AppConfig;
$appConfigs = AppConfig::find()->where('')->all();

$this->title = 'Application Settings';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(isset($message) && $message !== false) { ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php } ?>
<div class="col-md-3 col-md-offset-5">
    <h2>Application Settings</h2>

    <form id="admin-settings-form"
          action="/admin/settings"
          method="POST"
          class="form-horizontal"
          onsubmit="return AdminSettings.validateSettings()"
    >
        <?php
        foreach($appConfigs as $conf){
            $inputOptions = $conf->getInputOptions();
            ?>
            <div class="form-group field-applicationtype-keyword required">
                <label for="applicationtype-keyword" class="control-label"><?= $conf->name ?></label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="<?= $inputOptions['type'] ?>"
                           min="0.01"
                           step="0.01"
                           class="form-control currency-val"
                           maxlength="255"
                    <?= AppConfig::getCustomClasses($conf->code) ?>"
                    value="<?= $conf->val ?>"
                    name="AppConfig[<?= $conf->code ?>]"
                    style="<?= $inputOptions['width'] ?>"
                    />
                    <div class="help-block"></div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <button class="btn btn-primary btn-raised" type="submit">Save</button>
        </div>

    </form>
</div>
