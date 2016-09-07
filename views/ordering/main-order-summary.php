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


if(isset($params['Orders'])){                         
?>
<?php 
$viewOnly = false;
include('_main_order_summary_info.php');
?>
<div class="form-group col-xs-12">
    <label>Instructions</label>
    <textarea class="form-control" rows="5" cols="25" name="notes" placeholder="Please add your extra instructions here..."><?php echo isset($params['notes']) ? $params['notes'] : ''?></textarea>
</div>
<div class="col-xs-12 text-center">
    <button class="btn btn-raised btn-primary" type="button" onclick="javascript: Order.checkOut();">Checkout</button>
</div>

    <div class="modal fade" id="relogin-modal" data-is-login='<?php echo $model !== false ? 1 : 0?>' tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Register / Login</h4>
                </div>
                <div class="modal-body">
                        <div class="col-xs-12 text-center" style="margin-bottom: 10px">
                            <a class='btn btn-info  btn-raised' href='/site/reg-customer'>Register</a>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-bottom: 10px">
                            <a class='btn btn-info  btn-raised' href='/site/login'>Login</a>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog" aria-labelledby="checkout-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="checkout-modal-label">Order Checkout</h4>
                </div>
                <div class="modal-body">
                    <div class='fieldset step1'>
                        <?php if(TenantHelper::isVendorAllowDelivery($itemsFinalAmount)){?>
                            <div class="form-group">
                                <div class="">
                                    <label class="">Do you want it delivered?</label>
                                    <div class="checkbox">
                                        <label class='pull-left'>
                                            <input type="checkbox" <?php echo isset($_POST['isDelivery']) && $_POST['isDelivery'] == 1 ? 'checked' : '' ?> value="1" class="has-delivery" name="isDelivery" data-amount="<?php echo TenantHelper::getDeliveryAmount()?>"/>
                                        </label>
                                        <div class=' pull-left' style='margin-left: 10px'>
                                            <select style='display: <?php echo isset($_POST['isDelivery']) && $_POST['isDelivery'] == 1 ? 'block' : 'none' ?>' name='deliveryAddressType'>
                                                <option value='current'><?php echo $model !== false ? $model->getFullAddress() : ''?></option>
                                                <option value='new'>New Address</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                        <div class="form-group">
                            <label class="">Pickup / Delivery?</label>
    
                            <div class="">
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" <?php echo !isset($_POST['isAdvanceOrder']) || (isset($_POST['isAdvanceOrder']) && $_POST['isAdvanceOrder'] == 0) ? 'checked' : '' ?> value="0" class="is-advance-order" name="isAdvanceOrder" /> Now                                        &nbsp;&nbsp;&nbsp;
                                       
                                     </label>
                                   
                                </div>
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" <?php echo isset($_POST['isAdvanceOrder']) && $_POST['isAdvanceOrder'] == 1 ? 'checked' : '' ?> value="1" class="is-advance-order" name="isAdvanceOrder" /> Later
                                        &nbsp;&nbsp;&nbsp;
                                            <input id="advanceTimePicker" name="advanceTime" type="text" class="input-small advance-time-pickup" value='<?php echo isset($_POST['advanceTime']) ? $_POST['advanceTime'] : ''?>'>
                                            <i class="icon-time"></i>
                                        <script type="text/javascript">
                                            $('#advanceTimePicker').timepicker({
                                                template: false,
                                                showInputs: true,
                                                minuteStep: 1,
                                                defaultTime: 'current'
                                            });
                                        </script>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                       <div class='new-address row' style='display: none'>
                            <div class='col-xs-12'>
                                <label class="">New Delivery Address:</label>
                            </div>
                            <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
                                <input type='text' class='form-control' name='deliveryStreetAddress'  placeholder='Street Address'/>
                            </div>
                            <div class='col-xs-12 col-sm-9 col-md-4 col-md-offset-3 form-group'>
                                <input type='text' class='form-control' name='deliveryCity'  placeholder='City'/>
                            </div>
                            <div class='col-xs-6 col-sm-3 col-md-2 form-group'>
                                <select class='form-control' name='deliveryState'>
                                    <option value="" selected disabled hidden>State</option>
                                    <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                                    <option value="<?php echo $stateCode?>" ><?php echo $stateCode?></option>
                                <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">Coupon Code</label>
                            <div class="">
                                <input type="text" class="form-control col-md-6 not-required" data-vendor-id="<?php echo $vendorId?>" name="couponCode" value="<?php echo $vendorCoupon !== false ? $vendorCoupon->code : ""?>"/>
                                <button type="button" class="btn btn-primary" onclick="javascript: Order.applyCoupon()">Apply</button>
                            </div>
                        </div>
                        
                        <div class='col-xs-12 form-group text-center'>
                            <button type="button" class="btn btn-raised btn-primary btn-next" data-step='step1'>Continue</button>
                        </div>
                        
                    </div>
                    <!-- for new cc -->
                    <div class='fieldset step2' style='display: none'>
                       
                        <div class="form-group">
                            <label class="">Payment Type</label>
    
                            <div class="">
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" value="<?php echo Orders::PAYMENT_TYPE_CARD?>" <?php echo !isset($params['paymentType']) || (isset($params['paymentType']) && $params['paymentType'] == Orders::PAYMENT_TYPE_CARD) ? "checked" : ""?> name="paymentType"/>Card
                                        &nbsp;&nbsp;&nbsp;
                                        <select style='display: none' name='cardToUse'>
                                            <option value='current'>Existing credit card ending in <?php echo $model !== false ? $model->cardLast4 : ''?></option>
                                            <option value='new'>New Card</option>
                                        </select>
                                     </label>
                                   
                                </div>
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" value="<?php echo Orders::PAYMENT_TYPE_CASH?>" <?php echo (isset($params['paymentType']) && $params['paymentType'] == Orders::PAYMENT_TYPE_CASH) ? "checked" : ""?> name="paymentType"/>Cash
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class='new-card row' style='display: none'>
                                <div class='col-xs-12'>
                                    <label>New Card Details:</label>
                                  </div>
                                <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
                                    <input type='text' class='form-control' name='User[billingName]' placeholder='Billing Name'/>
                                </div>
                                <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
                                    <input type='text' class='form-control' name='User[billingStreetAddress]'  placeholder='Billing Street Address'/>
                                </div>
                                <div class='col-xs-12 col-sm-3 col-md-3 col-md-offset-3 form-group'>
                                    <input type='text' class='form-control' name='User[billingCity]'  placeholder='City'/>
                                </div>
                                <div class='col-xs-6 col-sm-3 col-md-3 form-group'>
                                    <select class='form-control' name='User[billingState]'>
                                        <option value="" selected disabled hidden>State</option>
                                        <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                                        <option value="<?php echo $stateCode?>" ><?php echo $stateCode?></option>
                                    <?php }?>
                                    </select>
                                </div>
                                <div class='col-xs-6 col-sm-3 col-md-3 form-group'>
                                    <input type='text' class='form-control' name='User[billingZip]'  placeholder='Zip'/>
                                </div>
                                
                                
                                <input type='hidden' name='User[billingPhoneAreaCode]' value='<?php echo $model->billingPhoneAreaCode?>'/>
                                <input type='hidden' name='User[billingPhone3]' value='<?php echo $model->billingPhone3?>'/>
                                <input type='hidden' name='User[billingPhone4]' value='<?php echo $model->billingPhone4?>'/>
                                <input type='hidden' name='User[cardLast4]' class='billing-last-4' value=''/>
                        
                                <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
                                    <input type='text' class='form-control card-number' name='cc'  placeholder='Credit Card Number'/>
                                </div>
                                <div class='col-xs-4 col-md-2 col-md-offset-3 form-group'>
                                    <input type='text' class='form-control card-cvv'  name='cvv'   placeholder='CVV'/>
                                </div>
                        
                                <div class='col-xs-4 col-md-2 form-group'>
                                      <select name='ccMonth'  class="form-control card-expiry-month">
                                      <option value=''>Month</option>
                            		    	<?php for($index = 1 ; $index < 13; $index++){
                            		    	         $indexVal = $index < 10 ? '0'.$index : $index;
                            		    	    ?>
                            		    	<option value="<?php echo $indexVal?>"><?php echo $indexVal?></option>
                            		    	<?php }?>
                            		    </select>
                                </div>
                                <div class='col-xs-4 col-md-2 form-group'>
                                         <select name='ccYear'  class="form-control card-expiry-year">
                                         <option value=''>Year</option>
                            		    	<?php
                            		    	$curYear = date('Y');
                            		    	for($index = $curYear ; $index < $curYear + 20; $index++){?>
                            		    	<option value="<?php echo $index?>"><?php echo $index?></option>
                            		    	<?php }?>
                            		    </select>
                                </div>
                        </div>
                        <div class='col-xs-12 form-group text-center'>
                            <button type="button" class="btn btn-raised btn-default btn-back" data-step='step2'>Back</button>
                            <button type="button" class="btn btn-raised btn-primary btn-next btn-next-cc" data-step='step2'>Continue</button>
                        </div>
                    </div>
                    
                    <!-- last step for order confirmation -->
                    <div class='fieldset step3' style='display: none'>
                       
                        <?php 
                        $viewOnly = true;
                        include('_main_order_summary_info.php');
                        ?>
                        
                
                        <div class='col-xs-12 form-group text-center'>
                            <button type="button" class="btn btn-raised btn-default btn-back" data-step='step3'>Back</button>
                            <button type="button" class="btn btn-raised btn-primary btn-next" data-step='step3'>Pay Now</button>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                   
                </div>
            </div>
        </div>
    </div>

<?php }else{?>
<div class="col-xs-12 text-center">
    <label>Please add your order now</label>
</div>
<?php }?>

