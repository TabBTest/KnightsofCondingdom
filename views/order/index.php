<?php

$params = require(\Yii::$app->basePath . '/config/params.php');

use app\models\VendorMenuItem;
use app\models\Orders;
$this->title = 'Order Management';
$this->params['breadcrumbs'][] = $this->title;

$pageJs = <<<JS
var socket = io('{$params['nodejs_host']}' + ':' + '{$params['nodejs_port']}');

socket.on('orders:newOrder', function() {
  console.log('a new order was received.');
});
JS;

//$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.8/socket.io.min.js');
//$this->registerJs($pageJs, $this::POS_READY);
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
        <form id='current-order-form'>
            <div class='col-xs-12 form-group'>
                <label> <input value='1' type='checkbox' name='filter[showCompleted]' id='showCompletedOrder'/>&nbsp;&nbsp;Show Completed Order</label>
            </div>
            <div class='col-xs-12 form-group'>
                <label>Name:&nbsp;&nbsp; <input type='text' name='filter[name]' class='form-control' /> </label>
                <label>Order #:&nbsp;&nbsp; <input type='text' name='filter[orderId]' class='form-control' /> </label>
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
                <label>Name:&nbsp;&nbsp; <input type='text' name='filter[name]' class='form-control' /> </label>
                <label>Order #:&nbsp;&nbsp; <input type='text' name='filter[orderId]' class='form-control' /> </label>
                <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-info' onclick='Order.search("archived");'>Search</button></label>
            </div>
        </form>
        <div class="col-xs-12 vendor-order-archive-body"  data-user-id='<?php echo $userId?>' data-url='<?php echo $urlArchive?>'>
            <?php echo $this->render('_archive_list', ['orders' => $archivedOrders, 'userId' => $userId]);?>
        </div>
    </div>
</div>