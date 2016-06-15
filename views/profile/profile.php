<?php 

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
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
        <input type='text' class='form-control' name='User[name]' value='<?php echo $model->name?>'/>
    </div>
    <div class='col-xs-12 form-group'>
        <label>Address</label>
        <input type='text' class='form-control' name='User[address]'  value='<?php echo $model->address?>'/>
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