(function($){
	$(document).ready(function(){
		var url = $('[data-name=attendance-url]').data('value');

		function addEvent(obj, name, func){
	        if (obj.attachEvent) {
	            obj.attachEvent("on" + name, func);
	        } else {
	            obj.addEventListener(name, func, false);
	        }
	    }
	    function pluginLoaded() {
	        window.webcard = document.getElementById("webcard");
	        addEvent(webcard, "cardpresent", initCard);
	    }

	    function initCard(reader) {
	        reader.connect(2); // 1-Exclusive, 2-Shared
	        var apdu = "FFCA000000",
	         	resp = reader.transcieve(apdu);
	        if (resp.substr(-4) == "9000") {
	        	var uid =  resp.substr(0, resp.length - 4);
	        	log(uid);
	        }
	        reader.disconnect();
	    }

	    function log(uid){
	    	console.log('Logging: '+uid);
	    	var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	    	$.post(url, {uid:uid, timestamp:currentTime }).done(function(response){
	    		if(response.result){
	    			$.each(response.data, function(i,v){
	    				$('#'+i).text(v);
	    			});
	    		}
	    	}).fail(function(){

	    	});
	    }

	    pluginLoaded();
	});
})(jQuery);