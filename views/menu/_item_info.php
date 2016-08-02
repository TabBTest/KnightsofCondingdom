<?php 
use app\helpers\UtilityHelper;
?>
<div class="panel panel-default menu-panel"
   style="margin-bottom: 20px;"
   data-menu-id="<?= $item->id?>">
   
        <div class="panel-heading" role="tab" id="headingOne">
            <div class="panel-title pull-left">
                <h4>
                    <i class="fa fa-arrows" aria-hidden="true"></i>
                    <a class="vendor-menu-category-item-<?php echo $item->menuCategoryId?>" role="button" data-toggle="collapse" data-parent1="#accordion1" href="#menu<?= $item->id ?>" aria-expanded="false" aria-controls="menu<?php echo $item->id?>">
                        <?php echo $item->name?>
                    </a>
                </h4>
            </div>
            <div class="panel-title pull-right">
                <label class="form-label">$<?php echo UtilityHelper::formatAmountForDisplay($item->amount)?></label>
                <button class="btn btn-raised btn-default btn-xs " onclick="javascript: VendorMenu.newAddOn(<?php echo $item->id?>, 'menu-item')"  data-type='menu-item' type='button' data-menu-item-id='<?php echo $item->id?>'>Edit Add-ons</button>
                <button class="btn btn-raised btn-default btn-xs " onclick="javascript: VendorMenu.editItem(<?php echo $item->id?>)" type='button'>Edit Menu Item</button>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="menu<?php echo $item->id?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <div class='col-xs-3'>
                <?php if($item->hasPhoto()){?>
                <img src='/menu-images/<?php echo $item->getPhotoPath() ?>' width='150px' height='150px'/>
                <?php }else{?>
                <img src='/images/placeholder.png' width='150px' height='150px'/>
                <?php }?>
                
            </div>
            <div class='col-xs-4'>
                <label class="form-label"><?php echo $item->description?></label>
            </div>
             <div class="col-xs-3">
            </div>
          </div>
        </div>
</div>