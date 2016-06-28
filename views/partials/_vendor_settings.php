<?php 
use app\models\User;
use app\helpers\UtilityHelper;
use yii\widgets\MaskedInput;
use app\models\TenantInfo;
use app\helpers\TenantHelper;
?>
<div id="tab-settings" class="tab-pane fade in active">
        <br>
        <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Widget</label>
            <textarea class='form-control' rows="5" cols="20">
<script type="text/javascript" id="foodapp-js" src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/js/sdk.js"></script>
<div class='restalutions-widget-button' style='position: absolute; top: 10px; right: 10px; height: 20px; width: 100px; -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 14px;
    padding: 6px 12px;
    text-align: center;background-color: #5cb85c; '>Show Menu
</div>
<div class="restalutions-widget" data-subdomain='<?php echo TenantHelper::getVendorSubdomain($model->id)?>' style='position: absolute; right: 10px; top: 50px; display: none'></div></textarea>
        </div>
    </div>
    
    <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Button</label>
            <a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-now.jpg" /></a>
            <textarea class='form-control' rows="5" cols="20"><a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-now.jpg" /></a></textarea>
        </div>
    </div>

    <form action='/vendor/save-settings' method='POST'>
        <?php
        $userId = $model->id;
        ?>
        <input type='hidden' value='<?php echo $userId?>' name='userId'/>
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
    </div>