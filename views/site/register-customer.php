<?php

use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;

$params = require(\Yii::$app->basePath . '/config/params.php');

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('Stripe.setPublishableKey(\'' . \Yii::$app->params['stripe_publishable_key'] . '\');', $this::POS_READY);
?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
<div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
</div>
<?php } ?>

<form action='/site/reg-customer' method='POST' id='register-form'>
    <div class='row fieldset'>
        <div class='col-xs-12 form-group text-center'>
        <h1>REGISTER</h1>
        <h3>Step 1 - Account Information</h3>
        </div>
        <div class="col-xs-6 col-md-3 col-md-offset-3 form-group">
            <label class="control-label">First Name</label>
            <input type="text" class="form-control" name="User[firstName]" placeholder=""/>
        </div>
        <div class="col-xs-6 col-md-3 form-group">
            <label class="control-label">Last Name</label>
            <input type='text' class='form-control' name='User[lastName]' placeholder=""/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <label class="control-label">Street Address</label>
            <input type='text' class='form-control' name='User[streetAddress]' placeholder=""/>
        </div>
        <div class='col-xs-12 col-sm-9 col-md-4 col-md-offset-3 form-group'>
            <label class="control-label">City</label>
            <input type='text' class='form-control' name='User[city]'  placeholder=""/>
        </div>
        <div class='col-xs-6 col-sm-3 col-md-2 form-group'>
            <label class="control-label">State</label>
            <select class='form-control' name='User[state]'>
                <option value="" selected disabled hidden></option>
                <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                <option value="<?php echo $stateCode?>" ><?php echo $stateCode?></option>
            <?php }?>
            </select>
        </div>
        <div class="col-xs-12 col-md-6 col-md-offset-3 form-group form-inline phone" data-key="phone">
            <label class="control-label">Phone Number</label><br />
            <input type="tel" class="form-control short-input" name='User[phoneAreaCode]'  maxlength="3" placeholder='Area Code'/>
            <input type='tel' class='form-control short-input' name='User[phone3]' maxlength="3" placeholder='XXX'/>
            <input type='tel' class='form-control short-input' name='User[phone4]' maxlength="4" placeholder='XXXX'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <label class="control-label">Email</label>
            <input type='text' class='form-control' name='User[email]' id='email'  placeholder=""/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <label class="control-label">Confirm Email</label>
            <input type='text' class='form-control' name='confirmEmail' id='confirmEmail'  placeholder=""/>
        </div>
        
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='hidden' name='isOptIn' value='0'/>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" name="isOptIn" />
                    I want to receive promotional emails
                </label>
            </div>
        </div>
        <div class='col-xs-12 col-md-12 form-group'>
            <div align="center" class="g-recaptcha" data-sitekey="<?= $params['recaptcha_site_key'] ?>"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=en">
            </script>
        </div>
        <div class="col-xs-12 form-group text-center">
            <button type="button" class="btn btn-primary btn-raised btn-next">NEXT</button>
        </div>
    </div>
    <div class='row fieldset' style='display: none'>
        <div class='col-xs-12 form-group text-center'>
            <h1>REGISTER</h1>
            <h3>Step 2 - Payment Information</h3>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <label class="control-label">Billing Name</label>
            <input type='text' class='form-control' name='User[billingName]' placeholder="" />
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <label class="control-label">Billing Street Address</label>
            <input type='text' class='form-control' name='User[billingStreetAddress]' placeholder="" />
        </div>
        <div class='col-xs-12 col-sm-9 col-md-4 col-md-offset-3 form-group'>
            <label class="control-label">Billing City</label>
            <input type='text' class='form-control' name='User[billingCity]' placeholder="" />
        </div>
        <div class='col-xs-6 col-sm-3 col-md-2 form-group'>
            <label class="control-label">Billing State</label>
            <select class='form-control' name='User[billingState]'>
                <option value="" selected disabled hidden></option>
                <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                <option value="<?php echo $stateCode?>" ><?php echo $stateCode?></option>
            <?php }?>
            </select>
        </div>
         <div class='col-xs-12 col-md-6 col-md-offset-3 form-group form-inline phone' data-key='billing-phone'>
             <label class="control-label">Billing Phone Number</label><br />
             <input type='tel' class='form-control short-input' name='User[billingPhoneAreaCode]'  maxlength="3" placeholder='Area Code'/>
             <input type='tel' class='form-control short-input' name='User[billingPhone3]' maxlength="3" placeholder='XXX'/>
             <input type='tel' class='form-control short-input' name='User[billingPhone4]' maxlength="4" placeholder='XXXX'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <label class="control-label">Credit Card Number</label>
            <input type='text' class='form-control card-number' placeholder=""/>
        </div>
        <div class='col-xs-4 col-md-2 col-md-offset-3 form-group'>
            <label class="control-label">CVV</label>
            <input type='text' class='form-control card-cvv' placeholder=""/>
        </div>

        <div class='col-xs-4 col-md-2 form-group'>
            <label class="control-label">Expiry Month</label>
              <select  class="form-control card-expiry-month">
    		    	<?php for($index = 1 ; $index < 13; $index++){
    		    	         $indexVal = $index < 10 ? '0'.$index : $index;
    		    	    ?>
    		    	<option value="<?php echo $indexVal?>"><?php echo $indexVal?></option>
    		    	<?php }?>
    		    </select>
        </div>
        <div class='col-xs-4 col-md-2 form-group'>
            <label class="control-label">Expiry Year</label>
                 <select  class="form-control card-expiry-year">
    		    	<?php
    		    	$curYear = date('Y');
    		    	for($index = $curYear ; $index < $curYear + 20; $index++){?>
    		    	<option value="<?php echo $index?>"><?php echo $index?></option>
    		    	<?php }?>
    		    </select>
        </div>

        <div class='col-xs-12 form-group text-center'>
            <button type='button' class='btn btn-primary btn-raised btn-next btn-register' data-is-last='1'>SUBMIT</button>
        </div>
    </div>
    </form>
