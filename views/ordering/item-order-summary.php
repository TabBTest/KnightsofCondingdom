<?php 
use app\helpers\UtilityHelper;
use app\models\VendorMenuItem;
use app\models\VendorMenuItemAddOns;


?>

<table class='table table-condensed'>
    <tbody>
<?php 
$finalAmount = 0;
foreach($params['Orders'] as $orderKey => $menuItemId){
    $quantity = $params['OrdersQuantity'][$orderKey];
    $menuItem = VendorMenuItem::findOne($menuItemId);
    $totalAmount =  $quantity * $menuItem->amount;
    $finalAmount += $totalAmount;
?>
    <tr>
        <td><?php echo $quantity?> <?php echo $menuItem->name?></td>
        <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAmount)?></td>
    </tr>
    
    
<?php
    if(isset($params['AddOns'][$orderKey])){
        foreach($params['AddOns'][$orderKey] as $addOnId){
            
            $menuItemAddOn = VendorMenuItemAddOns::findOne($addOnId);
            $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
            $finalAmount += $totalAddonAmount;
            ?>
        <tr>
            <td>Add-ons: <?php echo $quantity?> <?php echo $menuItemAddOn->name?></td>
            <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
        </tr>
    <?php 
        }
    } 
}

?>
<tr>
    <td><label class='form-label'>Total</label></td>
    <td><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($finalAmount)?></label></td>
</tr>
</tbody>
</table>