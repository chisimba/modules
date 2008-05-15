/**
 * 	$Id: ServerListener.java,v 1.4 2007/03/05 12:55:04 adrian Exp $
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

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.logging.Level;
import java.util.logging.Logger;
import avoir.realtime.common.DEBUG_ENGINE;
import java.util.Vector;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.AttentionPacket;
import java.util.LinkedList;
import java.io.OutputStream;
import java.net.InetAddress;

/**
 * Sets up the ServerSocket and ServerThread
 */
public class ServerListener extends Thread {

    private static Logger logger = Logger.getLogger(ServerListener.class.getName());
    private int port;
    private ClientList clients = new ClientList();
    private LinkedList<ChatPacket> chats = new LinkedList<ChatPacket>();
    private LinkedList<AttentionPacket> raisedHands = new LinkedList<AttentionPacket>();
    private Vector<Session> presentationLocks = new Vector<Session>();
    private Vector<SlideServer> slideServers = new Vector<SlideServer>();
//    private Vector<MediaInputStream> mediaInputStreams = new Vector<MediaInputStream>();
    private String[] unwantedHosts = {"192.102.9.73"};

    /**
     * The port number
     * @param port port number
     */
    public ServerListener(int port) {
        this.port = port;
    }

    private boolean invalidClient(Socket socket) {
        InetAddress address = socket.getInetAddress();
        for (int i = 0; i < unwantedHosts.length; i++) {
            if (unwantedHosts[i].indexOf(address.getHostName()) > -1 ||
                    unwantedHosts[i].indexOf(address.getHostAddress()) > -1) {
                try {
                    socket.close();
                } catch (IOException ex) {
                    System.err.println("Could not close: " + socket.getInetAddress());
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Run method
     */
    @Override
    public void run() {
        try {
            ServerSocket serverSocket = new ServerSocket(port);
            if (DEBUG_ENGINE.debug_level > 0) {
                DEBUG_ENGINE.print("Listening for connections on port " + port);
            }
            logger.info("Listening for connections on port " + port);
            while (true) {
                try {
                    Socket socket = serverSocket.accept();
                    if (!invalidClient(socket)) {
                        ServerThread server = new ServerThread(socket,
                                clients,
                                chats, presentationLocks,
                                slideServers,
                                raisedHands, new Vector());
                        new Thread(server).start();
                    }
                } catch (Exception e) {
                    logger.log(Level.SEVERE, "Error spawning listener thread",
                            e);
                }
            }
        } catch (IOException e) {
            logger.log(Level.SEVERE, "Error assigning server socket", e);
        }
    }
}
