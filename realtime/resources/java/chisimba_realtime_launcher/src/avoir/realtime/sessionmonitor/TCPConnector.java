/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.sessionmonitor;

import java.io.*;


import java.util.Vector;



import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSocket;
import javax.net.ssl.SSLSocketFactory;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

/**
 *
 * @author developer
 */
public class TCPConnector {

    private boolean running = true;
    private SessionListing base;
    /**
     * Reader for the ObjectInputStream
     */
    protected ObjectInputStream reader;
    /**
     * Writer for the ObjectOutputStream
     */
    protected ObjectOutputStream writer;
  
  private String SUPERNODE_HOST = "196.21.45.85";
    private int SUPERNODE_PORT = 80;
   // private String SUPERNODE_HOST = "localhost";
    //private int SUPERNODE_PORT = 22225;
    
    //everything is encrypted here
    private SSLSocketFactory dfactory;
    private SSLSocket socket;

    public TCPConnector() {
    }

    public TCPConnector(SessionListing base) {
        this.base = base;
    }

    public void setObjectInputStream(ObjectInputStream in) {
        reader = in;
    }

    public void setObjectOutputStream(ObjectOutputStream out) {
        writer = out;
    }

    /**
     * changes super node host
     * @param host
     */
    public void setSuperNodeHost(String host) {
        SUPERNODE_HOST = host;
    }

    /**
     * changes super node....port
     * @param port
     */
    public void setSuperNodePort(int port) {
        SUPERNODE_PORT = port;
    }

    public String getSuperNodeHost() {
        return SUPERNODE_HOST;
    }

    public int getSuperNodePort() {
        return SUPERNODE_PORT;
    }

    public void requestLiveSessions(String sessionId) {
        System.out.println("Requesting Live Sessions");
        sendPacket(new SessionListingRequestPacket(sessionId));
    }

    public void requestUserRemoval(String sessionId) {
        sendPacket(new RemoveSessionMonitorPacket(sessionId));
    }

    /**
     * sends a TCP Packet to Super Node
     * @param packet
     */
    public void sendPacket(SessionListingRequestPacket packet) {
        try {
            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();
            } else {
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public void sendPacket(RemoveSessionMonitorPacket packet) {
        try {
            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();
            } else {
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }
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

    /**
     * Initial connection
     * @return
     */
    public boolean connect() {
        boolean result = false;
        System.setProperty("java.protocol.handler.pkgs", "com.sun.net.ssl.internal.www.protocol");

        try {
            try {
                if (base != null) {
                    base.setText("Connecting ...", false);//to " + SUPERNODE_HOST+" ...");

                }
                //if (base != null) {
                // if (base.isLocalhost()) {
                // SUPERNODE_HOST = "127.0.0.1";
                // SUPERNODE_PORT = 22225;
                // }
                // }
                //install the all trusting manager
                SSLContext context = SSLContext.getInstance("SSL");
                context.init(null, trustAllCerts, new java.security.SecureRandom());
                dfactory = context.getSocketFactory();
                socket = (SSLSocket) dfactory.createSocket(SUPERNODE_HOST, SUPERNODE_PORT);
                try {
                    socket.startHandshake();
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
                result = true;
            } catch (Exception ex) {
                if (base != null) {
                    base.setText("Connection Error: Cannot connect to server", true);
                }
                ex.printStackTrace();
                return false;
            }
            writer = new ObjectOutputStream(new BufferedOutputStream(socket.getOutputStream()));
            writer.flush();
            reader = new ObjectInputStream(new BufferedInputStream(socket.getInputStream()));
            result = true;
            running = true;
            startListen();
            sleep(500);
            requestLiveSessions(base.getSessionId());
            base.showTableListing();
        } catch (IOException ex) {
            if (base != null) {
                base.setText("Connection Error: " + ex.getMessage(), true);
            }
            ex.printStackTrace();
        }
        return result;
    }

    private void sleep(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    public boolean isRunning() {
        return running;
    }

    public void setRunning(boolean running) {
        this.running = running;
    }

    public void startListen() {
        Thread t = new Thread() {

            @Override
            public void run() {
                listen();
            }
        };
        t.start();
    }

    /**
     * Listens for communications from the super node
     */
    public void listen() {
        while (running) {
            try {
                Object packet = null;
                try {
                    System.out.println("In Listen");
                    packet = reader.readObject();
                    System.out.println(packet.getClass());
                    if (packet instanceof SessionListingReplyPacket) {

                        SessionListingReplyPacket p = (SessionListingReplyPacket) packet;
                        Vector<SessionPresenter> presenters = p.getPresenters();
                        Vector<SessionParticipant> participants = p.getParticipants();
                        System.out.println("Received : " + presenters.size() + " presenters and " + participants.size() + " participants");
                        base.refreshTable(participants, presenters);
                    }
                } catch (EOFException ex) {
                    ex.printStackTrace();
                    if (base != null) {
                        base.setText("Disconnected from server.", true);
                    }
                    running = false;
                }
            } catch (Exception ex) {
                //for now, just cut off the listening
                running = false;
                if (base != null) {
                    base.setText("Disconnected from server.", true);
                }
                ex.printStackTrace();
            }
        }
    }
}
