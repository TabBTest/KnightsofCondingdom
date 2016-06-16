<?php
use app\models\VendorMenuItem; 
use app\helpers\UtilityHelper;
?>
<form action='/ordering/save' method='POST'>

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
                $vendorMenuItem = VendorMenuItem::findOne($detail['menuItemId']);
                $totalAmount = intval($detail['quantity']) * $vendorMenuItem->amount;
                $finalTotalAmount += $totalAmount;
            ?>
            <input type='hidden' name='Orders[<?php echo $detail['menuItemId']?>]' value='<?php echo $detail['quantity']?>' />
            <tr>
                <td><?php echo $vendorMenuItem->name?></td>
                <td>$<?php echo UtilityHelper::formatAmountForDisplay($vendorMenuItem->amount)?></td>
                <td><?php echo $detail['quantity']?></td>
                <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAmount)?></td>
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
        <button type='button' class='btn btn-default' data-dismiss="modal">Cancel</button>
        <button class='btn btn-success'>Submit</button>
    </div>
</div>
</form>