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
package avoir.realtime.tcp.monitor;


import java.io.*;

import java.net.Socket;
import java.util.logging.Level;
import java.util.logging.Logger;

import avoir.realtime.tcp.common.packet.*;

import java.util.LinkedList;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * Handles communications for the server, to and from the clients Processes
 * packets from client actions and broadcasts these updates
 */
@SuppressWarnings("serial")
public class MonitorServerThread extends Thread {

    private static DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd H:mm:ss");
    private static Logger logger = Logger.getLogger(MonitorServerThread.class.getName());
    private LinkedList<ChatPacket> chats;
    private MonitorClientList clients;
    private static String tName;
    int startX = 10;
    int startY = 10;
    private Socket socket;
    private InputStream inStream;
    private OutputStream outStream;
    private ObjectOutputStream objectOutStream;
    public static final int BUFFER_SIZE = 4096;
    private Socket forwader;

    /**
     * Constructor accepts connections
     * 
     * @param socket The socket
     */
    public MonitorServerThread(Socket socket,
            MonitorClientList clients) {

        this.clients = clients;

        tName = getName();
        logger.info("Server " + tName + " accepted connection from " + socket.getInetAddress() + "\n");
        this.socket = socket;
//        forwader=new

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
     * Get todays datetime
     * @return
     */
    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
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
                    logger.info(ex.getLocalizedMessage());

                    break;
                }

            } catch (Exception ex) {

                ex.printStackTrace();
            // break;
            }
        }
    }
}
