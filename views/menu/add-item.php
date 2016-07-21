<form action='/menu/save-item' method='POST' enctype="multipart/form-data" id='menu-item-form'>
    <input type='hidden' name='vendorMenuId' value='<?php echo $menuItem->vendorMenuId?>'/>
    <input type='hidden' name='id' value='<?php echo $menuItem->id?>'/>
    <input type='hidden' name='menuCategoryId' value='<?php echo $menuItem->menuCategoryId?>'/>
    <input type='hidden' name='sorting' value='<?php echo $menuItem->sorting?>'/>
    <div class='row'>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Menu Name</label>
            <input type='text' class='form-control' name='name' value='<?php echo $menuItem->name?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Description</label>
            <textarea class='form-control' name='description' rows='5' cols='20'><?php echo $menuItem->description?></textarea>
        </div>
        <?php if($menuItem->hasPhoto()){?>
        <div class='col-xs-12 form-group'>
            <img src='/menu-images/<?php echo $menuItem->getPhotoPath() ?>' width='150px' height='150px'/>
        </div>
        <?php }?>
            
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Photo</label>
            <input type='file'  name='photo' value=''/>
        </div>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Price</label>
            <input type='text' name='amount' class='form-control price' value='<?php echo $menuItem->amount?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn' data-dismiss="modal">Close</button>
            <?php if($menuItem->isNewRecord == false){?>
                <button class='btn btn-danger delete-menu-item' type='button' data-menu-item-id='<?php echo $menuItem->id?>'>Delete</button>
            <?php }?>
            <button type='button' class='btn btn-success' onclick="javascript: VendorMenu.saveItem()">Save</button>
        </div>
    </div>
</form>
