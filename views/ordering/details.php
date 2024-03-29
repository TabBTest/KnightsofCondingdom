<?php
use app\models\VendorMenuItem; 
use app\helpers\UtilityHelper;
use app\models\OrderDetails;
use app\models\User;
use app\models\Orders;
?>

<div class='row form-group'>
    <div class='col-xs-12'>
         <div class='col-xs-12'>
            <label class='form-label'>Order ID#: <?php echo $orderInfo->getOrderId();?></label>
        </div>
    </div>
    <?php if(\Yii::$app->user->identity->role == User::ROLE_ADMIN || \Yii::$app->user->identity->role == User::ROLE_VENDOR){
        $customerInfo = User::findOne($orderInfo->customerId);
    ?>
    <div class='col-xs-6'>
        <div class='col-xs-12'>
            <label class='form-label'>Customer Name: <?php echo $customerInfo->getFullName();?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Address: <?php echo $orderInfo->getDeliveryAddress();?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Contact #: <?php echo $customerInfo->getContactNumber();?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Date Ordered: <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->date_created );?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Payment Type: <?php echo $orderInfo->getPaymentType();?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Order Type: <?php echo $orderInfo->isDelivery == 1 ? 'For Delivery' : 'For Store Pick-up';?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Advance Order: <?php echo $orderInfo->isAdvanceOrder == 1 ? 'YES' : '-';?></label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>Scheduled Pickup / Delivery Time: <?php echo $orderInfo->isAdvanceOrder == 1 ? \Yii::$app->user->identity->showConvertedTime($orderInfo->advancePickupDeliveryTime ) : '-';?></label>
        </div>
        <div class='col-xs-12' >
            <label class='form-label'>Is Paid? 
            <?php if($orderInfo->isPaid == 1){
            ?>
                <i class="fa fa-check alert-success" aria-hidden="true"></i>
            <?php 
            }else{
            ?>
                <i class="fa fa-times alert-danger" aria-hidden="true"></i>
                <button class='btn btn-xs btn-raised btn-info' onclick='javascript: Order.markAsPaid(<?php echo $orderInfo->id?>)' type='button'>Mark As Paid</button>
            <?php 
            }?>
            </label>
        </div>
        <div class='col-xs-12' >
            <label class='form-label'>Is Fax? 
            <?php if($orderInfo->isFaxOrder == 1){
            ?> YES
            <?php 
            }else{
            ?>
                NO
            <?php 
            }?>
            </label>
        </div>
        <?php if($orderInfo->isFaxOrder == 1){
        $orderInfo->isFaxSent();
        ?>
        <div class='col-xs-12' >
            <label class='form-label'>Is Fax Sent? 
            <?php if($orderInfo->isFaxSent == Orders::FAX_STATUS_SENT){
            ?> YES, 
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->faxSentDate );?>
            <?php 
            }else{
                $faxStat = '';
                if($orderInfo->isFaxSent == Orders::FAX_STATUS_ERROR){
                    $faxStat = 'Error';
                }else if($orderInfo->isFaxSent == Orders::FAX_STATUS_PROCESSING){
                    $faxStat = 'Pending';
                }
                echo $faxStat;
            ?>
                <button class='btn btn-xs btn-raised  btn-info' onclick='javascript: Order.resendFax(<?php echo $orderInfo->id?>)' type='button'>Resend Fax</button>
                
            <?php 
            }?>
            </label>
        </div>
        <?php }?>
    </div>
    <div class='col-xs-6'>
        <div class='col-xs-12'>
            <label class='form-label'>CONFIRM : 
            <?php if($orderInfo->faxConfirmTimeIsNA == 1){
                 echo 'NA';
            }else if($orderInfo->date_created != null){?>
            <?php
            if($orderInfo->confirmedDateTime == null){?>
            <button type='button' class='btn btn-raised btn-primary btn-sm' onclick='javascript: Order.confirm(<?php echo $orderInfo->id?>)'>CONFIRM</button>
            <?php }else{?>
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->confirmedDateTime );?>
            <?php }
            }?>
            </label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>START : 
           <?php if($orderInfo->faxStartTimeIsNA == 1){
                 echo 'NA';
            }else if($orderInfo->confirmedDateTime != null){?>
            <?php if($orderInfo->startDateTime == null){?>
            <button type='button' class='btn btn-raised btn-primary btn-sm'  onclick='javascript: Order.start(<?php echo $orderInfo->id?>)'>START</button>
            <?php }else{?>
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->startDateTime );?>
            <?php }
            }else{
                echo '-';
            }?>
            </label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>PICKED UP? : 
           <?php if($orderInfo->faxPickupTimeIsNA == 1){
                 echo 'NA';
            }else if($orderInfo->startDateTime != null){?>
            <?php if($orderInfo->pickedUpDateTime == null){?>
            <button type='button' class='btn btn-raised btn-primary btn-sm'  onclick='javascript: Order.pickup(<?php echo $orderInfo->id?>)'>PICKED UP</button>
            <?php }else{?>
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->pickedUpDateTime );?>
            <?php }
            }else{
                echo '-';
            }?>
            </label>
        </div>
        <?php if($orderInfo->isCancelled == 1){?>
        <div class='col-xs-12'>
            <label class='form-label'>CANCELLED : 
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->cancellation_date );?> - <?php echo $orderInfo->getCancelledBy()->getFullName();?>
            </label>
            
        </div>
        <?php }?>
        <?php if($orderInfo->isRefunded == 1){?>
        <div class='col-xs-12'>
            <label class='form-label'>REFUNDED : 
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->refund_date );?> - <?php echo $orderInfo->getRefundedBy()->getFullName();?>
            </label>
            
        </div>
        <?php }?>
        <br />
        <div class="col-xs-12">
            <label class="form-label">
                <button type="button"
                        class="btn btn-raised  btn-danger btn-sm"
                        onclick="javascript: Order.archiveOrder(<?= $orderInfo->id ?>)">
                    Archive
                </button>
                <?php if($orderInfo->isCancelled != 1){?>
                <button type="button"
                        class="btn btn-raised btn-danger btn-sm"
                        onclick="javascript: Order.cancelOrder(<?= $orderInfo->id ?>)">
                    Cancel Order
                </button>
                <?php }?>
                <?php if($orderInfo->isPaid == 1 && $orderInfo->isRefunded != 1){?>
                <button type="button"
                        class="btn btn-raised btn-danger btn-sm"
                        onclick="javascript: Order.refundOrder(<?= $orderInfo->id ?>)">
                    Refund
                </button>
                <?php }?>
            </label>
        </div>
    </div>
    
    
    
            
    <?php }?>
    <div class='col-xs-12' style='margin-top: 20px;'>
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
                    if($detail->type != OrderDetails::TYPE_COUPON)
                        $finalTotalAmount +=  $detail->totalAmount;
                    else{
                        $finalTotalAmount -=  $detail->totalAmount;
                    }
                    $isAddOn = $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON ? true : false;
                ?>
                <tr>
                    <td style='padding-left: <?php echo $isAddOn  ? '20px' : '0px'?>'>
                        <?php echo $isAddOn ? 'Add-ons: ': ''?>
                        <?php echo $detail->name?>
                        <?php if($detail->notes != null && $detail->notes != ''){?>
                        <br />
                        <label class='form-label'><?php echo 'Notes: '.$detail->notes?></label>
                        <?php }?>
                    </td>
                    <td>
                    <?php echo $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON || $detail->type == OrderDetails::TYPE_MENU_ITEM ? '$'.UtilityHelper::formatAmountForDisplay($detail->amount) : ''?>
                    </td>
                    <td>
                    
                    <?php echo $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON || $detail->type == OrderDetails::TYPE_MENU_ITEM ? $detail->quantity : ''?></td>
                    <td>
                    <?php if($detail->type != OrderDetails::TYPE_COUPON){?>
                        $<?php echo UtilityHelper::formatAmountForDisplay($detail->totalAmount)?>
                    <?php }else{?>
                    ($<?php echo UtilityHelper::formatAmountForDisplay($detail->totalAmount)?>)
                    <?php }?>
                    </td>
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
    <div class='col-xs-12'>
        <label class='form-label'>Order Notes: <?php echo nl2br($orderInfo->notes)?></label>
    </div>
</div>

<div class='row form-group'>
    <div class='col-xs-12 text-center'>
        <button type='button' class='btn btn-raised btn-default' data-dismiss="modal">Close</button>
    </div>
</div>
