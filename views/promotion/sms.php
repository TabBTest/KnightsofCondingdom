<?php
use app\models\VendorPromotion; 
?>
<div class='form-group'>
 <form action='/promotion/send' class='promotion-form-sms'>
  
        <input type='hidden'  name='subject' value='SMS Subject'  class='form-control'/>
    
    <input type='hidden' name='type' value='<?php echo VendorPromotion::TYPE_SMS?>'/>
    <div class='form-group'>
        <label class='form-label'>Promotions</label>
        <textarea rows='5' cols='25' maxlength="120" class='form-control' name="promoHtml" placeholder="Enter your promotion here..."></textarea>    
    </div>
</form>
</div>
<div class='form-group text-center'>
<button type='button' class='btn btn-info btn-send-promo-sms' data-to='0'>Send to Self</button>
<button type='button' class='btn btn-info btn-send-promo-sms' data-to='1'>Send to Customers</button>
</div>