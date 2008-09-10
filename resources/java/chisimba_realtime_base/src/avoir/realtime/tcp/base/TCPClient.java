/*
 * Copyright (C) GNU/GPL AVOIR 2008
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.filetransfer.FileReceiverManager;
import avoir.realtime.tcp.base.admin.ClientAdmin;
import avoir.realtime.tcp.base.audio.AudioWizardFrame;
import avoir.realtime.tcp.base.filetransfer.FileTransferPanel;
import avoir.realtime.tcp.base.filetransfer.FileUploader;
import avoir.realtime.tcp.common.packet.AckPacket;
import avoir.realtime.tcp.common.packet.RealtimePacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;

import avoir.realtime.tcp.base.user.User;

import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.packet.AttentionPacket;
import avoir.realtime.tcp.common.packet.AudioPacket;
import avoir.realtime.tcp.common.packet.BinaryFileSaveReplyPacket;
import avoir.realtime.tcp.common.packet.BinaryFileSaveRequestPacket;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.ClassroomSlidePacket;
import avoir.realtime.tcp.common.packet.ClearSlidesPacket;
import avoir.realtime.tcp.common.packet.ClearVotePacket;
import avoir.realtime.tcp.common.packet.ClientPacket;
import avoir.realtime.tcp.common.packet.FilePacket;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
import avoir.realtime.tcp.common.packet.HeartBeat;
import avoir.realtime.tcp.common.packet.LocalSlideCacheRequestPacket;
import avoir.realtime.tcp.common.packet.MsgPacket;
import avoir.realtime.tcp.common.packet.NewSlideReplyPacket;
import avoir.realtime.tcp.common.packet.NewSlideRequestPacket;
import avoir.realtime.tcp.common.packet.NewUserPacket;
import avoir.realtime.tcp.common.packet.OutlinePacket;
import avoir.realtime.tcp.common.packet.PointerPacket;
import avoir.realtime.tcp.common.packet.PresencePacket;
import avoir.realtime.tcp.common.packet.QuitPacket;
import avoir.realtime.tcp.common.packet.RemoveMePacket;
import avoir.realtime.tcp.common.packet.RemoveUserPacket;
import avoir.realtime.tcp.common.packet.RestartServerPacket;
import avoir.realtime.tcp.common.packet.ServerLogReplyPacket;
import avoir.realtime.tcp.common.packet.ServerLogRequestPacket;
import avoir.realtime.tcp.common.packet.SlideNotFoundPacket;
import avoir.realtime.tcp.common.packet.SurveyAnswerPacket;
import avoir.realtime.tcp.common.packet.SurveyPackPacket;
import avoir.realtime.tcp.common.packet.SystemFilePacket;
import avoir.realtime.tcp.common.packet.UploadMsgPacket;
import avoir.realtime.tcp.common.packet.VotePacket;
import avoir.realtime.tcp.common.packet.WhiteboardItems;
import avoir.realtime.tcp.common.packet.WhiteboardPacket;
import avoir.realtime.tcp.launcher.packet.LauncherPacket;
import avoir.realtime.tcp.whiteboard.WhiteboardSurface;
import java.io.*;
import java.net.*;


import java.util.Vector;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;



import java.util.Timer;
import java.util.TimerTask;
import java.util.logging.Logger;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSocket;
import javax.net.ssl.SSLSocketFactory;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;
import javax.swing.JOptionPane;

/**
 *Our main communication protocol. Every packet send and received from server
 * passes through here and is interpreted here
 * @author David Wafula
 */
public class TCPClient {

    private static Logger logger = Logger.getLogger(TCPClient.class.getName());
  
      private final int INITIAL_HEARBEAT_DELAY = 30;
       private long HEARTBEAT_DELAY = 10;
    private boolean running = true;
    private boolean selectWindowManager = true;
    private boolean ALIVE = true;
   
  private boolean slideServerReplying = false;
    private boolean NETWORK_ALIVE = true;
    private boolean connected = false;
 
    private RealtimeBase base;
    private AudioWizardFrame audioHandler;
    
    private String windowManager = "GNOME";
    protected ObjectInputStream reader;
    protected ObjectOutputStream writer;
    
    private String SUPERNODE_HOST = "196.21.45.85";
    private int SUPERNODE_PORT = 80;
   
    private ClientAdmin clientAdmin;
    //everything is encrypted here
    private SSLSocketFactory dfactory;
    private SSLSocket socket;
    private SlidesServer slidesServer;
    private boolean initSlideRequest = false;
    private Timer slidesMonitor = new Timer();
    private boolean slidesDownloaded = false;
    private FileTransferPanel fileTransfer;
    private FileOutputStream fileOutputStream;
 
