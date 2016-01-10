<?php

include_once '../../php/IQRF.Class.php';

if(!class_exists('IQRF')){
	echo "IQRF class doesn't exists!\n";
	exit();
}

/* decode JSON */
$request = json_decode($_POST['request']);

if($request === NULL){
    echo "Invalid JSON data!";
    exit();
}

/* create new IQRF object */
$iqrf = new IQRF();

/* connect to unid network interface */
if(!$iqrf->connect()){
    echo "Can't conenct to deamon!";
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
            $response = false;
            $errormsg = $iqrf->errormsg;
        }
        else{
            $response = json_encode(array('led' => true ));
        }
        break;

    case 'getFRC':
        $FRCdata = $iqrf->getFRC($request->type);
        if($FRCdata === false){
            $response = false;
            $errormsg = $iqrf->errormsg ? $iqrf->errormsg : "getFRC error!";
            break;
        }
        else{
            $response = json_encode(array(
                'data' => $FRCdata 
            ));
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
