$(document).ready(function () {
  $.material.init();
  
  //if($("#phone").length == 1)
  $('.fax-number').mask("(999) 999-9999");
  $(".order-quantity[type='number']").keypress(function (evt) {
    evt.preventDefault();
  });
  $('#register-form .fieldset:eq(0)').fadeIn('slow');

  $('.closeall').click(function () {
    $('#menu-'+$(this).data('id')+' .panel-collapse.in')
      .collapse('hide');
  });
  $('.openall').click(function () {
    $('#menu-'+$(this).data('id')+' .panel-collapse:not(".in")')
      .collapse('show');
  });

  $('#register-form input[type="text"], #register-form input[type="email"], #register-form input[type="tel"]').on('focus', function () {
    $(this).parent().removeClass('has-error');
    $(this).parent().find('.help-block').html('');
  });

  $('#showCompletedOrder').on('change', function () {
    Order.loadVendor();
  });
  // next step
  $('#register-form .btn-next').on('click', function () {
    var parent_fieldset = $(this).parents('.fieldset');
    var next_step = true;

    parent_fieldset.find('input[type="text"],input[type="email"],input[type="tel"], textarea, select').each(function () {
      //console.log($(this).attr('id'))
      if ($(this).hasClass('not-required')) {
        ;
      } else if ($(this).val() == "") {
        $(this).parent().addClass('has-error');
        next_step = false;
      } else if ($(this).attr('id') == "email") {
        if (validateEmailFormat($(this).val()) == false) {
          bError = true;
          next_step = false;
          $(this).parent().addClass('has-error');
        }
      } else if ($(this).attr('id') == "confirmEmail" && $(this).val() != $('#email').val()) {
        bError = true;
        next_step = false;
        $(this).parent().addClass('has-error');
      } else {
        $(this).parent().removeClass('has-error');
      }
    });

    if (next_step) {

      $(this).parent().parent().removeClass('has-error');
      $(this).parent().parent().find('.help-block').html('');
      if ($(this).data('is-last') == 1) {
        $(this).attr('disabled', true);
        /*
        Stripe.card.createToken({
          number: $('.card-number').val(),
          cvc: $('.card-cvv').val(),
          exp_month: $('.card-expiry-month').val(),
          exp_year: $('.card-expiry-year').val()
        }, stripeResponseHandler);
        */
        App.ccValidator({number: $('.card-number').val(),
	        cvc: $('.card-cvv').val(),
	        exp_month: $('.card-expiry-month').val(),
	        exp_year: $('.card-expiry-year').val()},stripeResponseHandler);
        //$('.btn-prev-last').attr('disabled', true);
        //$('form input, form textarea, select').DataSaver('remove');

      } else {
        parent_fieldset.fadeOut(400, function () {
          $(this).next().fadeIn();
        });
      }
    }

  });


  $('.btn-save-billing').on('click', function () {
    $('#billing-form').find('input[type="text"],input[type="email"],input[type="tel"], select').each(function () {
      if ($(this).hasClass('not-required')) {
        ;
      } else if ($(this).val() == "") {

        $(this).parent().addClass('has-error');
      }
      else {
        $(this).parent().removeClass('has-error');
      }
    });

    if ($('#billing-form .has-error').length == 0) {
      //we
      //(this).attr('disabled', true);
    	/*
      Stripe.card.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvv').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandlerSaveBilling);
      */
    	App.ccValidator({number: $('.card-number').val(),
    	        cvc: $('.card-cvv').val(),
    	        exp_month: $('.card-expiry-month').val(),
    	        exp_year: $('.card-expiry-year').val()},stripeResponseHandlerSaveBilling);
      	
    }
  })
  // submit
  $('#register-form').on('submit', function (e) {

    $(this).find('input[type="text"],input[type="email"],input[type="tel"], textarea, select').each(function () {
      if ($(this).hasClass('not-required')) {
        ;
      } else if ($(this).val() == "") {
        e.preventDefault();
        $(this).parent().addClass('has-error');
      }
      else {
        $(this).parent().removeClass('has-error');
      }
    });

  });
  if ($('.add-category-item').length != 0) {
    VendorMenu.setupUI();
  }

  $('.add-category-item').on('click', function () {
    var sorting = $('#menu-'+$(this).data('id')+' .vendor-menu-categories').length + 1;
    $.get('/menu/add-category', 'sorting=' + sorting+'&menuId='+$(this).data('id'), function (html) {
      $('#custom-modal .modal-title').html('Add Category');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
    })

  });



  $('.btn-submit-order').on('click', function () {
    var hasOrder = false;
    $('.order-quantity').each(function () {
      if ($(this).val() > 0) {
        hasOrder = true;
      }
    });
    if (hasOrder) {
      $.post('/ordering/summary', $('#customer-order-form').serialize(), function (html) {
        $('#custom-modal .modal-title').html('Order Summary');
        $('#custom-modal .modal-body').html(html);
        $('#custom-modal').modal('show');
      });
    } else {
      Messages.showError('Please add your order');
    }
  });
  
  if($('.main-order-summary-content').length == 1){
	  Order.refreshMainOrderSummary();
  }

  $('.btn-send-promo').on('click', function () {
    $('.promotion-form-email has-error').removeClass('has-error');
    if ($('.promotion-form-email input[name="subject"]').val() == '') {
      $('.promotion-form-email input[name="subject"]').parent().addClass('has-error');
      return false;
    }
    $('#promo-html').val(tinyMCE.get('promotion').getContent());
    var prefix = '';
    if(window.location.href.indexOf('/admin') != -1){
    	prefix = '/admin';
    }
    
    var to = $(this).data('to');
   
    if(to == 1){
    	
    	$.post(prefix+'/promotion/get-customers', 'type=email', function (html) {
    		$('#custom-modal .modal-title').html('Choose Customers');
            $('#custom-modal .modal-body').html(html);
            $('#custom-modal').modal('show');
            
            setupUiCustomerPromo();
           
            
  	    })
    	
    }else{	    	    
	    $.post(prefix+'/promotion/send?to=' + to, $('.promotion-form-email').serialize(), function (resp) {
	      var data = $.parseJSON(resp);
	      if (data.status == 1) {
	        Messages.showSuccess('Promotions is being processed already');
	      }
	    })
    }
  })
  $('.btn-send-promo-sms').on('click', function () {
    var to = $(this).data('to');
    if(window.location.href.indexOf('/admin') !== false){
    	prefix = '/admin';
    }
    if(to == 1){
    	
    	$.post(prefix+'/promotion/get-customers', 'type=sms', function (html) {
    		$('#custom-modal .modal-title').html('Choose Customers');
            $('#custom-modal .modal-body').html(html);
            $('#custom-modal').modal('show');
            
            setupUiCustomerPromo();
           
            
  	    })
    	
    }else{	
	    $.post(prefix+'/promotion/send?to=' + to, $('.promotion-form-sms').serialize(), function (resp) {
	      var data = $.parseJSON(resp);
	      if (data.status == 1) {
	        Messages.showSuccess('Promotions is being processed already');
	      }
	    })
    }
  })

  if ($('#tab-settings').length != 0) {

    var clipboard = new Clipboard('.btn-copy-widget');

    clipboard.on('success', function (e) {
      e.clearSelection();
      Messages.showSuccess('Copied text to clipboard');
    });
    clipboard.on('error', function (e) {
      var d = new CM();
      Messages.showError('An Error occurred. The text was no copied in your Clipboard. Please try again.');
    });

    $('textarea.copy-content').on('click', function () {


      $(this).focus();
      $(this).select();
      document.execCommand("copy");
      //Messages.showSuccess('Copied text to clipboard');
    });
    /*
     if (element.nodeName === 'INPUT' || element.nodeName === 'TEXTAREA') {
     element.focus();
     element.setSelectionRange(0, element.value.length);

     selectedText = element.value;
     }
     */
  }
  setupUi();
  setupUiVendorOverrides();
  listLinkActions();
  if ($('.add-ons-popover').length > 0)
    $('.add-ons-popover').popover({'placement': 'top', 'trigger': 'hover'});

  $('.add-to-cart').on('click', function () {
    if($(this).data('open-for-order') == 0){
    	Messages.showError('Item is not available for ordering as of this time.');
    	return;
    }
    $.get('/ordering/add-item', 'menuItemId=' + $(this).data('menu-item-id'), function (html) {
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal .modal-title').html(menuItemTitle);
      $('#custom-modal').modal('show');
      $('.add-ons-popover').popover({'placement': 'top', 'trigger': 'hover'});
      $.material.init();
      $.material.input();
      Order.showItemOrderSummary();
      $('.order-changes').off('change');
      $('.order-changes').on('change', Order.showItemOrderSummary);
    })

  });

  if ($('.preview-operating-hours').length == 1) {
    VendorSettings.previewHours();
  }

  $(".phone .form-control").on('keyup', function () {
    if ($(this).val().length == $(this).attr('maxlength')) {
      $(this).parent().next('.phone[data-key="' + $(this).parent().data('key') + '"]').find('.form-control').focus();
    }
  });
  $('.btn-change-password').on('click', function () {

    $.get('/profile/change-password', 'id=' + $(this).data('id'), function (html) {
      $('#custom-modal .modal-title').html('Change Password');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
      $('.save-new-password').off('click');
      $('.save-new-password').on('click', function () {
        $('form#change-password .has-error').removeClass('has-error');
        if ($('input[name="password"]').val() == '') {
          $('input[name="password"]').parent().addClass('has-error');
        }
        if ($('input[name="password"]').val().length < 6) {
          $('input[name="password"]').parent().addClass('has-error');
        }
        if ($('input[name="password"]').val() != $('input[name="confirmPassword"]').val()) {
          $('input[name="confirmPassword"]').parent().addClass('has-error');
        }

        if ($('form#change-password .has-error').length == 0) {
          swal({
              title: "Confirm Password Change",
              text: "Are you sure you want to change your password?",
              type: "warning",
              showCancelButton: true,
              confirmButton: 'Yes, confirm',
              cancelButton: 'No, keep it'
            },
            function (isConfirm) {
              if (isConfirm) {
                $.post('/profile/save-password', $('form#change-password').serialize(), function (data) {
                  var resp = $.parseJSON(data);
                  if (resp.status == 1) {
                    Messages.showSuccess('Password Change Successfully');
                    $('#custom-modal').modal('hide');
                  } else if (resp.status == 1) {
                    Messages.showError('Password Change Failed, please try again');
                  }
                });
              }
            });
        }
      });
    })

  });

  $("input[type='checkbox'][name='isStoreOpen']").bootstrapSwitch({
    'onText': 'Open',
    'offText': 'Close',
    'onColor': 'success',
    'offColor': 'danger',
    'state': $("input[type='checkbox'][name='isStoreOpen']").is(':checked'),
    'labelText': 'Store',
    'onSwitchChange': function (event, state) {
      console.log(state);
      if (state == true) {
        $('.store-close-reason').hide();
      } else {
        $('.store-close-reason').show();
      }
    }
  });
  $('select[name="timeToPickUp"]').on('change', function () {
    $.post('/vendor/save-time-to-pickup', 'id=' + $(this).data('user-id') + '&timeToPickUp=' + $(this).val(), function (data) {
      var resp = $.parseJSON(data);
      if (resp.status == 1) {
        Messages.showSuccess('Time to pickup updated successfully');
      } else {
        Messages.showError('Processing failed, please try again');
      }
    })
  });


  $(document).on('click', '.coupon-archive', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var next = $(this).data('next');

    var titleText = '';
    var contentText = '';

    if (next == 0) {
      titleText = 'Unarchive Coupon';
      contentText = 'Are you sure you want to unarchive this coupon code?';
    } else {
      titleText = 'Archive Coupon';
      contentText = 'Are you sure you want to archive this coupon code?';
    }

    swal({
        title: titleText,
        text: contentText,
        type: "warning",
        showCancelButton: true
      },
      function (isConfirm) {
        if (isConfirm) {
          $('#form-archive-coupon #id').val(id);
          $('#form-archive-coupon #archive').val(next);
          $('#form-archive-coupon').submit();
        }
      });
  });

  $('textarea[name="promoHtml"]').change(updateCountdownSms);
  $('textarea[name="promoHtml"]').keyup(updateCountdownSms);
  
});
var updateCountdownSms = function(){
  var remaining = $('textarea[name="promoHtml"]').data('max-length') - $('textarea[name="promoHtml"]').val().length;
  jQuery('.countdown-sms').text(remaining + ' characters remaining.');
};
var Order = {
  init: false,
  timeLimit: 15000, //30000
  loadCustomer: function () {
    $.get($('.customer-order-body').data('url'), 'page=1&userId=' + $('.customer-order-history-pagination').data('user-id'), function (html) {
      $('.customer-order-body').html(html);
      setupUi();
    })

  },
  checkOut : function(){
	  if($('#relogin-modal').data('is-login') == 1){
		  $('#checkout-modal').modal('show');
	  }else{
		  $('#relogin-modal').modal('show');
	  }
  },
  setUpWorkflow : function(){
	  $('#checkout-modal .fieldset').hide();
	  $('#checkout-modal .fieldset:eq(0)').fadeIn('slow');
	  
	  $('#checkout-modal .btn-back').off('click');
	  $('#checkout-modal .btn-back').on('click', function(){
		  var step = $(this).data('step');
		  $(this).parents('.fieldset').fadeOut(400, function() {
			  if(step == 'step2'){
				  $(this).prev().fadeIn();  
			  }else if(step == 'step3'){
				  /*
				  if(Order.isAddNewDeliveryAddress()){
					  $(this).parent().find('.fieldset.step2').fadeIn();  
				  }else{
					  $(this).parent().find('.fieldset.step1').fadeIn();  
				  }
				  */
				  $(this).parent().find('.fieldset.step2').fadeIn();  
			  }else if(step == 'step4'){
				  /*
				  if(Order.isAddNewCC()){
					  $(this).parent().find('.fieldset.step3').fadeIn();  
				  }else if(Order.isAddNewDeliveryAddress()){
					  $(this).parent().find('.fieldset.step2').fadeIn();  
				  }else{
					  $(this).parent().find('.fieldset.step1').fadeIn();  
				  }
				  */
			  }
	    		
	    		
	    	});
	  });
	  
	  $('#checkout-modal .btn-next').off('click');
	  $('#checkout-modal .btn-next').on('click', function () {
	     var parent_fieldset = $(this).parents('.fieldset');
	     var next_step = true;
	     var curButtonStep = $(this).data('step');
	     
	     if(curButtonStep == 'step1' ){
	    	 if(Order.isAddNewDeliveryAddress()){
	    		 $('.new-address').find('input[type="text"], select').removeClass('not-required');
	    		 $('.new-address').find('input[type="text"], select').addClass('required');
	    	 } else {
	    		 $('.new-address').find('input[type="text"], select').removeClass('required');
	    		 $('.new-address').find('input[type="text"], select').addClass('not-required');
	    	 }
    	 }else if(curButtonStep == 'step2' ){
	    	 if(Order.isAddNewCC()){
	    		 $('.new-card').find('input[type="text"], select').removeClass('not-required');
	    		 $('.new-card').find('input[type="text"], select').addClass('required');
	    	 } else {
	    		 $('.new-card').find('input[type="text"], select').removeClass('required');
	    		 $('.new-card').find('input[type="text"], select').addClass('not-required');
	    	 }
    	 }
	     
	     parent_fieldset.find('input[type="text"], select').each(function () {
	    	
	    	 
	       //console.log($(this).attr('id'))
	    	if ($(this).hasClass('not-required')) {
	         ;
	       } else if ($(this).hasClass('advance-time-pickup')) {
	    	   
	    	   if($('.is-advance-order[value=1]').is(':checked') && Order.isAdvanceDeliveryTimeValid() === false){
	    		   $(this).parent().addClass('has-error');
			       next_step = false;  
	    	   }
	    	   
		         
	       }else if ($(this).val() == "") {
	         $(this).parent().addClass('has-error');
	         next_step = false;
	       } else {
	         $(this).parent().removeClass('has-error');
	       }
	     });

	     if (next_step) {
    	    
	    	 if($(this).data('step') == 'step1'){
	    		 
	    		 parent_fieldset.fadeOut(400, function () {
    				 
	    	          $(this).parent().find('.fieldset.step2').fadeIn();
	    	        });
	    		 /*
	    		 if(Order.isAddNewDeliveryAddress()){
	    			 //move to step 2
	    			 
	    		 }else if(Order.isAddNewCC()){
	    			 //move to step 3
	    			 parent_fieldset.fadeOut(400, function () {
	    				 $(this).parent().find('.fieldset.step3').fadeIn();
		    	        });
	    		 }else{
	    			 parent_fieldset.fadeOut(400, function () {
	    				 $(this).parent().find('.fieldset.step4').fadeIn();
	    	        });
	    			// $('#main-order-summary').submit();
	    		 }
	    		 */
	    	 }else if($(this).data('step') == 'step2'){
	    		
	    		 if(Order.isAddNewCC()){
		    		 App.ccValidator({number: $('.card-number').val(),
	 	    	        cvc: $('.card-cvv').val(),
	 	    	        exp_month: $('.card-expiry-month').val(),
	 	    	        exp_year: $('.card-expiry-year').val()},stripeResponseOrderHandler);
	    		 }else{
	    			 parent_fieldset.fadeOut(400, function () {
	    				 $(this).parent().find('.fieldset.step3').fadeIn();
		    	        });
	    		 }
	    		 
	    		 /*
	    		 if(Order.isAddNewCC()){
	    			 //move to step 3
	    			 parent_fieldset.fadeOut(400, function () {
	    				 $(this).parent().find('.fieldset.step3').fadeIn();
		    	        });
	    		 }else{
	    			 parent_fieldset.fadeOut(400, function () {
	    				 $(this).parent().find('.fieldset.step4').fadeIn();
	    	        });
	    			 //$('#main-order-summary').submit();
	    		 }
	    		 */
	    	 }else if($(this).data('step') == 'step3'){
	    		 $(this).attr('disabled', true);
	    		 $('#main-order-summary').submit();
	    		 /*
    	         Stripe.card.createToken({
    	           number: $('.card-number').val(),
    	           cvc: $('.card-cvv').val(),
    	           exp_month: $('.card-expiry-month').val(),
    	           exp_year: $('.card-expiry-year').val()
    	         }, stripeResponseOrderHandler);
    	         */
    	        
    	         
	    	 }else if($(this).data('step') == 'step4'){
	    		 
	    	 }
	    	 
	       //$(this).parent().parent().removeClass('has-error');
	       //$(this).parent().parent().find('.help-block').html('');
	       
	     }

	   });
  },
  changeDeliveryAddress : function(){
	  if(Order.isAddNewDeliveryAddress()){
		  $('.new-address').show();
	  }else{
		  $('.new-address').hide();
	  }
  },
  changeCardToUse : function(){
	  /*
	  if($('select[name="cardToUse"]').val() == 'current'){
		  if($('.has-delivery').length == 1 && $('.has-delivery').is(':checked') == true &&  $('select[name="deliveryAddressType"]').length == 1 && $('select[name="deliveryAddressType"]').val() != 'current'){
			  $('#checkout-modal .fieldset:eq(0) .btn-next').html('Continue');
		  }else{
			  $('#checkout-modal .fieldset:eq(0) .btn-next').html('Pay Now');
		  }
	  }else{
		  $('#checkout-modal .fieldset:eq(0) .btn-next').html('Continue');
	  }
	  */
	  
	  if(Order.isAddNewCC()){
		  $('.new-card').show();
	  }else{
		  $('.new-card').hide();
	  }
  },
  isAddNewDeliveryAddress : function(){
	  if($('.has-delivery').length == 1 && $('.has-delivery').is(':checked') == true && 
	  $('select[name="deliveryAddressType"]').length == 1 && $('select[name="deliveryAddressType"]').val() != 'current'){
		  return true;
	  }
	  return false;
  },
  isAddNewCC : function(){
	  if($('input[name="paymentType"]:checked').val() == 1 && $('select[name="cardToUse"]').val() != 'current'){
		  return true;
	  }
	  return false;
  },
  checkPaymentType : function(){
	  //card
	  if($('input[name="paymentType"]:checked').val() == 1){
		  //we show
		  $('select[name="cardToUse"]').show();
		  $('select[name="cardToUse"]').off('change');
		  $('select[name="cardToUse"]').on('change', Order.changeCardToUse);
		  Order.changeCardToUse();
	  }else{
		  $('select[name="cardToUse"]').hide();
		  $('#checkout-modal .fieldset:eq(0) .btn-next').html('Pay Now');
	  } 
	  
	  if($('select[name="deliveryAddressType"]').length == 1){
		  $('select[name="deliveryAddressType"]').off('change');
		  $('select[name="deliveryAddressType"]').on('change', Order.changeDeliveryAddress);
	  }
	  
	  if($('.has-delivery').length == 1 && $('.has-delivery').is(':checked') == true &&  $('select[name="deliveryAddressType"]').length == 1 && $('select[name="deliveryAddressType"]').val() != 'current'){
		  $('#checkout-modal .fieldset:eq(0) .btn-next').html('Continue');
	  }
  },
  loadVendor: function () {
    var showCompleted = $('#showCompletedOrder').is(':checked') ? 1 : 0;

    Order.loadCurrentOrder();
    Order.loadArchivedOrder();


  },
  loadCurrentOrder: function () {
    var param = $('#current-order-form').serialize();
    $.get($('.vendor-order-body').data('url'), 'page=1&eid='+$('.vendor-order-body').data('eid')+'&userId=' + $('.vendor-order-body').data('user-id') + '&' + param, function (html) {
      $('.vendor-order-body').html(html);
      setupUi();
    });
  },
  loadArchivedOrder: function () {
    var param = $('#archived-order-form').serialize();
    $.get($('.vendor-order-archived-body').data('url'), 'page=1&eid='+$('.vendor-order-archived-body').data('eid')+'&userId=' + $('.vendor-order-archived-body').data('user-id') + '&' + param, function (html) {
      $('.vendor-order-archived-body').html(html);
      setupUi();
    })
  },
  loadSalesOrder: function () {
    var param = $('#sales-form').serialize();
    $('#sales-form .has-error').removeClass('has-error');
    $('#sales-form input[type="text"]').each(function(){
    	if($(this).val() == ''){
    		$(this).parent().addClass('has-error');
    	}
    })
    if($('#sales-form .has-error').length == 0 ){
    	$.get($('.vendor-sales-body').data('url'), 'page=1&userId=' + $('.vendor-sales-body').data('user-id') + '&' + param, function (html) {
	      $('.vendor-sales-body').html(html);
	      setupUi();
	    })
	    
	    $.get($('.vendor-sales-body').data('url-summary'), 'userId=' + $('.vendor-sales-body').data('user-id') + '&' + param, function (html) {
	      $('#sales-summary').html(html);
	    })
    }
  },
  loadAdminReceivableOrder : function(){
	  var param = $('#admin-receivable-form').serialize();
    $('#admin-receivable-form .has-error').removeClass('has-error');
    $('#admin-receivable-form input[type="text"]').each(function(){
    	if($(this).val() == ''){
    		$(this).parent().addClass('has-error');
    	}
    })
    if($('#admin-receivable-form .has-error').length == 0 ){
    	$.get($('.admin-receivable-body').data('url'), 'page=1&userId=' + $('.admin-receivable-body').data('user-id') + '&' + param, function (html) {
	      $('.admin-receivable-body').html(html);
	      setupUi();
	    })
	    
	    $.get($('.admin-receivable-body').data('url-summary'), 'userId=' + $('.admin-receivable-body').data('user-id') + '&' + param, function (html) {
	      $('#sales-summary').html(html);
	    })
	    
    }
  },
  search: function (type) {
    if (type == 'current')
      Order.loadCurrentOrder();
    else if (type == 'archived')
      Order.loadArchivedOrder();
    else if (type == 'sales')
        Order.loadSalesOrder();
    else if (type == 'admin-receivable')
        Order.loadAdminReceivableOrder();
    
  },
  confirm: function (orderId) {
    swal({
        title: "Order # " + (10000 + orderId) + " as Confirmed?",
        text: "Are you sure you want to mark this order as confirmed?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'No'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/order/confirm', 'id=' + orderId, function () {
            Order.loadVendor();
            Customer.viewOrder(orderId);
          });
        }
      });
  },
  start: function (orderId) {
    swal({
        title: "Order # " + (10000 + orderId) + " as Started?",
        text: "Are you sure you want to mark this order as started?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'No'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/order/start', 'id=' + orderId, function () {
            Order.loadVendor();
            Customer.viewOrder(orderId);
          });
        }
      });
  },
  pickup: function (orderId) {
    swal({
        title: "Order # " + (10000 + orderId) + " as Picked Up?",
        text: "Are you sure you want to mark this order as picked up?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'No'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/order/pickup', 'id=' + orderId, function () {
            Order.loadVendor();
            Customer.viewOrder(orderId);
          });
        }
      });
  },
  markAsPaid: function (orderId) {
    swal({
        title: "Order # " + (10000 + orderId) + " as Paid?",
        text: "Are you sure you want to mark this order as paid?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'No'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/order/mark-paid', 'id=' + orderId, function () {
            Order.loadVendor();
            Customer.viewOrder(orderId);
          });
        }
      });
  },
  resendFax : function(orderId){
	    swal({
	        title: "Resend Order # " + (10000 + orderId) + " to Fax?",
	        text: "Are you sure you want to resend fax for this order?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonText: 'Yes, confirm',
	        cancelButtonText: 'No'
	      },
	      function (isConfirm) {
	        if (isConfirm) {
	          $.post('/order/send-fax', 'id=' + orderId, function () {
	            //Order.loadVendor();
	            Customer.viewOrder(orderId);
	          });
	        }
	      });
  },
  archiveOrder: function (orderId) {
    swal({
        title: "Order # " + (10000 + orderId) + " as Archived?",
        text: "Are you sure you want to archived this order?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, confirm',
        cancelButtonText: 'No'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/order/archive', 'id=' + orderId, function () {
            Order.loadVendor();
            if ($('#custom-modal').hasClass('in')) {
              Customer.viewOrder(orderId);
            }

          })
        }
      });
  },
  cancelNow : function(){
	  $('#cancel-form .has-error').removeClass('has-error');
	  if($('#cancel-form textarea').val() == ''){
		  $('#cancel-form textarea').parent().addClass('has-error');
	  }
	  if($('#cancel-form .has-error').length == 0){
		  $.post('/order/cancel', $('#cancel-form').serialize(), function(data){
			  var resp = $.parseJSON(data);
			  if(resp.status == 1){
				  Messages.showSuccess('Order Cancellation Successful');
				  if ($('#custom-modal').hasClass('in')) {
		              Customer.viewOrder($('#cancel-form input[name="id"]').val());
		            }
			  }else{
				  Messages.showError('Order Cancellation Failed');
			  }
		  });
	  }
  },
  refundNow : function(){
	  $('#refund-form .has-error').removeClass('has-error');
	  if($('#refund-form textarea').val() == ''){
		  $('#refund-form textarea').parent().addClass('has-error');
	  }
	  if($('#refund-form .has-error').length == 0){
		  $.post('/order/refund', $('#refund-form').serialize(), function(data){
			  var resp = $.parseJSON(data);
			  if(resp.status == 1){
				  Messages.showSuccess('Order Refund Successful');
				  if ($('#custom-modal').hasClass('in')) {
		              Customer.viewOrder($('#refund-form input[name="id"]').val());
		            }
			  }else{
				  Messages.showError('Order Refund Failed');
			  }
		  });
	  }
  },
  cancelOrder : function (orderId) {
	  $.get('/order/cancel', 'id='+orderId, function(data){
		  var resp = $.parseJSON(data);
		  $('#custom-modal .modal-title').html('Cancel Order');
	      $('#custom-modal .modal-body').html(resp.html);
	      $('#custom-modal').modal('show');  
	  });
  },
  refundOrder : function (orderId) {
	  $.get('/order/refund', 'id='+orderId, function(data){
		  var resp = $.parseJSON(data);
		  $('#custom-modal .modal-title').html('Refund Order');
	      $('#custom-modal .modal-body').html(resp.html);
	      $('#custom-modal').modal('show');  
	  });
  },
  showItemOrderSummary: function () {
    $.post('/ordering/item-order-summary', $('#item-order-summary').serialize(), function (html) {
      $('.item-order-summary-content').html(html);
    })
  },
  AddOrder: function () {
    $('#item-order-summary .has-error').removeClass('has-error');
    if ($.isNumeric($('.order-quantity').val()) && parseInt($('.order-quantity').val()) > 0) {
      if($('.order-quantity').data('is-edit') == 1){
        $('.order-' + $('form#item-order-summary').data('key')).remove();
      }
      Order.refreshMainOrderSummary();
    } else {
      $('.order-quantity').parent().addClass('has-error');
    }

  },
  refreshMainOrderSummary: function () {
	  
	  $.ajax({
	        type: "POST",
	        url: '/ordering/add-order',
	        data:  $('#item-order-summary, #main-order-summary').serialize(),
	        async: false,
	        success: function (html) {
	            $('.main-order-summary-content').html(html);
	            $('#custom-modal').modal('hide');
	            $('.delete-order-item').off('click');
	            $('.delete-order-item').on('click', Order.deleteOrderItem);
	            $('.edit-order-item').off('click');
	            $('.edit-order-item').on('click', Order.editOrderItem);
	            
	            $('#item-order-summary').html('');
	            $.material.init();

	            if ($('.has-delivery').length == 1) {
	              $('.has-delivery').off('click');
	              $('.has-delivery').on('click', function () {
	              	
	                Order.refreshMainOrderSummary();
	                $('#checkout-modal').modal('show');
	              });
	            }
	            if($('.is-advance-order').length > 0){
	          	  $('.is-advance-order').off('click');
	                $('.is-advance-order').on('change', Order.checkIsAdvanceDelivery);
	                Order.checkIsAdvanceDelivery();
	            }
	            $('#checkout-modal').off('show.bs.modal');
	            $('#checkout-modal').on('show.bs.modal', function (e) {
	          	  Order.setUpWorkflow();
	          	  Order.checkPaymentType();
	            })
	            $('input[name="paymentType"]').off('change');
	            $('input[name="paymentType"]').on('change', Order.checkPaymentType);
	            
	          }
	  })
	  
  },
  isAdvanceDeliveryTimeValid : function(){
	  var ret = false;
	  $.ajax({
	        type: "GET",
	        url: '/ordering/check-advance-order',
	        data:  'time='+$('#advanceTimePicker').val(),
	        async: false,
	        success: function(data){
	        	ret = true;
	        	var resp = $.parseJSON(data);
	        	if(resp.isValidAdvanceTime == 1){
	        		ret =  true;
	        	}else{
	        		//we show the specific error
	        		//isStoreOpen":1,"isMoreThanTimeLimit":0,"isWithin24Hours
	        		if(resp.isStoreOpen == 0){
	        			Messages.showError('Store is not open on the chosen advance order time.');
	        		}else if(resp.isMoreThanTimeLimit == 0){
	        			Messages.showError('Advance order time should be with greater than the minimum time for pickup.');
	        		}else if(resp.isWithin24Hours == 0){
	        			Messages.showError('Advance order time should be within the next 24 hours.');

	        		}
	        		ret =  false;
	        	}
	        }
	  })
	  return ret;
  },
  checkIsAdvanceDelivery : function(){
	  if($('.is-advance-order').length > 0){
		if($('.is-advance-order[value=1]').is(':checked')){
      		$('.advance-time-pickup').show();
      	}else{
      		$('.advance-time-pickup').hide();
      	}
      } 
  },
  deleteOrderItem: function () {
    $('.order-' + $(this).data('key')).remove();
    Order.refreshMainOrderSummary();
  },
  editOrderItem: function () {
	  var menuItemTitle = $(this).data('menu-item-title');
	  var key = $(this).data('key');
    $.get('/ordering/add-item', 'menuItemId=' + $(this).data('menu-item-id')+'&key='+$(this).data('key'), function (html) {
      $('#custom-modal .modal-title').html(menuItemTitle);
      $('#custom-modal .modal-body').html(html);
      
      var menuItemId = $('input[type="hidden"][name="Orders['+key+']"]').val();
      var quantity = $('input[type="hidden"][name="OrdersQuantity['+key+']"]').val();
      var notes = $('input[type="hidden"][name="OrdersNotes['+key+']"]').val();
      
      $('form#item-order-summary input[name="OrdersQuantity['+key+']"]').val(quantity).trigger('change');
      $('form#item-order-summary textarea[name="OrdersNotes['+key+']"]').html(notes);
      
      if($('input[type="hidden"][name="AddOnsExclusive['+key+']"]').length != 0){
    	  
      }
      if($('input[type="hidden"][name="AddOns['+key+']"]').length != 0){
    	  
      }                
      $('.additionals.'+key).each(function(){
    	  var addOnId = $(this).data('add-on-id');
    	  //if($(this).hasClass('exclusive')){
    		  $('form#item-order-summary .add-on-'+addOnId).prop('checked', true);
    	  //}
    		  
    		  if($('.additionals-special.'+key).length == 1){
    			  $('form#item-order-summary .add-on-special-'+addOnId).val($('.additionals-special.'+key).val());
    		  }
      })
      
      $('#custom-modal').modal('show');
      $('.add-ons-popover').popover({'placement': 'top', 'trigger': 'hover'});
      $.material.init();
      Order.showItemOrderSummary();
      $('.order-changes').off('change');
      $('.order-changes').on('change', Order.showItemOrderSummary);
    })
  },
  applyCoupon: function () {

    $('input[name="couponCode"]').parent().removeClass('has-error');
    if ($('input[name="couponCode"]').val() == '') {
      $('input[name="couponCode"]').parent().addClass('has-error');
    } else {
      $.get('/coupon/check', 'code=' + $('input[name="couponCode"]').val() + '&vendorId=' + $('input[name="couponCode"]').data('vendor-id'), function (data) {
        var resp = $.parseJSON(data);
        if (resp.status == 1) {
          Order.refreshMainOrderSummary();
          $('#checkout-modal').modal('hide');
        } else {
          Messages.showError('Invalid Coupon Code');
        }
      });
    }

  }
}
var setupUiCustomerPromo = function(){
	
	if ($('.promotion-user-pagination').length != 0) {
	    // init bootpag	    
	    
	    $('.promotion-user-pagination').bootpag({
            total: $('.promotion-user-pagination').data('total-pages'),
            page: $('.promotion-user-pagination').data('current-page'),
            maxVisible: 10
          }).on("page", function (event, /* page number here */ num) {
        	  var param = $('#promotion-user-search-form').serialize();
            $.get('/promotion/view-customers', 'page=' + num + '&userId=' + $('.promotion-user-pagination').data('user-id')+'&'+param, function (html) {
              $('.promotion-user-body').html(html);
              setupUiCustomerPromo();
            })
          });
	}
}
var setupUiVendorOverrides = function(){
	
	if ($('.overrides-user-pagination').length != 0) {
	    // init bootpag	    
	    
	    $('.overrides-user-pagination').bootpag({
            total: $('.overrides-user-pagination').data('total-pages'),
            page: $('.overrides-user-pagination').data('current-page'),
            maxVisible: 10
          }).on("page", function (event, /* page number here */ num) {
        	  var param = $('#overrides-user-search-form').serialize();
            $.get('/admin/vendors/view-vendors', 'page=' + num +'&'+param, function (html) {
              $('.overrides-user-body').html(html);
              setupUiVendorOverrides();
            })
          });
	}
	
	if ($('.payable-user-pagination').length != 0) {
	    // init bootpag	    
	    
	    $('.payable-user-pagination').bootpag({
            total: $('.payable-user-pagination').data('total-pages'),
            page: $('.payable-user-pagination').data('current-page'),
            maxVisible: 10
          }).on("page", function (event, /* page number here */ num) {
        	  var param = $('#payable-user-search-form').serialize();
            $.get('/admin/vendors/view-vendors-payable', 'page=' + num +'&'+param, function (html) {
              $('.payable-user-body').html(html);
              setupUiVendorOverrides();
            })
          });
	}
	if ($('.receivable-user-pagination').length != 0) {
	    // init bootpag	    
	    
	    $('.receivable-user-pagination').bootpag({
            total: $('.receivable-user-pagination').data('total-pages'),
            page: $('.receivable-user-pagination').data('current-page'),
            maxVisible: 10
          }).on("page", function (event, /* page number here */ num) {
        	  var param = $('#receivable-user-search-form').serialize();
            $.get('/admin/vendors/view-vendors-receivable', 'page=' + num +'&'+param, function (html) {
              $('.receivable-user-body').html(html);
              setupUiVendorOverrides();
            })
          });
	}
	
}
var setupUi = function () {
  if ($('.customer-order-history-pagination').length != 0) {
    // init bootpag
    $('.customer-order-history-pagination').bootpag({
      total: $('.customer-order-history-pagination').data('total-pages'),
      page: $('.customer-order-history-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      $.get($('.customer-order-body').data('url'), 'page=' + num + '&userId=' + $('.customer-order-history-pagination').data('user-id'), function (html) {
        $('.customer-order-body').html(html);
        setupUi();
      })
    });
    if (Order.init == false) {
      setInterval(Order.loadCustomer, Order.timeLimit); // it will call the function autoload() after each 30 seconds.
      Order.init = true;
    }

  }
  if ($('.vendor-order-history-pagination').length != 0) {
    // init bootpag
    $('.vendor-order-history-pagination').bootpag({
      total: $('.vendor-order-history-pagination').data('total-pages'),
      page: $('.vendor-order-history-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      var showCompleted = $('#showCompletedOrder').is(':checked') ? 1 : 0;
      $.get($('.vendor-order-body').data('url'), 'page=' + num + '&userId=' + $('.vendor-order-history-pagination').data('user-id') + '&filter[showCompleted]=' + showCompleted, function (html) {
        $('.vendor-order-body').html(html);
        setupUi();
      })
    });
    if (Order.init == false) {
      Order.loadVendor();
      Order.init = true;
    }
    $('[data-toggle="tooltip"]').tooltip();
  }
  if ($('.vendor-order-archive-history-pagination').length != 0) {
    // init bootpag
    $('.vendor-order-archive-history-pagination').bootpag({
      total: $('.vendor-order-archive-history-pagination').data('total-pages'),
      page: $('.vendor-order-archive-history-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      $.get($('.vendor-order-archive-body').data('url'), 'page=' + num + '&userId=' + $('.vendor-order-archive-history-pagination').data('user-id'), function (html) {
        $('.vendor-order-archive-body').html(html);
        setupUi();
      })
    });
    /*
     if(Order.init == false){
     setInterval(Order.loadVendor, Order.timeLimit); // it will call the function autoload() after each 30 seconds.
     Order.init = true;
     }
     */
    $('[data-toggle="tooltip"]').tooltip();
  }
  if ($('.vendor-sales-pagination').length != 0) {
	    // init bootpag
	    $('.vendor-sales-pagination').bootpag({
	      total: $('.vendor-sales-pagination').data('total-pages'),
	      page: $('.vendor-sales-pagination').data('current-page'),
	      maxVisible: 10
	    }).on("page", function (event, /* page number here */ num) {
	      $.get($('.vendor-sales-body').data('url'), 'page=' + num + '&userId=' + $('.vendor-sales-pagination').data('user-id'), function (html) {
	        $('.vendor-sales-body').html(html);
	        setupUi();
	      })
	    });
	   
	    $('[data-toggle="tooltip"]').tooltip();
	  }
  if ($('.admin-receivable-pagination').length != 0) {
	    // init bootpag
	    $('.admin-receivable-pagination').bootpag({
	      total: $('.admin-receivable-pagination').data('total-pages'),
	      page: $('.admin-receivable-pagination').data('current-page'),
	      maxVisible: 10
	    }).on("page", function (event, /* page number here */ num) {
	      $.get($('.admin-receivable-body').data('url'), 'page=' + num + '&userId=' + $('.admin-receivable-pagination').data('user-id'), function (html) {
	        $('.admin-receivable-body').html(html);
	        setupUi();
	      })
	    });
	   
	    $('[data-toggle="tooltip"]').tooltip();
	  }
  if ($('.vendor-billing-pagination').length != 0) {
    // init bootpag
    $('.vendor-billing-pagination').bootpag({
      total: $('.vendor-billing-pagination').data('total-pages'),
      page: $('.vendor-billing-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      $.get($('.vendor-billing-body').data('url'), 'page=' + num + '&userId=' + $('.vendor-billing-pagination').data('user-id'), function (html) {
        $('.vendor-billing-body').html(html);
        setupUi();
      })
    });
  }

  if ($('.vendor-customer-pagination').length != 0) {
    // init bootpag
    $('.vendor-customer-pagination').bootpag({
      total: $('.vendor-customer-pagination').data('total-pages'),
      page: $('.vendor-customer-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      $.get('/customer/viewpage', 'page=' + num + '&userId=' + $('.vendor-customer-pagination').data('user-id'), function (html) {
        $('.vendor-customer-body').html(html);
        setupUi();
      })
    });
  }

  if ($('.promo-email-paginationn').length != 0) {
    // init bootpag
    $('.promo-email-pagination').bootpag({
      total: $('.promo-email-pagination').data('total-pages'),
      page: $('.promo-email-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      $.get($('.promo-email-body').data('url'), 'page=' + num + '&userId=' + $('.promo-email-pagination').data('user-id'), function (html) {
        $('.promo-email-body').html(html);
        setupUi();
      })
    });
  }

  if ($('.promo-sms-pagination').length != 0) {
    // init bootpag
    $('.promo-sms-pagination').bootpag({
      total: $('.promo-sms-pagination').data('total-pages'),
      page: $('.promo-sms-pagination').data('current-page'),
      maxVisible: 10
    }).on("page", function (event, /* page number here */ num) {
      $.get($('.promo-sms-body').data('url'), 'page=' + num + '&userId=' + $('.promo-sms-pagination').data('user-id'), function (html) {
        $('.promo-sms-body').html(html);
        setupUi();
      })
    });
  }

  VendorSettings.setupOperatingHoursUI();
};


