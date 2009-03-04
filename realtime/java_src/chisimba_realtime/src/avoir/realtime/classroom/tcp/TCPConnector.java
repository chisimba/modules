/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.classroom.tcp;

import avoir.realtime.appsharing.AppView;
import avoir.realtime.appsharing.RemoteDesktopViewerFrame;
import avoir.realtime.appsharing.tcp.StopScreenSharing;

import avoir.realtime.chat.ChatPopup;
import avoir.realtime.chat.PrivateChatFrame;
import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.classroom.SessionTimer;
import avoir.realtime.common.packet.ClientPacket;
import avoir.realtime.common.packet.NewUserPacket;
import avoir.realtime.common.packet.PresencePacket;
import avoir.realtime.common.TCPSocket;
import avoir.realtime.common.packet.RemoveUserPacket;
import avoir.realtime.common.packet.WhiteboardItems;
import avoir.realtime.common.packet.WhiteboardPacket;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.classroom.whiteboard.item.Item;
import avoir.realtime.common.Constants;
import avoir.realtime.common.FileManager;
import avoir.realtime.common.packet.AudioPacket;
import avoir.realtime.common.packet.ChatLogPacket;
import avoir.realtime.common.packet.ChatPacket;
import avoir.realtime.common.packet.ClassroomFile;
import avoir.realtime.common.packet.ClassroomFileLog;
import avoir.realtime.common.packet.ClassroomSlidePacket;
import avoir.realtime.common.packet.CurrentSessionSlidePacket;
import avoir.realtime.common.packet.DesktopPacket;
import avoir.realtime.common.packet.FilePacket;
import avoir.realtime.common.packet.FileUploadPacket;
import avoir.realtime.common.packet.FileViewReplyPacket;
import avoir.realtime.common.packet.MsgPacket;
import avoir.realtime.common.packet.NewSlideReplyPacket;
import avoir.realtime.common.packet.NotepadLogPacket;
import avoir.realtime.common.packet.NotepadPacket;
import avoir.realtime.common.packet.PointerPacket;
import avoir.realtime.common.packet.QuestionPacket;
import avoir.realtime.common.packet.SessionImg;
import avoir.realtime.common.packet.SurveyPackPacket;
import avoir.realtime.common.user.User;
import avoir.realtime.common.packet.LocalSlideCacheRequestPacket;
import avoir.realtime.common.packet.MobileUsersPacket;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectOutput;
import java.io.ObjectOutputStream;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Timer;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;

/**
 *
 * @author developer
 */
public class TCPConnector extends TCPSocket {

    protected ClassroomMainFrame mf;
    protected RemoteDesktopViewerFrame viewer;
    protected ChatPopup chatPopup = new ChatPopup();
    protected Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    protected Object packet;
    private Timer timer = new Timer();
    private boolean showAppSharingFrame = true;
    private AppView appViewer;
    private JScrollPane whiteboardScrollPane;
    private Map<String, PrivateChatFrame> privateChats = new HashMap<String, PrivateChatFrame>();
    private SessionTimer sessionTimer;
    private String selectedFilePath;
    private String fileManagerMode = ".";
    private FileManager fileManager;

    public TCPConnector(ClassroomMainFrame mf) {
        this.mf = mf;
        sessionTimer = new SessionTimer(mf);
    }

    @Override
    public Map<String, PrivateChatFrame> getPrivateChats() {
        return privateChats;
    }

    public SessionTimer getSessionTimer() {
        return sessionTimer;
    }

    public String getSelectedFilePath() {
        return selectedFilePath;
    }

    @Override
    public ClassroomMainFrame getMf() {
        return mf;
    }

    public String getFileManagerMode() {
        return fileManagerMode;
    }

    public void setFileManagerMode(String fileManagerMode) {
        this.fileManagerMode = fileManagerMode;
    }

    public void setSelectedFilePath(String selectedFilePath) {
        this.selectedFilePath = selectedFilePath;
    }

    private String stripExt(String filename) {
        int index = filename.lastIndexOf(".");
        if (index > -1) {
            return filename.substring(0, index);
        }
        return filename;
    }

