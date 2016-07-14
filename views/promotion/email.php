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
	  ],
	  setup: function(ed1) {
		    var text = '';
		    var $elem = $('#word-count-promotion');
	        var charLimit = parseInt($elem .data('limit'));
	        ed1.on('KeyDown', function(ed, e) {
	            text = ed1.getContent().replace(/(< ([^>]+)<)/g, '').replace(/\s+/g, ' ');
	            text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	            charCount = charLimit - (text.length);
	            $elem.html(charCount);
	            if(charCount <= 0 && ed.keyCode != 8) {
	                return tinymce.dom.Event.cancel(ed);
	            }
	        });
		    
		}
	 });
	 </script>
	 <div class='form-group'>
	 <form action='/promotion/send' class='promotion-form-email'>
	    <div class='form-group'>
            <label class='form-label'>Subject</label>
            <input type='text'  name='subject' required  class='form-control'/>
        </div>
        <input type='hidden' name='type' value='<?php echo VendorPromotion::TYPE_EMAIL?>'/>
        <div class='form-group'>
            <label class='form-label'>Promotions</label>
            <textarea id='promotion' name='promotion' placeholder='Promotions here'></textarea>
            <div><span id="word-count-promotion" data-limit='2000'>2000</span> <span>characters remaing</span></div>    
        </div>
        
        
        <input type="hidden" id="promo-html" name="promoHtml" />
        <input type="hidden" id="userList" name="userList" />
    </form>
    </div>
    <div class='form-group text-center'>
    <button type='button' class='btn btn-success btn-send-promo' data-to='0'>Send to Self</button>
    <button type='button' class='btn btn-success btn-send-promo' data-to='1'>Send to Customers</button>    
    </div>
    
    <script>
    var max_chars = 70; //max characters
    var allowed_keys = [8, 13, 16, 17, 18, 20, 33, 34, 35, 36, 37, 38, 39, 40, 46];
    var chars_without_html = 0;

    function alarmChars() {
        if (chars_without_html > (max_chars - 25)) {
            $('#chars_left').css('color', 'red');
        } else {
            $('#chars_left').css('color', 'gray');
        }
    }
    </script>