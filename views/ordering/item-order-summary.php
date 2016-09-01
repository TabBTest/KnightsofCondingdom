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
if(isset($params['AddOnsExclusive'][$orderKey])){
            $menuItemAddOn = VendorMenuItemAddOns::findOne($params['AddOnsExclusive'][$orderKey]);
            $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
            $itemName = $menuItemAddOn->name;
            if(isset($params['AddOnsSpecial'][$orderKey][$addOnId])){
                if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_FULL){
                    $totalAddonAmount =  $quantity * $menuItemAddOn->amountFull;
                    $itemName .= ' - Full';
                }else if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_LEFT_HALF
                    || $params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_RIGHT_HALF){
                    $totalAddonAmount =  $quantity * $menuItemAddOn->amountHalf;
                    if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_LEFT_HALF){
                        $itemName .= ' - Left Half';
                    }else{
                        $itemName .= ' - Right Half';
                    }
                }else if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_ON_THE_SIDE){
                    $totalAddonAmount =  $quantity * $menuItemAddOn->amountSide;
                    $itemName .= ' - On this side';                    
                }
            }
            $finalAmount += $totalAddonAmount;
?>
        <tr>
            <td>Add-ons: <?php echo $quantity?> <?php echo $itemName?></td>
            <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
        </tr>
<?php 
        }    
    
    if(isset($params['AddOns'][$orderKey])){
        
        
        foreach($params['AddOns'][$orderKey] as $addOnId){
            
            $menuItemAddOn = VendorMenuItemAddOns::findOne($addOnId);
            $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
            $itemName = $menuItemAddOn->name;
            if(isset($params['AddOnsSpecial'][$orderKey][$addOnId])){
                if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_FULL){
                    $totalAddonAmount =  $quantity * $menuItemAddOn->amountFull;
                    $itemName .= ' - Full';
                }else if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_LEFT_HALF
                    || $params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_RIGHT_HALF){
                    $totalAddonAmount =  $quantity * $menuItemAddOn->amountHalf;
                    if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_LEFT_HALF){
                        $itemName .= ' - Left Half';
                    }else{
                        $itemName .= ' - Right Half';
                    }
                }else if($params['AddOnsSpecial'][$orderKey][$addOnId] == VendorMenuItemAddOns::SPECIAL_TYPE_ON_THE_SIDE){
                    $totalAddonAmount =  $quantity * $menuItemAddOn->amountSide;
                    $itemName .= ' - On this side';                    
                }
            }
            $finalAmount += $totalAddonAmount;
            
            ?>
        <tr>
            <td>Add-ons: <?php echo $quantity?> <?php echo $itemName?></td>
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