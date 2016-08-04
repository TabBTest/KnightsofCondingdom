<?php
use app\models\User;
use app\helpers\UtilityHelper;
use app\models\TenantInfo;
use app\helpers\TenantHelper;
use app\models\VendorOperatingHours;

$userId = $model->id;
$operatingTime = UtilityHelper::getOperatingTime();
?>

<div id="operating-hours" class="tab-pane <?php echo $_REQUEST['view'] == 'settings' ? 'active' : ''?>">
    <form action="/vendor/save-operating-hours" method="POST" id="operating-hours-form" onsubmit="return VendorSettings.validateOperatingHours()">
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
        <div class='row form-group form-inline'>

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
                                    <button onclick="javascript: VendorSettings.addOperatingHours(<?php echo $key?>)" type='button' class='btn btn-primary btn-xs'>Add More Time</button>
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
                <button class='btn btn-primary btn-raised'>Save</button>
            </div>
        </div>
</div>
</form>
