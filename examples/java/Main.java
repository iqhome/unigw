import unigw.IQRF;


public class Main {

	public static void main(String[] args) {

		String hostname = "192.168.0.133";
		int port = 5000;


		IQRF iqrf = new IQRF(hostname, port);

		System.out.print( "Module info:"+iqrf.getModuleInfo() + "\n" );
		System.out.print( "Number of nodes:"+iqrf.getNodeNum() + "\n");
   }
}
