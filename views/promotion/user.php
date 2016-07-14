<div class='row'>
    <div class='col-xs-12 form-group'>
        <label>Send To: </label>
        <input type='radio' name='send-to' class='send-to-all' value='1'/>&nbsp;All&nbsp;&nbsp;&nbsp;
        <input type='radio' name='send-to' value='0'/>&nbsp;Specific Users
        <table class='table table-condensed table-striped customer-list'>
            
        </table>
    </div>

    <form id='promotion-user-search-form'>    
        <div class='col-xs-12 form-group'>
            <label>First Name:&nbsp;&nbsp; <input type='text' name='filter[firstName]' class='form-control' /> </label>
            <label>Last Name:&nbsp;&nbsp; <input type='text' name='filter[lastName]' class='form-control' /> </label>
            <label>Email:&nbsp;&nbsp; <input type='text' name='filter[email]' class='form-control' /> </label>
            <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-info' onclick='Customer.searchPromo();'>Search</button></label>
            <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-success pull-right' onclick='Customer.addPromoCustomer();'>Add To List</button></label>
        </div>
    </form>
    
    <div class="col-xs-12 promotion-user-body">
        <?php echo $this->render('_user_list', ['customers' => $customers]);?>
    </div>
</div>
<div class='row'>
    <div class='col-xs-12 form-group'>
        <button type='button' class='btn btn-info' data-dismiss='modal'>Cancel</button>
        <button type='button' class='btn btn-success' onclick='Customer.sendPromoNow("<?php echo $type?>");'>Send Now</button>
    </div>

</div>