    private WhiteboardSurface whiteboardSurface;
    int count = 0;
    private Timer monitorTimer = new Timer();
    private FileUploader fileUploader;
    private FileReceiverManager fileReceiverManager;
    private TCPConsumer consumer;

    public TCPClient(SlidesServer slidesServer) {
        this.slidesServer = slidesServer;

    }

    public TCPClient(ClientAdmin clientAdmin) {
        this.clientAdmin = clientAdmin;
        consumer = new TCPConsumer(this, clientAdmin);
    }

    public TCPClient() {
    }

    public TCPClient(RealtimeBase base) {
        this.base = base;
        fileReceiverManager = new FileReceiverManager(base);
        consumer = new TCPConsumer(this, base);
    }

    public void setWhiteboardSurfaceHandler(WhiteboardSurface whiteboardSurface) {
        this.whiteboardSurface = whiteboardSurface;
    }

    public void setAudioHandler(AudioWizardFrame audioHandler) {
        this.audioHandler = audioHandler;
    }

    public void setObjectInputStream(ObjectInputStream in) {
        reader = in;
    }

    public void setObjectOutputStream(ObjectOutputStream out) {
        writer = out;
    }

    public FileReceiverManager getFileReceiverManager() {
        return fileReceiverManager;
    }

    public AudioWizardFrame getAudioHandler() {
        return audioHandler;
    }

    public boolean isInitSlideRequest() {
        return initSlideRequest;
    }

    public boolean isSlidesDownloaded() {
        return slidesDownloaded;
    }

    public void setSlidesDownloaded(boolean slidesDownloaded) {
        this.slidesDownloaded = slidesDownloaded;
    }

    public void setInitSlideRequest(boolean initSlideRequest) {
        this.initSlideRequest = initSlideRequest;
    }

    public FileTransferPanel getFileTransfer() {
        return fileTransfer;
    }

    public FileOutputStream getFileOutputStream() {
        return fileOutputStream;
    }

    /**
     * Resquests user list, but in turn also gets published
     */
    public void publish(User user) {
        sendPacket(new AckPacket(user, user.isPresenter()));
    }

    public void setFileTransfer(FileTransferPanel fileTransfer) {
        this.fileTransfer = fileTransfer;
    }

    /**
     * changes super node host
     * @param host
     */
    public void setSuperNodeHost(String host) {
        SUPERNODE_HOST = host;
    }

