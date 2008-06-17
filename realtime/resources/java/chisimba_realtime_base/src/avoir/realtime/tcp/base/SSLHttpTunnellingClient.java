/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import java.net.*;
import java.io.*;
import javax.net.ssl.*;
import java.util.*;

public class SSLHttpTunnellingClient {

    private SSLSocketFactory dfactory;
    private String tunnelHost;
    private int tunnelPort;
    HashMap rec = new HashMap();
    SSLSocket result;
    Socket tunnel;
    static InetSocketAddress addr;
    RealtimeBase base;
    // Create a trust manager that does not validate certificate chains
    TrustManager[] trustAllCerts = new TrustManager[]{
        new X509TrustManager() {

            public java.security.cert.X509Certificate[] getAcceptedIssuers() {
                return null;
            }

            public void checkClientTrusted(
                    java.security.cert.X509Certificate[] certs, String authType) {
            }

            public void checkServerTrusted(
                    java.security.cert.X509Certificate[] certs, String authType) {
            }
        }
    };

    /** Creates a new instance of SSLHttpTunnellingClient */
    public SSLHttpTunnellingClient(String proxyhost, String proxyport, RealtimeBase base) {
        tunnelHost = proxyhost;
        tunnelPort = Integer.parseInt(proxyport);
        this.base = base;
        System.setProperty("https.proxyHost", tunnelHost);
        System.setProperty("https.proxyPort", "" + tunnelPort);
        tunnelHost = System.getProperty("https.proxyHost");
        tunnelPort = Integer.parseInt(System.getProperty("https.proxyPort"));
        System.setProperty("java.protocol.handler.pkgs", "com.sun.net.ssl.internal.www.protocol");

        try {
            Socket ss = createSocket("196.21.45.85", 80);
            ObjectOutputStream output = new ObjectOutputStream(new BufferedOutputStream(ss.getOutputStream()));
            ObjectInputStream input = new ObjectInputStream(new BufferedInputStream(ss.getInputStream()));
            base.getTcpClient().setObjectOutputStream(output);
            base.getTcpClient().setObjectInputStream(input);
            base.getTcpClient().startListen();
            base.getTcpClient().publish(base.getUser());
            base.initChatRoom();
            base.getSessionManager().startSession();
            base.doPostConnectControlsCheck();
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Error: " + e);
        }
    }

    public Socket createSocket(String host, int port) throws IOException, UnknownHostException {
        return createSocket(null, host, port, true);
    }

    public Socket createSocket(Socket s, String host, int port,
            boolean autoClose) {
        try {
            System.out.println("Connecting...");
            base.setText("Initializing session 1 of 3...", false);
            tunnel = new Socket(tunnelHost, tunnelPort);
            System.out.println("Connected to proxy: " + tunnel);
            base.setText("Initializing session 2 of 3...", false);
            doTunnelHandshake(tunnel, host, port);
            SSLContext context = null;
            try {
                context = SSLContext.getInstance("SSL");
                context.init(null, trustAllCerts, new java.security.SecureRandom());
            } catch (Exception ex) {
                ex.printStackTrace();
            }
            dfactory = context.getSocketFactory();
            result = (SSLSocket) dfactory.createSocket(tunnel, host, port, autoClose);
            System.out.println("result " + result);
        } catch (Exception e) {
            e.printStackTrace();
            base.setText("Error connecting to server.", true);
            System.out.println("Exc " + e);
        }


        result.addHandshakeCompletedListener(
                new HandshakeCompletedListener() {

                    public void handshakeCompleted(HandshakeCompletedEvent event) {
                        System.out.println("Handshake finished!");
                    }
                });
        System.out.println("result1.startHandshake " + result);
        try {
            result.startHandshake();
        } catch (Exception ex) {
            base.setText("Cannot connect to server", true);

        }
        System.out.println("result.startHandshake " + result);
        return result;
    }

    private void doTunnelHandshake(Socket tunnel, String host, int port)
            throws IOException {
        System.out.println("Inside handshake");
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
            base.setText("Error connecting to proxy", true);

            throw new IOException("Unable to tunnel through " + tunnelHost + ":" + tunnelPort + ". Proxy returns \"" + replyStr + "\"");
        }
        System.out.println("Tunnel handshake Successfull");
        base.setText("Initializing session 3 of 3...", false);

    /* tunneling Handshake was successful! */
    }

    public String[] getDefaultCipherSuites() {
        return dfactory.getDefaultCipherSuites();
    }

    public String[] getSupportedCipherSuites() {
        return dfactory.getSupportedCipherSuites();
    }

    public static String getaddress() {
        String address = "";
        try {

            System.setProperty("java.net.useSystemProxies", "true");
            List l = ProxySelector.getDefault().select(
                    new URI("http://www.google.com/"));

            for (Iterator iter = l.iterator(); iter.hasNext();) {

                Proxy proxy = (Proxy) iter.next();

                System.out.println("proxy type : " + proxy.type());

                addr = (InetSocketAddress) proxy.address();

                if (addr == null) {

                    System.out.println("No Proxy");

                } else {

                    System.out.println("proxy hostname : " + addr.getHostName());
                    address = addr.getHostName();
                    System.out.println("proxy port : " + addr.getPort());

                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

        System.out.println("Valaddress" + addr.getHostName());
        System.out.println("Valport" + addr.getPort());
        return address;
    }

    public static String getPort() {
        String port = "";
        try {

            System.setProperty("java.net.useSystemProxies", "true");
            List l = ProxySelector.getDefault().select(
                    new URI("http://www.google.com/"));

            for (Iterator iter = l.iterator(); iter.hasNext();) {

                Proxy proxy = (Proxy) iter.next();

                System.out.println("proxy type : " + proxy.type());

                addr = (InetSocketAddress) proxy.address();

                if (addr == null) {

                    System.out.println("No Proxy");

                } else {

                    System.out.println("proxy hostname : " +
                            addr.getHostName());

                    System.out.println("proxy port : " +
                            addr.getPort());
                    port = String.valueOf(addr.getPort());
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

        System.out.println("Valaddress1" + addr.getHostName());
        System.out.println("Valport1" + addr.getPort());
        return port;
    }
}