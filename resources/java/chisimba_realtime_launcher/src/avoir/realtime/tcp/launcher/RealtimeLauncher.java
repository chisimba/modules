/*
 * RealtimeLauncher.java
 *
 * Created on 18 May 2008, 05:41
 */
package avoir.realtime.tcp.launcher;

import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;
import avoir.realtime.tcp.launcher.Launcher;
import java.awt.BorderLayout;
import java.awt.Color;
import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.lang.reflect.Method;
import java.net.Socket;
import java.net.URL;
import java.net.URLClassLoader;
import java.net.URLConnection;
import java.security.AllPermission;
import java.security.CodeSource;
import java.security.Permissions;
import java.security.Policy;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.JMenuBar;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;

/**
 *
 * @author  developer
 */
public class RealtimeLauncher extends javax.swing.JApplet {

    private Version v = new Version();
    private String REALTIME_HOME = avoir.realtime.tcp.launcher.Constants.getRealtimeHome();
    private String internalVer = REALTIME_HOME + "/lib/" + v.version;
    int plVer = 0;
    private String[][] plugins = {
        {"jspeex.jar", "Audio Codec"},
        {"realtime-base-0.1.jar", "Realtime Base",},
        {"realtime-whiteboard-0.1.jar", "Whiteboard",},
        {"realtime-pluginmanager-0.1.jar", "Plugin Manager"},
    };
    private String[] sounds = {
        "test.wav",
    };
    public RealtimePlugin pl;
    private static final Class[] parameters = new Class[]{URL.class};
    private TCPConnector tcpConnector;
    private SSLHttpTunnellingClient sslClient;
    private String userName = "Anonymous";
    private String fullName = "Anonymous";
    private String sessionId = "xx";
    private String sessionTitle = "xx";
    private String slidesDir = "";
    private String slideServerId = "";
    private String resourcesPath = "";
    private String appletCodeBase = "";
    private String siteRoot = "";
    private String host;
    private boolean isPresenter = false;
    private Launcher launcher;
    private boolean localhost = false;
    private String userLevel = "";
    private NetworkClassLoader classLoader;
    private Timer pluginMonitor = new Timer();
    private boolean pluginDownloaded = false;

