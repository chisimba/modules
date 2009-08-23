/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.common.util;

import java.text.DecimalFormat;
import java.util.Timer;
import java.util.TimerTask;
import org.avoir.realtime.gui.main.GUIAccessManager;

/**
 *
 * @author kim
 */
public class RTimer {

    public static int INTERVAL = 1000;
    private static Timer timer = new Timer();
    private static Timer mytimer = new Timer();
    private static DecimalFormat decimalFormat=new DecimalFormat("00");

    public static void init() {

        timer.cancel();
        timer = new Timer();
        timer.scheduleAtFixedRate(new MySessionTimer(), 0, INTERVAL);
    }

    public static void initMyTimer() {
        mytimer.cancel();
        mytimer = new Timer();
        mytimer.scheduleAtFixedRate(new MySessionTimer(), 1000, 1000);
    }

    public static void reset() {
        timer.cancel();
        timer = new Timer();
    }

    static class SessionTimer extends TimerTask {

        public void run() {
        }
    }

    static class MySessionTimer extends TimerTask {

        int hours = 0;
        int minutes = 0;
        int seconds = 0;

        public void run() {
            seconds++;
            if (seconds > 59) {
                minutes++;
                seconds = 0;
            }
            if (minutes > 59) {
                hours++;
                minutes = 0;
            }

            GUIAccessManager.mf.getTimerField().setText("<html>In session for <font color=\"green\">"+decimalFormat.format(hours)+"h: "+decimalFormat.format(minutes)+"m:"+decimalFormat.format(seconds)+"s</font>");
        }
    }
}
