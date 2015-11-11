<?php

include_once '../../php/IQRF.Class.php';

$ip = "127.0.0.1";
$port = 5000;

$iqrf = new IQRF();


if(!$iqrf->connect($ip, $port)){
    echo "FAIL";
    exit();
}

$nodemap = $iqrf->getNodeMap();


if(isset($nodemap)){

    $response = array('nodes' => $nodemap );

    echo json_encode($response);
}
else{
    echo 'FAIL';
}

exit();

?>
