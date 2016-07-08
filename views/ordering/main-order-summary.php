<?php 
use app\helpers\UtilityHelper;
use app\models\VendorMenuItem;
use app\models\VendorMenuItemAddOns;
use app\helpers\TenantHelper;
use app\models\TenantInfo;
use app\models\Orders;
use app\models\AppConfig;
if(isset($params['Orders'])){                         
?>
<div class='col-xs-12 text-center'>
<table class='table table-condensed'>
    <tbody>
<?php 
$finalAmount = 0;

    foreach($params['Orders'] as  $orderKey => $menuItemId){
        $quantity = $params['OrdersQuantity'][$orderKey];
        $menuItem = VendorMenuItem::findOne($menuItemId);
        $totalAmount =  $quantity * $menuItem->amount;
        $finalAmount += $totalAmount;
    ?>
        <tr class='order-<?php echo $orderKey?>'>
            <td>
            <a href='javascript: void(0)' class='delete-order-item' data-key='<?php echo $orderKey?>'>
            <i class="fa fa-times" aria-hidden="true"></i>
            </a>
            </td>
            <td>
            <input type='hidden' name='Orders[<?php echo $orderKey?>]' value='<?php echo $menuItem->id?>' />
            <input type='hidden' name='OrdersQuantity[<?php echo $orderKey?>]' value='<?php echo $quantity?>'/>
            
            <?php if(isset($params['OrdersNotes'][$orderKey])){?>
                <input type='hidden' name='OrdersNotes[<?php echo $orderKey?>]' value='<?php echo $params['OrdersNotes'][$orderKey]?>' />
            <?php }?>
            <?php echo $quantity?> <?php echo $menuItem->name?></td>
            <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAmount)?></td>
        </tr>
        
        
    <?php
        if(isset($params['AddOns'][$orderKey])){
            foreach($params['AddOns'][$orderKey] as $addOnId => $elem){
                $menuItemAddOn = VendorMenuItemAddOns::findOne($addOnId);
                $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
                $finalAmount += $totalAddonAmount;
                ?>
            <tr class='order-<?php echo $orderKey?>'>
                <td></td>
                <td style='padding-left: 20px;'>
                <input type='hidden' name='AddOns[<?php echo $orderKey?>][<?php echo $menuItemAddOn->id?>]' value='<?php echo $quantity?>' />
                Add-ons: <?php echo $quantity?> <?php echo $menuItemAddOn->name?></td>
                <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
            </tr>
        <?php 
            }
        } 
    }
    $totalFinalAmount = $finalAmount * $salesTax;
    $salesTax = $totalFinalAmount - $finalAmount;
?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>    
    Sales Tax</label></td>
    <td><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($salesTax)?></label></td>
</tr>
<?php 
$adminFee = floatval(UtilityHelper::getAppConfig(AppConfig::ADMIN_FEE, 0));
$totalFinalAmount += $adminFee;
?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>    
    Web Fee</label></td>
    <td><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($adminFee)?></label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>Total</label></td>
    <td><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($totalFinalAmount)?></label></td>
</tr>
</tbody>
</table>
</div>
<div class='col-xs-12'>
    <label>Instructions</label>
    <textarea class='form-control' rows='5' cols='25' name='notes' placeholder='Please add your extra instructions here...'><?php echo isset($params['notes']) ? $params['notes'] : ''?></textarea>    
</div>
<div class='col-xs-12'>
    <label>How do you want to pay?</label>
            
</div>
<div class='col-xs-12'>
    <label>
        <input type='radio' value='<?php echo Orders::PAYMENT_TYPE_CARD?>' <?php echo !isset($params['paymentType']) || (isset($params['paymentType']) && $params['paymentType'] == Orders::PAYMENT_TYPE_CARD) ? 'checked' : ''?> name='paymentType'/>&nbsp;&nbsp;Card
    </label>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <label>
        <input type='radio' value='<?php echo Orders::PAYMENT_TYPE_CASH?>' <?php echo (isset($params['paymentType']) && $params['paymentType'] == Orders::PAYMENT_TYPE_CASH) ? 'checked' : ''?> name='paymentType'/>&nbsp;&nbsp;Cash
    </label>        
</div>
<br />
<div class='col-xs-12 text-center' style='margin: 10px 0;'>
    <button class='btn btn-success'>Order Now</button>
</div>
<?php }else{?>
<div class='col-xs-12 text-center'>
    <label>Please add your order now</label>
</div>
<?php }?>