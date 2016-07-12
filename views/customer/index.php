<?php 

use app\models\VendorMenuItem;
use app\models\Orders;
use app\models\User;
$this->title = 'Customer Management';
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

<form id='customer-search-form'>    
    <div class='col-xs-12 form-group'>
        <label>First Name:&nbsp;&nbsp; <input type='text' name='filter[firstName]' class='form-control' /> </label>
        <label>Last Name:&nbsp;&nbsp; <input type='text' name='filter[lastName]' class='form-control' /> </label>
        <label>Email:&nbsp;&nbsp; <input type='text' name='filter[email]' class='form-control' /> </label>
        <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-info' onclick='Customer.search();'>Search</button></label>
    </div>
</form>

<div class="col-xs-12 vendor-customer-body">
    <?php echo $this->render('_list', ['customers' => User::getVendorCustomers(\Yii::$app->user->id, 20, 1, [])]);?>
</div>