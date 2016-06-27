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
            <th>Name</th>
            <th>Order Details</th>
            <th>Time</th>
            <th>Status</th>
            <th>Confirmed Time</th>
            <th>Start Time</th>
            <th>Picked-up Time</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $orderInfo){?>
        <tr class="" data-id="<?php echo $orderInfo->id?>">
            <td><?php echo $orderInfo->getCustomerName()?></td>
            <td><a href="javascript: Customer.viewOrder(<?php echo $orderInfo->id?>)">See Order</a></td>
            <td><?php echo date('m-d-Y H:i', strtotime($orderInfo->date_created));?></td>
            <td>           
                <?php if($orderInfo->status == Orders::STATUS_PROCESSED){?>
                <i class="fa fa-check alert-success" aria-hidden="true"></i>                
                <?php }else if($orderInfo->status == Orders::STATUS_PENDING){?>
                <i class="fa fa-clock-o alert-warning" aria-hidden="true"></i>
                <?php }else if($orderInfo->status == Orders::STATUS_NEW){?>
                <i class="fa fa-exclamation alert-danger" aria-hidden="true"></i>
                <?php }?>
            </td>
            <td>
                <?php if($orderInfo->date_created != null){?>
                <?php if($orderInfo->confirmedDateTime == null){?>
                <button type='button' class='btn btn-info btn-xs' onclick='javascript: Order.confirm(<?php echo $orderInfo->id?>)'>CONFIRM</button>
                <?php }else{?>
                <?php echo date('m-d-Y H:i', strtotime($orderInfo->confirmedDateTime));?>
                <?php }
                }?>
                
            </td>
            <td>
                <?php if($orderInfo->confirmedDateTime != null){?>
                <?php if($orderInfo->startDateTime == null){?>
                <button type='button' class='btn btn-info btn-xs'  onclick='javascript: Order.start(<?php echo $orderInfo->id?>)'>START</button>
                <?php }else{?>
                <?php echo date('m-d-Y H:i', strtotime($orderInfo->startDateTime));?>
                <?php }
                }?>
            </td>
            <td>
                <?php if($orderInfo->startDateTime != null){?>
                <?php if($orderInfo->pickedUpDateTime == null){?>
                <button type='button' class='btn btn-info btn-xs'  onclick='javascript: Order.pickup(<?php echo $orderInfo->id?>)'>PICKED UP?</button>
                <?php }else{?>
                <?php echo date('m-d-Y H:i', strtotime($orderInfo->pickedUpDateTime));?>
                <?php }
                }?>
            </td>
           
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="vendor-order-history-pagination" data-user-id='<?php echo $userId?>' data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>