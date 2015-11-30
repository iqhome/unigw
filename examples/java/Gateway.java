import unigw.IQRF;


public class Gateway {

	public static void main(String[] args) {

		String hostname = "127.0.0.1";
		int port = 5000;


		IQRF iqrf = new IQRF(hostname, port);

		System.out.print( "Module info:"+iqrf.getModuleInfo() + "\n" );
		System.out.print( "Number of nodes:"+iqrf.getNodeNum() + "\n");
   }
}
