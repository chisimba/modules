/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.proxy;

import javax.net.ssl.*;
import java.io.*;
import java.net.*;
import java.util.Properties;
import zyh.net.HttpSocket;
import zyh.net.HttpSocketImplFactory;

public class EchoClient {

    private static int PORT_NUM = 22225;
    private static String host = "localhost";
    private Socket tunnel;
    private String tunnelHost = "192.168.1.238";
    private int tunnelPort = 8000;
    SSLSocket result;
    SSLSocketFactory socketFactory;

    public static void main(String args[]) {
        new EchoClient();
    }

    public EchoClient() {
        try {

            // Create a HttpSocketImpl factory.
            Properties properties = new Properties();

            HttpSocketImplFactory factory = new HttpSocketImplFactory("192.168.1.238", 8000, properties);
            // Used it for all HttpSockets create from now on.
            HttpSocket.setSocketImplFactory(factory);

            Socket httpSocket = new HttpSocket("192.168.1.238", 22225);
            System.out.println("conneced!!");
            OutputStream out = httpSocket.getOutputStream();
            out.write(1);

        /*            socketFactory =
        (SSLSocketFactory) SSLSocketFactory.getDefault();
        //Socket socket = socketFactory.createSocket(
        //      host, PORT_NUM);
        System.setProperty("https.proxyHost", tunnelHost);
        System.setProperty("https.proxyPort", "" + tunnelPort);
        tunnelHost = System.getProperty("https.proxyHost");
        tunnelPort = Integer.parseInt(System.getProperty("https.proxyPort"));
        System.setProperty("java.protocol.handler.pkgs", "com.sun.net.ssl.internal.www.protocol");
        System.out.println("Connecting to: " + tunnelHost + " " + tunnelPort);
        
        Socket ss = createSocket("192.168.1.238", 22225);
        OutputStream s=ss.getOutputStream();
        s.write(1);
        
         */      /*
        BufferedReader br = new BufferedReader(
        new InputStreamReader(System.in, "US-ASCII"));
        PrintWriter out = new PrintWriter(
        new OutputStreamWriter(
        socket.getOutputStream(), "US-ASCII"), true);
        
        BufferedReader socketBr = new BufferedReader(
        new InputStreamReader(
        socket.getInputStream(), "US-ASCII"));
        
        String string = null;
        System.out.print("First line: ");
        while (!(string = br.readLine()).equals("")) {
        out.println(string);
        String line = socketBr.readLine();
        System.out.println("Got Back: " + line);
        System.out.print("Next line: ");
        }
        socket.close();
         */        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public Socket createSocket(String host, int port) throws IOException, UnknownHostException {
        return createSocket(null, host, port, true);
    }

    public Socket createSocket(Socket s, String host, int port,
            boolean autoClose)
            throws IOException, UnknownHostException {
        try {
            System.out.println("Connecting...");

            tunnel = new Socket(tunnelHost, tunnelPort);
            System.out.println("Connected to proxy: " + tunnel);
            doTunnelHandshake(tunnel, host, port);

            result = (SSLSocket) socketFactory.createSocket(tunnel, host, port, autoClose);
            System.out.println("result " + result);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Exc " + e);
        }


        result.addHandshakeCompletedListener(
                new HandshakeCompletedListener() {

                    public void handshakeCompleted(HandshakeCompletedEvent event) {
                        System.out.println("Handshake finished!");
                        System.out.println(
                                "\t CipherSuite:" + event.getCipherSuite());
                        System.out.println(
                                "\t SessionId " + event.getSession());
                        System.out.println(
                                "\t PeerHost " + event.getSession().getPeerHost());
                    }
                });
        System.out.println("result1.startHandshake " + result);
        //  result.startHandshake();
        System.out.println("result.startHandshake " + result);
        return result;
    }

    private void doTunnelHandshake(Socket tunnel, String host, int port)
            throws IOException {
        System.out.println("Inside The  handshake");
        OutputStream out = tunnel.getOutputStream();
        String msg = "CONNECT " + host + ":" + port + " HTTP/1.0\n" + "User-Agent: " + sun.net.www.protocol.http.HttpURLConnection.userAgent + "\r\n\r\n";
        System.out.println(msg);
        byte b[];
        try {
            /*
             * We really do want ASCII7 -- the http protocol doesn't change
             * with locale.
             */
            b = msg.getBytes("ASCII7");
        } catch (UnsupportedEncodingException ignored) {
            /*
             * If ASCII7 isn't there, something serious is wrong, but
             * Paranoia Is Good (tm)
             */
            b = msg.getBytes();
        }
        out.write(b);
        out.flush();

        /*
         * We need to store the reply so we can create a detailed
         * error message to the user.
         */
        byte reply[] = new byte[200];
        int replyLen = 0;
        int newlinesSeen = 0;
        boolean headerDone = false; /* Done on first newline */

        InputStream in = tunnel.getInputStream();
        boolean error = false;

        while (newlinesSeen < 2) {
            int i = in.read();
            if (i < 0) {
                throw new IOException("Unexpected EOF from proxy");
            }
            if (i == '\n') {
                headerDone = true;
                ++newlinesSeen;
            } else if (i != '\r') {
                newlinesSeen = 0;
                if (!headerDone && replyLen < reply.length) {
                    reply[replyLen++] = (byte) i;
                }
            }
        }

        /*
         * Converting the byte array to a string is slightly wasteful
         * in the case where the connection was successful, but it's
         * insignificant compared to the network overhead.
         */
        String replyStr;
        try {
            replyStr = new String(reply, 0, replyLen, "ASCII7");
        } catch (UnsupportedEncodingException ignored) {
            replyStr = new String(reply, 0, replyLen);
        }
        System.out.println("Reply: " + replyStr);
        /* Look for 200 connection established */
        if (replyStr.toLowerCase().indexOf("200") == -1) {
            throw new IOException("Unable to tunnel through " + tunnelHost + ":" + tunnelPort + ". Proxy returns \"" + replyStr + "\"");
        }
        System.out.println("Tunnel handshake Successfull");

    /* tunneling Handshake was successful! */
    }
}
