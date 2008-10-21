/*
 * 
 *  Copyright (C) GNU/GPL AVOIR 2008
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
 *  along with this program; if notfchat, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.base.admin.ClientAdmin;
import avoir.realtime.tcp.base.appsharing.AppView;
import avoir.realtime.tcp.base.appsharing.RemoteDesktopViewerFrame;
import avoir.realtime.tcp.base.chat.ChatPopup;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.packet.AttentionPacket;
import avoir.realtime.tcp.common.packet.AudioPacket;
import avoir.realtime.tcp.common.packet.BinaryFileSaveReplyPacket;
import avoir.realtime.tcp.common.packet.BinaryFileSaveRequestPacket;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.ClassroomFile;
import avoir.realtime.tcp.common.packet.ClassroomFileLog;
import avoir.realtime.tcp.common.packet.ClassroomSlidePacket;
import avoir.realtime.tcp.common.packet.ClearVotePacket;
import avoir.realtime.tcp.common.packet.DesktopPacket;
import avoir.realtime.tcp.common.packet.FilePacket;
import avoir.realtime.tcp.common.packet.HeartBeat;
import avoir.realtime.tcp.common.packet.ImagePacket;
import avoir.realtime.tcp.common.packet.LocalSlideCacheRequestPacket;
import avoir.realtime.tcp.common.packet.MsgPacket;
import avoir.realtime.tcp.common.packet.NewSlideReplyPacket;
import avoir.realtime.tcp.common.packet.NewUserPacket;
import avoir.realtime.tcp.common.packet.OutlinePacket;
import avoir.realtime.tcp.common.packet.PointerPacket;
import avoir.realtime.tcp.common.packet.PresencePacket;
import avoir.realtime.tcp.common.packet.QuitPacket;
import avoir.realtime.tcp.common.packet.RemoveDocumentPacket;
import avoir.realtime.tcp.common.packet.RemoveUserPacket;
import avoir.realtime.tcp.common.packet.ServerLogReplyPacket;
import avoir.realtime.tcp.common.packet.SessionImg;
import avoir.realtime.tcp.common.packet.SlideNotFoundPacket;
import avoir.realtime.tcp.common.packet.SurveyAnswerPacket;
import avoir.realtime.tcp.common.packet.SurveyPackPacket;
import avoir.realtime.tcp.common.packet.SwitchTabPacket;
import avoir.realtime.tcp.common.packet.UpdateUserNetworkStatusPacket;
import avoir.realtime.tcp.common.packet.VotePacket;
import avoir.realtime.tcp.common.packet.WhiteboardItems;
import avoir.realtime.tcp.common.packet.WhiteboardPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileReplyPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;
import avoir.realtime.tcp.whiteboard.item.Img;
import avoir.realtime.tcp.whiteboard.item.Item;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.io.File;
import java.io.FileOutputStream;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JFileChooser;
import javax.swing.JOptionPane;

/**
 * this class consumes packets from the server
 * @author David Wafula
 */
public class TCPConsumer {

    private JFileChooser fc = new JFileChooser();
    private TCPClient tcpClient;
    private RealtimeBase base;
    private ClientAdmin clientAdmin;
    private boolean showChatFrame = true;
    private boolean showAppSharingFrame = true;
    private RemoteDesktopViewerFrame viewer;
    private ChatPopup chatPopup = new ChatPopup();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private boolean showTitle;
    private int prevIndex = 0;
    private AppView appViewer;

    public TCPConsumer(TCPClient tcpClient, RealtimeBase base) {
        this.tcpClient = tcpClient;
        this.base = base;
    }

    public TCPConsumer(TCPClient tcpClient) {
        this.tcpClient = tcpClient;

    }

    public TCPConsumer(TCPClient tcpClient, ClientAdmin clientAdmin) {
        this.tcpClient = tcpClient;
        this.clientAdmin = clientAdmin;
    }

    /**
     * send the chatlog to the chat room for the newly logged in user
     * @param p
     */
    public void processChatLogPacket(ChatLogPacket p) {
        if (base != null) {
            base.updateChat(p);
        }

    }

