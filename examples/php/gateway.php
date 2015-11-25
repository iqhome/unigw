<?php

echo "\nThis example show how to communicate with the\nIQRF DPA compatible Universal Gateway Daemon!\n\n";

include_once 'IQRF.Class.php';


$iqrf = new IQRF();

if(!$iqrf->connect()){
    echo "Can't create socket!";
    exit();
}

if(($response = $iqrf->getModuleInfo()) !== false){
    echo "Module Info:\n";
    print_r($response);
    echo"\n";
}
else{
    echo "Can't get Module info!";
    exit();
}

if(($response = $iqrf->getAddressingInfo()) !== false){
    echo "Number of nodes:\n";
    print_r($response);
    echo"\n";
}
else{
    echo "Can't get addressing info!";
    exit();
}
?>
