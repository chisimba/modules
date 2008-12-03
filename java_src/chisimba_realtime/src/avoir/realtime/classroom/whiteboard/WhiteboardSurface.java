/*
 * WhiteboardSurface.java
 * Although primarily this is the whiteboard surface, it also acts as a presentation
 * surface
 * 
 * Created on 17 June 2008, 02:56
 */
package avoir.realtime.classroom.whiteboard;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.classroom.tcp.TCPConnector;
import avoir.realtime.classroom.whiteboard.item.Item;

import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.Constants;


import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JComboBox;
import javax.swing.JScrollPane;
import javax.swing.JTextField;
import javax.swing.JToggleButton;
import javax.swing.JViewport;

/**
 *
 * @author  David Wafula
 */
public class WhiteboardSurface extends javax.swing.JPanel implements MouseListener,
        MouseMotionListener,
        KeyListener {

    public boolean XOR = false;
    private boolean dragging;
    private int startX; //mouse cursor position on draw start
    private int startY; //mouse cursor position on draw start
    private Color colour = new Color(255, 255, 0);//, alpha);
    private Item selectedItem;
    public boolean liveDrag = false;
    private final float[] dash1 = {1.0f};
    private final BasicStroke dashed = new BasicStroke(1.0f,
            BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 1.0f, dash1, 0.0f);
    private float strokeWidth = 5;
    private ClassroomMainFrame mf;
    private Rectangle pointerSurface = new Rectangle();
    private boolean drawingEnabled = true;
    private Vector<Item> items = new Vector<Item>();
    private JTextField textField = new JTextField();
    private Rectangle ovalRect1;
    private Rectangle ovalRect2;
    private Rectangle ovalRect3;
    private Rectangle ovalRect4;
    private Graphics2D graphics;
    public JComboBox fontSizeField = new JComboBox();
    public JComboBox fontNamesField = new JComboBox();
    public JToggleButton boldButton,  italicButton,  underButton;
    private Vector<Item> pointerLocations = new Vector<Item>();
    private int pointer = Constants.WHITEBOARD;
    protected ImageIcon warnIcon = ImageUtil.createImageIcon(this, "/icons/warn.png");
    protected ImageIcon leftHand = ImageUtil.createImageIcon(this, "/icons/hand_left.png");
    protected ImageIcon rightHand = ImageUtil.createImageIcon(this, "/icons/hand_right.png");
    protected ImageIcon arrowUp = ImageUtil.createImageIcon(this, "/icons/arrow_up.png");
    protected ImageIcon arrowSide = ImageUtil.createImageIcon(this, "/icons/arrow_side.png");
    protected ImageIcon blankIcon = ImageUtil.createImageIcon(this, "/icons/transparent.png");
    protected Pointer currentPointer = new Pointer(new Point(0, 0), blankIcon);
    protected boolean showStatusMessage = false;
    protected boolean showInfoMessage = false;
    protected String infoMessage = "";
    protected boolean isErrorMsg = false;
    protected Font msgFont = new Font("Dialog", 1, 10);
    protected ImageIcon slide;
    protected int slideIndex = 0;
    protected boolean fromPresenter = false;
    boolean firstSlide = false;
    protected int totalSlideCount;
    protected Rectangle currentSelectionArea = new Rectangle();
    int currentX, currentY;
    protected ImageIcon image;
    protected Vector<ImageIcon> imgs = new Vector<ImageIcon>();
    private WhiteboardUtil whiteboardManager;
    protected double magX = 1;
    protected double magY = 1;
    protected boolean gotSize = false;
    int slideWidth, slideHeight;

    /** Creates new form WhiteboardSurface */
    public WhiteboardSurface(ClassroomMainFrame mf) {
        initComponents();
        this.mf = mf;
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        whiteboardManager = new WhiteboardUtil(mf, this);

        pointerSurface = new Rectangle(0, 0, slideWidth, slideHeight);


    }

    public ImageIcon getImage() {
        return image;
    }

    public void setImage(ImageIcon image) {
        currentSelectionArea = new Rectangle();
        imgs.add(image);
        repaint();
    }

    public void setSelectedItem(Item selectedItem) {
        this.selectedItem = selectedItem;
        repaint();
    }

    public Rectangle getCurrentSelectionArea() {
        return currentSelectionArea;
    }

    public void setCurrentSelectionArea(Rectangle currentSelectionArea) {
        this.currentSelectionArea = currentSelectionArea;
    }

    public void setTCPConnector(TCPConnector tcpConnector) {
        //tcpConnector.setWhiteboardSurfaceHandler(this);
    }

    /**
     * Adds a new item
     * @param item
     */
    public void addItem(Item item) {

        items.addElement(item);
        repaint();
    }

    public Vector<Item> getItems() {
        return items;
    }

    public void replaceItem(Item item) {
        for (int i = 0; i < items.size(); i++) {
            Item xt = items.elementAt(i);
            if (xt != null) {
                if (xt.getId().equals(item.getId())) {
                    items.set(i, item);
                }
            }
        }
        repaint();
    }

    public void removeItem(Item item) {
        for (int i = 0; i < items.size(); i++) {
            Item xt = items.elementAt(i);
            if (xt.getId().equals(item.getId())) {
                items.remove(i);
            }
        }
        selectedItem = null;
        repaint();

    }

    public void clearItems() {
        items.clear();

        repaint();

    }

    public boolean isDrawingEnabled() {
        return drawingEnabled;
    }

    public void setDrawingEnabled(boolean drawingEnabled) {
        this.drawingEnabled = drawingEnabled;
    }

    /**
     * get current width of stroke used for painting
     * @return
     */
    public float getStrokeWidth() {
        return strokeWidth;
    }

    /**
     * Set the current width for the stroke used for painting
     * @param strokeWidth
     */
    public void setStrokeWidth(float strokeWidth) {
        this.strokeWidth = strokeWidth;
    }

    public void setItems(Vector<Item> items) {
        this.items = items;
        repaint();

    }

    public void mouseClicked(MouseEvent evt) {
    }

    public void mouseEntered(MouseEvent evt) {
    }

    public void mouseExited(MouseEvent evt) {
    }

    public void showMessage(String msg, boolean isErrorMessage) {
        this.infoMessage = msg;
        this.isErrorMsg = isErrorMessage;
        this.showInfoMessage = msg.trim().length() > 0 ? true : false;
        repaint();
    }

    public void mousePressed(MouseEvent evt) {
    }

    public void mouseReleased(MouseEvent evt) {
    }

    protected void centerViewPort() {
        JScrollPane scrollPane = mf.getSurfaceScrollPane();
        JViewport vport = scrollPane.getViewport();

        Dimension size = vport.getExtentSize();
        int xx = (getWidth() - size.width) / 2;
        int yy = (getHeight() - size.height) / 2;
        Rectangle rect = new Rectangle(xx, yy, size.width, size.height);
        scrollRectToVisible(rect);
    }

    protected void setOwnCursor() {
        Image img = blankIcon.getImage();
        Cursor curCircle = Toolkit.getDefaultToolkit().createCustomCursor(img, new Point(5, 5), "circle");
        setCursor(curCircle);
    }

    public WhiteboardUtil getWhiteboardManager() {
        return whiteboardManager;
    }

    public void setWhiteboardManager(WhiteboardUtil whiteboardManager) {
        this.whiteboardManager = whiteboardManager;
    }

    public void mouseDragged(MouseEvent evt) {
    }

    public Vector<ImageIcon> getImgs() {
        return imgs;
    }

    protected void scrollOnDrag(MouseEvent evt) {
        JScrollPane scrollPane = mf.getSurfaceScrollPane();
        JViewport vport = scrollPane.getViewport();
        Point viewPos = vport.getViewPosition();
        Dimension size = vport.getExtentSize();
        int vportx = viewPos.x;
        int vporty = viewPos.y;
        int dx = evt.getX() - startX;
        int dy = evt.getY() - startY;

        int newvportx = vportx - dx;
        int newvporty = vporty - dy;
        Rectangle rect = new Rectangle(newvportx, newvporty, size.width, size.height);
        scrollRectToVisible(rect);
    }

    public void processMouseMoved(MouseEvent evt) {
    }

    public void mouseMoved(MouseEvent evt) {
    }

    public void keyPressed(KeyEvent evt) {
    }

    public void keyReleased(KeyEvent evt) {
    }

    public void keyTyped(KeyEvent evt) {
    }

    public void clearWhiteboard() {
        clearItems();
        selectedItem = null;

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

        }
        repaint();
    }

    public void setPointer(int type) {
        pointer = type;
        pointerLocations.clear();
        setCurrentPointer(pointer);
    }

    public int getPointer() {
        return pointer;
    }

    protected void setCurrentPointer(int type) {

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

            default:
                currentPointer.setIcon(blankIcon);
        }
        repaint();
    }

    public Color getColour() {
        return colour;
    }

    public void setColour(Color colour) {
        this.colour = colour;
    }

    public void setGraphics(Graphics2D graphics) {
        this.graphics = graphics;
    }

    public double getMagX() {
        return magX;
    }

    public void setMagX(double magX) {
        this.magX = magX;
        centerViewPort();
        resized = true;
    }

    public double getMagY() {
        return magY;

    }

    public void setMagY(double magY) {
        this.magY = magY;
        centerViewPort();
        resized = true;
    }
    boolean resized = false;
    double prevXMag = magX;
    double prevYMag = magY;

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        g2.scale(magX, magY);
        if (!gotSize) {
            slideWidth = (int) (this.getSize().getWidth() * 0.8);
            slideHeight = (int) (this.getSize().getHeight() * 0.8);
            if (slideHeight > 0 && slideHeight > 0) {
                gotSize = false;
                pointerSurface = new Rectangle(0, 0, slideWidth, slideHeight);

            }

        }
        graphics = g2;

        if (XOR) {
            g2.setXORMode(getBackground());
        }
        int xx = (getWidth() - pointerSurface.width) / 2;
        int yy = (getHeight() - pointerSurface.height) / 2;

        g2.drawRect(xx, yy, pointerSurface.width, pointerSurface.height);

        paintSlides(g2);

        if (image != null) {
            graphics.drawImage(image.getImage(), 100, 100, this);
        }
        whiteboardManager.paintCurrentItem(g2, selectedItem, imgs);
        whiteboardManager.paintItems(g2, items, dashed, dragging, selectedItem, pointerSurface, imgs, ovalRect1, ovalRect2, ovalRect3, ovalRect4);

        if (showInfoMessage) {
            g2.setStroke(new BasicStroke());
            FontMetrics fm = graphics.getFontMetrics(msgFont);
            graphics.setColor(Color.white);
            graphics.fillRect(35, 65, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            graphics.setColor(Color.BLACK);
            graphics.drawRect(35, 65, fm.stringWidth(infoMessage) + 30, fm.getHeight() + 10);
            graphics.setFont(msgFont);

            if (isErrorMsg) {
                graphics.drawImage(warnIcon.getImage(), 10, 70, this);
            }
            graphics.drawString(infoMessage, 60, 80);
        }
        if (currentPointer != null) {
            graphics.drawImage(currentPointer.getIcon().getImage(),
                    (int) pointerSurface.x + currentPointer.getPoint().x - 10,
                    (int) pointerSurface.y + currentPointer.getPoint().y - 10, this);
        }

        g2.setStroke(new BasicStroke());

        if ((prevXMag < magX || prevYMag < magY) && resized) {
            int ww = getWidth();
            int hh = getHeight();
            int newW = ww + (int) magX * 100;
            int newH = hh + (int) magY * 100;
            prevXMag = magX;
            prevYMag = magY;
            setPreferredSize(new Dimension(newW, newH));
            revalidate();
            resized = false;
        }
        if ((prevXMag > magX || prevYMag > magY) && resized) {
            int ww = getWidth();
            int hh = getHeight();
            int newW = ww - (int) magX * 100;
            int newH = hh - (int) magY * 100;
            prevXMag = magX;
            prevYMag = magY;
            setPreferredSize(new Dimension(newW, newH));
            revalidate();
            resized = false;
        }


    }

    public double getPrevXMag() {
        return prevXMag;
    }

    public void setPrevXMag(double prevXMag) {
        this.prevXMag = prevXMag;
    }

    public double getPrevYMag() {
        return prevYMag;
    }

    public void setPrevYMag(double prevYMag) {
        this.prevYMag = prevYMag;

    }

    protected void paintSlides(Graphics2D g2) {
        if (slide != null) {
            int slideWidth = slide.getIconWidth();
            int slideHeight = slide.getIconHeight();
            if (slideWidth >= getWidth()) {
                slideWidth = (int) (slideWidth * 0.9);
            }


            int xx = ((int) (getWidth() * 1) - slide.getIconWidth()) / 2;
            int yy = ((int) (getHeight() * 1) - slide.getIconHeight()) / 2;
            g2.setFont(new Font("Dialog", 1, 10));
            g2.drawImage(slide.getImage(), xx, yy, (int) (slideWidth), (int) (slideHeight), this);

            firstSlide = false;
        }
    }

    public void setCurrentSlide(ImageIcon slide, int slideIndex, int totalSlideCount, boolean fromPresenter) {
        this.slide = slide;
        pointerLocations.clear();
        this.totalSlideCount = totalSlideCount;
        this.showStatusMessage = false;
        if (fromPresenter) {
            this.slideIndex = slideIndex;
        } else {
            this.slideIndex = slideIndex;
        }
        this.fromPresenter = fromPresenter;
        repaint();
    }

    public void setTextColor(Color color) {
        textField.setForeground(color);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        setLayout(new java.awt.BorderLayout());
    }// </editor-fold>//GEN-END:initComponents
    // Variables declaration - do not modify//GEN-BEGIN:variables
    // End of variables declaration//GEN-END:variables
}
