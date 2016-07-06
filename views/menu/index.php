<?php 

use app\models\VendorMenuItem;
use app\models\MenuCategories;
$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if(\Yii::$app->getSession()->hasFlash('error')){?>
 <div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
 </div>
<?php } ?>
<?php if(\Yii::$app->getSession()->hasFlash('success')){?>
 <div class="">
<div class="alert alert-success">
    <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
</div>
 </div>
<?php } ?>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1><?php echo Yii::$app->session->get('name')?></h1>
    </div>
</div>



<div class='row'>
    <div class='col-xs-12 text-center'>
        &nbsp;&nbsp;&nbsp;&nbsp;<a style='margin-left: 10px' href="#" class="btn btn-default openall pull-right">Expand All</a> &nbsp;&nbsp;<a href="#" class="btn btn-default closeall pull-right">Close All</a>
    </div>
</div>

<div class='row'>
    <div class='col-xs-12 text-center'>
        <h1>Menu <button class='btn btn-info pull-right add-category-item' data-id='<?php echo $menu->id?>'>Add Category</button></h1>
    </div>
</div>


<div class="panel-group categories-main-panel" id="accordion">
<?php 
foreach($vendorCategories as $category){
?>
    <div class="panel panel-default categories-panel" data-category-id='<?php echo $category->id?>'>
        <div class="panel-heading">            
       <h4 class="panel-title">
        <i class="fa fa-arrows" aria-hidden="true"></i>      
        <a class='vendor-menu-categories' role="button" data-target="#category<?php echo $category->id?>" data-toggle="collapse" data-parent1="#accordion" href="#category<?php echo $category->id?>" aria-expanded="false" aria-controls="category<?php echo $category->id?>">
          <?php echo $category->name?>
        </a>
                
        <button class='btn btn-info btn-xs pull-right add-menu-item' data-category-id='<?php echo $category->id?>' data-id='<?php echo $menu->id?>'>Add Menu Item</button>
        <button style='margin-right: 10px;' class='btn btn-info btn-xs pull-right edit-category-item' data-id='<?php echo $category->id?>'>Edit Category</button>&nbsp;&nbsp;&nbsp;
      </h4>

        </div>
        <div id="category<?php echo $category->id?>" class="panel-collapse collapse in">
            <div class="panel-body">
                    
                    <?php echo nl2br($category->description)?>
                    <div class="panel-group categories-menu-panel" id="accordion1" role="tablist" aria-multiselectable="true">
                    <?php 
                    $menuItems = VendorMenuItem::find()->where('vendorMenuId = '. $menu->id . ' and menuCategoryId = ' . $category->id.' order by sorting asc')->all();
                    ?>
                <?php foreach($menuItems as $item){
                        if($item->isArchived == 1)
                            continue;
                    ?>
                  <div class="panel panel-default menu-panel" data-menu-id='<?php echo $item->id?>'>
                    <div class="panel-heading" role="tab" id="headingOne">
                      <h4 class="panel-title">
                        <i class="fa fa-arrows" aria-hidden="true"></i>
                        <a class='vendor-menu-category-item-<?php echo $category->id?>' role="button" data-toggle="collapse" data-parent1="#accordion1" href="#menu<?php echo $item->id?>" aria-expanded="false" aria-controls="menu<?php echo $item->id?>">
                          <?php echo $item->name?>
                        </a>
                        <label class='form-label' style='float: right; margin-right: 10px;'>$<?php echo $item->amount?></label>
                      </h4>
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
                        <div class='col-xs-6'>
                            <label class='form-label'><?php echo $item->description?></label>
                        </div>
                        <div class='col-xs-3'>
                            <button class='btn btn-info edit-menu-item' type='button' data-menu-item-id='<?php echo $item->id?>'>Edit</button>
                            <button class='btn btn-danger delete-menu-item' type='button' data-menu-item-id='<?php echo $item->id?>'>Delete</button>
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