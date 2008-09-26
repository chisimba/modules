/**
 * 	$Id: Server.java,v 1.6 2007/03/28 10:35:52 adrian Exp $
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

import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * The server for the Realtime applications.  Serves as the main class for the project
 */
public class MonitorServer {

    private static Logger logger = Logger.getLogger(MonitorServer.class.getName());

    /**
     * Main method for running the whiteboard server.
     * 
     * @param argv String array of arguments.
     */
    public static void main(String[] argv) {
        if (argv.length > 1) {
            throw new IllegalArgumentException("Syntax: Server [<port>]");
        }
        logger.info("Monitor Server started...\n");

        int port = argv.length == 0 ? 22225
                : Integer.parseInt(argv[0]);
        try {
            MonitorServerListener listener = new MonitorServerListener(port);
            new Thread(listener).start();
        } catch (Exception e) {
            logger.log(Level.SEVERE, "Error spawning listener thread", e);
        }

    }
}
