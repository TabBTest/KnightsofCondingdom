<?php 
use app\models\User;
use app\helpers\UtilityHelper;
use yii\widgets\MaskedInput;
?>
<div id="billing-info" class="tab-pane <?php echo $_REQUEST['view'] == 'billing' ? 'active' : ''?>">
        <br />
        <form action='/profile/save-billing' method='POST' id='billing-form'>
            <?php
            $userId = $model->id;
            ?>
            <input type='hidden' value='<?php echo $userId?>' name='userId'/>
            <div class='col-xs-12 form-group'>                                

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
            <div class='col-xs-12 form-group' style='margin: 0'>
                <label>Phone</label>            
            </div>
            <div class='col-xs-12 col-md-2 form-group phone' data-key='phone'>
                <input type='tel' class='form-control short-input' name='User[billingPhoneAreaCode]' value='<?php echo $model->billingPhoneAreaCode?>' maxlength="3" placeholder='Area Code'/>
            </div>
            <div class='col-xs-12 col-md-2 form-group phone' data-key='phone'>
                <input type='tel' class='form-control short-input' name='User[billingPhone3]' value='<?php echo $model->billingPhone3?>' maxlength="3" placeholder='XXX'/>
            </div>
            <div class='col-xs-12 col-md-8 form-group phone' data-key='phone'>
                <input type='tel' class='form-control short-input' name='User[billingPhone4]' value='<?php echo $model->billingPhone4?>' maxlength="4" placeholder='XXXX'/>
            </div>
            <div class='col-xs-12 col-md-6 form-group'>
                <label>Card Number</label>
                <input type='text' class='form-control card-number'  name='cc' placeholder='<?php echo 'XXXX-XXXX-XXXX-'.$model->cardLast4?>'/>
            </div>
            <div class='col-xs-4 col-md-2  form-group'>
                <label>Security Code</label>
                <input type='text' class='form-control card-cvv'  name='cvv'  placeholder='CVV'/>
            </div>

            <div class='col-xs-4 col-md-2 form-group'>
                <label>Expiry Month</label>
                <select name='ccMonth' class="form-control card-expiry-month">
                    <option value=''>Month</option>
                    <?php for($index = 1 ; $index < 13; $index++){
                        $indexVal = $index < 10 ? '0'.$index : $index;
                        ?>
                        <option <?php echo $indexVal == date('m', strtotime($model->cardExpiry)) ? 'selected' : ''?> value="<?php echo $indexVal?>"><?php echo $indexVal?></option>
                    <?php }?>
                </select>
            </div>
            <div class='col-xs-4 col-md-2 form-group'>
                <label>Expiry Year</label>
                <select name='ccYear' class="form-control card-expiry-year">
                    <option value=''>Year</option>
                    <?php
                    $curYear = date('Y');
                    for($index = $curYear ; $index < $curYear + 20; $index++){?>
                        <option <?php echo $index == date('Y', strtotime($model->cardExpiry)) ? 'selected' : ''?> value="<?php echo $index?>"><?php echo $index?></option>
                    <?php }?>
                </select>
            </div>
            <div class='col-xs-12 form-group text-center'>
                <button type='button' class='btn btn-primary btn-raised btn-save-billing'>Save</button>
            </div>

                
        </form>
    </div>
