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
package avoir.realtime.tcp.monitor;


import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.logging.Level;
import java.util.logging.Logger;


/**
 * Sets up the ServerSocket and ServerThread
 */
public class MonitorServerListener extends Thread {

    private static Logger logger = Logger.getLogger(MonitorServerListener.class.getName());
    private int port;
    private MonitorClientList clients = new MonitorClientList();


    /**
     * The port number
     * @param port port number
     */
    public MonitorServerListener(int port) {
        this.port = port;
    }

    

    /**
     * Run method
     */
    @Override
    public void run() {
        try {
            ServerSocket serverSocket = new ServerSocket(port);
            
            logger.info("SSL factory started..");
            logger.info("Listening for connections on port " + port);
            while (true) {
                try {
                    // Socket socket = serverSocket.accept();

                    Socket socket = serverSocket.accept();
                        MonitorServerThread server = new MonitorServerThread(socket,
                                clients);
                        new Thread(server).start();
                    
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
