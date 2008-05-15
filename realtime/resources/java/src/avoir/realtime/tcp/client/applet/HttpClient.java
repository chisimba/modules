package avoir.realtime.tcp.client.applet;

import java.io.*;
import java.net.*;


import java.net.URL;
import java.net.URLConnection;
import java.awt.Image;
import java.net.HttpURLConnection;
import javax.imageio.ImageIO;
import java.io.InputStream;
import javax.swing.JOptionPane;

public class HttpClient {

    public static int getSlideCount(String strURL, String strProxy, int iProxyPort, String home, int slideIndex, TCPTunnellingApplet client) {
        URL url = null;
        URLConnection c = null;
        try {
            URL urlOriginal = new URL(strURL);

            if ((null != strProxy) && (0 < strProxy.length())) {
                URL urlProxy = new URL(urlOriginal.getProtocol(),
                        strProxy,
                        iProxyPort,// A value of -1 means use the default port for the specified protocol.
                        strURL);// The original URL is passed as "the file on the host".

                if (-1 != iProxyPort) {
                }

                url = urlProxy;
            } else {
                url = urlOriginal;
            }

            c = url.openConnection();

            // In this example, we only consider HTTP connections.
            if (c instanceof HttpURLConnection)// instanceof returns true only if the object is not null.
            {

                HttpURLConnection h = (HttpURLConnection) c;
                h.connect();

                if (h.getResponseCode() != HttpURLConnection.HTTP_OK) {
                    client.setConnectingString("Cannot start the session. Server response: " + h.getResponseMessage());
                    JOptionPane.showMessageDialog(null, "Cannot start session. Error downloading:\n" + url);
                    System.out.println("ERROR: download failed for " + url);
                    return 0;
                }
                String ct = h.getContentType();
                int contentLength = h.getContentLength();
                if (ct.startsWith("text/") || contentLength == -1) {
                    /**
                     * A crude way of finding total slides through
                     * file listing. Will only work with apache 
                     * web server
                     */
                    InputStream stream = h.getInputStream();
                    BufferedReader in = new BufferedReader(new InputStreamReader(stream));
                    String s;
                    String line = "";
                    while ((s = in.readLine()) != null) {
                         System.out.println(s);
                        line += s;
                    }
                    int i1 = line.lastIndexOf("alt=\"[IMG]\"");
                    line = line.substring(i1);
                    int i2 = line.indexOf("img");
                    line = line.substring(i2);

                    char[] chars = line.toCharArray();
                    String value = "";
                    for (int i = 0; i < chars.length; i++) {
                        if (chars[i] == '.') {
                            break;
                        }

                        value += chars[i];
                    }
                    int slideCount = Integer.parseInt(value.substring(3));
                    return slideCount;
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();

        }
        return 0;
    }

    /**
     * This function makes an HTTP GET request of the specified URL using a proxy if provided.
     * If successfully, the HTTP response headers are printed out.
     * If the MIME type of the response is text/html, then the number of lines of text
     * is printed as well.
     *
     * @param strURL - A string representing the URL to request, eg, "http://bdn.borland.com/"
     * @param strProxy - A string representing either the IP address or host name of the proxy server.
     * @param iProxyPort - An integer that indicates the proxy port or -1 to indicate the default port for the protocol.
     * @return rc is true if the request succeeded and false otherwise.
     */
    public static boolean doURLRequest(String strURL, String strProxy, int iProxyPort, String home, int slideIndex, TCPTunnellingApplet client) {
        boolean rc = false;

        URL url = null;
        URLConnection c = null;
        //int bufferLength = 128;
        try {
            //         System.out.println("\nHTTP Request: " + strURL);

            URL urlOriginal = new URL(strURL);

            if ((null != strProxy) && (0 < strProxy.length())) {
                URL urlProxy = new URL(urlOriginal.getProtocol(),
                        strProxy,
                        iProxyPort,// A value of -1 means use the default port for the specified protocol.
                        strURL);// The original URL is passed as "the file on the host".

                // System.out.println("Using Proxy: " + strProxy);
                if (-1 != iProxyPort) {
                //   System.out.println("Using Proxy Port: " + iProxyPort);
                }

                url = urlProxy;
            } else {
                url = urlOriginal;
            }

            c = url.openConnection();

            // In this example, we only consider HTTP connections.
            if (c instanceof HttpURLConnection)// instanceof returns true only if the object is not null.
            {

                HttpURLConnection h = (HttpURLConnection) c;
                h.connect();

                if (h.getResponseCode() != HttpURLConnection.HTTP_OK) {
                    client.setConnectingString("Cannot start the session. Server response: " + h.getResponseMessage());
                    JOptionPane.showMessageDialog(null, "Cannot start session. Error downloading:\n" + url);
                    System.out.println("ERROR: download failed for " + url);
                    return false;
                }
                String ct = h.getContentType();
                int contentLength = h.getContentLength();
                if (ct.startsWith("text/") || contentLength == -1) {
                    return false;
                }

                InputStream stream = h.getInputStream();

                java.awt.image.BufferedImage ximg = ImageIO.read(stream);
                String theFile = url.getFile();
                theFile = theFile.substring(theFile.lastIndexOf('/') + 1);

                ImageIO.write(ximg, "jpg", new File(home + "/" + theFile));

                if (new File(home).list().length == client.getSlideCount()) {
                    client.setConnecting(false);
                    client.setConnectingString(" ");
                }


                //  System.out.println("download finished for " + url.getFile());
                /*
                byte[] buffer = new byte[contentLength];
                int bytesread = 0;
                int offset = 0;
                while (bytesread >= 0) {
                bytesread = stream.read(buffer, offset, buffer.length);
                if (bytesread == -1) {
                break;
                }
                offset += bytesread;
                }
                if (offset != contentLength) {
                System.err.println("Error: Only read " + offset + " bytes");
                System.err.println("Expected " + contentLength + " bytes");
                }
                String theFile = url.getFile();
                theFile = theFile.substring(theFile.lastIndexOf('/') + 1);
                FileOutputStream fout = new FileOutputStream(home +"/"+theFile);
                fout.write(buffer);*/

                h.disconnect();
            } else {
                System.out.println("**** No download: connection was not HTTP");
            }

            rc = true;
        } // Catch all exceptions.
        catch (Exception exc) {
            exc.printStackTrace();
            System.out.println("**** Connection failure: " + exc.toString());
        // System.out.println("**** Connection failure: " + exc.getMessage());// Same as above line but without the exception class name.
        } finally {
            // Do cleanup here.
            // For example, the following, in theory, could make garbage collection more efficient.
            // This might be the place where you choose to put your method call to your connection's "disconnect()";
            // curiously, while every URLConnection has a connect() method, they don't necessarily have a disconnect() method.
            // HttpURLConnection has a disconnect() which is called above.
            c = null;
            url = null;

            return rc;
        }
    }
}
