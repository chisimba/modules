/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.packet.PointerPacket;
import avoir.realtime.tcp.whiteboard.Whiteboard;
import avoir.realtime.tcp.whiteboard.item.Item;
import java.awt.*;
import javax.swing.*;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.text.AttributedCharacterIterator;
import java.text.AttributedString;
import java.awt.event.*;

import javax.swing.ImageIcon;
import java.awt.Color;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class Surface extends JPanel implements MouseListener,
        MouseMotionListener,
        KeyListener,
        ActionListener {

    private String title = "Realtime Presentations";
    private String connectingStr = "Connecting...This might take some few minutes, please wait...";
    private int paragraphEnd;
    private LineBreakMeasurer lineMeasurer;
    private int paragraphStart;
    private int yValue = 100;
    private int xValue = 100;
    private RealtimeBase base;
    private boolean connecting = false;
    private ImageIcon slide;
    private String statusMessage = " ";
    private boolean showStatusMessage = false;
    private boolean showInfoMessage = false;
    private String infoMessage = "";
    private ImageIcon warnIcon = ImageUtil.createImageIcon(this, "/icons/warn.png");
    private ImageIcon leftHand = ImageUtil.createImageIcon(this, "/icons/hand_left.png");
    private ImageIcon rightHand = ImageUtil.createImageIcon(this, "/icons/hand_right.png");
    private ImageIcon arrowUp = ImageUtil.createImageIcon(this, "/icons/arrow_up.png");
    private ImageIcon arrowSide = ImageUtil.createImageIcon(this, "/icons/arrow_side.png");
    private ImageIcon blankIcon = ImageUtil.createImageIcon(this, "/icons/transparent.png");
    private ImageIcon paintBrush = ImageUtil.createImageIcon(this, "/icons/paintbrush.png");
    private Font msgFont = new Font("Dialog", 1, 10);
    private boolean isErrorMsg = false;
    private int slideIndex = 0;
    private int presenterSlideIndex = 0;
    private int totalSlideCount = 0;
    private boolean fromPresenter = false;
    private boolean showConnectingString = false;
    private boolean showSplashScreen = true;
    private Vector<Item> whiteboardItems = new Vector<Item>();
    private Whiteboard whiteboard;
    private int pointer = Constants.HAND_RIGHT;
    private Pointer currentPointer = new Pointer(new Point(0, 0), rightHand);
    private Graphics2D graphics;
    private Rectangle pointerSurface = new Rectangle();
    private static final Cursor SELECT_CURSOR = new Cursor(Cursor.DEFAULT_CURSOR),  DRAW_CURSOR = new Cursor(Cursor.CROSSHAIR_CURSOR);

    public Surface(RealtimeBase xbase) {
        this.base = xbase;
        whiteboard = new Whiteboard(base);
        setLayout(new BorderLayout());
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        addMouseListener(this);
        addMouseMotionListener(this);
        addKeyListener(this);

        setCursor(DRAW_CURSOR);
    }

    public void addItem(Item item) {
        whiteboardItems.addElement(item);
        //currentPointer = null;
        paintPointer(graphics);
    //repaint();
    }

    public void replaceItem(int index, Item item) {
        if (whiteboardItems.size() > index) {
            whiteboardItems.set(index, item);
        } else {
            whiteboardItems.addElement(item);
        }
        currentPointer = null;
        paintPointer(graphics);
    }

    public void removeItem(int index) {
        whiteboardItems.remove(index);
        repaint();
    }

    public void clearItems() {
        whiteboardItems.clear();
        repaint();
    }

    public void actionPerformed(ActionEvent arg0) {
    }

    public void keyPressed(KeyEvent evt) {
        if (evt.getKeyCode() == KeyEvent.VK_ESCAPE) {
        }
    }

    public void keyReleased(KeyEvent arg0) {
    }

    public void keyTyped(KeyEvent evt) {
    }

    public void mouseDragged(MouseEvent evt) {
        Point xpoint = new Point(evt.getX() - 10, evt.getY() - 10);

        if (pointerSurface.contains(xpoint)) {
            int xOffset = evt.getX() - pointerSurface.x;
            int yOffset = evt.getY() - pointerSurface.y;
            Point point = new Point(xOffset, yOffset);
            switch (pointer) {
                case Constants.PAINT_BRUSH: {
                    whiteboard.addDot(evt.getX(), evt.getY(), whiteboardItems.size());
                    break;
                }
                case Constants.HAND_LEFT: {

                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_LEFT));

                    break;
                }
                case Constants.HAND_RIGHT: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_RIGHT));

                    break;
                }
                case Constants.ARROW_UP: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_UP));

                    break;
                }
                case Constants.ARROW_SIDE: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_SIDE));

                    break;
                }
            }
        }

        whiteboard.setPrevXY(evt.getX(), evt.getY());
        //repaint();
        paintPointer(graphics);
    }

    public void mouseMoved(MouseEvent evt) {
    }

    public void mouseClicked(MouseEvent arg0) {
    }

    public void mouseEntered(MouseEvent arg0) {
    }

    public void mouseExited(MouseEvent arg0) {
    }
    Vector<Rectangle> freeHand = new Vector<Rectangle>();
    int startX = 0;
    int startY = 0;
    int prevX = 0;
    int prevY = 0;

    private void setCurrentPointer(int type) {

        switch (type) {
            case Constants.HAND_LEFT: {
                currentPointer.setIcon(leftHand);
                break;
            }
            case Constants.HAND_RIGHT: {

                currentPointer.setIcon(rightHand);
                break;
            }
            case Constants.ARROW_UP: {
                currentPointer.setIcon(arrowUp);
                break;
            }
            case Constants.ARROW_SIDE: {
                currentPointer.setIcon(arrowSide);
                break;
            }
            case Constants.PAINT_BRUSH: {
                currentPointer.setIcon(paintBrush);
                break;
            }
        }
        repaint();
    }

    public void setCurrentPointer(int type, Point point) {
        switch (type) {
            case Constants.HAND_LEFT: {
                currentPointer = new Pointer(point, leftHand);
                break;
            }
            case Constants.HAND_RIGHT: {
                currentPointer = new Pointer(point, rightHand);
                break;
            }
            case Constants.ARROW_UP: {
                currentPointer = new Pointer(point, arrowUp);
                break;
            }
            case Constants.ARROW_SIDE: {
                currentPointer = new Pointer(point, arrowSide);
                break;
            }
            case Constants.PAINT_BRUSH: {
                currentPointer = new Pointer(point, paintBrush);
                break;
            }
        }
        repaint();
    }

    public void setPointer(int type) {
        pointer = type;
        whiteboardItems.clear();
        setCurrentPointer(pointer);
    }

    private void setOwnCursor() {
        int curWidth = 32;
        int curHeight = 32;
        int y = 0;
        int x = 0;
        int pix[] = new int[curWidth * curHeight];
        for (y = 0; y <= curHeight; y++) {
            for (x = 0; x <= curWidth; x++) {
                pix[y + x] = 0; // all points transparent
            // black circle - outside
            }
        }
        int curCol = Color.WHITE.getRGB();
        int yscale = 10;
        int xscale = 10;
        for (x = 2; x <= 8; x++) {
            pix[x] = curCol; // up
        }
        for (x = 2; x <= 8; x++) {
            pix[(yscale * curWidth) + x] = curCol; // bottom			
        }
        for (y = 2; y <= 8; y++) {
            pix[curWidth * y] = curCol; // left
        }
        for (y = 2; y <= 8; y++) {
            pix[(curWidth * y) + yscale] = curCol; // right
        }
        pix[1 + curWidth] = curCol;
        pix[yscale + curWidth - 1] = curCol;
        pix[1 + (curWidth * (yscale - 1))] = curCol;
        pix[(curWidth * (yscale - 1)) + yscale - 1] = curCol;

        // white circle - inside
        curCol = Color.white.getRGB();
        yscale = yscale - 1;
        xscale = xscale - 1;
        for (x = 3; x <= 7; x++) {
            pix[x + curWidth] = curCol; // up
        }
        for (x = 3; x <= 7; x++) {
            pix[(yscale * curWidth) + x] = curCol; // bottom			
        }
        for (y = 3; y <= 7; y++) {
            pix[curWidth * y + 1] = curCol; // left
        }
        for (y = 3; y <= 7; y++) {
            pix[(curWidth * y) + yscale] = curCol; // right
        }
        pix[2 + curWidth + curWidth] = curCol;
        pix[yscale + curWidth + curWidth - 1] = curCol;
        pix[1 + (curWidth * (yscale - 1)) + 1] = curCol;
        pix[(curWidth * (yscale - 1)) + yscale - 1] = curCol;

        Image img =blankIcon.getImage();// createImage(new MemoryImageSource(curWidth, curHeight, pix, 0, curWidth));
        Cursor curCircle = Toolkit.getDefaultToolkit().createCustomCursor(img, new Point(5, 5), "circle");
        setCursor(curCircle);


    }

    public void mousePressed(MouseEvent evt) {
        setOwnCursor();
        Point xpoint = new Point(evt.getX() - 10, evt.getY() - 10);

        if (pointerSurface.contains(xpoint)) {
            int xOffset = evt.getX() - pointerSurface.x;
            int yOffset = evt.getY() - pointerSurface.y;
            Point point = new Point(xOffset, yOffset);
            switch (pointer) {
                case Constants.PAINT_BRUSH: {
                    prevX = startX = evt.getX();
                    prevY = startY = evt.getY();
                    whiteboard.drawPenStart(evt.getX(), evt.getY(), whiteboardItems.size());
                    break;
                }
                case Constants.HAND_LEFT: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_LEFT));

                    break;
                }
                case Constants.HAND_RIGHT: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_RIGHT));

                    break;
                }
                case Constants.ARROW_UP: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_UP));

                    break;
                }
                case Constants.ARROW_SIDE: {
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_SIDE));

                    break;
                }


            }
        }
        repaint();
    }

    public void mouseReleased(MouseEvent evt) {

        switch (whiteboard.getBRUSH()) {
            case Whiteboard.BRUSH_PEN: {
                //whiteboard.drawPenStop(evt.getX(), evt.getY(), whiteboardItems.size());
                break;
            }
        }
        setCursor(DRAW_CURSOR);
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
        whiteboardItems.clear();
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

    private void paintPointer(Graphics2D g) {
        if (currentPointer != null) {
            g.drawImage(currentPointer.getIcon().getImage(), pointerSurface.x + currentPointer.getPoint().x - 10, pointerSurface.y + currentPointer.getPoint().y - 10, this);
        }
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
        FontMetrics fm = g2.getFontMetrics();
    }

    private void paintSlides(Graphics2D g2) {
        if (slide != null) {
            int xx = (getWidth() - slide.getIconWidth()) / 2;
            int yy = (getHeight() - slide.getIconHeight()) / 2;
            int slideWidth = slide.getIconWidth();
            int slideHeight = slide.getIconHeight();
            if (slideWidth >= getWidth()) {
                slideWidth = (int) (slideWidth * 0.9);
            }

            g2.drawImage(slide.getImage(), xx, yy, this);
            pointerSurface = new Rectangle(xx - 5, yy - 5, slideWidth + 10, slideHeight + 10);
            g2.draw(pointerSurface);
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
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        graphics = (Graphics2D) g;
        paintSlides(graphics);
        drawWhiteboardItems(graphics);
        paintPointer(graphics);

        graphics.setStroke(new BasicStroke(1));
        if (showStatusMessage) {
            showStatusMessage(graphics);
        }

        if (showSplashScreen) {
            showSplashScreen(graphics);
        }


        if (showInfoMessage) {
            FontMetrics fm = graphics.getFontMetrics(msgFont);
            graphics.setColor(Color.white);
            graphics.fillRect(5, 15, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            graphics.setColor(Color.BLACK);
            graphics.drawRect(5, 15, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            graphics.setFont(msgFont);
            if (isErrorMsg) {
                graphics.drawImage(warnIcon.getImage(), 10, 20, this);
            }
            graphics.drawString(infoMessage, 30, 30);
        }
    }

    private void drawWhiteboardItems(Graphics2D g) {

        for (int i = 0; i < whiteboardItems.size(); i++) {
            whiteboardItems.elementAt(i).paint(g);
        }

    }

    class Pointer {

        Point point;
        ImageIcon icon;

        public Pointer(Point point, ImageIcon icon) {
            this.point = point;
            this.icon = icon;
        }

        public ImageIcon getIcon() {
            return icon;
        }

        public void setIcon(ImageIcon icon) {
            this.icon = icon;
        }

        public Point getPoint() {
            return point;
        }

        public void setPoint(Point point) {
            this.point = point;
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
