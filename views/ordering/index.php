<?php 

use app\models\VendorMenuItem;
use yii\widgets\MaskedInput;
use app\helpers\UtilityHelper;
use app\models\TenantInfo;
use app\models\VendorOperatingHours;
use app\models\VendorMenu;
use app\models\MenuCategories;
$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;

$hasDelivery = TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_HAS_DELIVERY) == 1 ? true : false;

$hasActiveMenuAlready = false;
$allMenus = VendorMenu::findAll(['vendorId' => $vendor->id]);

function testIfMenuIsOpen($menu) {
    return ($menu->isMenuOpenForOrder());
}

function testIfMenuIsClosed($menu) {
    return (!$menu->isMenuOpenForOrder());
}

function getMenuLinkHTML($menu) {
    return <<<HTML
<a class="menu-link-notes" data-toggle="tab" href="#menu-{$menu->id}">{$menu->name}</a>
HTML;
}

$availableMenus = array_map("getMenuLinkHTML", array_filter($allMenus, "testIfMenuIsOpen"));
$unavailableMenus = array_map("getMenuLinkHTML", array_filter($allMenus, "testIfMenuIsClosed"));

$js = <<<JS
$('a.menu-link-notes').click(function() {
    $('ul.nav.nav-tabs').find('.active').removeClass('active');
    $('li > a[href=\"' + $(this).attr('href') + '\"]').parent().addClass('active');
});
JS;

$this->registerJs($js, $this::POS_READY);
?>

