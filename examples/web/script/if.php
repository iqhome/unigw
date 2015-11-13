<?php

/* use the examples/php source */
include_once '../../php/IQRF.Class.php';


/* local address for unid */
$ip = "127.0.0.1";
$port = 5000;

/* parse JSON */
$dc = json_decode($_POST['dc']);

if($dc === NULL){
    echo "Invalid JSON data!";
    exit();
}

/* create new IQRF object */
$iqrf = new IQRF();

/* connect to unid network interface */
if(!$iqrf->connect($ip, $port)){
    echo "Can't connect to damon!";
    exit();
}

/* send set LED request */
if(($e = $iqrf->setLED($dc->node, $dc->color, $dc->state))){
    echo "Can't set LED!    errorCode: ".$e;
    exit();
}

$response = array('led' => true );

echo json_encode($response);
?>
