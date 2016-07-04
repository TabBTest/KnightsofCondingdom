<?php 
use app\helpers\TenantHelper;


if(TenantHelper::isVendorStoreClose() === true){
    
         ?>
         <div class='alert alert-danger'>Store is closed</div>
        <?php            
}?>