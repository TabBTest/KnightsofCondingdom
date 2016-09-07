<?php 
use app\helpers\UtilityHelper;
use app\models\VendorMenuItemAddOns;

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
            <div class="input-group">
                <input type="hidden" name="Orders[<?= $key ?>]" value="<?= $item->id ?>" min="0" />
                <input id="add-order-quantity-field"
                       style="width: 60px"
                       data-is-edit="<?= $isEdit ? 1 : 0 ?>"
                       class="form-control order-quantity order-changes"
                       type="number"
                       name="OrdersQuantity[<?= $key ?>]"
                       value="1"
                       min="1"
                />
                <button type="button"
                        class="btn btn-default btn-raised fa fa-angle-down input-num-control"
                        style="padding: 8px 16px 8px 16px; margin-left: 3px;"
                        aria-hidden="true"
                        onclick="$(this).siblings('input').val(function(index, value) {return value == 1 ? 1 : --value }); Order.showItemOrderSummary()">
                </button>
                <button type="button"
                        class="btn btn-default btn-raised fa fa-angle-up input-num-control"
                        style="padding: 8px 16px 8px 16px; margin-left: 3px;"
                        aria-hidden="true"
                        onclick="$(this).siblings('input').val(function(index, value) {return ++value });  Order.showItemOrderSummary()">
                </button>
            </div>
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
                            <?= $addOn->name ?>
                            <?php if($addOn->isSpecialOrder == 0){?>
                             - $<?= UtilityHelper::formatAmountForDisplay($addOn->amount) ?>
                            <?php }else{?>
                            <select class='order-changes' name="AddOnsSpecial[<?= $key ?>][<?= $addOn->id ?>]">
                                <?php if($addOn->isSpecialHalf){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_LEFT_HALF?>'>Left Half - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountHalf) ?></option>
                                <?php }?>
                                <?php if($addOn->isSpecialFull){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_FULL?>'>Full - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountFull) ?></option>
                                <?php }?>
                                <?php if($addOn->isSpecialHalf){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_RIGHT_HALF?>'>Right Half - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountHalf) ?></option>
                                <?php }?>
                                <?php if($addOn->isSpecialSide){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_ON_THE_SIDE?>'>On the side - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountSide) ?></option>
                                <?php }?>
                            </select>
                            <?php }?>
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
                            /> <?= $addOn->name ?>
                            <?php if($addOn->isSpecialOrder == 0){?>
                             - $<?= UtilityHelper::formatAmountForDisplay($addOn->amount) ?>
                            <?php }else{?>
                            <select class='order-changes add-on-special-<?= $addOn->id ?> form-control' name="AddOnsSpecial[<?= $key ?>][<?= $addOn->id ?>]">
                                <?php if($addOn->isSpecialHalf){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_LEFT_HALF?>'>Left Half - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountHalf) ?></option>
                                <?php }?>
                                <?php if($addOn->isSpecialFull){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_FULL?>'>Full - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountFull) ?></option>
                                <?php }?>
                                <?php if($addOn->isSpecialHalf){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_RIGHT_HALF?>'>Right Half - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountHalf) ?></option>
                                <?php }?>
                                <?php if($addOn->isSpecialSide){?>
                                    <option value='<?php echo VendorMenuItemAddOns::SPECIAL_TYPE_ON_THE_SIDE?>'>On the side - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amountSide) ?></option>
                                <?php }?>
                            </select>
                            <?php }?>
                            
                        </label>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        <?php } ?>
        <div class="form-group col-xs-12">
            <label for="order-notes-field" class="control-label">Have Special Prep Instructions?  Please Explain:</label>
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
         <button data-dismiss='modal' style='margin-right: 10px' class="btn btn-raised btn-default pull-right"
                type="button">Cancel
        </button>
    </div>
    <div class="clearfix"></div>
</div>

<script>
    var menuItemTitle = '<?= $item->name?> - $<?= UtilityHelper::formatAmountForDisplay($item->amount) ?>';
</script>
