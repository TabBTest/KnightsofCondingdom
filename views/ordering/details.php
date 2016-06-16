<?php
use app\models\VendorMenuItem; 
use app\helpers\UtilityHelper;
?>

<div class='row form-group'>
    <div class='col-xs-12'>
        <table class='table table-condensed'>
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $finalTotalAmount = 0;
            foreach($orders as $detail){
                $finalTotalAmount +=  $detail->totalAmount;
            ?>
            <tr>
                <td><?php echo $detail->name?></td>
                <td>$<?php echo UtilityHelper::formatAmountForDisplay($detail->amount)?></td>
                <td><?php echo $detail->quantity?></td>
                <td>$<?php echo UtilityHelper::formatAmountForDisplay($detail->totalAmount)?></td>
            </tr>
            <?php }?>
            <tr>
                <td colspan='3'><label class='pull-right'>Final Total Amount</label></td>
                <td><label>$<?php echo UtilityHelper::formatAmountForDisplay($finalTotalAmount)?></label></td>
            </tr>
        </tbody>
        </table>
    </div>
</div>

<div class='row form-group'>
    <div class='col-xs-12 text-center'>
        <button type='button' class='btn btn-default' data-dismiss="modal">Close</button>
    </div>
</div>