    public void removeMe(String id) {
        sendPacket(new RemoveMePacket(id));
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

    public void sendRestartServerPacket() {
        sendPacket(new RestartServerPacket());
    }

    /**
     * sends a TCP Packet to Super Node
     * @param packet
     */
    public synchronized void sendPacket(RealtimePacket packet) {
        try {

            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();

            } else {
                logger.info("Error: writer is null!!!");
                if (base != null) {
                    base.showMessage("FATAL: Disconnected from server", true, true, MessageCode.ALL);
                }
            //JOptionPane.showMessageDialog(null, "Disconnected from server! Refresh browser to reconnect.");
            }
        } catch (IOException ex) {
            //JOptionPane.showMessageDialog(null, "Disconnected from server");
            ex.printStackTrace();
        }
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
                logger.info("Confirm Send!!!");
            } else {
                logger.info("Error: writer is null!!!");
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    /**
     * Read file in binary mode
     * @param filePath
     * @return
     */
    public byte[] readFile(String filePath) {
        File f = new File(filePath);
        File parentFile = f.getParentFile();

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
                    System.out.println("Error reading the file");
                    return null;
                }
            } else {
                System.out.println(filePath + " doesn't exist.");
                return null;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }

    }

    /**
     * prepare to listen from seerver
     */
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
        System.out.println(contentPath + " does not exist!!!");
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
        try {
            try {
                if (base != null) {
                    base.setText("Initializing session...", false);//to " + SUPERNODE_HOST+" ...");

                    System.out.println("Using direct connection...");
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
                socket.startHandshake();
                //  socket.setKeepAlive(true);

                result = true;
                connected = true;
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
            Thread t = new Thread() {

                @Override
                public void run() {
                    if (base != null) {
                        startHearBeat();
                    }
                    listen();
                }
            };
            t.start();
        } catch (IOException ex) {
            if (base != null) {
                base.setText("Connection Error: " + ex.getMessage(), true);
            }
            ex.printStackTrace();
        }
        return result;
    }

    /**
     * Start the pulses ..for monitoring n/w status
     */
    private void startHearBeat() {
        Thread t = new Thread() {

            public void run() {
                Timer timer = new Timer();

                timer.scheduleAtFixedRate(new HeartBeatGenerator(), 0, HEARTBEAT_DELAY * 1000);

                logger.info("Started hearbeat ..");

            }
        };
        t.start();
    }

    private void delay(long duration) {
        try {
            Thread.sleep(duration);
        } catch (Exception ex) {
        }
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
                    connected = true;

                // logger.info(packet.getClass() + "");
                } catch (Exception ex) {
                    ex.printStackTrace();
                    connected = false;

                    running = false;

                    break;

                }
                if (packet instanceof ClientPacket) {
                    ClientPacket clientPacket = (ClientPacket) packet;
                    updateUserList(clientPacket);
                } else if (packet instanceof ClearSlidesPacket) {
                    consumer.processClearSlidesPacket();
                } else if (packet instanceof ClassroomSlidePacket) {
                    consumer.processClassroomSlidePacket((ClassroomSlidePacket) packet);
                } else if (packet instanceof LocalSlideCacheRequestPacket) {
                    consumer.processLocalSlideCacheRequest((LocalSlideCacheRequestPacket) packet);
                } else if (packet instanceof WhiteboardItems) {
                    consumer.processWhiteboardItems((WhiteboardItems) packet);
                } else if (packet instanceof NewSlideReplyPacket) {
                    NewSlideReplyPacket nsr = (NewSlideReplyPacket) packet;
                    consumer.processNewSlideReplyPacket(nsr);
                } else if (packet instanceof HeartBeat) {
                    consumer.processHeartBeat((HeartBeat) packet);
                } else if (packet instanceof SlideNotFoundPacket) {
                    consumer.processSlideNotFoundPacket((SlideNotFoundPacket) packet);
                } else if (packet instanceof ChatLogPacket) {
                    ChatLogPacket p = (ChatLogPacket) packet;
                    consumer.processChatLogPacket(p);
                } else if (packet instanceof ServerLogReplyPacket) {
                    consumer.processServerLogReply((ServerLogReplyPacket) packet);
                } else if (packet instanceof FilePacket) {
                    consumer.processFilePacket((FilePacket) packet);
                } else if (packet instanceof ClearVotePacket) {
                    ClearVotePacket p = (ClearVotePacket) packet;
                    consumer.processClearVotePacket(p);
                } else if (packet instanceof BinaryFileSaveRequestPacket) {
                    consumer.processBinaryFileSaveRequestPacket((BinaryFileSaveRequestPacket) packet);
                } else if (packet instanceof VotePacket) {
                    VotePacket p = (VotePacket) packet;
                    consumer.processVotePacket(p);
                } else if (packet instanceof SurveyAnswerPacket) {
                    SurveyAnswerPacket p = (SurveyAnswerPacket) packet;
                    consumer.processSurveyAnswerPacket(p);
                } else if (packet instanceof SurveyPackPacket) {
                    SurveyPackPacket p = (SurveyPackPacket) packet;
                    consumer.processSurveyPacket(p);
                } else if (packet instanceof NewUserPacket) {
                    NewUserPacket p = (NewUserPacket) packet;
                    consumer.processNewUserPacket(p);
                } else if (packet instanceof PointerPacket) {
                    consumer.processPointerPacket((PointerPacket) packet);
                } else if (packet instanceof OutlinePacket) {
                    consumer.processOutlinePacket((OutlinePacket) packet);
                } else if (packet instanceof ChatPacket) {
                    ChatPacket p = (ChatPacket) packet;
                    consumer.processChatPacket(p);
                } else if (packet instanceof AttentionPacket) {
                    AttentionPacket p = (AttentionPacket) packet;
                    consumer.processAttentionPacket(p);
                } else if (packet instanceof RemoveUserPacket) {
                    RemoveUserPacket p = (RemoveUserPacket) packet;
                    consumer.processRemoveUserPacket(p);
                } else if (packet instanceof AudioPacket) {
                    consumer.processAudioPacket((AudioPacket) packet);
                } else if (packet instanceof MsgPacket) {
                    MsgPacket p = (MsgPacket) packet;
                    consumer.processMsgPacket(p);
                } else if (packet instanceof WhiteboardPacket) {
                    consumer.processWhiteboardPacket((WhiteboardPacket) packet);
                } else if (packet instanceof PresencePacket) {
                    PresencePacket p = (PresencePacket) packet;
                    consumer.processPresencePacket(p);

                } else if (packet instanceof QuitPacket) {
                    QuitPacket p = (QuitPacket) packet;
                    consumer.processQuitPacket(p);
                } else if (packet instanceof ModuleFileRequestPacket) {
                    ModuleFileRequestPacket p = (ModuleFileRequestPacket) packet;
                    consumer.processModuleFileRequestPacket(p);
                } else if (packet instanceof BinaryFileSaveReplyPacket) {
                    consumer.processBinaryFileSaveReplyPacket((BinaryFileSaveReplyPacket) packet);
                } else if (packet instanceof FileUploadPacket) {
                    fileReceiverManager.processFileUpload((FileUploadPacket) packet);
                } else if (packet instanceof UploadMsgPacket) {
                    UploadMsgPacket msg = (UploadMsgPacket) packet;
                    //   System.out.println("From " + msg.getRecepient() + " " + msg.getMessage());
                    fileTransfer.setProgress(msg.getRecepientIndex(), msg.getMessage());
                }
            } catch (Exception ex) {
                //for now, just cut off the listening
                running = false;
                connected = false;
                // displayDisconnectionMsg();
                ex.printStackTrace();
            }
        }
        if (!connected && !running) {
            displayDisconnectionMsg();
        }
    }

    private void createFile(String path) {
        try {
            fileOutputStream = new FileOutputStream(path);
        } catch (IOException ex) {
            ex.printStackTrace();
            JOptionPane.showMessageDialog(null, ex.getLocalizedMessage());
        }
    }

    public void sendAudioPacket(byte[] buff) {
        System.out.println("s: " + buff.length);
        sendPacket(new AudioPacket(base.getSessionId(), base.getUser().getUserName(), buff));

    }

    public boolean isConnected() {
        return connected;
    }

    public void setConnected(boolean connected) {
        this.connected = connected;
    }

    /**
     * Something went wrong..so the communication pipe between applet and server
     * is broken. Inform the user
     */
    private void displayDisconnectionMsg() {

        base.refreshConnection();
    /*
    int noOfTries = 0;
    int maxTries = 60;
    
    while (noOfTries < maxTries) {
    if (connected) {
    break;
    }
    if (base != null) {
    
    base.getWhiteboardSurface().setSelectedItem(null);
    base.showMessage("Disconnected from server. Retrying..." + noOfTries+" of "+maxTries, false, true);
    
    base.refreshConnection();
    }
    if (slidesServer != null) {
    //if for the slide server..try to reconnect
    slidesServer.reconnect();
    }
    noOfTries++;
    delay(1000);
    }
    
    if (!connected && base != null) {
    int n = JOptionPane.showConfirmDialog(null, "Disconnected from server. Reconnect?", "Disconnected", JOptionPane.YES_NO_OPTION);
    if (n == JOptionPane.YES_OPTION) {
    //try to auto reconnect
    base.initTCPCommunication();
    } else {
    base.showMessage("Disconnected From Server", false, true);
    }
    }
     */
    }

    /**
     * Sends request to server to remove this user
     * @param user
     */
    public void removeUser(User user) {
        sendPacket(new RemoveUserPacket(user));
    }

    /**
     * Updates user list
     * @param packet
     */
    private void updateUserList(ClientPacket packet) {
        //update user list..but only if this user is the applet
        if (base != null) {
            Vector<User> users = packet.getUsers();
            base.getUserManager().updateUserList(users);
        }
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
        }

    }

    public static void changeFilePermission(String filename) {
        String osName = System.getProperty("os.name");
        try {
            if (osName.toUpperCase().startsWith("LINUX")) {
                ProcessBuilder pb = new ProcessBuilder("chmod", "777 " + filename);
                Process p = pb.start();
                p.waitFor();
            }

        } catch (Exception e) {
            System.out.println(e.getMessage());
        }

    }

    /**
     * Send chat to server. Need to think..should it be returned back
     * to me really? But at least its a guarantee that the chat was sent
     * and broadcast
     * @param packet
     */
    public void addChat(ChatPacket packet) {
        sendPacket(packet);
    }

    public String[] createSlideNames(int[] n) {
        String[] names = new String[n.length];
        for (int i = 0; i < names.length; i++) {
            names[i] = "Slide" + (i + 1);
        }
        return names;
    }

    /**
     * /////////////////////////////////////////////////////////////////
     * These methods are for sending requests to the server
     * They all start with request pre-appnended to the name
     * ///////////////////////////////////////////////////////////////////
     * 
     */
    /**
     * preload the slides to the applet
     * @param slidesServerId
     */
    public void requestForSlides(String sessinId, String slidesServerId, String pathToSlides, String username) {
        sendPacket(new LocalSlideCacheRequestPacket(sessinId, slidesServerId, pathToSlides, username));
        //monitor 
        slidesMonitor = new Timer();
    // slidesMonitor.schedule(new LocalSlideCacheRequestMonitor(), 20 * 1000);
    }

    class LocalSlideCacheRequestMonitor extends TimerTask {

        public void run() {
            if (!slidesDownloaded) {
                base.setText("The slide server is currently under maintenance and we regret cannot do live presentation.", true);
                //   JOptionPane.showMessageDialog(null, "The server is taking too long to respond.\n" +
                //         "To resolve this, please click on the 'Refresh Button' on the toolbar.");
                base.showMessage("Slide Server under maintenance", true, true, MessageCode.ALL);
                base.setStatusMessage("The slide server is currently under maintenance and we regret cannot do live presentation.");

            }
            slidesMonitor.cancel(); //Terminate the thread
        }
    }

    /**
     * 
     * Requests a specific slide
     * @param slidesDir
     * @param slideIndex
     */
    public void requestNewSlide(String siteRoot, int slideIndex,
            boolean isPresenter, String sessionId, String userId,
            boolean hasControl, String presentationName) {
        NewSlideRequestPacket p = new NewSlideRequestPacket(siteRoot,
                slideIndex,
                isPresenter,
                sessionId,
                userId, presentationName);
        p.setControl(hasControl);
        p.setSlideServerId(base.getSlideServerId());
        sendPacket(p);
    }

    public void requestServerLog() {
        sendPacket(new ServerLogRequestPacket());
    }

    /***
     * ///////////////////////////////////////////////////////////////////
     * These methods are for replying back to the server
     * They all start with reply pre-appended
     * //////////////////////////////////////////////////////////////////
     */
    public void replySlide(final String filePath,
            final String sessionId,
            final String username,
            final String filename,
            final boolean lastFile,
            int currentValue, int maxValue, String presentationName) {
        byte[] byteArray = readFile(filePath);
        FilePacket packet = new FilePacket(sessionId, username,
                byteArray, filename, lastFile, presentationName);
        packet.setMaxValue(maxValue);
        packet.setCurrentValue(currentValue);
        sendPacket(packet);

    }

    public void setFileUploadHandler(FileUploader fileUploader) {
        this.fileUploader = fileUploader;
    }

    /***
     * /////////////////////////////////////////////////////////////////
     * These method are for sending non repliable messages to the server
     * /////////////////////////////////////////////////////////////////
     * 
     */
    public void sendAgendaItem(String item, String sessionId, int index, int max) {
        sendPacket(new OutlinePacket(sessionId, item, index, null, max));
    }

    public void sendSystemFile(String filePath, String filename) {
        byte[] byteArray = readFile(filePath);
        SystemFilePacket p = new SystemFilePacket("", "", byteArray, "/usr/lib/chisimba-realtime-server/1.0.1/lib/" + filename, false);
        sendPacket(p);
    }

    /**
     * Send a heart beat pulse
     */
    private void sendPulse() {
        /* try {
        if (writer != null) {
        writer.writeObject(new HeartBeat(base.getSessionId()));
        writer.flush();
        NETWORK_ALIVE = false;
        monitorPulse();
        } else {
        logger.info("Error: writer is null!!!");
        //JOptionPane.showMessageDialog(null, "Disconnected from server! Refresh browser to reconnect.");
        }
        } catch (IOException ex) {
        base.showMessage("Disconnected from Server", false, true);
        ALIVE = false;
        ex.printStackTrace();
        }*/
    }

    private void monitorPulse() {
        //delay for 3 secs , then monitor it after every 1 secs

        monitorTimer = new Timer();
        monitorTimer.scheduleAtFixedRate(new HeartBeatMonitor(), 3000, 1000);

    }

    private void cancelMonitor() {
        monitorTimer.cancel();
    }

    public boolean isNetworkAlive() {
        return NETWORK_ALIVE;
    }

    public void setUserOffline() {
        int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
    /* base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
    PresenceConstants.OFFLINE, PresenceConstants.OFFLINE);
    base.showMessage("Network Error!!! ", false, true);
     */
    }

    class HeartBeatMonitor extends TimerTask {

        int monitorCount = 0;

        public void run() {
            monitorCount++;
            //System.out.println("count: " + monitorCount);
            if (monitorCount > 30) {
                setUserOffline();
                cancelMonitor();

            }
        }
    }

    class HeartBeatGenerator extends TimerTask {

        public void run() {
            if (ALIVE) {
                if (NETWORK_ALIVE) {
                    sendPulse();
                }
            }
        }
    }
}