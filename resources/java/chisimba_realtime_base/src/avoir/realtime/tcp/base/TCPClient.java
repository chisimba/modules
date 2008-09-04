/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.admin.ClientAdmin;
import avoir.realtime.tcp.base.audio.AudioWizardFrame;
import avoir.realtime.tcp.base.filetransfer.FileTransferPanel;
import avoir.realtime.tcp.base.filetransfer.FileUploader;
import avoir.realtime.tcp.common.packet.AckPacket;
import avoir.realtime.tcp.common.packet.RealtimePacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileReplyPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;

import avoir.realtime.tcp.base.user.User;

import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.packet.AttentionPacket;
import avoir.realtime.tcp.common.packet.AudioPacket;
import avoir.realtime.tcp.common.packet.BinaryFileChunkPacket;
import avoir.realtime.tcp.common.packet.BinaryFileSaveReplyPacket;
import avoir.realtime.tcp.common.packet.BinaryFileSaveRequestPacket;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.ClassroomSlidePacket;
import avoir.realtime.tcp.common.packet.ClearVotePacket;
import avoir.realtime.tcp.common.packet.ClientPacket;
import avoir.realtime.tcp.common.packet.FilePacket;
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
import avoir.realtime.tcp.common.packet.ServerLogReplyPacket;
import avoir.realtime.tcp.common.packet.ServerLogRequestPacket;
import avoir.realtime.tcp.common.packet.SlideNotFoundPacket;
import avoir.realtime.tcp.common.packet.SurveyAnswerPacket;
import avoir.realtime.tcp.common.packet.SurveyPackPacket;
import avoir.realtime.tcp.common.packet.SystemFilePacket;
import avoir.realtime.tcp.common.packet.UploadOKPacket;
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
import javax.swing.ImageIcon;
import javax.swing.JFileChooser;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
 */
public class TCPClient {

    private static Logger logger = Logger.getLogger(TCPClient.class.getName());
    private boolean running = true;
    private String windowManager = "GNOME";
    private boolean selectWindowManager = true;
    private RealtimeBase base;
    private AudioWizardFrame audioHandler;
    private boolean ALIVE = true;
    private long HEARTBEAT_DELAY = 10;
    private final int INITIAL_HEARBEAT_DELAY = 30;
    private boolean NETWORK_ALIVE = true;
    /**
     * Reader for the ObjectInputStream
     */
    protected ObjectInputStream reader;
    /**
     * Writer for the ObjectOutputStream
     */
    protected ObjectOutputStream writer;
    private boolean slideServerReplying = false;
    private String SUPERNODE_HOST = "196.21.45.85";
    private int SUPERNODE_PORT = 80;
    //  private String SUPERNODE_HOST = "localhost";
    //  private int SUPERNODE_PORT = 22225;
    private boolean showChatFrame = true;
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
    private JFileChooser fc = new JFileChooser();
    private WhiteboardSurface whiteboardSurface;
    int count = 0;
    private Timer monitorTimer = new Timer();
    private FileUploader fileUploader;

    public TCPClient(SlidesServer slidesServer) {
        this.slidesServer = slidesServer;
    }

    public TCPClient(ClientAdmin clientAdmin) {
        this.clientAdmin = clientAdmin;
    }

    public TCPClient() {
    }

