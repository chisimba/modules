/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

import java.util.Enumeration;
import java.net.*;
import java.util.Collections;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class IPUtil {

    /**
     * Get the first IP amongst the list 
     * @return
     */
    public static String getIP() {
        Vector ips = getIPs();
        if (ips.size() > 0) {
            String ip = (String) ips.firstElement();
            if (ip.startsWith("/")) {
                ip = ip.substring(1);
            }
            return ip;
        }
        return "127.0.0.1";
    }

    /**
     * Get all network interface and get the ips
     * @return
     */
    public static Vector getIPs() {
        Vector ips = new Vector();
        try {
            Enumeration<NetworkInterface> nets = NetworkInterface.getNetworkInterfaces();
            for (NetworkInterface netint : Collections.list(nets)) {
                displayInterfaceInformation(netint, ips);

            }
        } catch (SocketException ex) {
            ex.printStackTrace();
        }
        return ips;
    }

    private static void displayInterfaceInformation(NetworkInterface netint, Vector ips) {
        try {
            Enumeration<InetAddress> inetAddresses = netint.getInetAddresses();

            for (InetAddress inetAddress : Collections.list(inetAddresses)) {
                String ip = inetAddress.toString();
              //  System.out.println(ip);
                if (ip.indexOf("127.0.0.1") > 0 || ip.indexOf(":") > 0) {
                //don't, they are not valid ips
                } else {
                    ips.addElement(ip);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }
}
