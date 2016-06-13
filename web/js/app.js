$(document).ready(function() {
	//if($("#phone").length == 1)
		//$('#phone').mask("(999) 999-9999");

	$('#register-form .fieldset:eq(0)').fadeIn('slow');
	
	$('#register-form input[type="text"], #register-form input[type="email"], #register-form input[type="tel"]').on('focus', function() {
    	$(this).parent().removeClass('has-error');
    	$(this).parent().find('.help-block').html('');
    });
	
	 // next step
    $('#register-form .btn-next').on('click', function() {
    	var parent_fieldset = $(this).parents('.fieldset');
    	var next_step = true;
    	
    	parent_fieldset.find('input[type="text"],input[type="email"],input[type="tel"], textarea, select').each(function() {
    		//console.log($(this).attr('id'))
    		if($(this).hasClass('not-required')){
    			;
    		}else if( $(this).val() == "") {
    			$(this).parent().addClass('has-error');
    			next_step = false;
    		}else if ($(this).attr('id')  == "email")
			{
				if (validateEmailFormat($(this).val( )) == false)
				{
					bError    = true;
					next_step = false;
					$(this).parent().addClass('has-error');
				}
			}else if ($(this).attr('id')  == "confirmEmail" && $(this).val( ) != $('#email').val())
			{
				bError    = true;
				next_step = false;
				$(this).parent().addClass('has-error');				
			}else {
    			$(this).parent().removeClass('has-error');
    		}
    	});
    	
    	if( next_step ) {
    		
    		$(this).parent().parent().removeClass('has-error');
        	$(this).parent().parent().find('.help-block').html('');
    		if($(this).data('is-last') == 1)
    		{
    			$(this).attr('disabled', true);
    			Stripe.card.createToken({
    				  number: $('.card-number').val(),
    				  cvc: $('.card-cvv').val(),
    				  exp_month: $('.card-expiry-month').val(),
    				  exp_year: $('.card-expiry-year').val()
    			}, stripeResponseHandler);
    			
    			    			
    			//$('.btn-prev-last').attr('disabled', true);
    			//$('form input, form textarea, select').DataSaver('remove');	
    		    
    		}else{
	    		parent_fieldset.fadeOut(400, function() {
		    		$(this).next().fadeIn();		    				    		
		    	});
    		}
    	}
    	
    });
    // submit
    $('#register-form').on('submit', function(e) {
    	
    	$(this).find('input[type="text"],input[type="email"],input[type="tel"], textarea, select').each(function() {
    		if($(this).hasClass('not-required')){
    			;
    		}else if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).parent().addClass('has-error');
    		}
    		else {
    			$(this).parent().removeClass('has-error');
    		}
    	});
    	
    });
    
    $('.add-menu-item').on('click', function(){
    	$.get('/menu/add-item', 'id='+$(this).data('id'), function(html){
    		$('#custom-modal .modal-title').html('Add Menu Item');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    	})
    	
    });
    $('.edit-menu-item').on('click', function(){
    	$.get('/menu/edit-item', 'id='+$(this).data('menu-item-id'), function(html){
    		$('#custom-modal .modal-title').html('Edit Menu Item');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    	})
    	
    });
    $('.delete-menu-item').on('click', function(){
    	if(confirm('Are you sure you want to delete this item?')){
	    	$.post('/menu/delete-item', 'id='+$(this).data('menu-item-id'), function(html){
	    		window.location.href = '/menu';
	    	})
    	}
    	
    });
    
});

function validateEmailFormat(sEmail){
	var str=sEmail
	var filter= /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	if (filter.test(str))
	testresults=true
	else{
	//alert("Please input a valid email address!")
	testresults=false
	}
	return (testresults)
}
function stripeResponseHandler(status, response) {

	  // Grab the form:
	  var $form = $('#register-form');

	  if (response.error) { // Problem!

	    // Show the errors on the form:
	    Messages.showError(response.error.message);
	    $form.find('.btn-register').prop('disabled', false); // Re-enable submission

	  } else { // Token created!

	    // Get the token ID:
	    var token = response.id;

	    // Insert the token into the form so it gets submitted to the server:
	    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    $('form#register-form').submit();
	  }
}

var VendorMenu = {
		saveItem : function(){
			$('#menu-item-form .has-error').removeClass('has-error');
			$('#menu-item-form input[type="text"]').each(function(){
				if($(this).val() == ''){
					$(this).parent().addClass('has-error');
				}
				if($(this).hasClass('price') && !$.isNumeric($(this).val())){
					$(this).parent().addClass('has-error');
				}
			});
			
			if($('#menu-item-form .has-error').length == 0){
				$('#menu-item-form').submit();
			}
		}
}

var Messages = {
		showError : function(message){
			alert(message);
		}
}