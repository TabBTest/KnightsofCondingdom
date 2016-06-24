<?php

use app\models\VendorMenuItem;
use app\models\TenantInfo;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\User;
use app\helpers\TenantHelper;
$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

$jsSelectState = <<<JS
$('#select-state').val('$model->state');
JS;

$jsValidateSubdomain = <<<'JS'
if (!$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").is(":checked")) {
    console.log('kamote');
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled",true);
}

$("input[name='TenantCode[SUBDOMAIN_REDIRECT]']").click(function() {
    $("input[name='TenantCode[REDIRECT_URL]']").prop("disabled", !this.checked)
});
JS;

$this->registerJs('Stripe.setPublishableKey(\'' . \Yii::$app->params['stripe_publishable_key'] . '\');', $this::POS_READY);
$this->registerJs($jsSelectState, $this::POS_READY);
$this->registerJs($jsValidateSubdomain, $this::POS_READY);
?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
    <div class="">
        <div class="alert alert-danger">
            <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
        </div>
    </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('success')){?>
    <div class="">
        <div class="alert alert-success">
            <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
        </div>
    </div>
<?php } ?>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#tab-settings">Settings</a></li>
    <li><a data-toggle="tab" href="#tab-profile">Profile</a></li>
</ul>

<div class="tab-content">
    <div id="tab-settings" class="tab-pane fade in active">
        <br>
        <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Widget</label>
            <textarea class='form-control' rows="5" cols="20">
<script type="text/javascript" id="foodapp-js" src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/js/sdk.js"></script>
<div class='restalutions-widget-button' style='position: absolute; top: 10px; right: 10px; height: 20px; width: 100px; -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 14px;
    padding: 6px 12px;
    text-align: center;background-color: #5cb85c; '>Show Menu
