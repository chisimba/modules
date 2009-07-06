/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.chat;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.util.Vector;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JTextArea;

public class IconsSurface extends JPanel implements MouseListener, MouseMotionListener {

    Vector<ImageCell> images = new Vector<ImageCell>();
    int startX, startY;
    int imageStartX = 10;
    int imageStartY = 10;
    int dx = 0;
    int dy = 0;
    int colMonitor = 0;
    int cols = 3;
    int rows = 6;
    ImageCell selectedImage = null;
    int selectedIndex = 0;
    int counter = 0;
    private JTextArea chatIn;
    private Emot[] emots;
    private JPopupMenu popup;

    public IconsSurface(Emot[] emots, JTextArea xchatIn, JPopupMenu popup) {
        int x = 10;
        int y = 10;
        int c = 0;
        this.chatIn = xchatIn;
        this.popup = popup;
        this.emots = emots;
        for (int i = 0; i < emots.length; i++) {
            if (c > cols) {
                y += 30;
                x = 10;
                c = 0;
            }
            c++;
            images.add(new ImageCell(x, y, 16, 16, emots[i].getIcon().getImage(), emots[i].getDesc()));
            x += 30;
        }
        addMouseListener(this);
        addMouseMotionListener(this);
        setPreferredSize(new Dimension(150, 80));
        setBackground(Color.WHITE);
    }

    public void mouseClicked(MouseEvent e) {
        // System.out.println("mouse clicked");
        }

    public void mouseEntered(MouseEvent e) {
        //System.out.println("Entered");
        }

    public void mouseExited(MouseEvent e) {
        //  System.out.println("Exited");
        }

    public void mousePressed(MouseEvent e) {
        startX = e.getX();//starting point when mouse is pressed
        startY = e.getY();
        for (int i = 0; i < images.size(); i++) {
            ImageCell img = images.elementAt(i);
            //check if we have clicked inside this image
            if (img.contains(e.getPoint())) {
                selectedImage = img;
                chatIn.append(emots[i].getSymbol());

                dx = e.getX() - selectedImage.getX();
                dy = e.getY() - selectedImage.getY();
                selectedIndex = i;
                popup.setVisible(false);
                chatIn.requestFocus();
                repaint();
            }
        }
    }

    public void mouseReleased(MouseEvent e) {
    }

    public void mouseDragged(MouseEvent e) {
    }

    public void mouseMoved(MouseEvent e) {
        selectedImage = null;
        for (int i = 0; i < images.size(); i++) {
            ImageCell img = images.elementAt(i);
            //check if we have clicked inside this image
            if (img.contains(e.getPoint())) {
                selectedImage = img;
                dx = e.getX() - selectedImage.getX();
                dy = e.getY() - selectedImage.getY();
                selectedIndex = i;
                repaint();
            }
        }
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        for (int i = 0; i < images.size(); i++) {
            ImageCell img = images.elementAt(i);

            g.drawImage(img.getImg(), img.getX(), img.getY(), img.getW(), img.getH(), this);
            g2.setColor(Color.LIGHT_GRAY);

            g2.drawRect(img.getX() - 5, img.getY() - 5, img.getW() + 10, img.getH() + 10);
        }
        if (selectedImage != null) {
            g2.setColor(Color.BLACK);
            g2.drawRect(selectedImage.getX() - 5, selectedImage.getY() - 5, selectedImage.getW() + 10, selectedImage.getH() + 10);
        }

    }
}

class ImageCell {

    private int x,  y,  w,  h;
    private Image img;
    private String desc;

    public ImageCell(
            int x,
            int y,
            int w,
            int h,
            Image img) {
        this.x = x;


        this.y = y;
        this.w = w;
        this.h = h;
        this.img = img;
    }

    public ImageCell(int x, int y, int w, int h, Image img, String desc) {
        this.x = x;
        this.y = y;
        this.w = w;
        this.h = h;
        this.img = img;
        this.desc = desc;
    }

    public boolean contains(Point p) {
        Rectangle rect = new Rectangle(x, y, w, h);
        return rect.contains(p);
    }

    public void fillRect() {
    }

    public int getH() {
        return h;
    }

    public void setH(int h) {
        this.h = h;
    }

    public Image getImg() {
        return img;
    }

    public void setImg(Image img) {
        this.img = img;
    }

    public int getW() {
        return w;
    }

    public void setW(int w) {
        this.w = w;
    }

    public int getX() {
        return x;
    }

    public void setX(int x) {
        this.x = x;
    }

    public int getY() {
        return y;
    }

    public void setY(int y) {
        this.y = y;
    }

    public String getDesc() {
        return desc;
    }

    public void setDesc(String desc) {
        this.desc = desc;
    }
}


