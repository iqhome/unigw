<?php

include_once '../../php/IQRF.Class.php';

$ip = "127.0.0.1";
$port = 5000;

$iqrf = new IQRF();


if(!$iqrf->connect($ip, $port)){
    echo "FAIL";
    exit();
}

$map = $iqrf->getNodeMap();

if($map){
    for ($i=0; $i < count($map); $i++) {
        if($map[$i] == 0) continue;
        for ($j=0; $j < 8; $j++) {
            if($map[$i] & (1 << $j)){
                $nodes[] = $j + $i*8 ;
            }
        }
    }
}

if(isset($nodes)){

    $response = array('nodes' => $nodes );

    echo json_encode($response);
}
else{
    echo 'FAIL';
}

exit();

?>
