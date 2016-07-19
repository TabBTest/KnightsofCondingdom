<?php 

use app\models\VendorMenuItem;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\TenantInfo;
use app\models\VendorOperatingHours;
$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;

$hasDelivery = TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_HAS_DELIVERY) == 1 ? true : false;
?>

<?php echo $this->render('//partials/_show_message', []);?>
<div class='row'>
    <div class='col-xs-12'>
        <ul class="pager">
            <li><span><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel://<?= $vendor->getContactNumber();?>"><?= $vendor->getContactNumber();?></a></span></li>
            <li><span><a href="#" data-toggle="modal" data-target="#operating-hours-modal">Operating Hours</a></span></li>
            <li><span>Time to Pickup: <?= $vendor->getTimeToPickUpDisplay();?></span></li>
            <li><span>Offers Delivery: <?= $hasDelivery ? "Yes <i class=\"fa fa-check-circle\" aria-hidden=\"true\"></i>" : "No <i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>";?></span></li>
            <?php if($hasDelivery){?>
            <li><span>Minimum Delivery Amount: $<?php echo  UtilityHelper::formatAmountForDisplay(TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_DELIVERY_MINIMUM_AMOUNT));?></span></li>
            <li><span>Delivery Charge: $<?php echo  UtilityHelper::formatAmountForDisplay(TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_DELIVERY_CHARGE));?></span></li>
            <?php }?>
        </ul>
    </div>

    <div class="modal fade" id="operating-hours-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Operating
                        Hours <?php echo UtilityHelper::getTimeZoneDisplay($vendor->timezone) ?></h4>
                </div>
                <div class="modal-body">
                    <?php
                    $operatingTime = UtilityHelper::getOperatingTime();
                    foreach (UtilityHelper::getDays() as $key => $val) {
                        $operatingHours = VendorOperatingHours::getVendorOperatingHours($vendor->id, $key);
                        ?>
                        <div class='col-xs-12' style='margin-bottom: 10px'>
                            <div class='col-xs-6 text-center'>
                                <?php echo $val ?>
                            </div>
                            <div class='col-xs-6'>
                                <?php foreach ($operatingHours as $operatingHour) { ?>
                                    <div class='col-xs-12'>
                                        <?php foreach ($operatingTime as $val => $display) { ?>
                                            <?php echo $val == $operatingHour->startTime ? $display : '' ?>
                                        <?php } ?>
                                        -
                                        <?php foreach ($operatingTime as $val => $display) { ?>
                                            <?php echo $val == $operatingHour->endTime ? $display : '' ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="panel panel-primary">
            <h2 class="panel-heading text-center">Menu</h2>
            <div class="col-xs-12">
                <a href="#" class="btn btn-default openall pull-right">Expand All</a>
                <a href="#" class="btn btn-default closeall pull-right">Close All</a>
            </div>
            <div class="panel-group categories-main-panel" id="accordion">
                <?php
                foreach($vendorCategories as $category){
                    ?>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-danger" style="margin-bottom: 20px;" data-category-id='<?php echo $category->id?>'>
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="vendor-menu-categories" role="button" data-target="#category<?php echo $category->id?>" data-toggle="collapse" data-parent1="#accordion" href="#category<?php echo $category->id?>" aria-expanded="false" aria-controls="category<?php echo $category->id?>">
                                        <?php echo $category->name?>
                                    </a>
                                </h4>

                            </div>
                            <div id="category<?php echo $category->id?>" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <?php
                                        $menuItems = VendorMenuItem::find()->where('vendorMenuId = '. $menu->id . ' and menuCategoryId = ' . $category->id.' order by sorting asc')->all();
                                        ?>


                                        <?php foreach($menuItems as $item){
                                            if($item->isArchived == 1)
                                                continue;

                                            ?>
                                            <li class="col-xs-12 col-sm-12 col-md-6 list-group-item add-to-cart" data-menu-item-id="<?php echo $item->id?>">
                                                <label class="col-md-10 form-label menu-name"><?php echo $item->name?> </label>
                                                <span class="col-md-2 pull-right"><strong>$<?php echo UtilityHelper::formatAmountForDisplay($item->amount)?></strong></span>
                                                <br />
                                                <label class="col-md-10 form-label menu-description"><i><?php echo $item->description?></i></label>
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
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="panel panel-primary">
            <h2 class="panel-heading text-center">Order Summary</h2>
            <form id="main-order-summary" action='/ordering/save' method="POST">
                <div class="col-xs-12 main-order-summary-content">
                    <div class="col-xs-12 text-center">
                        <label>Please add your order now</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
