package unigw;

import java.io.IOException;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;
import java.net.SocketTimeoutException;
import java.net.UnknownHostException;

public class IQRF {

	String serverHostname;
	int serverPort;
	InetAddress IPAddress;
	String errormsg;


	public IQRF(String hostname, int port) {
		serverHostname 	= hostname;
		serverPort 		= port;
		try {
			IPAddress = InetAddress.getByName(serverHostname);
		} catch (Exception ex) {
			System.err.println(ex);
		}
	  }
	public String send(String message){
		String response = null;
		try {

		      DatagramSocket clientSocket = new DatagramSocket();

		      byte[] sendData = new byte[1024];
		      byte[] receiveData = new byte[1024];


		      sendData = message.getBytes();

		      //System.out.println ("Sending data to " + sendData.length + " bytes to server.");
		      DatagramPacket sendPacket = new DatagramPacket(sendData, sendData.length, IPAddress, serverPort);

		      clientSocket.send(sendPacket);

		      DatagramPacket receivePacket = new DatagramPacket(receiveData, receiveData.length);

		      //System.out.println ("Waiting for return packet");
		      clientSocket.setSoTimeout(1000);

		      try {
		           clientSocket.receive(receivePacket);
		           response = new String(receivePacket.getData());

		          }
		      catch (SocketTimeoutException ste)
		          {
		          	System.out.println ("Timeout Occurred: Packet assumed lost");
	   			 errormsg = "Timeout";
		      }

		      clientSocket.close();
		     }
		   catch (UnknownHostException ex) {
		     System.err.println(ex);
			 errormsg = "UnknownHostException";
		    }
		   catch (IOException ex) {
		     System.err.println(ex);
			 errormsg = "IOException";
		    }

	      return response;
	}
	public String getModuleInfo()
    {
        String message = "00000200FFFF";//array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
        String response = send(message);
		if(response == null){
			return errormsg;
		}
		else{
			return response;
		}
    }
    public int getNodeNum()
    {
    	String message = "00000000FFFF";//array( 0x00, 0x00, 0x00, 0x00, 0xFF, 0xFF );
		String response = send(message);
		if(response == null){
			return -1;
		}
		else{
			return Integer.parseInt(response.substring(16, 18), 16) ;
		}
    }

    public String discovery()
    {
    	String message = "00000700FFFF0700";//array( 0x00, 0x00, 0x02, 0x00, 0xFF, 0xFF );
		String response = send(message);
		if(response == null){
			return errormsg;
		}
		else{
			return response;
		}
    }
}
