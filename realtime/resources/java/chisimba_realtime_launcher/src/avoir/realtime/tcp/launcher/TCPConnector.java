/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher;

import avoir.realtime.tcp.launcher.packet.LauncherAckPacket;
import avoir.realtime.tcp.launcher.packet.LauncherMsgPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileReplyPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;
import avoir.realtime.tcp.launcher.packet.LauncherPacket;

import java.io.*;
import java.net.*;


import java.util.Vector;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;



import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSocket;
import javax.net.ssl.SSLSocketFactory;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
 */
public class TCPConnector {

    private String REALTIME_HOME = avoir.realtime.tcp.launcher.Constants.getRealtimeHome();
    private final String JAVA_HOME = System.getProperty("java.home");
    private RealtimePlugin realtimePlugin;
    private boolean running = true;
    private String windowManager = "GNOME";
    private boolean selectWindowManager = true;
    private RealtimeLauncher base;
    /**
     * Reader for the ObjectInputStream
     */
    protected ObjectInputStream reader;
    /**
     * Writer for the ObjectOutputStream
     */
    protected ObjectOutputStream writer;
    private boolean slideServerReplying = false;
   // private String SUPERNODE_HOST = "196.21.45.85";
    //private int SUPERNODE_PORT = 80;
    private String SUPERNODE_HOST = "localhost";
    private int SUPERNODE_PORT = 22225;
    //everything is encrypted here
    private SSLSocketFactory dfactory;
    private SSLSocket socket;
    private int value = 0;

    public TCPConnector() {
    }

    public TCPConnector(RealtimeLauncher base) {
        this.base = base;
    }

    public void setAudioPacketHandler(RealtimePlugin realtimePlugin) {
        this.realtimePlugin = realtimePlugin;
    }

    public void setObjectInputStream(ObjectInputStream in) {
        reader = in;
    }

    public void setObjectOutputStream(ObjectOutputStream out) {
        writer = out;
    }

