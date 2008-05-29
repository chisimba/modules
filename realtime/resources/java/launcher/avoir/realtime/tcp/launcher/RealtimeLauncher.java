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
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;
import java.lang.reflect.Method;
import java.net.URL;
import java.net.URLClassLoader;
import java.security.AllPermission;
import java.security.CodeSource;
import java.security.Permissions;
import java.security.Policy;
import java.util.Enumeration;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;

/**
 *
 * @author  developer
 */
public class RealtimeLauncher extends javax.swing.JApplet {

    private JMenu toolsMenu = new JMenu("Tools");
    private JMenuItem optionsMenuItem = new JMenuItem("Options");
    private Version v = new Version();
    int plVer = 0;
    private String[] plugins = {
        "jspeex.jar",
        "realtime-base-0.1.jar",
        "realtime-pluginmanager-0.1.jar",
    };
    public RealtimePlugin pl;
    private final String REALTIME_HOME = System.getProperty("user.home") + "/avoir-realtime-0.1/";
    private String title = "<font color=\"orange\">" +
            "<h1>Chisimba Realtime Communication Tools</h1></font><br>";
    private String owner = "University of Western Cape<br>" +
            "Free Software Innovation Unit<br>";
    private String cc = "<b>(c) 2008 AVOIR<br></b><br>";
    private String aboutText = "<html><center>" +
            title +
            owner +
            cc +
            // status +
            "</center>";
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
    private String siteRoot = "";
    private String host;
    private boolean isPresenter = false;
    private Launcher launcher;
    private boolean localhost = false;
    private JMenuItem aboutMenuItem = new JMenuItem("About");
    private String userLevel = "";
    private NetworkClassLoader classLoader;

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
        Enumeration en = classLoader.getURLs();
        while (en.hasMoreElements()) {
            System.out.println(en.nextElement());
        }
        aboutMenuItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                JOptionPane.showMessageDialog(null, aboutText + "<br><center>Version 0.1" +
                        " beta Build " + v.getVersion() + ", Plugin Build " + plVer + "</center>");

            }
        });

        optionsMenuItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                showOptionsFrame();
            }
        });
        toolsMenu.add(optionsMenuItem);
        menuBar.add(toolsMenu);

        aboutMenu.add(aboutMenuItem);
    }

    private void initSystemLoad() {
        Thread t = new Thread() {

            public void run() {
                loadSystem();
            }
        };
        t.start();
    }

    /**
     * Load the actual system, the system is basically made of the 
     * components
     */
    private void loadSystem() {
        RealtimeOptions.init();
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
    }

    public boolean isLocalhost() {
        return localhost;
    }

    public void checkPlugins() {


        boolean pluginsOK = true;
        for (int i = 0; i < plugins.length; i++) {
            File f = new File(REALTIME_HOME + "/lib/" + plugins[i]);
            if (!f.exists()) {
                pluginsOK = false;
                break;
            }
        }
        if (!pluginsOK) {
            System.out.println("One of plugins missing, requesting for download");
            requestForPlugins();
        } else {
            if (latestVersion(plugins)) {
                setText("Loading system ...", false);
                loadAllPlugins();
            } else {
                setText("Downloading latest plugins ...", false);
                requestForPlugins();
            }
        }

    }

    public JProgressBar getPb() {
        return pb;
    }

    private void requestForPlugins() {
        for (int i = 0; i < plugins.length; i++) {
            requestForPlugin(plugins[i]);

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

    private void requestForPlugin(String filename) {
        String filepath = resourcesPath + "/" + filename;
        ModuleFileRequestPacket p =
                new ModuleFileRequestPacket(null, filename, filepath,
                slideServerId, userName);
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
            this.resize(600, 405);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(null, "There was an error when loading plugins." +
                    " Refresh browser page.");
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
        String cache = System.getProperty("user.home") + "/avoir-realtime-0.1/lib/";
        java.io.File f = new java.io.File(cache);
        String[] list = f.list();
        //loop through files and delete everything in it
        for (int i = 0; i < list.length; i++) {
            System.out.println(list[i]);
            try {
                java.io.File session = new java.io.File(cache + "/" + list[i]);
                String[] imgs = session.list();
                for (int j = 0; j < imgs.length; j++) {
                    java.io.File img = new java.io.File(session.getAbsolutePath() + "/" + imgs[j]);
                    img.delete();
                }
                session.delete();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

    }

    private boolean latestVersion(String[] stringURLs) {
        try {
            //classLoader.clear();
            for (int n = 0; n < stringURLs.length; n++) {
                classLoader.addURL(new URL("file://" + REALTIME_HOME + "/lib/" + stringURLs[n]));
            }
            Class cl = null;
            try {
                cl = classLoader.loadClass("avoir.realtime.tcp.pluginmanager.PluginManager", true);
            } catch (NullPointerException ex) {
                JOptionPane.showMessageDialog(this, "An internal error has occured.\n" +
                        "The system framework is under maintenance now, we apologize for any inconvenience");

                //  JOptionPane.showMessageDialog(this, "Error occured launching the application." +
                //        "\nPlease restart your browswer ti fix this problwm.");
                //setText("Application launch error", true);
                //ex.printStackTrace();
                // return false;
                //clear the cache...even class loader then restart again
                //classLoader.clear();
                clearLocalLib();
                return false;
            }
            RealtimePlugin plugin = (RealtimePlugin) cl.newInstance();
            plVer = plugin.getVersion();
            int baseVer = v.getVersion();
            System.out.println("Launcher Version: " + baseVer);
            System.out.println("Plugin Version: " + plVer);
            boolean latest = false;
            latest = plVer >= baseVer;
            System.out.println("Latest version: " + latest);
            return latest;
        } catch (IOException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        } catch (SecurityException e) {
            e.printStackTrace();
        } catch (IllegalArgumentException e) {
            e.printStackTrace();
        } catch (InstantiationException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }
        System.out.println("Error determining plugin version");

        return false;
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
        menuBar = new javax.swing.JMenuBar();
        aboutMenu = new javax.swing.JMenu();

        retryButton.setText("Retry");
        retryButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                retryButtonActionPerformed(evt);
            }
        });
        retryPanel.add(retryButton);

        optionsButton.setText("Options");
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

        aboutMenu.setText("Help");
        menuBar.add(aboutMenu);

        setJMenuBar(menuBar);
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
    private javax.swing.JMenu aboutMenu;
    private javax.swing.JLabel infoField;
    private javax.swing.JPanel introPanel;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JMenuBar menuBar;
    private javax.swing.JButton optionsButton;
    private javax.swing.JProgressBar pb;
    private javax.swing.JButton retryButton;
    private javax.swing.JPanel retryPanel;
    // End of variables declaration//GEN-END:variables
}
