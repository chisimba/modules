/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.sound;

import java.util.Timer;
import java.util.TimerTask;
import javax.swing.ImageIcon;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.ConnectionManager;

/**
 *
 * @author kim
 */
public class SoundMonitor {

    private Timer timer = new Timer();
    private ImageIcon warnIcon = ImageUtil.createImageIcon(this, "/images/warn.png");

    public void init() {
        timer.cancel();
        timer = new Timer();
        timer.scheduleAtFixedRate(new SoundMonitorTimer(), 0, 1000);
    }

    public void cancel() {
        timer.cancel();
    }

    class SoundMonitorTimer extends TimerTask {

        boolean on = false;

        public void run() {
            if (ConnectionManager.isOwner) {
                if (!GUIAccessManager.mf.getUserListPanel().isAudioEnabled()) {
                    GUIAccessManager.mf.getUserListPanel().getSoundAlerterField().setIcon(on ? warnIcon : null);
                    GUIAccessManager.mf.getUserListPanel().getSoundAlerterField().setText(on ? "Audio not initialized. Click here" : "");
                } else {
                    cancel();
                }
            }
            on = !on;
        }
    }
}