    public void processFileVewReplyPacket(FileViewReplyPacket p) {
       
        ArrayList<String> filters = new ArrayList<String>();
        if (fileManager == null) {
            fileManager = new FileManager(p.getList(), this,null);
            fileManager.setSize(500, 400);
            fileManager.setLocationRelativeTo(null);
        }
        if (fileManagerMode.equals("notepad")) {
            
            fileManager.getUploadButton().setEnabled(false);
            fileManager.getSelectButton().setText("Open");
        }
        if (fileManagerMode.equals("document")) {
            fileManager.getUploadButton().setEnabled(true);
            fileManager.getSelectButton().setText("Open");
        }
        fileManager.setFiles(p.getList());

        fileManager.setVisible(true);
    }

    public void checkForSlides() {

        System.out.println("in start session");
        String slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + stripExt(mf.getUser().getSessionTitle());

        final File f = new File(slidePath);//avoir.realtime.common.Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId());
        //first check if it exist in local cache, if so dont bother with downloads
        //unless the 'UseCache option is off, so it forces downloads every time
        if (f.exists() && f.list().length > 1) {
            Constants.log("Slides detected in cache");
            if (mf.getRealtimeOptions().useCache()) {

                return;
            }
        } else {
            System.out.println("Nothing in cache..requesting for slides from " + mf.getUser().getSlideServerId() + " session " + mf.getUser().getSessionId());
            Constants.log("Nothing in cache..requesting for slides from " + mf.getUser().getSlideServerId() + " session " + mf.getUser().getSessionId());
            f.mkdirs();
            //     mf.getConnector().requestForSlides(mf.getUser().getSessionId(), mf.getUser().getSlideServerId(),
            //           mf.getUser().getSlidesDir(), mf.getUser().getUserName());
            sendPacket(new LocalSlideCacheRequestPacket(mf.getUser().getSessionId(), mf.getUser().getSlideServerId(),
                    mf.getUser().getSlidesDir(), mf.getUser().getUserName()));

        }

    }

    @Override
    public void startSession() {

        mf.initToolbar();
        mf.initAudio();
        connected = true;
        running = true;
        timer.cancel();
        timer = new Timer();
        timer.scheduleAtFixedRate(sessionTimer, 0, 1000);
        publish(mf.getUser());
        Thread t = new Thread() {

            public void run() {
                listen();
            }
        };
        t.start();
        checkForSlides();
        /* PresencePacket p = new PresencePacket(
        mf.getUser().getSessionId(),
        PresenceConstants.USER_ACTIVE_ICON,
        PresenceConstants.USER_ACTIVE,
        mf.getUser().getUserName());
        p.setForward(true);

        sendPacket(p);
         */
    }

    @Override
    public boolean connect(String host, int port) {
        if (super.connect(host, port)) {
            timer.cancel();
            timer = new Timer();
            timer.scheduleAtFixedRate(new SessionTimer(mf), 0, 1000);
            publish(mf.getUser());

            return true;
        }
        return false;
    }

