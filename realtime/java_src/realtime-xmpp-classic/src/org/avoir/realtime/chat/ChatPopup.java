/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.chat;


import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.awt.geom.RoundRectangle2D;
import java.text.AttributedCharacterIterator;
import java.text.AttributedString;
import javax.swing.BorderFactory;
import javax.swing.JPanel;
import javax.swing.JWindow;

/**
 *
 * @author developer
 */
public class ChatPopup extends JWindow {

    private String message;
    private String sender;
    private int paragraphEnd;
    private LineBreakMeasurer lineMeasurer;
    private int paragraphStart;
    private int yValue = 40;
    private int xValue = 10;
    private int windowHeight = 10;
    private MPanel mpanel = new MPanel();
    private boolean showHeader = true;

    public ChatPopup() {
        setSize(100, windowHeight);

        setLayout(new BorderLayout());
        setAlwaysOnTop(true);
        mpanel.setBorder(BorderFactory.createEtchedBorder());
        add(mpanel, BorderLayout.CENTER);
        
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(final String xsender, final String xmessage, final boolean xshowHeader) {
        Thread t = new Thread() {

            public void run() {
                message = xmessage;
                sender = xsender;
                windowHeight = 10;
                yValue = 0;
                showHeader = xshowHeader;
                mpanel.repaint();
                try {
                    sleep(5000);
                } catch (Exception ex) {
                }
                setVisible(false);
            }
        };
        t.start();
    }

    class MPanel extends JPanel {

        @Override
        protected void paintComponent(Graphics g) {
            super.paintComponent(g);
            Graphics2D g2 = (Graphics2D) g;
            g2.setColor(Color.WHITE);//new Color(255, 255, 0, 40));
            g2.fillRect(3, 1, getWidth(), windowHeight + 20);
            yValue = 55;
            //g2.setBackground(Color.WHITE);
            g2.setFont(new Font("dialog", 1, 11));
            g2.setColor(Color.RED);
            RoundRectangle2D rect = new RoundRectangle2D.Double(5, 5, 190, 20, 8, 8);
            g2.setColor(Color.WHITE);//new Color(255, 255, 0, 100));
            g2.fill(rect);
            g2.setColor(Color.BLACK);
            g2.draw(rect);
            g2.drawString("Chisimba Realtime:", 10, 20);

            g2.setColor(Color.BLACK);
            g2.setColor(new Color(0, 131, 0));
            if (showHeader) {
                g2.drawString(sender + " says:", 10, 40);
            }
            //g2.drawLine(10, 15, 180, 15);
            g2.setColor(Color.BLACK);
            windowHeight = 45;
            if (message != null) {
                if (message.trim().length() > 0) {
                    AttributedString mas = new AttributedString(message);
                    mas.addAttribute(TextAttribute.FONT, new Font("dialog", 0, 10));

                    drawText(g2, mas);
                }
            }

            g2.setColor(Color.BLACK);
            // g2.drawRect(3, 1, 180, windowHeight +20);

        }

        /**
         * draws multiple lines of text
         * @param g2d
         * @param mText
         */
        private void drawText(Graphics2D g2d, AttributedString mText) {
            // Create a new LineBreakMeasurer from the paragraph.
            AttributedCharacterIterator paragraph = mText.getIterator();
            paragraphStart = paragraph.getBeginIndex();
            paragraphEnd = paragraph.getEndIndex();
            FontRenderContext frc = g2d.getFontRenderContext();
            lineMeasurer = new LineBreakMeasurer(paragraph, frc);
            // Set break width
            float breakWidth = (float) (getSize().width * .8);
            float drawPosY = 0;
            // Set position to the index of the first character in the paragraph.
            lineMeasurer.setPosition(paragraphStart);
            int lineNo = 1;
            // Get lines until the entire paragraph has been displayed.
            while (lineMeasurer.getPosition() < paragraphEnd) {

                // Retrieve next layout. A cleverer program would also cache
                // these layouts until the component is re-sized.
                TextLayout layout = lineMeasurer.nextLayout(breakWidth);
                // Compute pen x position. If the paragraph is right-to-left we
                // will align the TextLayouts to the right edge of the panel.
                // Note: this won't occur for the English text in this sample.
                // Note: drawPosX is always where the LEFT of the text is placed.
                if (layout != null) {
                    float drawPosX = layout.isLeftToRight()
                            ? xValue : breakWidth - layout.getAdvance();

                    // Move y-coordinate by the ascent of the layout.
                    drawPosY += layout.getAscent();

                    // Draw the TextLayout at (drawPosX, drawPosY).

                    layout.draw(g2d, drawPosX, yValue);//drawPosY);

                    // Move y-coordinate in preparation for next layout.
                    drawPosY += layout.getDescent() + layout.getLeading();
                    yValue += 20;
                    windowHeight += 20;
                }
            }
            ChatPopup.this.setSize(200, windowHeight + 20);
        }
    }
}
