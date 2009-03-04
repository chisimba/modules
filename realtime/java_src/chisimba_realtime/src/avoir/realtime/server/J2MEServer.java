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

import avoir.realtime.common.Base64;
import avoir.realtime.common.user.User;
import avoir.realtime.common.user.UserLevel;
import avoir.realtime.common.packet.ChatPacket;
import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.RenderingHints;
import java.awt.image.BufferedImage;
import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.LinkedList;
import java.util.Vector;
import javax.imageio.ImageIO;
import javax.swing.ImageIcon;

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
    private String sessionId = "temp";

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
                    User user = new User(UserLevel.GUEST, thisUser, thisUser, "", 0, false, sessionId, "", "", false, "", "");

                    serverThread.getMobileUsers().add(new MobileUser(user, outStream));
                    // serverThread.getClients().addElement(serverThread.getSocket(), serverThread.getObjectOutStream(), outStream, user);
                    //send to desktop users too

                    serverThread.setThisUser(user);
                    serverThread.broadcastUser(user);
                    updateUserList();

                    populateReply("USER#" + thisUser, false);
                    Vector<String> desktopUsers = serverThread.getDesktopUsers();

                    for (int i = 0; i < desktopUsers.size(); i++) {
                        populateReply("USER#" + desktopUsers.elementAt(i), false);

                    }
                }
                if (clientCommand.startsWith("DISCONNECT")) {
                    running = false;
                }
                if (clientCommand.startsWith("CHAT#")) {
                    ChatPacket p = new ChatPacket(thisUser, clientCommand.substring("CHAT#".length()), "time", "chat.log", sessionId, new Color(0, 131, 0), "SansSerif", 1, 15, false, null);
                    serverThread.log("ChatLog.txt", "[" + p.getTime() + "] <" + p.getUsr() + "> " + p.getContent());
                    LinkedList<ChatPacket> chats = serverThread.getChats();
                    synchronized (chats) {

                        if (chats.size() > serverThread.getMAX_CHAT_SIZE()) {
                            chats.removeFirst();
                        }
                    }
                    serverThread.broadcastChat(p, sessionId);
                    populateReply("CHAT#"+thisUser+": "+ clientCommand.substring("CHAT#".length()), true);
                }
            } catch (Exception ex) {
                ex.printStackTrace();
                break;
            }
        }
    }

    public Image getScaledImage(Image srcImg, int w, int h) {

        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public void removeMeFromList() {
        for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
            System.out.println("Testing " + serverThread.getMobileUsers().elementAt(i) + "# against " + thisUser + "#");
            User currentUser = serverThread.getMobileUsers().elementAt(i).getUser();
            if (currentUser.getUserName().equalsIgnoreCase(thisUser)) {
                System.out.println(thisUser + " removed");
                serverThread.getMobileUsers().remove(i);
            }
        }
    }

    private void updateUserList() {
        for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
            sendReply("USER#" + serverThread.getMobileUsers().elementAt(i).getUser().getUserName());
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

    public void populateGraphic(String filePath) {
        try {
            Image img = new ImageIcon(filePath).getImage();
            Image scaledImage = getScaledImage(img, 160, 120);

            BufferedImage bu = new BufferedImage(scaledImage.getWidth(null), scaledImage.getHeight(null), BufferedImage.TYPE_INT_RGB);
            Graphics g = bu.getGraphics();
            g.drawImage(scaledImage, 0, 0, null);
            g.dispose();
            ByteArrayOutputStream bas = new ByteArrayOutputStream();
            ImageIO.write(bu, "png", bas);
            byte[] data = bas.toByteArray();
            for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
                OutputStream xoutStream = serverThread.getMobileUsers().elementAt(i).getOut();
                xoutStream.write("GRAPHIC".getBytes());
                xoutStream.flush();
                String gr = Base64.encode(data);
                xoutStream.write(gr.getBytes());
                xoutStream.flush();

                System.out.println("send graphic");

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
                    System.out.println("to j2me user: " + reply);
                }
            } else {
                for (int i = 0; i < serverThread.getMobileUsers().size(); i++) {
                    OutputStream xoutStream = serverThread.getMobileUsers().elementAt(i).getOut();
                    User currentUser = serverThread.getMobileUsers().elementAt(i).getUser();
                    if (!currentUser.getUserName().equals(thisUser)) {
                        xoutStream.write(reply.getBytes());
                        xoutStream.flush();
                        xoutStream.write('\n');
                        xoutStream.flush();
                        System.out.println("to j2me user: " + reply);
                    }
                }
            }

        } catch (Exception ex) {
            ex.printStackTrace();

        }

    }
}
