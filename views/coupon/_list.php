<?php 
use app\models\Orders;
$list = $orders['list'];
$totalCount = $orders['count'];
?>


<?php if($totalCount == 0){?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Order #</th>
            <th>Is Paid?</th>
            <th>Type</th>
            <th>Time</th>
            <th>Status</th>
            <th>Confirmed Time</th>
            <th>Start Time</th>
            <th>Picked-up Time</th>
            <th>Action</th>          
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
            <th>First Name</th>
            <th>Last Name</th>
            <th>Order #</th>
            <th>Is Paid?</th>
            <th>Type</th>
            <th>Time</th>
            <th>Status</th>
            <th>Confirmed Time</th>
            <th>Start Time</th>
            <th>Picked-up Time</th>
            <th>Action</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $couponOrder){
            $orderInfo = $couponOrder->getOrder();
        $customer = $orderInfo->getCustomer();
            ?>
        <tr class="" data-id="<?php echo $orderInfo->id?>">
            <td><?php echo $customer->firstName?></td>
            <td><?php echo $customer->lastName?></td>
            <td><a href="javascript: Customer.viewOrder(<?php echo $orderInfo->id?>)"># <?php echo $orderInfo->getOrderId()?></a></td>
            <td>
            <?php if($orderInfo->isPaid == 1){
            ?>
                <i data-toggle="tooltip" data-placement="left" title="Order Paid" class="fa fa-check alert-success" aria-hidden="true"></i>
            <?php 
            }else{
            ?>
                <i data-toggle="tooltip" data-placement="left" title="Unpaid Order" class="fa fa-times alert-danger" aria-hidden="true"></i>
            <?php 
            }?>
            </td>
            <td>
            <?php if($orderInfo->isDelivery == 1){
            ?>
                <i data-toggle="tooltip" data-placement="left" title="For Delivery" class="fa fa-truck" aria-hidden="true"></i>
            <?php 
            }else{
            ?>
                <i data-toggle="tooltip" data-placement="left" title="For Pickup" class="fa fa-shopping-cart" aria-hidden="true"></i>
            <?php 
            }?>
            </td>
            <td><?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->date_created );?></td>
            <td>           
                <?php if($orderInfo->status == Orders::STATUS_PROCESSED){?>
                <i data-toggle="tooltip" data-placement="left" title="Processed Order" class="fa fa-check alert-success" aria-hidden="true"></i>                
                <?php }else if($orderInfo->status == Orders::STATUS_PENDING){?>
                <i data-toggle="tooltip" data-placement="left" title="Pending Order" class="fa fa-clock-o alert-warning" aria-hidden="true"></i>
                <?php }else if($orderInfo->status == Orders::STATUS_NEW){?>
                <i data-toggle="tooltip" data-placement="left" title="New Order" class="fa fa-exclamation alert-danger" aria-hidden="true"></i>
                <?php }?>
            </td>
            <td>
                <?php if($orderInfo->date_created != null){?>
                <?php if($orderInfo->confirmedDateTime != null){?>
                <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->confirmedDateTime );?>
                <?php }
                }?>
                
            </td>
            <td>
                <?php if($orderInfo->confirmedDateTime != null){?>
                <?php if($orderInfo->startDateTime != null){?>
                <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->startDateTime );?>
                <?php }
                }?>
            </td>
            <td>
                <?php if($orderInfo->startDateTime != null){?>
                <?php if($orderInfo->pickedUpDateTime != null){?>
                <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->pickedUpDateTime );?>
                <?php }
                }?>
            </td>
           <td>
                <a data-toggle="tooltip" data-placement="left" title="Archive Order" href='javascript: Order.archiveOrder(<?php echo $orderInfo->id?>)'><i class="fa fa-file-archive-o" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="vendor-coupon-order-history-pagination" data-total-pages="<?php echo ceil($totalCount / 20)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>