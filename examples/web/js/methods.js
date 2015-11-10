
var script_interface = "script/if.php";

function ajax(script, query, callback, arg){

	var r = new XMLHttpRequest();
	r.onreadystatechange = function () {
		if (r.readyState != 4 || r.status != 200){
		 	return;
		}
		console.log(r.responseText);
		if(r.responseText != ''){
            try {
            	callback(r.responseText, arg);
            } catch (e) {
	            console.log(e);

            }
		}
	};
	r.open("POST", script , true);
	r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//r.timeout = 2000;
	//r.ontimeout = function () { alert("Request timeout out!!!"); }
	r.send(query);
}

function ajaxJSON (script , query , callback, arg) {

    ajax(script, query, function(d) {

        var json = false;
        try{
            json = JSON.parse(d);
        	callback(json, arg);
        }
        catch(e){
            //console.log(e);
        	callback(false, arg);
        }
    }, arg);
}


function toggleLED(id, led){

	var state = led.classList.contains("ledoff") ? 1 : 0;

	var dc = JSON.stringify({
		'node' 	: id,
		'color' : led.name,
		'state' : state
	});

	//console.log(dc)

	ajaxJSON(script_interface, "dc="+dc, function(r){
		if(r.led === true){
			if(state == 1){
				led.classList.remove("ledoff");
			}
			else{
				led.classList.add("ledoff");
			}
		}
	});
}
