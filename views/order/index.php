<?php

$params = require(\Yii::$app->basePath . '/config/params.php');

use app\models\VendorMenuItem;
use app\models\Orders;
use app\helpers\TenantHelper;
use app\models\User;
$this->title = 'Order Management';
$this->params['breadcrumbs'][] = $this->title;

$pageJs = <<<JS
var socket = io('{$params['nodejs_host']}' + ':' + '{$params['nodejs_port']}');

socket.on('orders:new_order', function() {
  Order.loadVendor();
});
JS;

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.8/socket.io.min.js');
$this->registerJs($pageJs, $this::POS_READY);
 
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

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>



<ul class="nav nav-tabs">    
    <li class="active"><a data-toggle="tab" href="#tab-current-order">Current Orders</a></li>
    <li><a data-toggle="tab" href="#tab-archive-order">Archive Orders</a></li>
</ul>

<div class="tab-content">
    <div id="tab-current-order" class="tab-pane active" style='margin-top: 10px'>
        
        <div class='col-xs-6 form-group'>
            <label class='form-label'>* Orders older than 24 hours are automatically archived.</label>
        </div>
        <div class=' col-xs-3  form-group pull-right'>
        <?php $vendor = User::findOne($userId)?>
                <select data-user-id='<?php echo $userId?>' class='form-control' name='timeToPickUp'>
                    <?php foreach(TenantHelper::getTimeToPickUp() as $key => $disp){?>
                    <option <?php echo $vendor->timeToPickUp == $key ? 'selected' : ''?> value='<?php echo $key?>'><?php echo $disp?></option>
                    <?php }?>
                </select>                
        </div>
        <div class='col-xs-offset-1 col-xs-2 form-group pull-right'>
            <label class='form-label'>Time to Pick-up</label>
        </div>
        
        <form id='current-order-form'>
            <div class='col-xs-12 form-group'>
                <label> <input value='1' type='checkbox' name='filter[showCompleted]' id='showCompletedOrder'/>&nbsp;&nbsp;Show Completed Order</label>
            </div>
            <div class='col-xs-12 form-group'>
                <label>First Name:&nbsp;&nbsp; <input type='text' name='filter[firstName]' class='form-control' /> </label>
                <label>Last Name:&nbsp;&nbsp; <input type='text' name='filter[lastName]' class='form-control' /> </label>
                <label>Order #:&nbsp;&nbsp; <input type='text' name='filter[orderId]' class='form-control' /> </label>
                <label>Type #:&nbsp;&nbsp; <select name='filter[isDelivery]' class='form-control' ><option value=''>All</option><option value='0'>For Pick-up</option><option value='1'>For Delivery</option></select> </label>
                <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-info' onclick='Order.search("current");'>Search</button></label>
            </div>
            
        </form>
        
       
        <div class="col-xs-12 vendor-order-body" data-user-id='<?php echo $userId?>' data-url='<?php echo $url?>'>
            <?php echo $this->render('_list', ['orders' => $orders, 'userId' => $userId]);?>
        </div>
    </div>
    <div id="tab-archive-order" class="tab-pane fade" style='margin-top: 10px'>
        <form id='archived-order-form'>
            
            <div class='col-xs-12 form-group'>
                <label>First Name:&nbsp;&nbsp; <input type='text' name='filter[firstName]' class='form-control' /> </label>
                <label>Last Name:&nbsp;&nbsp; <input type='text' name='filter[lastName]' class='form-control' /> </label>
                <label>Order #:&nbsp;&nbsp; <input type='text' name='filter[orderId]' class='form-control' /> </label>
                <label>Type #:&nbsp;&nbsp; <select name='filter[isDelivery]' class='form-control' ><option value=''>All</option><option value='0'>For Pick-up</option><option value='1'>For Delivery</option></select> </label>
                <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-info' onclick='Order.search("archived");'>Search</button></label>
            </div>
        </form>
        <div class="col-xs-12 vendor-order-archive-body"  data-user-id='<?php echo $userId?>' data-url='<?php echo $urlArchive?>'>
            <?php echo $this->render('_archive_list', ['orders' => $archivedOrders, 'userId' => $userId]);?>
        </div>
    </div>
</div>
