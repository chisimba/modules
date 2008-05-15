/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

/**
 *
 * @author developer
 */
public class ProxyHost {

    String proxyIp;
    int proxyPort;

    public ProxyHost(String proxyIp, int proxyPort) {
        this.proxyIp = proxyIp;
        this.proxyPort = proxyPort;
    }

    public String getProxyIp() {
        return proxyIp;
    }

    public int getProxyPort() {
        return proxyPort;
    }
    public String toString(){
        return proxyIp+":"+proxyPort;
    }
}
