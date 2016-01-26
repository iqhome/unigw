var SensorList = []; // array for sensor nodes
var Chart; // chart
var UpdateTimeout = null;
// measured types, see IQRF.class.php for more types
var MeasureTypes = {
    'Temeperature': 'temp050',
    'RSSI': 'rssi',
    'Humidity': 'humidity050'
};
var SelectedMeasureType = 'temp050'; // actual selected measure type
var AutoUpdateInterval = 5; // automatic update interval in sec
var AutoUpdateIntervalMin = 5;

/* on page load build the network */
window.onload = function() {

    createNetwork();
}


/* create newtwork build network nodes from info, which contains the node map */

function createNetwork(info) {

    var network = document.getElementById("network");

    var request = {
        'action': 'getNodeMap'
    };

    ajaxJSON(request, function(response, e) {

        network.innerHTML = "";
        if (!response) {
            showError(e);
            createSetUpInstructions(network);
        } else {
            if (!response.nodemap) {
                //console.log(response);
                showError("Can't read node map!");
                return;
            }
            for (var i = 0; i < response.nodemap.length; i++) {
                var id = response.nodemap[i];
                var s = new Sensor(network, id);
                SensorList[id] = s;
            }
            google.charts.load('current', {
                packages: ['corechart', 'line']
            });
            google.charts.setOnLoadCallback(function() {
                Chart = drawChart(response.nodemap);
            });
            createControlPanel();
            updateValues();
        }
    });
}

/* create network nodes like DCTR modules */

function Sensor(parent, id) {

    /* creaet sensor body */
    var sensor = document.createElement("div");
    sensor.className = "sensor";
    sensor.id = id;
    parent.appendChild(sensor);
    /* create sensor ID */
    var nid = document.createElement("div");
    nid.innerHTML = "Node " + id;
    nid.className = "sensor-name";
    sensor.appendChild(nid);

    var sensorcont = document.createElement("div");
    sensorcont.className = "sensorcont";
    sensor.appendChild(sensorcont);

    /* create LEDs on sensors body */
    var sensorvalue = document.createElement("div");
    sensorvalue.innerHTML = "-";
    sensorvalue.className = "sensor-value";
    sensorcont.appendChild(sensorvalue);

    var sensorunit = document.createElement("div");
    sensorunit.innerHTML = "";
    sensorunit.className = "sensor-unit";
    sensorcont.appendChild(sensorunit);

    this.setValue = function(value) {
        sensorvalue.innerHTML = value;
        return this;
    }
    this.setUnit = function(unit) {
        sensorunit.innerHTML = unit;
        return this;
    }
    return this;
}


function drawChart(nodes) {

    this.nodes = nodes;
    this.data = null;
    this.chart = null;
    this.options = null;

    this.build = function() {
        var cnt = 0;
        this.data = new google.visualization.DataTable();
        this.data.addColumn('datetime', 'X');
        for (var i = 0; i < this.nodes.length; i++) {
            this.data.addColumn('number', "Node " + this.nodes[i]);
        };

        this.options = {
            hAxis: {
                title: 'Time'
            },
            vAxis: {
                title: 'Temperature'
            }
        };

        this.chart = new google.visualization.LineChart(document.getElementById('tempchart'));
        this.chart.draw(this.data, options);
        return this;
    }

    this.update = function(values) {

        values.unshift(new Date()); // insert cnt value as first element
        this.data.addRow(values);
        this.chart.draw(this.data, this.options);
        return this;
    }
    this.clear = function() {
        this.build();
        return this;
    }


    this.build();

    return this;
}

