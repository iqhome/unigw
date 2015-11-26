<?php

/**
 * @brief  The IQRF Class for Universal Gateway daemon UDP interface
 *
 * @author     Gergely Sebestyen <sebestyen.gerely@iqhome.org>
 * @license    http://opensource.org/licenses/MIT
 * @version    Release: 1.0
 */

class IQRF{



    private $server = NULL;
    private $port = 0;
    private $sock;

    private $timeout = array('sec'=>2,'usec'=>0); // UDP socket timeout, depends on IQRF network communication timing

    public $errorcode = 0; // error code for last error
    public $errormsg = NULL; // error text message for las error

    /*
    * Constants for IQRF DPA commands
    * for details see IQRF DPA Framework Technical Guide
    */
    private $NADDR = array(
        'COORDINATOR' => 0,
        'BROADCAST'   => 0xFF
    );

    private $HWPID = array(
        'DEFAULT' => 0,
        'ALL' => 0xFFFF
    );

    private $PNUM = array(
        'PNUM_COORDINATOR'    => 0x00,
        'PNUM_NODE'           => 0x01,
        'PNUM_OS'             => 0x02,
        'PNUM_EEPROM'         => 0x03,
        'PNUM_EEEPROM'        => 0x04,
        'PNUM_RAM'            => 0x05,
        'PNUM_LEDR'           => 0x06,
        'PNUM_LEDG'           => 0x07,
        'PNUM_SPI'            => 0x08,
        'PNUM_IO'             => 0x09,
        'PNUM_THERMOMETER'    => 0x0A,
        'PNUM_PWM'            => 0x0B,
        'PNUM_UART'           => 0x0C,
        'PNUM_FRC'            => 0x0D,
        'PNUM_USER'           => 0x20,
        'PNUM_ERROR_FLAG'     => 0xFE
    );

    private $RESPONSE_CODE = array(
        'STATUS_NO_ERROR' =>   0, // No error
        'ERROR_FAIL' =>    1, // General fail
        'ERROR_PCMD' =>    2, // Incorrect PCMD
        'ERROR_PNUM' =>    3, // Incorrect PNUM or PCMD
        'ERROR_ADDR' =>    4, // Incorrect Address
        'ERROR_DATA_LEN' =>    5, // Incorrect Data length
        'ERROR_DATA' =>    6, // Incorrect Data
        'ERROR_HWPROFILE' =>   7, // Incorrect HW Profile ID used
        'ERROR_NADR' =>    8, // Incorrect NADR
        'ERROR_IFACE_CUSTOM_HANDLER' =>    9, // Data from interface consumed by Custom DPA Handler
        'ERROR_MISSING_CUSTOM_DPA_HANDLER' => 10, // Custom DPA Handler is missing
        'ERROR_USER_FROM' => 0x80, // Beginning of the user code error interval
        'ERROR_USER_TO' => 0xFE, // End of the user error code interval
        'STATUS_CONFIRMATION' => 0xFF // Error code used to mark confirmation
    );

