<?php
use app\helpers\UtilityHelper;
use app\models\OrderDetails;
use app\models\User;
use app\helpers\TenantHelper;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
   <style>
      @page {
        margin:0.9;padding:0.9; // you can set margin and padding 0 
      } 
      body {
        font-family: Times New Roman;
        font-size: 33px;
        text-align: center;
        border: thin solid black;  
      }
    </style>
</head>
<?php //if(\Yii::$app->user->identity->role == User::ROLE_ADMIN || \Yii::$app->user->identity->role == User::ROLE_VENDOR){
        $customerInfo = User::findOne($orderInfo->customerId);
  //  }
    ?>
<body style="width: 100%;">
    <div class="invoice-box" style="width: 100%;
        margin:auto;
        padding:30px;
        border:1px solid #eee;
        box-shadow:0 0 10px rgba(0, 0, 0, .15);
        font-size:16px;
        line-height:24px;
        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color:#555;">
        <table style="width: 100%">
            <tr class="top">
                <td colspan="2" style="padding:5px; vertical-align:top;">
                    <table style='width: 100%'>
                        <tr>
                            <td class="title" style=" font-size:45px;
        line-height:45px;
        color:#333;padding-bottom:20px;">
                                <a href="/" class="navbar-brand"><img alt="" class="img-responsive img-rounded" style="height: 100%;" src="/images/users/<?= TenantHelper::getVendorImageFromUrl() ?>" /></a>
                            </td>
                            
                            <td>
                               &nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table style='width: 100%'>
                        <tr>
                            <td style=" padding-bottom:40px;">
                               Order ID#: <?php echo $orderInfo->getOrderId();?><br>
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
                                       YES
                                    <?php 
                                    }else{
                                    ?>
                                        NO
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
                                <?php if($orderInfo->isFaxOrder == 1){?>
                                <div class='col-xs-12' >
                                    <label class='form-label'>Is Fax Sent? 
                                    <?php if($orderInfo->isFaxSent == 1){
                                    ?> YES, 
                                    <?php echo \Yii::$app->user->identity->showConvertedTime($orderInfo->faxSentDate );?>
                                    <?php 
                                    }else{
                                    ?>
                                        NO
                                    <?php 
                                    }?>
                                    </label>
                                </div>
                                <?php }?>
                            </td>
                            
                            <td style=" padding-bottom:40px;">
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
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            
            <tr class="heading">
               <td style="background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;">Name</td>
                  <td style="background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;">Unit Price</td>
                  <td style="background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;">Quantity</td>
                    <td style="background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold; text-align:right;">Total Amount</td>
                    
            </tr>
            <?php 
                $finalTotalAmount = 0;
                foreach($orders as $counter => $detail){
                    if($detail->type != OrderDetails::TYPE_COUPON)
                        $finalTotalAmount +=  $detail->totalAmount;
                    else{
                        $finalTotalAmount -=  $detail->totalAmount;
                    }
                    $isAddOn = $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON ? true : false;
                ?>
                <tr class="item">
                    <td style='border-bottom:1px solid #eee; padding-left: <?php echo $isAddOn  ? '20px' : '0px'?>'><?php echo $isAddOn ? 'Add-ons: ': ''?><?php echo $detail->name?>
                        <?php if($detail->notes != null && $detail->notes != ''){?>
                        <br />
                        <label class='form-label'><?php echo 'Notes: '.$detail->notes?></label>
                        <?php }?>
                    </td>
                    <td style='border-bottom:1px solid #eee;'>
                    <?php echo $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON || $detail->type == OrderDetails::TYPE_MENU_ITEM ? '$'.UtilityHelper::formatAmountForDisplay($detail->amount) : ''?>
                    </td>
                    <td style='border-bottom:1px solid #eee;'>
                    
                    <?php echo $detail->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON || $detail->type == OrderDetails::TYPE_MENU_ITEM ? $detail->quantity : ''?>
                    </td>
                    <td style='border-bottom:1px solid #eee; text-align:right; <?php echo $counter == count($orders) - 1 ? 'border-bottom: none' : ''?>'>
                    <?php if($detail->type != OrderDetails::TYPE_COUPON){?>
                        $<?php echo UtilityHelper::formatAmountForDisplay($detail->totalAmount)?>
                    <?php }else{?>
                    ($<?php echo UtilityHelper::formatAmountForDisplay($detail->totalAmount)?>)
                    <?php }?>
                    </td>
                </tr>
                <?php }?>
            
            
            <tr class="total">
               
                <td colspan='3'><label class='pull-right'>Final Total Amount</label></td>
                    <td style=" text-align:right; border-top:2px solid #eee;
        font-weight:bold;"><label>$<?php echo UtilityHelper::formatAmountForDisplay($finalTotalAmount)?></label></td>
            </tr>
        </table>
    </div>
</body>
</html>
