<?php 

use app\models\VendorMenuItem;
use app\models\MenuCategories;
use app\models\VendorMenu;

$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('//partials/_show_message', []);?>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>



<div class="row">
    <div class="col-xs-12 text-center">

    </div>
</div>

<ul class="nav nav-tabs">    
<?php 
$allMenus = VendorMenu::findAll(['vendorId' => $vendorId]);
foreach($allMenus as $index => $menus){
    $className = '';
    if(isset($_REQUEST['menuId']) && $_REQUEST['menuId'] != ''){
        if($_REQUEST['menuId'] == $menus->id)
            $className = 'active';
    }else if($index == 0){
        $className = 'active';
    }
?>
 <li class="<?php echo $className?>">
 <a data-toggle="tab" href="#menu-<?php echo $menus->id?>"><?php echo $menus->name?></a></li>
<?php 
}?>
<li class="">
 <a href="javascript: VendorSettings.addMenu(<?php echo $vendorId?>);"><i class='fa fa-plus'></i> Add Menu</a></li>
</ul>
<div class="tab-content">
<?php 
foreach($allMenus as $index => $menu){
    $className = '';
    if(isset($_REQUEST['menuId']) && $_REQUEST['menuId'] != ''){
        if($_REQUEST['menuId'] == $menu->id)
            $className = 'active';
    }else if($index == 0){
        $className = 'active';
    }
?>
 <div id="menu-<?php echo $menu->id?>" class="tab-pane <?php echo $className?>">
     <div class="row">
        <div class="panel panel-primary col-xs-12" id="menu-heading">
            
            <div class="pull-right">
                <button class="btn btn-primary" data-type='category' type='button' onclick="javascript: VendorSettings.editMenu(<?php echo $menu->id?>)">Edit Menu</button>
                <button class="btn btn-primary add-category-item" data-id="<?php echo $menu->id?>">Add Category</button>
                <button type="button" class="btn btn-primary openall" data-id="<?php echo $menu->id?>">Expand All</button>
                <button type="button" class="btn btn-primary closeall" data-id="<?php echo $menu->id?>">Close All</button>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    
    <div class="panel-group categories-main-panel" id="accordion">
    <?php 
    $vendorCategories = MenuCategories::find()->where('vendorMenuId = '.$menu->id.' and isArchived = 0 order by sorting asc')->all();
    
    
    foreach($vendorCategories as $category){
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
                    <button class="btn btn-raised btn-default btn-xs add-menu-item-add-ons" data-type='category' type='button' data-menu-category-id='<?php echo $category->id?>'>Edit Add-ons</button>
                    <button class="btn btn-raised btn-default btn-xs add-menu-item" data-category-id='<?php echo $category->id?>' data-id='<?php echo $menu->id?>'>Add Menu Item</button>
                    <button class="btn btn-raised btn-default btn-xs edit-category-item" data-id='<?php echo $category->id?>'>Edit Category</button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="category<?php echo $category->id?>" class="panel-collapse collapse in">
                <div class="panel-body">
                        <p><?= $category->description?></p>
                        <div class="panel-group categories-menu-panel" id="accordion1" role="tablist" aria-multiselectable="true">
                        <?php 
                        $menuItems = VendorMenuItem::find()->where('vendorMenuId = '. $menu->id . ' and menuCategoryId = ' . $category->id.' order by sorting asc')->all();
                        ?>
                    <?php foreach($menuItems as $item){
                            if($item->isArchived == 1)
                                continue;
                        ?>
                      <div class="panel panel-default menu-panel"
                           style="margin-bottom: 20px;"
                           data-menu-id="<?= $item->id?>">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <div class="panel-title pull-left">
                                <h4>
                                    <i class="fa fa-arrows" aria-hidden="true"></i>
                                    <a class="vendor-menu-category-item-<?php echo $category->id?>" role="button" data-toggle="collapse" data-parent1="#accordion1" href="#menu<?= $item->id ?>" aria-expanded="false" aria-controls="menu<?php echo $item->id?>">
                                        <?php echo $item->name?>
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-title pull-right">
                                <label class="form-label">$<?php echo $item->amount?></label>
                                <button class="btn btn-raised btn-default btn-xs add-menu-item-add-ons" data-type='menu-item' type='button' data-menu-item-id='<?php echo $item->id?>'>Edit Add-ons</button>
                                <button class="btn btn-raised btn-default btn-xs edit-menu-item" type='button' data-menu-item-id='<?php echo $item->id?>'>Edit Menu Item</button>
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
                      <?php }?>
                    </div>
    
          </div>
          
            </div>
        </div>
    <?php }?>            
    </div>
 </div>
<?php 
}?>
    
</div>
