/**sase
 *  $Id: ServerThread.java,v 1.13 2007/05/18 10:38:44 davidwaf Exp $
 *
 *  Copyright (C) GNU/GPL AVOIR 2007
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
package avoir.realtime.tcp.proxy;

import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.base.user.UserLevel;
import java.io.*;

import java.nio.channels.*;
import java.nio.*;
import java.net.Socket;
import java.util.Vector;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.Random;

import avoir.realtime.tcp.common.packet.*;
import avoir.realtime.tcp.launcher.packet.LauncherAckPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileReplyPacket;
import avoir.realtime.tcp.launcher.packet.ModuleFileRequestPacket;
import avoir.realtime.sessionmonitor.*;

import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.launcher.packet.LauncherMsgPacket;
import avoir.realtime.tcp.whiteboard.item.Item;
import java.util.LinkedList;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import javax.swing.JOptionPane;

/**
 * Handles communications for the server, to and from the clients Processes
 * packets from client actions and broadcasts these updates
 */
@SuppressWarnings("serial")
public class ServerThread extends Thread {

    private static DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd H:mm:ss");
    private static int MAX_CHAT_SIZE = 50;
    private static Random random = new Random();
    private static Logger logger = Logger.getLogger(ServerThread.class.getName());
    private LinkedList<ChatPacket> chats;
    private ClientList clients;
    private static String tName;
    int startX = 10;
    int startY = 10;
    private Socket socket;
    private Vector<Session> presentationLocks;
    private Vector<SlideServer> slideServers;
    private LinkedList<AttentionPacket> raisedHands;
    private Vector<LauncherClient> launchers;
    private boolean presentationLocked = false;
    private boolean firstTimeSlideRequest = true;
    private User thisUser;
    private InputStream inStream;
    private OutputStream outStream;
    private Vector mediaInputStreams;
    private ObjectOutputStream objectOutStream;
    private Vector<SessionMonitor> sessionmonitors;
    private boolean sendSlideServerErrorMessage = false;
    private Vector<Item> whiteboardItems;
    private boolean waitForSlideServer = false;

    /**
     * Constructor accepts connections
     * 
     * @param socket The socket
     */
    public ServerThread(Socket socket,
            ClientList clients,
            LinkedList<ChatPacket> chats,
            Vector<Session> presentationLocks,
            Vector<SlideServer> slideServers,
            LinkedList<AttentionPacket> raisedHands,
            Vector mediaInputStreams, Vector<LauncherClient> launchers,
            Vector<SessionMonitor> sessionmonitors,
            Vector<Item> whiteboardItems) {
        this.launchers = launchers;
        this.sessionmonitors = sessionmonitors;
        this.presentationLocks = presentationLocks;
        this.clients = clients;
        this.chats = chats;
        this.slideServers = slideServers;
        this.raisedHands = raisedHands;
        this.whiteboardItems = whiteboardItems;
        this.mediaInputStreams = mediaInputStreams;
        tName = getName();
        logger.info("Server " + tName + " accepted connection from " + socket.getInetAddress() + "\n");
        this.socket = socket;

    }

    /**
     * Run method initializes Object input and output Streams Calls dispatch
     * method which calls process messsages which processes incoming packets The
     * information carried by these packets is rebroadcasted to all of the
     * clients
     */
    @Override
    public void run() {
        try {
            outStream = socket.getOutputStream();
            objectOutStream = new ObjectOutputStream(
                    new BufferedOutputStream(outStream));
            objectOutStream.flush();
            inStream = socket.getInputStream();
            ObjectInputStream in = null;
            in = new ObjectInputStream(
                    new BufferedInputStream(inStream));
            //as long as we get a new user..clean up disconnected ones
            removeNullClients();
            listen(in, objectOutStream);

        } catch (Exception e) {
            logger.log(Level.SEVERE, "Error in Server " + tName, e);
        } finally {
            logger.info("Server " + tName + " disconnected from " + socket.getInetAddress() + "\n");
            try {
                socket.close();
            } catch (IOException e) {
                logger.log(Level.WARNING, "Error closing socket", e);
            }
        }
    }

    /**
     * generate a random string 
     * @return
     */
    protected static String randomString() {
        return Long.toString(random.nextLong(), 36);
    }

    /**
     * Remove lock
     * @param sessionId
     */
    private void removeLock(String sessionId, String userId) {
        synchronized (presentationLocks) {
            for (int i = 0; i < presentationLocks.size(); i++) {
                if (sessionId.trim().equals(presentationLocks.elementAt(i).getSessionId()) &&
                        userId.equals(presentationLocks.elementAt(i).getUserId())) {
                    presentationLocks.remove(i);
                }
            }
        }
    }

    /**
     * Remove lock
     * @param sessionId
     */
    private void removeLock(String userId) {
        synchronized (presentationLocks) {
            for (int i = 0; i < presentationLocks.size(); i++) {
                if (userId.equals(presentationLocks.elementAt(i).getUserId())) {
                    presentationLocks.remove(i);
                }
            }
        }
    }

