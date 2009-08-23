/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import javax.swing.JButton;

public class Speaker {

    private JWebBrowser webBrowser;
    private String speaker;
    private JButton button;

    public Speaker(JWebBrowser webBrowser, String speaker, JButton button) {
        this.webBrowser = webBrowser;
        this.speaker = speaker;
        this.button = button;
    }

    public String getSpeaker() {
        return speaker;
    }

    public JButton getButton() {
        return button;
    }

    public void setButton(JButton button) {
        this.button = button;
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
