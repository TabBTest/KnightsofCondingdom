<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
$('#select-state').val('$model->state');
JS;

$this->registerJs($js, $this::POS_READY);
?>

<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
 <div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
 </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('success')){?>
 <div class="">
<div class="alert alert-success">
    <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
</div>
 </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('warning')){?>
 <div class="">
<div class="alert alert-warning">
    <?php echo \Yii::$app->getSession()->getFlash('warning'); ?>
</div>
 </div>
<?php } ?>
<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1>Profile</h1>
    </div>
</div>

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
                <option value="" selected disabled hidden>State</option>
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="DC">DC</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>
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
