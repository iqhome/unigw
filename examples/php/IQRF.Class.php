<?php

/**
 *
 */
class IQRF
{

    public $errorcode = 0;
    public $errormsg = NULL;

    private $server = NULL;
    private $port = 0;
    private $sock;

    private $timeout = array('sec'=>2,'usec'=>0);

    function __construct()
    {

    }

    public function connect($ip, $port)
    {

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

    private function send($input)
    {
        if(!$this->sock) return false;
        if(empty($input)) return false;
        if( !socket_sendto($this->sock, $input , strlen($input) , 0 , $this->server , $this->port))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            return false;
        }
        return true;
    }

    private function recv(){
        if(!$this->sock) return false;
        if(socket_recv ( $this->sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
        {
           $errorcode = socket_last_error();
           $errormsg = socket_strerror($errorcode);
           return false;
        }
        return $reply;
    }
    /**/
    public function getModuleInfo()
    {
        $com_getModuleInfo = "00000200FFFF";//array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
        self::send($com_getModuleInfo);
        return self::recv();
    }
    public function getNodeNum()
    {
        $com_getNodeNum = "00000000FFFF";//array( 0x00, 0x00, 0x00, 0x00, 0xFF, 0xFF );
        self::send($com_getNodeNum);
        return self::recv();
    }

    public function getNodeMap(){
        $com_getModuleInfo = "00000002FFFF";//array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
        self::send($com_getModuleInfo);
        $resp = self::recv();
        if($resp){
            return str_split(substr($resp, 16),2);
        }
        return false;
    }

    public function setLED($node, $color, $state)
    {
        $pnum = $color == 'r' ? '06' :( $color == 'g' ? '07' : false);
        $pcom = $state === 1 || $state === 0 ? str_pad($state,2,0,STR_PAD_LEFT): false;
        if($pnum === false || $pcom === false) return false;
        $addr = str_pad(dechex($node), 4, 0, STR_PAD_LEFT);
        $naddr = substr($addr,-2).substr($addr,0,2);
        $com = $naddr.$pnum.$pcom."FFFF";
        //echo $com;
        self::send($com);
        $status = self::recv();
        //echo $status."\n";
        $response = self::recv();
        //echo $response."\n";
        if($response)
            return true;
        else
            return false;
    }

    public function discovery()
    {
        $com_getNodeNum = "00000700FFFF0700";//array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
        self::send($com_getNodeNum);
        return self::recv();
    }
}


?>
