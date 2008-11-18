/**
 *
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
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.server;

import avoir.realtime.common.user.User;
import avoir.realtime.common.user.UserLevel;
import avoir.realtime.common.packet.ChatPacket;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.LinkedList;

/**
 * This class servers as a communication interface to j2me clients.
 * @author David Wafula
 */
public class J2MEServer {

    private ServerThread serverThread;
    private boolean running = true;
    private InputStream inStream;
    private OutputStream outStream;
    private String thisUser;
    private String sessionId = "default_1216_1212841216";

    public J2MEServer(ServerThread serverThread) {
        this.serverThread = serverThread;
        inStream = serverThread.getInStream();
        outStream = serverThread.getOutStream();
    }

    public void setInStream(InputStream inStream) {
        this.inStream = inStream;
    }

    public void setOutStream(OutputStream outStream) {
        this.outStream = outStream;
    }

    public void listen() {


        while (running) {
            try {
                String clientCommand = "";
                int c = 0;
                BufferedReader br = new BufferedReader(new InputStreamReader(inStream));

                clientCommand = br.readLine();
                if (clientCommand == null) {
                    continue;
                }
                if (clientCommand.startsWith(J2MECommands.NEWUSER)) {
                    thisUser = clientCommand.substring(J2MECommands.NEWUSER.length() + 1);

                    serverThread.getMobileUsers().add(new MobileUser(thisUser, outStream));
                    //send to desktop users too

                    User user = new User(UserLevel.GUEST, thisUser, thisUser, "", 0, false, sessionId, "", "", false, "", "");
                    serverThread.setThisUser(user);
                    serverThread.broadcastUser(user);
                    updateUserList();

                    populateReply("USER#" + thisUser, false);

                }
                if (clientCommand.startsWith("CHAT#")) {
                    ChatPacket p = new ChatPacket(thisUser, clientCommand.substring("CHAT#".length()), "time", "chat.log", sessionId);
                    serverThread.log("ChatLog.txt", "[" + p.getTime() + "] <" + p.getUsr() + "> " + p.getContent());
                    LinkedList<ChatPacket> chats = serverThread.getChats();
                    synchronized (chats) {

                        if (chats.size() > serverThread.getMAX_CHAT_SIZE()) {
                            chats.removeFirst();
                        }
                    }
                    serverThread.broadcastChat(p, sessionId);
                }
            } catch (IOException ex) {
                ex.printStackTrace();
                break;
            }
        }
    }

    public void removeMeFromList() {
        for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
            String currentUser = serverThread.getMobileUsers().elementAt(i).getUsername();
            if (currentUser.equalsIgnoreCase(thisUser)) {
                serverThread.getMobileUsers().remove(i);
            }
        }
    }

    private void updateUserList() {
        for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
            sendReply("USER#" + serverThread.getMobileUsers().elementAt(i).getUsername());
        }
    }

    public void sendReply(String reply) {
        try {
            outStream.write(reply.getBytes());
            outStream.flush();
            outStream.write('\n');
            outStream.flush();

        } catch (Exception ex) {
            ex.printStackTrace();

        }

    }

    public void populateGraphic(byte[] buf) {
        try {
            for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
                OutputStream xoutStream = serverThread.getMobileUsers().elementAt(i).getOut();


                xoutStream.write("GRAPHIC".getBytes());
                xoutStream.flush();

                String gr = Base64.encode(buf);
                xoutStream.write(gr.getBytes());
                xoutStream.flush();



            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public void populateReply(String reply, boolean self) {
        try {
            if (self) {
                for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
                    OutputStream xoutStream = serverThread.getMobileUsers().elementAt(i).getOut();
                    xoutStream.write(reply.getBytes());
                    xoutStream.flush();
                    xoutStream.write('\n');
                    xoutStream.flush();
                }
            } else {
                for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
                    OutputStream xoutStream = serverThread.getMobileUsers().elementAt(i).getOut();
                    String currentUser = serverThread.getMobileUsers().elementAt(i).getUsername();
                    if (!currentUser.equalsIgnoreCase(thisUser)) {
                        xoutStream.write(reply.getBytes());
                        xoutStream.flush();
                        xoutStream.write('\n');
                        xoutStream.flush();
                    }
                }
            }

        } catch (Exception ex) {
            ex.printStackTrace();

        }

    }
}
