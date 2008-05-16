/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

import java.io.*;
import java.net.*;
import avoir.realtime.tcp.common.user.User;
import avoir.realtime.tcp.common.packet.*;

import java.util.Vector;
import avoir.realtime.common.DEBUG_ENGINE;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import avoir.realtime.admin.ClientAdmin;
import avoir.realtime.tcp.audio.AudioWizardFrame;
import javax.swing.JOptionPane;
import static avoir.realtime.common.Constants.*;

/**
 *
 * @author developer
 */
public class TCPClient {

    private AudioWizardFrame audioWizardFrame;
    private boolean running = true;
    private String windowManager = "GNOME";
    private boolean selectWindowManager = true;
    /**
     * Reader for the ObjectInputStream
     */
    protected ObjectInputStream reader;
    /**
     * Writer for the ObjectOutputStream
     */
    protected ObjectOutputStream writer;
    private Surface surface;
    private TCPTunnellingApplet applet;
    private boolean slideServerReplying = false;
    private boolean showChatFrame = true;
    private String SUPERNODE_HOST = "196.21.45.85";
    private int SUPERNODE_PORT = 80;
    private ClientAdmin clientAdmin;
    private Socket socket;

    /**
     * Const
     * @param surface
     * @param applet
     */
    public TCPClient(Surface surface, TCPTunnellingApplet applet) {
        this.surface = surface;
        this.applet = applet;
    }

    public TCPClient(ClientAdmin clientAdmin) {
        this.clientAdmin = clientAdmin;
    }

    public TCPClient() {
    }

