<form action='/menu/save-item-add-ons' method='POST' enctype="multipart/form-data" id='menu-item-add-ons-form'>
    <input type='hidden' name='vendorMenuItemId' value='<?php echo $menuItemAddOns->vendorMenuItemId?>'/>
    <input type='hidden' name='id' value='<?php echo $menuItemAddOns->id?>'/>
    <input type='hidden' name='sorting' value='<?php echo $menuItemAddOns->sorting?>'/>
    <div class='row'>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Add-on Name</label>
            <input type='text' class='form-control' name='name' value='<?php echo $menuItemAddOns->name?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Description</label>
            <textarea class='form-control' name='description' rows='5' cols='20'><?php echo $menuItemAddOns->description?></textarea>
        </div>
       
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Price</label>
            <input type='text' name='amount' class='form-control price' value='<?php echo $menuItemAddOns->amount?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn' data-dismiss="modal">Close</button>
            <?php if($menuItemAddOns->isNewRecord == false){?>
                <button class='btn btn-danger delete-menu-item-add-on' type='button' data-menu-item-add-on-id='<?php echo $menuItemAddOns->id?>'>Delete</button>
            <?php }?>
            <button type='button' class='btn btn-success' onclick="javascript: VendorMenu.saveItemAddOns()">Save</button>
        </div>
    </div>
</form>