<?php

include_once 'IQRF.Class.php';

$ip = "127.0.0.1";
$port = 5000;

$iqrf = new IQRF();

if(!$iqrf->connect($ip, $port)){
    echo "Can't connect!";
    exit();
}

$response = $iqrf->getModuleInfo();
if($response)
    echo "Module Info: ".$response."\n";

$response = $iqrf->getNodeNum();
if($response)
    echo "Node num:    ".$response."\n";

?>
