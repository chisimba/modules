/*
 * WhiteboardSurface.java
 *
 * Created on 17 June 2008, 02:56
 */
package avoir.realtime.tcp.whiteboard;

import avoir.realtime.tcp.base.ImageUtil;
import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.base.TCPClient;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.GenerateUUID;
import avoir.realtime.tcp.common.packet.WhiteboardPacket;
import avoir.realtime.tcp.whiteboard.item.FreeHand;
import avoir.realtime.tcp.whiteboard.item.Img;
import avoir.realtime.tcp.whiteboard.item.Item;
import avoir.realtime.tcp.whiteboard.item.Oval;
import avoir.realtime.tcp.whiteboard.item.Pen;
import avoir.realtime.tcp.whiteboard.item.Rect;
import avoir.realtime.tcp.whiteboard.item.Txt;
import avoir.realtime.tcp.whiteboard.item.WBLine;
import java.awt.AWTEvent;
import java.awt.AWTEventMulticaster;
import java.awt.AlphaComposite;
import java.awt.BasicStroke;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.Event;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.GraphicsEnvironment;
import java.awt.GridLayout;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.awt.geom.Line2D;
import java.text.AttributedString;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JTextField;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.PopupMenuEvent;

/**
 *
 * @author  developer
 */
public class WhiteboardSurface extends javax.swing.JPanel implements MouseListener,
        MouseMotionListener,
        KeyListener,
        ActionListener {

    public boolean XOR = false;
    private int alpha = 100;
    public static final int BRUSH_PEN = 0;
    public static final int BRUSH_RECT = 1;
    public static final int BRUSH_RECT_FILLED = 2;
    public static final int BRUSH_OVAL = 3;
    public static final int BRUSH_OVAL_FILLED = 4;
    public static final int BRUSH_LINE = 5;
    public static final int BRUSH_TEXT = 6;
    public static final int BRUSH_MOVE = 7;
    public static final int BRUSH_IMAGE = 8;
    private static final String[] brushName = new String[9];
    private static int brush = BRUSH_PEN;
    private static final String COMMAND_PIXEL = "pixel";
    private static final String COMMAND_XOR = "xor";
    private static final String COMMAND_LIVE = "live";
    private static final String COMMAND_ADD_IMAGE = "addimage";
    private boolean dragging;
    private int startX; //mouse cursor position on draw start
    private int startY; //mouse cursor position on draw start
    private int prevX; //last captured mouse co-ordinate pair
    private int prevY; //last captured mouse co-ordinate pair
    private Color colour = new Color(255, 255, 0, alpha);
    private Item selected;
    private Item selectedItem;
    private int selectedIndex = -1;
    public boolean liveDrag = false;
    private final float[] dash1 = {1.0f};
    private final BasicStroke dashed = new BasicStroke(1.0f,
            BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 1.0f, dash1, 0.0f);
    private Vector<WBLine> penVector = new Vector<WBLine>();
    private float strokeWidth = 5;
    private RealtimeBase base;
    private static final Cursor SELECT_CURSOR = new Cursor(Cursor.DEFAULT_CURSOR),  DRAW_CURSOR = new Cursor(Cursor.CROSSHAIR_CURSOR);
    private static final Color[] COLORS = {
        Color.black, Color.gray, Color.lightGray, Color.white, Color.red, Color.orange, Color.yellow,
        Color.green, Color.cyan, Color.blue, Color.magenta,
    };
    private ImageIcon penIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/pentool.gif");
    private ImageIcon moveIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/movearrow.gif");
    private ImageIcon rectNoFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/rectangle_nofill.gif");
    private ImageIcon rectFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/rectangle_fill.gif");
    private ImageIcon ovalNoFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/oval_nofill.gif");
    private ImageIcon ovalFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/oval_fill.gif");
    private ImageIcon textIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/text.gif");
    private ImageIcon lineIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/line.gif");
    private ImageIcon deleteIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/delete.gif");
    private ImageIcon clearIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/clear.gif");
    private ImageIcon undoIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/undo.png");
    private TButton moveButton = new TButton(moveIcon);
    private TButton penButton = new TButton(penIcon);
    private TButton rectNoFillButton = new TButton(rectNoFillIcon);
    private TButton rectFillButton = new TButton(rectFillIcon);
    private TButton ovalNoFillButton = new TButton(ovalNoFillIcon);
    private TButton ovalFillButton = new TButton(ovalFillIcon);
    private TButton lineButton = new TButton(lineIcon);
    private TButton textButton = new TButton(textIcon);
    private TButton handLeftButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand_left.png"));
    private TButton handRightButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand_right.png"));
    private TButton arrowUpButton = new TButton(ImageUtil.createImageIcon(this, "/icons/arrow_up.png"));
    private TButton arrowSideButton = new TButton(ImageUtil.createImageIcon(this, "/icons/arrow_side.png"));
    private JToolBar pointerToolbar = new JToolBar(JToolBar.VERTICAL);
    private MButton deleteButton = new MButton(deleteIcon);
    private MButton clearButton = new MButton(clearIcon);
    private MButton undoButton = new MButton(undoIcon);
    private boolean drawingEnabled = false;
    private Vector<Item> items = new Vector<Item>();
    private JTextField textField = new JTextField();
    private JTextField editTextField = new JTextField();
    private JPopupMenu editPopup = new JPopupMenu();
    private JPopupMenu popup = new JPopupMenu();
    private JPopupMenu deletePopup = new JPopupMenu();
    private int rect_size = 8;
    private int initH;
    private int initW;
    private int last_w;
    private int last_h;
    private int init_x2;
    private int init_y2;
    private int init_x1;
    private int init_y1;
    private Graphics2D graphics;
    private Vector<WBLine> tempPenVector = new Vector<WBLine>();
    public JComboBox fontSizeField = new JComboBox();
    public JComboBox fontNamesField = new JComboBox();
    public JToggleButton boldButton,  italicButton,  underButton;
    private PixelButton pixelButton = new PixelButton();
    private FontMetrics metrics;
    private Item lastItem = null;
    private Rectangle ovalRect1;
    private Rectangle ovalRect2;
    private Rectangle ovalRect3;
    private Rectangle ovalRect4;
    private boolean drawStroke = false;
    private JMenuItem deleteMenuItem = new JMenuItem("Delete");
    private AlphaComposite ac =
            AlphaComposite.getInstance(AlphaComposite.SRC, 0.5f);

    /** Creates new form WhiteboardSurface */
    public WhiteboardSurface(RealtimeBase base) {
        initComponents();
        this.base = base;
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        //this.setFocusable(true);
        addMouseListener(this);
        addKeyListener(this);
        addMouseMotionListener(this);




        setDrawingEnabled(base.getControl() || base.isPresenter());

        ButtonGroup bg = new ButtonGroup();
        bg.add(penButton);
        bg.add(moveButton);
        bg.add(rectFillButton);
        bg.add(rectNoFillButton);
        bg.add(ovalFillButton);
        bg.add(textButton);
        bg.add(lineButton);
        bg.add(ovalNoFillButton);

        bg.add(handRightButton);
        bg.add(handLeftButton);
        bg.add(arrowUpButton);
        bg.add(arrowSideButton);

        handRightButton.setSelected(true);
        pointerToolbar.add(handRightButton);
        handRightButton.addActionListener(this);
        handRightButton.setActionCommand("handRight");
        handRightButton.setToolTipText("Point right");


        pointerToolbar.add(handLeftButton);
        handLeftButton.addActionListener(this);
        handLeftButton.setActionCommand("handLeft");
        handLeftButton.setToolTipText("Point Left");

        pointerToolbar.add(arrowUpButton);
        arrowUpButton.addActionListener(this);
        arrowUpButton.setActionCommand("arrowUp");
        arrowUpButton.setToolTipText("Arrow Up");
        pointerToolbar.add(arrowSideButton);
        arrowSideButton.addActionListener(this);
        arrowSideButton.setActionCommand("arrowSide");
        arrowSideButton.setToolTipText("Arrow Side");
        buttonsToolbar.add(moveButton);
        moveButton.setToolTipText("Used for selecting an item");
        buttonsToolbar.add(penButton);
        penButton.setToolTipText("Freehand");
        buttonsToolbar.add(lineButton);
        lineButton.setToolTipText("Draw straight lines");
        buttonsToolbar.add(rectNoFillButton);
        rectNoFillButton.setToolTipText("Draw a rectangle: Not filled");
        buttonsToolbar.add(rectFillButton);
        rectFillButton.setToolTipText("Draw a rectangel: Filled");
        buttonsToolbar.add(ovalNoFillButton);
        ovalNoFillButton.setToolTipText("Draw an oval: Not filled");
        buttonsToolbar.add(ovalFillButton);
        ovalFillButton.setToolTipText("Draw an oval: Filled");
        buttonsToolbar.add(textButton);
        textButton.setToolTipText("Add Text");
        //buttonsToolbar.add(deleteButton);
        deleteButton.setToolTipText("Delete selected item");
        buttonsToolbar.add(clearButton);
        clearButton.setToolTipText("Clear whiteboard");
        buttonsToolbar.add(undoButton);
        undoButton.setToolTipText("Undo last action");
        penButton.addActionListener(this);
        penButton.setActionCommand("pen");

        moveButton.addActionListener(this);
        moveButton.setActionCommand("move");

        rectNoFillButton.addActionListener(this);
        rectNoFillButton.setActionCommand("rect_nofill");

        rectFillButton.addActionListener(this);
        rectFillButton.setActionCommand("rect_fill");

        ovalNoFillButton.addActionListener(this);
        ovalNoFillButton.setActionCommand("oval_nofill");

        ovalFillButton.addActionListener(this);
        ovalFillButton.setActionCommand("oval_fill");

        textButton.addActionListener(this);
        textButton.setActionCommand("texttool");

        lineButton.addActionListener(this);
        lineButton.setActionCommand("line");

        deleteButton.addActionListener(this);
        deleteButton.setActionCommand("delete");

        clearButton.addActionListener(this);
        clearButton.setActionCommand("clear");

        undoButton.addActionListener(this);
        undoButton.setActionCommand("undo");

        penButton.setSelected(true);
        currentColorField.setBackground(colour);
        createToolBar();
        popup.setOpaque(false);
        popup.setPopupSize(new Dimension(200, 21));
        popup.add(textField);
        popup.addPopupMenuListener(new javax.swing.event.PopupMenuListener() {

            public void popupMenuCanceled(PopupMenuEvent e) {
                commitStroke(startX, startY, prevX, prevY, true);
                popup.setVisible(false);
            }

            public void popupMenuWillBecomeInvisible(PopupMenuEvent e) {
            }

            public void popupMenuWillBecomeVisible(PopupMenuEvent e) {
            }
        });

        editTextField.addActionListener(this);
        editTextField.setActionCommand("edit");
        editPopup.setPopupSize(new Dimension(100, 21));
        editPopup.add(editTextField);

        repaint();

        textField.addActionListener(this);
        textField.setActionCommand("text");
        textField.setOpaque(false);
        textField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent arg0) {
                //resizeTextField();
            }

            public void removeUpdate(DocumentEvent arg0) {
                //resizeTextField();
            }

            public void changedUpdate(DocumentEvent arg0) {
            }
        });
        deleteMenuItem.setActionCommand("delete");
        deleteMenuItem.addActionListener(this);
        deletePopup.add(deleteMenuItem);
    }

    public JToolBar getPointerToolbar() {
        return pointerToolbar;
    }

    public void setSelectedItem(Item selectedItem) {
        this.selectedItem = selectedItem;
        repaint();
    }

    public JToolBar getButtonsToolbar() {
        return buttonsToolbar;
    }

    public TButton getArrowSideButton() {
        return arrowSideButton;
    }

    public TButton getArrowUpButton() {
        return arrowUpButton;
    }

    public TButton getHandLeftButton() {
        return handLeftButton;
    }

    public TButton getHandRightButton() {
        return handRightButton;
    }

    public JPanel getColorPanel() {
        return colorPanel;
    }

    public JToolBar getMainToolbar() {
        return mainToolbar;
    }

    private void resizeTextField() {
        Font f = getSelectedFont();
        metrics = graphics.getFontMetrics(f);

        int width = metrics.stringWidth(textField.getText());
        int hgt = metrics.getHeight();
        // popup.setPopupSize(new Dimension(width, hgt + 10));

        textField.setSize(width, hgt + 10);
        popup.setSize(new Dimension(width, hgt + 10));
    }

    public void setTCPClient(TCPClient tcpClient) {
        tcpClient.setWhiteboardSurfaceHandler(this);
    }

    /**
     * Adds a new item
     * @param item
     */
    public void addItem(Item item) {
        items.addElement(item);
        repaint();
    }

    public void replaceItem(Item item) {
        for (int i = 0; i < items.size(); i++) {
            Item xt = items.elementAt(i);
            if (xt.getId().equals(item.getId())) {
                //    System.out.println("Replace " + xt + " with " + item);
                items.set(i, item);
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

    public void processMousePressed(MouseEvent evt) {

        int button = evt.getButton();
        if (dragging == true) {
            return;
        }

        if (button == MouseEvent.BUTTON1) {
            if (drawingEnabled) {
                prevX = startX = evt.getX();
                prevY = startY = evt.getY();

                if (brush == BRUSH_MOVE) {
                    selected = null;
                    selectedItem =
                            null;

                    for (int i = 0; i <
                            items.size(); i++) {
                        Item tmp = items.elementAt(i);
                        if (tmp instanceof Txt) {
                            Txt txt = (Txt) tmp;

                            if (txt.contains(startX, startY, graphics)) {
                                selected = tmp;
                                selectedItem =
                                        selected;
                                selectedIndex =
                                        i;

                                boldButton.setSelected(false);
                                italicButton.setSelected(false);
                                underButton.setSelected(false);

                                underButton.setSelected(txt.isUnderlined());

                                Font f = txt.getFont();
                                fontNamesField.setSelectedItem(f.getFamily());
                                fontSizeField.setSelectedItem(f.getSize() + "");

                                int style = f.getStyle();
                                if (style == Font.BOLD) {
                                    boldButton.setSelected(true);
                                }

                                if (style == Font.ITALIC) {
                                    italicButton.setSelected(true);
                                }

                                setCursor(Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR));




                                if (evt.getClickCount() == 2) {


                                    FontMetrics metrics = graphics.getFontMetrics(f);

                                    int hgt = metrics.getHeight();
                                    int adv = metrics.stringWidth(txt.getContent());
                                    //popup.setPopupSize(100, hgt);

                                    Dimension size = new Dimension(adv + 2, hgt + 2);
                                    editTextField.setText(txt.getContent());
                                    editPopup.setPopupSize(size);

                                    if (txt.contains(evt.getX(), evt.getY(), graphics)) {
                                        editPopup.show(base.getSurface(), evt.getX(), evt.getY() - editTextField.getHeight());
                                    }

                                    editTextField.requestFocus();
                                }
                                repaint();
                                break;
                            }
                            repaint();
                        //break;
                        }

                        if (tmp.contains(startX, startY)) {

                            selected = tmp;
                            selectedItem =
                                    selected;
                            selectedIndex =
                                    i;

                            setCursor(Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR));

                            if (selectedItem instanceof WBLine) {
                                WBLine line = (WBLine) selectedItem;
                                init_x2 =
                                        line.x2;
                                init_y2 =
                                        line.y2;
                                init_x1 =
                                        line.x1;
                                init_y1 =
                                        line.y1;

                                Rectangle r1 = new Rectangle(line.x1 - 5,
                                        line.y1 - 5, 10, 10);
                                Rectangle r2 = new Rectangle(line.x2 - 5,
                                        line.y2 - 5, 10, 10);

                                if (r1.contains(evt.getPoint())) {
                                    setCursor(Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR));
                                }

                                if (r2.contains(evt.getPoint())) {
                                    setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
                                }

                            } else if (tmp instanceof Oval) {
                                Rectangle bounds = tmp.getBounds();
                                if (bounds.contains(startX, startY)) {
                                    selected = tmp;
                                    selectedItem =
                                            selected;
                                    selectedIndex =
                                            i;
                                    setCursor(Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR));

                                    int xx = bounds.x;
                                    int yy = bounds.y;
                                    int ww = bounds.width;
                                    int hh = bounds.height;
                                    last_w = xx + ww;
                                    initW = ww;
                                    last_h = yy + hh;
                                    initH = hh;
                                    if (ovalRect1 != null) {
                                        Rectangle r1 = ovalRect2;//new Rectangle((xx + ww) - 20,
                                        //yy, 20, 20);
                                        Rectangle r2 = ovalRect3;// new Rectangle((xx + ww) - 20,

                                        if (r1.contains(evt.getPoint())) {
                                            setCursor(Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR));
                                            last_w = xx + ww;
                                            initW = ww;
                                        }

                                        if (r2.contains(evt.getPoint())) {
                                            setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
                                        }
                                    }
                                    repaint();
                                    break;
                                }

                            } else {
                                Rectangle bounds = selected.getBounds();
                                int xx = bounds.x;
                                int yy = bounds.y;
                                int ww = bounds.width;
                                int hh = bounds.height;
                                Rectangle r1 = new Rectangle(
                                        (xx + ww) - 20, yy, 20, 20);
                                Rectangle r2 = new Rectangle(
                                        (xx + ww) - 20, (yy + hh) - 20, 20,
                                        20);

                                last_w = xx + ww;
                                initW = ww;
                                last_h = yy + hh;
                                initH = hh;

                                //initialWidth = ww;
                                //initialHeight = hh;

                                if (r1.contains(evt.getPoint())) {
                                    setCursor(Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR));
                                    last_w =
                                            xx + ww;
                                    initW =
                                            ww;
                                }

                                if (r2.contains(evt.getPoint())) {
                                    setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
                                }

                                break;
                            }

                        }
                    }
                } else if (brush == BRUSH_PEN) {
                    penVector = new Vector<WBLine>();
                    tempPenVector =
                            new Vector<WBLine>();
                    penVector.addElement(new WBLine(startX, startY, startX,
                            startY, colour, strokeWidth));
                    tempPenVector =
                            penVector;

                }

                if (brush == BRUSH_TEXT) {
                    if (popup.isVisible()) {
                        popup.setVisible(false);
                    }

                    textField.setText("");
                    Font f = getSelectedFont();
                    textField.setFont(f);
                    FontMetrics metrics = graphics.getFontMetrics(f);

                    int hgt = metrics.getHeight();
                    //  textField.setForeground(colour);
                    popup.setPopupSize(100, hgt + (int) (hgt * 0.2));
                    //popup.setPreferredSize(new Dimension(100, 50));
                    popup.show(base.getSurface(), evt.getX(), evt.getY() - textField.getHeight());
                    textField.requestFocus();
                }

            }
            dragging = true;
        }

        /**
         * allow modifying of existing text through right click
         */
        if (button == MouseEvent.BUTTON3) {
            if (drawingEnabled) {
                /*     selected = null;
                
                int tempIndex = 0;
                for (int i = 0; i <
                items.size(); i++) {
                Item tmp = items.elementAt(i);
                if (tmp.contains(startX, startY)) {
                selected = tmp;
                selectedIndex =
                tempIndex;
                tempIndex++;
                
                break;
                
                }
                
                
                }
                if (editPopup.isVisible()) {
                editPopup.setVisible(false);
                }*/

                if (brush == BRUSH_MOVE) {
                    if (selectedItem != null) {
                        deletePopup.show(base.getSurface(), evt.getX(), evt.getY());
                    }

                }
            }
        }
        repaint();
    }

    public void mousePressed(MouseEvent evt) {
        processMousePressed(evt);
    }

    public void processMouseReleased(MouseEvent evt) {
        drawStroke = false;
        if ((dragging == false) || (evt.getButton() != MouseEvent.BUTTON1)) {
            return;
        }

        dragging = false;
        if (drawingEnabled) {
            if (brush != BRUSH_PEN) {
                commitStroke(startX, startY, prevX, prevY, true);
            }

            if (brush == BRUSH_PEN) {

                base.getTcpClient().sendPacket(
                        new WhiteboardPacket(base.getSessionId(), new Pen(penVector, colour,
                        strokeWidth), Constants.ADD_NEW_ITEM, GenerateUUID.getId()));

                penVector.removeAllElements();
                tempPenVector.removeAllElements();
                penVector =
                        null;
            }

            if (brush == BRUSH_MOVE) {
                if (selected != null) {
                    if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR)) {

                        if (!(selectedItem instanceof WBLine)) {
                            Item newItem = selected.getTranslated(prevX - startX, prevY - startY);
                            selectedItem.setIndex(selectedIndex);

                            newItem.setIndex(selectedIndex);
                            base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                                    newItem, Constants.REPLACE_ITEM, selected.getId()));

                        }

                    }
                    if ((this.getCursor() == Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR)) || (this.getCursor() == Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR))) {

                        if (!(selectedItem instanceof WBLine)) {
                            int changeW = evt.getX() - last_w;
                            int changeH = evt.getY() - last_h;
                            int newW = initW + changeW;
                            int newH = initH + changeH;

                            if ((newW > 10) && (newH > 10)) {
                                Item item = selectedItem;

                                item.setSize(newW, newH);
                                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                                        item, Constants.REPLACE_ITEM, selected.getId()));

                            //  base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                            //        item, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));

                            }
                        }

                    }
                }
            }
        }
        setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
    }

    public void mouseReleased(MouseEvent evt) {
        processMouseReleased(evt);
    }

    public void processMouseDragged(MouseEvent evt) {
        drawStroke = true;
        if (dragging == false) {
            return;
        }



        int x = evt.getX();
        int y = evt.getY();
        if (drawingEnabled) {
            if (brush == BRUSH_PEN) {
                penVector.addElement(new WBLine(prevX, prevY, x, y, colour,
                        strokeWidth));
                tempPenVector =
                        penVector;
                lastItem = new Pen(penVector, colour, strokeWidth);

                drawTempPen();

            } else if (brush == BRUSH_MOVE) {
                if (selected != null) {
                    if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR)) {
                        if (selectedItem instanceof WBLine) {
                            WBLine line = (WBLine) selected;
                            int w = evt.getX() - startX;
                            int h = evt.getY() - startY;
                            line.x1 = (init_x1 + w);
                            line.y1 = (init_y1 + h);
                            line.x2 = (init_x2 + w);
                            line.y2 = (init_y2 + h);
                            Item newItem = line.getTranslated(x - prevX, y - prevY);
                            selectedItem = newItem;
                            selectedItem.setIndex(selectedIndex);

                            base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                                    selectedItem, Constants.REPLACE_ITEM, selected.getId()));

                        } else {
                            Item newItem = selected.getTranslated(x - startX, y - startY);
                            selectedItem = newItem;

                            selectedItem.setIndex(selectedIndex);
                            //System.out.println("Existing id: " + selected.getId());
                            base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                                    selectedItem, Constants.REPLACE_ITEM, selected.getId()));


                        }
                    }

                    if ((this.getCursor() == Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR)) || (this.getCursor() == Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR))) {
                        if (selectedItem instanceof WBLine) {
                            WBLine line = (WBLine) selectedItem;
                            if ((this.getCursor() == Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR))) {
                                line.x1 = (evt.getX());
                                line.y1 = (evt.getY());
                            }
                            if ((this.getCursor() == Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR))) {
                                line.x2 = (evt.getX());
                                line.y2 = (evt.getY());

                                selectedItem = line;

                            // selectedItem.setIndex(selectedIndex);
                            //base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                            //      selectedItem, Constants.REPLACE_ITEM, selected.getId()));
                            }
                        } else {
                            int changeW = evt.getX() - last_w;
                            int changeH = evt.getY() - last_h;
                            int newW = initW + changeW;
                            int newH = initH + changeH;
                            if ((newW > 10) && (newH > 10)) {
                                selectedItem.setSize(newW, newH);
                                selectedItem.setIndex(selectedIndex);

                            }
                        }
                    }
                }
            }
            prevX = x;
            prevY = y;
        }

        repaint();
    }

    public void mouseDragged(MouseEvent evt) {
        processMouseDragged(evt);
    }

    public void processMouseMoved(MouseEvent evt) {
        if (ovalRect1 != null) {
            Rectangle r1 = ovalRect2;//new Rectangle((xx + ww) - 20,
            //yy, 20, 20);
            Rectangle r2 = ovalRect3;// new Rectangle((xx + ww) - 20,
            //(yy + hh) - 10, 20, 20);
            // System.out.println("R1: " + r1 + "\nR2 : " + r2 + "\nEvt : " + evt.getPoint() + "\nContains R1: " + (r1.contains(evt.getPoint())) + "\nContails R2: " + r2.contains(evt.getPoint()));
            if (r1.contains(evt.getPoint())) {
                setCursor(Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR));
            } else {
                setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
            }

            if (r2.contains(evt.getPoint())) {
                setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
            } else {
                setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
            }
        }
    }

    public void mouseMoved(MouseEvent evt) {
        processMouseMoved(evt);
    }

    public void keyPressed(KeyEvent evt) {

        if (evt.getKeyCode() == Event.DELETE) {

            deleteSelectedItem();
        }
    }

    public void keyReleased(KeyEvent evt) {
    }

    public void keyTyped(KeyEvent evt) {
    }

    public void deleteSelectedItem() {
        if (selectedItem != null) {
            base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                    selectedItem, Constants.REMOVE_ITEM, selected.getId()));
        }
    }

    public void clearWhiteboard() {

        base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                new FreeHand(), Constants.CLEAR_ITEMS, GenerateUUID.getId()));
        selectedItem = null;
        repaint();
    }

    public void actionPerformed(ActionEvent evt) {
        base.getSurface().setPointer(Constants.WHITEBOARD);
        if (evt.getActionCommand().equals("handLeft")) {

            base.getSurface().setPointer(Constants.HAND_LEFT);
        }
        if (evt.getActionCommand().equals("handRight")) {

            base.getSurface().setPointer(Constants.HAND_RIGHT);
        }
        if (evt.getActionCommand().equals("arrowUp")) {

            base.getSurface().setPointer(Constants.ARROW_UP);
        }
        if (evt.getActionCommand().equals("arrowSide")) {

            base.getSurface().setPointer(Constants.ARROW_SIDE);
        }
        if (evt.getActionCommand().equals("paintBrush")) {
            base.getSurface().setPointer(Constants.PAINT_BRUSH);
        }
        if (evt.getActionCommand().equals("whiteBoard")) {
            base.getSurface().setPointer(Constants.WHITEBOARD);
        }
        if (evt.getActionCommand().equals("delete")) {
            deleteSelectedItem();

        }
        if (evt.getActionCommand().equals("clear")) {
            int n = JOptionPane.showConfirmDialog(this, "This action will clear whiteboard items.", "Clear", JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                clearWhiteboard();
            }

        }
        if (evt.getActionCommand().equals("undo")) {
            if (lastItem != null) {
                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(),
                        lastItem, Constants.REMOVE_ITEM, lastItem.getId()));
            }
            repaint();
        }

        if (evt.getActionCommand().equals("line")) {
            brush = BRUSH_LINE;
        }

        if (evt.getActionCommand().equals("move")) {
            brush = BRUSH_TEXT;
        }

        if (evt.getActionCommand().equals("move")) {
            brush = BRUSH_MOVE;
        }

        if (evt.getActionCommand().equals("pen")) {
            brush = BRUSH_PEN;
        }

        if (evt.getActionCommand().equals("rect_nofill")) {
            brush = BRUSH_RECT;
        }

        if (evt.getActionCommand().equals("rect_fill")) {
            brush = BRUSH_RECT_FILLED;
        }

        if (evt.getActionCommand().equals("oval_fill")) {
            brush = BRUSH_OVAL_FILLED;
        }

        if (evt.getActionCommand().equals("oval_nofill")) {
            brush = BRUSH_OVAL;
        }

        if (evt.getActionCommand().equals("texttool")) {
            brush = BRUSH_TEXT;
        }

        if (evt.getActionCommand().equals("text")) {
            commitStroke(startX, startY, prevX, prevY, true);
            popup.setVisible(false);
        }


        if (evt.getActionCommand().equals("edit")) {
            int size = Integer.parseInt((String) fontSizeField.getSelectedItem());
            int style = Font.PLAIN;
            if (boldButton.isSelected()) {
                style = Font.BOLD;
            }

            if (italicButton.isSelected()) {
                style = Font.ITALIC;
            }

            if (boldButton.isSelected() && italicButton.isSelected()) {
                style = Font.BOLD | Font.ITALIC;
            }
            if (selectedItem != null) {
                if (selectedItem instanceof Txt) {
                    Txt old = (Txt) selectedItem;
                    Rectangle r = old.getRect(graphics);
                    Txt txt = new Txt(r.x, r.y, editTextField.getForeground(), editTextField.getText(),
                            new Font((String) fontNamesField.getSelectedItem(), style, size),
                            underButton.isSelected());

                    base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), txt, Constants.REPLACE_ITEM, old.getId()));

                    editPopup.setVisible(false);
                    selectedItem = null;
                    repaint();
                }
            }
        }

    }

    public void setGraphics(Graphics2D graphics) {
        this.graphics = graphics;
    }

    private void drawTempPen() {
        Pen p = new Pen(tempPenVector, colour,
                strokeWidth);
        graphics.setStroke(new BasicStroke(p.getStroke()));
        graphics.setColor(p.getCol());
        Vector<WBLine> v = p.getPoints();
        for (int k = 0; k <
                v.size(); k++) {
            WBLine l = v.elementAt(k);
            graphics.drawLine(l.x1, l.y1, l.x2, l.y2);
        }

    }

    private void commitStroke(int x1, int y1, int x2, int y2, boolean fill) {


        int x; // Top-left corner, width, and height.
        int y; // Top-left corner, width, and height.
        int w; // Top-left corner, width, and height.
        int h; // Top-left corner, width, and height.

        if (x2 >= x1) { // x1 is left edge
            x = x1;
            w =
                    x2 - x1;
        } else { // x2 is left edge
            x = x2;
            w =
                    x1 - x2;
        }

        if (y2 >= y1) { // y1 is top edge
            y = y1;
            h =
                    y2 - y1;
        } else { // y2 is top edge.
            y = y2;
            h =
                    y1 - y2;
        }

        switch (brush) {
            case BRUSH_LINE:
                WBLine line = new WBLine(x1, y1, x2, y2, colour,
                        strokeWidth);
                base.getTcpClient().sendPacket(
                        new WhiteboardPacket(base.getSessionId(), line, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = line;
                break;

            case BRUSH_RECT:
                Rect rect = new Rect(x, y, w, h, colour, false,
                        strokeWidth);
                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), rect, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = rect;
                break;

            case BRUSH_OVAL:
                Oval oval = new Oval(x, y, w, h, colour, false,
                        strokeWidth);
                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), oval, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = oval;
                break;

            case BRUSH_RECT_FILLED:
                Rect filledRect = new Rect(x, y, w, h, colour, true,
                        strokeWidth);
                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), filledRect, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = filledRect;
                break;

            case BRUSH_OVAL_FILLED:
                Oval filledOval = new Oval(x, y, w, h, colour, true,
                        strokeWidth);
                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), filledOval, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = filledOval;
                break;

            case BRUSH_TEXT:
                int size = Integer.parseInt((String) fontSizeField.getSelectedItem());
                int style = Font.PLAIN;
                if (boldButton.isSelected()) {
                    style = Font.BOLD;
                }

                if (italicButton.isSelected()) {
                    style = Font.ITALIC;
                }

                if (boldButton.isSelected() && italicButton.isSelected()) {
                    style = Font.BOLD | Font.ITALIC;
                }
                Txt txt = new Txt(x, y, textField.getForeground(), textField.getText(),
                        new Font((String) fontNamesField.getSelectedItem(), style, size),
                        underButton.isSelected());
                base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), txt, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = txt;
                break;

        }


    }

    /**
     * this draws current item...though probably temporarily
     * @param g Graphics
     */
    public void drawStroke(Graphics2D g) {
        // System.out.println();
        if (drawStroke) {
            int x1 = startX;
            int y1 = startY;
            int x2 = prevX;
            int y2 = prevY;

            // g.setXORMode(getBackground());
            g.setStroke(new BasicStroke(strokeWidth));
            g.setColor(colour);

            int x; // Top-left corner, width, and height.
            int y; // Top-left corner, width, and height.
            int w; // Top-left corner, width, and height.
            int h; // Top-left corner, width, and height.

            if (x2 >= x1) { // x1 is left edge
                x = x1;
                w =
                        x2 - x1;
            } else { // x2 is left edge
                x = x2;
                w =
                        x1 - x2;
            }

            if (y2 >= y1) { // y1 is top edge
                y = y1;
                h =
                        y2 - y1;
            } else { // y2 is top edge.
                y = y2;
                h =
                        y1 - y2;
            }

            switch (brush) {
                case BRUSH_PEN:
                    if (penVector != null) {
                        for (int i = 0; i <
                                penVector.size(); i++) {
                            WBLine line = penVector.elementAt(i);
                            g.drawLine(line.x1, line.y1, line.x2, line.y2);
                        }

                    }
                    break;
                case BRUSH_LINE:
                    g.drawLine(x1, y1, x2, y2);
                    break;

                case BRUSH_RECT:
                    g.drawRect(x, y, w, h);
                    break;

                case BRUSH_OVAL:
                    g.drawOval(x, y, w, h);
                    break;

                case BRUSH_RECT_FILLED:
                    g.fillRect(x, y, w, h);
                    break;

                case BRUSH_OVAL_FILLED:
                    g.fillOval(x, y, w, h);
                    break;

                case BRUSH_TEXT:
                    /*Graphics2D g2 = (Graphics2D) g;
                    g2.setColor(colour);
                    String txt = textField.getText();
                    int size = Integer.parseInt((String) clientApplet.fontSizeField
                    .getSelectedItem());
                    int style = Font.PLAIN;
                    if (clientApplet.boldButton.isSelected()) {
                    style = Font.BOLD;
                    }
                    if (clientApplet.italicButton.isSelected()) {
                    style = Font.ITALIC;
                    }
                    if (clientApplet.boldButton.isSelected()
                    && clientApplet.italicButton.isSelected()) {
                    style = Font.BOLD | Font.ITALIC;
                    }
                    
                    Font font = new Font((String) clientApplet.fontNamesField
                    .getSelectedItem(), style, size);
                    boolean underLine = clientApplet.underButton.isSelected();
                    if (txt.length() > 0) {
                    AttributedString as = new AttributedString(txt);
                    as.addAttribute(TextAttribute.FONT, font);
                    if (underLine) {
                    as.addAttribute(TextAttribute.UNDERLINE,
                    TextAttribute.UNDERLINE_ON);
                    }
                    TextLayout tl = new TextLayout(as.getIterator(), g2
                    .getFontRenderContext());
                    tl.draw(g2, x2, y2);
                    }*/
                    break;
            }
        }

    }

    private Font getSelectedFont() {
        int size = Integer.parseInt((String) fontSizeField.getSelectedItem());
        int style = Font.PLAIN;
        if (boldButton.isSelected()) {
            style = Font.BOLD;
        }

        if (italicButton.isSelected()) {
            style = Font.ITALIC;
        }

        if (boldButton.isSelected() && italicButton.isSelected()) {
            style = Font.BOLD | Font.ITALIC;
        }

        return new Font((String) fontNamesField.getSelectedItem(),
                style, size);
    }

    /** Our own button behavoir
     */
    class TButton extends JToggleButton {

        public TButton(ImageIcon icon) {
            super(icon);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 8));

        }
    }

    /** Our own button behavoir
     */
    class MButton extends JButton {

        public MButton(ImageIcon icon) {
            super(icon);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 8));

        }
    }
