package org.avoir.realtime.gui.screenviewer.webstart.gui;

import java.util.Date;

import java.awt.*;
import java.awt.event.*;

import javax.imageio.ImageIO;
import javax.swing.*;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;

import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.screenviewer.webstart.beans.ConnectionBean;
import org.avoir.realtime.gui.screenviewer.webstart.screen.BlankArea;
import org.avoir.realtime.gui.screenviewer.webstart.screen.ScreenJob;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.quartz.impl.StdSchedulerFactory;
import org.quartz.SchedulerFactory;
import org.quartz.Scheduler;
import org.quartz.Trigger;
import org.quartz.TriggerUtils;
import org.quartz.JobDetail;

public class StartScreen {

    public static StartScreen instance = null;
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    java.awt.Container contentPane;
    SchedulerFactory schedFact;
    Scheduler sched;
    JFrame t;
    JLabel textArea;
    JLabel textWarningArea;
    JLabel textAreaQualy;
    JButton startButton;
    JButton stopButton;
    JButton exitButton;
    JSpinner jSpin;
    JLabel tFieldScreenZoom;
    JLabel blankArea;
    BlankArea virtualScreen;
    JLabel vscreenXLabel;
    JLabel vscreenYLabel;
    JSpinner jVScreenXSpin;
    JSpinner jVScreenYSpin;
    JLabel vscreenWidthLabel;
    JLabel vscreenHeightLabel;
    JSpinner jVScreenWidthSpin;
    JSpinner jVScreenHeightSpin;
    JLabel vScreenIconLeft;
    JLabel vScreenIconRight;
    JLabel vScreenIconUp;
    JLabel vScreenIconDown;
    JLabel myBandWidhtTestLabel;

    public void initMainFrame() {
        try {

            UIManager.setLookAndFeel(new com.incors.plaf.kunststoff.KunststoffLookAndFeel());


//			 make Web Start happy
//			 see http://developer.java.sun.com/developer/bugParade/bugs/4155617.html
            UIManager.getLookAndFeelDefaults().put("ClassLoader", getClass().getClassLoader());


            schedFact = new StdSchedulerFactory();
            sched = schedFact.getScheduler();
            sched.start();

            t = new JFrame("Desktop Publisher");
            contentPane = t.getContentPane();
            contentPane.setBackground(Color.WHITE);
            textArea = new JLabel();
            textArea.setBackground(Color.WHITE);
            contentPane.setLayout(null);
            contentPane.add(textArea);
            textArea.setText("This module will allow you to publish your screen");
            textArea.setBounds(10, 0, 400, 24);

            startButton = new JButton("start Sharing");
            startButton.addActionListener(new ActionListener() {

                public void actionPerformed(ActionEvent arg0) {
                    GUIAccessManager.mf.setSize(ss.width / 4, ss.height);
                    
                    captureScreenStart();
                    GUIAccessManager.mf.getWebbrowserManager().showScreenShareViewerAsEmbbededTab(t);
                    t.setVisible(false);


                    RealtimePacket p = new RealtimePacket();
                    p.setMode(RealtimePacket.Mode.SCREEN_SHARE_INVITE);
                    StringBuilder sb = new StringBuilder();
                    sb.append("<instructor>").append(ConnectionManager.getUsername()).append("</instructor>");
                    sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                    p.setContent(sb.toString());
                    ConnectionManager.sendPacket(p);

                }
            });
            startButton.setBounds(10, 50, 200, 24);
            t.add(startButton);


            stopButton = new JButton("Stop Sharing");
            stopButton.addActionListener(new ActionListener() {

                public void actionPerformed(ActionEvent arg0) {
                    GUIAccessManager.mf.setAlwaysOnTop(false);
                    GUIAccessManager.mf.setSize(ss);
                    captureScreenStop();
                    GUIAccessManager.mf.getChatRoomManager().sendMessage("", 0, Color.BLACK, "stop-screen-share");
                }
            });
            stopButton.setBounds(220, 50, 200, 24);
            stopButton.setEnabled(false);
            t.add(stopButton);

            jSpin = new JSpinner(new SpinnerNumberModel(25, 10, 100, 5));
            jSpin.setBounds(140, 80, 50, 24);
            jSpin.addChangeListener(new ChangeListener() {

                public void stateChanged(ChangeEvent arg0) {

                    setNewStepperValues();
                }
            });
            t.add(jSpin);

            textAreaQualy = new JLabel();
            contentPane.add(textAreaQualy);
            textAreaQualy.setText("Quality (%)");
            textAreaQualy.setBackground(Color.LIGHT_GRAY);
            textAreaQualy.setBounds(10, 80, 100, 24);

            //add the small screen thumb to the JFrame
            new VirtualScreen();

            textWarningArea = new JLabel();
            contentPane.add(textWarningArea);
            textWarningArea.setBounds(10, 310, 400, 54);
            //textWarningArea.setBackground(Color.WHITE);

            Image im_left = ImageIO.read(StartScreen.class.getResource("/images/background.png"));
            ImageIcon iIconBack = new ImageIcon(im_left);

            JLabel jLab = new JLabel(iIconBack);
            jLab.setBounds(0, 0, 500, 440);
            t.add(jLab);

            t.addWindowListener(new WindowAdapter() {

                public void windowClosing(WindowEvent e) {
                    t.setVisible(false);

                }
            });
            t.pack();
            t.setSize(500, 440);
            t.setLocationRelativeTo(GUIAccessManager.mf);
            t.setVisible(true);
            t.setResizable(false);

            System.err.println("initialized");

        } catch (Exception err) {
            System.out.println("randomFile Exception: ");
            err.printStackTrace();
        }

    }

