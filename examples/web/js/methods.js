
var AjaxTraceEnable = false;
var DebugEnable = true;

var gwio = "php/io.php";

function ajax(script, request, callback, arg){

	var r = new XMLHttpRequest();
	r.onreadystatechange = function () {
		if (r.readyState != 4 || r.status != 200){
		 	return;
		}
		if(AjaxTraceEnable)
			console.log(r.responseText);
		if(r.responseText != ''){
            if(callback)
            	callback(r.responseText, arg);
		}
	};
	r.open("POST", script , true);
	r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//r.timeout = 2000;
	//r.ontimeout = function () { console.log("Request timeout occured!"); }
	r.send(request);
}

function ajaxJSON (request , callback, arg) {

	var json = JSON.stringify(request);
	if(!json) return false;

    ajax(gwio, "request="+json, function(d) {

        var json = false;
        try{
            json = JSON.parse(d);
        }
        catch(e){
			if(DebugEnable)
				console.log(e);
        	callback(false, d);
        	return;
        }
        if(callback)
        	callback(json, arg);
    }, arg);
}


function toggleLED(id, led){

	var state = led.classList.contains("ledoff") ? 1 : 0;

	var request = {
		'action' : 'setLED',
		'node' 	: id,
		'color' : led.name,
		'state' : state
	};

	ajaxJSON(request, function(response, e){
		if(!response){
			showError(e);
		}
		else{
			if(response.led === true){
				if(state == 1){
					led.classList.remove("ledoff");
				}
				else{
					led.classList.add("ledoff");
				}
			}
		}
	});
}
