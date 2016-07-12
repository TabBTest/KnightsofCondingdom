<?php 
use app\helpers\TenantHelper;


if(TenantHelper::isVendorStoreClose() === true){
    $reason = TenantHelper::getCloseReason();
         ?>
         <div class='alert alert-danger'>Store is closed <?php echo $reason != '' ? ' - '.$reason : ''?></div>
        <?php            
}?>