    public TCPClient(RealtimeBase base) {
        this.base = base;
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

    /**
     * sends a TCP Packet to Super Node
     * @param packet
     */
    public void sendPacket(RealtimePacket packet) {
        try {

            if (packet instanceof WhiteboardPacket) {
                WhiteboardPacket p = (WhiteboardPacket) packet;
            //System.out.println(p.getItem());
            }
            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();

            } else {
                logger.info("Error: writer is null!!!");
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

    private byte[] readFile(String filePath) {
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

    public boolean isShowChatFrame() {
        return showChatFrame;
    }

    public void setShowChatFrame(boolean showChatFrame) {
        this.showChatFrame = showChatFrame;
    }

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
                //     logger.info(packet.getClass() + "");
                } catch (Exception ex) {
                    ex.printStackTrace();
                    displayDisconnectionMsg();
                    running = false;
                    break;

                }
                if (packet instanceof ClientPacket) {
                    ClientPacket clientPacket = (ClientPacket) packet;
                    updateUserList(clientPacket);
                } else if (packet instanceof ClassroomSlidePacket) {
                    processClassroomSlidePacket((ClassroomSlidePacket) packet);
                } else if (packet instanceof LocalSlideCacheRequestPacket) {
                    processLocalSlideCacheRequest((LocalSlideCacheRequestPacket) packet);
                } else if (packet instanceof WhiteboardItems) {
                    processWhiteboardItems((WhiteboardItems) packet);
                } else if (packet instanceof NewSlideReplyPacket) {
                    NewSlideReplyPacket nsr = (NewSlideReplyPacket) packet;
                    processNewSlideReplyPacket(nsr);
                } else if (packet instanceof HeartBeat) {
                    processHeartBeat((HeartBeat) packet);
                } else if (packet instanceof SlideNotFoundPacket) {
                    processSlideNotFoundPacket((SlideNotFoundPacket) packet);
                } else if (packet instanceof ChatLogPacket) {
                    ChatLogPacket p = (ChatLogPacket) packet;
                    processChatLogPacket(p);
                } else if (packet instanceof ServerLogReplyPacket) {
                    processServerLogReply((ServerLogReplyPacket) packet);
                } else if (packet instanceof FilePacket) {
                    processFilePacket((FilePacket) packet);
                } else if (packet instanceof ClearVotePacket) {
                    ClearVotePacket p = (ClearVotePacket) packet;
                    processClearVotePacket(p);
                } else if (packet instanceof BinaryFileSaveRequestPacket) {
                    processBinaryFileSaveRequestPacket((BinaryFileSaveRequestPacket) packet);
                } else if (packet instanceof VotePacket) {
                    VotePacket p = (VotePacket) packet;
                    processVotePacket(p);
                } else if (packet instanceof SurveyAnswerPacket) {
                    SurveyAnswerPacket p = (SurveyAnswerPacket) packet;
                    processSurveyAnswerPacket(p);
                } else if (packet instanceof SurveyPackPacket) {
                    SurveyPackPacket p = (SurveyPackPacket) packet;
                    processSurveyPacket(p);
                } else if (packet instanceof NewUserPacket) {
                    NewUserPacket p = (NewUserPacket) packet;
                    processNewUserPacket(p);
                } else if (packet instanceof PointerPacket) {
                    processPointerPacket((PointerPacket) packet);
                } else if (packet instanceof OutlinePacket) {
                    processOutlinePacket((OutlinePacket) packet);
                } else if (packet instanceof ChatPacket) {
                    ChatPacket p = (ChatPacket) packet;
                    processChatPacket(p);
                } else if (packet instanceof AttentionPacket) {
                    AttentionPacket p = (AttentionPacket) packet;
                    processAttentionPacket(p);
                } else if (packet instanceof RemoveUserPacket) {
                    RemoveUserPacket p = (RemoveUserPacket) packet;
                    processRemoveUserPacket(p);
                } else if (packet instanceof AudioPacket) {
                    processAudioPacket((AudioPacket) packet);
                } else if (packet instanceof MsgPacket) {
                    MsgPacket p = (MsgPacket) packet;
                    processMsgPacket(p);
                } else if (packet instanceof WhiteboardPacket) {
                    processWhiteboardPacket((WhiteboardPacket) packet);
                } else if (packet instanceof PresencePacket) {
                    PresencePacket p = (PresencePacket) packet;
                    processPresencePacket(p);
                } else if (packet instanceof UploadOKPacket) {
                    processUploadOKPacket((UploadOKPacket) packet);
                } else if (packet instanceof QuitPacket) {
                    QuitPacket p = (QuitPacket) packet;
                    processQuitPacket(p);
                } else if (packet instanceof ModuleFileRequestPacket) {
                    ModuleFileRequestPacket p = (ModuleFileRequestPacket) packet;
                    processModuleFileRequestPacket(p);
                } else if (packet instanceof BinaryFileSaveReplyPacket) {
                    processBinaryFileSaveReplyPacket((BinaryFileSaveReplyPacket) packet);
                } else if (packet instanceof BinaryFileChunkPacket) {
                    processBinaryFileChunkPacket((BinaryFileChunkPacket) packet);
                }
            } catch (Exception ex) {
                //for now, just cut off the listening
                running = false;
                displayDisconnectionMsg();
                ex.printStackTrace();
            }
        }
    }

