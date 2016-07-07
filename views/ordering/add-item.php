<?php 
use app\helpers\UtilityHelper;

$addOns =  $item->getAddOns();

$key = strtotime('now');
?>

<div class='row'>
    <div class='col-xs-8'>
    <form id='item-order-summary'>
    <div class='col-xs-12'>
        <label>Quantity</label>
        <input  type='hidden' name='Orders[<?php echo $key?>]' value='<?php echo $item->id?>' min='0'/>
        <input style='width: 100px' class='form-control order-quantity order-changes' type='number' name='OrdersQuantity[<?php echo $key?>]' value='1' min='0'/>    
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
        <h3>Add-ons</h3>
        <ul class='list-group'>
    <?php 
    foreach($addOns as $index => $addOn){
        ?>
      <li  data-toggle="popover" title="Description" data-menu-item-add-on-id='<?php echo $addOn->id?>' data-content="<?php echo $addOn->description?>" class="vendor-menu-item-add-on-<?php echo $item->id?> list-group-item add-ons-popover">
            <input type='checkbox' name='AddOns[<?php echo $key?>][<?php echo $addOn->id?>]' value='<?php echo $addOn->id?>' class='order-changes'/>&nbsp;&nbsp;&nbsp;
            <label class='form-label'><?php echo $addOn->name?> - $<?php echo UtilityHelper::formatAmountForDisplay($addOn->amount)?></label>
     
         
       </li>
       <?php 
        }
        ?>
        </ul>
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
        <button class='btn btn-success' onclick="javascript: Order.AddOrder()" type='button'>Add</button>
    </div>
</div>