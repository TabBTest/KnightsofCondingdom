<?php 

use app\models\VendorMenuItem;
use app\models\Orders;
use app\models\User;
$this->title = 'Customer Management';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('//partials/_show_message', []);?>

<form class="form-inline" id="customer-search-form">
    <div class="form-group">
        <label class="control-label">First Name:</label>
        <input type="text" name="filter[firstName]" class="form-control" />
    </div>
    <div class="form-group">
        <label class="control-label">Last Name:</label>
        <input type="text" name="filter[lastName]" class="form-control" />
    </div>
    <div class="form-group">
        <label class="control-label">Email:</label>
        <input type="text" name="filter[email]" class="form-control" />
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary btn-raised" onclick="Customer.search();">Search</button>
    </div>
</form>

<div class="col-xs-12 vendor-customer-body">
    <?php echo $this->render('_list', ['customers' => User::getVendorCustomers(\Yii::$app->user->id, 20, 1, [])]);?>
</div>

<style>
    #customer-search-form > .form-group {
        margin-right: 10px;
    }
</style>
