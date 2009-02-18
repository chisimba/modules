/*
 * Copyright (C) GNU/GPL AVOIR 2008
 *
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
package avoir.realtime.common;

import avoir.realtime.chat.PrivateChatFrame;
import avoir.realtime.classroom.SessionTimer;
import avoir.realtime.common.packet.AckPacket;
import avoir.realtime.common.packet.RealtimePacket;
import avoir.realtime.common.user.User;
import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.Map;
import java.util.Vector;

/**
 *
 * @author David Wafula
 */
public abstract class TCPSocket {

    protected ObjectInputStream reader;
    protected ObjectOutputStream writer;
    private Socket socket;
    protected boolean running = false;
    protected boolean connected = false;
    protected String host;
    protected int port;
    private String name;

    /**
     * Initial connection
     * @return
     */
    public boolean connect(String host, int port) {
        boolean result = false;
        this.host = host;
        this.port = port;
        try {
            try {

                /* SSLContext context = null;
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
                 */
                socket = new Socket(host, port);
                result = true;
            } catch (UnknownHostException ex) {
                ex.printStackTrace();
                return false;
            }
            OutputStream out = socket.getOutputStream();
            out.write("avoirdesktopclient".getBytes());
            out.flush();
            out.write('\n');
            out.flush();
            writer = new ObjectOutputStream(new BufferedOutputStream(out));
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
            ex.printStackTrace();
            return false;
        }
        return result;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
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

    public abstract void startSession();

    public abstract Map<String, PrivateChatFrame> getPrivateChats();

    public abstract SessionTimer getSessionTimer();

    public void setReader(ObjectInputStream reader) {
        this.reader = reader;
    }

    public void setWriter(ObjectOutputStream writer) {
        this.writer = writer;
    }

    /**
     * Resquests user list, but in turn also gets published
     */
    public void publish(User user) {
        sendPacket(new AckPacket(user, user.isPresenter()));
    }

    /**
     * Listen for incoming packets from the server
     * This method should be implemented by the subclasses
     */
    public void listen() {
    }

    /**
     * sends a TCP Packet to server
     * @param packet
     */
    public synchronized void sendPacket(RealtimePacket packet) {
        try {

            writer.writeObject(packet);
            writer.flush();


        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }
}
