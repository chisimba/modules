/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;



import java.net.*;
import java.util.*;

/**
 *
 * @author developer
 */
public class ProxyDetector {

    public static ProxyHost getProxy(String host) {
        try {

            System.setProperty("java.net.useSystemProxies", "true");
            List l = ProxySelector.getDefault().select(
                    new URI("http://"+host));

            for (Iterator iter = l.iterator(); iter.hasNext();) {

                Proxy proxy = (Proxy) iter.next();

                System.out.println("proxy type : " + proxy.type());

                InetSocketAddress addr = (InetSocketAddress) proxy.address();

                if (addr == null) {

                    System.out.println("No Proxy");
                    return null;

                } else {

                    System.out.println("proxy hostname : " +
                            addr.getHostName());

                    System.out.println("proxy port : " +
                            addr.getPort());
                    return new ProxyHost(addr.getHostName(), addr.getPort());

                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
}
