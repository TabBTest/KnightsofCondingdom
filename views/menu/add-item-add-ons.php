<?php
use app\models\VendorMenuItemAddOns;
use app\models\VendorMenuItem;
use app\models\MenuCategories;
?>
<form action="/menu/save-item-add-ons" method="POST" enctype="multipart/form-data" id="menu-item-add-ons-form">
    <input type='hidden' name='vendorMenuItemId' value='<?php echo $menuItemAddOns->vendorMenuItemId?>'/>
    <input type='hidden' name='menuCategoryId' value='<?php echo $menuItemAddOns->menuCategoryId?>'/>
    <input type='hidden' name='id' value='<?php echo $menuItemAddOns->id?>'/>
    <input type='hidden' name='sorting' value='<?php echo $menuItemAddOns->sorting?>'/>
    <div class='row'>
        <div class='col-xs-6 form-group'>
            <label class='form-label'>Add-on Type</label>
            <select  name='isExclusive' class='form-control short-input'>
                <option <?php echo $menuItemAddOns->isExclusive == 0 ? 'selected' : ''?> value='0'>Addition</option>
                <option <?php echo $menuItemAddOns->isExclusive == 1 ? 'selected' : ''?> value='1'>Exclusive</option>
            </select>
            
        </div>
        <div class='col-xs-6 form-group'>
            
            <input type='hidden' name='isSpecialOrder' value="0"/>
            <div class="checkbox">
                <label class='form-label'>
                    <input type='checkbox' name='isSpecialOrder' class='special-order' <?php echo $menuItemAddOns->isSpecialOrder == 1 ? 'checked' : ''?> onclick="javascript: VendorMenu.specialOrderChange();"  value="1"/>
                    Is Special Order?
                </label>
            </div>

                                    
           
            
        </div>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Add-on Name</label>
            <input type='text' class='form-control' name='name' value='<?php echo $menuItemAddOns->name?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Description</label>
            <textarea class='form-control' name='description' rows='2' cols='20'><?php echo $menuItemAddOns->description?></textarea>
        </div>
       
        <div class='col-xs-12 form-group non-special-price'>
            <label class='form-label'>Price</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type='text' name='amount' class='form-control price short-input' value='<?php echo $menuItemAddOns->amount?>'/>
            </div>
        </div>
        
        <div class='col-xs-12 form-group special-price'>
            <input type='hidden' name='isSpecialFull' value="0"/>
            <div class="checkbox">
                <label class='form-label'>
                    <input type='checkbox' name='isSpecialFull' class='' <?php echo $menuItemAddOns->isSpecialFull == 1 ? 'checked' : ''?> value="1"/>
                    Full - Price
                </label>
            </div>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type='text' name='amountFull' class='form-control price-custom short-input' value='<?php echo $menuItemAddOns->amountFull?>'/>
            </div>
        </div>
        
        <div class='col-xs-12 form-group special-price'>
            <input type='hidden' name='isSpecialHalf' value="0"/>
            <div class="checkbox">
                <label class='form-label'>
                    <input type='checkbox' name='isSpecialHalf' class='' <?php echo $menuItemAddOns->isSpecialHalf == 1 ? 'checked' : ''?> value="1"/>
                    Half - Price
                </label>
            </div>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type='text' name='amountHalf' class='form-control price-custom short-input' value='<?php echo $menuItemAddOns->amountHalf?>'/>
            </div>
        </div>
        
        <div class='col-xs-12 form-group special-price'>
            <input type='hidden' name='isSpecialSide' value="0"/>
            <div class="checkbox">
                <label class='form-label'>
                    <input type='checkbox' name='isSpecialSide' class='' <?php echo $menuItemAddOns->isSpecialSide == 1 ? 'checked' : ''?> value="1"/>
                    On The Side - Price
                </label>
            </div>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type='text' name='amountSide' class='form-control price-custom short-input' value='<?php echo $menuItemAddOns->amountSide?>'/>
            </div>
        </div>
        
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn' data-dismiss="modal">Close</button>
            <?php if($menuItemAddOns->isNewRecord == false){?>
            <?php if($menuItemAddOns->menuCategoryId > 0){?>
                <button class='btn btn-danger delete-menu-item-add-on'  data-type='category' type='button' data-menu-category-id='<?php echo $menuItemAddOns->menuCategoryId?>' data-menu-item-add-on-id='<?php echo $menuItemAddOns->id?>'>Delete</button>
            <?php }else{?>
                <button class='btn btn-danger delete-menu-item-add-on'  data-type='menu-item' type='button' data-menu-item-id='<?php echo $menuItemAddOns->vendorMenuItemId?>' data-menu-item-add-on-id='<?php echo $menuItemAddOns->id?>'>Delete</button>
            <?php }?>
                
            <?php }?>
            <button type='button' class='btn btn-raised btn-primary' onclick="javascript: VendorMenu.saveItemAddOns()">Save</button>
        </div>
    </div>
</form>

<ul class="nav nav-tabs">
    
    <li class="active"><a data-toggle="tab" href="#tab-exclusive">Exclusive</a></li>
    <li><a data-toggle="tab" href="#tab-additions">Additions</a></li>
</ul>

<div class="tab-content">    
<?php 
$item = false;
if($menuItemAddOns->menuCategoryId > 0){
    $item = MenuCategories::findOne($menuItemAddOns->menuCategoryId);
}else{
    $item = VendorMenuItem::findOne($menuItemAddOns->vendorMenuItemId);
}
?>
    <?php echo $this->render('_addons_list', ['item' => $item, 'list' => $exclusiveList, 'tabName' => 'tab-exclusive', 'show' => true]);?>
    <?php echo $this->render('_addons_list', ['item' => $item, 'list' => $nonExclusiveList, 'tabName' => 'tab-additions', 'show' => false]);?>    
</div>
<script>
VendorMenu.setupUI();
</script>
