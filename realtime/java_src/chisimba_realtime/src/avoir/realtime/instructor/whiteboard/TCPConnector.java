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
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.appsharing.RemoteDesktopViewerFrame;

import avoir.realtime.chat.ChatPopup;
import avoir.realtime.chat.PrivateChat;
import avoir.realtime.chat.PrivateChatFrame;
import avoir.realtime.classroom.SessionTimer;
import avoir.realtime.classroom.packets.ClientPacket;
import avoir.realtime.classroom.packets.NewUserPacket;
import avoir.realtime.classroom.packets.PresencePacket;
import avoir.realtime.common.TCPSocket;
import avoir.realtime.classroom.packets.RemoveUserPacket;
import avoir.realtime.classroom.packets.WhiteboardItems;
import avoir.realtime.classroom.packets.WhiteboardPacket;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.classroom.whiteboard.item.Item;
import avoir.realtime.common.Constants;
import avoir.realtime.common.packet.ChatLogPacket;
import avoir.realtime.common.packet.ChatPacket;
import avoir.realtime.common.packet.ClassroomFile;
import avoir.realtime.common.packet.ClassroomFileLog;
import avoir.realtime.common.packet.ClassroomSlidePacket;
import avoir.realtime.common.packet.FilePacket;
import avoir.realtime.common.packet.FileUploadPacket;
import avoir.realtime.common.packet.MsgPacket;
import avoir.realtime.common.packet.NewSlideReplyPacket;
import avoir.realtime.common.packet.RealtimePacket;
import avoir.realtime.common.packet.RequestScrape;
import avoir.realtime.common.packet.SessionImg;
import avoir.realtime.common.user.User;
import avoir.realtime.instructor.packets.LocalSlideCacheRequestPacket;
import avoir.realtime.instructor.packets.NewSlideRequestPacket;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.io.File;
import java.io.FileOutputStream;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.util.HashMap;
import java.util.Map;
import java.util.Timer;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JTabbedPane;

/**
 *
 * @author developer
 */
public class TCPConnector extends TCPSocket {

    protected Classroom mf;
    protected RemoteDesktopViewerFrame viewer;
    protected ChatPopup chatPopup = new ChatPopup();
    protected Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    protected Object packet;
    protected Whiteboard whiteboardSurface;
    private JTabbedPane pane;
    private boolean initialSlideRequest = true;
    private Timer timer = new Timer();
    private Map<String, PrivateChatFrame> privateChats = new HashMap<String, PrivateChatFrame>();

    public TCPConnector(Classroom mf) {
        this.mf = mf;

    }

    @Override
    public Map<String, PrivateChatFrame> getPrivateChats() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public boolean connectToServer(String host, int port) {
        if (connect(host, port)) {
            timer.cancel();
            timer = new Timer();
            timer.scheduleAtFixedRate(new SessionTimer(mf), 0, 1000);
            mf.getSessionManager().startSession();
            publish(mf.getUser());
            return true;
        }
        return false;
    }

    public void startSession() {
        running = true;
        connected = true;

        timer.cancel();
        timer = new Timer();
        timer.scheduleAtFixedRate(new SessionTimer(mf), 0, 1000);
        publish(mf.getUser());

        mf.getSessionManager().startSession();
        Thread t = new Thread() {

            public void run() {
                mf.getTcpConnector().listen();
            }
        };
        t.start();
    }

    /**
     *
     * Requests a specific slide
     * @param slidesDir
     * @param slideIndex
     */
    public void requestNewSlide(String siteRoot, int slideIndex,
            boolean isPresenter, String sessionId, String userId,
            boolean hasControl, String presentationName, boolean isWebPresent) {
        NewSlideRequestPacket p = new NewSlideRequestPacket(siteRoot,
                slideIndex,
                isPresenter,
                sessionId,
                userId, presentationName, isWebPresent);
        p.setControl(hasControl);
        p.setSlideServerId(mf.getUser().getSlideServerId());
        sendPacket(p);
        Constants.log("Requested new slide");
    }

    public String[] createSlideNames(int[] n) {
        String[] names = new String[n.length];
        for (int i = 0; i <
                names.length; i++) {
            names[i] = "Slide" + (i + 1);
        }

        return names;
    }

    public void setWhiteboardSurfaceHandler(Whiteboard whiteboardSurface) {
        this.whiteboardSurface = whiteboardSurface;
        mf.setWhiteBoardSurface(whiteboardSurface);
    }

    @Override
    public synchronized void sendPacket(RealtimePacket p) {
        if (p instanceof WhiteboardPacket) {
            WhiteboardPacket wb = (WhiteboardPacket) p;
            wb.setForward(mf.getMenuManager().shareWhiteboard());
            super.sendPacket(wb);
        } else if (p instanceof PresencePacket) {
            PresencePacket pp = (PresencePacket) p;
            pp.setForward(mf.getMenuManager().shareWhiteboard());
            super.sendPacket(pp);
        } else {
            super.sendPacket(p);
        }
    }