//paints the current itme
    private void paintCurrentItem(Graphics2D g2) {
        Item temp = selectedItem;
        if (temp instanceof Rect) {
            Rect r = (Rect) temp;
            g2.setStroke(new BasicStroke(r.getStroke()));
            g2.setColor(r.getCol());

            if (r.isFilled()) {
                g2.fill(r.getRect());
            } else {
                g2.draw(r.getRect());
            }

        }
        if (temp instanceof WBLine) {
            WBLine l = (WBLine) temp;
            g2.setStroke(new BasicStroke(l.getStroke()));
            g2.setColor(l.getCol());
            Line2D line = l.getLine();
            g2.draw(line);
        }

        if (temp instanceof Pen) {
            Pen p = (Pen) temp;
            g2.setStroke(new BasicStroke(p.getStroke()));
            g2.setColor(p.getCol());
            Vector<WBLine> v = p.getPoints();
            for (int k = 0; k <
                    v.size(); k++) {
                WBLine l = v.elementAt(k);
                g2.drawLine(l.x1, l.y1, l.x2, l.y2);
            }

        }
        if (temp instanceof Oval) {
            Oval o = (Oval) temp;
            g2.setStroke(new BasicStroke(o.getStroke()));
            g2.setColor(o.getCol());
            if (o.isFilled()) {
                Rectangle r = o.getBounds();
                g2.fillOval(r.x, r.y, r.width, r.height);
            } else {
                Rectangle r = o.getRect();
                g2.drawOval(r.x, r.y, r.width, r.height);
            }

        }
        if (temp instanceof Img) {
            Img img = (Img) temp;
            Rectangle bounds = img.getBounds();
            g2.drawImage(img.getImg().getImage(), bounds.x, bounds.y,
                    bounds.width, bounds.height, this);
        }

        if (temp instanceof Txt) {
            Txt t = (Txt) temp;
            g2.setColor(t.getCol());

            String txt = t.getContent();
            Font font = t.getFont();
            boolean underLine = t.isUnderlined();
            Point point = t.getPoint();

            if (txt.length() > 0) {
                AttributedString as = new AttributedString(txt);
                as.addAttribute(TextAttribute.FONT, font);
                if (underLine) {
                    as.addAttribute(TextAttribute.UNDERLINE,
                            TextAttribute.UNDERLINE_ON);
                }

                TextLayout tl = new TextLayout(as.getIterator(), g2.getFontRenderContext());
                tl.draw(g2, point.x, point.y);
            }

        }


    }

    public void paintItems(Graphics2D g2) {

        for (int i = 0; i <
                items.size(); i++) {
            Item temp = items.elementAt(i);
            if (temp instanceof Rect) {
                Rect r = (Rect) temp;
                g2.setStroke(new BasicStroke(r.getStroke()));
                g2.setColor(r.getCol());

                if (r.isFilled()) {
                    g2.fill(r.getRect());
                } else {
                    g2.draw(r.getRect());
                }


            }
            if (temp instanceof WBLine) {
                WBLine l = (WBLine) temp;
                g2.setStroke(new BasicStroke(l.getStroke()));
                g2.setColor(l.getCol());
                Line2D line = l.getLine();
                g2.draw(line);
            }

            if (temp instanceof Pen) {
                Pen p = (Pen) temp;
                g2.setStroke(new BasicStroke(p.getStroke()));
                g2.setColor(p.getCol());
                Vector<WBLine> v = p.getPoints();
                for (int k = 0; k <
                        v.size(); k++) {
                    WBLine l = v.elementAt(k);
                    g2.drawLine(l.x1, l.y1, l.x2, l.y2);
                }

            }
            if (temp instanceof Oval) {
                Oval o = (Oval) temp;
                g2.setStroke(new BasicStroke(o.getStroke()));
                g2.setColor(o.getCol());
                if (o.isFilled()) {
                    Rectangle r = o.getBounds();
                    g2.fillOval(r.x, r.y, r.width, r.height);
                } else {
                    Rectangle r = o.getRect();
                    g2.drawOval(r.x, r.y, r.width, r.height);
                }

            }
            if (temp instanceof Img) {
                Img img = (Img) temp;
                Rectangle bounds = img.getBounds();
                g2.drawImage(img.getImg().getImage(), bounds.x, bounds.y,
                        bounds.width, bounds.height, this);
            }

            if (temp instanceof Txt) {
                Txt t = (Txt) temp;
                g2.setColor(t.getCol());

                String txt = t.getContent();
                Font font = t.getFont();
                boolean underLine = t.isUnderlined();
                Point point = t.getPoint();

                if (txt.length() > 0) {
                    AttributedString as = new AttributedString(txt);
                    as.addAttribute(TextAttribute.FONT, font);
                    if (underLine) {
                        as.addAttribute(TextAttribute.UNDERLINE,
                                TextAttribute.UNDERLINE_ON);
                    }

                    TextLayout tl = new TextLayout(as.getIterator(), g2.getFontRenderContext());
                    tl.draw(g2, point.x, point.y);
                }

            }


            g2.setColor(Color.black);
        }

        g2.setStroke(dashed);
        g2.setColor(Color.red);

        //draw red rectange around selected item
        if (selectedItem instanceof Txt) {
            Txt txt = (Txt) selectedItem;
            Rectangle r = txt.getRect(g2);
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
        }

        if (selectedItem instanceof Rect) {
            Rect rr = (Rect) selectedItem;
            Rectangle r = rr.getRect();
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
            g2.draw(r);
        }

        if (selectedItem instanceof Img) {
            Img img = (Img) selectedItem;
            Rectangle r = img.getBounds();
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
        }

        if (selectedItem instanceof Oval) {
            Oval oo = (Oval) selectedItem;
            Rectangle r = oo.getRect();
            ovalRect1 = new Rectangle(r.x, r.y, rect_size, rect_size);
            g2.fill(ovalRect1);
            ovalRect2 = new Rectangle((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fill(ovalRect2);
            ovalRect3 = new Rectangle((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fill(ovalRect3);
            ovalRect4 = new Rectangle(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
            g2.fill(ovalRect4);
        }

        if (selectedItem instanceof WBLine) {

            WBLine line = (WBLine) selectedItem;
            g2.fillRect(line.x1 - (rect_size / 2), line.y1 - (rect_size / 2), rect_size, rect_size);
            g2.fillRect(line.x2 - (rect_size / 2), line.y2 - (rect_size / 2), rect_size, rect_size);

        }

    /*
    This is intentionally commented by David Wafula...needs clarification
    from Nik...this is due to changes made on this method.
    //note: images will always be drawn on the top layer
    //would fix by making ClipArt extend Item but it requires
    //an ImageObserver to paint.
    drawImages(tmp);
    if (tooltip) {
    tooltipIcon.paint(tmp, this);
    }
    
    g.drawImage(backbuffer, 0, 0, this);
    }
    
     */
    }

    /**
     * Create the tool bar.
     */
    private void createToolBar() {
        try {
            boldButton = new JToggleButton(ImageUtil.createImageIcon(this,
                    "/icons/whiteboard/text_bold.png"));
            boldButton.setBorderPainted(false);
            boldButton.setToolTipText("Bold");
            mainToolbar.add(boldButton);

            italicButton =
                    new JToggleButton(ImageUtil.createImageIcon(this,
                    "/icons/whiteboard/text_italic.png"));
            italicButton.setToolTipText("Italic");
            italicButton.setBorderPainted(false);
            mainToolbar.add(italicButton);

            underButton =
                    new JToggleButton(ImageUtil.createImageIcon(this,
                    "/icons/whiteboard/text_under.png"));
            underButton.setToolTipText("Underline");
            underButton.setBorderPainted(false);
            mainToolbar.add(underButton);

            pixelButton.addActionListener(this);
            pixelButton.setActionCommand(COMMAND_PIXEL);
            pixelButton.setSize(81, 27);
            /**
             * probably not the best place to place the pixel button..but now with the
             * toolbar layout...where else?
             */
            mainToolbar.add(pixelButton);

            for (int i = 8; i <
                    100; i++) {
                fontSizeField.addItem(i + "");
            }

            fontSizeField.setSelectedItem("12");
            GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
            String[] fontFamilies = ge.getAvailableFontFamilyNames();
            for (int i = 0; i <
                    fontFamilies.length; i++) {
                fontNamesField.addItem(fontFamilies[i]);
            }

            fontNamesField.setSelectedItem("Dialog");
            mainToolbar.add(fontNamesField);
            JPanel p = new JPanel();
            p.setLayout(new BorderLayout());
            p.add(fontSizeField, BorderLayout.WEST);
            mainToolbar.add(p);
            mainToolbar.setEnabled(false);

            createPixelOptionFrame();

        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    /**
     * Create an option frame from where one can choose the pixel width of a drawing
     * tool
     */
    private void createPixelOptionFrame() {
        JPanel p = new JPanel();
        p.setPreferredSize(new Dimension(100, 100));
        p.setLayout(new GridLayout(0, 1));
        PixelButtonOption b = new PixelButtonOption(1);
        p.setSize(81, 21);
        p.add(b);

        b =
                new PixelButtonOption(3);
        p.setSize(81, 21);

        p.add(b);
        b =
                new PixelButtonOption(5);
        p.setSize(81, 21);

        p.add(b);
        b =
                new PixelButtonOption(7);
        p.setSize(81, 21);

        p.add(b);
        b =
                new PixelButtonOption(9);
        p.setSize(81, 21);

        p.add(b);
        b =
                new PixelButtonOption(11);
        p.setSize(81, 21);
    //just put the panel on the popup for rendering
    // popup.add(p);
    }

    /**
     *
     * Create our own custom button that basically shows current pixel selection
     * Has some basic jbutton methods
     * */
    class PixelButton extends Component {

        boolean pressed = false;
        ActionListener actionListener;
        String actionCommand;

        PixelButton() {

            // Rather than adding a MouseListener we force all Mouse
            // button events to be sent to the ProcessEvents()
            // method with this call to enableEvents with this mask.
            enableEvents(AWTEvent.MOUSE_EVENT_MASK);
        }

        public void paint(Graphics g) {

























































            int width = getSize().width, height = getSize().height;

            // Use gray for the button sides.
            g.setColor(Color.GRAY);

            // Use the 3D rectangle drawing methods to create a
            // button style border around the image.
            // Redraw with smaller dimensions to make border larger. 
            // Note that the last argument determines if the rectangle
            // shading is for raised or lowered bevel.
            g.draw3DRect(0, 0, width - 1, height - 1, !pressed);
            g.draw3DRect(1, 1, width - 3, height - 3, !pressed);
            g.draw3DRect(2, 2, width - 5, height - 5, !pressed);

            // Change back to white for the image background.
            g.setColor(Color.white);

            // Fill the area inside the last 3D rectangle
            g.fillRect(3, 3, width - 6, height - 6);

            Graphics2D g2 = (Graphics2D) g;
            g2.setColor(Color.black);
            g2.setStroke(new BasicStroke(strokeWidth));
            g2.drawLine(2, height / 2, width - 2, height / 2);

        }

        // The layout managers call this for a component when they
        // decide how much space to allow for it.
        public Dimension getPreferredSize() {
            return getSize();
        }

        // We forced all mouse click events to come here
        // in a manner similar to the old Java 1.0 event
        // handling style. The event is examined to see
        // what kind it is.
        public void processEvent(AWTEvent e) {
            if (e.getID() == MouseEvent.MOUSE_PRESSED) {
                // Set the button in the pressed state
                pressed = true;
                // and paint it in pressed state.
                repaint();
            } else if (e.getID() == MouseEvent.MOUSE_RELEASED) {
                // Set the button in up state
                pressed = false;
                repaint();
                // and call fireEvent, which will send events
                // to the listeners to PictureButton.
                fireEvent();
            }
            super.processEvent(e);
        }

        // A listener or event handling for this button may check
        // its ActionCommand value to see which button it is.
        // For a regular Button the ActionCommand value defaults
        // to the name on the Button. The setActionCommand allows
        // one to use a different value to test for in the
        // listener. Here we are making our own button so we
        // should set the ActionCommand value.
        public void setActionCommand(String actionCommand) {
            this.actionCommand = actionCommand;
        }

        // These three methods are what allow this button to become
        // an event generator. The AWTEventMulticaster takes care
        // of most of the work.

        // Here we allow listeners to add themselves to our
        // listener list. We use the AWTEventMulticaster static add.
        public void addActionListener(ActionListener l) {

            // NOTE: an AWTEventMulticaster object is returned here.
            // but since it implements all the listener interfaces
            // so we can reference it with our actionListener variable.
            actionListener = AWTEventMulticaster.add(actionListener, l);
        }

        // Here we allow listeners to remove themselves from our
        // listener list. We use the AWTEventMulticaster static
        // remove method.
        public void removeActionListener(ActionListener l) {
            actionListener = AWTEventMulticaster.remove(actionListener, l);
        }

        // This method is called by the processEvent() above
        // when the mouse button is released over the button .
        private void fireEvent() {

            if (actionListener != null) {
                // listener list. We use the AWTEventMulticaster
                // static add.
                ActionEvent event = new ActionEvent(this,
                        ActionEvent.ACTION_PERFORMED, actionCommand);

                // The AWTEventMulticaster object, but referenced
                // here with our actionListener variable, will call
                // all the actionListeners with this call to its
                // actionPerformed.
                actionListener.actionPerformed(event);
            }
        }
    }

    /**
     * This is still our own button, with most basic jbutton methods, but
     * we do our own painting on paint method
     */
    class PixelButtonOption extends Component implements ActionListener {

        boolean pressed = false;
        ActionListener actionListener;
        String actionCommand;
        float width;
        BasicStroke basicStroke;

        PixelButtonOption(float width) {
            this.width = width;
            basicStroke = new BasicStroke(width);
            this.addActionListener(this);
            enableEvents(AWTEvent.MOUSE_EVENT_MASK);
        }

        /**
         * we do our own painting here
         * @param g Graphics
         */
        public void paint(Graphics g) {
            int width = getSize().width, height = getSize().height;
            g.setColor(Color.white);
            g.draw3DRect(1, 1, width - 3, height - 3, !pressed);
            g.setColor(Color.white);
            g.fillRect(3, 3, width - 6, height - 6);
            Graphics2D g2 = (Graphics2D) g;
            g2.setColor(Color.black);
            g2.setStroke(basicStroke);
            g2.drawLine(2, height / 2, width - 2, height / 2);
        }

        public void actionPerformed(ActionEvent e) {
            strokeWidth = width;
            pixelButton.repaint();
        }

        public Dimension getPreferredSize() {
            return getSize();
        }

        public void processEvent(AWTEvent event) {
            if (event.getID() == MouseEvent.MOUSE_PRESSED) {
                // Set the button in the pressed state
                pressed = true;
                // and paint it in pressed state.
                repaint();
            } else if (event.getID() == MouseEvent.MOUSE_RELEASED) {
                // Set the button in up state
                pressed = false;
                repaint();
                // and call fireEvent, which will send events
                // to the listeners to PictureButton.
                fireEvent();
            }
            super.processEvent(event);
        }

        public void setActionCommand(String actionCommand) {
            this.actionCommand = actionCommand;
        }

        public void addActionListener(ActionListener l) {
            actionListener = AWTEventMulticaster.add(actionListener, l);
        }

        public void removeActionListener(ActionListener l) {
            actionListener = AWTEventMulticaster.remove(actionListener, l);
        }

        private void fireEvent() {
            if (actionListener != null) {
                ActionEvent event = new ActionEvent(this,
                        ActionEvent.ACTION_PERFORMED, actionCommand);
                actionListener.actionPerformed(event);
            }
        }
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        graphics =
                g2;
        if (XOR) {
            g2.setXORMode(getBackground());
        }

        drawStroke(g2);
        paintItems(g2);
    //  paintCurrentItem(g2);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        colorBG = new javax.swing.ButtonGroup();
        buttonsToolbar = new javax.swing.JToolBar();
        colorPanel = new javax.swing.JPanel();
        colorToolbar = new javax.swing.JToolBar();
        blackButton = new javax.swing.JToggleButton();
        lightGrayButton = new javax.swing.JToggleButton();
        grayButton = new javax.swing.JToggleButton();
        whiteButtton = new javax.swing.JToggleButton();
        redButton = new javax.swing.JToggleButton();
        yellowButton = new javax.swing.JToggleButton();
        orangeButton = new javax.swing.JToggleButton();
        greenButton = new javax.swing.JToggleButton();
        cyanButton = new javax.swing.JToggleButton();
        blueButton = new javax.swing.JToggleButton();
        magentaButton = new javax.swing.JToggleButton();
        currentColorField = new javax.swing.JPanel();
        moreColorsButton = new javax.swing.JButton();
        mainToolbar = new javax.swing.JToolBar();

        setLayout(new java.awt.BorderLayout());

        buttonsToolbar.setOrientation(1);
        buttonsToolbar.setRollover(true);
        add(buttonsToolbar, java.awt.BorderLayout.LINE_START);

        colorPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Color Panel"));

        colorToolbar.setRollover(true);

        blackButton.setBackground(new java.awt.Color(0, 0, 0));
        colorBG.add(blackButton);
        blackButton.setActionCommand("0");
        blackButton.setBorderPainted(false);
        blackButton.setFocusable(false);
        blackButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        blackButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        blackButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                blackButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(blackButton);

        lightGrayButton.setBackground(java.awt.Color.lightGray);
        colorBG.add(lightGrayButton);
        lightGrayButton.setActionCommand("1");
        lightGrayButton.setBorderPainted(false);
        lightGrayButton.setFocusable(false);
        lightGrayButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        lightGrayButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        lightGrayButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                lightGrayButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(lightGrayButton);

        grayButton.setBackground(java.awt.Color.gray);
        colorBG.add(grayButton);
        grayButton.setActionCommand("2");
        grayButton.setBorderPainted(false);
        grayButton.setFocusable(false);
        grayButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        grayButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        grayButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                grayButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(grayButton);

        whiteButtton.setBackground(new java.awt.Color(255, 255, 255));
        colorBG.add(whiteButtton);
        whiteButtton.setActionCommand("3");
        whiteButtton.setBorderPainted(false);
        whiteButtton.setFocusable(false);
        whiteButtton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        whiteButtton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        whiteButtton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                whiteButttonActionPerformed(evt);
            }
        });
        colorToolbar.add(whiteButtton);

        redButton.setBackground(java.awt.Color.red);
        colorBG.add(redButton);
        redButton.setActionCommand("4");
        redButton.setBorderPainted(false);
        redButton.setFocusable(false);
        redButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        redButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        redButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                redButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(redButton);

        yellowButton.setBackground(java.awt.Color.yellow);
        colorBG.add(yellowButton);
        yellowButton.setSelected(true);
        yellowButton.setActionCommand("5");
        yellowButton.setBorderPainted(false);
        yellowButton.setFocusable(false);
        yellowButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        yellowButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        yellowButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                yellowButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(yellowButton);

        orangeButton.setBackground(java.awt.Color.orange);
        colorBG.add(orangeButton);
        orangeButton.setBorderPainted(false);
        orangeButton.setFocusable(false);
        orangeButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        orangeButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        orangeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                orangeButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(orangeButton);

        greenButton.setBackground(java.awt.Color.green);
        colorBG.add(greenButton);
        greenButton.setBorderPainted(false);
        greenButton.setFocusable(false);
        greenButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        greenButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        greenButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                greenButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(greenButton);

        cyanButton.setBackground(java.awt.Color.cyan);
        colorBG.add(cyanButton);
        cyanButton.setBorderPainted(false);
        cyanButton.setFocusable(false);
        cyanButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        cyanButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        cyanButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cyanButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(cyanButton);

        blueButton.setBackground(java.awt.Color.blue);
        colorBG.add(blueButton);
        blueButton.setBorderPainted(false);
        blueButton.setFocusable(false);
        blueButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        blueButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        blueButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                blueButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(blueButton);

        magentaButton.setBackground(java.awt.Color.magenta);
        magentaButton.setBorderPainted(false);
        magentaButton.setFocusable(false);
        magentaButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        magentaButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        magentaButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                magentaButtonActionPerformed(evt);
            }
        });
        colorToolbar.add(magentaButton);

        colorPanel.add(colorToolbar);

        currentColorField.setBorder(javax.swing.BorderFactory.createTitledBorder("Current Color"));
        currentColorField.setPreferredSize(new java.awt.Dimension(100, 25));
        colorPanel.add(currentColorField);

        moreColorsButton.setText("More Colors");
        moreColorsButton.setEnabled(false);
        colorPanel.add(moreColorsButton);

        add(colorPanel, java.awt.BorderLayout.PAGE_END);

        mainToolbar.setRollover(true);
        add(mainToolbar, java.awt.BorderLayout.PAGE_START);
    }// </editor-fold>//GEN-END:initComponents

private void blackButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_blackButtonActionPerformed
    colour = new Color(0, 0, 0, 100);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_blackButtonActionPerformed

private void lightGrayButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_lightGrayButtonActionPerformed

    colour = new Color(192, 192, 192, alpha);
    textField.setForeground(colour);//GEN-LAST:event_lightGrayButtonActionPerformed
        currentColorField.setBackground(colour);
    }

