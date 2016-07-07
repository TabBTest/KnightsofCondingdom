<?php 

use app\models\VendorMenuItem;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
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

    <div class='col-xs-8'>
        <div class='col-xs-12 text-center'>
            <h1>Menu</h1>
        </div>
    <div class="panel-group categories-main-panel" id="accordion">
    <?php 
    foreach($vendorCategories as $category){
    ?>
        <div class='col-xs-6'>
        <div class="panel panel-default" data-category-id='<?php echo $category->id?>'>
            <div class="panel-heading">            
           <h4 class="panel-title">
            <a class='vendor-menu-categories' role="button" data-target="#category<?php echo $category->id?>" data-toggle="collapse" data-parent1="#accordion" href="#category<?php echo $category->id?>" aria-expanded="false" aria-controls="category<?php echo $category->id?>">
              <?php echo $category->name?>
            </a>
          </h4>
    
            </div>
            <div id="category<?php echo $category->id?>" class="panel-collapse collapse in">
                <div class="panel-body">
                        
                                          
                      
                      <table class='table table-condensed table-striped'>
                        <thead>
                            <tr>
                                <th width='60%'>Name</th>
                                <th width='20%'>Amount</th>
                                <th width='20%'>&nbsp;</th>
                            </tr>
                        </thead>
                        
                        <?php 
                        $menuItems = VendorMenuItem::find()->where('vendorMenuId = '. $menu->id . ' and menuCategoryId = ' . $category->id.' order by sorting asc')->all();
                        ?>                                  
                            
                            <tbody>
                        <?php foreach($menuItems as $item){
                                if($item->isArchived == 1)
                                    continue;
                                
                        ?>
                                 <tr>
                                    <td><?php echo $item->name?>&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-info' href='javascript: VendorMenu.openMenuDetails("<?php echo md5($item->id)?>")'>Details</a>
                                        <div class='menu-details-<?php echo md5($item->id)?>' style='display: none'>
                                        <?php if($item->hasPhoto()){?>
                                        <img src='/menu-images/<?php echo $item->getPhotoPath() ?>' width='150px' height='150px'/>
                                        <?php }else{?>
                                        <img src='/images/placeholder.png' width='150px' height='150px'/>
                                        <?php }?>
                                         <label class='form-label'><?php echo $item->description?></label>
                                        </div>
                                    </td>
                                    <td>$<?php echo UtilityHelper::formatAmountForDisplay($item->amount)?></td>
                                    <td>
                                    <a href='javascript: void(0)' class='add-to-cart' data-menu-item-id="<?php echo $item->id?>"><i class="fa fa-plus" aria-hidden="true"></i></a>                               
                                  
                                </td>
                                </tr>
                        <?php 
                              }
                            ?>
                        </tbody>
                        </table>
                    </div>
    
          </div>
          
            </div>
            </div>
            <?php }?>
        </div> 
    </div>
    <div class='col-xs-4'>
        <div class='col-xs-12 text-center'>
            <h1>Order Summary</h1>
        </div>
        <form id='main-order-summary' action='/ordering/save' method="POST">
            <div class='col-xs-12 main-order-summary-content'>
                     <div class='col-xs-12 text-center'>
                        <label>Please add your order now</label>
                    </div>          
            </div>                      
        </form>
    </div>
</div>