    /**
     * User has raised/not raised the hand. update the user
     * @param p
     */
    public void processAttentionPacket(AttentionPacket p) {
        /*        if (base != null) {
        base.getUserManager().updateUserList(p.getUserName(), p.isHandRaised(),
        p.isAllowControl(), p.getOrder(), p.isYes(), p.isNo(), p.isYesNoSession(), p.isSpeakerEnabled(), p.isMicEnabled());
        }
         */
    }

    // ///////////////////////////////////////////////////////////////////
    // These methods are for acting on the requests send from the server//
    // They all start with process preappended to the name              //
    /////////////////////////////////////////////////////////////////////
    /**
     * This handles the audio packets
     * @param packet
     */
    public void processAudioPacket(AudioPacket packet) {
        if (tcpClient.getAudioHandler() != null) {
            tcpClient.getAudioHandler().playPacket(packet);

        }


    }

    public void processQuitPacket(QuitPacket p) {
        //just quit but not good this way...we need a way of checking if this
        //was meant for us and not just a broadcast (DOF Attacks :) )
        System.exit(0);
    }

    /**
     * Removes this user from the user list. let the GUI applet handle that
     * @param p
     */
    public void processRemoveUserPacket(RemoveUserPacket p) {
        if (base != null) {
            base.getUserManager().removeUser(p.getUser());
        }

    }

    /**
     * Send message to surface to be displayed
     * @param p
     */
    public void processMsgPacket(MsgPacket p) {
        if (base != null) {
            base.showMessage(p.getMessage(), p.isTemporary(), p.isErrorMsg(), p.getMessageType());
            if (p.isErrorMsg()) {
                base.setStatusMessage("");
            }

        }

        if (clientAdmin != null) {
            clientAdmin.setMessage(p.getMessage());
        }

    }