    /**
     * preload the slides to the applet
     * @param slidesServerId
     */
    public void requestForSlides(String sessinId, String slidesServerId, String pathToSlides, String username) {
        sendPacket(new LocalSlideCacheRequestPacket(sessinId, slidesServerId, pathToSlides, username));

    }

    public void setRunning(boolean running) {
        this.running = running;
    }

    @Override
    public void listen() {

        while (running) {

            try {
                packet = null;
                try {

                    packet = reader.readObject();
                    //System.out.println(packet.getClass());
                    connected = true;
                    if (packet instanceof ClientPacket) {
                        ClientPacket clientPacket = (ClientPacket) packet;
                        mf.getUserListManager().updateUserList(clientPacket.getUsers());
                    } else if (packet instanceof NewUserPacket) {
                        NewUserPacket p = (NewUserPacket) packet;
                        mf.getUserListManager().addNewUser(p.getUser());
                        mf.getChatRoom().update(new ChatPacket("System", p.getUser().getUserName() + " joined room.",
                                "", "", p.getSessionId(), new Color(0, 131, 0), "SansSerif", 1, 15, false, null));

                    } else if (packet instanceof RemoveUserPacket) {
                        RemoveUserPacket p = (RemoveUserPacket) packet;
                        mf.getUserListManager().removeUser(p.getUser());
                        mf.getChatRoom().update(new ChatPacket("System", p.getUser().getUserName() + " left room.",
                                "", "", p.getSessionId(), new Color(0, 131, 0), "SansSerif", 1, 15, false, null));

                    } else if (packet instanceof PresencePacket) {
                        PresencePacket p = (PresencePacket) packet;
                        int userIndex = mf.getUserListManager().getUserIndex(p.getUserName());
                        if (userIndex > -1) {
                            mf.getUserListManager().setUser(userIndex, p.getPresenceType(), p.isShowIcon(), true);
                        }
                    } else if (packet instanceof ChatPacket) {
                        ChatPacket p = (ChatPacket) packet;
                        processChatPacket(p);
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
                    } else if (packet instanceof MsgPacket) {
                        MsgPacket p = (MsgPacket) packet;
                        if (p.isErrorMsg()) {
                            mf.showInfoMessage(p.getMessage());
                        } else {
                            mf.showInfoMessage(p.getMessage());
                        }
                    } else if (packet instanceof RequestScrape) {
                        if (pane == null) {
                            pane = mf.getMainTabbedPane();
                        }
                        Point location = pane.getSelectedComponent().getLocationOnScreen();
                        int width = mf.getWhiteboard().getWidth();
                        int height = mf.getWhiteboard().getHeight();
                        mf.getMenuManager().getScreenScraper().setFullScrapeRect(new Rectangle(location.x, location.y, width, height));
                        mf.getMenuManager().getScreenScraper().scrapeRequested();

                    } else if (packet instanceof FilePacket) {
                        FilePacket p = (FilePacket) packet;
                        processFilePacket(p);
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
            refreshConnection();
        }
    }

    public void processFilePacket(FilePacket packet) {
        try {
            String home = avoir.realtime.common.Constants.getRealtimeHome() + "/presentations/" + packet.getSessionId();
            if (!mf.isWebPresent()) {
                home = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + packet.getPresentationName();

            }
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
            mf.getSessionManager().setSlideCount(packet.getMaxValue());
            mf.setSelectedFile(packet.getPresentationName());
            //float perc = ((float) packet.getCurrentValue() + 1 / (float) packet.getMaxValue()) * 100;
            //  base.getSurface().setConnectingString("Please wait " + new java.text.DecimalFormat("##.#").format(perc) + "% ...");

            if (initialSlideRequest) {
                requestNewSlide(mf.getUser().getSiteRoot(), 0, mf.getUser().isPresenter(),
                        mf.getUser().getSessionId(), mf.getUser().getUserName(),
                        mf.getUser().isPresenter(), packet.getPresentationName(), true);
                mf.getArchiveManager().addDefaultArchive(packet.getPresentationName());
                initialSlideRequest = false;
                mf.getWhiteboard().setCurrentSlide(new ImageIcon(home + "/img0.jpg"), 0, packet.getMaxValue(), true);

            }

        } catch (Exception ex) {
            ex.printStackTrace();
            mf.showErrorMessage("Error writing slide " + packet.getFilename());


        }
    }
    /**
     * New slide. Look for it in the local cache and display it.
     * But let the GUI class do that
     * @param packet
     */
    boolean firstTimeSlideReply = true;

    public void processNewSlideReplyPacket(NewSlideReplyPacket packet) {
        int slideIndex = packet.getSlideIndex();

        String slidePath = Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId() + "/img" + slideIndex + ".jpg";
        if (firstTimeSlideReply) {
            showThumbNails(mf.getSelectedFile(), Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId());
        }
        if (packet.isWebPresent()) {
            if (firstTimeSlideReply) {
                showThumbNails(mf.getSelectedFile(), Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId());
            }
        } else {
            slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + packet.getPresentationName() + "/img" + slideIndex + ".jpg";
            if (firstTimeSlideReply) {
                showThumbNails(mf.getSelectedFile(), Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + packet.getPresentationName());
            }

        }
        mf.getSessionManager().setCurrentSlide(slideIndex, packet.isIsPresenter(), slidePath);
        if (packet.getMessage() != null) {
            mf.showInfoMessage(packet.getMessage());
        }
        mf.getWhiteboard().repaint();
        mf.setSelectedFile(packet.getPresentationName());
    }

    private void showThumbNails(String presentationName, String path) {

        if (!mf.getSelectedFile().equals(presentationName) || firstTimeSlideReply) {

            int[] slidesList = mf.getConnector().getImageFileNames(path);
            mf.getWhiteboard().clearThumbNails();

            for (int i = 0; i < slidesList.length; i++) {
                String imgPath = path + "/img" + slidesList[i] + ".jpg";
                Image srcImg = new ImageIcon(imgPath).getImage();
                mf.getWhiteboard().addThumbNail(mf.getWhiteboard().getWhiteboardManager().getScaledImage(srcImg), i, 0, true);
            }
            mf.getWhiteboard().repaint();
            firstTimeSlideReply = false;
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

    public void processWhiteboardItems(WhiteboardItems p) {
        mf.getWhiteboard().setItems(p.getWhiteboardItems());
        Vector<Item> items = p.getWhiteboardItems();

        for (int i = 0; i < items.size(); i++) {
            Item item = items.elementAt(i);
            if (item instanceof Img) {
                Img img = (Img) item;
                String filePath = Constants.getRealtimeHome() + "/classroom/images/" + mf.getUser().getSessionId() + "/" + img.getImagePath();
                if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
                    sendPacket(new SessionImg(filePath, img));
                } else {

                    mf.loadCachedImage(img, filePath);

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
        mf.getWhiteboard().setImage(new ImageIcon(path));

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
                mf.getClassroomManager().showWebpage(p.getPath(), p.getId(), true);//a log is always treated as new
            }
        }
    }

    private void processFile(String filePath, ClassroomFile p) {
        if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
            sendPacket(p);
        } else {
            if (p.getType() == Constants.FLASH) {
                mf.getClassroomManager().showFlashPlayer(filePath, p.getId(), p.getSessionId());
            }
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

            Image srcImg = new ImageIcon(home + "/img" + packet.getCurrentValue() + ".jpg").getImage();
            if (packet.getCurrentValue() == 0) {
                mf.getWhiteboard().clearThumbNails();
            }
            mf.getWhiteboard().addThumbNail(mf.getWhiteboard().getWhiteboardUtil().getScaledImage(srcImg), packet.getCurrentValue(), 0, true);
            mf.getWhiteboard().repaint();

            mf.setSelectedFile(packet.getPresentationName());
            //mf.setSlideCount(packet.getMaxValue());
            if (packet.isLastFile()) {
                //    base.getSessionManager().setSlideCount(packet.getMaxValue());
                int[] slidesList = mf.getTcpConnector().getImageFileNames(home);

                mf.getArchiveManager().addArchive(createSlideNames(slidesList), packet.getPresentationName());
                mf.getWhiteBoardSurface().setCurrentSlide(new ImageIcon(home + "/img0.jpg"), 0, slidesList.length, true);

            }
        } catch (Exception ex) {
            ex.printStackTrace();

            mf.showErrorMessage("Error writing slide " + packet.getFilename());


        }
    }

    private void animateTab() {
        if (mf.getMainTabbedPane().getSelectedIndex() != 0) {
            mf.getClassroomManager().animateTabTitle(mf.getMainTabbedPane(), 0);
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
                privateChats.put(p.getId(), privateChatFrame);
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
            System.out.println("Disconnected from server. Reconnecting to " + host + ":" + port + " " + count + " of " + max + "...");
            if (connect(host, port)) {

                publish(mf.getUser());
                break;
            }
            if (count > max) {
                break;
            }
            sleep(5000);
            count++;

        }
        if (!connected) {
            System.out.println("Connection to server failed. Contact your system administrator.");

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
