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
    <div class='col-xs-12 text-center'>
        <h1>Menu</h1>
    </div>
</div>
<form action='/ordering/save' method='POST' id='customer-order-form'>


<div class="panel-group categories-main-panel" id="accordion">
<?php 
foreach($vendorCategories as $category){
?>
    <div class="panel panel-default" data-category-id='<?php echo $category->id?>'>
        <div class="panel-heading">            
       <h4 class="panel-title">
        <a class='vendor-menu-categories' role="button" data-target="#category<?php echo $category->id?>" data-toggle="collapse" data-parent1="#accordion" href="#category<?php echo $category->id?>" aria-expanded="false" aria-controls="category<?php echo $category->id?>">
          <?php echo $category->name?>
        </a>
      </h4>

        </div>
        <div id="category<?php echo $category->id?>" class="panel-collapse collapse ">
            <div class="panel-body">
                    
                                      
                  
                  <table class='table table-condensed table-striped'>
                    <thead>
                        <tr>
                            <th width='60%'>Name</th>
                            <th width='20%'>Amount</th>
                            <th width='20%'>Quantity</th>
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
                                <td><a href='javascript: VendorMenu.openMenuDetails("<?php echo md5($item->id)?>")'><?php echo $item->name?></a>
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
                                <td><input class='form-control order-quantity' type='number' name='Orders[<?php echo $item->id?>]' value='0' min='0'/>
                                
                              
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
        <?php }?>
    </div> 

<div class='row'>
    <div class='col-xs-12 text-center'>
        <button type='button' class='btn btn-success btn-submit-order'>View Order Summary</button>
    </div>
</div>

</form>