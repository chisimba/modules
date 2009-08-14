/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;

public class Speaker {

    private JWebBrowser webBrowser;
    private String speaker;

    public Speaker(JWebBrowser webBrowser, String speaker) {
        this.webBrowser = webBrowser;
        this.speaker = speaker;
    }

    public String getSpeaker() {
        return speaker;
    }

    public void setSpeaker(String speaker) {
        this.speaker = speaker;
    }

    public JWebBrowser getWebBrowser() {
        return webBrowser;
    }

    public void setWebBrowser(JWebBrowser webBrowser) {
        this.webBrowser = webBrowser;
    }
}