function validateEmailFormat(sEmail) {
  var str = sEmail
  var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  if (filter.test(str))
    testresults = true
  else {
    //alert("Please input a valid email address!")
    testresults = false
  }
  return (testresults)
}
function stripeResponseHandlerSaveBilling(status, response) {

  // Grab the form:
  var $form = $('#billing-form');
  if (response.error) { // Problem!

    // Show the errors on the form:
    Messages.showError(response.error.message);
    //$form.find('.btn-register').prop('disabled', false); // Re-enable submission

  } else { // Token created!

    // Get the token ID:
    //var token = response.id;

    // Insert the token into the form so it gets submitted to the server:
    //$form.append($('<input type="hidden" name="stripeToken" />').val(token));
	 
    $('form#billing-form').submit();
  }
}
function stripeResponseOrderHandler(status, response) {

	  // Grab the form:
	  var $form = $('#main-order-summary');

	  if (response.error) { // Problem!

	    // Show the errors on the form:
	    Messages.showError(response.error.message);
	    $form.find('.btn-next-cc').prop('disabled', false); // Re-enable submission

	  } else { // Token created!

	    // Get the token ID:
	    //var token = response.id;

	    // Insert the token into the form so it gets submitted to the server:
	    //$form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    //$('form#main-order-summary').submit();
	    
	    $('#checkout-modal .fieldset:visible').fadeOut(400, function () {
	    	 $('#checkout-modal .fieldset.step4').fadeIn();
	    });
	   
	  }
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
    //var token = response.id;

    // Insert the token into the form so it gets submitted to the server:
    //$form.append($('<input type="hidden" name="stripeToken" />').val(token));
    $('form#register-form').submit();
  }
}