    /** Initializes the applet RealtimeLauncher */
    @Override
    public void init() {
        try {
            java.awt.EventQueue.invokeAndWait(new Runnable() {

                public void run() {
                    initSecurity();
                    initComponents();
                    initCustomComponents();
                    pb.setIndeterminate(true);
                    initSystemLoad();

                }
            });
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public int getPluginsNumber() {
        return plugins.length;
    }

    /**
     * Even though this applet is signed, it is not the main application. we need to
     * set security permissions so that when it invokes the main application
     * through reflection, we dont get hit by the security manager
     */
    private void initSecurity() {
        Policy.setPolicy(new Policy() {

            @Override
            public Permissions getPermissions(CodeSource codesource) {
                Permissions perms = new Permissions();
                perms.add(new AllPermission());
                return perms;
            }

            @Override
            public void refresh() {
                refresh();
            }
        });
    }

    private void initCustomComponents() {

        classLoader = new NetworkClassLoader(this.getClass().getClassLoader());
    }

    public void setPluginDownloaded(boolean state) {
        pluginDownloaded = state;
    }

    private void initSystemLoad() {
        Thread t = new Thread() {

            public void run() {
                forceUpgrade();
                loadSystem();
            }
        };
        t.start();
    }

    private void forceUpgrade() {
        // if (!new File(internalVer).exists()) {
        clearLocalLib();
    //}
    }

    /**
     * Load the actual system, the system is basically made of the 
     * components
     */
    private void loadSystem() {
        RealtimeOptions.init();
        try {
            new File(internalVer).createNewFile();
        } catch (Exception ignore) {
        }
        tcpConnector = new TCPConnector(this);
        initUser();
        //for non proxy connections...just connect direct
        if (RealtimeOptions.useDirectConnection()) {


            pb.setIndeterminate(true);

            if (tcpConnector.connect()) {

                setText("Connected...initializing system load...", false);
                tcpConnector.publish(launcher);
                checkPlugins();

            } else {
                setText("Error connecting to server.", true);
            }
        }
        //here we use manual proxy..and do the tunnelling through the proxy
        if (RealtimeOptions.useManualProxy()) {
            sslClient = new SSLHttpTunnellingClient(RealtimeOptions.getProxyHost(),
                    RealtimeOptions.getProxyPort(), this);
        }

    }

    public Launcher getLauncher() {
        return launcher;
    }

    private void initUser() {
        userName = getParameter("userName");
        fullName = getParameter("fullName");
        sessionId = getParameter("sessionId");
        sessionTitle = getParameter("sessionTitle");
        slidesDir = getParameter("slidesDir");
        siteRoot = getParameter("siteRoot");
        appletCodeBase = getParameter("appletCodeBase");
        resourcesPath = getParameter("resourcesPath");
        slideServerId = getParameter("slideServerId");
        userLevel = getParameter("userLevel");
        isPresenter = new Boolean(getParameter("isSessionPresenter")).booleanValue();
        localhost = new Boolean(getParameter("isLocalhost")).booleanValue();

        //if not logged in, then assign random number
        if (userName == null || userName.startsWith("Language")) { //not logging on through KEWL for dev, so give random user name

            userName = "Guest" + new java.util.Random().nextInt(200);
            fullName = userName;
        }
        //the host from which this applet originated
        host = getCodeBase().getHost();

        launcher = new Launcher(userName, host, sessionId, sessionTitle, slidesDir, localhost, slideServerId);
        //start the slide server ..
        try {
            //  URLConnection connection = new URL(siteRoot + "/index.php?module=webpresent&action=runslideserver&slideServerId=" + slideServerId).openConnection();
            //  connection.connect();

            Socket socket = new Socket(host, 80);
            OutputStream os = socket.getOutputStream();
            boolean autoflush = true;

            PrintWriter out = new PrintWriter(socket.getOutputStream(), autoflush);
             BufferedReader in = new BufferedReader(
                    new InputStreamReader(socket.getInputStream()));

            out.println("GET " + "/chisimba/app/index.php?module=webpresent&action=runslideserver&slideServerId=" + slideServerId + " HTTP/1.1");
            out.println("Host: " + host + ":80");
            out.println("Connection: Close");
            out.println();
// read the response
            boolean loop = true;
            StringBuffer sb = new StringBuffer(8096);

            while (loop) {
                if (in.ready()) {
                    int i = 0;
                    while (i != -1) {
                        i = in.read();
                        sb.append((char) i);
                    }
                    loop = false;
                }
                Thread.currentThread().sleep(50);
            }

// display the response to the out console
            System.out.println(sb.toString());
            socket.close();


        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public boolean isLocalhost() {
        return localhost;
    }

    public void checkPlugins() {
        boolean pluginsOK = true;
        for (int i = 0; i < sounds.length; i++) {
            File f = new File(REALTIME_HOME + "/sounds/" + sounds[i]);
            if (!f.exists()) {
                requestForSounds();
                break;
            }
        }
        for (int i = 0; i < plugins.length; i++) {
            File f = new File(REALTIME_HOME + "/lib/" + plugins[i][0]);
            if (!f.exists()) {
                pluginsOK = false;
                break;
            }
        }
        if (!pluginsOK) {
            System.out.println("One of plugins missing, requesting for download");
            requestForPlugins();
        } else {
            loadAllPlugins();

        }

    }

    public JProgressBar getPb() {
        return pb;
    }

    private void requestForSounds() {
        for (int i = 0; i < sounds.length; i++) {
            requestForResource("sounds/" + sounds[i], "sounds");
        }
    }

    private void requestForPlugins() {
        for (int i = 0; i < plugins.length; i++) {
            requestForResource(plugins[i][0], plugins[i][1]);
            //got to monitor this one for 1 full minute.
            if (i == plugins.length - 1) {
                try {
                    pluginMonitor.schedule(new PluginRequestMonitor(), 60 * 1000);
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }
    }

    public void setText(String txt, boolean error) {
        if (error) {
            infoField.setForeground(Color.RED);
            showOptionsReconnectPanel();
        } else {
            infoField.setForeground(Color.BLACK);
        }
        infoField.setText(txt);
    }

    private void requestForResource(String filename, String desc) {
        String filepath = resourcesPath + "/" + filename;
        ModuleFileRequestPacket p =
                new ModuleFileRequestPacket(null, filename, filepath,
                slideServerId, userName);
        p.setDesc(desc);
        tcpConnector.sendPacket(p);

    }

    public static void main(String[] args) {
        new RealtimeLauncher().init();
    }

    @Override
    public void destroy() {
        if (pl != null) {
            pl.removeUser(userName, sessionId);
        }
    }

    public static void addFile(String s) throws IOException {
        File f = new File(s);
        addFile(f);
    }// end method

    public static void addFile(File f) throws IOException {
        addURL(f.toURI().toURL());
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

    public void loadAllPlugins() {
        try {
            String[] xplugins = {
                "realtime-whiteboard-0.1.jar",
                "realtime-base-0.1.jar",
                "realtime-pluginmanager-0.1.jar",
                "jspeex.jar",
            };
            for (int n = 0; n < xplugins.length; n++) {
                classLoader.addURL(new URL("file://" + REALTIME_HOME + "/lib/" + xplugins[n]));
            }
            Class cl = classLoader.loadClass("avoir.realtime.tcp.pluginmanager.PluginManager", true);
            pl = (RealtimePlugin) cl.newInstance();
            mainPanel.removeAll();
            setText("", false);
            pl.setSessionTitle(sessionTitle);
            pl.setApplectCodeBase(appletCodeBase);
            pl.setGlassPaneHandler(this);
            JPanel basePanel = pl.createBase(
                    userLevel,
                    fullName,
                    userName,
                    host, 22225,
                    isPresenter,
                    sessionId,
                    slidesDir,
                    localhost,
                    siteRoot,
                    slideServerId,
                    resourcesPath);
            mainPanel.add(basePanel, BorderLayout.CENTER);
            this.resize(getWidth() + 5, getHeight() + 5);
            JMenuBar menuBar = pl.getMenuBar();
            setJMenuBar(menuBar);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(null, "There was an error when loading plugins." +
                    " Restart your browser to fix this.");
            setText("Error loading plugins, refresh browser page", true);
        }
    }

    private void showOptionsFrame() {

        avoir.realtime.tcp.launcher.RealtimeOptionsFrame optionsFrame = new avoir.realtime.tcp.launcher.RealtimeOptionsFrame(RealtimeLauncher.this);
        optionsFrame.setSize(400, 300);
        optionsFrame.setLocationRelativeTo(null);
        optionsFrame.setVisible(true);
    }

    private void showOptionsReconnectPanel() {
        retryButton.setEnabled(true);
        optionsButton.setEnabled(true);
        pb.setIndeterminate(false);
        java.awt.GridBagConstraints gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        introPanel.add(retryPanel, gridBagConstraints);
    }

    public TCPConnector getTcpConnector() {
        return tcpConnector;
    }

    private void clearLocalLib() {
        String cache = avoir.realtime.tcp.launcher.Constants.getRealtimeHome() + "/lib/";
        java.io.File f = new java.io.File(cache);
        String[] list = f.list();
        if (list != null) {
            //loop through files and delete everything in it
            for (int i = 0; i < list.length; i++) {
                try {
                    java.io.File session = new java.io.File(cache + "/" + list[i]);
                    String[] imgs = session.list();
                    if (imgs != null) {
                        for (int j = 0; j < imgs.length; j++) {
                            java.io.File img = new java.io.File(session.getAbsolutePath() + "/" + imgs[j]);
                            img.delete();
                        }
                    }
                    session.delete();
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }
    }

    class PluginRequestMonitor extends TimerTask {

        public void run() {
            if (!pluginDownloaded) {
                setText("Error: Server taking too long to reply.", true);
                JOptionPane.showMessageDialog(null, "The server is taking too long to respond.\n" +
                        "To resolve this, please restart the browser.");

            }
            pluginMonitor.cancel(); //Terminate the thread
        }
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

        retryPanel = new javax.swing.JPanel();
        retryButton = new javax.swing.JButton();
        optionsButton = new javax.swing.JButton();
        mainPanel = new javax.swing.JPanel();
        introPanel = new javax.swing.JPanel();
        infoField = new javax.swing.JLabel();
        pb = new javax.swing.JProgressBar();

        retryButton.setText("Retry");
        retryButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                retryButtonActionPerformed(evt);
            }
        });
        retryPanel.add(retryButton);

        optionsButton.setText("Configure");
        optionsButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsButtonActionPerformed(evt);
            }
        });
        retryPanel.add(optionsButton);

        mainPanel.setLayout(new java.awt.BorderLayout());

        introPanel.setBackground(new java.awt.Color(255, 255, 255));
        introPanel.setLayout(new java.awt.GridBagLayout());

        infoField.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        infoField.setText("Loading...Please wait");
        introPanel.add(infoField, new java.awt.GridBagConstraints());

        pb.setBackground(new java.awt.Color(255, 255, 255));
        pb.setIndeterminate(true);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        introPanel.add(pb, gridBagConstraints);

        mainPanel.add(introPanel, java.awt.BorderLayout.CENTER);

        getContentPane().add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

private void retryButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_retryButtonActionPerformed
    retryButton.setEnabled(false);
    optionsButton.setEnabled(false);
    pb.setIndeterminate(true);
    initSystemLoad();
}//GEN-LAST:event_retryButtonActionPerformed

private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_optionsButtonActionPerformed

    showOptionsFrame();
}//GEN-LAST:event_optionsButtonActionPerformed
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JLabel infoField;
    private javax.swing.JPanel introPanel;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JButton optionsButton;
    private javax.swing.JProgressBar pb;
    private javax.swing.JButton retryButton;
    private javax.swing.JPanel retryPanel;
    // End of variables declaration//GEN-END:variables
}
