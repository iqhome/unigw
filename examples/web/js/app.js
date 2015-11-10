
var script_network = "script/ntw.php";

window.onload = function() {

    ajaxJSON(script_network, "", function(info){
        createNetwork(info);
    });
}

function createNetwork(info){
    var network = document.getElementById("network");
    for (var i = 0; i < info.nodes.length; i++) {
        createNode(network, info.nodes[i]);
    }
}
function createNode(parent, id){
    var node = document.createElement("div");
    node.className = "node";
    node.id = id;
    parent.appendChild(node);

    var nid = document.createElement("div");
    nid.innerHTML = id;
    nid.className = "nodeid";
    node.appendChild(nid);

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
