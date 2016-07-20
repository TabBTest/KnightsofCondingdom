<?php 
use app\helpers\UtilityHelper;
use app\models\VendorMenuItem;
use app\models\VendorMenuItemAddOns;
use app\helpers\TenantHelper;
use app\models\TenantInfo;
use app\models\Orders;
use app\models\AppConfig;
use app\models\MenuCategories;
use app\controllers\VendorController;
use app\models\VendorCoupons;
if(isset($params['Orders'])){                         
?>
<div class='col-xs-12 text-center'>
<table class='table table-condensed'>
    <tbody>
<?php 
$finalAmount = 0;
$itemsFinalAmount = 0;
$vendorId = false;
$couponCode =  isset($params['couponCode']) ? $params['couponCode'] : '';
$vendorCoupon = false;
    foreach($params['Orders'] as  $orderKey => $menuItemId){
        $quantity = $params['OrdersQuantity'][$orderKey];
        $menuItem = VendorMenuItem::findOne($menuItemId);
        if($vendorId === false){
            $menuCategory = MenuCategories::findOne($menuItem->menuCategoryId);
            $vendorId = $menuCategory->vendorId;
            $vendorCoupon = VendorCoupons::isValidCoupon($couponCode, $vendorId);
            
        }
        $totalAmount =  $quantity * $menuItem->amount;
        $finalAmount += $totalAmount;
    ?>
        <tr class='order-<?php echo $orderKey?>'>
            <td>
            <a href='javascript: void(0)' class='delete-order-item' data-key='<?php echo $orderKey?>'>
            <i class="fa fa-times" aria-hidden="true"></i>
            </a>
            <a href='javascript: void(0)' class='edit-order-item' data-menu-item-id='<?php echo $menuItem->id?>'  data-key='<?php echo $orderKey?>'>
            <i class="fa fa-pencil" aria-hidden="true"></i>
            </a>
            </td>
            <td>
            <input type='hidden' name='Orders[<?php echo $orderKey?>]' value='<?php echo $menuItem->id?>' />
            <input type='hidden' name='OrdersQuantity[<?php echo $orderKey?>]' value='<?php echo $quantity?>'/>
            
            <?php if(isset($params['OrdersNotes'][$orderKey])){?>
                <input type='hidden' name='OrdersNotes[<?php echo $orderKey?>]' value='<?php echo $params['OrdersNotes'][$orderKey]?>' />
            <?php }?>
            <?php echo $quantity?> <?php echo $menuItem->name?></td>
            <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAmount)?></td>
        </tr>
        <?php 
        if(isset($params['AddOnsExclusive'][$orderKey])){
            
                $menuItemAddOn = VendorMenuItemAddOns::findOne($params['AddOnsExclusive'][$orderKey]);
                $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
                $finalAmount += $totalAddonAmount;
                ?>
                    <tr class='order-<?php echo $orderKey?>'>
                        <td></td>
                        <td style='padding-left: 20px;'>
                        <input type='hidden' name='AddOnsExclusive[<?php echo $orderKey?>]' value='<?php echo $menuItemAddOn->id?>' class='additionals <?php echo $orderKey?> exclusive' data-add-on-id='<?php echo $menuItemAddOn->id?>' />
                        Add-ons: <?php echo $quantity?> <?php echo $menuItemAddOn->name?></td>
                        <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
                    </tr>
                <?php 
                    }
                
        ?>
        
    <?php
        if(isset($params['AddOns'][$orderKey])){
            foreach($params['AddOns'][$orderKey] as $addOnId => $elem){
                $menuItemAddOn = VendorMenuItemAddOns::findOne($addOnId);
                $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
                $finalAmount += $totalAddonAmount;
                ?>
            <tr class='order-<?php echo $orderKey?>'>
                <td></td>
                <td style='padding-left: 20px;'>
                <input type='hidden' name='AddOns[<?php echo $orderKey?>][<?php echo $menuItemAddOn->id?>]' class='additionals <?php echo $orderKey?>' data-add-on-id='<?php echo $menuItemAddOn->id?>' value='<?php echo $quantity?>' />
                Add-ons: <?php echo $quantity?> <?php echo $menuItemAddOn->name?></td>
                <td>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
            </tr>
        <?php 
            }
        } 
    }
    $itemsFinalAmount = $finalAmount;
    $totalFinalAmount = $finalAmount * $salesTax;
    $salesTax = $totalFinalAmount - $finalAmount;
?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>    
    Sales Tax</label></td>
    <td><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($salesTax)?></label></td>
