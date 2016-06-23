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
<?php } ?>

<form action='/site/reg-customer' method='POST' id='register-form'>
    <div class='row fieldset'>
        <div class='col-xs-12 form-group text-center'>
        <h1>REGISTER</h1>
        <h3>Step 1 - Account Information</h3>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[name]' placeholder='Name'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[streetAddress]'  placeholder='Street Address'/>
        </div>
        <div class='col-xs-12 col-sm-9 col-md-4 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[city]'  placeholder='City'/>
        </div>
        <div class='col-xs-6 col-sm-3 col-md-2 form-group'>
            <select class='form-control' name='User[state]'>
                <option value="" selected disabled hidden>State</option>
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="DC">DC</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>
            </select>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <?php
            echo MaskedInput::widget([
                'name' => 'User[phoneNumber]',
                'mask' => '999-999-9999',
            ]);
            ?>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[email]' id='email'  placeholder='Email'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
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
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[billingName]' placeholder='Billing Name'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[billingStreetAddress]'  placeholder='Billing Street Address'/>
        </div>
        <div class='col-xs-12 col-sm-9 col-md-4 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[billingCity]'  placeholder='City'/>
        </div>
        <div class='col-xs-6 col-sm-3 col-md-2 form-group'>
            <select class='form-control' name='User[billingState]'>
                <option value="" selected disabled hidden>State</option>
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="DC">DC</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>
            </select>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <?php
            echo MaskedInput::widget([
                'name' => 'User[billingPhoneNumber]',
                'mask' => '999-999-9999',
            ]);
            ?>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control card-number' placeholder='Credit Card Number'/>
        </div>
        <div class='col-xs-4 col-md-2 col-md-offset-3 form-group'>
            <input type='text' class='form-control card-cvv'    placeholder='CVV'/>
        </div>

        <div class='col-xs-4 col-md-2 form-group'>
              <select  class="form-control card-expiry-month">
    		    	<?php for($index = 1 ; $index < 13; $index++){
    		    	         $indexVal = $index < 10 ? '0'.$index : $index;
    		    	    ?>
    		    	<option value="<?php echo $indexVal?>"><?php echo $indexVal?></option>
    		    	<?php }?>
    		    </select>
        </div>
        <div class='col-xs-4 col-md-2 form-group'>
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
</form>
