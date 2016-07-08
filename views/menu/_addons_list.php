<div id="<?php echo $tabName?>" class="tab-pane fade <?php echo $show ? 'in active' : ''?>">
    <?php if(count($list) != 0){?>
    <br />
      <ul class="list-group add-ons-list col-xs-6">
      <?php 
      
      foreach($list as $addOn){
        ?>
      <li  data-toggle="popover" title="Description" data-menu-item-add-on-id='<?php echo $addOn->id?>' 
      data-content="<?php echo $addOn->description?>" 
      class="vendor-menu-item-add-on-<?php echo $item->id?> list-group-item add-ons-popover">
     <?php echo $addOn->name?>
     <a class='btn btn-xs btn-info pull-right edit-menu-item-add-on' href='javascript: VendorMenu.editAddOn(<?php echo $addOn->id?>)' data-menu-item-add-on-id='<?php echo $addOn->id?>'>Edit</a>
     <label style='margin-right: 10px;' class='pull-right'>$<?php echo $addOn->amount?></label>
     
         
       </li>
       <?php 
        }?>
    </ul>
    <?php }else{?>
    <label class='control-label'>None</label>
    <?php }?>
    <div class='row'>
    &nbsp;
    <br />
    <br />
    </div>
</div>