<?php echo $this->render('//partials/_show_message', []);?>
<div class='row'>
    <div class='col-xs-12'>
        <ul class="pager">
            <li>
                <span>
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <a href="tel://<?= $vendor->getContactNumber();?>"><?= $vendor->getContactNumber();?></a>
                </span>
            </li>
            <li>
                <span>
                    <a href="#" data-toggle="modal" data-target="#operating-hours-modal">Operating Hours</a>
                </span>
            </li>
            <li>
                <span>Time to Pickup: <?= $vendor->getTimeToPickUpDisplay();?></span>
            </li>
            <li>
                <span>
                    Offers Delivery:&nbsp;
                    <?= $hasDelivery ?
                        "Yes <i class=\"fa fa-check-circle\" aria-hidden=\"true\"></i>" :
                        "No <i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>"
                    ?>
                </span>
            </li>
            <?php if($hasDelivery) { ?>
            <li>
                <span>
                    Minimum Delivery Amount: <?= '$'.UtilityHelper::formatAmountForDisplay(
                        TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_DELIVERY_MINIMUM_AMOUNT)
                    ) ?>
                </span>
            </li>
            <li>
                <span>Delivery Charge: 
                    <?= '$'.UtilityHelper::formatAmountForDisplay(
                        TenantInfo::getTenantValue($vendor->id, TenantInfo::CODE_DELIVERY_CHARGE)
                    ) ?>
                </span>
            </li>
            <?php } ?>
            <?php if ($availableMenus) { ?>
            <li>
                <span>
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    Now Serving: <?= implode(", ", $availableMenus) ?>
                </span>
            </li>
            <?php } ?>
            <?php if ($unavailableMenus) { ?>
            <li>
                <span>
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    Unavailable: <?= implode(", ", $unavailableMenus) ?>
                </span>
            </li>
            <?php } ?>
        </ul>
    </div>

    <div class="modal fade" id="operating-hours-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Operating
                        Hours <?= UtilityHelper::getTimeZoneDisplay($vendor->timezone) ?></h4>
                </div>
                <div class="modal-body">
                    <?php
                    $operatingTime = UtilityHelper::getOperatingTime();
                    foreach (UtilityHelper::getDays() as $key => $val) {
                        $operatingHours = VendorOperatingHours::getVendorOperatingHours($vendor->id, $key);
                        ?>
                        <div class="col-xs-12" style="margin-bottom: 10px">
                            <div class="col-xs-6 text-center">
                                <?= $val ?>
                            </div>
                            <div class="col-xs-6">
                                <?php foreach ($operatingHours as $operatingHour) { ?>
                                    <div class="col-xs-12">
                                        <?php foreach ($operatingTime as $val => $display) { ?>
                                            <?= $val == $operatingHour->startTime ? $display : '' ?>
                                        <?php } ?>
                                        -
                                        <?php foreach ($operatingTime as $val => $display) { ?>
                                            <?= $val == $operatingHour->endTime ? $display : '' ?>
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
    <ul class="nav nav-tabs">
<?php
foreach($allMenus as $index => $menus){
    $className = '';
    $openForOrder = $menus->isMenuOpenForOrder();
    if(isset($_REQUEST['menuId']) && $_REQUEST['menuId'] != ''){
        if($_REQUEST['menuId'] == $menus->id){
            $className = 'active';
            $hasActiveMenuAlready = true;
        }
    }else { //if($index == 0){
        //$className = 'active';
        if($openForOrder && $hasActiveMenuAlready == false){
            $hasActiveMenuAlready = true;
            $className = 'active';
        }
    }
?>
 <li class="<?php echo $className?> nav-menu-tab">
 <a data-toggle="tab" href="#menu-<?php echo $menus->id?>"><?php echo $menus->name?></a></li>
<?php 
}?>
</ul>
    </div>
        
            
            <div class="tab-content">
<?php 
$hasActiveMenuAlready = false;
foreach($allMenus as $index => $menu){
    $className = '';
    $openForOrder = $menu->isMenuOpenForOrder();
    if(isset($_REQUEST['menuId']) && $_REQUEST['menuId'] != ''){
        if($_REQUEST['menuId'] == $menu->id){
            $className = 'active';
            $hasActiveMenuAlready = true;
        }
    }else {//if($index == 0){
        //$className = 'active';
        
        if($openForOrder && $hasActiveMenuAlready == false){
            $hasActiveMenuAlready = true;
            $className = 'active';
        }
    }
    
    
    
?>
 <div id="menu-<?php echo $menu->id?>" class="tab-pane <?php echo $className?>">
    <div class="row">
        <div class="col-xs-12" id="menu-heading"> 
     <?php if($openForOrder){?>
        <label class=''>Available For Ordering</label>
     <?php }else{?>
     <label class=''>Available from <?php echo $menu->displayMenuAvailabilityInCustomerTimezone()?></label>
     <?php }?>           
            
        </div>
    </div>
    
     <div class="row">
        <div class="col-xs-12" id="menu-heading">            
            <div class="pull-right">
                <button type="button" class="btn btn-primary openall" data-id="<?php echo $menu->id?>">Expand All</button>
                <button type="button" class="btn btn-primary closeall" data-id="<?php echo $menu->id?>">Close All</button>
            </div>
        </div>
    </div>
    <?php 
    $vendorCategories = MenuCategories::find()->where('vendorMenuId = '.$menu->id.' and isArchived = 0 order by sorting asc')->all();


    foreach($vendorCategories as $category){
    ?>
        <div class="panel panel-danger categories-panel" style="margin-bottom: 20px;" data-category-id="<?php echo $category->id?>">
            
            
            <div class="panel-group categories-main-panel" id="accordion">
                
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-danger"
                             style="margin-bottom: 20px;"
                             data-category-id="<?= $category->id ?>"
                        >
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="vendor-menu-categories"
                                       role="button"
                                       data-target="#category<?= $category->id ?>"
                                       data-toggle="collapse"
                                       data-parent1="#accordion"
                                       href="#category<?= $category->id ?>"
                                       aria-expanded="false"
                                       aria-controls="category<?= $category->id ?>"
                                    >
                                        <?= $category->name ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="category<?= $category->id ?>" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="panel-body" style="border: none;">
                                    <ul class="list-group">
                                        <?php
                                        $menuItems = VendorMenuItem::find()->where(
                                            'vendorMenuId = ' .
                                            $menu->id .
                                            ' and menuCategoryId = '.
                                            $category->id.' order by sorting asc'
                                        )->all();
                                        ?>
                                        <?php foreach($menuItems as $item){
                                            if($item->isArchived == 1)
                                                continue;
                                            ?>
                                            <li class="col-xs-12 col-sm-12 col-md-6 list-group-item add-to-cart"
                                                data-menu-item-id="<?= $item->id ?>" data-open-for-order='<?php echo $openForOrder?>'>
                                                <label class="col-md-10 form-label menu-name"><?= $item->name?></label>
                                                <span class="col-md-2 pull-right">
                                                    <strong>
                                                        $<?= UtilityHelper::formatAmountForDisplay($item->amount)?>
                                                    </strong>
                                                </span>
                                                <br />
                                                <label class="col-md-10 form-label menu-description">
                                                    <i><?= $item->description ?></i>
                                                </label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

          </div>
          
        
    <?php }?>
 </div>
<?php 
}?>
    
</div>
</div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="panel panel-primary">
            <h2 class="panel-heading text-center" style='margin-top: 0'>Order Summary</h2>
            <form id="main-order-summary" action="/ordering/save" method="POST">
                <div class="col-xs-12 main-order-summary-content">
                    <div class="col-xs-12 text-center">
                        <label>Please add your order now</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