</tr>
<?php 
$adminFee = floatval(UtilityHelper::getAppConfig(AppConfig::ADMIN_FEE, 0));
$totalFinalAmount += $adminFee;
?>
<?php
$deliveryAmount = 0; 
if(TenantHelper::isVendorAllowDelivery($itemsFinalAmount)){
    $deliveryAmount = isset($_POST['isDelivery']) && $_POST['isDelivery'] == 1 ? TenantHelper::getDeliveryAmount() : 0;
    $totalFinalAmount += $deliveryAmount;
    ?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>    
    Delivery Charge</label></td>
    <td><label class='form-label delivery-amount'>
    $<?php echo UtilityHelper::formatAmountForDisplay($deliveryAmount)?>
    </label></td>
</tr>

<?php }?>
<?php if($adminFee != 0){?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>    
    Web Fee</label></td>
    <td><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($adminFee)?></label></td>
</tr>
<?php }?>
<?php if($vendorCoupon){?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>
    <?php $couponDiscountDisplay = '';
    $discount = false;
    if($vendorCoupon->discountType == VendorCoupons::TYPE_AMOUNT){
        $couponDiscountDisplay = 'Coupon Discount ($'.UtilityHelper::formatAmountForDisplay($vendorCoupon->discount).')';
        $discount = floatval($vendorCoupon->discount);
        
    }else if($vendorCoupon->discountType == VendorCoupons::TYPE_PERCENTAGE){
        $couponDiscountDisplay = 'Coupon Discount ('.UtilityHelper::formatAmountForDisplay($vendorCoupon->discount).'%)';
        $discount = $totalFinalAmount * (floatval($vendorCoupon->discount) / 100);
    }
    
    if($discount !== false){
        if($totalFinalAmount >= $discount){
            $totalFinalAmount = $totalFinalAmount - $discount;
        }else{
            $totalFinalAmount = 0;
        }
    }
    ?>    
    <?php echo $couponDiscountDisplay?></label></td>
    <td><label class='form-label discount-amount' data-type='<?php echo $vendorCoupon->discountType?>' data-discount='<?php echo $vendorCoupon->discount?>'>$<?php echo UtilityHelper::formatAmountForDisplay($discount)?></label></td>
</tr>
<?php }?>
<tr>
    <td>&nbsp;</td>
    <td><label class='form-label'>Total</label></td>
    <td><label class='form-label final-amount' data-amount='<?php echo $totalFinalAmount-$deliveryAmount?>'>$<?php echo UtilityHelper::formatAmountForDisplay($totalFinalAmount)?></label></td>
</tr>
</tbody>
</table>
</div>
<div class="form-group col-xs-12">
    <label>Instructions</label>
    <textarea class="form-control" rows="5" cols="25" name="notes" placeholder="Please add your extra instructions here..."><?php echo isset($params['notes']) ? $params['notes'] : ''?></textarea>
</div>
<div class="col-xs-12 text-center">
    <button class="btn btn-raised btn-primary" type="button" data-toggle="modal" data-target="#checkout-modal">Checkout</button>
</div>

    <div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog" aria-labelledby="checkout-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="checkout-modal-label">Order Checkout</h4>
                </div>
                <div class="modal-body">
                    <?php if(TenantHelper::isVendorAllowDelivery($itemsFinalAmount)){?>
                        <div class="form-group">
                            <div class="">
                                <label class="">Do you want it delivered?</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php echo isset($_POST['isDelivery']) && $_POST['isDelivery'] == 1 ? 'checked' : '' ?> value="1" class="has-delivery" name="isDelivery" data-amount="<?php echo TenantHelper::getDeliveryAmount()?>"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    <div class="form-group">
                        <label class="">Payment Type</label>

                        <div class="">
                            <div class="radio radio-primary">
                                <label>
                                    <input type="radio" value="<?php echo Orders::PAYMENT_TYPE_CARD?>" <?php echo !isset($params['paymentType']) || (isset($params['paymentType']) && $params['paymentType'] == Orders::PAYMENT_TYPE_CARD) ? "checked" : ""?> name="paymentType"/>Card
                                </label>
                            </div>
                            <div class="radio radio-primary">
                                <label>
                                    <input type="radio" value="<?php echo Orders::PAYMENT_TYPE_CASH?>" <?php echo (isset($params['paymentType']) && $params['paymentType'] == Orders::PAYMENT_TYPE_CASH) ? "checked" : ""?> name="paymentType"/>Cash
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="">Coupon Code</label>
                        <div class="">
                            <input type="text" class="form-control col-md-6" data-vendor-id="<?php echo $vendorId?>" name="couponCode" value="<?php echo $vendorCoupon !== false ? $vendorCoupon->code : ""?>"/>
                            <button type="button" class="btn btn-info" onclick="javascript: Order.applyCoupon()">Apply</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-primary">Pay Now</button>
                </div>
            </div>
        </div>
    </div>

<?php }else{?>
<div class="col-xs-12 text-center">
    <label>Please add your order now</label>
</div>
<?php }?>

