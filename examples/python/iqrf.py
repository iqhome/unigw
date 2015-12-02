
import socket

class IQRF:

    __server = ""
    __port = 0
    __sock = 0

    __timeout = 2 # 2 sec socket timeout

    #def __init__:

    def connect(self, ip, port):

        if not ip or not port:
            return False

        self.__server = ip
        self.__port = port;
        try:
            self.__sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
            self.__sock.settimeout(self.__timeout)
        except socket.error as msg:
            print (msg)
            return False
        return True


    def send(self, inputstr):

        if not self.__sock:
            return False
        if not inputstr:
            return False;
        try:
            self.__sock.sendto(inputstr.encode('utf-8'), (self.__server, self.__port))
        except socket.error as msg:
            print (msg)
            return False
        return True


    def recv(self):
        if not self.__sock:
            return False
        try:
            data, addr = self.__sock.recvfrom(1024)
        except socket.error as msg:
            print (msg)
            return False
        return data

    def getModuleInfo(self):

        com_getModuleInfo = "00000200FFFF" #array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
        self.send(com_getModuleInfo);
        return self.recv();

    def getNodeNum(self):

        com_getNodeNum = "00000000FFFF" #array( 0x00, 0x00, 0x00, 0x00, 0xFF, 0xFF );
        self.send(com_getNodeNum)
        return self.recv();
