/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
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
package avoir.realtime.appsharing;

import avoir.realtime.common.appshare.DesktopPacketIntf;
import avoir.realtime.common.appshare.DesktopPacketProcessor;

/**
 *
 * @author developer
 */
public class DefaultDesktopPacketProcessor extends DesktopPacketProcessor {

    private RemoteDesktopViewerFrame viewer;
    private AppView appViewer;
    private boolean showAppSharingFrame = true;

    public DefaultDesktopPacketProcessor() {
    }

    public void processDesktopPacket(final DesktopPacketIntf packet) {


        if (showAppSharingFrame) {
            int hh = packet.getData().getHeight();
            int ww = packet.getData().getWidth();

            viewer = new RemoteDesktopViewerFrame();

            viewer.setSize(ww, hh);
            appViewer = new AppView(viewer.getWidth(), viewer.getHeight());
            viewer.setContentPane(appViewer);
            viewer.setVisible(true);
            showAppSharingFrame = false;
        }

        Thread t = new Thread() {

            public void run() {

                appViewer.pixelUpdate(packet.getData());
            }
        };
        t.start();
    }
}
