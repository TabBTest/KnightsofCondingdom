<?php
use app\models\User;
use app\helpers\UtilityHelper;
use app\models\TenantInfo;
use app\helpers\TenantHelper;
use app\models\VendorOperatingHours;

$userId = $model->id;

?>

<div id="delivery-settings" class="tab-pane <?= $_REQUEST['view'] == 'delivery-settings' ? 'active' : '' ?>">
    <form action="/vendor/save-settings" method="POST" class="vendor-settings-form" onsubmit="return VendorSettings.validateSettings()">
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
                       step="0.01"
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
                       step="0.01"
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