    void setNewStepperValues() {
        //System.out.println(jSpin.getValue());
        ConnectionBean.imgQuality = new Float(Double.valueOf(jSpin.getValue().toString()) / 100);
    }

    public void showBandwidthWarning(String warning) {
        textWarningArea.setText(warning);
    //JOptionPane.showMessageDialog(t, warning);
    }

    void captureScreenStart() {
        try {

            // System.err.println("captureScreenStart");

            JobDetail jobDetail = new JobDetail(ConnectionBean.quartzScreenJobName, Scheduler.DEFAULT_GROUP, ScreenJob.class);

            Trigger trigger = TriggerUtils.makeSecondlyTrigger(ConnectionBean.intervallSeconds);
            trigger.setStartTime(new Date());
            trigger.setName("myTrigger");

            sched.scheduleJob(jobDetail, trigger);

            startButton.setEnabled(false);
            stopButton.setEnabled(true);

        } catch (Exception err) {
            System.out.println("captureScreenStart Exception: ");
            System.err.println(err);
            textArea.setText("Exception: " + err);
        }
    }

    void captureScreenStop() {
        try {
            sched.deleteJob(ConnectionBean.quartzScreenJobName, Scheduler.DEFAULT_GROUP);
            startButton.setEnabled(true);
            stopButton.setEnabled(false);
        } catch (Exception err) {
            System.out.println("captureScreenStop Exception: ");
            System.err.println(err);
            textArea.setText("Exception: " + err);
        }
    }

    public StartScreen(String url, String SID, String room, String domain, String publicSID, String record) {

        //JOptionPane.showMessageDialog(t, "publicSID: "+publicSID);

        ConnectionBean.connectionURL = url;
        ConnectionBean.SID = SID;
        ConnectionBean.room = room;
        ConnectionBean.domain = domain;
        ConnectionBean.publicSID = publicSID;
        ConnectionBean.record = record;
        instance = this;
    //instance.showBandwidthWarning("StartScreen: "+SID+" "+room+" "+domain+" "+url);
    //this.initMainFrame();
    }

    public static void main(String[] args) {
        String url = args[0];
        String SID = args[1];
        String room = args[2];
        String domain = args[3];
        String publicSID = args[4];
        String record = args[5];
        new StartScreen(url, SID, room, domain, publicSID, record);
    }
}
