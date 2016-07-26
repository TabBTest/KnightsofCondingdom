<?php

$params = require(\Yii::$app->basePath . '/config/params.php');

use app\models\VendorMenuItem;
use app\models\Orders;
use app\helpers\TenantHelper;
use app\models\User;
$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
 
?>

<?= $this->render('//partials/_show_message', []) ?>

<div class="row">
    <div class="col-xs-12 text-center">
        <h1><?= Yii::$app->session->get('name') ?></h1>
    </div>
</div>

<div id="search-box-sales-orders" class="col-md-5 panel panel-primary">
    <div class="panel-heading" data-toggle="collapse" data-target="#search-box-current-orders-body">
        <h4 class="panel-title">Search Options</h4>
    </div>
    <div id="search-box-current-orders-body" class="collapse in panel-body">
        <form id="sales-form" class="form-inline">
            <input type="hidden" name="filter[vendorId]" value="<?= $userId ?>" />
            <div class="form-group">
                <label class="control-label">Start Date:
                    <input data-date-format="mm-dd-yyyy"
                           data-date-autoclose="true"
                           data-provide="datepicker"
                           type="text"
                           name="filter[fromDate]"
                           class="form-control"
                           value="<?= $fromDateDisplay ?>" />
                </label>
            </div>
            <div class="form-group">
                <label class="control-label">End Date:
                    <input data-date-format="mm-dd-yyyy"
                           data-date-autoclose="true"
                           data-provide="datepicker"
                           type="text"
                           name="filter[toDate]"
                           class="form-control"
                           value="<?= $toDateDisplay ?>" />
                </label>
            </div>
            <div class="form-group">
                <button type="button"
                        class="btn btn-raised btn-primary"
                        onclick="Order.search('sales')">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<div id="sales-summary" class="col-md-offset-1 col-md-6 panel panel-primary">
    <div class="panel-heading" data-toggle="collapse" data-target="#sales-summary-body">
        <h4 class="panel-title">Sales Summary</h4>
    </div>
    <div id="sales-summary-body" class="collapse in panel-body">
        Sample Data here
    </div>
</div>

<div class="col-md-12 vendor-sales-body" data-user-id="<?= $userId ?>" data-url="<?= $url?>">
    <?= $this->render('_list', ['orders' => $orders, 'userId' => $userId]) ?>
</div>
