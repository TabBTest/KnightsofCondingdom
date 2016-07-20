<?php 
use app\helpers\UtilityHelper;

$addOns =  $item->getAddOns();

$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : strtotime('now');
$isEdit = isset($_REQUEST['key']) ? true : false;
?>

<div class='row'>
    <div class='col-xs-12 col-sm-12 col-md-8'>
    <form id='item-order-summary' data-key='<?php echo $key?>'>
    <div class='col-xs-12'>
        <label>Quantity</label>
        <input  type='hidden' name='Orders[<?php echo $key?>]' value='<?php echo $item->id?>' min='0'/>
        <input style='width: 100px' data-is-edit='<?php echo $isEdit ? 1 : 0?>' class='form-control order-quantity order-changes' type='number' name='OrdersQuantity[<?php echo $key?>]' value='1' min='0'/>    
    </div>
    <div class='col-xs-12'>
        <h2><?php echo $item->name?> - $<?php echo UtilityHelper::formatAmountForDisplay($item->amount)?></h2>
        <?php if($item->hasPhoto()){?>
        <img src='/menu-images/<?php echo $item->getPhotoPath() ?>' width='150px' height='150px'/>
        <?php }else{?>
        <img src='/images/placeholder.png' width='150px' height='150px'/>
        <?php }?>
         <label class='form-label'><?php echo $item->description?></label>
    </div>
    
    <?php if(count($addOns) > 0){
?>
    <div class='col-xs-12'>
    <?php 
    
            
            
            
                        
            
            
            if(count($categoryExclusives) != 0 || count($itemExclusives) != 0){
    ?>
    <div class='col-xs-6'>
        <label class='control-label'>Please Choose One</label>
        <ul class='list-group'>
    <?php 
    $allAddOns = array_merge($categoryExclusives, $itemExclusives);
    foreach($allAddOns as $index => $addOn){
        ?>
      <li  data-toggle="popover" title="Description" data-menu-item-add-on-id='<?php echo $addOn->id?>' data-content="<?php echo $addOn->description?>" class="vendor-menu-item-add-on-<?php echo $item->id?> list-group-item add-ons-popover">
            <input type='radio' name='AddOnsExclusive[<?php echo $key?>]' value='<?php echo $addOn->id?>' class='order-changes add-on-<?php echo $addOn->id?>'/>&nbsp;&nbsp;&nbsp;
            <label class='form-label'><?php echo $addOn->name?> - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amount)?></label>
     
         
       </li>
       <?php 
        }
        ?>
        </ul>
    </div>
    <?php 
            }
            if(count($categoryNonExclusives) != 0 || count($itemNonExclusives) != 0){
    ?>
    <div class='col-xs-6'>
        <label class='control-label'>Additions</label>
        <ul class='list-group'>
    <?php 
    $allAddOns = array_merge($categoryNonExclusives, $itemNonExclusives);
    foreach($allAddOns as $index => $addOn){
        ?>
      <li  data-toggle="popover" title="Description" data-menu-item-add-on-id='<?php echo $addOn->id?>' data-content="<?php echo $addOn->description?>" class="vendor-menu-item-add-on-<?php echo $item->id?> list-group-item add-ons-popover">
            <input type='checkbox' name='AddOns[<?php echo $key?>][<?php echo $addOn->id?>]' value='<?php echo $addOn->id?>' class='order-changes add-on-<?php echo $addOn->id?>'/>&nbsp;&nbsp;&nbsp;
            <label class='form-label'><?php echo $addOn->name?> - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amount)?></label>
     
         
       </li>
       <?php 
        }
        ?>
        </ul>
    </div>
    <?php 
            }
    ?>
        
        </div>
    <?php 
}?>
<div class='col-xs-12'>
        <label>Note</label>
        <textarea class='form-control' rows='5' cols='25' name='OrdersNotes[<?php echo $key?>]' placeholder='Please add your extra instructions here...'></textarea>    
    </div>
    
    </form>
    </div>
    <div class='col-xs-4'>
        <div class='col-xs-12'>
            <label>Summary</label>               
        </div>
         <div class='col-xs-12 item-order-summary-content'>
                           
        </div>
    </div>
</div>
<br />
<div class='row'>
    <div class='col-xs-12' style='text-align: center'>
        <button class='btn btn-default' type='button' data-dismiss='modal'>Cancel</button>
        <button class='btn btn-success' onclick="javascript: Order.AddOrder()" type='button'><?php echo $isEdit ? 'Update' : 'Add'?></button>
    </div>
</div>
