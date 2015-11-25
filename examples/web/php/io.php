<?php

include_once '../../php/IQRF.Class.php';
/*
echo 'FAIL';
exit();
*/

$request = json_decode($_POST['request']);

if($request === NULL){
    echo "Invalid JSON data!";
    exit();
}

/* create new IQRF object */
$iqrf = new IQRF();

/* connect to unid network interface */
if(!$iqrf->connect()){
    echo "FAIL";
    exit();
}
/* default error message */
$errormsg = "FAIL";

switch ($request->action) {
    /* get node map */
    case 'getNodeMap':
        $nodemap = $iqrf->getNodeMap();
        if($nodemap === false){
            $response = false;
            break;
        }
        $addressinfo = $iqrf->getAddressingInfo();
        if($addressinfo === false){
            $response = false;
            break;
        }
        $moduleinfo = $iqrf->getModuleInfo();
        if($moduleinfo === false){
            $response = false;
        }
        else{
            $response = json_encode(array(
                'nodemap' => $nodemap ,
                'addressinfo' => $addressinfo,
                'moduleinfo' => $moduleinfo
            ));
        }
        break;

    case 'setLED':
        /* send set LED request */
        if(($e = $iqrf->setLED($request->node, $request->color, $request->state))){
            //echo "Can't set LED!    errorCode: ".$e;
            $response = false;
            $errormsg = "Node unreachable!";
        }
        else{
            $response = json_encode(array('led' => true ));
        }
        break;

    /* example */
    /*
    case 'action':
        $response = $iqrf -> method(arguments);
        break;
    */
    default:
        // invalid action
        # code...
        break;
}

if(isset($response) && $response !== false){
    echo $response;
}
else{
    echo $errormsg;
}

exit();

?>
