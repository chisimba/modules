/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.net;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Graphics;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author david
 */
public class EC2Manager {

    private static JFrame fr = new JFrame("Launching on EC2 Server ...");
    private static JLabel infoLabel = new JLabel("Please wait ...");
    private static JProgressBar pb = new JProgressBar();
    private static Timer connectionTimer = new Timer();
    private static ConnectionTimer timerTask = new ConnectionTimer();

    public static void showStatus(String info) {
        infoLabel.setText(info);
    }

    public static void dispose() {
        timerTask.cancel();
        fr.dispose();
    }

    public static void updateConnectionStatus(int val) {
        if (val == 0) {
            pb.setIndeterminate(false);
            pb.setMinimum(0);
            pb.setMaximum(100);
        }
        pb.setValue(val);
    }

    static class ConnectionTimer extends TimerTask {

        public void run() {
            JOptionPane.showMessageDialog(null, "Error connecting to server");
            System.exit(0);
        }
    }

    public static void requestLaunchEC2Instance() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.LAUNCH_EC2);
        StringBuilder sb = new StringBuilder();
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);


    }

    public static EC2Manager getInstance() {
        return new EC2Manager();
    }

    public EC2Manager() {
    }

    public static void showLoginProgress() {

        Surface panel = new Surface();
        panel.setLayout(new BorderLayout());
        infoLabel.setForeground(Color.WHITE);
        panel.add(infoLabel, BorderLayout.NORTH);

        pb.setIndeterminate(true);
        panel.add(pb, BorderLayout.SOUTH);
        fr.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        fr.setContentPane(panel);
        fr.setSize(400, 290);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
        connectionTimer.cancel();
        connectionTimer = new Timer();
        connectionTimer.schedule(timerTask, 4 * 60 * 1000);
    }

    static class Surface extends JPanel {

        ImageIcon logo = ImageUtil.createImageIcon(getInstance(), "/images/logo.png");

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            g.drawImage(logo.getImage(), 0, 0, this);
        }
    }
}
