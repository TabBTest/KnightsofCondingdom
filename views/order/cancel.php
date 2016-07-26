<form action='/order/cancel' method='POST' enctype="multipart/form-data" id='cancel-form'>
    <input type='hidden' name='id' value='<?php echo $orderId?>'/>
    <div class='row'>
        
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Reason</label>
            <textarea class='form-control' name='reason' rows='5' cols='20' placeholder='Reason here'></textarea>
        </div>
       
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn' data-dismiss="modal">Close</button>
           
            <button type='button' class='btn btn-success' onclick="javascript: Order.cancelNow()">Cancel</button>
        </div>
    </div>
</form>