    private $PCMD = array(
        'CMD_COORDINATOR_ADDR_INFO' =>  0,
        'CMD_COORDINATOR_DISCOVERED_DEVICES' =>  1,
        'CMD_COORDINATOR_BONDED_DEVICES' =>  2,
        'CMD_COORDINATOR_CLEAR_ALL_BONDS' =>  3,
        'CMD_COORDINATOR_BOND_NODE' =>  4,
        'CMD_COORDINATOR_REMOVE_BOND' =>  5,
        'CMD_COORDINATOR_REBOND_NODE' =>  6,
        'CMD_COORDINATOR_DISCOVERY' =>  7,
        'CMD_COORDINATOR_SET_DPAPARAMS' =>  8,
        'CMD_COORDINATOR_SET_HOPS' =>  9,
        'CMD_COORDINATOR_DISCOVERY_DATA ' => 10,
        'CMD_COORDINATOR_BACKUP ' => 11,
        'CMD_COORDINATOR_RESTORE ' => 12,
        'CMD_COORDINATOR_READ_REMOTELY_BONDED_MID ' => 15,
        'CMD_COORDINATOR_CLEAR_REMOTELY_BONDED_MID ' => 16,
        'CMD_COORDINATOR_ENABLE_REMOTE_BONDING ' => 17,
        'CMD_NODE_READ' =>  0,
        'CMD_NODE_REMOVE_BOND' =>  1,
        'CMD_NODE_READ_REMOTELY_BONDED_MID' =>  2,
        'CMD_NODE_CLEAR_REMOTELY_BONDED_MID' =>  3,
        'CMD_NODE_ENABLE_REMOTE_BONDING' =>  4,
        'CMD_NODE_REMOVE_BOND_ADDRESS' =>  5,
        'CMD_NODE_BACKUP' =>  6,
        'CMD_NODE_RESTORE' =>  7,
        'CMD_OS_READ' =>  0,
        'CMD_OS_RESET' =>  1,
        'CMD_OS_READ_CFG' =>  2,
        'CMD_OS_RFPGM' =>  3,
        'CMD_OS_SLEEP' =>  4,
        'CMD_OS_BATCH' =>  5,
        'CMD_OS_SET_USEC' =>  6,
        'CMD_OS_SET_MID' =>  7,
        'CMD_OS_RESTART' =>  8,
        'CMD_OS_WRITE_CFG_BYTE' =>  9,
        'CMD_OS_WRITE_CFG ' => 15,
        'CMD_RAM_READ' =>  0,
        'CMD_RAM_WRITE' =>  1,
        'CMD_EEPROM_READ' =>  0,
        'CMD_EEPROM_WRITE' =>  1,
        'CMD_EEEPROM_READ' =>  0,
        'CMD_EEEPROM_WRITE' =>  1,
        'CMD_LED_SET_OFF' =>  0,
        'CMD_LED_SET_ON' =>  1,
        'CMD_LED_GET' =>  2,
        'CMD_LED_PULSE' =>  3,
        'CMD_SPI_WRITE_READ' =>  0,
        'CMD_IO_DIRECTION' =>  0,
        'CMD_IO_SET' =>  1,
        'CMD_IO_GET' => 2,
        'CMD_THERMOMETER_READ' =>  0,
        'CMD_PWM_SET' =>  0,
        'CMD_UART_OPEN' =>  0,
        'CMD_UART_CLOSE' =>  1,
        'CMD_UART_WRITE_READ' =>  2,
        'CMD_FRC_SEND' =>  0,
        'CMD_FRC_EXTRARESULT' =>  1,
        'CMD_FRC_SEND_SELECTIVE' =>  2,
        'CMD_FRC_SET_PARAMS' =>  3,
        'CMD_GET_PER_INFO' => 0x3f
    );

    private $PERIPHERAL = array(
        'PERIPHERAL_TYPE_DUMMY' => 0x00,
        'PERIPHERAL_TYPE_COORDINATOR' => 0x01,
        'PERIPHERAL_TYPE_NODE' => 0x02,
        'PERIPHERAL_TYPE_OS' => 0x03,
        'PERIPHERAL_TYPE_EEPROM' => 0x04,
        'PERIPHERAL_TYPE_BLOCK_EEPROM' => 0x05,
        'PERIPHERAL_TYPE_RAM' => 0x06,
        'PERIPHERAL_TYPE_LED' => 0x07,
        'PERIPHERAL_TYPE_SPI' => 0x08,
        'PERIPHERAL_TYPE_IO' => 0x09,
        'PERIPHERAL_TYPE_UART' => 0x0a,
        'PERIPHERAL_TYPE_THERMOMETER' => 0x0b,
        'PERIPHERAL_TYPE_ADC' => 0x0c,
        'PERIPHERAL_TYPE_PWM' => 0x0d,
        'PERIPHERAL_TYPE_FRC' => 0x0e,
        'PERIPHERAL_TYPE_USER_AREA' => 0x80
    );

    private $FRC = array(
        'FRC_USER_BIT_FROM' => 0x40,
        'FRC_USER_BIT_TO' => 0x7F,
        'FRC_USER_BYTE_FROM' => 0xC0,
        'FRC_USER_BYTE_TO' => 0xDF,
        'FRC_USER_2BYTE_FROM' => 0xF0,
        'FRC_USER_2BYTE_TO' => 0xFF
    );

    private $TR_SERIES = array(
        "TR-52D",      //0
        "TR-58D-RJ",    //1
        "TR-72D",       //2
        "TR-53D",       //3
        "",             //4
        "",             //5
        "",             //6
        "",             //7
        "TR-54D",       //8
        "TR-55D",       //9
        "TR-56D",       //10
        "TR-76D"        //11
    );