var VendorMenu = {
	specialOrderChange : function(){
		if($('.special-order').length == 1){
			var isChecked = $('.special-order').is(':checked');
			if(isChecked){
				$('.special-price').show();
				$('.non-special-price').hide();
			}else{
				$('.special-price').hide();
				$('.non-special-price').show();
			}
		}
	},
  saveItem: function () {
    $('#menu-item-form .has-error').removeClass('has-error');
    $('#menu-item-form input[type="text"]').each(function () {
      if ($(this).val() == '') {
        $(this).parent().addClass('has-error');
      }
      if ($(this).hasClass('price') && !$.isNumeric($(this).val())) {
        $(this).parent().addClass('has-error');
      }
      
      
    });

    if ($('#menu-item-form .has-error').length == 0) {
      //$('#menu-item-form').submit();
      
      $.post($('#menu-item-form').attr('action'), $('#menu-item-form').serialize(), function(data){
    	  $('#custom-modal').modal('hide');
    	  var resp = $.parseJSON(data);
    	  if($('.menu-panel[data-menu-id='+resp.id+']').length == 1)
    		  $('.menu-panel[data-menu-id='+resp.id+']').replaceWith(resp.html);
    	  else{
    		  //addd in the end
    		  $('#category'+resp.menuCategoryId+' .categories-menu-panel').append(resp.html);

    	  }
    	  Messages.showSuccess('Menu Item Saved Successfully');
      })
    }
  },
  addItem : function (categoryId, menuId) {
	    var sorting = $('.vendor-menu-category-item-' + categoryId).length + 1;
	    $.get('/menu/add-item', 'id=' + menuId + '&categoryId=' + categoryId + '&sorting=' + sorting, function (html) {
	      $('#custom-modal .modal-title').html('Add Menu Item');
	      $('#custom-modal .modal-body').html(html);
	      $('#custom-modal').modal('show');
	    })

  },
  editItem :  function (menuItemId) {
	    $.get('/menu/edit-item', 'id=' + menuItemId, function (html) {
	        $('#custom-modal .modal-title').html('Edit Menu Item');
	        $('#custom-modal .modal-body').html(html);
	        $('#custom-modal').modal('show');
	        $('.delete-menu-item').off('click');
	        $('.delete-menu-item').on('click', function () {
	          var itemId = menuItemId;
	          swal({
	              title: "Delete Item?",
	              text: "Are you sure you want to delete this item?",
	              type: "warning",
	              showCancelButton: true,
	              confirmButtonText: 'Yes, remove it',
	              cancelButtonText: 'No, keep it'
	            },
	            function (isConfirm) {
	              if (isConfirm) {
	                $.post('/menu/delete-item', 'id=' + itemId, function (html) {
	                  //window.location.href = '/menu';
	                  
	                  if($('.menu-panel[data-menu-id='+itemId+']').length == 1){
	                	  $('.menu-panel[data-menu-id='+itemId+']').remove();
	                	  $('#custom-modal').modal('hide');
	                	  Messages.showSuccess('Menu Item Deleted Successfully');
	                  }
	            		  
	                  
	                });
	              }
	            });
	        });

	      })

    },
    editCategoryItem : function (catId) {

        $.get('/menu/edit-category', 'id=' + catId, function (html) {
          $('#custom-modal .modal-title').html('Edit Category');
          $('#custom-modal .modal-body').html(html);
          $('#custom-modal').modal('show');

          $('.delete-menu-category').off('click');
          $('.delete-menu-category').on('click', function () {
            var categoryId = $(this).data('menu-category-id');
            swal({
                title: "Delete Menu Category?",
                text: "Are you sure you want to delete this menu category?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'No, keep it'
              },
              function (isConfirm) {
                if (isConfirm) {
                  $.post('/menu/delete-category', 'id=' + categoryId, function (html) {
                	  //window.location.href = '/menu';
                      if($('.categories-panel[data-category-id='+categoryId+']').length == 1){
	                	  $('.categories-panel[data-category-id='+categoryId+']').remove();
	                	  $('#custom-modal').modal('hide');
	                	  Messages.showSuccess('Menu Category Deleted Successfully');
	                  }
                  });
                }
              }
            );
          });
        })

      },
  saveItemAddOns: function () {
    $('#menu-item-add-ons-form .has-error').removeClass('has-error');
    $('#menu-item-add-ons-form input[type="text"]').each(function () {
      if ($(this).val() == '') {
    	  
    	  if($('.special-order').is(':checked')){
    		  if($(this).hasClass('price-custom') && $(this).val() == ''){
    			  $(this).val(0);
    		  }else{
    			  if ($(this).hasClass('price-custom') && !$.isNumeric($(this).val())) {
            		  $(this).parent().addClass('has-error');
            	  }else{
            		  if(!$(this).hasClass('price')){
        	    		  $(this).parent().addClass('has-error');
        	    	  }
        	      }
    		  }
        	  
          }else{
        	  if ($(this).hasClass('price') && !$.isNumeric($(this).val())) {
        	        $(this).parent().addClass('has-error');
    	      }else{
    	    	  if(!$(this).hasClass('price-custom')){
    	    		  $(this).parent().addClass('has-error');
    	    	  }
    	      }
          }
    	  
        
      }
      
      
      
    });

    if ($('#menu-item-add-ons-form .has-error').length == 0) {

      var menuItemAddonData = $('#menu-item-add-ons-form').serialize();
      var menuItemAddonDataJson = JSON.parse(JSON.stringify($('#menu-item-add-ons-form').serializeArray()));
      var addonType = Boolean(parseInt(menuItemAddonDataJson[4].value)) ? 'exclusive' : 'additions';

      $.post('/menu/save-item-add-ons', menuItemAddonData, function (data) {
        var resp = $.parseJSON(data);
        if (resp.status == 1) {
          Messages.showSuccess('Add-on Saved Successfully');
          if (resp.type == 'menu-item') {
            VendorMenu.openNewAddOn(menuItemAddonDataJson[0].value, addonType);
          }
          else if (resp.type == 'category') {
            VendorMenu.openNewAddOnCategory(menuItemAddonDataJson[1].value, addonType);
          }
        }
      })
    }
  },
  openMenuDetails: function (menuId) {
    $('.menu-details-' + menuId).toggle();
  },
  saveCategory: function () {
    $('#category-item-form .has-error').removeClass('has-error');
    $('#category-item-form input[type="text"]').each(function () {
      if ($(this).val() == '') {
        $(this).parent().addClass('has-error');
      }
    });

    if ($('#category-item-form .has-error').length == 0) {
      $.post($('#category-item-form').attr('action'), $('#category-item-form').serialize(), function(data){
    	  $('#custom-modal').modal('hide');
    	  var resp = $.parseJSON(data);
    	  if($('.categories-panel[data-category-id='+resp.id+']').length == 1)
    		  $('.categories-panel[data-category-id='+resp.id+']').replaceWith(resp.html);
    	  else{
    		  //addd in the end
    		  $('#menu-'+resp.menuId+' .categories-main-panel').append(resp.html);

    	  }
    	  Messages.showSuccess('Menu Category Saved Successfully');
      })
      
    }
  },
  updateCategorySort: function (sort) {
    $.post('/menu/save-category-sort', 'sort=' + sort, function () {
      Messages.showSuccess('Updated Category Ordering Successfully');
    })
  },
  updateMenuSort: function (sort) {
    $.post('/menu/save-menu-sort', 'sort=' + sort, function () {
      Messages.showSuccess('Updated Menu Ordering Successfully');
    })
  },
  updateMenuAddOnSort: function (sort) {
    $.post('/menu/save-menu-add-on-sort', 'sort=' + sort, function () {
      Messages.showSuccess('Updated Add On Ordering Successfully');
    })
  },
  setupUI: function () {

    $('.categories-main-panel').sortable({
      update: function (event, ui) {
        var sortNums = '';
        ui.item.parent().find('.categories-panel').each(function (index) {
          if (sortNums != '') {
            sortNums += ',';
          }
          sortNums += $(this).data('category-id') + ':' + index;
        });
        if (sortNums != '')
          VendorMenu.updateCategorySort(sortNums);
      }
    });
    $('.categories-menu-panel').sortable(
      {
        update: function (event, ui) {
          var sortNums = '';
          ui.item.parent().find('.menu-panel').each(function (index) {
            if (sortNums != '') {
              sortNums += ',';
            }
            sortNums += $(this).data('menu-id') + ':' + index;
          });
          if (sortNums != '')
            VendorMenu.updateMenuSort(sortNums);
        }
      });

    $('.add-ons-list').sortable(
      {
        update: function (event, ui) {
          var sortNums = '';
          ui.item.parent().find('.list-group-item').each(function (index) {
            if (sortNums != '') {
              sortNums += ',';
            }
            sortNums += $(this).data('menu-item-add-on-id') + ':' + index;
          });

          if (sortNums != '') {
            VendorMenu.updateMenuAddOnSort(sortNums);
          }

        }
      });
    VendorMenu.specialOrderChange();
  },
  editAddOn: function (id) {
    $.get('/menu/edit-item-add-ons', 'id=' + id, function (html) {
      $('#custom-modal .modal-title').html('Edit Add-ons');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
      $('.delete-menu-item-add-on').off('click');
      $('.delete-menu-item-add-on').on('click', function () {
        var itemAddOnId = $(this).data('menu-item-add-on-id');
        var menuItemId = $(this).data('menu-item-id');
        var type = $(this).data('type');
        var menuCategoryId = $(this).data('menu-category-id');
        swal({
            title: "Delete Item Add-on?",
            text: "Are you sure you want to delete this item add-on?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'No, keep it'
          },
          function (isConfirm) {
            if (isConfirm) {
              $.post('/menu/delete-item-add-ons', 'id=' + itemAddOnId, function (html) {
                if (type == 'menu-item') {
                  Messages.showSuccess('Menu Item Add-on Deleted Successfully');
                  VendorMenu.openNewAddOn(menuItemId, 'additions');
                } else if (type == 'category') {
                  Messages.showSuccess('Menu Category Add-on Deleted Successfully');
                  VendorMenu.openNewAddOnCategory(menuCategoryId, 'additions');
                }
              });
            }
          });
      });
    });
  },
  newAddOn: function (id, type) {
    if (type == 'menu-item')
      VendorMenu.openNewAddOn(id, 'additions');
    else if (type == 'category')
      VendorMenu.openNewAddOnCategory(id, 'additions');
  },
  openNewAddOn: function (menuItemId, addonType) {
    $.get('/menu/add-item-add-ons', 'id=' + menuItemId, function (html) {
      $('#custom-modal .modal-title').html('Menu Item - Add-ons');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
      $('.nav-tabs a[href="#tab-' + addonType + '"]').tab('show');
      $.material.init();
    })
  },
  openNewAddOnCategory: function (menuCategoryId, addonType) {
    $.get('/menu/add-category-add-ons', 'id=' + menuCategoryId, function (html) {
      $('#custom-modal .modal-title').html('Category - Add-ons');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
      $('.nav-tabs a[href="#tab-' + addonType + '"]').tab('show');
      $.material.init();
    })
  }
}


