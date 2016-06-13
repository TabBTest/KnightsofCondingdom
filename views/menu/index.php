<?php 

use app\models\VendorMenuItem;
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
        <h1>Menu <button class='btn btn-info pull-right add-menu-item' data-id='<?php echo $menu->id?>'>Add Menu Item</button></h1>
    </div>
</div>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php 
    $menuItems = VendorMenuItem::findAll(['vendorMenuId' => $menu->id]);
    ?>
<?php foreach($menuItems as $item){
        if($item->isArchived == 1)
            continue;
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#menu<?php echo $item->id?>" aria-expanded="false" aria-controls="menu<?php echo $item->id?>">
          <?php echo $item->name?>
        </a>
        <label class='form-label' style='float: right; margin-right: 10px;'>$<?php echo $item->amount?></label>
      </h4>
    </div>
    <div id="menu<?php echo $item->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
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