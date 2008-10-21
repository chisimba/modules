/*
 * RealtimeLauncher.java
 * This is a light weight class that launches the main application bundled in a
 * different jar. This is accomplished using some fairly advanced reflection API
 * It can be invoked both as an application  or as an applet. 
 * Created on 18 May 2008, 05:41
 */
package avoir.realtime.tcp.launcher;

import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;
import avoir.realtime.tcp.launcher.Launcher;
import avoir.realtime.tcp.launcher.packet.VersionRequestPacket;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.lang.reflect.Method;
import java.net.Socket;
import java.net.URL;
import java.net.URLClassLoader;
import java.security.AllPermission;
import java.security.CodeSource;
import java.security.Permissions;
import java.security.Policy;
import java.util.Map;
import java.util.Properties;
import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;
import java.util.jar.Attributes;
import java.util.jar.JarFile;
import java.util.jar.Manifest;
import javax.swing.JFrame;
import javax.swing.JMenuBar;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;

/**
 *
 * @author  David Wafula
 */
public class RealtimeLauncher extends javax.swing.JApplet {

    private Map<String, Integer> currentVersions;
    private static String versionPropsFilename = "version.properties";
    private Version v = new Version();
    private String REALTIME_HOME = avoir.realtime.tcp.launcher.Constants.getRealtimeHome();
    private String internalVer = REALTIME_HOME + "/lib/realtime_" + v.version;
    private int MODE = Constants.APPLET;
    int plVer = 0;
    private String version = "1.0.2";
    private String swt = getPlatformSpecificSWT();
    private Vector<Plugin> staticPluginsRequired = new Vector<Plugin>();
    private Vector<Plugin> corePluginsRequired = new Vector<Plugin>();
    private String[][] staticPlugins = {
        {"jspeex.jar", "Audio Codec"},
        {"jna.jar", "JNA",},
        {"DJNativeSwing-" + version + ".jar", "Native Swing",},
        {swt, "SWT",},
    };
    private String[][] corePlugins = {
        {"realtime-base-" + version + ".jar", "Realtime Base",},
        {"realtime-whiteboard-" + version + ".jar", "Whiteboard",},
        {"realtime-flashplayer-" + version + ".jar", "Flash Player",},
        {"realtime-filetransfer-" + version + ".jar", "File Transfer",},
        {"realtime-appsharing-" + version + ".jar", "Application Sharing",},
        {"realtime-audio-" + version + ".jar", "Audio",},
        {"realtime-chat-" + version + ".jar", "Chat",},
        {"realtime-survey-" + version + ".jar", "Survey",},
        {"realtime-common-" + version + ".jar", "Common",},
        {"realtime-admin-" + version + ".jar", "Admin",},
        {"realtime-managers-" + version + ".jar", "Managers",},
        {"realtime-standalone-" + version + ".jar", "Standalone",},
        {"realtime-user-" + version + ".jar", "User",},
        {"realtime-images.jar", "Images",},
        {"realtime-pluginmanager-" + version + ".jar", "Plugin Manager"},
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
    private String resourcesPath = "../";
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
    private String supernodeHost = "196.21.45.85";
    private int supernodePort = 80;
    private Timer monitor;
    private JFrame mainFrame = new JFrame("Chisimba Realtime Virtual Classroom");
    private String userDetails;
    private String userImagePath;
    private static Properties props;

    /** Initializes the applet RealtimeLauncher */
    @Override
    public void init() {

        try {
            java.awt.EventQueue.invokeAndWait(new Runnable() {

                public void run() {
                    MODE = Constants.APPLET;
                    initSecurity();
                    initComponents();
                    initCustomComponents();
                    mainPb.setIndeterminate(true);
                    initSystemLoad();

                }
            });
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public String[][] getCorePlugins() {
        return corePlugins;
    }

    public Map<String, Integer> getCurrentVersions() {
        return currentVersions;
    }

    public void setCurrentVersions(Map<String, Integer> currentVersions) {
        this.currentVersions = currentVersions;
        checkPluginsAndResources();
    }

    /**
     * Relying on swt for flash player, embedded web, media player etc. But they
     * are platform specific. Need to get hold of the one for MAC
     * @return
     */
    private String getPlatformSpecificSWT() {
        String filename = "swt.jar";
        String osName = System.getProperty("os.name");
        if (osName.toUpperCase().startsWith("LINUX")) {
            filename = "swt-linux.jar";
        }
        if (osName.toUpperCase().startsWith("WINDOWS")) {
            filename = "swt-3.5M1-win32-win32-x86.jar";
        }
        return filename;
    }

    /**
     * How many core plugins do we need?
     * @return
     */
    public int getCorePluginsNumber() {
        return corePlugins.length;
    }

    public JProgressBar getMinorPb() {
        return minorPb;
    }

    public String getREALTIME_HOME() {
        return REALTIME_HOME;
    }

    public int getJarVersionNo(String jarFileName) {
        try {

            JarFile jar = new JarFile(jarFileName);

            final Manifest manifest = jar.getManifest();
            final Attributes mattr = manifest.getMainAttributes();
            String verAttr = mattr.getValue("JarVersion");

            if (verAttr != null) {
                String[] toks = verAttr.split(",");
                int v = 0;
                for (int i = 0; i < toks.length; i++) {
                    int c = Integer.parseInt(toks[i]);
                    if (i > 0) {
                        c = c * 100;
                    }
                    v += c;
                }
                return v;
            }
        } catch (Exception ex) {
            //igonre this, means we must request for new one anyway
            //ex.printStackTrace();
        }
        return -1;
    }

    /**
     * Even though this applet is signed, it is not the main application. we need to
     * set security permissions so that when it invokes the main application
     * through reflection, we dont get hit by the security manager
     */
    private void initSecurity() {
        Policy.setPolicy(new Policy() {

            @Override
            public Permissions getPermissions(
                    CodeSource codesource) {
                Permissions perms = new Permissions();


                perms.add(
                        new AllPermission());
                return perms;
            }

            @Override
            public void refresh() {
                refresh();
            }
        });
    }

    /**
     * initialize network launcher
     */
    private void initCustomComponents() {

        classLoader = new NetworkClassLoader(this.getClass().getClassLoader());
    }

    /**
     * update plugin download status
     * @param state
     */
    public void setPluginDownloaded(boolean state) {
        pluginDownloaded = state;
    }

    /**
     * initialize the system load
     */
    private void initSystemLoad() {
        Thread t = new Thread() {

            public void run() {
                loadSystem();

            }
        };
        t.start();
    }

    private static void write(String filename, String txt) {
        try {
            FileWriter outFile = new FileWriter(filename, true);
            PrintWriter printWriter = new PrintWriter(outFile);
            printWriter.println(txt);
            printWriter.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Load the actual system, the system is basically made of the 
     * components
     */
    private void loadSystem() {

        try {
            for (int i = 0; i < corePlugins.length; i++) {
                write(internalVer, corePlugins[i] + "=" + v);
            }
        } catch (Exception ignore) {
        }
        if (MODE == Constants.APPLET) {
            supernodeHost = getParameter("supernodeHost");

            try {
                supernodePort = Integer.parseInt(getParameter("supernodePort").trim());
            } catch (NumberFormatException ex) {
                JOptionPane.showMessageDialog(this, "Invalid supernode port: " + getParameter("supernodePort") + ". Using default 80",
                        "Invalid super node Port", JOptionPane.ERROR_MESSAGE);
            }
        }
        tcpConnector = new TCPConnector(this);
        tcpConnector.setSuperNodeHost(supernodeHost);
        tcpConnector.setSuperNodePort(supernodePort);
        if (MODE == Constants.APPLET) {
            initUser();
        } else {
            launcher = new Launcher(userName, host, sessionId, sessionTitle, slidesDir, localhost, slideServerId);

        }
        //for non proxy connections...just connect direct
        if (RealtimeOptions.useDirectConnection()) {
            mainPb.setIndeterminate(true);
            if (tcpConnector.connect()) {

                setText("Connected...initializing system load...", false);
                tcpConnector.publish(launcher);
                //request for latest versions of the core plugins
                tcpConnector.sendPacket(new VersionRequestPacket(corePlugins));
            } else {
                setText("Error connecting to server.", true);
            }
        }
        //here we use manual proxy..and do the tunnelling through the proxy
        if (RealtimeOptions.useManualProxy()) {
            sslClient = new SSLHttpTunnellingClient(RealtimeOptions.getProxyHost(),
                    RealtimeOptions.getProxyPort(), this, supernodeHost, supernodePort);

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

            Socket socket = new Socket(host, supernodePort);
            OutputStream os = socket.getOutputStream();
            boolean autoflush = true;

            PrintWriter out = new PrintWriter(socket.getOutputStream(), autoflush);
            BufferedReader in = new BufferedReader(
                    new InputStreamReader(socket.getInputStream()));

            out.println("GET " + "/app/index.php?module=webpresent&action=runslideserver&slideServerId=" + slideServerId + " HTTP/1.1");
            out.println("Host: " + host + ":" + supernodePort + "");
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
            //  System.out.println(sb.toString());
            socket.close();


        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    class Plugin {

        private String filename;
        private String desc;

        public Plugin(String filename, String desc) {
            this.filename = filename;
            this.desc = desc;
        }

        public String getDesc() {
            return desc;
        }

        public void setDesc(String desc) {
            this.desc = desc;
        }

        public String getFilename() {
            return filename;
        }

        public void setFilename(String filename) {
            this.filename = filename;
        }
    }

    /**
     * Need to use the application properties, so initilize them
     */
    public static void loadProperties() {
        try {

            // create and load default properties
            Properties defaultProps = new Properties();
            FileInputStream in = new FileInputStream(versionPropsFilename);
            defaultProps.load(in);
            in.close();

            // create program properties with default
            props = new Properties(defaultProps);

            // now load properties from last invocation
            in = new FileInputStream(versionPropsFilename);
            props.load(in);
            in.close();

        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    /**
     * Are we local host? For debugging purposes
     * @return
     */
    public boolean isLocalhost() {
        return localhost;
    }

    /**
     * Check to see if we have all the plugins needed and thier associated
     * resources. And if the versions are up to date
     */
    public void checkPluginsAndResources() {
        boolean corePluginsOK = true;
        for (int i = 0; i < sounds.length; i++) {
            File f = new File(REALTIME_HOME + "/sounds/" + sounds[i]);
            if (!f.exists()) {
                requestForSounds();
                break;
            }
        }
        for (int i = 0; i < currentVersions.size(); i++) {


            int prevVer = getJarVersionNo(REALTIME_HOME + "/lib/" + corePlugins[i][0]);
            int currVer = currentVersions.get(corePlugins[i][0]).intValue();
            if (prevVer < currVer || currVer == -1) {
                corePluginsOK = false;
                System.out.println(corePlugins[i][0] + ": curr version: " + prevVer + " new ver: " + currVer);
                System.out.println("Update required");
                corePluginsRequired.add(new Plugin(corePlugins[i][0], corePlugins[i][1]));
            }
        }

        for (int i = 0; i < staticPlugins.length; i++) {
            File f = new File(REALTIME_HOME + "/lib/" + staticPlugins[i][0]);
            if (!f.exists()) {
                staticPluginsRequired.add(new Plugin(staticPlugins[i][0], staticPlugins[i][1]));
            }
        }

        for (int i = 0; i < staticPluginsRequired.size(); i++) {
            System.out.println(staticPluginsRequired.elementAt(i) + " not found. Requesting for donwload ...");
            requestForResource("../lib/" + staticPluginsRequired.elementAt(i).getFilename(), staticPluginsRequired.elementAt(i).getDesc());
        }

        if (corePluginsOK) {
            loadAllPlugins();
        }else{
            requestForCorePlugins();
        }


    }

    /**
     * Get the progress bar instance
     * @return
     */
    public JProgressBar getPb() {
        return mainPb;
    }

    /**
     * We dont have sound files..so request 
     */
    private void requestForSounds() {
        for (int i = 0; i < sounds.length; i++) {
            String path = "../sounds/" + sounds[i];

            requestForResource(path, "sounds");
        }
    }

    public int getStaticPluginsNo() {
        return staticPluginsRequired.size();
    }

    public int getCorePluginsNo() {
        return corePluginsRequired.size();
    }

    /**
     * Request for the plugins. These are basically jar files
     */
    private void requestForCorePlugins() {


        for (int i = 0; i < corePluginsRequired.size(); i++) {
            setText("Requesting for " + corePluginsRequired.elementAt(i).getDesc() + " ...", false);
            String path = "../lib/" + corePluginsRequired.elementAt(i).getFilename();
            System.out.println(i + " of " + corePluginsRequired.size() + " Requesting for " + corePluginsRequired.elementAt(i).getDesc() + " ...");
            requestForResource(path, corePluginsRequired.elementAt(i).getDesc());
            //got to monitor this one for 1 full minute.
            if (i == corePlugins.length - 1) {
                try {
                    pluginMonitor.schedule(new PluginRequestMonitor(), 60 * 1000 * 30);
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }
    }

    /**
     * Display text. If is error, paint it in RED and show the reconnect
     * panel, else black
     * @param txt
     * @param error
     */
    public void setText(String txt, boolean error) {
        if (error) {
            infoField.setForeground(Color.RED);
            showOptionsReconnectPanel();
        } else {
            infoField.setForeground(Color.BLACK);
        }
        infoField.setText(txt);
    }

    /**
     * Here we actually send a real request to the server
     * @param filename
     * @param desc
     */
    private void requestForResource(String filename, String desc) {

        String filepath = filename;

        ModuleFileRequestPacket p =
                new ModuleFileRequestPacket(null, filename, filepath,
                slideServerId, userName);
        p.setDesc(desc);
        tcpConnector.sendPacket(p);
    // monitor = new java.util.Timer();
    // monitor.schedule(new BinFileRequestMonitor(), 10 * 1000);

    }

    /**
     * Monitor the status of a resource request
     */
    class BinFileRequestMonitor extends TimerTask {

        public void run() {
            if (!tcpConnector.isBinRequestReplied()) {
                setText("Error: Server taking too long to reply.", true);
            }
            monitor.cancel(); //Terminate the thread
        }
    }

    /**
     * Launch this as an application. Not as an applet. Now, when launched as
     * an application, the whiteboard is set as the default surface. But if
     * launched as applet (webPresent mode), then the slide surface is the default
     * surface.
     * @param host
     * @param port
     */
    private void launchAsApplication(String host, int port, String username,
            String fullnames,
            boolean isPresenter,
            String sessionId,
            String sessionTitle,
            String userDetails,
            String userImagePath,
            String isLoggedIn,
            String siteRoot,
            String resourcesPath,
            String userLevel,
            String chatLogPath,
            String filePath,
            String slideServerId) {

        this.userName = username;
        this.fullName = fullnames;
        this.isPresenter = isPresenter;
        this.sessionTitle = sessionTitle;
        this.sessionId = sessionId;
        this.userDetails = userDetails;
        this.userImagePath = userImagePath;
        this.siteRoot = siteRoot;
        this.resourcesPath = resourcesPath;
        this.userLevel = userLevel;
        this.slidesDir = filePath;
        this.slideServerId = slideServerId;
        MODE = Constants.WEBSTART;
        supernodeHost = host;
        supernodePort = port;
        RealtimeOptions.init(corePlugins, staticPlugins, v.getVersion());

        initSecurity();
        initComponents();
        initCustomComponents();

        mainFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        //JWindow mainFrame = new JWindow();
        mainFrame.setContentPane(mainPanel);
        mainFrame.setSize(400, 300);
        mainFrame.setLocationRelativeTo(null);
        mainFrame.setVisible(true);

        mainPb.setIndeterminate(true);
        initSystemLoad();

    }

    /**
     * If run as application
     * @param args
     */
    public static void main(String[] args) {
        //JFrame.setDefaultLookAndFeelDecorated(true);
        //JDialog.setDefaultLookAndFeelDecorated(true);
        int port = 80;
        String host = "196.21.45.85";
        try {
            host = args[0];
            port = Integer.parseInt(args[1]);

        } catch (NumberFormatException ex) {
            JOptionPane.showMessageDialog(null, "Invalid Supernode Port");
            port = 80;
            host = "196.21.45.85";
        }

        new RealtimeLauncher().launchAsApplication(host, port,
                args[2], args[3], new Boolean(args[4]), args[5], args[6], args[7],
                args[8], args[9], args[10], args[11], args[12], args[13], args[14], args[15]);
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

    /**
     * Add a URL to the network classloader path
     * @param u
     * @throws java.io.IOException
     */
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

    /**
     * Means we have all required plugins..so load all of them
     */
    public void loadAllPlugins() {

        try {
            String[] xplugins = {
                "realtime-images.jar",
                "jna.jar",
                "DJNativeSwing-" + version + ".jar",
                swt,
                "realtime-admin-" + version + ".jar",
                "realtime-managers-" + version + ".jar",
                "realtime-standalone-" + version + ".jar",
                "realtime-flashplayer-" + version + ".jar",
                "realtime-user-" + version + ".jar",
                "realtime-common-" + version + ".jar",
                "realtime-survey-" + version + ".jar",
                "realtime-chat-" + version + ".jar",
                "realtime-audio-" + version + ".jar",
                "realtime-appsharing-" + version + ".jar",
                "realtime-filetransfer-" + version + ".jar",
                "realtime-whiteboard-" + version + ".jar",
                "realtime-base-" + version + ".jar",
                "realtime-pluginmanager-" + version + ".jar",
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
            pl.setUserDetails(userDetails);
            pl.setUserImagePath(userImagePath);
            if (MODE == Constants.APPLET) {
                JPanel basePanel = pl.createBase(
                        userLevel,
                        fullName,
                        userName,
                        supernodeHost, supernodePort,
                        isPresenter,
                        sessionId,
                        slidesDir,
                        localhost,
                        siteRoot,
                        slideServerId,
                        resourcesPath, getAppletContext());
                mainPanel.add(basePanel, BorderLayout.CENTER);
                JMenuBar menuBar = pl.getMenuBar();
                setJMenuBar(menuBar);
                this.resize(getWidth() + 5, getHeight() + 5);

            } else {
                Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
                JPanel basePanel = pl.createClassroomBase(
                        supernodeHost, supernodePort, MODE, userName,
                        fullName,
                        isPresenter,
                        sessionId, userLevel,
                        slidesDir,
                        siteRoot,
                        slideServerId,
                        resourcesPath,
                        mainFrame);
                JMenuBar menuBar = pl.getMenuBar();


                mainPanel.add(basePanel, BorderLayout.CENTER);
                mainFrame.setJMenuBar(menuBar);

                mainFrame.setSize((ss.width / 8) * 8, (ss.height / 8) * 8);
                mainFrame.setLocationRelativeTo(null);
            }

        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(null, "There was an error when loading plugins." +
                    " Restart your browser to fix this.");
            setText("Error loading plugins, refresh browser page", true);
        }
    }

    /**
     * Show options frmae
     */
    private void showOptionsFrame() {

        avoir.realtime.tcp.launcher.RealtimeOptionsFrame optionsFrame = new avoir.realtime.tcp.launcher.RealtimeOptionsFrame(RealtimeLauncher.this);
        optionsFrame.setSize(400, 300);
        optionsFrame.setLocationRelativeTo(null);
        optionsFrame.setVisible(true);
    }

    /**
     * Displays a panel that allows user to reconnect
     */
    private void showOptionsReconnectPanel() {
        retryButton.setEnabled(true);
        optionsButton.setEnabled(true);
        mainPb.setIndeterminate(false);
        java.awt.GridBagConstraints gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;

        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        introPanel.add(retryPanel, gridBagConstraints);
    }

    /**
     * Get TCP Connect instance
     * @return
     */
    public TCPConnector getTcpConnector() {
        return tcpConnector;
    }

    /**
     * Ideally delete the files in the local cache
     */
    private void clearCoreLib() {
        String cache = avoir.realtime.tcp.launcher.Constants.getRealtimeHome() + "/lib/";
        java.io.File f = new java.io.File(cache);
        String[] list = f.list();
        if (list != null) {
            //loop through files and delete everything in it that is core
            for (int i = 0; i < list.length; i++) {
                try {
                    java.io.File libFile = new java.io.File(cache + "/" + list[i]);
                    String[] imgs = libFile.list();
                    if (imgs != null) {
                        for (int j = 0; j < imgs.length; j++) {
                            java.io.File img = new java.io.File(libFile.getAbsolutePath() + "/" + imgs[j]);
                            img.delete();
                        }
                    }
                    if (libFile.getName().startsWith("realtime")) {
                        libFile.delete();

                    }
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
        mainPb = new javax.swing.JProgressBar();
        minorPb = new javax.swing.JProgressBar();
        jLabel1 = new javax.swing.JLabel();

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
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        introPanel.add(infoField, gridBagConstraints);

        mainPb.setBackground(new java.awt.Color(255, 255, 255));
        mainPb.setIndeterminate(true);
        mainPb.setPreferredSize(new java.awt.Dimension(148, 15));
        mainPb.setStringPainted(true);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        introPanel.add(mainPb, gridBagConstraints);

        minorPb.setPreferredSize(new java.awt.Dimension(248, 15));
        minorPb.setStringPainted(true);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(2, 0, 0, 0);
        introPanel.add(minorPb, gridBagConstraints);

        jLabel1.setText("Overall Progress");
        introPanel.add(jLabel1, new java.awt.GridBagConstraints());

        mainPanel.add(introPanel, java.awt.BorderLayout.CENTER);

        getContentPane().add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

private void retryButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_retryButtonActionPerformed
    retryButton.setEnabled(false);
    optionsButton.setEnabled(false);
    mainPb.setIndeterminate(true);
    initSystemLoad();
}//GEN-LAST:event_retryButtonActionPerformed

private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_optionsButtonActionPerformed

    showOptionsFrame();
}//GEN-LAST:event_optionsButtonActionPerformed
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JLabel infoField;
    private javax.swing.JPanel introPanel;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JProgressBar mainPb;
    private javax.swing.JProgressBar minorPb;
    private javax.swing.JButton optionsButton;
    private javax.swing.JButton retryButton;
    private javax.swing.JPanel retryPanel;
    // End of variables declaration//GEN-END:variables
}
