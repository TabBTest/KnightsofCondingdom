<?php 
use app\models\User;
use app\helpers\UtilityHelper;
use yii\widgets\MaskedInput;
use app\models\TenantInfo;
use app\helpers\TenantHelper;
use app\models\VendorOperatingHours;
?>

<?php 
// var_dump(Yii::$app->session->get('role'));
// var_dump($model->isVendorStoreOpen());
?>
<div id="tab-settings" class="tab-pane fade in active">
        <br>
        <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Widget</label>
            <textarea class='form-control copy-content' id='widget-js' rows="5" cols="20">
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
<br />
<a href="javascript: void(0)" data-clipboard-target="#widget-js" class="btn btn-info btn-copy-widget" data-type='widget'><i class="fa fa-copy">Copy to Clipboard</i></a>
        </div>
    </div>
    
    
    
    <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Button</label>
            <a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-now.jpg" /></a>
            <textarea class='form-control copy-content' id='widget-button' rows="5" cols="20"><a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-now.jpg" /></a></textarea>
            <br />
<a href="javascript: void(0)" data-clipboard-target="#widget-button" class="btn btn-info btn-copy-widget" data-type='button'><i class="fa fa-copy">Copy to Clipboard</i></a>
        </div>
    </div>

    <form action='/vendor/save-settings' method='POST' class='vendor-settings-form' onsubmit="return VendorSettings.validateSettings()">
        <?php
        $userId = $model->id;
        ?>
        <input type='hidden' value='<?php echo $userId?>' name='userId'/>
        <?php foreach(TenantInfo::getTenantCodes() as $codeKey => $codeDescription){
            ?>
            <?php if($codeKey == TenantInfo::CODE_SUBDOMAIN_REDIRECT || $codeKey == TenantInfo::CODE_HAS_DELIVERY){?>

                <div class='row form-group'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <input type='hidden' value='0' name='TenantCode[<?php echo $codeKey?>]'/>
                        <input type='checkbox' style='margin-left: 10px' class='' value='1' name='TenantCode[<?php echo $codeKey?>]' <?php echo TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'checked' : ''?>/>
                    </div>
                </div>
            <?php }else{                 
                ?>
                <div class='row form-group'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <input class='form-control <?php echo TenantInfo::getCustomClasses($codeKey)?>' type='text' name='TenantCode[<?php echo $codeKey?>]' value="<?php echo TenantInfo::getTenantValue($userId, $codeKey)?>"/>
                    </div>
                </div>
            <?php }?>
        <?php }?>
        <?php 
        $operatingTime = UtilityHelper::getOperatingTime();                
        ?>
        <div class='row form-group  form-inline'>
            <div class='col-xs-12'>
                <label class='form-label'>Operating Hours</label>
            </div>
            <?php foreach(UtilityHelper::getDays() as $key => $val){
                $operatingHours = VendorOperatingHours::getVendorOperatingHours($userId, $key);
                ?>
            
            <div class="form-group col-xs-12" style='margin-bottom: 10px'>
            <label for="inputEmail3" class="col-xs-2 control-label"><?php echo $val?></label>
            <div class='col-xs-10'>
                <div class='operating-hour-list' data-day='<?php echo $key?>'>
                
                    <?php foreach($operatingHours as $operatingHour){?>
                    <div class='operating-hours row' data-day='<?php echo $key?>' style='margin-bottom: 10px'>
                        <div class="col-xs-2">
                            <select  style='width: 100%' class='form-control start' name='startTime[<?php echo $key?>][]'>
                                <option value=''>Start Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option <?php echo $operatingHour->startTime == $val ? 'selected' : ''?> value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>            
                      
                        <div class="col-xs-2">
                            
                            <select   style='width: 100%' class='form-control end' name='endTime[<?php echo $key?>][]'>
                                <option value=''>End Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option <?php echo $operatingHour->endTime == $val ? 'selected' : ''?> value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-xs-2" style='margin-top: 5px;'>                    
                            <a data-day='<?php echo $key?>' class='delete-operating-hours' href='javascript: void(0)'><i class='fa fa-trash'></i></a>                
                        </div>
                    </div>
                    <?php }?>
                
                    <?php if(count($operatingHours) == 0){?>
                    <div class='operating-hours row' data-day='<?php echo $key?>' style='margin-bottom: 10px'>
                        <div class="col-xs-2">
                            <select  style='width: 100%' class='form-control start' name='startTime[<?php echo $key?>][]'>
                                <option value=''>Start Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>            
                      
                        <div class="col-xs-2">
                            
                            <select   style='width: 100%' class='form-control end' name='endTime[<?php echo $key?>][]'>
                                <option value=''>End Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-xs-2" style='margin-top: 5px;'>                    
                            <a data-day='<?php echo $key?>' class='delete-operating-hours' href='javascript: void(0)'><i class='fa fa-trash'></i></a>                
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class='row'>
                    <div class='col-xs-12' >
                        <button onclick="javascript:  VendorSettings.addOperatingHours(<?php echo $key?>)" type='button' class='btn btn-info btn-xs'>Add More Time</button>
                    </div>
                </div>
            </div>
            
          </div>
            <?php }?>
        </div>
        
        <div class='row form-group'>
            <div class='col-xs-12'>
                <button class='btn btn-success'>Save</button>
            </div>
        </div>
    </form>
    </div>