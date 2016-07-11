<?php
use app\models\VendorMenuItem; 
use app\helpers\UtilityHelper;
use app\models\OrderDetails;
use app\models\User;
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
            <label class='form-label'>Address: <?php echo $customerInfo->getFullAddress();?></label>
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
        <div class='col-xs-12' >
            <label class='form-label'>Is Paid? 
            <?php if($orderInfo->isPaid == 1){
            ?>
                <i class="fa fa-check alert-success" aria-hidden="true"></i>
            <?php 
            }else{
            ?>
                <i class="fa fa-times alert-danger" aria-hidden="true"></i>
                <button class='btn btn-xs btn-info' onclick='javascript: Order.markAsPaid(<?php echo $orderInfo->id?>)' type='button'>Mark As Paid</button>
            <?php 
            }?>
            </label>
        </div>
    </div>
    <div class='col-xs-6'>
        <div class='col-xs-12'>
            <label class='form-label'>CONFIRM : 
            <?php if($orderInfo->date_created != null){?>
            <?php if($orderInfo->confirmedDateTime == null){?>
            <button type='button' class='btn btn-info btn-xs' onclick='javascript: Order.confirm(<?php echo $orderInfo->id?>)'>CONFIRM</button>
            <?php }else{?>
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->confirmedDateTime );?>
            <?php }
            }?>
            </label>
        </div>
        <div class='col-xs-12'>
            <label class='form-label'>START : 
           <?php if($orderInfo->confirmedDateTime != null){?>
            <?php if($orderInfo->startDateTime == null){?>
            <button type='button' class='btn btn-info btn-xs'  onclick='javascript: Order.start(<?php echo $orderInfo->id?>)'>START</button>
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
           <?php if($orderInfo->startDateTime != null){?>
            <?php if($orderInfo->pickedUpDateTime == null){?>
            <button type='button' class='btn btn-info btn-xs'  onclick='javascript: Order.pickup(<?php echo $orderInfo->id?>)'>PICKED UP?</button>
            <?php }else{?>
            <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->pickedUpDateTime );?>
            <?php }
            }else{
                echo '-';
            }?>
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
                    $finalTotalAmount +=  $detail->totalAmount;
                    $isAddOn = $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON ? true : false;
                ?>
                <tr>
                    <td style='padding-left: <?php echo $isAddOn  ? '20px' : '0px'?>'><?php echo $isAddOn ? 'Add-ons: ': ''?><?php echo $detail->name?>
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
                    <td>$<?php echo UtilityHelper::formatAmountForDisplay($detail->totalAmount)?></td>
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
        <button type='button' class='btn btn-default' data-dismiss="modal">Close</button>
    </div>
</div>