function updateValues() {

    var request = {
        'action': 'getFRC',
        'type': SelectedMeasureType
    };
    ajaxJSON(request, function(response, e) {
        if (!response) {
            showError(e);
        } else {
            refreshLastUpdate();
            var chartdata = [];

            for (i in SensorList) {
                var value = response.data[i];
                if (value == 0) {
                    value = "-"; // no response
                    unit = "";
                    chartvalue = null;
                } else {
                    switch (request.type) {
                        case 'temp050':
                            /* value = (Temperature[°C] * 2) + 64 ( 0.5 °C resolution ) */
                            chartvalue = value = (value - 64) / 2;
                            unit = '°C';
                            break;
                        case 'humidity050':
                            /* value = (Humidity[%] * 2) + 1 ( 0.5 % resolution ) */
                            chartvalue = value = (value - 1) / 2;
                            unit = "%rH";
                            break;
                        case 'rssi':
                        default:
                            unit = "";
                            chartvalue = value;
                            break;
                    }
                }
                //console.log(unit)
                //console.log(value)
                //console.log(chartvalue)
                SensorList[i].setValue(value).setUnit(unit);
                chartdata.push(chartvalue);
            };
            Chart.update(chartdata);
        }
    });

    UpdateTimeout = setTimeout(function() {
        updateValues();
    }, AutoUpdateInterval * 1000);

}


function createControlPanel() {

    var contorlcont = document.createElement("div");
    contorlcont.id = 'control';
    document.body.appendChild(contorlcont);

    var head = document.createElement("div");
    head.innerHTML = "Control Panel";
    head.className = 'infohead';
    contorlcont.appendChild(head);

    var table = document.createElement('table');
    contorlcont.appendChild(table);

    // interval value
    var r = document.createElement('tr');
    table.appendChild(r);
    var c = document.createElement('td');
    c.innerHTML = 'Update interval [sec]';
    r.appendChild(c);
    var c = document.createElement('td');
    r.appendChild(c);
    var interval = document.createElement('input');
    interval.type = 'number';
    interval.min = AutoUpdateIntervalMin;
    interval.id = 'interval';
    interval.value = AutoUpdateInterval;
    c.appendChild(interval);

    var btn = document.createElement('button');
    btn.className = "start-button";
    btn.innerHTML = "Set";
    btn.onclick = function() {
        var val = interval.value >= AutoUpdateIntervalMin ? interval.value : AutoUpdateIntervalMin;
        AutoUpdateInterval = interval.value;
    }
    c.appendChild(btn);

    // select measured type
    var r = document.createElement('tr');
    table.appendChild(r);
    var c = document.createElement('td');
    c.innerHTML = 'Measured type';
    r.appendChild(c);
    var c = document.createElement('td');
    r.appendChild(c);
    var mtype = document.createElement('select');
    mtype.id = 'mtype';
    c.appendChild(mtype);

    mtype.onchange = function() {
        Chart.clear();
        SelectedMeasureType = this.value;
    }

    for (var key in MeasureTypes) {
        var opt = document.createElement('option');
        opt.value = MeasureTypes[key];
        if (opt.value == SelectedMeasureType) {
            opt.selected = true;
        }
        opt.innerHTML = key;

        mtype.appendChild(opt);
    }

    // start/stop automatic update
    var r = document.createElement('tr');
    table.appendChild(r);
    var c = document.createElement('td');
    c.innerHTML = 'Automatic update';
    r.appendChild(c);
    var c = document.createElement('td');
    r.appendChild(c);

    var btn = document.createElement('button');
    btn.className = "start-button";
    btn.innerHTML = "Running";
    btn.onclick = function() {
        if (StartStopUpdate()) {
            btn.innerHTML = "Running";
        } else {
            btn.innerHTML = "Stopped";
        }
    }
    c.appendChild(btn);

    // last update
    var r = document.createElement('tr');
    table.appendChild(r);
    var c = document.createElement('td');
    c.innerHTML = 'Last update';
    r.appendChild(c);
    var c = document.createElement('td');
    r.appendChild(c);
    var lastupdate = document.createElement('span');
    lastupdate.id = 'lastupdate';
    c.appendChild(lastupdate);
}

function StartStopUpdate() {
    if (!UpdateTimeout) {
        updateValues();
        return true;
    } else {
        clearTimeout(UpdateTimeout);
        UpdateTimeout = null;
        return false;
    }
}

function refreshLastUpdate() {
    var l = document.getElementById('lastupdate');
    if (l) l.innerHTML = new Date().toISOString().slice(0, 19).replace('T', ' ');
}