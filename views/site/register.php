<?php

use yii\widgets\MaskedInput;
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

<form action='/site/reg-vendor' method='POST' id='register-form'>
<div class='row fieldset'>
    <div class='col-xs-12 form-group text-center'>
    <h1>REGISTER</h1>
    <h3>Step 1 - Account Information</h3>
    </div>
    <div class='col-xs-12 form-group'>
        <input type='text' class='form-control' name='User[name]' placeholder='Company Name'/>
    </div>
    <div class='col-xs-12 form-group'>
        <input type='text' class='form-control' name='User[address]'  placeholder='Company Address'/>
    </div>
    <div class='col-xs-12 form-group'>
<!--         <input type='text' class='form-control' name='User[phoneNumber]' id='phone' placeholder='Company Phone Number'/> -->
         <?php
        echo MaskedInput::widget([
            'name' => 'User[phoneNumber]',
            'mask' => '999-999-9999',
        ]);


        ?>

    </div>

    <div class='col-xs-6 form-group'>
        <input type='text' class='form-control' name='User[email]' id='email'  placeholder='Company Email'/>
    </div>
    <div class='col-xs-6 form-group'>
        <input type='text' class='form-control' name='confirmEmail' id='confirmEmail'  placeholder='Confirm Email'/>
    </div>
    <div class='col-xs-12 form-group text-center'>
        <button type='button' class='btn btn-success btn-next'>NEXT</button>
    </div>
</div>
<div class='row fieldset' style='display: none'>
    <div class='col-xs-12 form-group text-center'>
        <h1>REGISTER</h1>
        <h3>Step 2 - Payment Information</h3>
    </div>
    <div class='col-xs-12 form-group'>
        <input type='text' class='form-control' name='User[billingName]' placeholder='Billing Name'/>
    </div>
    <div class='col-xs-12 form-group'>
        <input type='text' class='form-control' name='User[billingAddress]'  placeholder='Billing Address'/>
    </div>
    <div class='col-xs-7 form-group'>
        <input type='text' class='form-control card-number' placeholder='Credit Card Number'/>
    </div>
    <div class='col-xs-1 form-group'>
        <input type='text' class='form-control card-cvv'    placeholder='CVV'/>
    </div>

    <div class='col-xs-2 form-group'>
          <select  class="form-control card-expiry-month">
		    	<?php for($index = 1 ; $index < 13; $index++){
		    	         $indexVal = $index < 10 ? '0'.$index : $index;
		    	    ?>
		    	<option value="<?php echo $indexVal?>"><?php echo $indexVal?></option>
		    	<?php }?>
		    </select>
    </div>
    <div class='col-xs-2 form-group'>
             <select  class="form-control card-expiry-year">
		    	<?php
		    	$curYear = date('Y');
		    	for($index = $curYear ; $index < $curYear + 20; $index++){?>
		    	<option value="<?php echo $index?>"><?php echo $index?></option>
		    	<?php }?>
		    </select>
    </div>

    <div class='col-xs-12 form-group text-center'>
        <button type='button' class='btn btn-success btn-next btn-register' data-is-last='1'>SUBMIT</button>
    </div>
</div>
</form>
