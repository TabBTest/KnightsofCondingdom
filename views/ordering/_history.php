<?php 
use app\models\Orders;
$list = $orders['list'];
$totalCount = $orders['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Orders</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Order Details</th>
            <th>Total Amount</th>
            <th>Time</th>
            <th>Transaction ID</th>
            <th>Status</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $orderInfo){?>
        <tr class="" data-id="<?php echo $orderInfo->id?>">
            <td><a href="javascript: Customer.viewOrder(<?php echo $orderInfo->id?>)">See Order</a></td>
            <td>$<?php echo $orderInfo->getTotalAmount()?></td>
            <td><?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->date_created );?></td>
            <td><?php echo  $orderInfo->transactionId;?></td>
            <td>           
                <?php if($orderInfo->status == Orders::STATUS_PROCESSED){?>
                <i class="fa fa-check alert-success" aria-hidden="true"></i>                
                <?php }else{?>
                <i class="fa fa-clock-o alert-warning" aria-hidden="true"></i>
                <?php }?>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="customer-order-history-pagination" data-user-id='<?php echo $userId?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>