    public void setAudioPacketHandler(AudioWizardFrame audioWizardFrame) {
        this.audioWizardFrame = audioWizardFrame;
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
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print(getClass(), "Sending Publish request");
        }
        sendPacket(new AckPacket(user, user.isPresenter()));
    }

    /**
     * changes super node host
     * @param host
     */
    public void setSuperNodeHost(String host) {
        SUPERNODE_HOST = host;
    }

    public void setSessionEnabled(String sessionId, boolean state) {
        sendPacket(new SessionStatePacket(sessionId, state));
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
     * preload the slides to the applet
     * @param slidesServerId
     */
    public void requestForSlides(String sessinId, String slidesServerId, String pathToSlides, String username) {
        sendPacket(new LocalSlideCacheRequestPacket(sessinId, slidesServerId, pathToSlides, username));
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
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print(getClass(), "Sending new slide request to " + siteRoot);
        }
        NewSlideRequestPacket p = new NewSlideRequestPacket(siteRoot,
                slideIndex,
                isPresenter,
                sessionId,
                userId);
        p.setControl(hasControl);
        p.setSlideServerId(applet.getSlideServerId());
        sendPacket(p);
    }

    /**
     * sends a TCP Packet to Super Node
     * @param packet
     */
    public void sendPacket(RealtimePacket packet) {
        try {
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print(getClass(), "Sending packet " + packet.getClass());
            }
            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();

                if (DEBUG_ENGINE.debug_level > 3) {
                    DEBUG_ENGINE.print("Send !!!!");
                }
            } else {
                if (DEBUG_ENGINE.debug_level > 3) {
                    DEBUG_ENGINE.print("Writer is null !!!! Cannot send  packet " + packet.getClass());
                }
            }
        } catch (IOException ex) {
            if (applet != null) {
                applet.setStatusBarMessage(ex.getMessage());
            }
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print("Error !!!!!!");
            }
            ex.printStackTrace();
        }
    }

    /**
     * Updates user list
     * @param packet
     */
    private void updateUserList(ClientPacket packet) {
        //update user list..but only if this user is the applet
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print(getClass(), "Updating user list");
        }
        if (applet != null) {
            Vector<User> users = packet.getUsers();
            applet.updateUserList(users);
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

    /**
     * Sends request to server to remove this user
     * @param user
     */
    public void removeUser(User user) {
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print(getClass(), "Removing user " + user);
        }
        sendPacket(new RemoveUserPacket(user));
    }

    /**
     * User has raised/or put down the hand. Send to server for processing
     * @param userName
     * @param sessionId
     * @param raised
     * @param allowControl
     */
    public void sendAttentionPacket(String userName, String sessionId,
            boolean raised, boolean allowControl, boolean yes, boolean no,
            boolean isYesNoSession, boolean isPrivate) {
        sendPacket(new AttentionPacket(userName, sessionId, raised,
                allowControl, yes, no, isYesNoSession, isPrivate));
    }

    public void sendSystemFile(String filePath, String filename) {
        byte[] byteArray = readFile(filePath);
        SystemFilePacket p = new SystemFilePacket("", "", byteArray, "/usr/lib/realtime/lib/" + filename, false);
        sendPacket(p);
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

        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print(getClass(), "In get image file names: getting slides count");
        }
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
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print("Found " + imgNos.length);
            }
            return imgNos;

        }
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print("none found. returning null ");
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

    /**
     * New slide. Look for it in the local cache and display it.
     * But let the GUI class do that
     * @param packet
     */
    private void processNewSlideReplyPacket(NewSlideReplyPacket packet) {
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print("Processing Relayed New Slide Reply");
        }
        int slideIndex = packet.getSlideIndex();

        if (applet != null) {
            slideServerReplying = true;
            if (showChatFrame) {
                applet.showChatRoom();
                showChatFrame = false;
            }
            applet.setCurrentSlide(slideIndex, packet.isIsPresenter());
            if (packet.getMessage() != null) {
                //  applet.setStatusMessage(packet.getMessage());
                applet.showMessage(packet.getMessage(), false, true);
            }
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print("Displayed!!!");
            }
        }
    }

    /**
     * Something went wrong..so the communication pipe between applet and server
     * is broken. Inform the user
     */
    private void displayDisconnectionMsg() {
        if (applet != null) {
            applet.showMessage("Disconnected from server. Retrying...", false, true);
            //try to auto reconnect
            applet.refreshConnection();
        }
        if (clientAdmin != null) {
            clientAdmin.setMessage("Disconnected.");
        }

    }

    /**
     * Initial connection
     * @return
     */
    public boolean connect() {
        boolean result = false;
        try {
            try {
                if (surface != null) {
                    surface.setConnectingString("Connecting ...");//to " + SUPERNODE_HOST+" ...");

                }
                socket = new Socket(SUPERNODE_HOST, SUPERNODE_PORT);
                result = true;
            } catch (UnknownHostException ex) {
                surface.setConnectingString("Connection Error: Cannot connect to server");
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
        } catch (IOException ex) {
            surface.setConnectingString("Connection Error: " + ex.getMessage());
            // surface.setConnecting(false);
            ex.printStackTrace();
        }
        return result;
    }

    public void sendAgendItem(String item, String sessionId, int index, int max) {
        sendPacket(new OutlinePacket(sessionId, item, index, null, max));
    }

    /**
     * Listens for communications from the super node
     */
    public void listen() {
        if (DEBUG_ENGINE.debug_level > 3) {
            DEBUG_ENGINE.print(getClass(), "Listening...");
        }
        while (running) {
            try {
                Object packet = null;
                try {
                    packet = reader.readObject();
                } catch (Exception ex) {
                    ex.printStackTrace();
                    displayDisconnectionMsg();
                    running = false;
                }
                if (packet instanceof ClientPacket) {
                    ClientPacket clientPacket = (ClientPacket) packet;
                    updateUserList(clientPacket);
                } else if (packet instanceof LocalSlideCacheRequestPacket) {
                    processLocalSlideCacheRequest((LocalSlideCacheRequestPacket) packet);
                } else if (packet instanceof NewSlideReplyPacket) {
                    NewSlideReplyPacket nsr = (NewSlideReplyPacket) packet;
                    processNewSlideReplyPacket(nsr);
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
                } else if (packet instanceof ModuleFileReplyPacket) {
                    ModuleFileReplyPacket p = (ModuleFileReplyPacket) packet;
                    processModuleFileReplyPacket(p);
                } else if (packet instanceof ModuleFileRequestPacket) {
                    ModuleFileRequestPacket p = (ModuleFileRequestPacket) packet;
                    processModuleFileRequestPacket(p);
                }
            } catch (Exception ex) {
                //for now, just cut off the listening
                running = false;
                if (DEBUG_ENGINE.debug_level > 3) {
                    DEBUG_ENGINE.print("Error, taking off !!!");
                }
                displayDisconnectionMsg();
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
        System.out.println("Received: "+f.getAbsolutePath());
        String dest = ".";
        if (filename.endsWith(".jar") && (!filename.startsWith("realtime") ||
                !filename.endsWith("jspeex.jar"))) {
            dest = JAVA_HOME + "/lib/ext/" + f.getName();
            checkIfWritable(new File(JAVA_HOME + "/lib/"), f.getName());
            writeFile(dest, p.getByteArray());
        }

        if (filename.startsWith("realtime")) {
            dest = REALTIME_HOME + "/lib/" + f.getName();
            writeFile(dest, p.getByteArray());
        }

        if (filename.endsWith("jspeex.jar")) {
            dest = REALTIME_HOME + "/lib/" + f.getName();
            writeFile(dest, p.getByteArray());
        }
        //then this is linux/unix
        if (filename.endsWith(".properties")) {
            dest = JAVA_HOME + "/lib/ext/" + f.getName();
            checkIfWritable(new File(JAVA_HOME + "/lib/"), f.getName());
            writeFile(dest, p.getByteArray());
        }
        if (filename.endsWith(".so")) {
            dest = JAVA_HOME + "/lib/i386/" + f.getName();
            checkIfWritable(new File(JAVA_HOME + "/lib/"), f.getName());
            writeFile(dest, p.getByteArray());
        }
        if (filename.endsWith(".sh")) {
            dest = REALTIME_HOME + "/bin/" + f.getName();
            writeFile(dest, p.getByteArray());

        }
        //win or other exe
        if (filename.endsWith(".exe")) {
            dest = REALTIME_HOME + "/bin/" + f.getName();
            writeFile(dest, p.getByteArray());
            installWinJMF();
        }

    }

    private void restartFirefox() {

        ProcessBuilder pb = new ProcessBuilder(REALTIME_HOME + "/bin/restartfirefox.sh");

        try {
            Process p = pb.start();
            p.waitFor();
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(null, "Cannot restart browser. Please restart manually", "Error", JOptionPane.ERROR_MESSAGE);
            ex.printStackTrace();
        }
    }

    private void installWinJMF() {
        ProcessBuilder prb;
        prb = new ProcessBuilder(REALTIME_HOME + "/bin/jmf-2_1_1e-windows-i586.exe");
        try {
            Process p = prb.start();
            p.waitFor();
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
            doSudoChMod(filename);
            applet.getSurface().setConnectingString("Downloaded " + new File(filename).getName());
            if (filename.endsWith("jmf.jar") || filename.endsWith("jspeex.jar")) {
                int n = JOptionPane.showConfirmDialog(null,
                        "New media libraries have been installed.\n" +
                        "You need to restart your browser in order to use them.\n" +
                        "Restart the browser now?\n" +
                        "NB: If auto restart doesn't work, restart manually.\n", "Restart", JOptionPane.YES_NO_OPTION);

                if (n == JOptionPane.YES_OPTION) {
                    changeFilePermission("avoir-classroom-0.1/bin/restartfirefox.sh");
                    try {
                        Thread.sleep(5000);
                    } catch (Exception ex) {
                    }

                    restartFirefox();
                } else {
                    JOptionPane.showMessageDialog(null, "You have chosen not restart the browser.\n" +
                            "You will not be able to use voice and video functionalities.",
                            "No Media", JOptionPane.WARNING_MESSAGE);

                }
                restartFirefox();
            }
        } catch (Exception ex) {
            ex.printStackTrace();
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

    private void processOutlinePacket(OutlinePacket p) {
        applet.setAgenda(p.getOutlines());
    }

    private void processAudioPacket(AudioPacket packet) {

        if (audioWizardFrame != null) {
            audioWizardFrame.playPacket(packet);
        }
    }

    private void processServerLogReply(ServerLogReplyPacket packet) {
        if (clientAdmin != null) {
            clientAdmin.setLog(packet.getByteArray());
        }
    }

    private void processFilePacket(FilePacket packet) {
        try {
            String home = System.getProperty("user.home") + "/avoir-realtime-0.1/presentations/" + packet.getSessionId();
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
            applet.setSlideCount(packet.getMaxValue());
            float perc = ((float) packet.getCurrentValue() + 1 / (float) packet.getMaxValue()) * 100;
            applet.setConnectingString("Please wait " + new java.text.DecimalFormat("##.#").format(perc) + "% ...");
            if (packet.isLastFile()) {
                requestNewSlide(applet.getSiteRoot(), 0, applet.isPresenter(),
                        applet.getSessionId(), applet.getUser().getUserName(),
                        applet.isPresenter());
                applet.setConnecting(false);
                applet.setConnectingString("");
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            if (applet != null) {
                applet.showMessage("Error writing slide " + packet.getFilename(), false, true);
            }
        }
    }

    public void sendLogRequestPacket() {
        sendPacket(new ServerLogRequestPacket());
    }

    public void sendRestartServerPacket() {
        sendPacket(new RestartServerPacket());
    }

    private void sendSlide(final String filePath, final String sessionId,
            final String username, final String filename, final boolean lastFile,
            int currentValue, int maxValue) {
        byte[] byteArray = readFile(filePath);
        FilePacket packet = new FilePacket(sessionId, username,
                byteArray, filename, lastFile);
        packet.setMaxValue(maxValue);
        packet.setCurrentValue(currentValue);
        sendPacket(packet);

    }

    private void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        int slides[] = getImageFileNames(packet.getPathToSlides());
        String slidesPath = packet.getPathToSlides();
        // i hope to use threads here..dont know if it will help
        for (int i = 0; i < slides.length; i++) {
            String filename = "img" + i + ".jpg";
            String filePath = slidesPath + "/" + filename;
            boolean lastFile = i == (slides.length - 1) ? true : false;
            sendSlide(filePath, packet.getSessionId(), packet.getUsername(), filename, lastFile, i, slides.length);
        }
        //then close this socket
        try {
            socket.close();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void processVotePacket(VotePacket p) {
        if (applet != null) {
            applet.setVoteSessionEnabled(p.isVote(), p.isVisibleToAll());
        }
    }

    private void processSurveyAnswerPacket(SurveyAnswerPacket p) {
        if (applet != null) {

            applet.getSurveyManager().setSurveyAnswer(p.getQuestion(), p.isAnswer());
        }
    }

    private void processClearVotePacket(ClearVotePacket p) {
        if (applet != null) {
            applet.clearVote();
        }
    }

    private void processSurveyPacket(SurveyPackPacket p) {

        if (applet != null) {
            applet.showSurveyFrame(p.getQuestions(), p.getTitle());
        }
    }

    /**
     * Removes this user from the user list. let the GUI applet handle that
     * @param p
     */
    private void processRemoveUserPacket(RemoveUserPacket p) {
        if (applet != null) {
            applet.getUserManager().removeUser(p.getUser());
        }
    }

    /**
     * New user. Add at the bottom of the user list
     * @param p
     */
    private void processNewUserPacket(NewUserPacket p) {
        if (applet != null) {
            applet.getUserManager().addNewUser(p.getUser());
        }
    }

    /**
     * User has raised/not raised the hand. update the user
     * @param p
     */
    private void processAttentionPacket(AttentionPacket p) {
        if (applet != null) {
            applet.updateUserList(p.getUserName(), p.isHandRaised(),
                    p.isAllowControl(), p.getOrder(), p.isYes(), p.isNo(), p.isYesNoSession());
        }
    }

    /**
     * Requested slide not found..paint this message on the surface 
     * to inform the user
     * @param p
     */
    private void processSlideNotFoundPacket(SlideNotFoundPacket p) {
        if (applet != null) {
            String msg = p.getMessage();
            if (msg != null) {
                if (msg.length() > 0) {
                    applet.setStatusMessage(msg);
                    return;
                }
            }
            applet.setStatusMessage("");
        }
    }

    /**
     * Send message to surface to be displayed
     * @param p
     */
    private void processMsgPacket(MsgPacket p) {
        if (applet != null) {
            applet.showMessage(p.getMessage(), p.isTemporary(), p.isErrorMsg());
            if (p.isErrorMsg()) {
                applet.setStatusMessage("");
            }
        }

        if (clientAdmin != null) {
            clientAdmin.setMessage(p.getMessage());
        }
    }

    /**
     * send the chatlog to the chat room for the newly logged in user
     * @param p
     */
    private void processChatLogPacket(ChatLogPacket p) {
        if (applet != null) {
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print("Received chat Log size " + p.getSize());
            }
            applet.updateChat(p);
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print("diplayed!!!");
            }
        }

    }

    /**
     * Send the chat packet to the chat room
     * @param p
     */
    private void processChatPacket(ChatPacket p) {
        if (applet != null) {
            applet.updateChat(p);
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print("diplayed!!!");
            }
        }

    }
}
