<?php 

use app\models\VendorMenuItem;
use app\models\MenuCategories;
use app\models\VendorMenu;
use app\helpers\UtilityHelper;

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
          <?php echo $this->render('_category_info', ['category' => $category]);?>
    <?php }?>            
    </div>
 </div>
<?php 
}?>
    
</div>
