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
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.classroom.whiteboard.item.Item;

import avoir.realtime.common.BuilderSlide;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.Constants;


import avoir.realtime.common.Pointer;
import avoir.realtime.common.packet.XmlQuestionPacket;
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
import java.awt.event.InputEvent;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.geom.RoundRectangle2D;
import java.util.ArrayList;
import java.util.Hashtable;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.InputMap;
import javax.swing.JComponent;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.JTextField;
import javax.swing.JViewport;
import javax.swing.KeyStroke;

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
    private RoundRectangle2D.Double pointerSurface = new RoundRectangle2D.Double();
    private boolean drawingEnabled = true;
    private Vector<Item> items = new Vector<Item>();
    private JTextField textField = new JTextField();
    private Rectangle ovalRect1;
    private Rectangle ovalRect2;
    private Rectangle ovalRect3;
    private Rectangle ovalRect4;
    private Graphics2D graphics;
    private String customSlideText = "";
    //public JComboBox fontSizeField = new JComboBox();
    //public JComboBox fontNamesField = new JComboBox();
    //public JToggleButton boldButton = new JToggleButton();
    //public JToggleButton italicButton = new JToggleButton();
    //public JToggleButton underButton = new JToggleButton();
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
    int angle = 40;
    private Image introLogo = ImageUtil.createImageIcon(this, "/icons/intro_logo.jpg").getImage();
    protected boolean showLogo = true;
    private ArrayList<BuilderSlide> slides;
    // The LineBreakMeasurer used to line-break the paragraph.
    private LineBreakMeasurer lineMeasurer;
    // index of the first character in the paragraph.
    private int paragraphStart;
    // index of the first character after the end of the paragraph.
    private int paragraphEnd;
    private static final Hashtable<TextAttribute, Object> map =
            new Hashtable<TextAttribute, Object>();

    static {
        map.put(TextAttribute.FAMILY, "Serif");
        map.put(TextAttribute.SIZE, new Float(18.0));
    }
    private Color textColor = Color.BLACK;
    int textsize = 18;

    /** Creates new form WhiteboardSurface */
    public WhiteboardSurface(ClassroomMainFrame mf) {
        // Install input map on the root pane which watches Ctrl key pressed/released events
        final InputMap im = new InputMap() {

            public Object get(KeyStroke keyStroke) {
                boolean ctrlDown = false;
                // For key pressed "events" this needs to complete in case
                // automatic key repeat is switched on in the operating system.
                if (keyStroke.getKeyEventType() != KeyEvent.KEY_TYPED) { // key pressed or released
                    JOptionPane.showMessageDialog(null, "release");
                    boolean oldCtrlDown = ctrlDown;
                    ctrlDown = (keyStroke.getModifiers() & InputEvent.CTRL_DOWN_MASK) != 0;
                    if (oldCtrlDown && !ctrlDown) // Ctrl key released
                    {
                        JOptionPane.showMessageDialog(null, "release");
                    }
                }
                return super.get(keyStroke);
            }
        };


        im.setParent(getInputMap(
                JComponent.WHEN_ANCESTOR_OF_FOCUSED_COMPONENT));
        setInputMap(
                JComponent.WHEN_ANCESTOR_OF_FOCUSED_COMPONENT, im);


        initComponents();
        this.mf = mf;
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        whiteboardManager = new WhiteboardUtil(mf, this);

        pointerSurface = new RoundRectangle2D.Double(0, 0, slideWidth, slideHeight, angle, angle);
        //addKeyListener(this);
        //setFocusable(true);
        //addKeyListener(this);


    }

    public ImageIcon getImage() {
        return image;
    }

    public void setSlides(ArrayList<BuilderSlide> slides) {
        this.slides = slides;
        setContent(0);
    }

    public void setContent(int index) {
        BuilderSlide xslide = slides.get(index);
        XmlQuestionPacket question = xslide.getQuestion();
        //titleField.setText(slide.getTitle());
        //textField.setText(xslide.getText());
        /*if (question != null) {
        questionField.setText(question.getQuestionPath());
        }*/
        setCustomSlideText(xslide.getText());
        textColor=xslide.getTextColor();
        textsize=xslide.getTextSize();
        setCurrentSlide(xslide.getImage(), slideIndex, slides.size(), true);
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
        showLogo = false;
        if (item instanceof Img) {
            items.add(0, item);

        } else {
            items.addElement(item);
        }
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

    public String getCustomSlideText() {
        return customSlideText;
    }

    public void setCustomSlideText(String customSlideText) {
        this.customSlideText = customSlideText;
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
                pointerSurface = new RoundRectangle2D.Double(0, 0, slideWidth, slideHeight, angle, angle);

            }

        }
        graphics = g2;

        if (XOR) {
            g2.setXORMode(getBackground());
        }
        int xx = (getWidth() - (int) pointerSurface.getWidth()) / 2;
        int yy = (getHeight() - (int) pointerSurface.getHeight()) / 2;
        if (showLogo) {
            g2.drawImage(introLogo, xx + 50, yy + 50, (int) (pointerSurface.width * 0.8), (int) (pointerSurface.height * 0.8), this);
        }


        //  g2.drawRect(xx, yy, (int)pointerSurface.getWidth(), (int)pointerSurface);
        pointerSurface = new RoundRectangle2D.Double(xx, yy, slideWidth, slideHeight, angle, angle);
        g2.setColor(Color.GRAY);//new Color(255, 153, 51));
        g2.setStroke(new BasicStroke(4f));
        g2.draw(pointerSurface);
        paintSlides(g2);
        g2.setStroke(new BasicStroke());
        g2.setColor(Color.BLACK);


        if (image != null) {
            graphics.drawImage(image.getImage(), 100, 100, this);
        }
        whiteboardManager.paintCurrentItem(g2, selectedItem, imgs);
        whiteboardManager.paintItems(g2, items, dashed, dragging, selectedItem, pointerSurface, imgs, ovalRect1, ovalRect2, ovalRect3, ovalRect4);
        drawCustomText(g2);
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
            /*          graphics.drawImage(currentPointer.getIcon().getImage(),
            (int) pointerSurface.getX() + currentPointer.getPoint().x - 10,
            (int) pointerSurface.getY() + currentPointer.getPoint().y - 10, this);
             */
            graphics.drawImage(currentPointer.getIcon().getImage(),
                    currentPointer.getPoint().x - 10,
                    currentPointer.getPoint().y - 10, this);

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

    private void drawCustomText(Graphics2D g2) {
        if (customSlideText.trim().equals("")) {
            return;
        }
        String[] lines = customSlideText.split("\n");
        int xx = 100;
        int yy = 100;
        g2.setColor(textColor);
        g2.setFont(new Font("SansSerif", 0, textsize));
        for (int i = 0; i < lines.length; i++) {

            g2.drawString(lines[i], xx, yy);
            yy += 30;
        }
        /*
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
        float drawPosX = layout.isLeftToRight()
        ? 100 : breakWidth - layout.getAdvance();

        // Move y-coordinate by the ascent of the layout.
        drawPosY += layout.getAscent();

        // Draw the TextLayout at (drawPosX, drawPosY).
        layout.draw(g2, drawPosX, drawPosY);

        // Move y-coordinate in preparation for next layout.
        drawPosY += layout.getDescent() + layout.getLeading();
        }*/
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
            g2.drawImage(slide.getImage(), 50, 50, (int) (slideWidth), (int) (slideHeight), this);

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
