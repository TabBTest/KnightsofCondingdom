<?php

$params = require(\Yii::$app->basePath . '/config/params.php');

use app\models\VendorMenuItem;
use app\models\Orders;
use app\helpers\TenantHelper;
use app\models\User;
use app\helpers\UtilityHelper;
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

<?php echo $this->render('//partials/_show_message', []);?>

<div class="row">
    <div class="col-xs-12 text-center">
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>



<ul class="nav nav-tabs">    
    <li class="active"><a data-toggle="tab" href="#tab-current-order">Current Orders</a></li>
    <li><a data-toggle="tab" href="#tab-archived-order">Archived Orders</a></li>
</ul>

<div class="tab-content">
    <div id="tab-current-order" class="tab-pane active">
        <div id="search-box-current-orders" class="col-md-9 panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" data-target="#search-box-current-orders-body">
                <h4 class="panel-title">Search Options</h4>
            </div>
            <div id="search-box-current-orders-body" class="collapse in">
            <div class="panel-body">
                <form id="current-order-form" class="form-inline">
                    <div class="form-group">
                        <label class="control-label">First Name: <input type="text" name="filter[firstName]" class="form-control" /></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Last Name: <input type="text" name="filter[lastName]" class="form-control" /></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Order #: <input type="text" name="filter[orderId]" class="form-control" /></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Type:
                            <select name="filter[isDelivery]" class="form-control">
                                <option value="">All</option>
                                <option value="0">For Pick-up</option>
                                <option value="1">For Delivery</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="control-label">
                                <input value="1" type="checkbox" name="filter[showCompleted]" id="showCompletedOrder"/>
                                Show Completed Orders
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button"
                                class="btn btn-raised btn-primary"
                                onclick="Order.search('current');">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <div class="col-xs-3 form-group pull-right">
            <?php $vendor = User::findOne($userId)?>
            <label class="form-label">Time to Pick-up</label>
            <select data-user-id="<?= $userId?>" class="form-control" name="timeToPickUp">
                <?php foreach(TenantHelper::getTimeToPickUp() as $key => $disp){?>
                    <option <?= $vendor->timeToPickUp == $key ? 'selected' : '' ?>
                        value="<?= $key ?>"><?= $disp ?></option>
                <?php }?>
            </select>
        </div>
        <div class="col-xs-9 form-group">
            <label class="form-label">* Orders older than 24 hours are automatically archived.</label>
        </div>
        <div class="vendor-order-body col-xs-12" data-user-id='<?php echo $userId?>' data-eid='<?php echo UtilityHelper::encodeIdentifier($userId)?>' data-url='<?php echo $url?>'>
            <?php echo $this->render('_list', ['orders' => $orders, 'userId' => $userId]);?>
        </div>
    </div>
    <div id="tab-archived-order" class="tab-pane fade">
        <div id="search-box-archived-orders" class="col-md-9 panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" data-target="#search-box-archived-orders-body">
                <h4 class="panel-title">Search Options</h4>
            </div>
            <div id="search-box-archived-orders-body" class="collapse in">
            <div class="panel-body">
                <form id="archived-order-form" class="form-inline">
                    <div class="form-group">
                        <label class="control-label">First Name: <input type="text" name="filter[firstName]" class="form-control" /></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Last Name: <input type="text" name="filter[lastName]" class="form-control" /></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Order #: <input type="text" name="filter[orderId]" class="form-control" /></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Type:
                            <select name="filter[isDelivery]" class="form-control">
                                <option value="">All</option>
                                <option value="0">For Pick-up</option>
                                <option value="1">For Delivery</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="control-label">
                                <input value="1" type="checkbox" name="filter[showCompleted]" id="showCompletedOrder"/>
                                Show Completed Orders
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button"
                                class="btn btn-raised btn-primary"
                                onclick="Order.search('archived');">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <div class="vendor-order-archived-body col-xs-12"
             data-user-id="<?= $userId ?>"
             data-eid='<?php echo UtilityHelper::encodeIdentifier($userId)?>'
             data-url="<?= $urlArchive ?>">
            <?= $this->render('_archive_list', ['orders' => $archivedOrders, 'userId' => $userId]) ?>
        </div>
    </div>
</div>
