<?php

$this->title = 'Accounts Payable Summary Details';
$this->params['breadcrumbs'][] = $this->title;
 
?>

<?php echo $this->render('//partials/_show_message', []);?>

<div class="row">
    <div class="col-xs-12 text-center">
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>

<div id="search-box-sales-orders" class="col-md-5 panel panel-primary">
    <div class="panel-heading" data-toggle="collapse" data-target="#search-box-current-orders-body">
        <h4 class="panel-title">Search Options</h4>
    </div>
    <div id="search-box-current-orders-body" class="collapse in panel-body">
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

<div id="receivable-summary" class="col-md-offset-1 col-md-6 panel panel-primary">
    <div class="panel-heading" data-toggle="collapse" data-target="#receivable-summary-body">
        <h4 class="panel-title">Accounts Receivable Summary</h4>
    </div>
    <div id="receivable-summary-body" class="collapse in panel-body">
        Sample Data here
    </div>
</div>
        
        
        <div class="vendor-sales-body" data-user-id='<?php echo $userId?>' data-url='<?php echo $url?>'>
            <?php echo $this->render('_payable-summary-details-list', ['orders' => $orders, 'userId' => $userId]);?>
        </div>
    </div>
</div>
