<?php 

use app\models\VendorMenuItem;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\TenantInfo;
use app\models\VendorOperatingHours;
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
    <div class='col-xs-12'>
        <label class='form-label'>Name: <?php echo $vendor->businessName;?></label>
    </div>
    <div class='col-xs-12'>
        <label class='form-label'>Contact Number: <?php echo $vendor->getContactNumber();?></label>
    </div>
    <div class='col-xs-12'>
        <label class='form-label'>Time To Pickup: <?php echo $vendor->getTimeToPickUpDisplay();?></label>
    </div>
    <?php 
    $hasDelivery = TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_HAS_DELIVERY) == 1 ? true : false;
    ?>
    <div class='col-xs-12'>
        <label class='form-label'>Has Delivery: <?php echo  $hasDelivery ? 'Yes' : 'No';?></label>
    </div>
    <?php if($hasDelivery){?>
    <div class='col-xs-12'>
        <label class='form-label'>Minimum Delivery Amount: $<?php echo  UtilityHelper::formatAmountForDisplay(TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_DELIVERY_MINIMUM_AMOUNT));?></label>
    </div>
     <div class='col-xs-12'>
        <label class='form-label'>Delivery Charge: $<?php echo  UtilityHelper::formatAmountForDisplay(TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_DELIVERY_CHARGE));?></label>
    </div>
    <?php }?>
    <div class='col-xs-12'>
        <label class='form-label'>Time To Pickup: <?php echo $vendor->getTimeToPickUpDisplay();?></label>
    </div>
    
    
    <div class='col-xs-4'>
    <div class='col-xs-12' style='text-align: center'>
        <label class='form-label'>Operating Hours <?php echo UtilityHelper::getTimeZoneDisplay($vendor->timezone)?></label>
    </div>
    <?php 
    $operatingTime = UtilityHelper::getOperatingTime();
    foreach(UtilityHelper::getDays() as $key => $val){
        $operatingHours = VendorOperatingHours::getVendorOperatingHours($vendor->id, $key);
    ?>
    <div class='col-xs-12' style='margin-bottom: 10px'>
         <div class='col-xs-3'>
            <label for="inputEmail3" class="col-xs-2 control-label pull-right"><?php echo $val?></label>
         </div>
     
         <div class='col-xs-8'>
            <?php foreach($operatingHours as $operatingHour){
                
             ?>
                <div class='col-xs-12'><label class='control-label pull-right'>
                <?php foreach($operatingTime as $val => $display){?>
                <?php echo $val == $operatingHour->startTime ?  $display : ''?>
                <?php }?>
                -
                <?php foreach($operatingTime as $val => $display){?>
                <?php echo $val == $operatingHour->endTime ?  $display : ''?>
                <?php }?>
                </label></div>
             <?php
             }
             ?>
         </div>
    </div>
        
                    
    <?php 
    }      
    ?>
    </div>
</div>

<div class='row'>

    <div class='col-xs-8'>
        
        <div class='col-xs-12 text-center'>
            <h1>Menu</h1>
        </div>
        
        
        <div class='col-xs-12 form-group'>
            &nbsp;&nbsp;&nbsp;&nbsp;<a style='margin-left: 10px' href="#" class="btn btn-default openall pull-right">Expand All</a> &nbsp;&nbsp;<a href="#" class="btn btn-default closeall pull-right">Close All</a>
        </div>
        
    <div class="panel-group categories-main-panel" id="accordion">
        <?php 
        foreach($vendorCategories as $category){
        ?>
        <div class='col-xs-12'>
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
                        <ul class='list-group'>
                           <?php 
                            $menuItems = VendorMenuItem::find()->where('vendorMenuId = '. $menu->id . ' and menuCategoryId = ' . $category->id.' order by sorting asc')->all();
                            ?>                                  
                                
                               
                            <?php foreach($menuItems as $item){
                                    if($item->isArchived == 1)
                                        continue;
                                    
                            ?>
                            <li  class='list-group-item add-to-cart' data-menu-item-id="<?php echo $item->id?>">
                                    <label class='form-label menu-name'><?php echo $item->name?> </label>
                                    <span class='pull-right'>$<?php echo UtilityHelper::formatAmountForDisplay($item->amount)?></span>
                                    <br />
                                    <label class='form-label menu-description'><i><?php echo $item->description?></i></label>
                            </li>                                 
                            <?php 
                                  }
                                ?>
                            
                        </ul>
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
<style>
li.add-to-cart:hover{
	border: 2px solid green;
}
</style>