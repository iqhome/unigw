<?php

include_once '../../php/IQRF.Class.php';
/*
echo 'FAIL';
exit();
*/
/* local address for unid */
$ip = "127.0.0.1";
$port = 5000;

/* create new IQRF object */
$iqrf = new IQRF();

/* connect to unid network interface */
if(!$iqrf->connect($ip, $port)){
    echo "FAIL";
    exit();
}
/* get node map */
$nodemap = $iqrf->getNodeMap();

/* if no error send back the node map in JSON format */
if(isset($nodemap)){

    $response = array('nodes' => $nodemap );

    echo json_encode($response);
}
else{
    echo 'FAIL';
}

exit();

?>
