<?php 
use app\models\Orders;
use app\helpers\UtilityHelper;
$list = $orders['list'];
$totalCount = $orders['count'];
?>


<?php if($totalCount == 0){?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Order #</th>
            <th>Is Paid?</th>
            <th>Type</th>
            <th>Time</th>
            <th>Status</th>
            <th>Confirmed Time</th>
            <th>Start Time</th>
            <th>Picked-up Time</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan='11'>No Orders</td>
        </tr>
    </tbody>
</table>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Order #</th>
            <th>Confirmed Time</th>
            <th>Food Cost</th>
            <th>Sales Tax</th>
            <th>Delivery Charge</th>
            <th>Web Fee</th>
            <th>Discount</th>
            <th>Total Cost</th>
            <th>Credit Card Fee</th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $orderInfo){
        $customer = $orderInfo->getCustomer();
            ?>
        <tr class="" data-id="<?php echo $orderInfo->id?>">           
            <td><a href="javascript: Customer.viewOrder(<?php echo $orderInfo->id?>)"><?php echo $orderInfo->getOrderId()?></a></td>
            <td>
                <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->confirmedDateTime);?>
            </td>
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getFoodCost());?>
            </td>
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getSalesTax());?>
            </td>
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getDeliveryCharge());?>
            </td>
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getWebFee());?>
            </td>
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getDiscount());?>
            </td>
            
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getTotalReceivableCost());?>
            </td>
            <td>
                $ <?php echo UtilityHelper::formatAmountForDisplay($orderInfo->getCCFee());?>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="admin-receivable-pagination" data-user-id='<?php echo $userId?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>