</div>
<div class="restalutions-widget" data-subdomain='<?php echo TenantHelper::getVendorSubdomain(\Yii::$app->user->id)?>' style='position: absolute; right: 10px; top: 50px; display: none'></div></textarea>
        </div>
    </div>

    <form action='/vendor/save-settings' method='POST'>
        <?php
        $userId = \Yii::$app->user->id;
        ?>
        <?php foreach(TenantInfo::getTenantCodes() as $codeKey => $codeDescription){
            ?>
            <?php if($codeKey == TenantInfo::CODE_SUBDOMAIN_REDIRECT){?>

                <div class='row form-group'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <input type='hidden' value='0' name='TenantCode[<?php echo $codeKey?>]'/>
                        <input type='checkbox' style='margin-left: 10px' class='' value='1' name='TenantCode[<?php echo $codeKey?>]' <?php echo TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'checked' : ''?>/>
                    </div>
                </div>
            <?php }else{?>
                <div class='row form-group'>
                    <div class='col-xs-12'>
                        <label class='form-label'><?php echo $codeDescription?></label>
                        <input class='form-control' type='text' name='TenantCode[<?php echo $codeKey?>]' value="<?php echo TenantInfo::getTenantValue($userId, $codeKey)?>"/>
                    </div>
                </div>
            <?php }?>
        <?php }?>

        <div class='row form-group'>
            <div class='col-xs-12'>
                <button class='btn btn-success'>Save</button>
            </div>
        </div>
    </form>
    </div>
    <div id="tab-profile" class="tab-pane fade">
        <br>
        <form action='/profile/save' method='POST'>
            <?php
            $userId = \Yii::$app->user->id;
            ?>
            <div class='col-xs-12 form-group'>
                <label>Name</label>
                <input type='text' class='form-control' name='User[name]' value='<?= $model->name?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>Street Address</label>
                <input type='text' class='form-control' name='User[streetAddress]'  value='<?= $model->streetAddress?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>City</label>
                <input type='text' class='form-control' name='User[city]'  value='<?= $model->city?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>State</label>
                <select class='form-control' id='select-state' name='User[state]'>
                    <option value="">State</option>
                    <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                        <option value="<?php echo $stateCode?>" <?php echo $stateCode == $model->state ? 'selected' : ''?>><?php echo $stateCode?></option>
                    <?php }?>
                </select>
            </div>
            <div class='col-xs-12 form-group'>
                <label>Phone</label>
                <?php
                echo MaskedInput::widget([
                    'name' => 'User[phoneNumber]',
                    'mask' => '999-999-9999',
                    'value' => $model->phoneNumber
                ]);
                ?>
            </div>

            <div class='col-xs-12 form-group'>
                <label>Email</label>
                <input type='text' class='form-control' name='User[email]' id='email'  value='<?php echo $model->email?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>New Password</label>
                <input type='password' class='form-control' name='password'  value=''/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>Confirm New Password</label>
                <input type='password' class='form-control' name='confirmPassword'  value=''/>
            </div>
            <div class='col-xs-12 form-group text-center'>
                <button class='btn btn-success'>Save</button>
            </div>

        </form>


        <div class='row'>
            <div class='col-xs-12 text-center'>
                <h2>Billing Info</h2>
                <?php if(Yii::$app->session->get('role') == User::ROLE_VENDOR){?>
                    <a class='btn btn-sm btn-info pull-right' href='/vendor/billing'>Billing History</a>
                <?php }?>
            </div>
        </div>

        <form action='/profile/save-billing' method='POST' id='billing-form'>
            <?php
            $userId = \Yii::$app->user->id;
            ?>
            <div class='col-xs-12 form-group'>
                <label>Current Card: <?php echo 'XXXX-XXXX-XXXX-'.$model->cardLast4?></label>
                <br />
                <label>Current Card Expires: <?php echo date('M Y', strtotime($model->cardExpiry))?></label>
                <br />

                <?php
                $cardState = $model->getCardState();

                if($cardState == User::CARD_STATE_EXPIRED){
                    ?>
                    <div class='alert alert-danger'>Card Information is expired</div>
                    <?php
                }else if($cardState == User::CARD_STATE_NEAR_EXPIRE){
                    ?>
                    <div class='alert alert-warning'>Your Card would expire in <?php echo date('M Y', strtotime(Yii::$app->user->identity->cardExpiry))?></div>
                    <?php
                }else if($cardState == User::CARD_STATE_NOT_EXISTING){
                    ?>
                    <div class='alert alert-danger'>Please add your card billing information</div>
                    <?php
                }else{
                    ;
                }
                ?>

            </div>
            <div class='col-xs-12 form-group'>
                <label>Billing Name</label>
                <input type='text' class='form-control' name='User[billingName]' value='<?= $model->billingName?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>Street Address</label>
                <input type='text' class='form-control' name='User[billingStreetAddress]'  value='<?= $model->billingStreetAddress?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>City</label>
                <input type='text' class='form-control' name='User[billingCity]'  value='<?= $model->billingCity?>'/>
            </div>
            <div class='col-xs-12 form-group'>
                <label>State</label>
                <select class='form-control' id='select-state' name='User[billingState]'>
                    <option value="">State</option>
                    <?php foreach(UtilityHelper::getStateList() as $stateCode){?>
                        <option value="<?php echo $stateCode?>" <?php echo $stateCode == $model->billingState ? 'selected' : ''?>><?php echo $stateCode?></option>
                    <?php }?>
                </select>
            </div>
            <div class='col-xs-12 form-group'>
                <label>Phone</label>
                <?php
                echo MaskedInput::widget([
                    'name' => 'User[billingPhoneNumber]',
                    'mask' => '999-999-9999',
                    'value' => $model->billingPhoneNumber
                ]);
                ?>
            </div>
            <div class='col-xs-12 col-md-6 form-group'>
                <label>Card Number</label>
                <input type='text' class='form-control card-number' placeholder='Credit Card Number'/>
            </div>
            <div class='col-xs-4 col-md-2  form-group'>
                <label>Security Code</label>
                <input type='text' class='form-control card-cvv'    placeholder='CVV'/>
            </div>

            <div class='col-xs-4 col-md-2 form-group'>
                <label>Expiry Month</label>
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
                <label>Expiry Year</label>
                <select  class="form-control card-expiry-year">
                    <option value=''>year</option>
                    <?php
                    $curYear = date('Y');
                    for($index = $curYear ; $index < $curYear + 20; $index++){?>
                        <option value="<?php echo $index?>"><?php echo $index?></option>
                    <?php }?>
                </select>
            </div>
            <div class='col-xs-12 form-group text-center'>
                <button type='button' class='btn btn-success btn-save-billing'>Save</button>
            </div>

        </form>
    </div>
</div>
