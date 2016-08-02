<?php

$params = require(\Yii::$app->basePath . '/config/params.php');

use app\models\VendorMenuItem;
use app\models\Orders;
use app\helpers\TenantHelper;
use app\models\User;
$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
 
?>

<?php echo $this->render('//partials/_show_message', []);?>


<div class="tab-content">
    <div id="tab-sales-order" class="tab-pane active">
        <div id="search-box-sales-orders" class="col-md-7 panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" data-target="#search-box-current-orders-body">
                <h4 class="panel-title">Search Options</h4>
            </div>
            <div id="search-box-current-orders-body" class="collapse in">
            <div class="panel-body">
                <form id="sales-form" class="form-inline">
                    <input type='hidden' name="filter[vendorId]" value='<?php echo $userId?>'/>
                    <div class="form-group">
                        <label class="control-label">Start Date: 
                        <input data-date-format="mm-dd-yyyy" data-date-autoclose='true' data-provide="datepicker" type="text" name="filter[fromDate]" class="form-control" value='<?php echo $fromDateDisplay?>'/></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">End Date: 
                        <input data-date-format="mm-dd-yyyy" data-date-autoclose='true' data-provide="datepicker" type="text" name="filter[toDate]" class="form-control"  value='<?php echo $toDateDisplay?>'/></label>
                    </div>
                    
                    <div class="form-group">
                        <button type="button"
                                class="btn btn-raised btn-primary"
                                onclick="Order.search('sales');">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <div id="search-box-sales-orders" class="col-md-4 col-md-offset-1 panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" data-target="#sales-summary">
                <h4 class="panel-title">Summary</h4>
            </div>
            <div id="sales-summary" class="collapse in panel-body">
                <?php echo $salesSummary?>
                
            </div>
        </div>
        
        <div class="vendor-sales-body" data-user-id='<?php echo $userId?>' data-url='<?php echo $url?>' data-url-summary='<?php echo $urlSummary?>'>
            <?php echo $this->render('_list', ['orders' => $orders, 'userId' => $userId]);?>
        </div>
    </div>
</div>