    @Override
    public void listen() {
        while (running) {

            try {
                packet = null;
                try {
                    packet = reader.readObject();
                    //  System.out.println(packet.getClass());
                    connected = true;
                    if (packet instanceof ClientPacket) {
                        ClientPacket clientPacket = (ClientPacket) packet;
                        mf.getUserListManager().updateUserList(clientPacket.getUsers());
                    } else if (packet instanceof NewUserPacket) {
                        NewUserPacket p = (NewUserPacket) packet;
                        mf.getUserListManager().addNewUser(p.getUser());
                        mf.getChatRoom().update(new ChatPacket("System", p.getUser().getUserName() + " joined room.", "", "",
                                p.getSessionId(), Color.GRAY, "SansSerif", 0, 12, false, null));
                    } else if (packet instanceof RemoveUserPacket) {
                        RemoveUserPacket p = (RemoveUserPacket) packet;
                        mf.getUserListManager().removeUser(p.getUser());
                        mf.getChatRoom().update(new ChatPacket("System", p.getUser().getUserName() + " left room.", "",
                                "", p.getSessionId(), Color.GRAY, "SansSerif", 0, 12, false, null));
                    } else if (packet instanceof NotepadLogPacket) {
                        // mf.getToolBar().addSubButton("/icons/application_cascade.png", "savednotepad", "Notepad", "notepad");
                        // JOptionPane.showMessageDialog(null, "got " + ((NotepadLogPacket) packet).getNotepads().size());
                        saveNotepads((NotepadLogPacket) packet);
                    } else if (packet instanceof PresencePacket) {
                        PresencePacket p = (PresencePacket) packet;
                        int userIndex = mf.getUserListManager().getUserIndex(p.getUserName());
                        if (userIndex > -1) {
                            mf.getUserListManager().setUser(userIndex, p.getPresenceType(), p.isShowIcon(), true);
                        }
                    } else if (packet instanceof FileViewReplyPacket) {
                        processFileVewReplyPacket((FileViewReplyPacket) packet);
                    } else if (packet instanceof MobileUsersPacket) {
                        processMobileUsersPacket((MobileUsersPacket) packet);
                    } else if (packet instanceof ChatPacket) {
                        ChatPacket p = (ChatPacket) packet;
                        processChatPacket(p);
                    } else if (packet instanceof PointerPacket) {
                        PointerPacket p = (PointerPacket) packet;
                        mf.getWhiteBoardSurface().setCurrentPointer(p.getType(), p.getPoint());
                    } else if (packet instanceof ChatLogPacket) {
                        ChatLogPacket p = (ChatLogPacket) packet;
                        mf.updateChat(p);
                    } else if (packet instanceof MsgPacket) {
                        MsgPacket p = (MsgPacket) packet;
                        mf.showInfoMessage(p.getMessage());
                    } else if (packet instanceof FileUploadPacket) {
                        animateTab();
                        mf.getFileRecieverManager().processFileDownload((FileUploadPacket) packet);
                    } else if (packet instanceof ClassroomSlidePacket) {
                        processClassroomSlidePacket((ClassroomSlidePacket) packet);
                    } else if (packet instanceof WhiteboardPacket) {
                        WhiteboardPacket p = (WhiteboardPacket) packet;
                        processWhiteboardPacket(p);
                        animateTab();
                    } else if (packet instanceof ClassroomFileLog) {
                        ClassroomFileLog p = (ClassroomFileLog) packet;
                        processClassromFilesLog(p);
                    } else if (packet instanceof WhiteboardItems) {
                        processWhiteboardItems((WhiteboardItems) packet);
                    } else if (packet instanceof NewSlideReplyPacket) {
                        NewSlideReplyPacket nsr = (NewSlideReplyPacket) packet;
                        processNewSlideReplyPacket(nsr);
                    } else if (packet instanceof CurrentSessionSlidePacket) {
                        CurrentSessionSlidePacket p = (CurrentSessionSlidePacket) packet;
                        processCurrentSessionSlidePacket(p);
                    } else if (packet instanceof SurveyPackPacket) {
                        SurveyPackPacket pac = (SurveyPackPacket) packet;
                        mf.showSurveyFrame(pac.getQuestions(), pac.getTitle());
                    } else if (packet instanceof QuestionPacket) {
                        QuestionPacket p = (QuestionPacket) packet;
                        mf.initAnswerFrame(p, p.getQuestion());
                    } else if (packet instanceof DesktopPacket) {
                        DesktopPacket p = (DesktopPacket) packet;
                        processDesktopPacket(p);
                    } else if (packet instanceof StopScreenSharing) {
                        if (whiteboardScrollPane != null) {
                            mf.getMainTabbedPane().remove(0);
                            mf.getMainTabbedPane().addTab("Default", whiteboardScrollPane);
                            showAppSharingFrame = true;
                        }
                    } else if (packet instanceof FilePacket) {
                        FilePacket p = (FilePacket) packet;
                        processFilePacket(p);
                    } else if (packet instanceof AudioPacket) {
                        AudioPacket p = (AudioPacket) packet;

                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    connected = false;
                    running = false;
                    break;
                }

            } catch (Exception ex) {
                //for now, just cut off the listening
                running = false;
                connected = false;

                ex.printStackTrace();
            }
        }

        if (!connected && !running) {
            timer.cancel();
            refreshConnection();
        }
    }

    private void processMobileUsersPacket(MobileUsersPacket p) {
        mf.getUserListManager().addMobileUserList(p.getUsers());
    }

    private void saveNotepads(NotepadLogPacket p) {
        String path = Constants.getRealtimeHome() + "/notepad/";
        File f = new File(path);
        f.mkdirs();
        for (int i = 0; i < p.getNotepads().size(); i++) {
            try {
                NotepadPacket note = p.getNotepads().get(i);
                String name = note.getFilename();
                if (!name.endsWith(".txt")) {
                    name += ".txt";
                }

                FileOutputStream fstrm = new FileOutputStream(new File(path + "/" + name));
                ObjectOutput ostrm = new ObjectOutputStream(fstrm);
                ostrm.writeObject(note.getDocument());
                ostrm.flush();

            } catch (IOException io) {
                System.err.println("IOException: " + io.getMessage());
            }
        }
    }

    public void processDesktopPacket(final DesktopPacket packet) {


        if (showAppSharingFrame) {
            int hh = packet.getData().getHeight();
            int ww = packet.getData().getWidth();

            viewer = new RemoteDesktopViewerFrame();

            //viewer.setSize(ww, hh);
            appViewer = new AppView(viewer.getWidth(), viewer.getHeight());
            //viewer.setContentPane(appViewer);
            whiteboardScrollPane = (JScrollPane) mf.getMainTabbedPane().getComponent(0);
            mf.getMainTabbedPane().remove(0);
            mf.getMainTabbedPane().addTab("Application Sharing", appViewer);
            //viewer.setVisible(true);
            showAppSharingFrame = false;
        }

        Thread t = new Thread() {

            public void run() {

                appViewer.pixelUpdate(packet.getData());
            }
        };
        t.start();
    }

    private void processCurrentSessionSlidePacket(CurrentSessionSlidePacket p) {
        String dir = p.getPresentationName() + "/img" + p.getSlideIndex() + ".jpg";
        String home = avoir.realtime.common.Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + dir;
        System.out.println(home);
        try {
            mf.getWhiteBoardSurface().setCurrentSlide(new ImageIcon(home), p.getSlideIndex(), 0, true);
            mf.getWhiteBoardSurface().repaint();
        } catch (Exception ex) {
            ex.printStackTrace();

        }
    }

    public void processWhiteboardPacket(WhiteboardPacket p) {
        if (p.getStatus() == avoir.realtime.common.Constants.ADD_NEW_ITEM) {
            mf.getWhiteBoardSurface().addItem(p.getItem());
        }
        if (p.getStatus() == avoir.realtime.common.Constants.REPLACE_ITEM) {

            mf.getWhiteBoardSurface().replaceItem(p.getItem());
        }
        if (p.getStatus() == avoir.realtime.common.Constants.REMOVE_ITEM) {

            mf.getWhiteBoardSurface().removeItem(p.getItem());
        }
        if (p.getStatus() == avoir.realtime.common.Constants.CLEAR_ITEMS) {

            mf.getWhiteBoardSurface().clearItems();
        }
    }

    public void processFilePacket(FilePacket packet) {
        try {
            //    String home = avoir.realtime.common.Constants.getRealtimeHome() + "/presentations/" + packet.getSessionId();
            String home = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + packet.getPresentationName();

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
            float perc = ((float) packet.getCurrentValue() + 1 / (float) packet.getMaxValue()) * 100;
        } catch (Exception ex) {
            ex.printStackTrace();
            mf.showErrorMessage("Error writing slide " + packet.getFilename());

        }
    }

    public void processWhiteboardItems(WhiteboardItems p) {
        mf.getWhiteBoardSurface().setItems(p.getWhiteboardItems());
        Vector<Item> items = p.getWhiteboardItems();
        for (int i = 0; i < items.size(); i++) {
            Item item = items.elementAt(i);
            if (item instanceof Img) {
                Img img = (Img) item;
                String filePath = Constants.getRealtimeHome() + "/classroom/images/" + mf.getUser().getSessionId() + "/" + img.getImagePath();
                if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
                    sendPacket(new SessionImg(filePath, img));
                } else {                    //  mf.loadCachedImage(img, filePath);
                }
            }

            if (item instanceof Img) {
                Img img = (Img) item;
                String filePath = Constants.getRealtimeHome() + "/classroom/images/" + mf.getUser().getSessionId() + "/" + img.getImagePath();
                if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
                    sendPacket(new SessionImg(filePath, img));
                } else {
                    loadCachedImage(img, filePath);
                }
            }
        }
    }

    /**
     * Here the session is on, and so reload the images in case you broke off before the session
     * ended
     */
    public void loadCachedImage(Img img, String path) {

        File cacheHome = new File(avoir.realtime.common.Constants.getRealtimeHome() + "/classroom/images/" + mf.getUser().getSessionId());
        if (!cacheHome.exists()) {
            return;
        }
        mf.getWhiteBoardSurface().setImage(new ImageIcon(path));

    }

    public void processClassromFilesLog(ClassroomFileLog packet) {
        /**
         * 1. Check if the files exist on local cache
         * 2. request for missing ones
         */
        Vector<ClassroomFile> log = packet.getLog();
        for (int i = 0; i < log.size(); i++) {
            ClassroomFile p = log.elementAt(i);
            if (p.getType() == Constants.PRESENTATION) {
                String filePath = Constants.getRealtimeHome() + "/classroom/presentations/" + mf.getUser().getSessionId() + "/" + p.getPath();
                processFile(filePath, p);

            }
            if (p.getType() == Constants.FLASH) {
                String filePath = Constants.getRealtimeHome() + "/classroom/flash/" + mf.getUser().getSessionId() + "/" + p.getPath();
                processFile(filePath, p);

            }
            if (p.getType() == Constants.WEBPAGE) {
                // mf.getClassroomManager().showWebpage(p.getPath(), p.getId(), true);//a log is always treated as new
            }
        }
    }

    private void processFile(String filePath, ClassroomFile p) {
        if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
            sendPacket(p);
        } else {
            if (p.getType() == Constants.FLASH) {
                //  mf.getClassroomManager().showFlashPlayer(filePath, p.getId(), p.getSessionId());
            }
        }
    }

    private void animateTab() {
        if (mf.getMainTabbedPane().getSelectedIndex() != 0) {
//            mf.getClassroomManager().animateTabTitle(mf.getMainTabbedPane(), 0);
        }
    }

    public void processClassroomSlidePacket(ClassroomSlidePacket packet) {
        try {
            int dot = packet.getPresentationName().lastIndexOf(".");
            String dir = packet.getPresentationName();
            if (dot > 0) {
                dir = packet.getPresentationName().substring(0, dot);
            }
            String home = avoir.realtime.common.Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + dir;
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
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            mf.showErrorMessage("Error writing slide " + packet.getFilename());
        }
    }

    public void processNewSlideReplyPacket(NewSlideReplyPacket packet) {
        int slideIndex = packet.getSlideIndex();
        // String slidePath = Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId() + "/img" + slideIndex + ".jpg";
        // if (!packet.isWebPresent()) {
        String slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + packet.getPresentationName() + "/img" + slideIndex + ".jpg";
        //}

        mf.getWhiteBoardSurface().setCurrentSlide(new ImageIcon(slidePath), slideIndex, slideIndex, true);
        if (packet.getMessage() != null) {
            mf.showInfoMessage(packet.getMessage());
        }

    }

    /**
     * Send the chat packet to the chat room
     * @param p
     */
    public void processChatPacket(ChatPacket p) {
        if (p.isPrivateChat()) {
            PrivateChatFrame privateChatFrame = privateChats.get(p.getId());
            if (privateChatFrame == null) {
                User usr = mf.getUserListManager().getUser(p.getUsr());
                privateChatFrame = new PrivateChatFrame(usr, mf, p.getId());
                privateChats.put(usr.getUserName(), privateChatFrame);
            }
            privateChatFrame.getChatRoom().update(p);
            privateChatFrame.show();

        } else {
            if (!mf.getParentFrame().isActive()) {
                if (!mf.getUser().getUserName().equals(p.getUsr())) {
                    showChatPopup(p.getUsr(), p.getContent());
                }

            }
            mf.updateChat(p);
        }
    }

    private void showChatPopup(String user, String message) {
        chatPopup.setMessage(user, message);
        chatPopup.setLocation(ss.width - 200, ss.height - chatPopup.getHeight() - 100);
        chatPopup.setVisible(true);
    }

    /**
     * If diconnected from server, try to auto re-connect
     */
    public void refreshConnection() {
        connected = false;
        int count = 0;
        int max = 10;
        mf.getChatRoom().getTextPane().setText("");

        while (!connected) {
            mf.showErrorMessage("Disconnected from server. Retrying " + count + " of " + max + "...");
            if (this.connect(host, port)) {
                break;
            }

            if (count > max) {
                break;
            }
            sleep(5000);
            count++;

        }
        if (!connected) {
            mf.showErrorMessage("Connection to server failed.");

        }
    }

    /**
     * Pause for a specified time
     * @param time
     */
    private void sleep(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }
}
