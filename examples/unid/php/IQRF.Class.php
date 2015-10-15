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

    public function discovey()
    {
        $com_getNodeNum = "00000700FFFF0700";//array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
        self::send($com_getNodeNum);
        return self::recv();
    }
}


?>
