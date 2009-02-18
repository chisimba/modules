/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.classroom;

import avoir.realtime.common.ImageUtil;
import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Font;
import java.awt.GradientPaint;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Rectangle;
import java.awt.RenderingHints;
import java.awt.image.BufferedImage;
import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public class InfoPanel extends JPanel {

    private String centerMessage = "Quick Info";
    private Image exicon = ImageUtil.createImageIcon(this, "/icons/exclamation.png").getImage();
    private Image icon = exicon;// ImageUtil.createImageIcon(this, "/icons/warn.png").getImage();
    private Image warnicon = ImageUtil.createImageIcon(this, "/icons/warn.png").getImage();
    private Image infoicon = ImageUtil.createImageIcon(this, "/icons/info.png").getImage();
    private Image helpicon = ImageUtil.createImageIcon(this, "/icons/help.png").getImage();
    private Image audioicon = ImageUtil.createImageIcon(this, "/icons/audio.png").getImage();

    public InfoPanel() {
        setBackground(Color.WHITE);
    }

    public Image getScaledImage(Image srcImg, int w, int h) {

        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public void setCenterMessage(String msg) {
        centerMessage = msg;
        repaint();
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        g2.setPaint(new GradientPaint(0, 0, Color.LIGHT_GRAY, getWidth(),
                getHeight(), Color.WHITE, false));


        Rectangle r = new Rectangle(0, 0, getWidth(), getHeight());
        g2.fill(r);

        g2.setColor(Color.GRAY);
        g2.setStroke(new BasicStroke(3));
        g2.drawRoundRect(5, 5, getWidth() - 10, getHeight() - 10, 10, 10);
        g2.setFont(new Font("Dialog", 1, 16));
        g2.setColor(new Color(112, 106, 184));//new Color(255, 153, 51));
        g2.drawImage(infoicon, 20, 50, this);
        g2.drawImage(warnicon, 40, 50, this);
        g2.drawImage(exicon, 60, 50, this);
        g2.drawImage(audioicon, 80, 50, this);
        g2.drawImage(helpicon, 100, 50, this);
        g2.drawString(centerMessage, 20, 40);
       
    }
}