    /**
     * Resquests user list, but in turn also gets published
     */
    public void publish(Launcher launcher) {
        sendPacket(new LauncherAckPacket(launcher, false));
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

    /**
     * sends a TCP Packet to Super Node
     * @param packet
     */
    public void sendPacket(LauncherPacket packet) {
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

    private byte[] readFile(String filePath) {
        File f = new File(filePath);
        File parentFile = f.getParentFile();
        String filename = parentFile.getName() + "/" + f.getName();//either bin or lib

        try {
            if (f.exists()) {
                FileChannel fc = new FileInputStream(f.getAbsolutePath()).getChannel();

                ByteBuffer buff = ByteBuffer.allocate((int) fc.size());
                fc.read(buff);
                if (buff.hasArray()) {
                    byte[] byteArray = buff.array();
                    fc.close();
                    return byteArray;
                } else {
                    System.out.println("error reading the file");
                    return null;
                }
            } else {
                System.out.println(filename + " doesn't exist.");
                return null;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }

    }

    /**
     * gets the jpg  file names generated from the presentation
     * @param contentPath
     * @param objectOut
     * @return
     */
    public int[] getImageFileNames(String contentPath) {
        File dir = new File(contentPath);
        String[] fileList = dir.list();

        if (fileList != null) {

            java.util.Arrays.sort(fileList);
            Vector newList = new Vector();
            int totalSlides = 0;
            int c = 0;
            for (int i = 0; i < fileList.length; i++) {
                if (fileList[i].endsWith(".jpg")) {
                    newList.addElement(fileList[i]);
                    totalSlides++;
                    c++;
                }
            }
            int[] imgNos = new int[newList.size()];
            for (int i = 0; i < newList.size(); i++) {
                String fn = (String) newList.elementAt(i);

                if (fn != null) {
                    for (int j = 0; j < fn.length(); j++) {
                        if (Character.isDigit(fn.charAt(j))) {
                            int imgNo = Integer.parseInt(fn.substring(fn.indexOf(fn.charAt(j)), fn.indexOf(".jpg")));
                            imgNos[i] = imgNo;
                            break;
                        }
                    }
                }
            }
            java.util.Arrays.sort(imgNos);
            return imgNos;

        }
        return null;
    }

    /**
     * Returns the status of replies from slides server
     * @return
     */
    public boolean isSlideServerReplying() {
        return slideServerReplying;
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

                SSLContext context = null;
                try {
                    context = SSLContext.getInstance("SSL");
                    context.init(null, trustAllCerts, new java.security.SecureRandom());
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
                dfactory = context.getSocketFactory();

                socket = (SSLSocket) dfactory.createSocket(SUPERNODE_HOST, SUPERNODE_PORT);
                try {
                    socket.startHandshake();
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
                result = true;
            } catch (UnknownHostException ex) {
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
        } catch (IOException ex) {
            if (base != null) {
                base.setText("Connection Error: " + ex.getMessage(), true);
            }
            ex.printStackTrace();
        }
        return result;
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
                    packet = reader.readObject();
                } catch (Exception ex) {
                    ex.printStackTrace();
                    if (base != null) {
                        base.setText("Disconnected from server.", true);
                    }
                    running = false;
                }

                if (packet instanceof ModuleFileReplyPacket) {
                    ModuleFileReplyPacket p = (ModuleFileReplyPacket) packet;
                    processModuleFileReplyPacket(p);
                } else if (packet instanceof ModuleFileRequestPacket) {
                    ModuleFileRequestPacket p = (ModuleFileRequestPacket) packet;
                    processModuleFileRequestPacket(p);
                } else if (packet instanceof LauncherMsgPacket) {
                    LauncherMsgPacket p = (LauncherMsgPacket) packet;
                    JOptionPane.showMessageDialog(null, p.getMessage());
                    base.setText("Error. Click on 'home', then revisit this presentation again to resolve.", true);
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

    private void processModuleFileRequestPacket(ModuleFileRequestPacket p) {
        byte[] byteArray = readFile(p.getFilePath());
        ModuleFileReplyPacket rep = new ModuleFileReplyPacket(byteArray,
                p.getFilename(), p.getFilePath(), p.getUsername());

        sendPacket(rep);
    }

    private void doSudoChMod(String filename) {
        String[] opts = {"KDE", "GNOME"};
        if (selectWindowManager) {
            windowManager = (String) JOptionPane.showInputDialog(null,
                    "Media libraries need to be installed for audio/video to work.\n" +
                    "Select your Window Manager system", "Window Manager", JOptionPane.QUESTION_MESSAGE, null, opts, opts[1]);
        }
        if (windowManager != null) {

            ProcessBuilder pb = new ProcessBuilder();
            if (((String) windowManager).equals("KDE")) {

                pb.command("kdesu", "-d", "chmod -R  777 " + filename);
            } else if (((String) windowManager).equals("GNOME")) {
                pb.command("gksu", "--message",
                        "This program needs your admin password in order to continue with installation",
                        "chmod -R 777 " + filename);
            } else {
                JOptionPane.showMessageDialog(null, "Unknown Window Manager");
                return;
            }
            try {
                Process p = pb.start();
                p.waitFor();
                selectWindowManager = false;
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(null, "Error copying files");
            }
        } else {
            JOptionPane.showMessageDialog(null, "Audio and Video Functionality will not work.");
        }

    }

    private void checkIfWritable(File f, String filename) {
        String path = new File(System.getProperty("java.home") +
                "/lib/").getAbsolutePath();
        if (!f.canRead() || !f.canWrite()) {
            doSudoChMod(path);
        }
        if (!f.canRead() || !f.canWrite()) {
            String msg = "<html><b><font color>Could not install media file: " +
                    filename + "</font>\n<html>Please change permissions of " +
                    "<font color=\"red\">" + path +
                    "</font> to <font color=\"green\">writable</font> for installation to work.</b>";

            JOptionPane.showMessageDialog(null, msg);
        }

    }

    private void processModuleFileReplyPacket(ModuleFileReplyPacket p) {
        String filename = p.getFilename();
        File f = new File(filename);
        base.setText("Processing " + p.getDesc() + "...", false);
        System.out.println("Downloading " + f.getName() + "... ");
        String dest = ".";
        if (filename.endsWith(".jar")) {

            dest = REALTIME_HOME + "/lib/" + f.getName();
            writeFile(dest, p.getByteArray(), false);

            if (value == 0) {
                base.getPb().setIndeterminate(false);
                base.getPb().setMaximum(base.getPluginsNumber());
                base.getPb().setMinimum(0);

            }

            base.getPb().setValue(++value);
            //last in order..means all others are downloaded..hopefull
            //so load all of them
            if (f.getName().equals("realtime-pluginmanager-0.1.jar")) {
                if (base != null) {
                    base.setPluginDownloaded(true);
                    System.out.println("Loading plugins...");
                    base.setText("Loading system ...", false);
                    base.loadAllPlugins();
                    System.out.println("Done");
                }
            }
        }

        if (filename.endsWith(".properties")) {
            dest = JAVA_HOME + "/lib/ext/" + f.getName();
            checkIfWritable(new File(JAVA_HOME + "/lib/"), f.getName());
            writeFile(dest, p.getByteArray(), true);
        }

        //this is linux/unix
        if (filename.endsWith(".so")) {
            dest = JAVA_HOME + "/lib/i386/" + f.getName();
            checkIfWritable(new File(JAVA_HOME + "/lib/"), f.getName());
            writeFile(dest, p.getByteArray(), true);
        }

        if (filename.endsWith(".sh")) {
            dest = REALTIME_HOME + "/bin/" + f.getName();
            writeFile(dest, p.getByteArray(), true);

        }

        if (filename.endsWith(".wav")) {
            dest = REALTIME_HOME + "/sounds/" + f.getName();
            writeFile(dest, p.getByteArray(), false);

        }
        //win or other exe
        if (filename.endsWith(".exe")) {
            dest = REALTIME_HOME + "/bin/" + f.getName();
            writeFile(dest, p.getByteArray(), false);
            installWinJMF();
        }
    }

    private void installWinJMF() {
        ProcessBuilder prb;
        prb =
                new ProcessBuilder(REALTIME_HOME + "/bin/jmf-2_1_1e-windows-i586.exe");
        try {
            Process p = prb.start();
            p.waitFor();
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public void writeFile(String filename, byte[] byteArray, boolean chmod) {
        try {
            FileChannel fc =
                    new FileOutputStream(filename).getChannel();
            fc.write(ByteBuffer.wrap(byteArray));
            fc.close();
            if (chmod) {
                doSudoChMod(filename);
            }

        } catch (Exception ex) {
            ex.printStackTrace();
            //delete if errors
            new File(filename).delete();
        }

    }

    public static void changeFilePermission(String filename) {
        String osName = System.getProperty("os.name");
        try {
            if (osName.toUpperCase().startsWith("LINUX")) {
                System.out.println("Changing mod of " + filename + " to 777");
                ProcessBuilder pb = new ProcessBuilder("chmod", "777 " + filename);
                Process p = pb.start();
                p.waitFor();
            }

        } catch (Exception e) {
            System.out.println(e.getMessage());
        }

    }
}
