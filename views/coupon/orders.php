<?php

use app\models\VendorMenuItem;
use app\models\Orders;
use app\helpers\TenantHelper;
use app\models\User;
$this->title = 'Coupon Orders';
$this->params['breadcrumbs'][] = $this->title;

 
?>

<?php echo $this->render('//partials/_show_message', []);?>

<div class="col-xs-12 vendor-coupon-order-body" data-vendor-coupon-id='<?php echo $vendorCouponId ?>' data-url='<?php echo $url?>'>
    <?php echo $this->render('_list', ['orders' => $orders]);?>
</div>
    
