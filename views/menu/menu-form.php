<?php 
use app\helpers\UtilityHelper;
?>
<form action='/menu/save-menu' method='POST' enctype="multipart/form-data" id='menu-form'>
    <input type='hidden' name='id' value='<?php echo $model->id?>'/>
    <?php if($model->isNewRecord){?>
    <input type='hidden' name='vendorId' value='<?php echo $model->vendorId?>'/>
    <?php }?>
    
    <div class='row'>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Name</label>
            <input type='text' class='form-control' name='name' value='<?php echo $model->name?>'/>
        </div>
        
        <?php 
        $operatingTime = UtilityHelper::getOperatingTime();
        ?>
        
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Start Time</label>
             <select  class='form-control short-input' name='startTime'>
                <option value=''>Start Time</option>
                <?php foreach($operatingTime as $val => $display){?>
                <option <?php echo $model->startTime == $val ? 'selected' : ''?> value='<?php echo $val?>'><?php echo $display?></option>
                <?php }?>
            </select>
        </div>
        
        <div class='col-xs-12 form-group'>
            <label class='form-label'>End Time</label>
            <select class='form-control short-input' name='endTime'>
                <option value=''>End Time</option>
                <?php foreach($operatingTime as $val => $display){?>
                <option <?php echo $model->endTime == $val ? 'selected' : ''?> value='<?php echo $val?>'><?php echo $display?></option>
                <?php }?>
            </select>
        </div>
        
        
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn' data-dismiss="modal">Close</button>
            <?php if($model->isNewRecord == false){?>
                <button class='btn btn-danger' onclick="javascript: VendorSettings.deleteMenu(<?php echo $model->id?>)" type='button'>Delete</button>
            <?php }?>
            <button type='button' class='btn btn-success' onclick="javascript: VendorSettings.saveMenu()">Save</button>
        </div>
    </div>
</form>