var VendorSettings = {
  validateSettings: function () {
    $('.vendor-settings-form .has-error').removeClass('has-error');
    $('.numeric').each(function () {
      if ($.isNumeric($(this).val()) == false && $(this).parents('.row[data-key="' + $(this).data('key') + '"]').is(':visible')) {
        $(this).parent().addClass('has-error');
      }
    });
    
    if($('select[name="TenantCode[SEND_FAX_ON_ORDER]"]').length == 1 && $('select[name="TenantCode[SEND_FAX_ON_ORDER]"]').val() == 1){
    	//we validate the phone
    	if($('.fax-number').val().length != 14){
    		$('.fax-number').parent().addClass('has-error');
    	}
    }

    if ($('.vendor-settings-form .has-error').length == 0) {
      return true;
    }
    return false;
  },
  validateOperatingHours: function () {
    $('#operating-hours-form').each(function () {
      if ($(this).find('.start').val() == '' && $(this).find('.end').val() != '') {
        $(this).find('.start').parent().addClass('has-error');
      } else if ($(this).find('.start').val() != '' && $(this).find('.end').val() == '') {
        $(this).find('.end').parent().addClass('has-error');
      }
    });

    if ($('#operating-hours-form .has-error').length == 0) {
      return true;
    }
    return false;
  },
  viewPromo: function (id) {
    $.get('/promotion/view', 'id=' + id, function (html) {
      $('#custom-modal .modal-title').html('View Promo');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
    });
  },
  addOperatingHours: function (day) {
    //$('.operating-hours[data-day='+day+']:eq(0)').clone().appendTo($('.operating-hour-list[data-day='+day+']'));

    var $elem = $('.operating-hours[data-day=' + day + ']:eq(0)').clone();
    $elem.find('select').val('');
    $('.operating-hour-list[data-day=' + day + ']').append($elem);

    VendorSettings.setupOperatingHoursUI();
  },
  setupOperatingHoursUI: function () {
    $('.delete-operating-hours').off('click');
    $('.delete-operating-hours').on('click', function () {
      var day = $(this).data('day');
      if ($('.operating-hours[data-day=' + day + ']').length == 1) {
        var $elem = $('.operating-hours[data-day=' + day + ']').clone();
        $(this).parents('.operating-hours').remove();
        $elem.find('select').val('');
        $('.operating-hour-list[data-day=' + day + ']').append($elem);
      }
      else {
        $(this).parents('.operating-hours').remove();
      }
      VendorSettings.previewHours();
    });
    $('.operating-hr').on('change', VendorSettings.previewHours);
  },
  previewHours: function () {
    $.post('/vendor/preview-hours', $('#operating-hours-form').serialize(), function (html) {
      $('.preview-operating-hours').html(html);
    });
  },
  addMenu : function(vendorId){
	  $.get('/menu/create', 'vendorId='+vendorId, function(html){
		  $('#custom-modal .modal-title').html('Add Menu');
	      $('#custom-modal .modal-body').html(html);
	      $('#custom-modal').modal('show');
	  });
	  
  },
  editMenu : function(menuId){
	  $.get('/menu/edit', 'id='+menuId, function(html){
		  $('#custom-modal .modal-title').html('Edit Menu');
	      $('#custom-modal .modal-body').html(html);
	      $('#custom-modal').modal('show');
	  });
	  
  },
  saveMenu : function(){
	  	$('#menu-form .has-error').removeClass('has-error');
	    $('#menu-form input[type="text"], #menu-form select').each(function () {
	      if ($(this).val() == '') {
	        $(this).parent().addClass('has-error');
	      }
	      
	    });

	    if ($('#menu-form .has-error').length == 0) {
	      $('#menu-form').submit();
	    }
	  
  },
  deleteMenu : function(id){
	  swal({
          title: "Delete Menu?",
          text: "Are you sure you want to delete this menu?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: 'Yes, remove it',
          cancelButtonText: 'No, keep it'
        },
        function (isConfirm) {
          if (isConfirm) {
            $.post('/menu/delete-menu', 'id=' + id, function (html) {
              window.location.href = '/menu';
            });
          }
        }
      );
  }
}
var Vendors  = {
	search : function(){
		var prefix = '';
	    if(window.location.href.indexOf('/admin') != -1){
	    	prefix = '/admin';
	    }
	    
	    var param = $('#overrides-user-search-form').serialize();
        $.get('/admin/vendors/view-vendors', 'page=1&'+param, function (html) {
          $('.overrides-user-body').html(html);
          setupUiVendorOverrides();
        })
	},
	searchPayable : function(){
		var prefix = '';
	    
	    var param = $('#payable-user-search-form').serialize();
        $.get('/admin/vendors/view-vendors-payable', 'page=1&'+param, function (html) {
          $('.payable-user-body').html(html);
          setupUiVendorOverrides();
        })
	},
	searchReceivable : function(){
		var prefix = '';
	    
	    var param = $('#receivable-user-search-form').serialize();
        $.get('/admin/vendors/view-vendors-receivable', 'page=1&'+param, function (html) {
          $('.receivable-user-body').html(html);
          setupUiVendorOverrides();
        })
	},
}
var Customer = {
  viewOrder: function (orderId) {
    $.get('/ordering/details', 'id=' + orderId, function (html) {
      $('#custom-modal .modal-title').html('Order Details');
      $('#custom-modal .modal-body').html(html);
      $('#custom-modal').modal('show');
    });
  },
  search: function () {
    var param = $('#customer-search-form').serialize();


    $.get('/customer/viewpage', 'page=1&userId=' + $('.vendor-customer-pagination').data('user-id') + '&' + param, function (html) {
      $('.vendor-customer-body').html(html);
      setupUi();
      listLinkActions();
    })

  },
  selectAll : function(){
	  $('.customer-promo').prop('checked', $('.all-checkbox-promo').is(':checked'));
  },
  addPromoCustomer : function(){
	  if($('.customer-promo:checked').length == 0){
		  Messages.showError('Please select a user');
	  }else{
		  $('.customer-promo:checked').each(function(){
			 var html = '<tr data-id="'+$(this).data('id')+'" ><td>'+$(this).data('name')+'</td><td><button type="button" style="padding: 0" class="btn btn-danger btn-xs" onclick="javascript: Customer.removeCustomerPromo('+$(this).data('id')+')">Remove</button></td></tr>';
			 if($('.customer-list tr[data-id='+$(this).data('id')+']').length == 0){
				 $('.customer-list').append(html);
			 }
		  });
	  }
  },
  removeCustomerPromo : function(id){
	  swal({
	        title: "Remove User?",
	        text: "Are you sure you want to remove this user?",
	        showCancelButton: true,
	        confirmButtonText: 'Yes, continue',
	        cancelButtonText: 'No, keep it'
	      },
	      function (isConfirm) {
	        if (isConfirm) {
	        	$('.customer-list tr[data-id='+id+']').remove();
	        }
	      });
	  
  },
  sendPromoNow : function(type){
	 var cont = false;
	 if($('.send-to-all').is(':checked') || $('.customer-list tr').length != 0) {
		 cont = true;
	 }else{
		 Messages.showError('Please select at least 1 user');
	 }
	 if(cont){
	 swal({
	        title: "Confirm Send?",
	        text: "Are you sure you want to send this promo?",
	        showCancelButton: true,
	        confirmButtonText: 'Yes, continue',
	        cancelButtonText: 'No, keep it'
	      },
	      function (isConfirm) {
	        if (isConfirm) {
	        	var userList = '';
	        	if($('.send-to-all').is(':checked')){
	        		userList = 'ALL';
	        	}else{
	        		$('.customer-list tr').each(function(){
	        			if(userList != '')
	        				userList += ',';
	        			userList += $(this).data('id');
	        		})
	        	}
	        	var prefix = '';
	     	    if(window.location.href.indexOf('/admin') != -1){
	     	    	prefix = '/admin';
	     	    }
	     	    
	     	    
	        	$('.promotion-form-'+type+' #userList').val(userList);
	        	$.post(prefix+'/promotion/send?to=1', $('.promotion-form-'+type).serialize(), function (resp) {
	       	      var data = $.parseJSON(resp);
	       	      if (data.status == 1) {
	       	        Messages.showSuccess('Promotions is being processed already');
	       	      }
	       	    })
	        	$('#custom-modal').modal('hide');
	        }
	      });
	 }
  },
  searchPromo: function () {
	    var prefix = '';
	    if(window.location.href.indexOf('/admin') != -1){
	    	prefix = '/admin';
	    }
	    
	    var param = $('#promotion-user-search-form').serialize();
        $.get(prefix+'/promotion/view-customers', 'page=1&userId=' + $('.promotion-user-pagination').data('user-id')+'&'+param, function (html) {
          $('.promotion-user-body').html(html);
          setupUiCustomerPromo();
        })
        

  },
  activate: function (id) {
    swal({
        title: "Activate Customer?",
        text: "Are you sure you want to activate this customer account?",
        showCancelButton: true,
        confirmButtonText: 'Yes, continue',
        cancelButtonText: 'No, keep it'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/customer/activate', 'id=' + id, function (html) {
            Messages.showSuccess('Customer Activated Successfully');
            Customer.search();
          });
        }
      });
  },
  deactivate: function (id) {
    swal({
        title: "Deactivate Customer?",
        text: "Are you sure you want to deactivate this customer account?",
        showCancelButton: true,
        confirmButtonText: 'Yes, continue',
        cancelButtonText: 'No, keep it'
      },
      function (isConfirm) {
        if (isConfirm) {
          $.post('/customer/deactivate', 'id=' + id, function (html) {
            Messages.showSuccess('Customer Deactivated Successfully');
            Customer.search();
          });
        }
      });
  }
};
var AdminSettings = {
	validateSettings: function () {
	    $('form#admin-settings-form .has-error').removeClass('has-error');
	    $('.numeric').each(function () {
	      if ($.isNumeric($(this).val()) == false) {
	        $(this).parent().addClass('has-error');
	      }
	    });

	    $('.more-than-zero').each(function () {
	      if ($.isNumeric($(this).val()) == false || parseFloat($(this).val()) < 0) {
	        $(this).parent().addClass('has-error');
	      }
	    });
    
	    if ($('form#admin-settings-form .has-error').length == 0) {
	      return true;
	    }
	    return false;
	  },
}
var Messages = {
  showError: function (message) {
    swal({
      title: 'Notification',
      text: message,
      type: 'error',
      allowOutsideClick: true
    });
  },
  showSuccess: function (message) {
    swal({
      title: 'Notification',
      text: message,
      type: 'success',
      allowOutsideClick: true
    });
  }
};

