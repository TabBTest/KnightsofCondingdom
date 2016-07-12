var FoodApp = {
		jquery : false,
		id : false,
		setupWidget : function(id, footerText){			
			FoodApp.id =  id;
			/*
			injectFiles([FoodApp.getScriptContext() + "/web/plugins/feedback/css/lead.custom.popup.css", 
			             FoodApp.getScriptContext() + "/web/plugins/feedback/js/lead.custom.popup.js"], function() {
			     // do some other stuff here
				
					fm_options = {
						show_form : false,
						title_label: ' ',
						trigger_label: "&nbsp;&nbsp;&nbsp;Request an Estimate&nbsp;&nbsp;",
						position : "right-top",
						iframe_url : FoodApp.getScriptContext() + "/pros/lead-generator?id="+FoodApp.id,
						footer_text: footerText
					};

					//init feedback_me plugin
					fm.init(fm_options);
					//FoodApp.jquery('.feedback_trigger').trigger('click');
			});
			*/
			//alert(FoodAppWidget.subdomain);
			/*
			var iframe = document.createElement('iframe');
			var html = '<body>Foo</body>';
			iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(html);
			document.body.appendChild(iframe);
			*/
			injectFiles([FoodApp.getScriptContext() + "/widget/widget.css"], function() {

			});
			
			FoodApp.jquery('.restalutions-widget-button').on('click', function(){
				//FoodApp.jquery('.modal-widget').show();
				FoodApp.jquery('.close-widget, .close-modal').off('click');
				FoodApp.jquery('.close-widget, .close-modal').on('click', function(){
					FoodApp.jquery('.modal-widget').hide();
					FoodApp.jquery('.restalutions-widget-button').html('Show Menu');
				});
				FoodApp.jquery('.modal-widget').toggle();
				if(FoodApp.jquery('.modal-widget').is(':visible')){
					FoodApp.jquery('.restalutions-widget-button').html('Hide Menu');
				}else{
					FoodApp.jquery('.restalutions-widget-button').html('Show Menu');
				}
			});
			var subdomain = FoodApp.jquery('.restalutions-widget').data('subdomain');
			FoodApp.jquery('.restalutions-widget').html('<iframe style="border: none" id="foodapp-iframe" height="100%" width="100%" name="myFrame" src="http://'+subdomain+'/ordering/menu">');
			
			
			
		},
		getScriptContext : function(){
			var srcUrl = document.getElementById("foodapp-js").src;
			var contextPath = srcUrl.substr(0,
					srcUrl.indexOf("/", srcUrl.indexOf("//") + 2));
			return (contextPath);
		}
}
var injectFiles = function(files, callback) {
  var filesLoaded = 0;
  var parent = document.querySelector("body") || document.querySelector("head");
  var onFileLoaded = function() {
    if(++filesLoaded == files.length) {
      callback();
    }
  }
  for(var i=0; i  < files.length ; i++){
	var file = files[i];
    var parts = file.split(".");
    var ext = parts[parts.length-1].toLowerCase();
    switch(ext) {
      case "js":
        var script = document.createElement('script');
        script.setAttribute("type", "text/javascript");
        script.onload = function() {
          onFileLoaded();
        }
        parent.appendChild(script);
        script.setAttribute("src", file);
      break;
      case "css":
        var css = document.createElement('link');
        css.setAttribute("rel", "stylesheet");
        css.setAttribute("type", "text/css");
        css.onload = function() {
          onFileLoaded();
        }
        parent.appendChild(css);
        css.setAttribute("href", file);
      break;
    }
  }
}

var parent = document.querySelector("body") || document.querySelector("head");
var s = document.createElement("script");
s.src = FoodApp.getScriptContext() + "/js/jquery.js"     // insert your own jQuery link
s.onload = function() {
	FoodApp.jquery = jQuery.noConflict(true);
	FoodApp.setupWidget();
}

parent.appendChild(s);

