/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

import avoir.realtime.common.RealtimeOptions;
import java.awt.*;
import javax.swing.*;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.text.AttributedCharacterIterator;
import java.text.AttributedString;
import java.awt.event.*;
import avoir.realtime.tcp.client.applet.ImageUtil;
import javax.swing.ImageIcon;
import java.awt.Color;

/**
 *
 * @author developer
 */
public class Surface extends JPanel {

    private String title = "Realtime Presentations";
    private String connectionTitle = "Connection mode: ";
    private String connectingStr = "Connecting...This might take some few minutes, please wait...";
    private int paragraphEnd;
    private LineBreakMeasurer lineMeasurer;
    private int paragraphStart;
    private int yValue = 100;
    private int xValue = 100;
    private TCPTunnellingApplet applet;
    private Rectangle connectButton = new Rectangle(100, 100, 81, 27);
    private Rectangle optionsButton = new Rectangle(210, 100, 81, 27);
    private boolean connectButtonPressed = false;
    private boolean optionsButtonPressed = false;
    private boolean connecting = false;
    private ImageIcon slide;
    private String statusMessage = " ";
    private boolean showStatusMessage = false;
    private boolean showInfoMessage = false;
    private String infoMessage = "";
    private ImageIcon warnIcon = ImageUtil.createImageIcon(this, "/icons/warn.png");
    private Font msgFont = new Font("Dialog", 1, 10);
    private boolean isErrorMsg = false;
    private int slideIndex = 0;
    private int presenterSlideIndex = 0;
    private int totalSlideCount = 0;
    private boolean fromPresenter = false;
    private boolean showConnectingString = false;
    private boolean showSplashScreen = true;

    public Surface(TCPTunnellingApplet xapplet) {
        this.applet = xapplet;
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        final Cursor c = this.getCursor();
        this.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                if (connectButton.contains(e.getX(), e.getY())) {
                    setConnecting(true);
                    applet.connect();

                }
                if (optionsButton.contains(e.getX(), e.getY())) {

                    applet.showOptionsFrame(0);
                }
            }

            @Override
            public void mousePressed(MouseEvent e) {
                //   connectButtonPressed = connectButton.contains(e.getX(), e.getY());
                //   optionsButtonPressed = optionsButton.contains(e.getX(), e.getY());

                repaint();
            }

            @Override
            public void mouseReleased(MouseEvent e) {
                connectButtonPressed = false;
                optionsButtonPressed = false;

                repaint();
            }

            @Override
            public void mouseEntered(MouseEvent e) {

                if (connectButton.contains(e.getX(), e.getY()) || optionsButton.contains(e.getX(), e.getY())) {
                    connectButtonPressed = true;
                    optionsButtonPressed = true;
                }
                repaint();

            }

