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

import java.io.*;

import java.nio.channels.*;
import java.nio.*;
import java.net.Socket;
import java.util.Vector;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.Random;

import avoir.realtime.tcp.common.user.User;
import avoir.realtime.tcp.common.user.UserLevel;


import avoir.realtime.tcp.common.packet.*;
import java.util.LinkedList;
import avoir.realtime.tcp.common.packet.ModuleFileRequestPacket;

/**
 * Handles communications for the server, to and from the clients Processes
 * packets from client actions and broadcasts these updates
 */
@SuppressWarnings("serial")
public class ServerThread extends Thread {

   
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
    private boolean presentationLocked = false;
    private boolean firstTimeSlideRequest = true;
    private User thisUser;
    private InputStream inStream;
    private OutputStream outStream;
    private Vector mediaInputStreams;
    private ObjectOutputStream objectOutStream;

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
            Vector mediaInputStreams) {
        this.presentationLocks = presentationLocks;
        this.clients = clients;
        this.chats = chats;
        this.slideServers = slideServers;
        this.raisedHands = raisedHands;
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
        for (int i = 0; i < presentationLocks.size(); i++) {
            if (sessionId.trim().equals(presentationLocks.elementAt(i).getSessionId()) &&
                    userId.equals(presentationLocks.elementAt(i).getUserId())) {
                presentationLocks.remove(i);
            }
        }
    }

    /**
     * check if presentation is locked
     * @param sessionId
     * @return
     */
    private boolean presentationIsLocked(String sessionId) {
        for (int i = 0; i < presentationLocks.size(); i++) {
            if (sessionId.equals(presentationLocks.elementAt(i).getSessionId())) {
                return true;
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
        for (int i = 0; i < chats.size(); i++) {
            if (chats.get(i).getSessionId().equals(user.getSessionId())) {
                chts.addLast(chats.get(i));
            }
        }
        return chts;
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
            session.setTime(avoir.realtime.common.DEBUG_ENGINE.getDateTime());
            presentationLocks.addElement(session);
            broadcastMsg(new MsgPacket(user.getFullName() + " presenting. Started at " +
                    avoir.realtime.common.DEBUG_ENGINE.getDateTime(), false, false), user.getSessionId());
        } else {
            presentationLocked = true;
            user.setAsPresenter(false);
        }
    }

    private void removeSlideServer(User user) {
        for (int i = 0; i < slideServers.size(); i++) {
            if (user.getUserName().equals(user.getUserName())) {
                slideServers.remove(i);
            }
        }
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
                } catch (Exception ex) {
                    if (thisUser != null) {
                        if (thisUser.isSlidesHost()) {
                            removeSlideServer(thisUser);
                        } else {
                            clients.removeUser(thisUser);
                        //removeStream(thisUser.getSessionId());
                        }
                        //deal with any raised hands
                        for (int i = 0; i < raisedHands.size(); i++) {
                            if (raisedHands.get(i).getUserName().equals(thisUser.getUserName())) {
                                raisedHands.remove(i);
                            }
                        }
                    }
                    ex.printStackTrace();

                    break;
                }

                RealtimePacket packet = null;

                if (obj instanceof RealtimePacket) {
                    packet = (RealtimePacket) obj;
                }
                if (packet != null) {
                    if (obj instanceof AckPacket) {
                        AckPacket ack = (AckPacket) obj;
                        thisUser = ack.getUser();


                        //check if user is slides host, if so, add to special list
                        if (thisUser.isSlidesHost()) {
                            slideServers.addElement(new SlideServer(thisUser.getUserName(), objectOut));
                        } else {
                            if (thisUser.getLevel().equals(UserLevel.LECTURER)) {
                                clients.add(0, objectOut, outStream, thisUser);
                            } else {
                                clients.addElement(objectOut, outStream, thisUser);
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

                        }
                    } else if (packet instanceof SessionStatePacket) {
                        Session session = getSession(thisUser.getSessionId());
                        SessionStatePacket p = (SessionStatePacket) packet;
                        session.setState(p.getState());
                        setCurrentSession(session);
                        String msg = p.getState() ? session.getFullName() + " presenting. Started at " + session.getTime() : "Session Paused";
                        MsgPacket msgP = new MsgPacket(msg, true, false);
                        broadcastMsg(msgP, thisUser.getSessionId());
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
                    } else if (packet instanceof ModuleFileRequestPacket) {

                        ModuleFileRequestPacket p = (ModuleFileRequestPacket) obj;
                        processModuleFilePacketRequest(p);
                    } else if (packet instanceof ModuleFileReplyPacket) {
                        ModuleFileReplyPacket p = (ModuleFileReplyPacket) packet;
                        processModuleFileReplyPacket(p);
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
                        clients.removeElement(objectOut, outStream);
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
                        synchronized (clients) {
                            // Session session = getSession(thisUser.getSessionId());
                            ChatPacket p = (ChatPacket) packet;
                            chats.addLast(p);

                            logChat("ChatLog.txt", "[" + p.getTime() + "] <" + p.getUsr() + "> " + p.getContent());
                            if (chats.size() > MAX_CHAT_SIZE) {
                                chats.removeFirst();
                            }

                            broadcastChat(p, p.getSessionId());
                        }
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
                break;
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
        broadcastPacket(p);
    }

    private void processFilePacket(FilePacket packet) {
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
                        break;
                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }
                }
            }
        }
    }

    private void processModuleFileReplyPacket(ModuleFileReplyPacket packet) {
        for (int i = 0; i < clients.size(); i++) {

            if (clients.nameAt(i).getUserName().equals(packet.getUsername())) {
                try {
                    clients.elementAt(i).writeObject(packet);
                    clients.elementAt(i).flush();
                    break;
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

        }
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
            ex.printStackTrace();
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

            ex.printStackTrace();
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

            ex.printStackTrace();
        }
    }

    private void processModuleFilePacketRequest(ModuleFileRequestPacket packet) {
        //locate the slides server based on the ids
        boolean slideServerFound = false;
        for (int i = 0; i < slideServers.size(); i++) {
            if (slideServers.elementAt(i).getId().equals(packet.getSlidesServerId())) {
                sendPacket(packet, slideServers.elementAt(i).getObjectOutputStream());
                slideServerFound = true;
                System.out.println("requesting from slide server ...");
            }
        }
        if (!slideServerFound) {
            System.out.println("slide server not foound!!");
            sendPacket(new MsgPacket("Media library server not found! Audio/Video may not work.", false, true), objectOutStream);
        }
    }

    private void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        //locate the slides server based on the ids
        boolean slideServerFound = false;
        for (int i = 0; i < slideServers.size(); i++) {
            if (slideServers.elementAt(i).getId().equals(packet.getSlidesServerId())) {
                sendPacket(packet, slideServers.elementAt(i).getObjectOutputStream());
                slideServerFound = true;
            }
        }
        if (!slideServerFound) {
            sendPacket(new MsgPacket("Slide server not found!", false, true), objectOutStream);
        }
    }

    private void processResetSession(VotePacket p) {
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(p.getSessionId())) {
                AttentionPacket ap = new AttentionPacket(clients.nameAt(i).getUserName(),
                        p.getSessionId(), false,
                        false, false, false, false, false);
                broadcastPacket(ap, p.getSessionId());
            }
        }
    }

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
    public void logChat(String fileName, String txt) {
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
                ex.printStackTrace();
            }
        }
        return xusrs;
    }

    private void broadcastChat(ChatPacket chatPacket, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {

                    clients.elementAt(i).writeObject(chatPacket);
                    clients.elementAt(i).flush();

                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastPacket(RealtimePacket packet, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {
                    if (!clients.nameAt(i).isPresenter()) {
                        clients.elementAt(i).writeObject(packet);
                        clients.elementAt(i).flush();
                    }
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastAudioPacket(AudioPacket packet) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(packet.getSessionId())) {

                //dont send back to self...
                if (clients.elementAt(i) != objectOutStream) {
                    try {

                        clients.elementAt(i).writeObject(packet);
                        clients.elementAt(i).flush();

                    } catch (IOException ex) {
                        ex.printStackTrace();
                    }
                }
            }
        }

    }

    private void broadcastPacket(RealtimePacket packet) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(packet.getSessionId())) {
                try {
                    if (!clients.nameAt(i).isPresenter()) {
                        clients.elementAt(i).writeObject(packet);
                        clients.elementAt(i).flush();
                    }
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastClearVotePacket(RealtimePacket packet, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {
                    clients.elementAt(i).writeObject(packet);
                    clients.elementAt(i).flush();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastSurveyAnswerPacket(RealtimePacket packet, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {
                    if (clients.nameAt(i).isPresenter()) {
                        clients.elementAt(i).writeObject(packet);
                        clients.elementAt(i).flush();
                    }
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastAttention(AttentionPacket p, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {

                    clients.elementAt(i).writeObject(p);
                    clients.elementAt(i).flush();

                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastPrivateAttention(AttentionPacket p, String sessionId) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {
                    if (clients.nameAt(i).isPresenter()) {
                        clients.elementAt(i).writeObject(p);
                        clients.elementAt(i).flush();
                    }
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastSlide(NewSlideReplyPacket p) {

        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(p.getSessionId())) {
                try {

                    clients.elementAt(i).writeObject(p);
                    clients.elementAt(i).flush();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private void broadcastMsg(MsgPacket p, String sessionId) {
        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(sessionId)) {
                try {
                    clients.elementAt(i).writeObject(p);
                    clients.elementAt(i).flush();
                } catch (IOException ex) {
                    ex.printStackTrace();
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
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(user.getSessionId()) &&
                    !clients.nameAt(i).getUserName().equals(user.getUserName())) {
                try {
                    clients.elementAt(i).writeObject(new NewUserPacket(user));
                    clients.elementAt(i).flush();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    /**
     * Update rest of the participants in this session if the user
     * @param user
     */
    private void broadcastRemovedUser(RemoveUserPacket p) {
        //send it to all those people logged in on this presentation (session)
        for (int i = 0; i < clients.size(); i++) {
            if (clients.nameAt(i).getSessionId().equals(p.getUser().getSessionId())) {
                try {
                    clients.elementAt(i).writeObject(p);
                    clients.elementAt(i).flush();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    private Session getSession(String sessionId) {
        for (int i = 0; i < presentationLocks.size(); i++) {
            if (sessionId.equals(presentationLocks.elementAt(i).getSessionId())) {
                return presentationLocks.elementAt(i);
            }
        }
        return null;
    }

    private void setCurrentSession(Session session) {
        for (int i = 0; i < presentationLocks.size(); i++) {
            if (session.getSessionId().equals(presentationLocks.elementAt(i).getSessionId())) {
                presentationLocks.set(i, session);
                return;
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
                        p.setMessage("Presentation is locked by " + session.getFullName());
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
                p.setMessage("You are not in sync with presenter");
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
            ex.printStackTrace();
        }

    }

    private boolean replyViaSlideServer(NewSlideRequestPacket packet, ObjectOutputStream tout) {
        for (int i = 0; i < slideServers.size(); i++) {
            if (slideServers.elementAt(i).getId().equals(thisUser.getHost())) {
            }

        }
        return false;
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
            ex.printStackTrace();
        }

    }
}
