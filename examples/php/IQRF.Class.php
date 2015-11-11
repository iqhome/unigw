<?php


include_once 'dpa.php';

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

            $map = str_split(substr($resp, 16),2);
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

    public function setLED($node, $color, $state)
    {
        $pnum = $color == 'r' ? $_PNUM['PNUM_LEDG'] :( $color == 'g' ? $_PNUM['PNUM_LEDG'] : false);
        $pcom = $state === 1 || $state === 0 ? $state: false;
        if($pnum === false || $pcom === false) return false;

        $comstring = self::create_dpa_request(array(
                'NADDR' => $node,
                'PNUM'  => $pnum,
                'PCMD'  => $pcom,
                'HWPID' => $_HWPID['ALL'],
                'PDATA' => false
        ));

        if($comstring === false){
            echo "some error with command";
            return 2;
        }

        //echo $com;
        self::send($com);
        $status = self::recv();
        //echo $status."\n";
        $response = self::recv();
        //echo $response."\n";
        if($response)
            return 0;
        else
            return 1;
    }

    private function create_dpa_request($request = false){

        if($request === false)
            return false;
        if(!isset($request['NADDR'])
        || !isset($request['PNUM'])
        || !isset($request['PCMD'])
        || !isset($request['HWPID'])){
            return false;
        }
        /* create NADDR string */
        /* test items */
        if($request['NADDR'] < 0    || $request['NADDR'] > 0xFFFF ){ return false;}
        if($request['PNUM']  < 0    || $request['PNUM']  > 0xFF ){ return false;}
        if($request['PCMD']  < 0    || $request['PCMD']  > 0xFF ){ return false;}
        if($request['HWPID'] < 0    || $request['HWPID'] > 0xFFFF ){ return false;}

        $addr = self::zerofill($request['NADDR']), 4);
        $naddr = substr($addr,-2).substr($addr,0,2);

        /* create HWPID string */
        $pnum = self::zerofill($request['PNUM'], 2);
        /* create HWPID string */
        $hwpid = self::zerofill($request['HWPID'], 4);

        /* if PDATA avilable create PDATA STRING */
        if(isset($request['PDATA']) && $request['PDATA'] !== false){
            for ($i=0; $i < count($request['PDATA']); $i++) {
                if($request['PDATA'][$i] < 0 || $request['PDATA'][$i] > 0xFF ) {
                    return false;
                }
                $pdata = self::zerofill($request['PDATA'][$i],2);
            }
        }
        /* if PDATA not set create 0 length string */
        else{
            $pdata = "";
        }

        /* create full command string */
        return $naddr.$pnum.$pcom.$hwpid.$pdata;
    }

    private function zerofill($value, $length)
    {
        return str_pad(dechex($value, $length, 0, STR_PAD_LEFT);
    }
}


?>
