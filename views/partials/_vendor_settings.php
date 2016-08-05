<?php 
use app\models\TenantInfo;
use app\helpers\TenantHelper;

$userId = $_SESSION['__id'];
?>

<div id="tab-settings" class="tab-pane <?= $_REQUEST['view'] == 'settings' ? 'active' : '' ?>">
    <br />
    <div class="form-group">
        <div class="col-xs-12">
            <label class="form-label">Your Widget</label>
            <textarea readonly
                      class="form-control copy-content"
                      id="widget-js"
                      rows="5"
                      cols="20"
            ><?= $this->render('//partials/_widget', ['model' => $model]);?></textarea>
            <br />
            <a href="javascript: void(0)"
               data-clipboard-target="#widget-js"
               class="btn btn-primary btn-copy-widget"
               data-type="widget">
                Copy to Clipboard
            </a>
            <a target="_blank"
               href="/preview/widget?id=<?= $model->id ?>"
               class="btn btn-primary btn-preview-widget">
                Preview
            </a>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-12">
            <label class="form-label">Your Button</label>
            <a href="https://<?= TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank">
                <img style="height: 100px"
                     src="https://<?= \Yii::$app->params['defaultSiteURL'] ?>/images/order-buttons/<?= $model->orderButtonImage ?>"
                />
            </a>
            <textarea readonly
                      class="form-control copy-content"
                      id="widget-button"
                      rows="5"
                      cols="20"
            ><a href="https://<?= TenantHelper::getVendorSubdomain($model->id)?>/ordering/menu" target="_blank"><img style="height: 100px" src="https://<?= \Yii::$app->params['defaultSiteURL'] ?>/images/order-buttons/<?= $model->orderButtonImage ?>" /></a></textarea>
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

        <div class="form-group" data-key="TAG_LINE">
            <label class="control-label">Tag Line</label>
            <input type="text"
                   class="form-control"
                   name="TenantCode[TAG_LINE]"
                   data-key="TAG_LINE"
                   value="<?= TenantInfo::getTenantValue($userId, 'TAG_LINE') ?>"
            />
        </div>
        <div class="form-group" data-key="EXISTING_URL">
            <label class="control-label">Existing Website</label>
            <input type="text"
                   class="form-control"
                   name="TenantCode[EXISTING_URL]"
                   data-key="EXISTING_URL"
                   value="<?= TenantInfo::getTenantValue($userId, 'EXISTING_URL') ?>"
            />
        </div>
        <div class="form-group" data-key="SUBDOMAIN">
            <label class="control-label">Subdomain</label>
            <input type="text"
                   class="form-control"
                   name="TenantCode[SUBDOMAIN]"
                   data-key="SUBDOMAIN"
                   value="<?= TenantInfo::getTenantValue($userId, 'SUBDOMAIN') ?>"
            />
        </div>
        <div class="form-group" data-key="SUBDOMAIN_REDIRECT">
            <div class="checkbox">
                <label>
                    Subdomain Redirect
                    <input type="checkbox"
                           name="TenantCode[SUBDOMAIN_REDIRECT]"
                           data-key="SUBDOMAIN_REDIRECT"
                           value="1"
                           <?= TenantInfo::getTenantValue($userId, 'SUBDOMAIN_REDIRECT') == 1 ? 'checked' : '' ?>
                    />
                </label>
            </div>
        </div>
        <div class="form-group" data-key="REDIRECT_URL">
            <label class="control-label">Redirect URL</label>
            <input type="text"
                   class="form-control"
                   name="TenantCode[REDIRECT_URL]"
                   data-key="REDIRECT_URL"
                   value="<?= TenantInfo::getTenantValue($userId, 'REDIRECT_URL') ?>"
            />
        </div>
        <div class="form-group" data-key="SALES_TAX">
            <label class="control-label">Sales Tax (in percentage)</label>
            <div class="input-group">
                <input type="number"
                       maxlength="2"
                       class="form-control"
                       name="TenantCode[SALES_TAX]"
                       data-key="SALES_TAX"
                       value="<?= TenantInfo::getTenantValue($userId, 'SALES_TAX') ?>"
                />
                <span class="input-group-addon">%</span>
            </div>
            <?php
            $salesTax = TenantInfo::getTenantValue($userId, 'SALES_TAX');
            if ($salesTax != '') { ?>
                <span>Your current sales tax is <?= $salesTax ?>%.</span>
            <?php } ?>
        </div>
        <div class="form-group" data-key="HAS_DELIVERY">
            <label class="control-label">Do you offer deliveries?</label>
            <select class="form-control" name="TenantCode[HAS_DELIVERY]" data-key="HAS_DELIVERY">
                <?php
                $hasDelivery = TenantInfo::getTenantValue($userId, 'HAS_DELIVERY');
                ?>
                <option <?= $hasDelivery == 0 ? 'selected' : '' ?> value="0">No</option>
                <option <?= $hasDelivery == 1 ? 'selected' : '' ?> value="1">Yes</option>
            </select>
        </div>
        <div class="form-group" data-key="DELIVERY_MINIMUM_AMOUNT">
            <label class="control-label">Minimum Delivery Amount</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number"
                       class="form-control"
                       name="TenantCode[DELIVERY_MINIMUM_AMOUNT]"
                       data-key="DELIVERY_MINIMUM_AMOUNT"
                       value="<?= TenantInfo::getTenantValue($userId, 'DELIVERY_MINIMUM_AMOUNT') ?>"
                />
            </div>
        </div>
        <div class="form-group" data-key="DELIVERY_CHARGE">
            <label class="control-label">Delivery Charge</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number"
                       class="form-control"
                       name="TenantCode[DELIVERY_CHARGE]"
                       data-key="DELIVERY_CHARGE"
                       value="<?= TenantInfo::getTenantValue($userId, 'DELIVERY_CHARGE') ?>"
                />
            </div>
        </div>
        <div class="row form-group">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-raised">Save</button>
            </div>
        </div>
    </form>
</div>
