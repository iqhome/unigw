
from iqrf import IQRF
import time

ip = "127.0.0.1"
port = 5000

con = IQRF()
con.connect(ip, port)
print ("Modle Info:           ", con.getModuleInfo())
print ("Modle Info:           ", con.getNodeNum())

