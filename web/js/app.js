$(document).ready(function() {
	//if($("#phone").length == 1)
		//$('#phone').mask("(999) 999-9999");
	$(".order-quantity[type='number']").keypress(function (evt) {
	    evt.preventDefault();
	});	
	$('#register-form .fieldset:eq(0)').fadeIn('slow');
	
	$('.closeall').click(function(){
	  $('.panel-collapse.in')
	    .collapse('hide');
	});
	$('.openall').click(function(){
	  $('.panel-collapse:not(".in")')
	    .collapse('show');
	});
	
	$('#register-form input[type="text"], #register-form input[type="email"], #register-form input[type="tel"]').on('focus', function() {
    	$(this).parent().removeClass('has-error');
    	$(this).parent().find('.help-block').html('');
    });
	
	$('#showCompletedOrder').on('change', function(){
		Order.loadVendor();
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
    
    
	$('.btn-save-billing').on('click', function(){
		$('#billing-form').find('input[type="text"],input[type="email"],input[type="tel"], select').each(function(){
			if($(this).hasClass('not-required')){
    			;
    		}else if( $(this).val() == "" ) {
    			
    			$(this).parent().addClass('has-error');
    		}
    		else {
    			$(this).parent().removeClass('has-error');
    		}
		});
		
		if($('#billing-form .has-error' ).length == 0){
			//we 
			//(this).attr('disabled', true);
			Stripe.card.createToken({
				  number: $('.card-number').val(),
				  cvc: $('.card-cvv').val(),
				  exp_month: $('.card-expiry-month').val(),
				  exp_year: $('.card-expiry-year').val()
			}, stripeResponseHandlerSaveBilling);
		}
	})
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
    if($('.add-category-item').length != 0){
    	VendorMenu.setupUI();	
    }
    
    $('.add-category-item').on('click', function(){
    	var sorting = $('.vendor-menu-categories').length + 1;
    	$.get('/menu/add-category', 'sorting='+sorting, function(html){
    		$('#custom-modal .modal-title').html('Add Category');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    	})
    	
    });
    $('.edit-category-item').on('click', function(){
    	
    	$.get('/menu/edit-category', 'id='+$(this).data('id'), function(html){
    		$('#custom-modal .modal-title').html('Edit Category');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    	})
    	
    });
    $('.add-menu-item').on('click', function(){
    	var sorting = $('.vendor-menu-category-item-'+$(this).data('category-id')).length + 1;
    	$.get('/menu/add-item', 'id='+$(this).data('id')+'&categoryId='+$(this).data('category-id')+'&sorting='+sorting, function(html){
    		$('#custom-modal .modal-title').html('Add Menu Item');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    	})
    	
    });
    $('.add-menu-item-add-ons').on('click', function(){
    	var sorting = $('.vendor-menu-item-add-on-'+$(this).data('menu-item-id')).length + 1;
    	$.get('/menu/add-item-add-ons', 'id='+$(this).data('menu-item-id')+'&sorting='+sorting, function(html){
    		$('#custom-modal .modal-title').html('Add Menu Item - Add-ons');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    		
    		
    		
    	})
    	
    });
    
    $('.edit-menu-item-add-on').on('click', function(){
    	$.get('/menu/edit-item-add-ons', 'id='+$(this).data('menu-item-add-on-id'), function(html){
    		$('#custom-modal .modal-title').html('Edit Menu Item - Add-ons');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    		$('.delete-menu-item-add-on').off('click');
    		$('.delete-menu-item-add-on').on('click', function(){
    	    	if(confirm('Are you sure you want to delete this item add on?')){
    		    	$.post('/menu/delete-item-add-ons', 'id='+$(this).data('menu-item-add-on-id'), function(html){
    		    		window.location.href = '/menu';
    		    	})
    	    	}
    	    	
    	    });
    		
    	})
    	
    });
    $('.edit-menu-item').on('click', function(){
    	$.get('/menu/edit-item', 'id='+$(this).data('menu-item-id'), function(html){
    		$('#custom-modal .modal-title').html('Edit Menu Item');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    		$('.delete-menu-item').off('click');
    		$('.delete-menu-item').on('click', function(){
    	    	if(confirm('Are you sure you want to delete this item?')){
    		    	$.post('/menu/delete-item', 'id='+$(this).data('menu-item-id'), function(html){
    		    		window.location.href = '/menu';
    		    	})
    	    	}
    	    	
    	    });
    		
    	})
    	
    });
    
    $('.btn-submit-order').on('click', function(){
    	var hasOrder = false;
    	$('.order-quantity').each(function(){
    		if($(this).val() > 0){
    			hasOrder = true;
    		}
    	});
    	if(hasOrder){    	
	    	$.post('/ordering/summary', $('#customer-order-form').serialize(), function(html){
	    		$('#custom-modal .modal-title').html('Order Summary');
	    		$('#custom-modal .modal-body').html(html);
	    		$('#custom-modal').modal('show');	
	    	});
    	}else{
    		alert('Please add your order');
    	}
    });
    
    $('.btn-send-promo').on('click', function(){
    	$('.promotion-form has-error').removeClass('has-error');
    	if($('.promotion-form input[name="subject"]').val() == ''){
    		$('.promotion-form input[name="subject"]').parent().addClass('has-error');
    		return false;
    	}
    	
    	var to = $(this).data('to');
    	$('#promo-html').val(tinyMCE.get('promotion').getContent())
    	$.post('/promotion/send?to='+to, $('.promotion-form').serialize(), function(resp){
    		var data = $.parseJSON(resp);
    		if(data.status == 1){
    			Messages.showSuccess('Promotions is being processed already');
    		}
    	})
    })
     $('.btn-send-promo-sms').on('click', function(){
    	var to = $(this).data('to');
    	$.post('/promotion/send?to='+to, $('.promotion-form-sms').serialize(), function(resp){
    		var data = $.parseJSON(resp);
    		if(data.status == 1){
    			Messages.showSuccess('Promotions is being processed already');
    		}
    	})
    })
    
    if( $('#tab-settings').length != 0){

        var clipboard = new Clipboard('.btn-copy-widget');

        clipboard.on('success', function(e) {
            e.clearSelection();
            Messages.showSuccess('Copied text to clipboard');
        });
        clipboard.on('error', function(e) {
            var d = new CM();
            Messages.showError('An Error occurred. The text was no copied in your Clipboard. Please try again.');
        });
        
        $('textarea.copy-content').on('click', function(){
        	
        	
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
    listLinkActions();
    $('.add-ons-popover').popover({'placement' : 'left', 'trigger' : 'hover'});
});
var Order = {
	init : false,
	timeLimit : 15000, //30000
	loadCustomer : function(){
		$.get($('.customer-order-body').data('url'), 'page=1&userId='+$('.customer-order-history-pagination').data('user-id'), function(html){
       	 	$('.customer-order-body').html(html);
       	 	setupUi();
        })
		
	},
	loadVendor : function(){
		var showCompleted = $('#showCompletedOrder').is(':checked') ? 1 : 0;
		$.get($('.vendor-order-body').data('url'), 'page=1&userId='+$('.vendor-order-body').data('user-id')+'&filter[showCompleted]='+showCompleted, function(html){
       	 $('.vendor-order-body').html(html);
       	 setupUi();
        })
        
	},
	confirm : function(orderId){
		$.post('/order/confirm', 'id='+orderId, function(){
			Order.loadVendor();	
		})
		
	},
	start : function(orderId){
		$.post('/order/start', 'id='+orderId, function(){
			Order.loadVendor();	
		})
	},
	pickup : function(orderId){
		$.post('/order/pickup', 'id='+orderId, function(){
			Order.loadVendor();	
		})
	}

}
var setupUi = function(){
	if( $('.customer-order-history-pagination').length != 0){
	    // init bootpag
	    	$('.customer-order-history-pagination').bootpag({
		        total: $('.customer-order-history-pagination').data('total-pages'),
		        page: $('.customer-order-history-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		         $.get($('.customer-order-body').data('url'), 'page='+num+'&userId='+$('.customer-order-history-pagination').data('user-id'), function(html){
		        	 $('.customer-order-body').html(html);
		        	 setupUi();
		         })
		    });
	    	if(Order.init == false){
	    		setInterval(Order.loadCustomer, Order.timeLimit); // it will call the function autoload() after each 30 seconds.	
	    		Order.init = true;
	    	}
	    	      
	}
	if( $('.vendor-order-history-pagination').length != 0){
	    // init bootpag
	    	$('.vendor-order-history-pagination').bootpag({
		        total: $('.vendor-order-history-pagination').data('total-pages'),
		        page: $('.vendor-order-history-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		    	var showCompleted = $('#showCompletedOrder').is(':checked') ? 1 : 0;
		         $.get($('.vendor-order-body').data('url'), 'page='+num+'&userId='+$('.vendor-order-history-pagination').data('user-id')+'&filter[showCompleted]='+showCompleted, function(html){
		        	 $('.vendor-order-body').html(html);
		        	 setupUi();
		         })
		    });
	    	if(Order.init == false){
	    		setInterval(Order.loadVendor, Order.timeLimit); // it will call the function autoload() after each 30 seconds.	
	    		Order.init = true;
	    	}
	}
	if( $('.vendor-order-archive-history-pagination').length != 0){
	    // init bootpag
	    	$('.vendor-order-archive-history-pagination').bootpag({
		        total: $('.vendor-order-archive-history-pagination').data('total-pages'),
		        page: $('.vendor-order-archive-history-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		         $.get($('.vendor-order-archive-body').data('url'), 'page='+num+'&userId='+$('.vendor-order-archive-history-pagination').data('user-id'), function(html){
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
	}
	if( $('.vendor-billing-pagination').length != 0){
	    // init bootpag
	    	$('.vendor-billing-pagination').bootpag({
		        total: $('.vendor-billing-pagination').data('total-pages'),
		        page: $('.vendor-billing-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		         $.get($('.vendor-billing-body').data('url'), 'page='+num+'&userId='+$('.vendor-billing-pagination').data('user-id'), function(html){
		        	 $('.vendor-billing-body').html(html);
		        	 setupUi();
		         })
		    });
	}
	
	if( $('.vendor-customer-pagination').length != 0){
	    // init bootpag
	    	$('.vendor-customer-pagination').bootpag({
		        total: $('.vendor-customer-pagination').data('total-pages'),
		        page: $('.vendor-customer-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		         $.get('/customer/viewpage', 'page='+num+'&userId='+$('.vendor-customer-pagination').data('user-id'), function(html){
		        	 $('.vendor-customer-body').html(html);
		        	 setupUi();
		         })
		    });
	}
	
	if( $('.promo-email-paginationn').length != 0){
	    // init bootpag
	    	$('.promo-email-pagination').bootpag({
		        total: $('.promo-email-pagination').data('total-pages'),
		        page: $('.promo-email-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		         $.get($('.promo-email-body').data('url'), 'page='+num+'&userId='+$('.promo-email-pagination').data('user-id'), function(html){
		        	 $('.promo-email-body').html(html);
		        	 setupUi();
		         })
		    });
	}
	
	if( $('.promo-sms-pagination').length != 0){
	    // init bootpag
	    	$('.promo-sms-pagination').bootpag({
		        total: $('.promo-sms-pagination').data('total-pages'),
		        page: $('.promo-sms-pagination').data('current-page'),
                maxVisible: 10
		    }).on("page", function(event, /* page number here */ num){
		         $.get($('.promo-sms-body').data('url'), 'page='+num+'&userId='+$('.promo-sms-pagination').data('user-id'), function(html){
		        	 $('.promo-sms-body').html(html);
		        	 setupUi();
		         })
		    });
	}
	
	VendorSettings.setupOperatingHoursUI();
};



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
function stripeResponseHandlerSaveBilling(status, response) {

	  // Grab the form:
	  var $form = $('#billing-form');

	  if (response.error) { // Problem!

	    // Show the errors on the form:
	    Messages.showError(response.error.message);
	    //$form.find('.btn-register').prop('disabled', false); // Re-enable submission

	  } else { // Token created!

	    // Get the token ID:
	    var token = response.id;

	    // Insert the token into the form so it gets submitted to the server:
	    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    $('form#billing-form').submit();
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
		},
		saveItemAddOns : function(){
			$('#menu-item-add-ons-form .has-error').removeClass('has-error');
			$('#menu-item-add-ons-form input[type="text"]').each(function(){
				if($(this).val() == ''){
					$(this).parent().addClass('has-error');
				}
				if($(this).hasClass('price') && !$.isNumeric($(this).val())){
					$(this).parent().addClass('has-error');
				}
			});
			
			if($('#menu-item-add-ons-form .has-error').length == 0){
				$('#menu-item-add-ons-form').submit();
			}
		},
		openMenuDetails : function(menuId){
			$('.menu-details-'+menuId).toggle();
		},
		saveCategory : function(){
			$('#category-item-form .has-error').removeClass('has-error');
			$('#category-item-form input[type="text"]').each(function(){
				if($(this).val() == ''){
					$(this).parent().addClass('has-error');
				}
			});
			
			if($('#category-item-form .has-error').length == 0){
				$('#category-item-form').submit();
			}
		},
		updateCategorySort : function(sort){
			$.post('/menu/save-category-sort', 'sort='+sort, function(){
				
			})
		},
		updateMenuSort : function(sort){
			$.post('/menu/save-menu-sort', 'sort='+sort, function(){
				
			})
		},
		updateMenuAddOnSort : function (sort){
			$.post('/menu/save-menu-add-on-sort', 'sort='+sort, function(){
				
			})
		},
		setupUI : function(){
			
			$('.categories-main-panel').sortable( { update: function( event, ui ) {
				var sortNums = '';
				ui.item.parent().find('.categories-panel').each(function(index){
					if(sortNums != ''){
						sortNums +=',';
					}
					sortNums += $(this).data('category-id') + ':' + index;
				});
				if(sortNums != '')
					VendorMenu.updateCategorySort(sortNums);
			} });
			$('.categories-menu-panel').sortable(
					{ update: function( event, ui ) {
						var sortNums = '';
						ui.item.parent().find('.menu-panel').each(function(index){
							if(sortNums != ''){
								sortNums +=',';
							}
							sortNums += $(this).data('menu-id') + ':' + index;
						});
						if(sortNums != '')
							VendorMenu.updateMenuSort(sortNums);
					} });
			
			$('.add-ons-list').sortable(
					{ update: function( event, ui ) {
						var sortNums = '';
						ui.item.parent().find('.list-group-item').each(function(index){
							if(sortNums != ''){
								sortNums +=',';
							}
							sortNums += $(this).data('menu-item-add-on-id') + ':' + index;
						});
						
						if(sortNums != ''){
							VendorMenu.updateMenuAddOnSort(sortNums);
						}
							
					} });
		}
}
var VendorSettings = {
		validateSettings : function(){
			$('.vendor-settings-form .has-error').removeClass('has-error');
			$('.numeric').each(function(){
				if($.isNumeric($(this).val()) == false && $(this).prop('disabled') == false){
					$(this).parent().addClass('has-error');
				}
			})
			
			//we check all the time
			$('.operating-hours').each(function(){
				if($(this).find('.start').val() == '' && $(this).find('.end').val() != ''){
					$(this).find('.start').parent().addClass('has-error');
				}else if($(this).find('.start').val() != '' && $(this).find('.end').val() == ''){
					$(this).find('.end').parent().addClass('has-error');
				}
			})
			
			if($('.vendor-settings-form .has-error').length == 0){
				return true;
			}
			return false;
		},
		viewPromo : function(id){			
    		$.get('/promotion/view', 'id='+id, function(html){
        		$('#custom-modal .modal-title').html('View Promo');
        		$('#custom-modal .modal-body').html(html);
        		$('#custom-modal').modal('show');	
        	});
		},
		addOperatingHours : function(day){
			//$('.operating-hours[data-day='+day+']:eq(0)').clone().appendTo($('.operating-hour-list[data-day='+day+']'));
			
			var $elem = $('.operating-hours[data-day='+day+']:eq(0)').clone();
			$elem.find('select').val('');					
			$('.operating-hour-list[data-day='+day+']').append($elem);
			
			VendorSettings.setupOperatingHoursUI();
		},
		setupOperatingHoursUI : function(){
			$('.delete-operating-hours').off('click');
			$('.delete-operating-hours').on('click', function(){
				var day = $(this).data('day');
				if($('.operating-hours[data-day='+day+']').length == 1){
					var $elem = $('.operating-hours[data-day='+day+']').clone();
					$(this).parents('.operating-hours').remove();	
					$elem.find('select').val('');					
					$('.operating-hour-list[data-day='+day+']').append($elem);
				}
				else{
					$(this).parents('.operating-hours').remove();	
				}
				
			});
		}
}
var Customer = {
	viewOrder : function(orderId){
		$.get('/ordering/details', 'id='+orderId, function(html){
    		$('#custom-modal .modal-title').html('Order Details');
    		$('#custom-modal .modal-body').html(html);
    		$('#custom-modal').modal('show');	
    	});
	}
}

var Messages = {
		showError : function(message){
			alert(message);
		},
		showSuccess : function(message){
			alert(message);
		}
}

function listLinkActions(){
    $('.show-action').on('click',function(e){
        e.preventDefault();
        var $_this = $(this),
            options = {'html':true,'placement':'auto right',container: 'body'},
            content = $_this.next('.pop-content').html();

        $_this.data('content', content);
        $_this.popover(options).popover('show');

    });
    /* hide on widow resize not to have popover position issues */
    $(window).on('resize',  function() {
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