<?php

echo "\nThis example show how to communicate with the\nIQRF DPA compatible Universal Gateway Daemon!\n\n";

include_once 'IQRF.Class.php';


$iqrf = new IQRF();

if(!$iqrf->connect()){
    echo "Can't create socket!\n";
    exit();
}

if(($response = $iqrf->getModuleInfo()) !== false){
    echo "Module Info:\n";
    print_r($response);
    echo"\n";
}
else{
    echo "Can't get Module info!\n";
    exit();
}

if(($response = $iqrf->getAddressingInfo()) !== false){
    echo "Number of nodes:\n";
    print_r($response);
    echo"\n";
}
else{
    echo "Can't get addressing info!\n";
    exit();
}
// test RTCC
echo "RTCC set: ";
if(($response = $iqrf->setRTCCTimeBCD()) !== false){
    echo "OK";
    echo"\n";
}
else{
    echo "Failed: Can't set RTCC!\n";
    exit();
}
echo "Get time from RTCC: ";
if(($response = $iqrf->getRTCCTime()) !== false){
    echo $response;
    echo"\n";
}
else{
    echo "Failed: Can't read data from RTCC!\n";
    exit();
}

// test EEPROM
$teststr = "We love IQRF!";
$testdata = unpack('C*',$teststr);

echo "Write data to EEPROM: ";
if(($response = $iqrf->writeEEPROM(0, $testdata)) !== false){
    echo "OK";
    echo"\n";
}
else{
    echo "Failed: Can't write EEPROM!\n";
    exit();
}

echo "Read data from EEPROM: ";
if(($response = $iqrf->readEEPROM(0, count($testdata))) !== false){
    echo implode(array_map("chr", $response));;
    echo"\n";
    echo "Clean up EEPROM test! ";
    $cleanup = array_pad(array(),count($testdata), 0);
    if($iqrf->writeEEPROM(0, $cleanup) !== false){
        echo "OK!";
    }
    else{
        echo "Failed!";
    }
    echo"\n";
}
else{
    echo "Failed: Can't read EEPROM!\n";
    exit();
}
?>
