/*
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
package avoir.realtime.tcp.proxy;

import avoir.realtime.tcp.common.packet.LocalSlideCacheRequestPacket;
import avoir.realtime.tcp.common.packet.NewSlideReplyPacket;
import avoir.realtime.tcp.common.packet.RemoveMePacket;
import avoir.realtime.tcp.common.packet.TestPacket;
import java.io.IOException;
import java.util.Vector;
import java.util.logging.Logger;

/**
 * This class processes all packets related to presentation slides
 * @author David Wafula
 */
public class SlideProcessor {

    private static Logger logger = Logger.getLogger(SlideProcessor.class.getName());
    private ServerThread serverThread;
    private Vector<SlideServer> slideServers;
    private ClientList clients;
    private boolean waitForSlideServer = false;

    public SlideProcessor(ServerThread serverThread, Vector<SlideServer> slideServers, ClientList clients) {
        this.serverThread = serverThread;
        this.slideServers = slideServers;
        this.clients = clients;
    }

    /**
     * Add new slide server. But we dont need duplicates, so take care of those first
     * @param ss
     */
    public void addSlideServer(SlideServer ss) {
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

    public void broadcastSlide(NewSlideReplyPacket p) {
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

    public void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        removeNullSlideServers();
        //locate the slides server based on the ids
        boolean slideServerFound = false;
        int MAX_TRIES = 10;
        int nooftries = 0;
        int sleep = 1 * 1000;
        waitForSlideServer = true;

        removeNullSlideServers();
        while (!slideServerFound) {
            synchronized (slideServers) {
                if (slideServers.size() == 0) {
                    break;
                }
                for (int i = 0; i < slideServers.size(); i++) {
                    System.out.print("Testing Slide server : " + nooftries + " ..");
                    if (slideServers.elementAt(i).getId().equals(packet.getSlidesServerId())) {
                        serverThread.sendPacket(packet, slideServers.elementAt(i).getObjectOutputStream());
                        slideServerFound = true;
                        waitForSlideServer = false;
                        System.out.println("OK");
                        break;
                    }
                }
            }
            if (nooftries++ > MAX_TRIES) {
                break;
            }
            System.out.println("FAIL");
            serverThread.delay(sleep);

        }

        if (!slideServerFound) {
            // sendPacket(new MsgPacket("Internal Error: Slide server " + packet.getSlidesServerId() + " not found!", Constants.LONGTERM_MESSAGE, Constants.ERROR_MESSAGE, MessageCode.CLASSROOM_SPECIFIC), objectOutStream);
        }
    }

    public void removeNullSlideServers() {
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

    public void removeSlideServer(RemoveMePacket p) {
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
}