    private $TR_DC= array("","DC");

    private $MCU_TYPE= array(
        "",//0
        "",//1
        "",//2
        "PIC16F886",//3
        "PIC16LF1938"//4
    );
    private $OSPOSTFIX = array(
        "",//0
        "",//1
        "",//2
        "",//3
        "D"//4
    );

    function __construct(){
    }

    /**
    * @brief Initialize connection - create a UDP socket
    * @param ip - IP address of gateway - default: "127.0.0.1"
    * @param port - gateway interface port - default: 5000
    * @return true if initialization was successful, false otherwise
    */
    public function connect($ip = "127.0.0.1", $port = 5000){

        if(empty($ip) || empty($port)) return false;
        $this->server = $ip;
        $this->port = $port;
        if(($this->sock = socket_create(AF_INET, SOCK_DGRAM, 0)) === false){
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            return false;
        }
        socket_set_option($this->sock,SOL_SOCKET,SO_RCVTIMEO,$this->timeout);

        return true;
    }

    /**
    * @brief Send hex string message to Universal Gateway daemon UDP interface
    * @param message - IQRF DPA compatible hex string
    * @return true if sending of message was successful, false otherwise
    */
    private function send($message){

        if(!$this->sock) return false;
        if(empty($message) || $message == "") return false;
        if( !socket_sendto($this->sock, $message , strlen($message) , 0 , $this->server , $this->port))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            return false;
        }
        return true;
    }

    /**
    * @brief Receive hex string message to Universal Gateway daemon UDP interface
    * @param message - IQRF DPA compatible hex string
    * @return true if receive was successful, false otherwise socket or timeout error
    */
    private function receive(){
        if(!$this->sock) return false;
        if(socket_recv ( $this->sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
        {
           $errorcode = socket_last_error();
           $errormsg = socket_strerror($errorcode);
           return false;
        }
        return $reply;
    }

    /**
    * @brief Build a hex string request from IQRF DPA format array
    * @param request - IQRF DPA compatible array
    * @return IQRF DPA hex string if build was successful, false in case of some error
    */
    private function dpa_request($request = false){

        if($request === false){
            echo "nodata";
            return false;
        }
        /* test items */
        if(!is_numeric($request['NADDR']) || $request['NADDR'] < 0 || $request['NADDR'] > 0xFFFF ){ echo "invalid naddr";return false;}
        if(!is_numeric($request['PNUM'] ) || $request['PNUM']  < 0 || $request['PNUM']  > 0xFF ){ echo "invalid pnum";return false;}
        if(!is_numeric($request['PCMD'] ) || $request['PCMD']  < 0 || $request['PCMD']  > 0xFF ){ echo "invalid pcmd";return false;}
        if(!is_numeric($request['HWPID']) || $request['HWPID'] < 0 || $request['HWPID'] > 0xFFFF ){ echo "invalid hwpid";return false;}
        /* create NADDR string */
        $addr = self::zf($request['NADDR'], 4);
        $naddr = substr($addr,-2).substr($addr,0,2);
        /* create PNUM string */
        $pnum = self::zf($request['PNUM'], 2);
        /* create PCMD string */
        $pcmd = self::zf($request['PCMD'], 2);
        /* create HWPID string */
        $hwpid = self::zf($request['HWPID'], 4);
        /* if PDATA avilable create PDATA STRING */
        if(isset($request['PDATA']) && $request['PDATA'] !== false){
            for ($i=0; $i < count($request['PDATA']); $i++) {
                if($request['PDATA'][$i] < 0 || $request['PDATA'][$i] > 0xFF ) {
                    echo "invalid pdata item";
                    return false;
                }
                $pdata = self::zf($request['PDATA'][$i],2);
            }
        }
        else{
            $pdata = "";
        }
        /* create full command string */
        return $naddr.$pnum.$pcmd.$hwpid.$pdata;
    }

    /**
    * @brief Parse and build a IQRF DPA array response from hex string
    * @param response - received hex string from the Universal Gateway daemon interface
    * @return IQRF DPA hex string if build was successful, false in case of some error
    */
    private function dpa_response($response = false){
        if($response === false){
            return false;
        }
        /* validate response */
        if(strlen($response) < 8){ // minimum DPA response length
            return false;
        }
        /* convert response */
        $binarray = array_map("hexdec", str_split($response, 2));
        /* build DPA compatible response command */
        return array(
                'NADDR' => $binarray[0].$binarray[1],
                'PNUM'  => $binarray[2],
                'PCMD'  => $binarray[3],
                'HWPID' => $binarray[4].$binarray[5],
                'ErrN'  => $binarray[6],
                'DpaValue' => $binarray[7],
                'PDATA' => array_slice($binarray, 8)
            );
    }

    /**
    * @brief Zerofill hex string
    * @param value - target value e.g.: 5F
    * @param length - required length of value e.g.: 4
    * @return zero filled value e.g.: 005F
    */
    private function zf($value, $length){
        return str_pad(dechex($value), $length, 0, STR_PAD_LEFT);
    }

    /**
    * @brief get Module Info DPA request
    * @param
    * @return DPA response array or false in case of some error
    */
    public function getModuleInfo()
    {
        /* setup DPA command string*/
        $comstring = self::dpa_request(array(
                        'NADDR' => $this->NADDR['COORDINATOR'],
                        'PNUM'  => $this->PNUM['PNUM_OS'],
                        'PCMD'  => $this->PCMD['CMD_OS_READ'],
                        'HWPID' => $this->HWPID['ALL'],
                        'PDATA' => false
                    ));
        /* send DPA request */
        if(!self::send($comstring)){
            return false;
        }
        /* receive DPA response string from coordinator */
        $response =  self::receive();
        if($response !== false){
            /* process response and parse message to IQRF DPA format */
            $r = self::dpa_response($response);
            /* check response validity */
            if($r !== false){
                /* build Module Info array -> based on the IQRF OS reference guide */
                $moduleinfo = array(
                    'MID' => strtoupper(self::zf($r['PDATA'][3],2).self::zf($r['PDATA'][2],2).self::zf($r['PDATA'][1],2).self::zf($r['PDATA'][0],2)),
                    "OsVersion" => self::zf($r['PDATA'][4] >> 4 & 0x0F,1).".".self::zf($r['PDATA'][4] & 0x0F,2).$this->OSPOSTFIX[$r['PDATA'][5] & 0x07],
                    "OsBuild" => self::zf($r['PDATA'][6] | $r['PDATA'][7] << 8,4),
                    "McuType" => $this->MCU_TYPE[$r['PDATA'][5] & 0x07],
                    "TrSeries" => $this->TR_DC[$r['PDATA'][3] & 0x80?1:0].$this->TR_SERIES[$r['PDATA'][5] >> 4 & 0x0F],
                    "Rssi" => $r['PDATA'][8],
                    "SupplyVoltage" => $r['PDATA'][9],
                    "Flags" => $r['PDATA'][10]
                );
            return $moduleinfo;
            }
        }
        return false;
    }

    /**
    * @brief get Addressing Info DPA request
    * @param
    * @return DPA response array or false in case of some error
    */
    public function getAddressingInfo()
    {
        /* setup DPA command string*/
        $comstring = self::dpa_request(array(
                        'NADDR' => $this->NADDR['COORDINATOR'],
                        'PNUM'  => $this->PNUM['PNUM_COORDINATOR'],
                        'PCMD'  => $this->PCMD['CMD_COORDINATOR_ADDR_INFO'],
                        'HWPID' => $this->HWPID['ALL'],
                        'PDATA' => false
                    ));
        /* send DPA request */
        if(!self::send($comstring)){
            return false;
        }
        /* receive DPA response from coordinator */
        $response =  self::receive();
        if($response !== false){
            /* process response and parse message to IQRF DPA format */
            $r = self::dpa_response($response);
            if($r !== false){
                /* build Addresing Info -> based on the IQRF DPA Technical Guide */
                $addressinginfo = array(
                    'DevNr' => $r['PDATA'][0],
                    "DID" => $r['PDATA'][1]
                );
            return $addressinginfo;
            }
        }
        return false;
    }

    /**
    * @brief get Node Map
    * @param
    * @return Node Map array of node network addresses or false in case of some error
    */
    public function getNodeMap(){
        /* setup DPA command string*/
        $comstring = self::dpa_request(array(
                        'NADDR' => $this->NADDR['COORDINATOR'],
                        'PNUM'  => $this->PNUM['PNUM_COORDINATOR'],
                        'PCMD'  => $this->PCMD['CMD_COORDINATOR_BONDED_DEVICES'],
                        'HWPID' => $this->HWPID['ALL'],
                        'PDATA' => false
                    ));

        /* send DPA request */
        if(!self::send($comstring)){
            return false;
        }
        /* receive DPA response from coordinator */
        $response = self::receive();
        if($response){
            $r = self::dpa_response($response);
            $map = $r['PDATA'];
            if($map){
                for ($i=0; $i < count($map); $i++) {
                    if($map[$i] == 0) continue;
                    for ($j=0; $j < 8; $j++) {
                        if($map[$i] & (1 << $j)){
                            $nodemap[] = $j + $i*8 ;
                        }
                    }
                }
                return $nodemap;
            }
        }
        return false;
    }

    /**
    * @brief Set LEDs on IQRF network nodes
    * @param node - node network addess
    * @param color - r(red) or g(green) color of LED
    * @param state - 0 - set off, 1 - set on
    * @return
    */
    public function setLED($node, $color, $state)
    {
        $pnum = $color == 'r' ? $this->PNUM['PNUM_LEDR'] :( $color == 'g' ? $this->PNUM['PNUM_LEDG'] : false);
        $pcom = $state === 1 ? 1 ($state === 0 ? $state: false);
        if($node <1 || $node > 239){
            $this->errorcode = 1;
            $this->errormsg = "Invalid node address!";
            return false;
        }
        if($pnum === false) {
            $this->errorcode = 2;
            $this->errormsg = "Invalid color!";
            return false;
        }
        if($pcom === false){
            $this->errorcode = 3;
            $this->errormsg = "Invalid state!";
            return false;
        }
        $comstring = self::dpa_request(array(
                        'NADDR' => $node,
                        'PNUM'  => $pnum,
                        'PCMD'  => $pcom,
                        'HWPID' => $this->HWPID['ALL'],
                        'PDATA' => false
                    ));
        if(!$comstring){
            return false;
        }
        if(!self::send($comstring)){
            return false;
        }
        $status = self::receive();             // status confirmation from coordinator
        $noderesponse = self::receive();       // response from node, if node unaccessible -> socket timeout
        if($noderesponse){
            $response = self::dpa_response($noderesponse);
            if($response['ErrN'] == 0){
                return 0;
            }
            else{
                return 2;
            }
        }
        else{
            return 3;
        }
    }

    /* example method */

    /*
    public function method()
    {
        // First create a DPA request structure
        // The dpa_request method converts the request structure to a hex string appropriate format to DPA request
        // The specific segments should be selected from predefined arrays based on the DPA datasheet
        // PDATA should be a decimal (byte) array

        $comstring = self::dpa_request(array(
                        'NADDR' => $this->NADDR['xx'],   // set NADDR
                        'PNUM'  => $this->PNUM['xx'],    // set PNUM
                        'PCMD'  => $this->PCMD['xx'],    // set HWPID
                        'HWPID' => $this->HWPID['xx'],   // set HWPID
                        'PDATA' => false                 // set PDATA
                    ));

        // send DPA request hex string to the Universal Gateway Daemon
        if(!self::send($comstring)){
            return false;
        }
        // receive DPA response from daemon/coordinator and check error
        // if the requset address is not the coordinator
        // receive status response from coordinator and check error
        $status = self::receive();

        // check errors
        $s = self::dpa_response($response);
        if(!$s){
            return false;
        }
        if($s['ErrN'] == 0xFF){ // status confirmation
            $this->errorcode = $s['ErrN'];
            $this->errormsg = "";
            return false;
        }

        // if the requset address is not the coordinator
        // receive DPA response from network nodes and check error
        $response =  self::receive();
        if($response !== false){
            // parse DPA response hex string and create a DPA command like array structure
            $r = self::dpa_response($response);
            if($r !== false){
                // for the simplier usage create a specific data array from response PDATA based on the DPA datasheet
                $addressinginfo = array(
                    'DevNr' => $r['PDATA'][0],
                    "DID" => $r['PDATA'][1]
                );
            return $addressinginfo;
            }
        }
        return false;
    }
    */
}


?>