private void grayButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_grayButtonActionPerformed
    colour = new Color(128, 128, 128, alpha);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_grayButtonActionPerformed

private void whiteButttonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_whiteButttonActionPerformed
    colour = new Color(255, 255, 255, alpha);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_whiteButttonActionPerformed

private void redButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_redButtonActionPerformed
    colour = new Color(255, 0, 0, 100);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_redButtonActionPerformed

private void yellowButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_yellowButtonActionPerformed
    colour = new Color(255, 255, 0, 100);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_yellowButtonActionPerformed

private void blueButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_blueButtonActionPerformed
    colour = Color.BLUE;
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_blueButtonActionPerformed

private void cyanButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cyanButtonActionPerformed
    colour = new Color(0, 255, 255, alpha);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_cyanButtonActionPerformed

private void greenButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_greenButtonActionPerformed
    colour = new Color(0, 255, 0, alpha);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);

}//GEN-LAST:event_greenButtonActionPerformed

private void orangeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_orangeButtonActionPerformed
    colour = new Color(255, 200, 0, alpha);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);

}//GEN-LAST:event_orangeButtonActionPerformed

private void magentaButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_magentaButtonActionPerformed
    colour = new Color(255, 0, 255, alpha);
    currentColorField.setBackground(colour);
    textField.setForeground(colour);
}//GEN-LAST:event_magentaButtonActionPerformed
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JToggleButton blackButton;
    private javax.swing.JToggleButton blueButton;
    private javax.swing.JToolBar buttonsToolbar;
    private javax.swing.ButtonGroup colorBG;
    private javax.swing.JPanel colorPanel;
    private javax.swing.JToolBar colorToolbar;
    private javax.swing.JPanel currentColorField;
    private javax.swing.JToggleButton cyanButton;
    private javax.swing.JToggleButton grayButton;
    private javax.swing.JToggleButton greenButton;
    private javax.swing.JToggleButton lightGrayButton;
    private javax.swing.JToggleButton magentaButton;
    private javax.swing.JToolBar mainToolbar;
    private javax.swing.JButton moreColorsButton;
    private javax.swing.JToggleButton orangeButton;
    private javax.swing.JToggleButton redButton;
    private javax.swing.JToggleButton whiteButtton;
    private javax.swing.JToggleButton yellowButton;
    // End of variables declaration//GEN-END:variables
}
