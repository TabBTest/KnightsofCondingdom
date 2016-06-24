<?php 
use app\models\User;
use app\helpers\UtilityHelper;
use yii\widgets\MaskedInput;
?>
<div id="tab-profile" class="tab-pane fade <?php echo isset($show) && $show ? 'in active' : ''?>">
    <br>
    <form action='/profile/save' method='POST'>
        <?php
        $userId = \Yii::$app->user->id;
        ?>
        <div class='col-xs-12 form-group'>
            <label>Name</label>
            <input type='text' class='form-control' name='User[name]' value='<?= $model->name?>'/>
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
        <div class='col-xs-12 form-group'>
            <label>Phone</label>
            <?php
            echo MaskedInput::widget([
                'name' => 'User[phoneNumber]',
                'mask' => '999-999-9999',
                'value' => $model->phoneNumber
            ]);
            ?>
        </div>

        <div class='col-xs-12 form-group'>
            <label>Email</label>
            <input type='text' class='form-control' name='User[email]' id='email'  value='<?php echo $model->email?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label>New Password</label>
            <input type='password' class='form-control' name='password'  value=''/>
        </div>
        <div class='col-xs-12 form-group'>
            <label>Confirm New Password</label>
            <input type='password' class='form-control' name='confirmPassword'  value=''/>
        </div>
        <div class='col-xs-12 form-group text-center'>
            <button class='btn btn-success'>Save</button>
        </div>

    </form>
    
</div>