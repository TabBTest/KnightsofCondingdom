<div id="<?php echo $tabName?>" class="tab-pane fade <?php echo $show ? "in active" : ""?>">
    <?php if(count($list) != 0){?>
    <br />
        <ul class="list-group add-ons-list col-md-12">
        <?php foreach($list as $addOn){ ?>
            <li data-toggle="popover"
                title="Description"
                data-menu-item-add-on-id="<?php echo $addOn->id?>"
                data-content="<?php echo $addOn->description?>"
                class="vendor-menu-item-add-on-<?php echo $item->id?> list-group-item add-ons-popover">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="panel-title pull-left">
                            <h4><i class="fa fa-arrows" aria-hidden="true"></i> <?php echo $addOn->name?></h4>
                        </div>
                        <div class="panel-title pull-right">
                            <label class="price-on-colored-panel">$<?php echo $addOn->amount?></label>
                            <a class="btn btn-xs btn-raised btn-default edit-menu-item-add-on"
                                href="javascript: VendorMenu.editAddOn(<?php echo $addOn->id?>)"
                                data-menu-item-add-on-id="<?php echo $addOn->id?>">
                                Edit
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

            </li>
       <?php }?>
    </ul>
    <?php } else {?>
    <label class="control-label">None</label>
    <?php } ?>
    <div class='row'>
    &nbsp;
    <br />
    <br />
    </div>
</div>
