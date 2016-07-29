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
use app\models\VendorAppConfigOverride;
use app\models\User;
?>
<style>
table.main-order-summary-table td.menu-name{
	text-align: left;
}
table.main-order-summary-table td.amount{
	text-align: right;
}
</style>
<div class='col-xs-12 text-center'>
<table class='table table-condensed main-order-summary-table'>
    <tbody>
<?php 
$model = false;
if(\Yii::$app->user->identity != null){
    $model = User::findOne(\Yii::$app->user->identity->id);
}else{
    \Yii::$app->response->redirect(['site/login']);
}
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
            <?php if($viewOnly == false){?>
            <a href='javascript: void(0)' class='delete-order-item' data-key='<?php echo $orderKey?>'>
            <i class="fa fa-times" aria-hidden="true"></i>
            </a>
            <a href='javascript: void(0)' class='edit-order-item' data-menu-item-id='<?php echo $menuItem->id?>'  data-key='<?php echo $orderKey?>'>
            <i class="fa fa-pencil" aria-hidden="true"></i>
            </a>
            <?php }?>
            </td>
            <td class='menu-name'>
            <?php if($viewOnly == false){?>
            <input type='hidden' name='Orders[<?php echo $orderKey?>]' value='<?php echo $menuItem->id?>' />
            <input type='hidden' name='OrdersQuantity[<?php echo $orderKey?>]' value='<?php echo $quantity?>'/>
            
            <?php if(isset($params['OrdersNotes'][$orderKey])){?>
                <input type='hidden' name='OrdersNotes[<?php echo $orderKey?>]' value='<?php echo $params['OrdersNotes'][$orderKey]?>' />
            <?php }?>
            <?php }?>
            <?php echo $quantity?> <?php echo $menuItem->name?></td>
            <td class='amount'>$<?php echo UtilityHelper::formatAmountForDisplay($totalAmount)?></td>
        </tr>
        <?php 
        if(isset($params['AddOnsExclusive'][$orderKey])){
            
                $menuItemAddOn = VendorMenuItemAddOns::findOne($params['AddOnsExclusive'][$orderKey]);
                $totalAddonAmount =  $quantity * $menuItemAddOn->amount;
                $finalAmount += $totalAddonAmount;
                ?>
                    <tr class='order-<?php echo $orderKey?>'>
                        <td></td>
                        <td class='menu-name' style='padding-left: 20px;'>
                        <?php if($viewOnly == false){?>
                        <input type='hidden' name='AddOnsExclusive[<?php echo $orderKey?>]' value='<?php echo $menuItemAddOn->id?>' class='additionals <?php echo $orderKey?> exclusive' data-add-on-id='<?php echo $menuItemAddOn->id?>' />
                        <?php }?>
                        Add-ons: <?php echo $quantity?> <?php echo $menuItemAddOn->name?></td>
                        <td class='amount'>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
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
                <td class='menu-name' style='padding-left: 20px;'>
                <?php if($viewOnly == false){?>
                <input type='hidden' name='AddOns[<?php echo $orderKey?>][<?php echo $menuItemAddOn->id?>]' class='additionals <?php echo $orderKey?>' data-add-on-id='<?php echo $menuItemAddOn->id?>' value='<?php echo $quantity?>' />
                <?php }?>
                Add-ons: <?php echo $quantity?> <?php echo $menuItemAddOn->name?></td>
                <td class='amount'>$<?php echo UtilityHelper::formatAmountForDisplay($totalAddonAmount)?></td>
            </tr>
        <?php 
            }
        } 
    }
    $itemsFinalAmount = $finalAmount;
    
    
?>
<?php if($vendorCoupon){?>
<tr>
    <td>&nbsp;</td>
    <td class='menu-name'><label class='form-label'>
    <?php $couponDiscountDisplay = '';
    $discount = false;
    if($vendorCoupon->discountType == VendorCoupons::TYPE_AMOUNT){
        $couponDiscountDisplay = 'Coupon Discount ($'.UtilityHelper::formatAmountForDisplay($vendorCoupon->discount).')';
        $discount = floatval($vendorCoupon->discount);
        
    }else if($vendorCoupon->discountType == VendorCoupons::TYPE_PERCENTAGE){
        $couponDiscountDisplay = 'Coupon Discount ('.UtilityHelper::formatAmountForDisplay($vendorCoupon->discount).'%)';
        $discount = $itemsFinalAmount * (floatval($vendorCoupon->discount) / 100);
    }
    
    if($discount !== false){
        if($itemsFinalAmount >= $discount){
            $itemsFinalAmount = $itemsFinalAmount - $discount;
        }else{
            $itemsFinalAmount = 0;
        }
    }
    ?>    
    <?php echo $couponDiscountDisplay?></label></td>
    <td class='amount'><label class='form-label discount-amount' data-type='<?php echo $vendorCoupon->discountType?>' data-discount='<?php echo $vendorCoupon->discount?>'>$<?php echo UtilityHelper::formatAmountForDisplay($discount)?></label></td>
</tr>
<?php }?>
<?php 
$totalFinalAmount = $itemsFinalAmount * $vendorSalesTax;
$salesTax = $totalFinalAmount - $itemsFinalAmount;
?>
<tr>
    <td>&nbsp;</td>
    <td class='menu-name'><label class='form-label'>    
    Sales Tax</label></td>
    <td class='amount'><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($salesTax)?></label></td>
</tr>
<?php 
$adminFee = floatval(VendorAppConfigOverride::getVendorOverride($vendorId, AppConfig::ADMIN_FEE));
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
    <td class='menu-name'><label class='form-label'>    
    Delivery Charge</label></td>
    <td class='amount'><label class='form-label delivery-amount'>
    $<?php echo UtilityHelper::formatAmountForDisplay($deliveryAmount)?>
    </label></td>
</tr>

<?php }?>
<?php if($adminFee != 0){?>
<tr>
    <td>&nbsp;</td>
    <td class='menu-name'><label class='form-label'>    
    Web Fee</label></td>
    <td class='amount'><label class='form-label'>$<?php echo UtilityHelper::formatAmountForDisplay($adminFee)?></label></td>
</tr>
<?php }?>

<tr>
    <td>&nbsp;</td>
    <td class='menu-name'><label class='form-label'>Total</label></td>
    <td class='amount'><label class='form-label final-amount' data-amount='<?php echo $totalFinalAmount-$deliveryAmount?>'>$<?php echo UtilityHelper::formatAmountForDisplay($totalFinalAmount)?></label></td>
</tr>
</tbody>
</table>
</div>