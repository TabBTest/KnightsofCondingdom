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

<form action='/site/reg-vendor' method='POST' id='register-form'>
    <div class='row fieldset'>
        <div class='col-xs-12 form-group text-center'>
            <h1>REGISTER</h1>
            <h3>Step 1 - Account Information</h3>
        </div>
        <div class='col-xs-6 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[businessName]' placeholder='Business Name'/>
        </div>
        <div class='col-xs-6 col-md-3 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='User[firstName]' placeholder='Business Owner - First Name'/>
        </div>
        <div class='col-xs-6 col-md-3 form-group'>
            <input type='text' class='form-control' name='User[lastName]' placeholder='Business Owner - Last Name'/>
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
                <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                    <option value="<?php echo $stateCode?>" ><?php echo $stateCode?></option>
                <?php }?>
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
            <input type='text' class='form-control' name='User[email]' id='email'  placeholder='Company Email'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <input type='text' class='form-control' name='confirmEmail' id='confirmEmail'  placeholder='Confirm Email'/>
        </div>
        <div class='col-xs-12 col-md-6 col-md-offset-3 form-group'>
            <div align="center" class="g-recaptcha" data-sitekey="<?= $params['recaptcha_site_key'] ?>"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=en">
            </script>
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
                <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                    <option value="<?php echo $stateCode?>" ><?php echo $stateCode?></option>
                <?php }?>
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
                <option value=''>Month</option>
                <?php for($index = 1 ; $index < 13; $index++){
                    $indexVal = $index < 10 ? '0'.$index : $index;
                    ?>
                    <option value="<?php echo $indexVal?>"><?php echo $indexVal?></option>
                <?php }?>
            </select>
        </div>
        <div class='col-xs-4 col-md-2 form-group'>
            <select  class="form-control card-expiry-year">
                <option value=''>Year</option>
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
