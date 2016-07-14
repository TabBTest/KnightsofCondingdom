<?php 
use app\models\VendorPromotion;
?>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
tinymce.init({
	  selector: 'textarea#promotion',
	  height: 500,
	  theme: 'modern',
	  plugins: [
	    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
	    'searchreplace wordcount visualblocks visualchars code fullscreen',
	    'insertdatetime media nonbreaking save table contextmenu directionality',
	    'emoticons template paste textcolor colorpicker textpattern imagetools'
	  ],
	  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	  toolbar2: 'print preview media | forecolor backcolor emoticons',
	  image_advtab: true,
	  templates: [
	    { title: 'Test template 1', content: 'Test 1' },
	    { title: 'Test template 2', content: 'Test 2' }
	  ],
	  content_css: [
	    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
	    '//www.tinymce.com/css/codepen.min.css'
	  ]
	 });
	 </script>
	 <div class='form-group'>
	 <form action='/admin/promotion/send' class='promotion-form'>
	    <div class='form-group'>
            <label class='form-label'>Subject</label>
            <input type='text'  name='subject' required  class='form-control'/>
        </div>
        <input type='hidden' name='type' value='<?php echo VendorPromotion::TYPE_EMAIL?>'/>
        <div class='form-group'>
            <label class='form-label'>Promotions</label>
            <textarea id='promotion' name='promotion'>Easy (and free!) You should check out our premium features.</textarea>    
        </div>
        
        
        <input type="hidden" id="promo-html" name="promoHtml" />
    </form>
    </div>
    <div class='form-group text-center'>
    <button type='button' class='btn btn-success btn-send-promo' data-to='0'>Send to Self</button>
    <button type='button' class='btn btn-success btn-send-promo' data-to='1'>Send to Vendors</button>
    </div>