<?php 
use app\models\VendorMenuItem;

?>

<div class="panel panel-danger categories-panel" style="margin-bottom: 20px;" data-category-id="<?php echo $category->id?>">
            <div class="panel-heading">
                <div class="panel-title pull-left">
                    <h4>
                        <i class="fa fa-arrows" aria-hidden="true"></i>
                        <a class="vendor-menu-categories" role="button" data-target="#category<?php echo $category->id?>" data-toggle="collapse" data-parent1="#accordion" href="#category<?php echo $category->id?>" aria-expanded="false" aria-controls="category<?php echo $category->id?>">
                            <?php echo $category->name?>
                        </a>
                    </h4>
                </div>
                <div class="panel-title pull-right">
                    <button class="btn btn-raised btn-default btn-xs" onclick="javascript: VendorMenu.newAddOn(<?php echo $category->id?>, 'category')" data-type='category' type='button' data-menu-category-id='<?php echo $category->id?>'>Edit Add-ons</button>
                    <button class="btn btn-raised btn-default btn-xs" onclick="javascript: VendorMenu.addItem(<?php echo $category->id?>, <?php echo $category->vendorMenuId?>)" data-category-id='<?php echo $category->id?>' data-id='<?php echo $category->vendorMenuId?>'>Add Menu Item</button>
                    <button class="btn btn-raised btn-default btn-xs" onclick="javascript: VendorMenu.editCategoryItem(<?php echo $category->id?>)" data-id='<?php echo $category->id?>'>Edit Category</button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="category<?php echo $category->id?>" class="panel-collapse collapse in">
                <div class="panel-body">
                        <p><?= $category->description?></p>
                        <div class="panel-group categories-menu-panel"  id="accordion1" role="tablist" aria-multiselectable="true">
                        <?php 
                        $menuItems = VendorMenuItem::find()->where('vendorMenuId = '. $category->vendorMenuId . ' and menuCategoryId = ' . $category->id.' order by sorting asc')->all();
                        ?>
                    <?php foreach($menuItems as $item){
                            if($item->isArchived == 1)
                                continue;
                        ?>
                      <?php echo $this->render('_item_info', ['item' => $item]);?>
                        
                      <?php }?>
                    </div>
    
          </div>
          
            </div>
        </div>