/*
 * Splash.java
 *
 * Created on 10 May 2008, 05:14
 */
package avoir.realtime.tcp.client.applet.splash;

import avoir.realtime.tcp.common.packet.ModuleFileRequestPacket;

import avoir.realtime.common.RealtimeOptionsFrame;
import avoir.realtime.tcp.common.user.User;
import avoir.realtime.tcp.common.user.UserLevel;
import java.awt.Color;
import java.net.Socket;
import static avoir.realtime.common.Constants.*;
import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.lang.reflect.Method;
import java.net.URL;
import java.net.URLClassLoader;
import java.net.UnknownHostException;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;

/**
 *
 * @author  developer
 */
public class Splash extends javax.swing.JApplet {

    int requiredVer = avoir.realtime.common.Version.version;
    private Socket socket;
    private ObjectInputStream reader;
    private ObjectOutputStream writer;
    private boolean running = false;
    private String userName = "Anonymous";
    private String fullName = "Anonymous";
    private String sessionId = "xx";
    private String slidesDir = "";
    private String slideServerId = "";
    private String resourcesPath = "";
    private String siteRoot = "";
    private String host;
    private boolean isPresenter = false;
    private User user;

    /** Initializes the applet Splash */
    @Override
    public void init() {
        try {
            java.awt.EventQueue.invokeAndWait(new Runnable() {

                public void run() {
                    initComponents();
                    infoField.setForeground(Color.RED);
                }
            });
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void initUser() {
        userName = getParameter("userName");
        fullName = getParameter("fullName");
        sessionId = getParameter("sessionId");
        slidesDir = getParameter("slidesDir");
        siteRoot = getParameter("siteRoot");
        resourcesPath = getParameter("resourcesPath");
        slideServerId = getParameter("slideServerId");
        String levelString = getParameter("userLevel");
        isPresenter = new Boolean(getParameter("isSessionPresenter")).booleanValue();
        //if not logged in, then assign random number
        if (userName == null || userName.startsWith("Language") || userName.startsWith("Anonymous")) { //not logging on through KEWL for dev, so give random user name

            userName = "Guest" + new java.util.Random().nextInt(200);
            fullName = userName;
        }
        //the host from which this applet originated
        host = getCodeBase().getHost();

        UserLevel userLevel = UserLevel.GUEST; //assume guest unless set differently in applet param

        if (levelString != null) {
            userLevel = UserLevel.valueOf(levelString.toUpperCase());
        }
        user = new User(userLevel, fullName, userName, host, 22225, isPresenter,
                sessionId, slidesDir, false, siteRoot, slideServerId);

    }

    /**
     * Initial connection
     * @return
     */
    public boolean connect() {
        boolean result = false;
        running = false;
        try {
            try {
                infoField.setForeground(new Color(0, 131, 0));
                infoField.setText("Connecting to server ...");
                socket = new Socket(SUPERNODE_HOST, SUPERNODE_PORT);
                infoField.setText("");
                infoField.setForeground(Color.RED);
                initUser();
                result = true;
            } catch (UnknownHostException ex) {
                infoField.setForeground(Color.RED);
                infoField.setText("Cannot connect to server.");
                connectButton.setEnabled(true);
                optionsButton.setEnabled(true);

                ex.printStackTrace();
                return false;
            }
            writer = new ObjectOutputStream(new BufferedOutputStream(socket.getOutputStream()));
            writer.flush();
            reader = new ObjectInputStream(new BufferedInputStream(socket.getInputStream()));
            result = true;
            running = true;
            Thread t = new Thread() {

                @Override
                public void run() {
                    listen();
                }
            };
            t.start();
            delay(1000);
            loadComponents();

        } catch (IOException ex) {
            infoField.setForeground(Color.RED);
            infoField.setText("Connection Error: " + ex.getMessage());
            connectButton.setEnabled(true);
            optionsButton.setEnabled(true);
            ex.printStackTrace();
        }
        return result;
    }

    /**
     * Listens for communications from the super node
     */
    public void listen() {
        while (running) {
            try {
                Object packet = null;
                try {
                    packet = reader.readObject();
                } catch (Exception ex) {
                    ex.printStackTrace();
                    running = false;
                    infoField.setForeground(Color.RED);
                    infoField.setText("Disconnected from the server. Refresh the browser");
                }


                if (packet instanceof ModuleFileRequestPacket) {
                    ModuleFileRequestPacket p = (ModuleFileRequestPacket) packet;
                    String filename = p.getFilename();
                    File f = new File(filename);
                    String dest = ".";
                    if (filename.endsWith(".jar")) {
                        dest = REALTIME_HOME + "/lib/" + f.getName();
                        writeFile(dest, p.getByteArray());
                    }
                    //then this is linux/unix
                    if (filename.endsWith(".so")) {
                        dest = JAVA_HOME + "/lib/i386/" + f.getName();
                        writeFile(dest, p.getByteArray());
                    }
                    //win
                    if (filename.endsWith(".exe")) {
                        dest = REALTIME_HOME + "/bin/" + f.getName();
                        writeFile(dest, p.getByteArray());
                        installWinJMF();
                    }

                }
            } catch (Exception ex) {
                ex.printStackTrace();
                running = false;
                infoField.setForeground(Color.RED);
                infoField.setText("Disconnected from the server. Refresh the browser");
                closeSocket();
            }
        }
    }

    private void closeSocket() {
        try {
            if (socket != null) {
                socket.close();
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public void writeFile(String filename, byte[] byteArray) {
        try {
            FileChannel fc =
                    new FileOutputStream(filename).getChannel();
            fc.write(ByteBuffer.wrap(byteArray));
            fc.close();
        } catch (Exception ex) {
            infoField.setText("Error downloading " + new File(filename).getName());
            ex.printStackTrace();
        }
    }

    private void dowloadLib(String filename, String filepath) {
        ModuleFileRequestPacket p =
                new avoir.realtime.tcp.common.packet.ModuleFileRequestPacket(null, 
                filename, filepath,"","");
        sendPacket(p);
    }

    public void sendPacket(ModuleFileRequestPacket packet) {
        try {
            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();
            } else {
                infoField.setText("Writer is null !!!! Cannot send  packet " + packet.getClass());
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    private void loadComponents() {

        pb.setMinimum(0);
        pb.setMaximum(linuxFileList.length);
        String os = System.getProperty("os.name");
        if (os.toUpperCase().startsWith("LINUX")) {
            for (int i = 0; i < linuxFileList.length; i++) {
                System.out.print("Processing " + linuxFileList[i] + "...");
                File f = null;
                if (linuxFileList[i].endsWith(".so")) {
                    f = new File(JAVA_HOME + "/lib/i386/" + linuxFileList[i]);
                }
                if (linuxFileList[i].endsWith(".jar")) {
                    f = new File(REALTIME_HOME + "/lib/" + linuxFileList[i]);
                }
                if (!f.exists()) {
                    String filepath = resourcesPath + "/jmf-linux-i586/lib/" + linuxFileList[i];
                    System.out.print("Not Found, requesting download\n");
                    infoField.setForeground(new Color(0, 131, 0));
                    infoField.setText("Downloading .." + linuxFileList[i]);
                    dowloadLib(linuxFileList[i], filepath);

                } else {
                    System.out.print("OK\n");
                }
                pb.setValue(i);
            }
        }


        pb.setMinimum(0);
        pb.setMaximum(winFileList.length);
        if (os.toUpperCase().startsWith("WINDOWS")) {
            try {
                Class.forName("javax.media.*");
            } catch (Exception ex) {
                for (int i = 0; i < winFileList.length; i++) {
                    String filepath = resourcesPath + "/jmf-windows-bin/" + winFileList[i];
                    infoField.setForeground(new Color(0, 131, 0));
                    infoField.setText("Downloading .." + winFileList[i]);
                    dowloadLib(winFileList[i], filepath);
                    pb.setValue(i);
                }
            }
        }

        pb.setMinimum(0);
        pb.setMaximum(realtimelib.length);
        for (int i = 0; i < realtimelib.length; i++) {
            File f = new File(REALTIME_HOME + "/lib/" + realtimelib[i]);
            String path = resourcesPath + "/" + realtimelib[i];
            System.out.print("Processing " + realtimelib[i] + " ...");
            if (!f.exists()) {
                System.out.print("Not found, requesting download\n");
                dowloadLib(realtimelib[i], path);

            } else {
                if (!realtimeVerlib[i].startsWith("realtime")) {
                    System.out.print("Found, checking version\n");
                    if (!isLatestVersion(f.getAbsolutePath(), realtimeVerlib[i], i)) {
                        dowloadLib(realtimelib[i], path);

                    } else {
                        loadClass(f.getAbsolutePath(), realtimeVerlib[i]);
                    }
                }
            }
            pb.setValue(i);
        }
        lauchMainGUI();
    }
    private static final Class[] parameters = new Class[]{URL.class};

    public static void addFile(String s) throws IOException {
        File f = new File(s);
        addFile(f);
    }// end method

    public static void addFile(File f) throws IOException {
        addURL(f.toURL());
    }// end method

    public static void addURL(URL u) throws IOException {
        URLClassLoader sysloader = (URLClassLoader) ClassLoader.getSystemClassLoader();
        Class sysclass = URLClassLoader.class;
        try {
            Method method = sysclass.getDeclaredMethod("addURL", parameters);
            method.setAccessible(true);
            method.invoke(sysloader, new Object[]{u});
        } catch (Throwable t) {
            t.printStackTrace();
            throw new IOException(
                    "Error, could not add URL to system classloader");
        }// end try catch

    }// end method

    private boolean isLatestVersion(String file, String classname, int index) {
        Integer ver = new Integer(-1);
        try {
            try {
                System.out.println("Adding " + file + " to cp..");
                addFile(file);
                System.out.println("Loading " + classname + " ...");
                Class cl = Class.forName(classname);
                //cl.newInstance();
                
                System.out.println(" Class Found ....Type: ");
            } catch (ClassNotFoundException e) {
                e.printStackTrace();
            }
            if (index == 0) {
//                    ver = ClassLoaderManager.getAppletVersion(file);
                // System.out.println("Ma name: "+ClassLoaderManager.getAppletName());
            }
            if (index == 2) {
             //   ver = ClassLoaderManager.getMediaVersion();
            }

            if (ver.intValue() == requiredVer) {
                System.out.println("\n   Found  latest Ver: " + ver + " == required " + requiredVer);
                return true;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        System.out.print("\n   WARN: Found ver " + ver + " != required " + requiredVer + "\n");

        return true;
    }

    private void loadClass(String file, String classname) {
        try {
            addFile(file);
            Class.forName(classname);
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public void delay(long duration) {
        try {
            Thread.sleep(duration);

        } catch (Exception ex) {
        }
    }

    private void installWinJMF() {
        ProcessBuilder prb;
        prb = new ProcessBuilder(REALTIME_HOME + "/bin/jmf-2_1_1e-windows-i586.exe");
        try {
            prb.start();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void lauchMainGUI() {
//        ClassLoaderManager.addMainGUI(mPanel);
//        ClassLoaderManager cl = new ClassLoaderManager();
  //      cl.addMainGUI(mPanel);
        this.resize(600, 500);
        repaint();
    }

    /**
     * changes super node host
     * @param host
     */
    public void setSuperNodeHost(String host) {
        SUPERNODE_HOST = host;
    }

    public void setSuperNodePort(int port) {
        SUPERNODE_PORT = port;
    }

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {
        java.awt.GridBagConstraints gridBagConstraints;

        mPanel = new javax.swing.JPanel();
        infoField = new javax.swing.JLabel();
        pb = new javax.swing.JProgressBar();
        cPanel = new javax.swing.JPanel();
        connectButton = new javax.swing.JButton();
        optionsButton = new javax.swing.JButton();

        mPanel.setLayout(new java.awt.GridBagLayout());
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        mPanel.add(infoField, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(7, 0, 0, 0);
        mPanel.add(pb, gridBagConstraints);

        connectButton.setText("Connect");
        connectButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                connectButtonActionPerformed(evt);
            }
        });
        cPanel.add(connectButton);

        optionsButton.setText("Options");
        optionsButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsButtonActionPerformed(evt);
            }
        });
        cPanel.add(optionsButton);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 4;
        mPanel.add(cPanel, gridBagConstraints);

        getContentPane().add(mPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

    public void showOptionsFrame(int tabIndex) {
        RealtimeOptionsFrame optionsFrame = new RealtimeOptionsFrame(null, tabIndex);
        optionsFrame.setSize(500, 500);
        optionsFrame.setLocationRelativeTo(null);
        optionsFrame.setVisible(true);

    }
private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_optionsButtonActionPerformed
    showOptionsFrame(0);
}//GEN-LAST:event_optionsButtonActionPerformed

private void connectButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_connectButtonActionPerformed
    Thread t = new  

          Thread() {

            @Override
        public void run() {
            connectButton.setEnabled(false);
            optionsButton.setEnabled(true);
            connect();
        //  closeSocket();
        }
    };
    t.start();

}//GEN-LAST:event_connectButtonActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton connectButton;
    private javax.swing.JLabel infoField;
    private javax.swing.JPanel mPanel;
    private javax.swing.JButton optionsButton;
    private javax.swing.JProgressBar pb;
    // End of variables declaration//GEN-END:variables
}
