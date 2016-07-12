<?php 
use app\models\User;
use app\helpers\UtilityHelper;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
?>
<div id="tab-profile" class="tab-pane fade <?php echo isset($show) && $show ? 'in active' : ''?>">
    <br>
    <form action='/profile/save' method='POST' enctype="multipart/form-data">
        <?php
        $userId = $model->id;
        ?>
        <input type='hidden' value='<?php echo $userId?>' name='userId'/>
         <?php if(Yii::$app->session->get('role') == User::ROLE_ADMIN){?>
         <div class='col-xs-12 form-group'>
            <label>Is Active?</label>
            <input type='hidden'  name='User[isActive]' value='0'/>
            <input type='checkbox' class='' name='User[isActive]' <?php echo $model->isActive == 1 ? 'checked' : ''?> value='1'/>
        </div>
        <?php }?>
        <div class="col-xs-12 form-group">
            <img src="/images/users/<?= $model->imageFile ?>" id="logo-thumbnail" class="img-rounded">
        </div>
        <div class="col-xs-12 form-group field-user-imagefile">
            <label class="control-label" for="user-imagefile">Logo</label>
            <input type="hidden" name="User[imageFile]" value=""><input type="file" id="user-imagefile" name="User[imageFile]" accept="image/jpeg|image/png">
        </div>
        <?php 
        $prefix = '';
        if($model->role == User::ROLE_VENDOR){
        $prefix = 'Business Owner - ';
        ?>
        <div class='col-xs-12 form-group'>
            <label>Business Name</label>
            <input type='text' class='form-control' name='User[businessName]' value='<?= $model->businessName?>'/>
        </div>
        
        <?php }?>
        <div class='col-xs-6 form-group'>
            <label><?php echo $prefix?>First Name</label>
            <input type='text' class='form-control' name='User[firstName]' value='<?= $model->firstName?>'/>
        </div>
        <div class='col-xs-6 form-group'>
            <label><?php echo $prefix?>Last Name</label>
            <input type='text' class='form-control' name='User[lastName]' value='<?= $model->lastName?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label>Street Address</label>
            <input type='text' class='form-control' name='User[streetAddress]'  value='<?= $model->streetAddress?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label>City</label>
            <input type='text' class='form-control' name='User[city]'  value='<?= $model->city?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label>State</label>
            <select class='form-control' id='select-state' name='User[state]'>
                <option value="">State</option>
                <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                    <option value="<?php echo $stateCode?>" <?php echo $stateCode == $model->state ? 'selected' : ''?>><?php echo $stateCode?></option>
                <?php }?>
            </select>
        </div>
        <div class='col-xs-12 form-group' style='margin: 0'>
            <label>Phone</label>            
        </div>
        <div class='col-xs-12 col-md-2 form-group phone' data-key='phone'>
            <input type='tel' class='form-control short-input' name='User[phoneAreaCode]' value='<?php echo $model->phoneAreaCode?>' maxlength="3" placeholder='Area Code'/>
        </div>
        <div class='col-xs-12 col-md-2 form-group phone' data-key='phone'>
            <input type='tel' class='form-control short-input' name='User[phone3]' value='<?php echo $model->phone3?>' maxlength="3" placeholder='XXX'/>
        </div>
        <div class='col-xs-12 col-md-2 form-group phone' data-key='phone'>
            <input type='tel' class='form-control short-input' name='User[phone4]' value='<?php echo $model->phone4?>' maxlength="4" placeholder='XXXX'/>
        </div>
        
        

        <div class='col-xs-12 form-group'>
            <label>Email</label>
            <input type='text' class='form-control' name='User[email]' id='email'  value='<?php echo $model->email?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label>Preferred Timezone</label>
            <select name='User[timezone]' required class='form-control'>
                <option value="">Select Timezone</option>
                <?php foreach(UtilityHelper::getAvailableTimezones() as $key => $timezone){?>
                    <option <?php echo $model->timezone == $timezone['timezone'] ? 'selected' : ''?> value="<?php echo $timezone['timezone']?>"><?php echo $timezone['textDisplay']?></option>
                <?php }?>
            </select>
           
        </div>
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn btn-info btn-change-password' data-id='<?php echo md5($model->id)?>'>Change Password</button>
        </div>
        
        <div class='col-xs-12 form-group text-center'>
            <button class='btn btn-success'>Save</button>
        </div>

    </form>
</div>