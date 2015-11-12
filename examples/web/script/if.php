<?php

include_once '../../php/IQRF.Class.php';

$ip = "127.0.0.1";
$port = 5000;



$dc = json_decode($_POST['dc']);

if($dc === NULL){
    echo "INVALIDDATA";
    exit();
}


$iqrf = new IQRF();


if(!$iqrf->connect($ip, $port)){
    echo "CONNECTFAIL";
    exit();
}

if(($e = $iqrf->setLED($dc->node, $dc->color, $dc->state))){
    echo "errorCode: ".$e;
    exit();
}


$response = array('led' => true );

echo json_encode($response);
?>
