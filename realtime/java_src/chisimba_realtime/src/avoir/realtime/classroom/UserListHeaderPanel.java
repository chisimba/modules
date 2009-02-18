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
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Rectangle;
import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public class UserListHeaderPanel extends JPanel {

    private Image icon = ImageUtil.createImageIcon(this, "/icons/personal.png").getImage();
    private int count = 1;

    public UserListHeaderPanel() {
    }

    public void setCount(int count) {
        this.count = count;
        repaint();
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        g2.setColor(Color.WHITE);
        Rectangle r = new Rectangle(0, 0, getWidth(), getHeight());
        g2.fill(r);
        g2.drawImage(icon, 10, 10, this);
        g2.setColor(Color.GRAY);
        g2.setStroke(new BasicStroke(3));
        g2.drawRoundRect(5, 5, getWidth() - 10, getHeight() - 10, 10, 10);
        g2.setFont(new Font("Dialog", 1, 16));
        g2.setColor(new Color(112, 106, 184));//new Color(255, 153, 51));

        g2.drawString(count > 1 ? count + " Partcipants" : count + " Participant", 52, 30);

    }
}
