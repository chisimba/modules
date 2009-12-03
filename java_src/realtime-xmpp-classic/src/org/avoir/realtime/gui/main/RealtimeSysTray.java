/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import org.avoir.realtime.gui.userlist.ParticipantListPanel;

import java.awt.AWTException;
import java.awt.Image;
import java.awt.MenuItem;
import java.awt.PopupMenu;
import java.awt.SystemTray;
import java.awt.Toolkit;
import java.awt.TrayIcon;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import org.avoir.realtime.common.util.ImageUtil;

/**
 *
 * @author kim
 */ //
public class RealtimeSysTray {

    private static TrayIcon trayIcon = null;
    private ImageIcon alertimage = ImageUtil.createImageIcon(this, "/images/icons_header.jpg");
    private ImageIcon image = ImageUtil.createImageIcon(this, "/images/post-it-notes.gif");


    public void init(final ParticipantListPanel userListPanel) {

        //final  TrayIcon trayIcon;
        final TrayIcon trayIcon2 = null;


        if (SystemTray.isSupported()) {


            SystemTray tray = SystemTray.getSystemTray();
            


            MouseListener mouseListener = new MouseListener() {

                public void mouseClicked(MouseEvent e) {
                    System.out.println("Tray Icon - Mouse clicked!");
                }

                public void mouseEntered(MouseEvent e) {
                    System.out.println("Tray Icon - Mouse entered!");
                }

                public void mouseExited(MouseEvent e) {
                    System.out.println("Tray Icon - Mouse exited!");
                }

                public void mousePressed(MouseEvent e) {
                    System.out.println("Tray Icon - Mouse pressed!");
                }

                public void mouseReleased(MouseEvent e) {
                    System.out.println("Tray Icon - Mouse released!");
                }
            };

            ActionListener exitListener = new ActionListener() {

                public void actionPerformed(ActionEvent e) {
                    System.exit(0);
                }
            };

            ActionListener audioListener = new ActionListener() {

                public void actionPerformed(ActionEvent e) {
                    //audioVideoTest.setLocationRelativeTo(null);
                    //audioVideoTest.setVisible(true);
                    userListPanel.getUserListTabbedPane().setSelectedIndex(1);

                }
            };


            ActionListener executeListener = new ActionListener() {

                public void actionPerformed(ActionEvent e) {
                    JOptionPane.showMessageDialog(null, "Realtime system, version 1.0, In this system ",
                            "User action", JOptionPane.INFORMATION_MESSAGE);

                }
            };


            PopupMenu popup = new PopupMenu();

            MenuItem execItem = new MenuItem("Help");
            execItem.addActionListener(executeListener);
            popup.add(execItem);

            MenuItem audioitem = new MenuItem("Audio");
            audioitem.addActionListener(audioListener);
            popup.add(audioitem);

            MenuItem exititem = new MenuItem("Exit");
            exititem.addActionListener(exitListener);
            popup.add(exititem);

            //PopupMenu menu = new PopupMenu();


            trayIcon = new TrayIcon(image.getImage(), "RealTime Tools", popup);

            ActionListener actionListener = new ActionListener() {

                public void actionPerformed(ActionEvent e) {
                    trayIcon.displayMessage("Action Event",
                            "An Action Event Has Been Performed!",
                            TrayIcon.MessageType.INFO);
                }
            };



            trayIcon.setImageAutoSize(true);
            trayIcon.addActionListener(actionListener);
            trayIcon.addMouseListener(mouseListener);

            ActionListener updateListener = new ActionListener() {

                public void actionPerformed(ActionEvent e) {
                    trayIcon.displayMessage("Action Event",
                            "An Action Event Has Been Performed!",
                            TrayIcon.MessageType.INFO);
                }
            };


            try {
                tray.add(trayIcon);
            } catch (AWTException e) {
                System.err.println("TrayIcon could not be added.");
            }

        } else {
            //  System Tray is not supported
        }

    }

    public void updateTrayIcon() {
        if (trayIcon != null) {
            trayIcon.setImage(alertimage.getImage());
        }
    }
    public void revertTrayIcon(){
        if (trayIcon != null) {
            trayIcon.setImage(image.getImage());
        }
    }
}
