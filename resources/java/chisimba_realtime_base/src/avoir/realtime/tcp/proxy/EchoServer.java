/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.proxy;
import javax.net.ssl.*;
   import javax.net.*;
   import java.io.*;
   import java.net.*;

   public class EchoServer {
     private static final int PORT_NUM = 22225;
     public static void main(String args[]) {
       ServerSocketFactory serverSocketFactory =
         SSLServerSocketFactory.getDefault();
       ServerSocket serverSocket = null;
       try {
         serverSocket =
           serverSocketFactory.createServerSocket(PORT_NUM);
       } catch (IOException ignored) {
         System.err.println("Unable to create server");
         System.exit(-1);
       }
       while(true) {
         Socket socket = null;
         try {
           socket = serverSocket.accept();
           InputStream is = socket.getInputStream();
           BufferedReader br = new BufferedReader(
             new InputStreamReader(is, "US-ASCII"));
           OutputStream os = socket.getOutputStream();
           Writer writer = 
             new OutputStreamWriter(os, "US-ASCII");
           PrintWriter out = new PrintWriter(writer, true);
           String line = null;
           while ((line = br.readLine()) != null) {
             out.println(line);
           }
         } catch (IOException exception) {
           exception.printStackTrace();
         } finally {
           if (socket != null) {
             try {
               socket.close();
             } catch (IOException ignored) {
             }
           }
         }
       }
     }
   }