            @Override
            public void mouseExited(MouseEvent e) {
                // connectButtonPressed = false;
                //optionsButtonPressed = false;
                repaint();

            }
        });


    }

    public String getMessage() {
        return infoMessage;
    }

    public void showMessage(String msg, boolean isErrorMessage) {
        this.infoMessage = msg;
        this.isErrorMsg = isErrorMessage;
        this.showInfoMessage = true;
        repaint();
    }

    public void setCurrentSlide(ImageIcon slide, int slideIndex, int totalSlideCount, boolean fromPresenter) {
        this.slide = slide;
        this.totalSlideCount = totalSlideCount;
        this.showStatusMessage = false;
        if (fromPresenter) {
            presenterSlideIndex = slideIndex;
            this.slideIndex = slideIndex;
        } else {
            this.slideIndex = slideIndex;
        }
        this.fromPresenter = fromPresenter;
        repaint();
    }

    public void setConnecting(boolean connect) {
        this.connecting = connect;
        repaint();
    }

    public void setShowSplashScreen(boolean show) {
        showSplashScreen = show;
    }

    public void setConnectingString(String msg) {
        connectingStr = msg;
        showConnectingString = true;
        repaint();
    }

    public void setStatusMessage(String msg) {
        //slide = null;
        this.statusMessage = msg;
        this.showStatusMessage = true;
        repaint();
    }

    private void showStatusMessage(Graphics2D g2) {
        Font font = new Font("Dialog", Font.BOLD, 12);
        if (statusMessage.length() > 0) {
            AttributedString as = new AttributedString(statusMessage);
            as.addAttribute(TextAttribute.FONT, font);
            yValue = 100;
            xValue = (int) (getWidth() * .2);
            g2.setColor(new Color(0, 131, 0));
            drawText(g2, as);
            yValue += 40;
        }
    }

    private void showSplashScreen(Graphics2D g2) {
        Font font = new Font("Dialog", Font.BOLD, 18);
        AttributedString as = new AttributedString(title);
        as.addAttribute(TextAttribute.FONT, font);
        yValue = 80;
        xValue = (int) (getWidth() * .2);
        drawText(g2, as);
        yValue += 40;

        /*
        font = new Font("Dialog", Font.BOLD, 12);
        as = new AttributedString(connectionTitle + RealtimeOptions.getConnectionMode());
        as.addAttribute(TextAttribute.FONT, font);
        drawText(g2, as);*/
        yValue += 20;

        if (showConnectingString) {
            font = new Font("Dialog", Font.BOLD, 10);
            as = new AttributedString(connectingStr);
            as.addAttribute(TextAttribute.FONT, font);
            as.addAttribute(TextAttribute.FOREGROUND, Color.RED);
            drawText(g2, as);
            yValue += 30;
        }
        font = new Font("Dialog", Font.BOLD, 10);
        as = new AttributedString(statusMessage);
        as.addAttribute(TextAttribute.FONT, font);
        as.addAttribute(TextAttribute.FOREGROUND, Color.RED);
        drawText(g2, as);
        yValue += 10;

        connectButton.setLocation(xValue, yValue);


        if (!connectButtonPressed) {
            g2.setColor(Color.LIGHT_GRAY);
            g2.fillRoundRect((int) connectButton.getX(), (int) connectButton.getY(), (int) connectButton.getWidth(), (int) connectButton.getHeight(), 5, 5);
        } else {
            g2.setColor(Color.DARK_GRAY);
            g2.fillRoundRect((int) connectButton.getX(), (int) connectButton.getY(), (int) connectButton.getWidth(), (int) connectButton.getHeight(), 5, 5);
        }

        FontMetrics fm = g2.getFontMetrics();

        String connectStr = "Connect";
        int xx = ((int) connectButton.getWidth() - fm.stringWidth(connectStr)) / 2;
        int yy = ((int) connectButton.getHeight() - fm.getHeight()) / 2;
        g2.setColor(Color.WHITE);
        g2.drawString(connectStr, xValue + xx, yValue + (int) connectButton.getHeight() - yy);


        optionsButton.setLocation(xValue + 110, yValue);
        if (!optionsButtonPressed) {
            g2.setColor(Color.LIGHT_GRAY);

            g2.fillRoundRect((int) optionsButton.getX(), (int) optionsButton.getY(), (int) optionsButton.getWidth(), (int) optionsButton.getHeight(), 5, 5);
        } else {
            g2.setColor(Color.BLACK);

            g2.fillRoundRect((int) optionsButton.getX(), (int) optionsButton.getY(), (int) optionsButton.getWidth(), (int) optionsButton.getHeight(), 5, 5);
        }

        String optionsStr = "Options";
        xx = ((int) optionsButton.getWidth() - fm.stringWidth(optionsStr)) / 2;
        yy = ((int) optionsButton.getHeight() - fm.getHeight()) / 2;
        g2.setColor(Color.WHITE);
        g2.drawString(optionsStr, xValue + 100 + xx, yValue + (int) optionsButton.getHeight() - yy);
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        if (slide != null) {
            int xx = (getWidth() - slide.getIconWidth()) / 2;
            int yy = (getHeight() - slide.getIconHeight()) / 2;
            g2.drawImage(slide.getImage(), xx, yy, this);
            g2.drawRect(xx - 5, yy - 5, slide.getIconWidth() + 10, slide.getIconHeight() + 10);
            if (presenterSlideIndex > 0) {
                g2.setColor(Color.BLACK);
                g2.setFont(msgFont);
                g2.drawString("Presenter Slide " + presenterSlideIndex + " of " + totalSlideCount, getWidth() - 130, 30);
            }
            if (slideIndex > 0) {

                g2.setColor(Color.ORANGE);
                g2.setFont(msgFont);
                g2.drawString("Own Slide " + slideIndex + " of " + totalSlideCount, getWidth() - 130, 50);
            }
        }
        if (showStatusMessage) {
            showStatusMessage(g2);
        }

        if (showSplashScreen) {
            showSplashScreen(g2);
        }


        if (showInfoMessage) {
            FontMetrics fm = g2.getFontMetrics(msgFont);
            g2.setColor(Color.white);
            g2.fillRect(5, 15, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            g2.setColor(Color.BLACK);
            g2.drawRect(5, 15, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            g2.setFont(msgFont);
            if (isErrorMsg) {
                g2.drawImage(warnIcon.getImage(), 10, 20, this);
            }
            g2.drawString(infoMessage, 30, 30);
        }
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
            }
        }
    }
}
