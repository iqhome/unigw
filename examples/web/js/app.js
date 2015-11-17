

var DebugEnable = false;

/* on page load build the network */
window.onload = function() {

    createNetwork();
}

/* create newtwork build network nodes from info, which contains the node map */
function createNetwork(info){

    var network = document.getElementById("network");

    var request = {
        'action' : 'getNodeMap'
    };

    ajaxJSON(request, function(response, e){
		if(!response){
			showError(e);
            createSetUpInstructions(network);
		}
		else{
            createModuleInfo(response);
            for (var i = 0; i < response.nodemap.length; i++) {
                createNode(network, response.nodemap[i]);
            }
            /*
            for (var i = 0; i < 150; i++) {
                createNode(network, i+1);
            }
            */
		}
	});
}
/* create system info */
function createModuleInfo(info) {

    var infocont = document.createElement("div");
    infocont.id = 'info';
    document.body.appendChild(infocont);

    var head = document.createElement("div");
    head.innerHTML = "Gateway IQRF Module Info";
    head.className = 'infohead';
    infocont.appendChild(head);

    /* fetch module info into a table */
    var table = document.createElement('table');
    infocont.appendChild(table);

    var r = document.createElement('tr');
    table.appendChild(r);
    var c = document.createElement('td');
    c.innerHTML = 'Number Of Nodes';
    r.appendChild(c);
    var c = document.createElement('td');
    c.innerHTML = info.addressinfo.DevNr;
    r.appendChild(c);

    var moduleinfo = info.moduleinfo;
    for (key in moduleinfo) {
        if (moduleinfo.hasOwnProperty(key)) {
            if(key == 'Rssi'
            || key == 'SupplyVoltage'
            || key == 'Flags'
            ) continue;
            var r = document.createElement('tr');
            table.appendChild(r);
            var c = document.createElement('td');
            c.innerHTML = key;
            r.appendChild(c);
            var c = document.createElement('td');
            c.innerHTML = moduleinfo[key];
            r.appendChild(c);
        }
    }
}
/* create network nodes like DCTR modules */
function createNode(parent, id){

    /* creaet node body */
    var node = document.createElement("div");
    node.className = "node";
    node.id = id;
    parent.appendChild(node);
    /* create node ID */
    var nid = document.createElement("div");
    nid.innerHTML = id;
    nid.className = "nodeid";
    node.appendChild(nid);

    /* create LEDs on nodes body */
    var leds = document.createElement("div");
    leds.className = "leds";
    node.appendChild(leds);

    var ledg = document.createElement("button");
    ledg.innerHTML = "G";
    ledg.classList.add("led");
    ledg.classList.add("ledg");
    ledg.classList.add("ledoff");
    ledg.id = "ledg";
    ledg.name = "g";
    ledg.onclick = function(){toggleLED(id, this);};
    leds.appendChild(ledg);

    var ledr = document.createElement("button");
    ledr.innerHTML = "R";
    ledr.classList.add("led");
    ledr.classList.add("ledr");
    ledr.classList.add("ledoff");
    ledr.id = "ledr";
    ledr.name = "r";
    ledr.onclick = function(){toggleLED(id, this);};
    leds.appendChild(ledr);
}

function showError(errortext){

    var win = document.createElement('div');
    win.className = 'error-window';
    document.body.appendChild(win);

    var etext = document.createElement('div');
    etext.className = 'error-text';
    etext.innerHTML = errortext;
    win.appendChild(etext);

    setTimeout(function(){
        win.parentNode.removeChild(win);
    },3000);

}

/* setup instructions if network is empty */
function createSetUpInstructions(parent) {

    var text = document.createElement('div');
    text.innerHTML = "Network nodes not found. Use IQRF IDE to set up the network.";
    parent.appendChild(text);
    var link = document.createElement('a');
    link.href = "https://www.youtube.com/watch?v=ZSlw_qBQu4E";
    link.target="_blank";
    link.innerHTML = "You can find a tutorial here."
    parent.appendChild(link);
}