    public void processClassroomFile(ClassroomFile p) {
        if (p.getType() == Constants.WEBPAGE) {
            if (p.isNewFile()) {
                base.getBaseManager().showWebpage(p.getPath(), p.getId(), p.isNewFile());
            } else {
                base.getBaseManager().showWebpage(p.getPath(), p.getOldId(), p.isNewFile());
            }

        }
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
                String filePath = Constants.getRealtimeHome() + "/classroom/presentations/" + base.getSessionId() + "/" + p.getPath();
                processFile(filePath, p);

            }
            if (p.getType() == Constants.FLASH) {
                String filePath = Constants.getRealtimeHome() + "/classroom/flash/" + base.getSessionId() + "/" + p.getPath();
                processFile(filePath, p);

            }
            if (p.getType() == Constants.WEBPAGE) {
                base.getBaseManager().showWebpage(p.getPath(), p.getId(), true);//a log is always treated as new
            }
        }
    }

    private void removeTab(String name) {
        for (int i = 1; i < base.getMainTabbedPane().getTabCount(); i++) {

            if (base.getMainTabbedPane().getToolTipTextAt(i).equals(name)) {
                base.getMainTabbedPane().remove(i);
            }
        }
    }

    public void processRemoveDocumentPacket(RemoveDocumentPacket p) {
        ;
        if (p.getType() == Constants.FLASH) {
            for (int i = 0; i < base.getFlashFiles().size(); i++) {
                if (base.getFlashFiles().elementAt(i).getId().equals(p.getId())) {
                    removeTab(base.getFlashFiles().elementAt(i).getFilename());
                }
            }
        }
        if (p.getType() == Constants.WEBPAGE) {
            for (int i = 0; i < base.getWebPages().size(); i++) {

                if (base.getWebPages().elementAt(i).getId().equals(p.getId())) {
                    removeTab(base.getWebPages().elementAt(i).getUrl());
                }
            }
        }
    }

    private void processFile(String filePath, ClassroomFile p) {
        if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
            tcpClient.sendPacket(p);
        } else {
            if (p.getType() == Constants.FLASH) {
                base.getBaseManager().showFlashPlayer(filePath, p.getId(), p.getSessionId());
            }
        }
    }

    public void processFilePacket(FilePacket packet) {
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
            base.setSelectedFile(packet.getPresentationName());
            float perc = ((float) packet.getCurrentValue() + 1 / (float) packet.getMaxValue()) * 100;
            base.getSurface().setConnectingString("Please wait " + new java.text.DecimalFormat("##.#").format(perc) + "% ...");

            if (!tcpClient.isInitSlideRequest()) {
                tcpClient.requestNewSlide(base.getSiteRoot(), 0, base.isPresenter(),
                        base.getSessionId(), base.getUser().getUserName(),
                        base.isPresenter(), packet.getPresentationName(), true);
                base.getAgendaManager().addDefaultAgenda(packet.getPresentationName());
                tcpClient.setInitSlideRequest(true);
                base.getWhiteboardSurface().setCurrentSlide(new ImageIcon(home + "/img0.jpg"), 0, packet.getMaxValue(), true);

            }
            tcpClient.setSlidesDownloaded(true);
        } catch (Exception ex) {
            ex.printStackTrace();
            if (base != null) {
                base.showMessage("Error writing slide " + packet.getFilename(), false, true, MessageCode.ALL);
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

            String home = avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/classroom/slides/" + base.getSessionId() + "/" + dir;

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
            if (packet.isLastFile()) {
                base.getSessionManager().setSlideCount(packet.getMaxValue());
                int[] slidesList = tcpClient.getImageFileNames(home);

                base.getAgendaManager().addAgenda(tcpClient.createSlideNames(slidesList), packet.getPresentationName());
                base.getWhiteboardSurface().setCurrentSlide(new ImageIcon(home + "/img0.jpg"), 0, slidesList.length, true);

            }
        } catch (Exception ex) {
            ex.printStackTrace();
            if (base != null) {
                base.showMessage("Error writing slide " + packet.getFilename(), false, true, MessageCode.ALL);
            }

        }
    }

    public void processBinaryFileSaveReplyPacket(BinaryFileSaveReplyPacket p) {
        tcpClient.getFileTransfer().processBinaryFileSaveReplyPacket(p.getFileName(), p.getFullName(), p.isAccepted());
    }

    public void processBinaryFileSaveRequestPacket(BinaryFileSaveRequestPacket p) {
        String filename = new File(p.getFileName()).getName();
        int n = JOptionPane.showConfirmDialog(base, p.getSenderName() + " has send you file " + filename + "\n" +
                "Accept?", "New File", JOptionPane.YES_NO_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            fc.setSelectedFile(new File(filename));
            if (fc.showSaveDialog(null) == JFileChooser.APPROVE_OPTION) {
                tcpClient.getFileReceiverManager().setFilename(fc.getSelectedFile().getAbsolutePath());
                tcpClient.sendPacket(new BinaryFileSaveReplyPacket(p.getSessionId(), p.getFileName(), base.getUser().getFullName(), p.getSenderName(), true, p.getUserName()));
            } else {
                tcpClient.sendPacket(new BinaryFileSaveReplyPacket(p.getSessionId(), p.getFileName(), base.getUser().getFullName(), p.getSenderName(), false, p.getUserName()));

            }

        } else {
            tcpClient.sendPacket(new BinaryFileSaveReplyPacket(p.getSessionId(), p.getFileName(), base.getUser().getFullName(), p.getSenderName(), false, p.getUserName()));

        }
    }

    public void processPresencePacket(PresencePacket p) {
        if (base != null) {

            int userIndex = base.getUserManager().getUserIndex(p.getUserName());
            if (userIndex > -1) {
                base.getUserManager().setUser(userIndex, p.getPresenceType(), p.isShowIcon(), true);

            }
        }
    }

    public void processClearSlidesPacket() {
        base.getWhiteboardSurface().setCurrentSlide(null, 0, 0, true);
    }

    public void processWhiteboardItems(WhiteboardItems p) {
        base.getWhiteboardSurface().setItems(p.getWhiteboardItems());
        Vector<Item> items = p.getWhiteboardItems();

        for (int i = 0; i < items.size(); i++) {
            Item item = items.elementAt(i);
            if (item instanceof Img) {
                Img img = (Img) item;
                String filePath = Constants.getRealtimeHome() + "/classroom/images/" + base.getSessionId() + "/" + img.getImagePath();
                if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
                    tcpClient.sendPacket(new SessionImg(filePath, img));
                } else {

                    base.getBaseManager().loadCachedImage(img, filePath);

                }
            }

            if (item instanceof Img) {
                Img img = (Img) item;
                String filePath = Constants.getRealtimeHome() + "/classroom/images/" + base.getSessionId() + "/" + img.getImagePath();
                if (!new File(filePath).exists()) { //request, because it means the session is on and we need this
                    tcpClient.sendPacket(new SessionImg(filePath, img));
                } else {

                    base.getBaseManager().loadCachedImage(img, filePath);

                }
            }
        }
    }

    public void processSwitchTabPacket(SwitchTabPacket packet) {
        SwitchTabPacket p = (SwitchTabPacket) packet;
        int currentSelectedIndex = base.getMainTabbedPane().getSelectedIndex();
        if (currentSelectedIndex != p.getTabIndex()) {
            base.getBaseManager().animateTabTitle(base.getMainTabbedPane(), p.getTabIndex());
            prevIndex = p.getTabIndex();
        } else {
            base.getBaseManager().getTabTimer().cancel();
            base.getMainTabbedPane().setBackgroundAt(prevIndex, base.getDockTabbedPane().getBackgroundAt(1));
        }
    }

    public void processPointerPacket(PointerPacket p) {

        base.getWhiteboardSurface().setCurrentPointer(p.getType(), p.getPoint());


    }

    public void processWhiteboardPacket(WhiteboardPacket p) {
        //     System.out.println("Received: " + p.getItem());

        if (p.getStatus() == avoir.realtime.tcp.common.Constants.ADD_NEW_ITEM) {
            base.getWhiteboardSurface().addItem(p.getItem());
            base.getSurface().repaint();
        }
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.REPLACE_ITEM) {

            base.getWhiteboardSurface().replaceItem(p.getItem());
            base.getSurface().repaint();
        }
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.REMOVE_ITEM) {

            base.getWhiteboardSurface().removeItem(p.getItem());
            base.getSurface().repaint();
        }
        if (p.getStatus() == avoir.realtime.tcp.common.Constants.CLEAR_ITEMS) {

            base.getWhiteboardSurface().clearItems();
            base.getSurface().repaint();
        }
    }

    /**
     * Send the chat packet to the chat room
     * @param p
     */
    public void processChatPacket(ChatPacket p) {
        if (base != null) {
            if (!base.getParentFrame().isActive()) {
                if (!base.getUserName().equals(p.getUsr())) {
                    showChatPopup(p.getUsr(), p.getContent());
                }

            }
            base.updateChat(p);
        //     base.getDockTabbedPane().setBackgroundAt(0, Color.ORANGE);


        }
    }

    private void showChatPopup(String user, String message) {
        chatPopup.setMessage(user, message);
        chatPopup.setLocation(ss.width - 200, ss.height - chatPopup.getHeight() - 100);
        chatPopup.setVisible(true);
    }

    public void processOutlinePacket(OutlinePacket p) {
        base.getAgendaManager().addAgenda(p.getOutlines(), base.getSessionTitle());

    }

    /**
     * New user. Add at the bottom of the user list
     * @param p
     */
    public void processNewUserPacket(NewUserPacket p) {
        if (base != null) {
            base.getUserManager().addNewUser(p.getUser());
        }

    }

    public void processUpdateUserPacket(UpdateUserNetworkStatusPacket p) {
        if (base != null) {

            boolean status = PresenceConstants.OFFLINE;
            if (p.isOnline()) {
                status = PresenceConstants.ONLINE;
            }

            int index = base.getUserManager().getUserIndex(p.getUser().getUserName());
            base.getUserManager().setUser(index, PresenceConstants.ONLINE_STATUS_ICON,
                    true, status);

        }

    }

    public void processImagePacket(ImagePacket packet) {
        try {
            String home = avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/classroom/" + packet.getSessionId() + "/images/";
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

            base.getWhiteboardSurface().setImage(new ImageIcon(fn));
        } catch (Exception ex) {
            ex.printStackTrace();
            if (base != null) {
                base.showMessage("Error writing image " + packet.getFilename(), true, true, MessageCode.ALL);
            }

        }
    }

    /**
     * Requested slide not found..paint this message on the surface 
     * to inform the user
     * @param p
     */
    public void processSlideNotFoundPacket(SlideNotFoundPacket p) {
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

    public void processSurveyAnswerPacket(SurveyAnswerPacket p) {
        if (base != null) {

            base.getSurveyManagerFrame().setSurveyAnswer(p.getQuestion(), p.isAnswer());
        }

    }

    public void processVotePacket(VotePacket p) {
        if (base != null) {
            base.getToolbarManager().setVoteButtonsEnabled(p.isVote(), p.isVisibleToAll());
        }
    }

    public void processSurveyPacket(SurveyPackPacket p) {

        if (base != null) {
            base.showSurveyFrame(p.getQuestions(), p.getTitle());
        }

    }

    public void processServerLogReply(ServerLogReplyPacket packet) {
        if (clientAdmin != null) {
            clientAdmin.setLog(packet.getByteArray());
        }

    }

    public void processClearVotePacket(ClearVotePacket p) {
        if (base != null) {
            base.getUserManager().clearVote();
        }

    }

    public synchronized void processModuleFileRequestPacket(ModuleFileRequestPacket p) {

        byte[] byteArray = tcpClient.readFile(p.getFilePath());

        ModuleFileReplyPacket rep = new ModuleFileReplyPacket(byteArray,
                p.getFilename(), p.getFilePath(), p.getUsername());
        rep.setDesc(p.getDesc());

        tcpClient.sendPacket(rep);

    }

    public void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        int slides[] = tcpClient.getImageFileNames(packet.getPathToSlides());

        String slidesPath = packet.getPathToSlides();
        // i hope to use threads here..dont know if it will help
        if (slides != null) {
            for (int i = 0; i <
                    slides.length; i++) {
                String filename = "img" + i + ".jpg";
                String filePath = slidesPath + "/" + filename;
                boolean lastFile = i == (slides.length - 1) ? true : false;
                tcpClient.replySlide(filePath, packet.getSessionId(), packet.getUsername(), filename, lastFile, i, slides.length, new File(slidesPath).getName());
            }
        }

    }

    /**
     * New slide. Look for it in the local cache and display it.
     * But let the GUI class do that
     * @param packet
     */
    public void processNewSlideReplyPacket(NewSlideReplyPacket packet) {
        int slideIndex = packet.getSlideIndex();
        base.setSelectedFile(packet.getPresentationName());
        if (base != null) {
            String slidePath = Constants.getRealtimeHome() + "/presentations/" + base.getSessionId() + "/img" + slideIndex + ".jpg";

            if (!packet.isWebPresent()) {
                slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + base.getSessionId() + "/" + packet.getPresentationName() + "/img" + slideIndex + ".jpg";
            }

            base.getSessionManager().setCurrentSlide(slideIndex, packet.isIsPresenter(), slidePath);
            if (packet.getMessage() != null) {
                base.showMessage(packet.getMessage(), false, true, MessageCode.ALL);
            }
        }
    }

    public void showRemoteDesktop() {
        if (viewer != null) {
            viewer.setVisible(true);
        }
    }

    public void closeRemoteDesktopFrame() {
        if (viewer != null) {
            viewer.dispose();
            viewer = null;
        }
    }

    public void processDesktopPacket(final DesktopPacket packet) {
        if (showAppSharingFrame) {
            int hh = packet.getData().getHeight();
            int ww = packet.getData().getWidth();

            viewer = new RemoteDesktopViewerFrame();

            viewer.setSize(ww, hh);
            appViewer = new AppView(viewer.getWidth(), viewer.getHeight(), base);
            viewer.setContentPane(appViewer);
            viewer.setVisible(true);
            showAppSharingFrame = false;
        }

        Thread t = new Thread() {

            public void run() {
                appViewer.pixelUpdate(packet.getData());
            }
        };
        t.start();
    }

    public void processHeartBeat(HeartBeat p) {
        tcpClient.getNetworkMonitor().setServerContacted(true);
    //  tcpClient.getNetworkMonitor().sendPulse();

    }
}