var App = {
	ccValidator : function(params, callback){
		/*
		 * (number: $('.card-number').val(),
    	        cvc: $('.card-cvv').val(),
    	        exp_month: $('.card-expiry-month').val(),
    	        exp_year: $('.card-expiry-year').val(),stripeResponseHandlerSaveBilling)
		 */
		//console.log(params.number);
		
		var valid = $.payment.validateCardNumber(params.number);
		var response = {};
		
		if (!valid) {
		  response.error = {};
		  response.error.message = 'Invalid Credit Card Number';
		  return callback(true, response);
		}
		var valid = $.payment.validateCardExpiry(params.exp_month, params.exp_year); //=> true
		if (!valid) {
		  response.error = {};
		  response.error.message = 'Invalid Credit Card Expiration Date';
		  return callback(true, response);
		}
		
		var valid = $.payment.validateCardCVC(params.cvc, $.payment.cardType(params.number)); //=> true
		if (!valid) {
		  response.error = {};
		  response.error.message = 'Invalid Credit Card Expiration Date';
		  return callback(true, response);
		}
		 return callback(true, response);
	}
}
function listLinkActions() {
  $('.show-action').on('click', function (e) {
    e.preventDefault();
    var $_this = $(this),
      options = {'html': true, 'placement': 'auto right', container: 'body'},
      content = $_this.next('.pop-content').html();

    $_this.data('content', content);
    $_this.popover(options).popover('show');

  });
  /* hide on widow resize not to have popover position issues */
  $(window).on('resize', function () {
    $('.show-action').popover('hide');
  });
  /* Hide all pops */
  $(document).on('click', function (e) {
    $('.show-action').each(function () {
      //the 'is' for buttons that trigger popups
      //the 'has' for icons within a button that triggers a popup
      if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
        $(this).popover('hide').popover('destroy');
      }
    });
  });
}
