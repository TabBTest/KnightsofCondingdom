<?php 

use app\helpers\UtilityHelper;
$totalFoodCost = 0;
$totalSalesTax = 0;
$totalDeliveryCharge = 0;
$totalDiscount = 0;
$totalWebFee = 0;
$totalCCFee = 0;
$totalGrandTotal = 0;
$totalFaxAttempts = 0;
foreach($orders['list'] as $order){
    $totalFoodCost += $order->getFoodCost();
    $totalSalesTax += $order->getSalesTax();
    $totalDeliveryCharge += $order->getDeliveryCharge();
    $totalDiscount += $order->getDiscount();
    $totalWebFee += $order->getWebFee();
    $totalCCFee += $order->getCCFee();
    $totalGrandTotal += $order->getTotalReceivableCost();
    $totalFaxAttempts += $order->getTotalFaxAttempts();
}
?>

<table class='table table-condensed table-striped summary'>
    <tbody>
        <tr>
            <td>Total Faxes</td>
            <td class='amounts'><?php echo $totalFaxAttempts?></td>
        </tr>
        <tr>
            <td>Total Food Cost</td>
            <td class='amounts'>$<?php echo UtilityHelper::formatAmountForDisplay($totalFoodCost)?></td>
        </tr>
        <tr>
            <td>Total Sales Tax</td>
            <td class='amounts'>$<?php echo UtilityHelper::formatAmountForDisplay($totalSalesTax)?></td>
        </tr>
        <tr>
            <td>Total Delivery Charge</td>
            <td class='amounts'>$<?php echo UtilityHelper::formatAmountForDisplay($totalDeliveryCharge)?></td>
        </tr>
        <tr>
            <td>Total Discount</td>
            <td class='amounts'>($<?php echo UtilityHelper::formatAmountForDisplay($totalDiscount)?>)</td>
        </tr>
        <tr>
            <td>Total Admin Fee</td>
            <td class='amounts'>($<?php echo UtilityHelper::formatAmountForDisplay($totalWebFee)?>)</td>
        </tr>
        <tr>
            <td>Total CC Fee</td>
            <td class='amounts'>($<?php echo UtilityHelper::formatAmountForDisplay($totalCCFee)?>)</td>
        </tr>
        <tr>
            <td>Grand Total</td>
            <td class='amounts'>$<?php echo UtilityHelper::formatAmountForDisplay($totalGrandTotal)?></td>
        </tr>
        
    </tbody>
</table>