    private void processUploadOKPacket(UploadOKPacket p) {
        if (fileUploader != null) {
            fileUploader.setProgress(p.getChunkNo());
        }
    }

    private void processWhiteboardItems(WhiteboardItems p) {
        base.getWhiteboardSurface().setItems(p.getWhiteboardItems());
    }

    private void processPointerPacket(PointerPacket p) {
        if (base.getMODE() == Constants.APPLET) {
            base.getSurface().setCurrentPointer(p.getType(), p.getPoint());
        } else {
            base.getWhiteboardSurface().setCurrentPointer(p.getType(), p.getPoint());
        }

    }

    private void processWhiteboardPacket(WhiteboardPacket p) {
        //   System.out.println("Received: " + p.getItem());
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.ADD_NEW_ITEM) {
            base.getWhiteboardSurface().addItem(p.getItem());
        }
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.REPLACE_ITEM) {

            base.getWhiteboardSurface().replaceItem(p.getItem());
        }
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.REMOVE_ITEM) {

            base.getWhiteboardSurface().removeItem(p.getItem());
        }
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.CLEAR_ITEMS) {

            base.getWhiteboardSurface().clearItems();
        }
    }

    private void processBinaryFileChunkPacket(BinaryFileChunkPacket p) {
        if (fileOutputStream != null) {
            try {
                if (!p.isLast()) {

                    fileOutputStream.write(p.getChunk());
                    System.out.println("received chunck: " + p.getFilename() + " " + p.getChunk().length);
                } else {
                    fileOutputStream.close();
                    System.out.println("closed");
                    fileOutputStream = null;
                }
            } catch (IOException ex) {
                ex.printStackTrace();
                JOptionPane.showMessageDialog(null, ex.getLocalizedMessage());
            }
        }
    }

    private void processBinaryFileSaveReplyPacket(BinaryFileSaveReplyPacket p) {
        fileTransfer.processBinaryFileSaveReplyPacket(p.getFileName(), p.getFullName(), p.isAccepted());
    }

    private void createFile(String path) {
        try {
            fileOutputStream = new FileOutputStream(path);
        } catch (IOException ex) {
            ex.printStackTrace();
            JOptionPane.showMessageDialog(null, ex.getLocalizedMessage());
        }
    }

    private void processBinaryFileSaveRequestPacket(BinaryFileSaveRequestPacket p) {
        String filename = new File(p.getFileName()).getName();
        int n = JOptionPane.showConfirmDialog(base, p.getSenderName() + " has send you file " + filename + "\n" +
                "Accept?", "New File", JOptionPane.YES_NO_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            fc.setSelectedFile(new File(filename));
            if (fc.showSaveDialog(null) == JFileChooser.APPROVE_OPTION) {
                createFile(fc.getSelectedFile().getAbsolutePath());
                sendPacket(new BinaryFileSaveReplyPacket(p.getSessionId(), p.getFileName(), base.getUser().getFullName(), p.getSenderName(), true, p.getUserName()));
            } else {
                sendPacket(new BinaryFileSaveReplyPacket(p.getSessionId(), p.getFileName(), base.getUser().getFullName(), p.getSenderName(), false, p.getUserName()));

            }

        } else {
            sendPacket(new BinaryFileSaveReplyPacket(p.getSessionId(), p.getFileName(), base.getUser().getFullName(), p.getSenderName(), false, p.getUserName()));

        }
    }

    private void processPresencePacket(PresencePacket p) {
        if (base != null) {

            int userIndex = base.getUserManager().getUserIndex(p.getUserName());
            if (userIndex > -1) {
                base.getUserManager().setUser(userIndex, p.getPresenceType(), p.isShowIcon(), true);
            }
        }
    }

    public void sendAudioPacket(byte[] buff) {
        System.out.println("s: " + buff.length);
        sendPacket(new AudioPacket(base.getSessionId(), base.getUser().getUserName(), buff));

    }

    /**
     * Something went wrong..so the communication pipe between applet and server
     * is broken. Inform the user
     */
    private void displayDisconnectionMsg() {
        if (base != null) {
            base.getWhiteboardSurface().setSelectedItem(null);
            base.showMessage("Disconnected from server. Retrying...", false, true);
            int n = JOptionPane.showConfirmDialog(null, "Disconnected from server. Reconnect?", "Disconnected", JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                //try to auto reconnect
                base.initTCPCommunication();
            } else {
                base.showMessage("Disconnected From Server", false, true);
            }
        }
        if (slidesServer != null) {
            //if for the slide server..try to reconnect
            slidesServer.reconnect();
        }

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

    // ///////////////////////////////////////////////////////////////////
    // These methods are for acting on the requests send from the server//
    // They all start with process preappended to the name              //
    /////////////////////////////////////////////////////////////////////
    /**
     * This handles the audio packets
     * @param packet
     */
    private void processAudioPacket(AudioPacket packet) {

        if (audioHandler != null) {
            audioHandler.playPacket(packet);

        }


    }

    private void processQuitPacket(QuitPacket p) {
        //just quit but not good this way...we need a way of checking if this
        //was meant for us and not just a broadcast (DOF Attacks :) )
        System.exit(0);
    }

    /**
     * Removes this user from the user list. let the GUI applet handle that
     * @param p
     */
    private void processRemoveUserPacket(RemoveUserPacket p) {
        if (base != null) {
            base.getUserManager().removeUser(p.getUser());
        }

    }

    /**
     * Send message to surface to be displayed
     * @param p
     */
    private void processMsgPacket(MsgPacket p) {
        if (base != null) {
            base.showMessage(p.getMessage(), p.isTemporary(), p.isErrorMsg());
            if (p.isErrorMsg()) {
                base.setStatusMessage("");
            }

        }

        if (clientAdmin != null) {
            clientAdmin.setMessage(p.getMessage());
        }

    }

    private void processFilePacket(FilePacket packet) {
        try {
            String home = avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/presentations/" + packet.getSessionId();
            File homeDir = new File(home);
            if (!homeDir.exists()) {
                homeDir.mkdirs();
            }

            String fn = home + "/" + packet.getFilename();
            FileChannel fc =
                    new FileOutputStream(fn).getChannel();
            byte[] byteArray = packet.getByteArray();
            fc.write(ByteBuffer.wrap(byteArray));
            fc.close();
            base.getSessionManager().setSlideCount(packet.getMaxValue());

            float perc = ((float) packet.getCurrentValue() + 1 / (float) packet.getMaxValue()) * 100;
            base.getSurface().setConnectingString("Please wait " + new java.text.DecimalFormat("##.#").format(perc) + "% ...");

            if (!initSlideRequest) {
                requestNewSlide(base.getSiteRoot(), 0, base.isPresenter(),
                        base.getSessionId(), base.getUser().getUserName(),
                        base.isPresenter());
                base.getSurface().setConnecting(false);
                base.getSurface().setConnectingString("");
                base.getAgendaManager().addDefaultAgenda(base.getSessionTitle());
                initSlideRequest = true;
            }
            slidesDownloaded = true;
        } catch (Exception ex) {
            ex.printStackTrace();
            if (base != null) {
                base.showMessage("Error writing slide " + packet.getFilename(), false, true);
            }

        }
    }

    private void processClassroomSlidePacket(ClassroomSlidePacket packet) {
        try {
            int dot = packet.getPresentationName().lastIndexOf(".");
            String dir = packet.getPresentationName();
            if (dot > 0) {
                dir = packet.getPresentationName().substring(0, dot);
            }

            String home = avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/classroom/slides/" + dir;
            File homeDir = new File(home);
            if (!homeDir.exists()) {
                homeDir.mkdirs();
            }

            String fn = home + "/" + packet.getFilename();
            FileChannel fc =
                    new FileOutputStream(fn).getChannel();
            byte[] byteArray = packet.getByteArray();
            fc.write(ByteBuffer.wrap(byteArray));
            fc.close();
            if (packet.isLastFile()) {
                base.getSessionManager().setSlideCount(packet.getMaxValue());
                int[] slidesList = getImageFileNames(home);

                base.getAgendaManager().addAgenda(createSlideNames(slidesList), packet.getPresentationName());
                base.getWhiteboardSurface().setCurrentSlide(new ImageIcon(home + "/img0.jpg"), 0, slidesList.length, true);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            if (base != null) {
                base.showMessage("Error writing slide " + packet.getFilename(), false, true);
            }

        }
    }

    public String[] createSlideNames(int[] n) {
        String[] names = new String[n.length];
        for (int i = 0; i < names.length; i++) {
            names[i] = "Slide" + (i + 1);
        }
        return names;
    }

    /**
     * send the chatlog to the chat room for the newly logged in user
     * @param p
     */
    private void processChatLogPacket(ChatLogPacket p) {
        if (base != null) {
            base.updateChat(p);
        }

    }

    /**
     * User has raised/not raised the hand. update the user
     * @param p
     */
    private void processAttentionPacket(AttentionPacket p) {
        /*        if (base != null) {
        base.getUserManager().updateUserList(p.getUserName(), p.isHandRaised(),
        p.isAllowControl(), p.getOrder(), p.isYes(), p.isNo(), p.isYesNoSession(), p.isSpeakerEnabled(), p.isMicEnabled());
        }
         */
    }

    /**
     * Send the chat packet to the chat room
     * @param p
     */
    private void processChatPacket(ChatPacket p) {
        if (base != null) {
            base.updateChat(p);
            if (showChatFrame) {
                base.showChatRoom();
                showChatFrame = false;
            }
        }
    }

    private void processOutlinePacket(OutlinePacket p) {
        base.getAgendaManager().addAgenda(p.getOutlines(), base.getSessionTitle());

    }

    /**
     * New user. Add at the bottom of the user list
     * @param p
     */
    private void processNewUserPacket(NewUserPacket p) {
        if (base != null) {
            base.getUserManager().addNewUser(p.getUser());
        }

    }

    /**
     * Requested slide not found..paint this message on the surface 
     * to inform the user
     * @param p
     */
    private void processSlideNotFoundPacket(SlideNotFoundPacket p) {
        if (base != null) {
            String msg = p.getMessage();
            if (msg != null) {
                if (msg.length() > 0) {
                    base.setStatusMessage(msg);
                    return;

                }


            }
            base.setStatusMessage("");
        }

    }

    private void processSurveyAnswerPacket(SurveyAnswerPacket p) {
        if (base != null) {

            base.getSurveyManagerFrame().setSurveyAnswer(p.getQuestion(), p.isAnswer());
        }

    }

    private void processVotePacket(VotePacket p) {
        if (base != null) {
            base.getToolbarManager().setVoteButtonsEnabled(p.isVote(), p.isVisibleToAll());
        }
    }

    private void processSurveyPacket(SurveyPackPacket p) {

        if (base != null) {
            base.showSurveyFrame(p.getQuestions(), p.getTitle());
        }

    }

    private void processServerLogReply(ServerLogReplyPacket packet) {
        if (clientAdmin != null) {
            clientAdmin.setLog(packet.getByteArray());
        }

    }

    private void processClearVotePacket(ClearVotePacket p) {
        if (base != null) {
            base.getUserManager().clearVote();
        }

    }

    private synchronized void processModuleFileRequestPacket(ModuleFileRequestPacket p) {

        byte[] byteArray = readFile(p.getFilePath());
        logger.info("Read File: " + p.getFilePath());
        ModuleFileReplyPacket rep = new ModuleFileReplyPacket(byteArray,
                p.getFilename(), p.getFilePath(), p.getUsername());
        rep.setDesc(p.getDesc());

        sendPacket(rep);
        logger.info("Send Back!!!");
    }

    private void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        int slides[] = getImageFileNames(packet.getPathToSlides());
        String slidesPath = packet.getPathToSlides();
        // i hope to use threads here..dont know if it will help
        if (slides != null) {
            for (int i = 0; i <
                    slides.length; i++) {
                String filename = "img" + i + ".jpg";
                String filePath = slidesPath + "/" + filename;
                boolean lastFile = i == (slides.length - 1) ? true : false;
                replySlide(filePath, packet.getSessionId(), packet.getUsername(), filename, lastFile, i, slides.length);
            }
        }
    //then close this socket

    /*      try {
    //close the client..then close the app, whicever closes first
    sendPacket(new QuitPacket((packet.getSessionId())));
    socket.close();
    } catch (Exception ex) {
    ex.printStackTrace();
    }
     */
    }

    /**
     * New slide. Look for it in the local cache and display it.
     * But let the GUI class do that
     * @param packet
     */
    private void processNewSlideReplyPacket(NewSlideReplyPacket packet) {
        int slideIndex = packet.getSlideIndex();
        if (base != null) {
            slideServerReplying = true;
            String slidePath = Constants.getRealtimeHome() + "/presentations/" + base.getSessionId() + "/img" + slideIndex + ".jpg";
            base.getSessionManager().setCurrentSlide(slideIndex, packet.isIsPresenter(), slidePath);
            if (packet.getMessage() != null) {
                base.showMessage(packet.getMessage(), false, true);
            }
        }
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
        slidesMonitor.schedule(new LocalSlideCacheRequestMonitor(), 20 * 1000);
    }

    class LocalSlideCacheRequestMonitor extends TimerTask {

        public void run() {
            if (!slidesDownloaded) {
                base.setText("Error: Server taking too long to reply. Click on 'Refresh' button to resolve.", true);
                //   JOptionPane.showMessageDialog(null, "The server is taking too long to respond.\n" +
                //         "To resolve this, please click on the 'Refresh Button' on the toolbar.");
                base.setStatusMessage("The server is taking too long to respond." +
                        "To resolve this, click on 'Refresh' button to resolve.");

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
            boolean hasControl) {
        NewSlideRequestPacket p = new NewSlideRequestPacket(siteRoot,
                slideIndex,
                isPresenter,
                sessionId,
                userId);
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
    private void replySlide(final String filePath,
            final String sessionId,
            final String username,
            final String filename,
            final boolean lastFile,
            int currentValue, int maxValue) {
        byte[] byteArray = readFile(filePath);
        FilePacket packet = new FilePacket(sessionId, username,
                byteArray, filename, lastFile);
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
        SystemFilePacket p = new SystemFilePacket("", "", byteArray, "/usr/lib/realtime/lib/" + filename, false);
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

    private void processHeartBeat(HeartBeat p) {

        cancelMonitor();
        NETWORK_ALIVE = true;
        base.showMessage("", false, false);
        int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
    /*   base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
    PresenceConstants.ONLINE, true);
     */
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