/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.common.ImageUtil;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.packet.PointerPacket;

import avoir.realtime.tcp.whiteboard.WhiteboardSurface;
import avoir.realtime.tcp.whiteboard.item.Item;
import java.awt.*;
import javax.swing.*;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
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
    private ImageIcon image;
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
    private Vector<Item> pointerLocations = new Vector<Item>();
    private int pointer = Constants.NO_POINTER;
    private Pointer currentPointer = new Pointer(new Point(0, 0), blankIcon);
    private Graphics2D graphics;
    private Rectangle pointerSurface = new Rectangle();
    private static final Cursor SELECT_CURSOR = new Cursor(Cursor.DEFAULT_CURSOR),  DRAW_CURSOR = new Cursor(Cursor.CROSSHAIR_CURSOR);
    // private PointerSurface pointerSurface = new PointerSurface();
    private boolean firstSlide = true;
    int currentX, currentY;
    private boolean drawSelection = false;
    private Vector<ImageIcon> imgs = new Vector<ImageIcon>();

    public Surface(RealtimeBase xbase) {
        this.base = xbase;
        setLayout(new BorderLayout());
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        setFocusable(true);
        this.requestFocus();
        addMouseListener(this);
        addMouseMotionListener(this);
        addKeyListener(this);
        //setBounds(100, 100, 300, 300);

        //this.base.getGlassPaneHandler().setGlassPane(pointerSurface);
        // pointerSurface.setVisible(true);
        setCursor(SELECT_CURSOR);
    }

    public void addItem(Item item) {
        pointerLocations.addElement(item);


    }

    public void setImage(ImageIcon image) {
        imgs.add(image);
        repaint();
    }

    public void setTotalSlideCount(int totalSlideCount) {

        this.totalSlideCount = totalSlideCount;
    }

    public void replaceItem(int index, Item item) {
        if (pointerLocations.size() > index) {
            pointerLocations.set(index, item);
        } else {
            pointerLocations.addElement(item);
        }
        currentPointer = null;

    }

    public void removeItem(int index) {
        pointerLocations.remove(index);
        repaint();
    }

    public void clearItems() {
        pointerLocations.clear();
        repaint();
    }

    public void actionPerformed(ActionEvent arg0) {
    }

    public void keyPressed(KeyEvent evt) {
        if (evt.getKeyCode() == KeyEvent.VK_DELETE) {
            base.getWhiteboardSurface().deleteSelectedItem();
        }
    }

    public void keyReleased(KeyEvent arg0) {
    }

    public void keyTyped(KeyEvent evt) {
    }

    public void mouseDragged(MouseEvent evt) {
        if (base.getControl()) {
            Point xpoint = new Point(evt.getX() - 10, evt.getY() - 10);
            currentX = evt.getX();
            currentY = evt.getY();
            //   if (pointerSurface.contains(xpoint)) {
            drawSelection = true;
            int xOffset = evt.getX() - pointerSurface.x;
            int yOffset = evt.getY() - pointerSurface.y;
            Point point = new Point(xOffset, yOffset);
            switch (pointer) {

                case Constants.HAND_LEFT: {

                    drawSelection = false;
                    setCurrentPointer(Constants.HAND_LEFT, point);
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_LEFT));

                    break;
                }
                case Constants.HAND_RIGHT: {
                    drawSelection = false;
                    setCurrentPointer(Constants.HAND_RIGHT, point);
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_RIGHT));

                    break;
                }
                case Constants.ARROW_UP: {
                    drawSelection = false;
                    setCurrentPointer(Constants.ARROW_UP, point);
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_UP));

                    break;
                }
                case Constants.ARROW_SIDE: {
                    drawSelection = false;
                    setCurrentPointer(Constants.ARROW_SIDE, point);
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_SIDE));

                    break;
                }
                case Constants.WHITEBOARD: {
                    drawSelection = false;
                    if (base.getWhiteboardSurface().getBrush() == WhiteboardSurface.BRUSH_SELECT) {
                        drawSelection = true;
                    }
                    base.getWhiteboardSurface().setGraphics(graphics);
                    base.getWhiteboardSurface().processMouseDragged(evt);

                    repaint();
                    break;
                }
                //  }
            }

        } else {
            base.showMessage("You dont have privileges to modify the whiteboard", true, true, MessageCode.ALL);

        }
        if (base.getWhiteboardSurface().getBrush() == WhiteboardSurface.BRUSH_MOVE &&
                pointer == Constants.NO_POINTER) {
            drawSelection = true;
        }
        repaint();

    }

    private void paintSelection(Graphics2D g2) {
        if (drawSelection && base.getWhiteboardSurface().getSelectedItem() == null) {
            Rectangle rect = new Rectangle(startX, startY, currentX - startX, currentY - startY);
            base.getWhiteboardSurface().setCurrentSelectionArea(rect);
            g2.draw(rect);
        }
    }

    public boolean isDrawSelection() {
        return drawSelection;
    }

    public void setDrawSelection(boolean drawSelection) {
        this.drawSelection = drawSelection;
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
            default:
                currentPointer.setIcon(blankIcon);
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
        pointerLocations.clear();
        setCurrentPointer(pointer);
    }

    private void setOwnCursor() {
        Image img = blankIcon.getImage();
        Cursor curCircle = Toolkit.getDefaultToolkit().createCustomCursor(img, new Point(5, 5), "circle");
        setCursor(curCircle);


    }

    public void mousePressed(MouseEvent evt) {
        if (base.getControl()) {
            drawSelection = false;
            Point xpoint = new Point(evt.getX() - 10, evt.getY() - 10);

            //  if (pointerSurface.contains(xpoint)) {
            startX = evt.getX();
            startY = evt.getY();
            int xOffset = evt.getX() - (int) pointerSurface.getX();
            int yOffset = evt.getY() - (int) pointerSurface.getY();
            Point point = new Point(xOffset, yOffset);
            switch (pointer) {
                case Constants.PAINT_BRUSH: {
                    prevX = startX = evt.getX();
                    prevY = startY = evt.getY();

                    break;
                }
                case Constants.HAND_LEFT: {
                    setOwnCursor();
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_LEFT));

                    break;
                }
                case Constants.HAND_RIGHT: {
                    setOwnCursor();
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.HAND_RIGHT));

                    break;
                }
                case Constants.ARROW_UP: {
                    setOwnCursor();
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_UP));

                    break;
                }
                case Constants.ARROW_SIDE: {
                    setOwnCursor();
                    base.getTcpClient().sendPacket(new PointerPacket(base.getSessionId(), point, Constants.ARROW_SIDE));

                    break;
                }
                case Constants.WHITEBOARD: {
                    base.getWhiteboardSurface().setGraphics(graphics);
                    base.getWhiteboardSurface().processMousePressed(evt);
                    repaint();
                    break;
                }


            }
        } else {
            base.showMessage("You dont have privileges to modify the whiteboard", true, true, MessageCode.ALL);

        }
        repaint();
    }

    public void mouseReleased(MouseEvent evt) {
        drawSelection = false;
        if (pointer == Constants.WHITEBOARD) {
            base.getWhiteboardSurface().setGraphics(graphics);
            base.getWhiteboardSurface().processMouseReleased(evt);

        }
        setCursor(SELECT_CURSOR);
        repaint();
    }

    public String getMessage() {
        return infoMessage;
    }

    public void showMessage(String msg, boolean isErrorMessage) {
        this.infoMessage = msg;
        this.isErrorMsg = isErrorMessage;
        this.showInfoMessage = msg.trim().length() > 0 ? true : false;
        repaint();
    }

    public void setCurrentSlide(ImageIcon slide, int slideIndex, int totalSlideCount, boolean fromPresenter) {
        this.slide = slide;
        pointerLocations.clear();
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

    public Rectangle getPointerSurface() {
        return pointerSurface;
    }

    private void paintSlides(Graphics2D g2) {
        if (slide != null) {
            int slideWidth = slide.getIconWidth();
            int slideHeight = slide.getIconHeight();
            if (slideWidth >= getWidth()) {
                slideWidth = (int) (slideWidth * 0.9);
            }

            // if (slideWidth >= getHeight()) {
            //   slideHeight = (int) (slideHeight * 0.9);
            // }

            int xx = (getWidth() - slide.getIconWidth()) / 2;
            int yy = (getHeight() - slide.getIconHeight()) / 2;
            g2.setFont(new Font("Dialog", 1, 10));
            g2.drawString(base.getSelectedFile() + " - " + (base.getSessionManager().getSlideIndex() + 1) + " of " + totalSlideCount, xx, yy - 15);

            g2.drawImage(slide.getImage(), xx, yy, slideWidth, slideHeight, this);
            //if (firstSlide) {
            Rectangle rect = new Rectangle(xx - 5, yy - 5, slideWidth + 10, slideHeight + 10);
            pointerSurface = new Rectangle(xx, yy, slideWidth, slideHeight);
            g2.draw(rect);
            firstSlide = false;
        //}

        /*FontMetrics fm = graphics.getFontMetrics(msgFont);
        String msg = "Presenter Slide " + presenterSlideIndex + " of " + totalSlideCount;
        graphics.setColor(Color.white);
        graphics.fillRect(getWidth() - 130, 15, fm.stringWidth(msg) + 20, fm.getHeight()*2 + 15);
        graphics.setColor(Color.BLACK);
        graphics.drawRect(getWidth() - 130, 15, fm.stringWidth(msg) + 20, fm.getHeight()*2 + 15);
        
        if (presenterSlideIndex > 0) {
        g2.setColor(Color.BLACK);
        g2.setFont(msgFont);
        g2.drawString(msg, getWidth() - 130, 30);
        }
        if (slideIndex > 0) {
        msg = "Own Slide " + slideIndex + " of " + totalSlideCount;
        g2.setColor(Color.ORANGE);
        g2.setFont(msgFont);
        g2.drawString(msg, getWidth() - 130, 50);
        }*/
        }
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        graphics = (Graphics2D) g;
        paintSlides(graphics);
        if (image != null) {
            graphics.drawImage(image.getImage(), 100, 100, this);
        }
        if (showInfoMessage) {
            FontMetrics fm = graphics.getFontMetrics(msgFont);
            graphics.setColor(Color.white);
            graphics.fillRect(5, 65, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            graphics.setColor(Color.BLACK);
            graphics.drawRect(5, 65, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            graphics.setFont(msgFont);

            if (isErrorMsg) {
                graphics.drawImage(warnIcon.getImage(), 10, 70, this);
            }
            graphics.drawString(infoMessage, 30, 80);
        }
        graphics.setColor(Color.BLACK);
        base.getWhiteboardSurface().drawStroke(graphics);
        base.getWhiteboardSurface().repaint();
        if (currentPointer != null) {
            graphics.drawImage(currentPointer.getIcon().getImage(),
                    (int) pointerSurface.x + currentPointer.getPoint().x - 10,
                    (int) pointerSurface.y + currentPointer.getPoint().y - 10, this);
        }
        paintSelection(graphics);
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
