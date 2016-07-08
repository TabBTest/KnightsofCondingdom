<form action='/menu/save-category' method='POST' enctype="multipart/form-data" id='category-item-form'>
    <input type='hidden' name='id' value='<?php echo $category->id?>'/>
    <input type='hidden' name='vendorId' value='<?php echo $category->vendorId?>'/>
    <input type='hidden' name='sorting' value='<?php echo $category->sorting?>'/>
    <div class='row'>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Category Name</label>
            <input type='text' class='form-control' name='name' value='<?php echo $category->name?>'/>
        </div>
        <div class='col-xs-12 form-group'>
            <label class='form-label'>Description</label>
            <textarea class='form-control' name='description' rows='5' cols='20'><?php echo $category->description?></textarea>
        </div>        
        <div class='col-xs-12 form-group'>
            <button type='button' class='btn' data-dismiss="modal">Close</button>
             <?php if($category->isNewRecord == false){?>
                <button class='btn btn-danger delete-menu-category' type='button' data-menu-category-id='<?php echo $category->id?>'>Delete</button>
            <?php }?>
            <button type='button' class='btn btn-success' onclick="javascript: VendorMenu.saveCategory()">Save</button>
        </div>
    </div>
</form>