/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.webbrowser;

import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserListener;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserNavigationEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserWindowOpeningEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserWindowWillOpenEvent;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smack.util.Base64;

/**
 *
 * @author developer
 */
public class RWebBrowserListener implements WebBrowserListener {

    private JWebBrowser wb;

    public RWebBrowserListener(JWebBrowser wb) {
        this.wb = wb;
    }

    public void commandReceived(WebBrowserEvent arg0, String arg1, String[] arg2) {
    }

    public void loadingProgressChanged(WebBrowserEvent evt) {
    }

    public void locationChangeCanceled(WebBrowserNavigationEvent evt) {
    }

    public void locationChanged(WebBrowserNavigationEvent evt) {
        //only up date if changes azre from the instructor, else we run into 
        //an infite loop
        if (GeneralUtil.isInstructor()) {
            RealtimePacket p = new RealtimePacket(RealtimePacket.Mode.UPDATE_URL);
            StringBuilder sb = new StringBuilder();
            String url = wb.getResourceLocation();
            url = Base64.encodeBytes(url.getBytes());
            sb.append("<url>").append(url).append("</url>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
        }
    }

    public void locationChanging(WebBrowserNavigationEvent evt) {
    }

    public void statusChanged(WebBrowserEvent evt) {
    }

    public void titleChanged(WebBrowserEvent arg0) {
    }

    public void windowClosing(WebBrowserEvent arg0) {
    }

    public void windowOpening(WebBrowserWindowOpeningEvent arg0) {
    }

    public void windowWillOpen(WebBrowserWindowWillOpenEvent arg0) {
    }
}
