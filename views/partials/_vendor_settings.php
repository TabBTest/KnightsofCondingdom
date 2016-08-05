<?php 
use app\models\TenantInfo;
use app\helpers\TenantHelper;

$userId = $_SESSION['__id'];
?>

<div id="tab-settings" class="tab-pane <?php echo $_REQUEST['view'] == 'settings' ? 'active' : ''?>">
        <br>
        <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Widget</label>
            <textarea class='form-control copy-content' id='widget-js' rows="5" cols="20"><?php echo $this->render('//partials/_widget', ['model' => $model]);?></textarea>
<br />
<a href="javascript: void(0)" data-clipboard-target="#widget-js" class="btn btn-primary btn-copy-widget" data-type='widget'>Copy to Clipboard</a>
<a target='_blank' href="/preview/widget?id=<?php echo $model->id?>" class="btn btn-primary btn-preview-widget">Preview</a>
        </div>
    </div>



    <div class='row form-group'>
        <div class='col-xs-12'>
            <label class='form-label'>Your Button</label>
            <a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="https://<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-buttons/<?= $model->orderButtonImage ?>" /></a>
            <textarea class='form-control copy-content' id='widget-button' rows="5" cols="20"><a href="https://<?php echo TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style='height: 100px' src="https://<?php echo \Yii::$app->params['defaultSiteURL']?>/images/order-buttons/<?= $model->orderButtonImage ?>" /></a></textarea>
            <br />
<a href="javascript: void(0)" data-clipboard-target="#widget-button" class="btn btn-primary btn-copy-widget" data-type='button'>Copy to Clipboard</a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#change-button-modal">
                Change Order Now Button
            </button>
            <div class="modal fade" id="change-button-modal" tabindex="-1" role="dialog" aria-labelledby="change-button-modal-label">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="change-button-modal-label">Select a Button Style</h4>
                        </div>
                        <div class="modal-body">
                            <div class="radio">
                                <label><input type="radio" name="order-now-button-select" value="order-now-01.png"><img src="/images/order-buttons/order-now-01.png" alt="Order Now Button 1"></label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="order-now-button-select" value="order-now-02.png"><img src="/images/order-buttons/order-now-02.png" alt="Order Now Button 2"></label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="order-now-button-select" value="order-now-03.png"><img src="/images/order-buttons/order-now-03.png" alt="Order Now Button 3"></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal" id="select-order-now-button">Select Image</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="/vendor/save-settings" method="POST" class="vendor-settings-form" onsubmit="return VendorSettings.validateSettings()">
        <input type="hidden" value="<?= $model->orderButtonImage ?>" name="orderButtonImage" id="order-now-button-field" />
        <?php foreach(TenantInfo::getTenantCodes() as $codeKey => $codeDescription) { ?>
            <?php if($codeKey == TenantInfo::CODE_SUBDOMAIN_REDIRECT) {?>
                <div class="row form-group" data-key="<?= $codeKey ?>">
                    <div class="col-xs-12 checkbox">
                        <input type="hidden" value="0" name="TenantCode[<?= $codeKey ?>]" />
                        <label class="form-label"><?php echo $codeDescription?>
                            <input type="checkbox"
                                   value='1'
                                   data-key="<?= $codeKey ?>"
                                   name="TenantCode[<?= $codeKey ?>]"
                                <?= TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'checked' : '' ?>
                            />
                        </label>
                    </div>
                </div>
            <?php } else if($codeKey == TenantInfo::CODE_HAS_DELIVERY) { ?>
             <div class="row form-group" data-key="<?= $codeKey?>">
                 <div class="col-xs-12">
                     <label class="form-label"><?= $codeDescription ?></label>
                     <select class="form-control short-input" data-key="<?= $codeKey ?>" name="TenantCode[<?= $codeKey ?>]">
                         <option <?= TenantInfo::getTenantValue($userId, $codeKey) == 0 ? 'selected' : '' ?> value="0">No</option>
                         <option <?= TenantInfo::getTenantValue($userId, $codeKey) == 1 ? 'selected' : '' ?> value="1">Yes</option>
                     </select>
                    </div>
             </div>
            <?php } else { ?>
                <div class="row form-group" data-key="<?= $codeKey ?>">
                    <div  class="form-inline">
                        <div class="col-xs-12">
                            <label class="form-label"><?= $codeDescription ?></label>
                            <?php if($codeKey == TenantInfo::CODE_SALES_TAX
                                && TenantInfo::getTenantValue($userId, $codeKey) != '') { ?>
                                <br />
                                <label class='form-label'>
                                    <i><?= 'Your Sales Tax is ' . TenantInfo::getTenantValue($userId, $codeKey).'%' ?></i>
                                </label>
                            <?php } ?>
                        <div class="input-group">
                            <?= TenantInfo::isDollarAmount($codeKey) ? '$' : ''?>
                            <input class="form-control <?= TenantInfo::getCustomClasses($codeKey) ?>"
                                   <?= TenantInfo::isPercentage($codeKey) ? 'maxlength="2"' : '' ?>
                                   data-key="<?= $codeKey ?>"
                                   type="text" name="TenantCode[<?= $codeKey ?>]"
                                   value="<?= TenantInfo::getTenantValue($userId, $codeKey) ?>"
                            /> <?= TenantInfo::isPercentage($codeKey) ? '%' : '' ?>
                        </div>
                    </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="row form-group">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-raised">Save</button>
            </div>
        </div>
    </form>
    </div>
