/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Point;
import java.awt.Window;
import java.awt.event.MouseEvent;
import java.awt.event.MouseMotionAdapter;
import java.awt.event.MouseMotionListener;
import java.awt.image.BufferedImage;
import javax.swing.JComponent;
import javax.swing.JLayeredPane;
import javax.swing.RootPaneContainer;
import javax.swing.SwingUtilities;

public class Magnifier extends JComponent {

    private static final int SIDE =128;
    private Point point;
    private BufferedImage image = new BufferedImage(SIDE, SIDE, BufferedImage.TYPE_INT_RGB);
    private MouseMotionListener l = new MouseMotionAdapter() {

        @Override
        public void mouseMoved(MouseEvent e) {
            point = e.getPoint();
            repaint();
        }
    };

    public Magnifier() {
        addMouseMotionListener(l);
    }

    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);
        Window w = SwingUtilities.getWindowAncestor(this);
        if (point != null && w instanceof RootPaneContainer) {
            RootPaneContainer rpc = (RootPaneContainer) w;
            JLayeredPane lp = rpc.getLayeredPane();
            Graphics2D g2 = image.createGraphics();
            g2.scale(2, 2);
            g2.translate(-point.x - SIDE / 4, -point.y + SIDE / 4);
            lp.paint(g2);
            g2.dispose();
            g.drawImage(image, point.x, point.y - SIDE / 2, null);
            g.setColor(Color.BLUE);
            g.drawRect(point.x, point.y - SIDE / 2, SIDE - 1, SIDE - 1);
        }
    }
}