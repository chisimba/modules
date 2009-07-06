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
package org.avoir.realtime.slidebuilder;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Rectangle;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.text.AttributedCharacterIterator;
import java.text.AttributedString;
import java.util.Hashtable;
import javax.swing.ImageIcon;
import javax.swing.JPanel;

public class Surface extends JPanel implements MouseListener, MouseMotionListener {

    int startX, startY, currentX, currentY;
    // The LineBreakMeasurer used to line-break the paragraph.
    private LineBreakMeasurer lineMeasurer;
    // index of the first character in the paragraph.
    private int paragraphStart;
    // index of the first character after the end of the paragraph.
    private int paragraphEnd;
    private final Hashtable<TextAttribute, Object> map =
            new Hashtable<TextAttribute, Object>();
    private String customSlideText;
    int textXPos = 100;
    int textYPos = 100;
    int offSetX = 0;
    int offSetY = 0;
    private Graphics2D graphics;
    private boolean dragText = false;
    private ImageIcon slideImage;
    private SlideBuilderManager sbm;

    public Surface(SlideBuilderManager sbm) {
        this.sbm = sbm;
        setBackground(Color.WHITE);
        map.put(TextAttribute.FAMILY, "Serif");
        map.put(TextAttribute.SIZE, new Float(18.0));
        addMouseListener(this);
        addMouseMotionListener(this);
    }

    public String getCustomSlideText() {
        return customSlideText;
    }

    public void setCustomSlideText(String customSlideText) {
        this.customSlideText = customSlideText;
    }

    public void setSlideImage(ImageIcon slideImage) {
        this.slideImage = slideImage;
        repaint();
    }

    public void mouseDragged(MouseEvent evt) {
        if (dragText) {
            textXPos = evt.getX() - offSetX;
            textYPos = evt.getY() - offSetY;
            repaint();
        }
    }

    public void mouseMoved(MouseEvent e) {
    }

    public void mouseClicked(MouseEvent e) {
    }

    public void mouseEntered(MouseEvent e) {
    }

    public void mouseExited(MouseEvent e) {
    }

    public void mousePressed(MouseEvent evt) {
        startX = evt.getX();
        startY = evt.getY();
        if (customSlideText == null) {
            return;
        }
        if (customSlideText.trim().equals("")) {
            return;
        }
        if (graphics != null) {
            FontMetrics fm = graphics.getFontMetrics();
            int totalLines = customSlideText.split("\n").length;
            Rectangle rect = new Rectangle(textXPos, textYPos - fm.getHeight(),
                    fm.stringWidth(customSlideText), fm.getAscent() * (totalLines + 1));

            if (rect.contains(evt.getPoint())) {
                dragText = true;
                offSetX = evt.getX() - textXPos;
                offSetY = evt.getY() - textYPos;
            } else {
                dragText = false;
            }

        }
        repaint();
    }

    public void mouseReleased(MouseEvent e) {
        dragText = false;
        repaint();
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        graphics = g2;
        if (slideImage != null) {
            g2.drawImage(slideImage.getImage(), 50, 50, this);
            setPreferredSize(new Dimension(slideImage.getIconWidth(), slideImage.getIconHeight()));
        }
        if (customSlideText != null) {
            FontMetrics fm = graphics.getFontMetrics(new Font("Dialog", 0, sbm.getTextSize()));
            if (!customSlideText.trim().equals("")) {
                // String lines[] = customSlideText.split("\n");
                int yy = textYPos;
                int longest = fm.stringWidth(customSlideText);
                g2.setColor(new Color(255, 0, 0, 100));
                g2.fillRoundRect(90, 100 - fm.getHeight(),
                        longest + 10, (fm.getHeight()) + 10, 5, 5);
                g2.setColor(Color.BLACK);
                g2.drawRoundRect(90, 100 - fm.getHeight(),
                        longest + 10, (fm.getHeight()) + 10, 5, 5);

                g2.setFont(new Font("Dialog", 0, sbm.getTextSize()));
                g2.setColor(Color.BLACK);
                g2.setColor(Color.BLACK);
                g2.drawString(customSlideText, 100, 100);
                /*
                for (int i = 0; i < lines.length; i++) {
                g2.drawString(lines[i], textXPos, yy);
                if (fm.stringWidth(lines[i]) > longest) {
                longest = fm.stringWidth(lines[i]);
                }
                yy += fm.getHeight();
                }
                 */
                //drawCustomText(g2);
                if (dragText) {

                    //   g2.drawRect(textXPos, textYPos - fm.getHeight(),
//                            longest, (fm.getHeight() * (lines.length + 1)) + 10);
                }
            }
        }
        revalidate();

    }

    private void drawCustomText(Graphics2D g2) {
        if (customSlideText.trim().equals("")) {
            return;
        }

        // Create a new LineBreakMeasurer from the paragraph.
        // It will be cached and re-used.
        if (lineMeasurer == null) {
            AttributedString str = new AttributedString(customSlideText);
            AttributedCharacterIterator paragraph = str.getIterator();
            paragraphStart = paragraph.getBeginIndex();
            paragraphEnd = paragraph.getEndIndex();
            FontRenderContext frc = g2.getFontRenderContext();
            lineMeasurer = new LineBreakMeasurer(paragraph, frc);
        }

        // Set break width to width of Component.
        float breakWidth = (float) getSize().width;
        float drawPosY = textYPos;
        lineMeasurer.setPosition(paragraphStart);

        // Get lines until the entire paragraph has been displayed.
        while (lineMeasurer.getPosition() < paragraphEnd) {
            TextLayout layout = lineMeasurer.nextLayout(breakWidth);

            float drawPosX = layout.isLeftToRight()
                    ? textXPos : breakWidth - layout.getAdvance();
            drawPosY += layout.getAscent();
            layout.draw(g2, drawPosX, drawPosY);
            drawPosY += layout.getDescent() + layout.getLeading();
        }
    }
}
