<?php 
use app\models\User;
use app\helpers\UtilityHelper;
use yii\widgets\MaskedInput;
use app\models\TenantInfo;
use app\helpers\TenantHelper;
use app\models\VendorOperatingHours;
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
            <a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-buttons/<?= $model->orderButtonImage ?>" /></a>
            <textarea class='form-control copy-content' id='widget-button' rows="5" cols="20"><a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-buttons/<?= $model->orderButtonImage ?>" /></a></textarea>
            <br />
<a href="javascript: void(0)" data-clipboard-target="#widget-button" class="btn btn-info btn-copy-widget" data-type='button'><i class="fa fa-copy">Copy to Clipboard</i></a>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#change-button-modal">
                Change Order Now Button
            </button>
            <div class="modal fade" id="change-button-modal" tabindex="-1" role="dialog" aria-labelledby="change-button-modal-label">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="change-button-modal-label">Select a Button Style</h4>
                        </div>
                        <div class="modal-body">
                            <div class="radio">
                                <label><input type="radio" name="order-now-button-select" value="order-now-01.png"><img src="/images/order-buttons/order-now-01.png" alt="Order Now Button 1"></label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="order-now-button-select" value="order-now-02.png"><img src="/images/order-buttons/order-now-02.png" alt="Order Now Button 2"></label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="order-now-button-select" value="order-now-03.png"><img src="/images/order-buttons/order-now-03.png" alt="Order Now Button 3"></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal" id="select-order-now-button">Select Image</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action='/vendor/save-settings' method='POST' class='vendor-settings-form' onsubmit="return VendorSettings.validateSettings()">
        <?php
        $userId = $model->id;
        ?>
        <input type='hidden' value='<?php echo $userId?>' name='userId'/>
        <input type='hidden' value='<?= $model->orderButtonImage ?>' name='orderButtonImage' id="order-now-button-field"/>
        <?php foreach(TenantInfo::getTenantCodes() as $codeKey => $codeDescription){
            ?>
            <?php if($codeKey == TenantInfo::CODE_SUBDOMAIN_REDIRECT){?>

                <div class='row form-group' data-key='<?php echo $codeKey?>'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <input type='hidden' value='0' name='TenantCode[<?php echo $codeKey?>]'/>
                        <input type='checkbox' style='margin-left: 10px' class='' value='1' data-key='<?php echo $codeKey?>' name='TenantCode[<?php echo $codeKey?>]' <?php echo TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'checked' : ''?>/>
                    </div>
                </div>
            <?php } else if($codeKey == TenantInfo::CODE_HAS_DELIVERY){
            ?>
             <div class='row form-group' data-key='<?php echo $codeKey?>'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <select class='form-control short-input' data-key='<?php echo $codeKey?>' name='TenantCode[<?php echo $codeKey?>]'>
                            <option <?php echo TenantInfo::getTenantValue($userId, $codeKey) == 0 ? 'selected' : ''?> value='0'>No</option>
                            <option <?php echo TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'selected' : ''?> value='1'>Yes</option>
                        </select>
                    </div>
                </div>
            <?php 
            }else{                 
                ?>
                <div class='row form-group' data-key='<?php echo $codeKey?>'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <input class='form-control <?php echo TenantInfo::getCustomClasses($codeKey)?>' data-key='<?php echo $codeKey?>' type='text' name='TenantCode[<?php echo $codeKey?>]' value="<?php echo TenantInfo::getTenantValue($userId, $codeKey)?>"/>
                    </div>
                </div>
            <?php }?>
        <?php }?>
        <?php 
        $operatingTime = UtilityHelper::getOperatingTime();
                        
        ?>
        
        <div class='row form-group'>
             <div class='col-xs-12'>
                <label class='form-label'>Is Store Open?</label>
                <input type='hidden' name="isStoreOpen" value='0'/>
                <input type="checkbox" name="isStoreOpen" <?php echo $model->isStoreOpen == 1 ? 'checked' : ''?> value='1'/>
            </div>
        </div>
        <div class='row form-group store-close-reason' style='display: <?php echo $model->isStoreOpen == 1 ? 'none' : 'block'?>'>
            <div class='col-xs-12'>
                <label class='form-label'>Reason for Store Closing:</label>
                <textarea class='form-control' name='storeCloseReason' rows='5' cols='20'><?php echo nl2br($model->storeCloseReason)?></textarea>
            </div>
        </div>
        <div class='row form-group  form-inline'>
            
            <div class='col-xs-8'>
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
                        <div class="col-xs-3">
                            <select  style='width: 100%' class='form-control start operating-hr' name='startTime[<?php echo $key?>][]'>
                                <option value=''>Start Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option <?php echo $operatingHour->startTime == $val ? 'selected' : ''?> value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>            
                      
                        <div class="col-xs-3">
                            
                            <select   style='width: 100%' class='form-control end operating-hr' name='endTime[<?php echo $key?>][]'>
                                <option value=''>End Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option <?php echo $operatingHour->endTime == $val ? 'selected' : ''?> value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-xs-3" style='margin-top: 5px;'>                    
                            <a data-day='<?php echo $key?>' class='delete-operating-hours' href='javascript: void(0)'><i class='fa fa-trash'></i></a>                
                        </div>
                    </div>
                    <?php }?>
                
                    <?php if(count($operatingHours) == 0){?>
                    <div class='operating-hours row' data-day='<?php echo $key?>' style='margin-bottom: 10px'>
                        <div class="col-xs-3">
                            <select  style='width: 100%' class='form-control start operating-hr' name='startTime[<?php echo $key?>][]'>
                                <option value=''>Start Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>            
                      
                        <div class="col-xs-3">
                            
                            <select   style='width: 100%' class='form-control end operating-hr' name='endTime[<?php echo $key?>][]'>
                                <option value=''>End Time</option>
                                <?php foreach($operatingTime as $val => $display){?>
                                <option value='<?php echo $val?>'><?php echo $display?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-xs-3" style='margin-top: 5px;'>                    
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
            <div class='col-xs-4'>
                <div class='col-xs-12'>
                    <label class='form-label'>Preview:</label>
                </div>
                <div class='col-xs-12 preview-operating-hours'>
                    
                </div>
            </div>
        </div>
        
        <div class='row form-group'>
            <div class='col-xs-12'>
                <button class='btn btn-success'>Save</button>
            </div>
        </div>
    </form>
    </div>