    private void removeNullLockedPresentations() {
        synchronized (presentationLocks) {
            Vector<Session> invalidSessions = new Vector<Session>();
            for (int i = 0; i < presentationLocks.size(); i++) {
                Socket socket = presentationLocks.elementAt(i).getSocket();
                /*
                if (socket.isClosed() ||
                !socket.isConnected() || socket.isInputShutdown() || socket.isOutputShutdown()) {
                invalidSessions.addElement(presentationLocks.elementAt(i));
                }*/
                try {
                    presentationLocks.elementAt(i).getStream().writeObject(new TestPacket());
                    presentationLocks.elementAt(i).getStream().flush();
                } catch (Exception ex) {
                    ex.printStackTrace();
                    logger.info(ex.getLocalizedMessage());
                    try {
                        invalidSessions.addElement(presentationLocks.elementAt(i));
                    } catch (Exception ex2) {
                        logger.info(ex2.getLocalizedMessage());
                    }
                }
            }
            //purge them
            for (int i = 0; i < invalidSessions.size(); i++) {

                presentationLocks.remove(invalidSessions.elementAt(i));
            }
        }
    }

    /**
     * check if presentation is locked
     * @param sessionId
     * @return
     */
    private boolean presentationIsLocked(String sessionId) {
        removeNullLockedPresentations();
        synchronized (presentationLocks) {
            for (int i = 0; i < presentationLocks.size(); i++) {
                if (sessionId.equals(presentationLocks.elementAt(i).getSessionId())) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Only get the relevant
     * @param user
     * @return
     */
    private LinkedList<ChatPacket> getChatLog(User user) {
        LinkedList<ChatPacket> chts = new LinkedList<ChatPacket>();
        synchronized (chats) {

            for (int i = 0; i < chats.size(); i++) {
                if (chats.get(i).getSessionId().equals(user.getSessionId())) {
                    chts.addLast(chats.get(i));
                }
            }
        }
        return chts;
    }

    /**
     * Get todays datetime
     * @return
     */
    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
    }

    /**
     * Initiates locking sessions
     * @param user
     */
    private void tryLockingPresentation(User user) {
        if (!presentationIsLocked(user.getSessionId())) {
            Session session = new Session();
            session.setSessionId(user.getSessionId());
            session.setUserId(user.getUserName());
            session.setFullName(user.getFullName());
            session.setSessionHasPresenter(true);
            session.setSessionName(user.getSessionTitle());
            session.setTime(getDateTime());
            session.setSocket(socket);
            session.setStream(objectOutStream);
            synchronized (presentationLocks) {
                presentationLocks.addElement(session);
            }
            broadcastMsg(new MsgPacket(user.getFullName() + " presenting. Started at " +
                    getDateTime(), false, false), user.getSessionId());
        } else {
            presentationLocked = true;
            user.setAsPresenter(false);
        }
    }

    /**
     * Add a new laucher client. However, duplicates lock up things..i have
     * no idea why. So, just remove them and get smooth sailing!
     * @param lc
     */
    private void addLauncherClient(LauncherClient lc) {
        //first, remove any existing ones wih same name
        Vector<Integer> dups = new Vector<Integer>();
        for (int i = 0; i < launchers.size(); i++) {
            if (launchers.elementAt(i).getId().equals(lc.getId())) {
                dups.add(i);
            }
        }

        for (int i = 0; i < dups.size(); i++) {
            launchers.removeElementAt(i);
        }

        launchers.addElement(lc);
    }

    /**
     * Add new slide server. But we dont need duplicates, so take care of those first
     * @param ss
     */
    private void addSlideServer(SlideServer ss) {
        Vector<Integer> dups = new Vector<Integer>();
        for (int i = 0; i < slideServers.size(); i++) {
            if (slideServers.elementAt(i).getId().equals(ss.getId())) {
                dups.add(i);
            }
        }
        for (int i = 0; i < dups.size(); i++) {
            slideServers.removeElementAt(i);
        }

        slideServers.add(ss);
    }

    /**
     * Listens to clients requests
     * @param objectIn
     * @param objectOut
     */
    private void listen(ObjectInputStream objectIn, final ObjectOutputStream objectOut) {
        while (true) {
            try {
                Object obj = null;
                try {
                    obj = objectIn.readObject();
                   // logger.info(obj.getClass() + "");
                } catch (Exception ex) {
                    logger.info(ex.getLocalizedMessage());
                    waitForSlideServer = false;
                    /* if (thisUser != null) {
                    removeDisconnectedUsers();
                    removeNullLockedPresentations();
                    broadcastRemovedUser(new RemoveUserPacket(thisUser));
                    updateMonitors();
                    }*/
                    break;
                }


                if (obj instanceof LauncherAckPacket) {
                    LauncherAckPacket lack = (LauncherAckPacket) obj;
                    synchronized (launchers) {
                        addLauncherClient(new LauncherClient(lack.getUser().getUserName(), objectOutStream));
                    }
                }
                if (obj instanceof ModuleFileRequestPacket) {

                    ModuleFileRequestPacket p = (ModuleFileRequestPacket) obj;
                    processModuleFilePacketRequest(p);
                }
                if (obj instanceof ModuleFileReplyPacket) {

                    ModuleFileReplyPacket p = (ModuleFileReplyPacket) obj;
                    processModuleFileReplyPacket(p);
                }
                if (obj instanceof SessionListingRequestPacket) {

                    SessionListingRequestPacket p = (SessionListingRequestPacket) obj;
                    synchronized (sessionmonitors) {
                        sessionmonitors.add(new SessionMonitor(objectOut, p.getSessionId()));
                    }
                    processSessionListingRequest();
                }
                if (obj instanceof RemoveSessionMonitorPacket) {
                    RemoveSessionMonitorPacket p = (RemoveSessionMonitorPacket) obj;
                    synchronized (sessionmonitors) {
                        for (int i = 0; i < sessionmonitors.size(); i++) {
                            if (sessionmonitors.elementAt(i).getMonitorId().equals(p.getSessionId())) {
                                sessionmonitors.remove(i);
                            }
                        }
                    }

                }

                RealtimePacket packet = null;

                if (obj instanceof RealtimePacket) {
                    packet = (RealtimePacket) obj;
                }
                if (packet != null) {
                    //clean null ones before doing anything
                    removeNullClients();
                    if (packet instanceof AckPacket) {
                        AckPacket ack = (AckPacket) packet;
                        thisUser = ack.getUser();

                        //check if user is slides host, if so, add to special list
                        if (thisUser.isSlidesHost()) {
                            if (!slideServerExists(thisUser.getUserName())) {
                                logger.info(thisUser.getUserName() + " registered as slide server");
                                synchronized (slideServers) {
                             //       slideServers.addElement(new SlideServer(thisUser.getUserName(), objectOut, socket));
                                addSlideServer(new SlideServer(thisUser.getUserName(), objectOut, socket));
                                }
                            } else {
                                logger.info(thisUser.getFullName() + " already exists ...shutting down!");
                                //just shut it down..as there is already running mate
                                sendPacket(new QuitPacket(thisUser.getSessionId()), objectOut);
                            }
                        } else {
                            log("users.txt", getDateTime() + " " + thisUser.getFullName() + "\n");
                            if (thisUser.getLevel() == (UserLevel.LECTURER)) {
                                synchronized (clients) {

                                    clients.add(0, socket, objectOut, outStream, thisUser);
                                }
                            } else {
                                synchronized (clients) {

                                    clients.addElement(socket, objectOut, outStream, thisUser);
                                }
                            }

                            if (thisUser.isPresenter()) {
                                tryLockingPresentation(thisUser);
                            }

                            //first send the user list
                            ClientPacket clientPacket = new ClientPacket(getUsersBySessionId(thisUser.getSessionId()));
                            sendPacket(clientPacket, objectOut);
                            //then broadcast to rest of the participants
                            broadcastUser(thisUser);
                            //update this user with the last chat, but only if was in
                            //same context
                            LinkedList<ChatPacket> chts = getChatLog(thisUser);
                            ChatLogPacket chatLogPacket = new ChatLogPacket(chts);
                            sendPacket(chatLogPacket, objectOut);
                            //send over any whiteboard stuff
                            sendPacket(new WhiteboardItems(whiteboardItems, thisUser.getSessionId()), objectOut);

                            //check if there is presenter....tell so
                            Session session = getSession(thisUser.getSessionId());
                            if (session == null) {
                                MsgPacket p = new MsgPacket("This presentation has no presenter [Is not Live].", false, true);
                                sendPacket(p, objectOut);
                            } else {
                                MsgPacket p = new MsgPacket(session.getFullName() + " presenting. Started at " + session.getTime(), false, false);
                                sendPacket(p, objectOut);
                                if (!thisUser.isPresenter()) {
                                    OutlinePacket outline = new OutlinePacket(thisUser.getSessionId(), null, 0, session.getOutLine(), 0);
                                    sendPacket(outline, objectOut);
                                }
                            }

                            //update any monitors too
                            updateMonitors();

                        }
                    } else if (packet instanceof SessionStatePacket) {
                        Session session = getSession(thisUser.getSessionId());
                        SessionStatePacket p = (SessionStatePacket) packet;
                        session.setState(p.getState());
                        setCurrentSession(session);
                        String msg = p.getState() ? session.getFullName() + " presenting. Started at " + session.getTime() : "Session Paused";
                        MsgPacket msgP = new MsgPacket(msg, true, false);
                        broadcastMsg(msgP, thisUser.getSessionId());
                    } else if (packet instanceof RemoveMePacket) {
                        removeSlideServer((RemoveMePacket) packet);
                    } else if (packet instanceof QuitPacket) {
                        broadcastPacket(packet, false);
                    } else if (packet instanceof SurveyAnswerPacket) {
                        broadcastSurveyAnswerPacket(packet, thisUser.getSessionId());

                    } else if (packet instanceof LocalSlideCacheRequestPacket) {
                        processLocalSlideCacheRequest((LocalSlideCacheRequestPacket) packet);
                    } else if (packet instanceof FilePacket) {
                        processFilePacket((FilePacket) packet);
                    } else if (packet instanceof OutlinePacket) {
                        processOutlinePacket((OutlinePacket) packet);
                    } else if (packet instanceof ClearVotePacket) {
                        broadcastClearVotePacket(packet, thisUser.getSessionId());
                    } else if (packet instanceof SystemFilePacket) {
                        processSystemFile((SystemFilePacket) packet);
                    } else if (packet instanceof VotePacket) {
                        broadcastPacket(packet, thisUser.getSessionId());
                    } else if (packet instanceof ServerLogRequestPacket) {
                        processServerLogRequest((ServerLogRequestPacket) packet);
                    } else if (packet instanceof SurveyPackPacket) {
                        SurveyPackPacket p = (SurveyPackPacket) packet;
                        broadcastPacket(p, thisUser.getSessionId());
                    } else if (packet instanceof PointerPacket) {
                        broadcastPacket((PointerPacket) packet, true);

                    } else if (packet instanceof WhiteboardPacket) {
                        WhiteboardPacket p = (WhiteboardPacket) packet;
                        Item item = p.getItem();
                        if (p.getStatus() == avoir.realtime.tcp.common.Constants.ADD_NEW_ITEM) {
                            whiteboardItems.add(item);
                        }
                        if (p.getStatus() == avoir.realtime.tcp.common.Constants.REPLACE_ITEM ||
                                p.getStatus() == avoir.realtime.tcp.common.Constants.REMOVE_ITEM) {
                            for (int i = 0; i < whiteboardItems.size(); i++) {
                                Item xt = whiteboardItems.elementAt(i);
                                if (xt.getId().equals(item.getId())) {
                                    switch (p.getStatus()) {
                                        case avoir.realtime.tcp.common.Constants.REPLACE_ITEM: {
                                            whiteboardItems.set(i, item);
                                            break;
                                        }

                                        case avoir.realtime.tcp.common.Constants.REMOVE_ITEM: {
                                            whiteboardItems.remove(i);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (p.getStatus() == avoir.realtime.tcp.common.Constants.CLEAR_ITEMS) {
                            whiteboardItems.clear();
                        }
                        broadcastPacket(p, true);

                    } else if (packet instanceof PresencePacket) {
                        PresencePacket p = (PresencePacket) packet;
                        updateUserPresenceStatus(p.isShowIcon(), p.getUserName(), p.getPresenceType());
                        broadcastPresencePacket(p, p.getSessionId());
                    } else if (packet instanceof BinaryFileSaveRequestPacket) {
                        processBinaryFileSaveRequestPacket((BinaryFileSaveRequestPacket) packet);
                    } else if (packet instanceof BinaryFileSaveReplyPacket) {
                        processBinaryFileSaveReplyPacket((BinaryFileSaveReplyPacket) packet);
                    } else if (packet instanceof BinaryFileChunkPacket) {
                        processBinaryFileChunckPacket((BinaryFileChunkPacket) packet);
                    } else if (packet instanceof AudioPacket) {
                        AudioPacket p = (AudioPacket) packet;
                        //this is a test....send it back to sender
                        if (p.isTest()) {
                            sendPacket(packet, objectOut);
                        } else {
                            broadcastAudioPacket(p);
                        }
                    } else if (packet instanceof AttentionPacket) {
                        AttentionPacket raiseHandPacket = (AttentionPacket) packet;
                        processAttentionPacket(raiseHandPacket);
                    } else if (packet instanceof RemoveUserPacket) {
                        RemoveUserPacket rmup = (RemoveUserPacket) packet;
                        synchronized (clients) {
                            waitForSlideServer = false;
                            clients.removeElement(socket, objectOut, outStream);
                        }
                        updateMonitors();
                        //   removeStream(thisUser.getSessionId());
                        //if presenter..clear the presentation...
                        if (rmup.getUser().isPresenter()) {

                            removeLock(rmup.getUser().getSessionId(), rmup.getUser().getUserName());
                            //inform members of this action
                            MsgPacket p = new MsgPacket(rmup.getUser().getFullName() + " has stopped the live presentation.", false, true);
                            broadcastMsg(p, rmup.getUser().getSessionId());
                        }
                        //update members user list
                        broadcastRemovedUser(rmup);
                        //deal with any raised hands
                        for (int i = 0; i < raisedHands.size(); i++) {
                            if (raisedHands.get(i).getUserName().equals(thisUser.getUserName())) {
                                raisedHands.remove(i);
                            }
                        }

                    } else if (packet instanceof RestartServerPacket) {
                        //  restartServer();
                        } else if (packet instanceof ChatPacket) {
                        // Session session = getSession(thisUser.getSessionId());
                        ChatPacket p = (ChatPacket) packet;
                        synchronized (clients) {

                            chats.addLast(p);
                        }
                        log("ChatLog.txt", "[" + p.getTime() + "] <" + p.getUsr() + "> " + p.getContent());
                        synchronized (clients) {

                            if (chats.size() > MAX_CHAT_SIZE) {
                                chats.removeFirst();
                            }
                        }
                        broadcastChat(p, p.getSessionId());

                    } else if (packet instanceof NewSlideRequestPacket) {
                        NewSlideRequestPacket newSlideRequest = (NewSlideRequestPacket) packet;
                        processNewSlideRequest(newSlideRequest, objectOut);
                    } else {
                        //just reply to the whoever requested that there is a problem
                        try {
                            objectOut.writeObject(new MsgPacket("Server received request it could not understand!", true, true));
                            objectOut.flush();
                        } catch (Exception ex) {
                            ex.printStackTrace();
                        }

                    }
                }


            } catch (Exception ex) {

                ex.printStackTrace();
            // break;
            }
        }
    }

    private void processBinaryFileSaveRequestPacket(BinaryFileSaveRequestPacket p) {
        synchronized (clients) {
            for (int i = 0; i < clients.size(); i++) {
                if (!clients.nameAt(i).getUserName().equals(p.getUserName()) &&
                        clients.nameAt(i).getSessionId().equals(p.getSessionId())) {
                    try {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void processBinaryFileSaveReplyPacket(BinaryFileSaveReplyPacket p) {
        synchronized (clients) {
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getUserName().equals(p.getSenderUsername())) {
                    try {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void processBinaryFileChunckPacket(BinaryFileChunkPacket p) {
        synchronized (clients) {
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getUserName().equals(p.getRecepientName())) {
                    try {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void updateMonitors() {
        synchronized (sessionmonitors) {
            for (int i = 0; i < sessionmonitors.size(); i++) {
                try {
                    sessionmonitors.elementAt(i).getStream().writeObject(new SessionListingReplyPacket(getParticipants(), getPresenters()));
                    sessionmonitors.elementAt(i).getStream().flush();
                } catch (IOException ex) {
                    sessionmonitors.remove(i);
                }
            }
        }
    }

    private void processOutlinePacket(OutlinePacket p) {
        Session session = getSession(p.getSessionId());
        String[] outline = session.getOutLine();
        if (outline == null) {
            outline = new String[p.getMax()];

        }
        outline[p.getIndex()] = p.getTitle();
        session.setOutLine(outline);
        setCurrentSession(session);
        p.setOutlines(outline);
        broadcastPacket(p, false);
    }

    private Vector<SessionPresenter> getPresenters() {
        Vector<SessionPresenter> presenters = new Vector<SessionPresenter>();
        synchronized (presentationLocks) {
            for (int i = 0; i < presentationLocks.size(); i++) {
                Session session = presentationLocks.elementAt(i);
                presenters.addElement(new SessionPresenter(session.getSessionId(), session.getSessionName(),
                        session.getSlideIndex(), session.getTime(), session.getFullName(),
                        session.getOutLine(), session.getState(), session.isSessionHasPresenter(),
                        session.getUserId()));
            }
        }
        return presenters;
    }

    private Vector<SessionParticipant> getParticipants() {
        Vector<SessionParticipant> participants = new Vector<SessionParticipant>();
        synchronized (clients) {

            for (int i = 0; i < clients.size(); i++) {
                User user = clients.nameAt(i);
                participants.addElement(new SessionParticipant(user.getUserName(),
                        user.getSessionId(), user.getSessionTitle(), user.getFullName()));
            }
        }
        return participants;
    }

    private void processSessionListingRequest() {
        try {
            objectOutStream.writeObject(new SessionListingReplyPacket(getParticipants(), getPresenters()));
            objectOutStream.flush();
        } catch (Exception ex) {
            logger.info(ex.getLocalizedMessage());
        }
    }

    private void delay(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    private void updateUserPresenceStatus(boolean state, String userName, int presenceType) {

        synchronized (clients) {
            if (presenceType == PresenceConstants.EDIT_ICON) {
                return; //too much overhead for edits..just dont bother
            }
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getUserName().equals(userName)) {
                    switch (presenceType) {
                        case PresenceConstants.HAND_ICON:
                            clients.nameAt(i).setHandOn(state);

                        case PresenceConstants.SPEAKER_ICON:
                            clients.nameAt(i).setSpeakerOn(state);

                        case PresenceConstants.MIC_ICON:
                            clients.nameAt(i).setHandOn(state);

                        case PresenceConstants.YES_ICON:
                            clients.nameAt(i).setYesOn(state);
                        case PresenceConstants.NO_ICON:
                            clients.nameAt(i).setNoOn(state);
                    }
                    return;
                }

            }
        }
    }

    private void processFilePacket(FilePacket packet) {
        synchronized (clients) {
            for (int i = 0; i < clients.size(); i++) {

                if (clients.nameAt(i).getSessionId().equals(packet.getSessionId())) {
                    if (clients.nameAt(i).getUserName().equals(packet.getUsername())) {
                        try {
                            if (clients.nameAt(i).isPresenter() && packet.isLastFile()) {
                                String[] outline = new String[packet.getMaxValue()];
                                Session session = getSession(packet.getSessionId());

                                session.setOutLine(outline);
                                setCurrentSession(session);
                            }
                            clients.elementAt(i).writeObject(packet);
                            clients.elementAt(i).flush();

                        } catch (Exception ex) {
                            logger.info(ex.getLocalizedMessage());
                        }
                    }
                }
            }
        }
    }

    private void processModuleFileReplyPacket(ModuleFileReplyPacket packet) {
    //    logger.info("Received: " + packet.getClass() + " : " + packet.getFilePath());
      //  logger.info("Trying to obtain lock..");
        synchronized (launchers) {
        //    logger.info("Obtained!!!! .. Launchers size: " + launchers.size());
            for (int i = 0; i < launchers.size(); i++) {
          //      logger.info("Loop " + i + ": testing " + launchers.elementAt(i).getId() + " against " + packet.getUsername());
                if (launchers.elementAt(i).getId().equals(packet.getUsername())) {
                    try {
            //            logger.info("Match found ...sending packet");
                        launchers.elementAt(i).getObjectOutputStream().flush();

                        launchers.elementAt(i).getObjectOutputStream().writeObject(packet);
                        launchers.elementAt(i).getObjectOutputStream().flush();
              //          logger.info("Send!!!!");
                    } catch (Exception ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }

            }
        }
        //logger.info("Released lock...");
    }

    private void processServerLogRequest(ServerLogRequestPacket packet) {
        String filename = "/usr/lib/realtime/bin/wrapper.log";
        sendPacket(new ServerLogReplyPacket(readBinaryFile(filename)), objectOutStream);
    }

    private byte[] readBinaryFile(String filePath) {
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
            logger.info(ex.getLocalizedMessage());
            return null;
        }

    }

    private void processSystemFile(SystemFilePacket packet) {
        try {
            FileChannel fc =
                    new FileOutputStream(packet.getFilename()).getChannel();
            byte[] byteArray = packet.getByteArray();
            fc.write(ByteBuffer.wrap(byteArray));
            fc.close();
            sendPacket(new MsgPacket("File Received", false, false), objectOutStream);
        } catch (IOException ex) {
            sendPacket(new MsgPacket(ex.getMessage(), false, false), objectOutStream);
            logger.info(ex.getLocalizedMessage());
        }
    }

    private void restartServer() {
        ProcessBuilder pb = new ProcessBuilder("/usr/lib/realtime/bin/realtime-supernode", "restart");

        try {
            Process p = pb.start();
            BufferedReader in = new BufferedReader(new InputStreamReader(
                    p.getInputStream()));
            String s = "";
            while ((s = in.readLine()) != null) {
                logger.info("myinfo: " + s);
            }

            in = new BufferedReader(new InputStreamReader(
                    p.getErrorStream()));
            while ((s = in.readLine()) != null) {
                logger.info("myerror: " + s);
            }
            p.waitFor();
            logger.info("Restarting server ...");
        } catch (Exception ex) {

            logger.info(ex.getLocalizedMessage());
        }
    }

    private void removeSlideServer(RemoveMePacket p) {
        synchronized (slideServers) {

            //first, get hold of offenders 
            for (int i = 0; i < slideServers.size(); i++) {
                try {
                    slideServers.elementAt(i).getId().equals(p.getId());
                    slideServers.remove(i);
                    break;
                } catch (Exception ex) {
                    logger.info(ex.getLocalizedMessage());
                }

            }
        }
    }

    private void removeNullSlideServers() {
        synchronized (slideServers) {
            Vector<SlideServer> invalidSlideServers = new Vector<SlideServer>();

            //first, get hold of offenders 
            for (int i = 0; i < slideServers.size(); i++) {
                try {
                    slideServers.elementAt(i).getObjectOutputStream().writeObject(new TestPacket());
                    slideServers.elementAt(i).getObjectOutputStream().flush();
                } catch (Exception ex) {
                    logger.info(ex.getLocalizedMessage());
                    invalidSlideServers.addElement(slideServers.elementAt(i));
                }

            }
            //then purge them
            for (int i = 0; i < invalidSlideServers.size(); i++) {
                slideServers.remove(invalidSlideServers.elementAt(i));

            }
        }
    }

    private void removeNullClients() {
        synchronized (clients) {
            Vector<User> invalidClients = new Vector<User>();

            //first, get hold of offenders 
            for (int i = 0; i < clients.size(); i++) {
                try {
                    clients.elementAt(i).writeObject(new TestPacket());
                    clients.elementAt(i).flush();
                } catch (Exception ex) {
                    logger.info(ex.getLocalizedMessage());
                    invalidClients.addElement(clients.nameAt(i));
                }

            }
            //then purge them
            for (int i = 0; i < invalidClients.size(); i++) {
                clients.removeUser(invalidClients.elementAt(i));

            }
        }
    }

    private boolean slideServerExists(String id) {

        removeNullSlideServers();
        boolean exists = false;
        synchronized (slideServers) {
            if (slideServers.size() < 1) {
                return false;
            }
            for (int i = 0; i < slideServers.size(); i++) {
                if (slideServers.elementAt(i).getId().equals(id)) {
                    Socket socket = slideServers.elementAt(i).getSocket();

                    if (socket.isClosed() ||
                            !socket.isConnected() || socket.isInputShutdown() || socket.isOutputShutdown()) {

                        slideServers.remove(i);

                        exists = false;

                    } else {
                        //try to flush output from this server too..as an extra test
                        try {
                            slideServers.elementAt(i).getObjectOutputStream().writeObject(new TestPacket());
                            slideServers.elementAt(i).getObjectOutputStream().flush();
                            exists = true;
                        } catch (Exception ex) {

                            logger.info("Cannot flush the slide server or some other error...terminating it");
                            logger.info("Actuall Error: " + ex.getMessage());
                            slideServers.remove(i);
                            exists = true;
                        }

                    }
                }
            }
        }
        return exists;
    }

    private void processModuleFilePacketRequest(ModuleFileRequestPacket packet) {

        //locate the slides server based on the ids
        boolean slideServerFound = false;
        int MAX_TRIES = 120;
        int nooftries = 0;
        int sleep = 1 * 1000;
        waitForSlideServer = true;

        removeNullSlideServers();
        while (!slideServerFound) {
            synchronized (slideServers) {

                System.out.println("Connecting to slide server " + packet.getSlidesServerId() + " attempt " + nooftries);
                for (int i = 0; i < slideServers.size(); i++) {
                    if (slideServers.elementAt(i).getId().equals(packet.getSlidesServerId())) {
                        try {
                            slideServers.elementAt(i).getObjectOutputStream().writeObject(packet);
                            slideServers.elementAt(i).getObjectOutputStream().flush();
                            slideServerFound = true;
                            logger.info("Found, breaking ...");
                            break;
                        } catch (Exception ex) {
                            logger.info(ex.getLocalizedMessage());
                        }
                    }
                }
            }
            if (nooftries++ > MAX_TRIES) {
                break;
            }
            delay(sleep);
            try {
                wait();
            } catch (Exception ex) {
            }
        }


        if (!slideServerFound) {
            System.out.println("Slide server " + packet.getSlidesServerId() + " not found!!");
            try {
                if (!sendSlideServerErrorMessage) {
                    objectOutStream.writeObject(new LauncherMsgPacket("An  internal error connecting to slide server.\n" +
                            "This is our fault. In order to avoid getting this message again, please do the following:\n" +
                            "1. Click on 'Retry' button to reconnect to slide server. If this doesn't help, then:\n" +
                            "2. Click on the 'home' link in the browser, then choose this presentation again. The problem will automatically resolve itself.\n" +
                            "3. If after 2 above you still get this message, contact the system admin: jscoble@uwc.ac.za", false, true));
                    objectOutStream.flush();
                    sendSlideServerErrorMessage = true;
                }
            } catch (Exception ex) {
                logger.info(ex.getMessage());
            }
        }
    }

    private void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        //locate the slides server based on the ids
        boolean slideServerFound = false;
        removeNullSlideServers();
        synchronized (slideServers) {
            for (int i = 0; i < slideServers.size(); i++) {
                if (slideServers.elementAt(i).getId().equals(packet.getSlidesServerId())) {
                    sendPacket(packet, slideServers.elementAt(i).getObjectOutputStream());
                    slideServerFound = true;
                    notify();
                }
            }
        }
        if (!slideServerFound) {
            sendPacket(new MsgPacket("Slide server " + packet.getSlidesServerId() + " not found!", false, true), objectOutStream);
        }
    }
    /*
    private void processResetSession(VotePacket p) {
    for (int i = 0; i < clients.size(); i++) {
    if (clients.nameAt(i).getSessionId().equals(p.getSessionId())) {
    AttentionPacket ap = new AttentionPacket(clients.nameAt(i).getUserName(),
    p.getSessionId(), false,
    false, false, false, false, false, p.isSpeakerEnabled(), p.isMicEnabled());
    broadcastPacket(ap, p.getSessionId());
    }
    }
    }*/

    /**
     * handle a raised hand/or a prevoiusly raised hand that probably is
     * being put down
     * @param p
     */
    private void processAttentionPacket(AttentionPacket p) {
        if (p.isPrivate()) {
            broadcastPrivateAttention(p, p.getSessionId());
        } else {
            broadcastAttention(p, p.getSessionId());
        }
    }

    /**
     * Logs the chat of a specific session
     * @param fileName
     * @param txt
     */
    public void log(String fileName, String txt) {
        try {
            BufferedWriter out = new BufferedWriter(new FileWriter(fileName, true));
            out.write(txt);
            out.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Get a list of user in this session
     * @param sessionId the session
     * @return
     */
    private Vector<User> getUsersBySessionId(String sessionId) {
        synchronized (clients) {

            Vector<User> xusrs = new Vector<User>();
            for (int i = 0; i < clients.size(); i++) {
                try {

                    try {
                        if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                            xusrs.addElement(clients.nameAt(i));
                        }
                    } catch (Exception ex) {
                        ex.equals(ex);
                    }
                } catch (Exception ex) {
                    logger.info(ex.getLocalizedMessage());
                }
            }
            return xusrs;
        }
    }

    private void removeDisconnectedUsers() {
        synchronized (clients) {
            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                try {
                    Socket socket = clients.socketAt(i);
                    if (socket.isClosed() ||
                            !socket.isConnected() || socket.isInputShutdown() || socket.isOutputShutdown()) {
                        broadcastRemovedUser(new RemoveUserPacket(clients.nameAt(i)));
                        clients.removeUser(clients.nameAt(i));
                    }
                    clients.elementAt(i).writeObject(new TestPacket());
                    clients.elementAt(i).flush();
                } catch (Exception ex) {
                    clients.removeUser(clients.nameAt(i));
                    logger.info(ex.getMessage());
                }
            }
        }
    }

    private void broadcastChat(ChatPacket chatPacket, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        synchronized (clients) {

            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        clients.elementAt(i).writeObject(chatPacket);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    private void broadcastPacket(RealtimePacket packet, String sessionId) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        if (!clients.nameAt(i).isPresenter()) {
                            clients.elementAt(i).writeObject(packet);
                            clients.elementAt(i).flush();
                        }
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void broadcastPacket(RealtimePacket packet, String sessionId, String username) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    if (clients.nameAt(i).getUserName().equals(username)) {
                        try {
                            if (!clients.nameAt(i).isPresenter()) {
                                clients.elementAt(i).writeObject(packet);
                                clients.elementAt(i).flush();
                            }
                        } catch (IOException ex) {
                            logger.info(ex.getLocalizedMessage());
                        }
                    }
                }
            }
        }
    }

    private void broadcastAudioPacket(AudioPacket packet) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(packet.getSessionId())) {

                    //dont send back to self...
                    if (!clients.nameAt(i).getUserName().equals(packet.getUsername())) {
                        try {

                            clients.elementAt(i).writeObject(packet);
                            clients.elementAt(i).flush();

                        } catch (IOException ex) {
                            logger.info(ex.getLocalizedMessage());
                        }
                    }
                }
            }
        }
    }

    private void broadcastPacket(RealtimePacket packet, boolean sendToPresenter) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(packet.getSessionId())) {
                    try {
                        if (sendToPresenter) {
                            clients.elementAt(i).writeObject(packet);
                            clients.elementAt(i).flush();
                        } else {
                            if (!clients.nameAt(i).isPresenter()) {
                                clients.elementAt(i).writeObject(packet);
                                clients.elementAt(i).flush();
                            }
                        }
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    /**
     * Broadcasts to other users but not
     * @param packet
     */
    private void broadcastWhiteboardPacket(RealtimePacket packet) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(packet.getSessionId())) {
                    try {
                        if (!clients.elementAt(i).equals(objectOutStream)) {
                            clients.elementAt(i).writeObject(packet);
                            clients.elementAt(i).flush();
                        }
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void broadcastClearVotePacket(RealtimePacket packet, String sessionId) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        clients.elementAt(i).writeObject(packet);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    private void broadcastPresencePacket(RealtimePacket packet, String sessionId) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        clients.elementAt(i).writeObject(packet);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    private void broadcastSurveyAnswerPacket(RealtimePacket packet, String sessionId) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        if (clients.nameAt(i).isPresenter()) {
                            clients.elementAt(i).writeObject(packet);
                            clients.elementAt(i).flush();
                        }
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    private void broadcastAttention(AttentionPacket p, String sessionId) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {

                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();

                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void broadcastPrivateAttention(AttentionPacket p, String sessionId) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        if (clients.nameAt(i).isPresenter()) {
                            clients.elementAt(i).writeObject(p);
                            clients.elementAt(i).flush();
                        }
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }
    }

    private void broadcastSlide(NewSlideReplyPacket p) {
        synchronized (clients) {

            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {

                if (clients.nameAt(i).getSessionId().equals(p.getSessionId())) {
                    try {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    private void broadcastMsg(MsgPacket p, String sessionId) {
        //send it to all those people logged in on this presentation (session)
        synchronized (clients) {
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                    try {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    /**
     * Update rest of the participants in this session if the user
     * No need to send back to self
     * @param user
     */
    private void broadcastUser(User user) {
        //send it to all those people logged in on this presentation (session)
        synchronized (clients) {
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(user.getSessionId()) &&
                        !clients.nameAt(i).getUserName().equals(user.getUserName())) {
                    try {
                        clients.elementAt(i).writeObject(new NewUserPacket(user));
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    /**
     * Update rest of the participants in this session if the user
     * @param user
     */
    private void broadcastRemovedUser(RemoveUserPacket p) {
        synchronized (clients) {
            //send it to all those people logged in on this presentation (session)
            for (int i = 0; i < clients.size(); i++) {
                if (clients.nameAt(i).getSessionId().equals(p.getUser().getSessionId())) {
                    try {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    } catch (IOException ex) {
                        logger.info(ex.getLocalizedMessage());
                    }
                }
            }
        }

    }

    private Session getSession(String sessionId) {
        synchronized (presentationLocks) {
            for (int i = 0; i < presentationLocks.size(); i++) {
                if (sessionId.equals(presentationLocks.elementAt(i).getSessionId())) {
                    return presentationLocks.elementAt(i);
                }
            }
        }
        return null;
    }

    private void setCurrentSession(Session session) {
        synchronized (presentationLocks) {
            for (int i = 0; i < presentationLocks.size(); i++) {
                if (session.getSessionId().equals(presentationLocks.elementAt(i).getSessionId())) {
                    presentationLocks.set(i, session);
                    return;
                }
            }
        }
    }

    private void processNewSlideReply(NewSlideReplyPacket p, ObjectOutputStream out) {
        Session session = getSession(p.getSessionId());

        //send it to all those people logged in on this presentation (session)
        if (p.isIsPresenter() || p.isControl()) { //if presenter, take over the presentation

            if (session != null) {
                if (presentationLocked) {
                    if (p.isIsPresenter()) {
                        p.setMessage("Presentation is locked by " + session.getFullName() + ". You just made local slide change.");
                    }
                    sendPacket(p, out);
                } else {
                    if (p.isIsPresenter()) {
                        session.setSlideIndex(p.getSlideIndex());
                        setCurrentSession(session);
                    }
                    broadcastSlide(p);

                }
            } else {
                p.setMessage("You are not in sync with presenter!");
                sendPacket(p, out);

            }

        } else {
            sendPacket(p, out);
        }


    }

    private void sendPacket(RealtimePacket packet, ObjectOutputStream out) {

        try {
            out.writeObject(packet);
            out.flush();

        } catch (IOException ex) {
            logger.info(ex.getLocalizedMessage());
        }

    }

    /**
     * This asks the actual slide from a non applet node which is 
     * should be having the slides 
     * @param packet
     * @param tout
     */
    private void processNewSlideRequest(NewSlideRequestPacket packet, ObjectOutputStream tout) {
        try {
            if (firstTimeSlideRequest) {
                //update with current slide..if any..if first time
                Session session = getSession(packet.getSessionId());
                if (session != null && (!packet.isPresenter())) {
                    NewSlideReplyPacket nsp = new NewSlideReplyPacket(session.getSlideIndex(), session.getSessionId(), true, packet.getSessionId());
                    firstTimeSlideRequest = false;
                    sendPacket(nsp, tout);
                    return;
                }
                firstTimeSlideRequest = false;
            }
            NewSlideReplyPacket p = new NewSlideReplyPacket(packet.getSlideIndex(),
                    packet.getSessionId(),
                    packet.isPresenter(),
                    packet.getUserId());
            p.setControl(packet.isControl());
            processNewSlideReply(p, tout);


        } catch (Exception ex) {
            sendErrorMsg("Requested slide " + packet.getSlideIndex() + " not found.", tout, true, true);
            SlideNotFoundPacket nfp = new SlideNotFoundPacket();
            nfp.setMessage("Details: " + ex.getMessage());
            sendPacket(nfp, tout);

        }
    }

    private void sendErrorMsg(String msg, ObjectOutputStream out, boolean temp, boolean wb) {
        try {
            out.writeObject(new MsgPacket(msg, temp, wb));
            out.flush();
        } catch (Exception ex) {
            logger.info(ex.getLocalizedMessage());
        }

    }
}
