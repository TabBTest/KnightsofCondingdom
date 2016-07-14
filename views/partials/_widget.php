<?php 
use app\helpers\TenantHelper;
?>
<script type="text/javascript" id="foodapp-js" src="//<?php echo \Yii::$app->params['defaultSiteURL']?>/js/sdk.js"></script>
<div class='restalutions-widget-button'>Show Menu</div>
<div  class="modal-widget" style='display: none'>
<div class="modal-content-widget" ><div class="modal-header-widget"><h2>Menu Order</h2></div>
<div class="modal-body-widget restalutions-widget" data-subdomain='<?php echo TenantHelper::getVendorSubdomain($model->id)?>' ></div>
<div class="modal-footer-widget"><button class='btn-widget btn-info pull-right-widget close-modal'>Close</button></div>
</div></div>