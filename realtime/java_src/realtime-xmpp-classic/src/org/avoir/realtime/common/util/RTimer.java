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
    public static int recording_INTERVAL = 250;
    private static Timer timer = new Timer();
    private static Timer mytimer = new Timer();
    private static Timer recordtimer = new Timer();
    private static DecimalFormat decimalFormat=new DecimalFormat("00");
    private static MySessionTimer sessionTimer;

    public static void init() {
        timer.cancel();
        timer = new Timer();
        timer.scheduleAtFixedRate(sessionTimer = new MySessionTimer(), 0, INTERVAL);
        //recordtimer.cancel();
        //recordtimer = new Timer();
        //recordtimer.scheduleAtFixedRate(sessionTimer = new MySessionTimer(), 0, recording_INTERVAL);

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

    public static double getTimeStamp(){
        return (double) sessionTimer.microSeconds/250;
    }

    static class MySessionTimer extends TimerTask {

        static int hours = 0;
        static int minutes = 0;
        static int seconds = 0;
        static int microSeconds = 0 ;

        public void run() {
            seconds++;
            microSeconds++;
            if (seconds > 59) {
                minutes++;
                seconds = 0;
            }
            if (minutes > 59) {
                hours++;
                minutes = 0;
            }

            GUIAccessManager.mf.getTimerField().setText("<html>In session for <font color=\"green\">"+decimalFormat.format(hours)+"h "+decimalFormat.format(minutes)+"m "+decimalFormat.format(seconds)+"s</font>");
            //GUIAccessManager.mf.getInfoField().setText("This is the time "+microSeconds);
        }


    }
}
