<?php 

use yii\helpers\Html;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts Payable Summary';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class='row'>
    <div class="col-xs-12">
    <div id="search-box-payable-orders" class="col-md-12 panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" data-target="#search-box-current-orders-body">
                <h4 class="panel-title">Search Options</h4>
            </div>
            <div id="search-box-current-orders-body" class="collapse in panel-body">
                
             <form id='payable-user-search-form' class='form-inline'>    
                
    
                    <div class="form-group">
                        <label class="control-label">Business Name:  </label>
                        <input type='text' name='filter[businessName]' class='form-control' /> 
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">First Name:  </label>
                        <input type='text' name='filter[firstName]' class='form-control' /> 
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Last Name:  </label>
                        <input type='text' name='filter[lastName]' class='form-control' /> 
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Email: </label>
                        <input type='text' name='filter[email]' class='form-control' /> 
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Start Date:  </label>
                        <input data-date-format="mm-dd-yyyy" data-date-autoclose='true' data-provide="datepicker" type="text" name="filter[fromDate]" class="form-control" value='<?php echo $fromDateDisplay?>'/></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">End Date:  </label>
                        <input data-date-format="mm-dd-yyyy" data-date-autoclose='true' data-provide="datepicker" type="text" name="filter[toDate]" class="form-control"  value='<?php echo $toDateDisplay?>'/></label>
                    </div>
                    
                    <div class="form-group">
                        <button type="button"
                                class="btn btn-raised btn-primary"
                                onclick="Vendors.searchPayable();">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
   
    
    <div class="col-xs-12 payable-user-body">
        <?php echo $this->render('_user_payable_list', ['vendors' => $vendors, 'fromDate' => $fromDateDisplay, 'toDate' => $toDateDisplay]);?>
    </div>
</div>