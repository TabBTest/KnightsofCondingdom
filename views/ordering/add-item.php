<?php 
use app\helpers\UtilityHelper;

$addOns =  $item->getAddOns();

$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : strtotime('now');
$isEdit = isset($_REQUEST['key']) ? true : false;
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-8">
    <form id="item-order-summary" data-key="<?= $key ?>">
        <div class="col-xs-12">
            <?php if ($item->hasPhoto()) { ?>
                <img src="/menu-images/<?= $item->getPhotoPath() ?>" width="150px" height="150px" />
            <?php } ?>
            <br />
            <label><?= $item->description ?></label>
        </div>
        <div class="form-group col-xs-12">
            <label class="control-label">Quantity</label>
            <input type="hidden" name="Orders[<?= $key ?>]" value="<?= $item->id ?>" min="0" />
            <input style="width: 100px"
                   data-is-edit="<?= $isEdit ? 1 : 0 ?>"
                   class="form-control order-quantity order-changes"
                   type="number"
                   name="OrdersQuantity[<?= $key ?>]"
                   value="1"
                   min="0"
            />
        </div>

        <?php if (count($addOns) > 0) { ?>
            <?php if (count($categoryExclusives) != 0 || count($itemExclusives) != 0) { ?>
            <div class="form-group col-xs-12 col-sm-12 col-md-6">
                <label class="control-label">Options</label>
                <?php
                $allAddOns = array_merge($categoryExclusives, $itemExclusives);
                foreach($allAddOns as $index => $addOn) { ?>
                <div>
                    <div data-toggle="popover"
                         data-placement="bottom"
                         data-content="<?= $addOn->description ?>"
                         data-menu-item-add-on-id="<?= $addOn->id ?>"
                         class="radio radio-primary vendor-menu-item-add-on-<?= $item->id ?> add-ons-popover"
                    >
                        <label>
                            <input type="radio"
                                   name="AddOnsExclusive[<?= $key ?>]"
                                   value="<?= $addOn->id ?>"
                                   class="order-changes add-on-<?= $addOn->id ?>"
                            />
                            <?= $addOn->name ?> - $<?= UtilityHelper::formatAmountForDisplay($addOn->amount) ?>
                        </label>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } if (count($categoryNonExclusives) != 0 || count($itemNonExclusives) != 0) { ?>
            <div class="form-group col-xs-12 col-sm-12 col-md-6">
                <label class="control-label">Add-ons</label>
                <?php
                $allAddOns = array_merge($categoryNonExclusives, $itemNonExclusives);
                foreach($allAddOns as $index => $addOn) { ?>
                <div>
                    <div data-toggle="popover"
                        data-placement="bottom"
                        data-content="<?= $addOn->description ?>"
                        data-menu-item-add-on-id="<?= $addOn->id ?>"
                        class="checkbox vendor-menu-item-add-on-<?= $item->id ?> add-ons-popover"
                    >
                        <label>
                            <input type="checkbox"
                                   name="AddOns[<?= $key ?>][<?= $addOn->id ?>]"
                                   value="<?= $addOn->id ?>" class="order-changes add-on-<?= $addOn->id ?>"
                            /> <?= $addOn->name ?> - $<?= UtilityHelper::formatAmountForDisplay($addOn->amount) ?>
                        </label>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        <?php } ?>
        <div class="form-group col-xs-12">
            <label for="order-notes-field" class="control-label">Note</label>
            <div>
                <textarea class="form-control"
                          rows="3"
                          cols="25"
                          name="OrdersNotes[<?= $key ?>]"
                          id="order-notes-field"></textarea>
                <span class="help-block">Please add your extra instructions here.</span>
            </div>
        </div>
    </form>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="col-xs-12">
            <label>Summary</label>
        </div>
        <div class="col-xs-12 item-order-summary-content">
        </div>
    </div>
</div>
<br />
<div class="row">
    <div class="col-xs-12">
        <button class="btn btn-raised btn-primary pull-right"
                onclick="javascript: Order.AddOrder()"
                type="button"><?= $isEdit ? 'Update' : 'Add' ?>
        </button>
    </div>
    <div class="clearfix"></div>
</div>

<script>
    var menuItemTitle = '<?= $item->name?> - $<?= UtilityHelper::formatAmountForDisplay($item->amount) ?>';
</script>
