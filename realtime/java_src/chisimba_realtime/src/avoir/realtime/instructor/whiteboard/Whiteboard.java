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
package avoir.realtime.instructor.whiteboard;

/*
 * WhiteboardSurface.java
 * Although primarily this is the whiteboard surface, it also acts as a presentation
 * surface
 *
 * Created on 17 June 2008, 02:56
 */
import avoir.realtime.classroom.whiteboard.WhiteboardSurface;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.classroom.whiteboard.item.Item;
import avoir.realtime.classroom.whiteboard.item.Oval;
import avoir.realtime.classroom.whiteboard.item.Pen;
import avoir.realtime.classroom.whiteboard.item.Rect;
import avoir.realtime.classroom.whiteboard.item.Txt;
import avoir.realtime.classroom.whiteboard.item.WBLine;
import avoir.realtime.common.ImageUtil;

import avoir.realtime.common.Constants;
import avoir.realtime.common.GenerateUUID;

import avoir.realtime.common.packet.PointerPacket;
import avoir.realtime.common.packet.WhiteboardPacket;

import avoir.realtime.common.Pointer;
import java.awt.AlphaComposite;
import java.awt.BasicStroke;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.Event;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.GradientPaint;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.GraphicsEnvironment;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.awt.event.MouseEvent;
import java.awt.geom.RoundRectangle2D;
import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JLabel;
import javax.swing.JMenu;
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
 * @author  David Wafula
 */
public class Whiteboard extends WhiteboardSurface implements
        ActionListener {

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
    public static final int BRUSH_SELECT = 9;
    private static final String[] brushName = new String[9];
    private static int brush = BRUSH_MOVE;
    private static final String COMMAND_PIXEL = "pixel";
    private static final String COMMAND_XOR = "xor";
    private static final String COMMAND_LIVE = "live";
    private static final String COMMAND_ADD_IMAGE = "addimage";
    private boolean dragging;
    private int startX; //mouse cursor position on draw start
    private int startY; //mouse cursor position on draw start
    private int prevX; //last captured mouse co-ordinate pair
    private int prevY; //last captured mouse co-ordinate pair
    private Color colour = new Color(255, 255, 0);//, alpha);
    private Item selected;
    private Item selectedItem;
    private int selectedIndex = -1;
    private final float[] dash1 = {1.0f};
    protected Font defaultFont = new Font("SansSerif", 0, 17);
    private final BasicStroke dashed = new BasicStroke(1.0f,
            BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 1.0f, dash1, 0.0f);
    private Vector<WBLine> penVector = new Vector<WBLine>();
    private float strokeWidth = 5;
    private Classroom mf;
    private static final Cursor SELECT_CURSOR = new Cursor(Cursor.DEFAULT_CURSOR), DRAW_CURSOR = new Cursor(Cursor.CROSSHAIR_CURSOR);
    private Rectangle pointerSurface = new Rectangle();
    /*Color.gray, Color.lightGray, Color.white, Color.red, Color.orange, Color.yellow,
    Color.green, Color.cyan, Color.blue, Color.magenta,
    };*/ private ImageIcon penIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/paintbrush.png");
    private ImageIcon moveIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/move.png");
    private ImageIcon rectNoFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/rectangle_nofill.png");
    private ImageIcon rectFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/roundrect.png");
    private ImageIcon ovalNoFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/oval_nofill.gif");
    private ImageIcon ovalFillIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/mini_circle.png");
    private ImageIcon textIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/text.png");
    private ImageIcon lineIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/color_line.png");
    private ImageIcon deleteIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/delete.gif");
    private ImageIcon clearIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/clear.gif");
    private ImageIcon undoIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/undo.png");
    private ImageIcon selectIcon = ImageUtil.createImageIcon(this, "/icons/whiteboard/frame_edit.png");
    private ImageIcon chisimbaBanner = ImageUtil.createImageIcon(this, "/icons/chisbanner.png");
    private ImageIcon colorChooserIcon = ImageUtil.createImageIcon(this, "/icons/colors-chooser.png");
    private TButton selectButton = new TButton(selectIcon);
    private TButton moveButton = new TButton(moveIcon);
    private TButton penButton = new TButton(penIcon);
    private TButton rectNoFillButton = new TButton(rectNoFillIcon);
    private TButton rectFillButton = new TButton(rectFillIcon);
    private TButton ovalNoFillButton = new TButton(ovalNoFillIcon);
    private TButton ovalFillButton = new TButton(ovalFillIcon);
    private TButton lineButton = new TButton(lineIcon);
    private TButton textButton = new TButton(textIcon);
    private MButton colorChooserButton = new MButton(colorChooserIcon);
    private MButton deleteButton = new MButton(deleteIcon);
    private MButton clearButton = new MButton(clearIcon);
    private MButton undoButton = new MButton(undoIcon);
    private TButton handLeftButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand_left.png"));
    private TButton handRightButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand_right.png"));
    private TButton arrowUpButton = new TButton(ImageUtil.createImageIcon(this, "/icons/arrow_up.png"));
    private TButton arrowSideButton = new TButton(ImageUtil.createImageIcon(this, "/icons/arrow_side.png"));
    private JToolBar pointerToolbar = new JToolBar(JToolBar.VERTICAL);
    private boolean drawingEnabled = true;
    private Vector<Item> items = new Vector<Item>();
    private JTextField textField = new JTextField();
    private JTextField editTextField = new JTextField();
    private JPopupMenu editPopup = new JPopupMenu();
    private JPopupMenu popup = new JPopupMenu();
    private JPopupMenu whiteboardPopup = new JPopupMenu();
    private int initH;
    private int initW;
    private int last_w;
    private int last_h;
    private int init_x2;
    private int init_y2;
    private int init_x1;
    private int init_y1;
    private Rectangle ovalRect1;
    private Rectangle ovalRect2;
    private Rectangle ovalRect3;
    private Rectangle ovalRect4;
    private Graphics2D graphics;
    private Vector<WBLine> tempPenVector = new Vector<WBLine>();
    private PixelButton pixelButton = new PixelButton(strokeWidth);
    private FontMetrics metrics;
    private Item lastItem = null;
    private boolean drawStroke = false;
    private JMenuItem deleteMenuItem = new JMenuItem("Delete");
    private AlphaComposite ac =
            AlphaComposite.getInstance(AlphaComposite.SRC, 0.5f);
    private Vector<Item> pointerLocations = new Vector<Item>();
    private int pointer = Constants.WHITEBOARD;
    boolean firstSlide = false;
    private int presenterSlideIndex;
    private JToolBar toolsToolbar = new JToolBar(JToolBar.VERTICAL);
    private Vector<Item> selectedItems = new Vector<Item>();
    int currentX, currentY;
    private boolean drawSelection = false;
    int slideWidth, slideHeight;
    private boolean firstThumbNail = true;
    private boolean showThumbNails = false;
    private Vector<ThumbNail> thumbNails = new Vector<ThumbNail>();
    private int xValue = 100;
    private ThumbNail selectedThumbNail = null;
    private int spacing = 60;
    private RoundRectangle2D slidesRect;
    private int slideYOffset = 50;
    private JPopupMenu slidePreviewPopup = new JPopupMenu();
    private JLabel slidesPreviewLabel = new JLabel();
    private Point prevThumbNailArea = new Point();
    private Timer leftScrollTimer = new Timer();
    private Timer rightScrollTimer = new Timer();
    private WhiteboardUtil whiteboardUtil;
    private Timer slidePanelTimer = new Timer();
    private Image cimg = blankIcon.getImage();
    private Cursor curCircle = Toolkit.getDefaultToolkit().createCustomCursor(cimg, new Point(5, 5), "circle");
    private JMenuItem insertGraphicMenuItem = new JMenuItem("Insert Graphic");
    private JMenuItem insertPresentationMenuItem = new JMenuItem("Insert Presentation");
    private String fontName = "SansSerif";
    private int fontStyle = 0;
    private int fontSize = 22;
    private JMenu textFontMenuItem = new JMenu("Text Font - " + fontName);
    private JMenu textSizeMenuItem = new JMenu("Text Size - " + fontSize);
    private JMenu textStyleMenuItem = new JMenu("Text Style - " + getStyleName(fontStyle));
    private JMenu lineSizeMenuItem = new JMenu("Line Size - " + strokeWidth);

    public Whiteboard(Classroom mf) {
        super(mf);
        initComponents();
        this.mf = mf;
        initFont();
        setBackground(Color.white);
        this.setBorder(BorderFactory.createLineBorder(Color.BLACK, 1));
        addMouseListener(this);
        addMouseMotionListener(this);
        whiteboardUtil = new WhiteboardUtil(mf, this);
        mf.getConnector().setWhiteboardSurfaceHandler(this);
        setDrawingEnabled(true);
        ButtonGroup bg = new ButtonGroup();
        slidePreviewPopup.add(slidesPreviewLabel);
        bg.add(penButton);
        bg.add(selectButton);
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

        pointerToolbar.setPreferredSize(new Dimension(25, 25));
        pointerToolbar.add(handRightButton);
        handRightButton.addActionListener(this);
        handRightButton.setActionCommand("handRight");
        handRightButton.setToolTipText("Point right");

        insertGraphicMenuItem.addActionListener(this);
        insertGraphicMenuItem.setActionCommand("insertGraphic");
        insertPresentationMenuItem.addActionListener(this);
        insertPresentationMenuItem.setActionCommand("insertPresentation");


        colorChooserButton.addActionListener(this);
        colorChooserButton.setActionCommand("color-chooser");
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
        currentColorField.setBackground(colour);
        //createToolBar();
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
        //  repaint();

        textField.addActionListener(this);
        textField.setFont(defaultFont);
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
        whiteboardPopup.add(insertGraphicMenuItem);
        whiteboardPopup.add(insertPresentationMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(deleteMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(textFontMenuItem);
        whiteboardPopup.add(textSizeMenuItem);
        whiteboardPopup.add(textStyleMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(lineSizeMenuItem);
        pointerSurface = new Rectangle(0, 0, slideWidth, slideHeight);
    }

    public void showBanner() {
        String id = GenerateUUID.getId();
        // imgs.add(chisimbaBanner);
        addItem(new Img(120, 120, 200, 51, "", 0, id));

    }

    public JPanel getCurrentColorField() {
        return currentColorField;
    }

    public JToolBar getPointerToolbar() {
        return pointerToolbar;
    }

    public void clearThumbNails() {
        thumbNails.clear();
        xValue = (getWidth() - pointerSurface.width) / 2;
    }

    public void addThumbNail(Image img, final int index, int maxIndex, boolean newThumbnail) {

        //repaint();
        if (firstThumbNail) {
            //  xValue = (getWidth() - pointerSurface.width             ) / 2;
            firstThumbNail = false;
        }
        if (newThumbnail) {
            thumbNails.add(new ThumbNail(img, xValue, 10, 40, 32, index, Color.LIGHT_GRAY));
        }
        xValue += spacing;
    }

    public JToolBar getToolsToolbar() {

        toolsToolbar.add(moveButton);
        moveButton.setToolTipText("Used for selecting an item");
        toolsToolbar.add(selectButton);
        selectButton.setToolTipText("Draw selection");
        selectButton.setIcon(selectIcon);
        colorChooserButton.setToolTipText("Color Chooser");
        toolsToolbar.add(colorChooserButton);
        toolsToolbar.add(penButton);
        penButton.setToolTipText("Freehand");
        toolsToolbar.add(lineButton);
        lineButton.setToolTipText("Draw straight lines");
        toolsToolbar.add(rectNoFillButton);
        rectNoFillButton.setToolTipText("Draw a rectangle: Not filled");
        toolsToolbar.add(rectFillButton);
        rectFillButton.setToolTipText("Draw a rectangel: Filled");
        toolsToolbar.add(ovalNoFillButton);
        ovalNoFillButton.setToolTipText("Draw an oval: Not filled");
        toolsToolbar.add(ovalFillButton);
        ovalFillButton.setToolTipText("Draw an oval: Filled");
        toolsToolbar.add(textButton);
        textButton.setToolTipText("Add Text");
        //toolsToolbar.add(deleteButton);
        deleteButton.setToolTipText("Delete selected item");
        toolsToolbar.add(clearButton);
        clearButton.setToolTipText("Clear whiteboard");
        toolsToolbar.add(undoButton);
        undoButton.setToolTipText("Undo last action");
        penButton.addActionListener(this);
        penButton.setActionCommand("pen");

        selectButton.addActionListener(this);
        selectButton.setActionCommand("select");

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

        //moveButton.setSelected(true);
        return toolsToolbar;
    }

    public JPanel getColorPanel() {
        return colorPanel;
    }

    public JToolBar getMainToolbar() {
        createToolBar();
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

    public void setTCPClient(TCPConnector tcpClient) {
        tcpClient.setWhiteboardSurfaceHandler(this);
    }

    public Item getSelectedItem() {
        return selectedItem;
    }

    public JToolBar getColorToolbar() {
        return colorToolbar;
    }

    private boolean setSelectedItem(MouseEvent evt, Item tmp, int i) {
        if (tmp instanceof Txt) {
            Txt txt = (Txt) tmp;

            if (txt.contains(startX, startY, graphics)) {
                selected = tmp;
                selectedItem =
                        selected;
                selectedIndex =
                        i;
                if (currentSelectionArea.isEmpty()) {
                    selectedItems.clear();
                    whiteboardUtil.selectItem(tmp, selectedItems);
                }


                Font f = txt.getFont();
                fontName = f.getFamily();
                textFontMenuItem.setText("Text Font - " + fontName);
                fontSize = f.getSize();
                textSizeMenuItem.setText("Text Size - " + fontSize);

                fontStyle = f.getStyle();
                if (fontStyle == Font.PLAIN) {
                    textStyleMenuItem.setText("Font Style - PLAIN");
                }
                if (fontStyle == Font.BOLD) {
                    textStyleMenuItem.setText("Font Style - BOLD");
                }

                if (fontStyle == Font.ITALIC) {
                    textStyleMenuItem.setText("Font Style - ITALIC");
                }
                if (fontStyle == (Font.BOLD | Font.ITALIC)) {
                    textStyleMenuItem.setText("Font Style - BOLD ITALIC");
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
//                        if (mf.getMODE() == Constants.APPLET) {
                        //                          editPopup.show(mf.getSurface(), evt.getX(), evt.getY() - editTextField.getHeight());
                        //                    } else {
                        editPopup.show(this, evt.getX(), evt.getY() - editTextField.getHeight());
                        //                  }

                    }

                    editTextField.requestFocus();
                }
                repaint();
                return true;
            }

        } else if (tmp instanceof WBLine) {
            // System.out.println("**********************" + tmp);
            WBLine line = (WBLine) tmp;
            if (line.contains(startX, startY)) {
                selected = tmp;
                selectedItem =
                        selected;
                selectedIndex =
                        i;
                if (currentSelectionArea.isEmpty()) {
                    selectedItems.clear();
                    whiteboardUtil.selectItem(tmp, selectedItems);
                }
                setCursor(Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR));

                //if (tmp instanceof WBLine) {
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
                repaint();
                return true;
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
                if (currentSelectionArea.isEmpty() && !drawSelection) {
                    selectedItems.clear();
                    whiteboardUtil.selectItem(tmp, selectedItems);
                }
                Rectangle obounds = selected.getBounds();
                int xx = obounds.x;
                int yy = obounds.y;
                int ww = obounds.width;
                int hh = obounds.height;
                Rectangle r1 = new Rectangle(
                        (xx + ww) - 20, yy, 20, 20);
                Rectangle r2 = new Rectangle(
                        (xx + ww) - 20, (yy + hh) - 20, 20,
                        20);

                last_w = xx + ww;
                initW = ww;
                last_h = yy + hh;
                initH = hh;


                if (r1.contains(evt.getPoint())) {
                    setCursor(Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR));
                    last_w = xx + ww;
                    initW = ww;
                }

                if (r2.contains(evt.getPoint())) {
                    setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
                }
                repaint();
                return true;
            }

        } else {
            if (tmp.contains(startX, startY)) {

                selected = tmp;
                selectedItem =
                        selected;
                selectedIndex =
                        i;
                setCursor(Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR));
                if (currentSelectionArea.isEmpty() && !drawSelection) {
                    selectedItems.clear();
                    whiteboardUtil.selectItem(tmp, selectedItems);
                }
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
                    last_w = xx + ww;
                    initW = ww;
                }

                if (r2.contains(evt.getPoint())) {
                    setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
                }
                repaint();
                return true;

            }

        }
        return false;
    }

    private String getStyleName(int style) {
        if (style == Font.PLAIN) {
            return "PLAIN";
        }
        if (fontStyle == Font.BOLD) {
            return "BOLD";
        }

        if (fontStyle == Font.ITALIC) {
            return "ITALIC";
        }
        return "PLAIN";
    }

    public void processMousePressed(MouseEvent evt) {
        int xx = (getWidth() - pointerSurface.width) / 2;
        Rectangle rect = new Rectangle(xx, slideHeight + slideYOffset, slideWidth, 60);
        if (rect.contains(evt.getPoint())) {
            for (int i = 0; i < thumbNails.size(); i++) {
                ThumbNail thumbNail = thumbNails.elementAt(i);
                Rectangle thRect = new Rectangle(thumbNail.getX(), 20 + slideHeight + slideYOffset,
                        thumbNail.getWidth(), thumbNail.getHeight());

                if (thRect.contains(evt.getPoint())) {
                    int index = selectedIndex;
                    String slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + stripExt(mf.getSelectedFile()) + "/img" + index + ".jpg";
                    //if (mf.isWebPresent()) {
                    //  slidePath = Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId() + "/img" + index + ".jpg";
                    //}
                    mf.getSessionManager().setCurrentSlide(index, mf.getUser().isPresenter(), slidePath);
                    mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(), index, mf.getUser().isPresenter(),
                            mf.getUser().getSessionId(), mf.getUser().getUserName(),
                            true, mf.getSelectedFile(), mf.isWebPresent());

                    break;
                }
            }
            return;
        }
        int button = evt.getButton();
        if (dragging == true) {
            return;
        }

        if (button == MouseEvent.BUTTON1) {
            if (drawingEnabled) {

                whiteboardUtil.showUserModifyingWhiteboard();
                prevX = startX = evt.getX();
                prevY = startY = evt.getY();
                if (brush == BRUSH_MOVE) {
                    selected = null;
                    selectedItem =
                            null;
                    for (int i = items.size() - 1; i >
                            -1; i--) {
                        Item tmp = items.elementAt(i);
                        if (setSelectedItem(evt, tmp, i)) {
                            break;
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
                    drawTempPen();

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
                    popup.setPopupSize(300, hgt + (int) (hgt * 0.2));
                    //popup.setPreferredSize(new Dimension(100, 50));
//                    if (mf.getMODE() == Constants.APPLET) {
                    //   popup.show(mf.getSurface(), evt.getX(), evt.getY() - textField.getHeight());
                    //  } else {
                    popup.show(this, evt.getX(), evt.getY() - textField.getHeight());
                    // }

                    textField.requestFocus();
                }
                /*                if (selectedThumbNail != null) {
                int index = selectedThumbNail.getIndex();
                String slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + stripExt(mf.getSelectedFile()) + "/img" + index + ".jpg";
                mf.getSessionManager().setCurrentSlide(index, mf.isPresenter(), slidePath);

                }
                 */
            } else {
                mf.showErrorMessage("You dont have privileges to modify the whiteboard");
            }
            dragging = true;
        }

        /**
         * allow modifying of existing text through right click
         */
        if (button == MouseEvent.BUTTON3) {
            if (drawingEnabled) {
                selected = null;

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
                    return;
                }

                deleteMenuItem.setEnabled(brush == BRUSH_MOVE && selectedItem != null);
                whiteboardPopup.show(this, evt.getX(), evt.getY());
            }
        }
        //loop t
        if (!pressAreaContainsSelectedItems()) {

            currentSelectionArea = new Rectangle();
            selectedItems.clear();
            if (selectedItem != null) {

                whiteboardUtil.selectItem(selectedItem, selectedItems);
            }
        }
        repaint();
    }

    /**
     * Adds a new item
     * @param item
     */
    @Override
    public void addItem(Item item) {
        showLogo = false;
        if (item instanceof Img) {
            items.add(0, item);

        } else {
            items.addElement(item);
        }
        repaint();
    }

    @Override
    public Vector<Item> getItems() {
        return items;
    }

    @Override
    public void setItems(Vector<Item> items) {
        this.items = items;
        repaint();

    }

    @Override
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

    @Override
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

    @Override
    public void clearItems() {
        items.clear();

        repaint();

    }

    private String stripExt(String filename) {
        int index = filename.lastIndexOf(".");
        if (index > -1) {
            return filename.substring(0, index);
        }
        return filename;
    }

    private boolean pressAreaContainsSelectedItems() {

        for (int i = 0; i < selectedItems.size(); i++) {
            Item item = selectedItems.elementAt(i);
            if (item.getBounds().contains(startX, startY)) {
                return true;
            }
        }
        return false;
    }

    @Override
    public void mouseClicked(MouseEvent evt) {
        if (evt.getButton() == MouseEvent.BUTTON3) {
            //   wbpopup.show(popup, evt.getX(), evt.getY());
        }
    }

    @Override
    public void mousePressed(MouseEvent evt) {
        if (isDrawingEnabled()) {
            drawSelection = false;
            startX = evt.getX();
            startY = evt.getY();
            // if (pointerSurface.contains(xpoint)) {
            // setOwnCursor();
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
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.HAND_LEFT));

                    break;
                }
                case Constants.HAND_RIGHT: {
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.HAND_RIGHT));

                    break;
                }
                case Constants.ARROW_UP: {
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.ARROW_UP));

                    break;
                }
                case Constants.ARROW_SIDE: {
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.ARROW_SIDE));

                    break;
                }
                case Constants.WHITEBOARD: {
                    processMousePressed(evt);
                    repaint();
                    break;
                }


            }
        } else {
            mf.showErrorMessage("You dont have privileges to modify the whiteboard");
        }
        if (selectedItem == null && !whiteboardUtil.isPointerInUser()) {
            //   setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }
        repaint();
    }

    public void processMouseReleased(MouseEvent evt) {
        drawStroke = false;
        if ((dragging == false) || (evt.getButton() != MouseEvent.BUTTON1)) {
            return;
        }

        dragging = false;
        if (drawingEnabled) {
            whiteboardUtil.showUserStoppedModifyingWhiteboard();
            if (brush != BRUSH_PEN) {
                commitStroke(startX, startY, prevX, prevY, true);
            } else if (brush == BRUSH_PEN) {
                Pen pen = new Pen(penVector, colour,
                        strokeWidth);
                mf.getConnector().sendPacket(
                        new WhiteboardPacket(mf.getUser().getSessionId(), pen, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                addItem(pen);
                //     selectedItem = pen;
  /*              penVector.removeAllElements();
                tempPenVector.removeAllElements();
                penVector =
                null;
                 */
            } else if (brush == BRUSH_MOVE) {
                if (selected != null) {
                    if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR)) {

                        if (!(selectedItem instanceof WBLine)) {
                            Item newItem = selected.getTranslated(prevX - startX, prevY - startY);
                            selectedItem.setIndex(selectedIndex);

                            newItem.setIndex(selectedIndex);
                            mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                    newItem, Constants.REPLACE_ITEM, selected.getId()));
                            replaceItem(newItem);

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
                                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                        item, Constants.REPLACE_ITEM, selected.getId()));

                                replaceItem(item);
                                //  mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                //        item, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));

                            }
                        }

                    }
                }
            }
        }
        setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        repaint();
    }

    private void paintSelection(Graphics2D g2) {
        /*  if (drawSelection && selectedItem == null) {
        Rectangle rect = new Rectangle(startX, startY, currentX - startX, currentY - startY);
        setCurrentSelectionArea(rect);
        g2.draw(rect);
        }
         */
    }

    @Override
    public void mouseReleased(MouseEvent evt) {
        drawSelection = false;
        if (pointer == Constants.WHITEBOARD) {
            processMouseReleased(evt);
            repaint();
        }
        /*if (selectedItem == null) {
        java.awt.Point pt = new java.awt.Point(evt.getX(), evt.getY());
        mf.getSurfaceScrollPane().getViewport().setViewPosition(pt);
        mf.getSurfaceScrollPane().repaint();
        repaint();
        }*/
        if (brush != BRUSH_TEXT) {
            brush = BRUSH_MOVE;
            moveButton.setSelected(true);
        }
        setCursor(DRAW_CURSOR);
    }

    private void fixPenTransparency() {
        if (brush == BRUSH_PEN) {
            alpha = 255;
        } else {
            alpha = 100;
        }
    }

    public void processMouseDragged(MouseEvent evt) {
        drawStroke = true;

        if (dragging == false) {
            return;
        }
        int x = evt.getX();
        int y = evt.getY();
        if (drawingEnabled) {
            showLogo = false;
            if (brush == BRUSH_PEN) {
                drawSelection = false;
                penVector.addElement(new WBLine(prevX, prevY, x, y, colour,
                        strokeWidth));
                tempPenVector =
                        penVector;
                lastItem = new Pen(penVector, colour, strokeWidth);

                drawTempPen();

            } else if (brush == BRUSH_MOVE) {

                if (selected != null) {
                    if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR)) {
                        for (int i = 0; i < selectedItems.size(); i++) {
                            selectedItem = selectedItems.elementAt(i);
                            selected = selectedItem;
                            if (selectedItem instanceof WBLine) {
                                WBLine line = (WBLine) selected;
                                int w = evt.getX() - startX;
                                int h = evt.getY() - startY;
                                /*   line.x1 = (init_x1 + w);
                                line.y1 = (init_y1 + h);
                                line.x2 = (init_x2 + w);
                                line.y2 = (init_y2 + h);
                                 */
                                Item newItem = line.getTranslated(x - startX, y - startY);
                                selectedItem = newItem;
                                selectedItem.setIndex(selectedIndex);

                                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                        selectedItem, Constants.REPLACE_ITEM, selected.getId()));

                                replaceItem(selectedItem);

                            } else {
                                Item newItem = selected.getTranslated(x - startX, y - startY);
                                selectedItem = newItem;
                                selectedItem.setIndex(selectedIndex);
                                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                        selectedItem, Constants.REPLACE_ITEM, selected.getId()));

                                replaceItem(selectedItem);
                            }
                        }
                        repaint();
                        return;
                    }

                    if ((this.getCursor() == Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR)) ||
                            (this.getCursor() == Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR))) {
                        //   drawSelection=false;
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
                                Item newItem = selectedItem.getTranslated(0, 0);
                                selectedItem.setIndex(selectedIndex);
                                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                        newItem, Constants.REPLACE_ITEM, selectedItem.getId()));

                            }
                        } else {
                            int changeW = evt.getX() - last_w;
                            int changeH = evt.getY() - last_h;
                            int newW = initW + changeW;
                            int newH = initH + changeH;
                            if ((newW > 10) && (newH > 10)) {
                                selectedItem.setSize(newW, newH);
                                Item newItem = selectedItem.getTranslated(0, 0);
                                selectedItem.setIndex(selectedIndex);
                                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                                        newItem, Constants.REPLACE_ITEM, selectedItem.getId()));


                            }
                        }
                    }
                } else {
                    //just draw the selection
                }
            }
            prevX = x;
            prevY = y;
        }

        repaint();


    }

    public WhiteboardUtil getWhiteboardUtil() {
        return whiteboardUtil;
    }

    public void setwhiteboardUtil(WhiteboardUtil whiteboardUtil) {
        this.whiteboardUtil = whiteboardUtil;
    }

    private void printPointer() {
        switch (pointer) {
            case Constants.HAND_LEFT: {
                System.out.println("hand left");
                break;
            }
            case Constants.HAND_RIGHT: {
                System.out.println("hand right");
                break;
            }
            case Constants.ARROW_UP: {
                System.out.println("arrow up");
                break;
            }
            case Constants.ARROW_SIDE: {
                System.out.println("arrow side");
                break;
            }
            case Constants.WHITEBOARD: {
                System.out.println("whiteboard");

                break;
            }
        }

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

    @Override
    public void mouseDragged(MouseEvent evt) {
        if (drawingEnabled) {
            // printPointer();
            currentX = evt.getX();
            currentY = evt.getY();

            drawSelection = true;
            int xOffset = evt.getX() - pointerSurface.x;
            int yOffset = evt.getY() - pointerSurface.y;
            Point point = new Point(xOffset, yOffset);
            switch (pointer) {

                case Constants.HAND_LEFT: {
                    drawSelection = false;
                    setOwnCursor();
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.HAND_LEFT));
                    setCurrentPointer(Constants.HAND_LEFT, point);

                    break;
                }
                case Constants.HAND_RIGHT: {
                    drawSelection = false;
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.HAND_RIGHT));
                    setCurrentPointer(Constants.HAND_RIGHT, point);
                    setOwnCursor();
                    break;
                }
                case Constants.ARROW_UP: {
                    drawSelection = false;
                    setCurrentPointer(Constants.ARROW_UP, point);
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.ARROW_UP));
                    setOwnCursor();
                    break;
                }
                case Constants.ARROW_SIDE: {
                    drawSelection = false;
                    setCurrentPointer(Constants.ARROW_SIDE, point);
                    mf.getConnector().sendPacket(new PointerPacket(mf.getUser().getSessionId(), point, Constants.ARROW_SIDE));
                    setOwnCursor();
                    break;
                }
                case Constants.WHITEBOARD: {
                    // setCursor(SELECT_CURSOR);

                    processMouseDragged(evt);

                    repaint();
                    break;
                }
            }
        } else {
            mf.showErrorMessage("You dont have privileges to modify the whiteboard");
        }
        if (selectedItem == null && !whiteboardUtil.isPointerInUser()) {
            // setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
            scrollOnDrag(evt);
        }
    }

    @Override
    public void setOwnCursor() {
        setCursor(curCircle);
    }

    @Override
    public void processMouseMoved(MouseEvent evt) {
        if (ovalRect1 != null) {
            Rectangle r1 = ovalRect2;//new Rectangle((xx + ww) - 20,
            //yy, 20, 20);
            Rectangle r2 = ovalRect3;// new Rectangle((xx + ww) - 20,
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

        if (slideHeight > 0) {
            int xx = (getWidth() - pointerSurface.width) / 2;
            Rectangle rect = new Rectangle(xx, slideHeight + slideYOffset, slideWidth, 60);
            if (rect.contains(evt.getPoint())) {
                checkIfShouldScrollThumbNails(evt);
                slidePanelTimer.cancel();
                showThumbNails = true;
            } else {
                slidePanelTimer.cancel();
                slidePanelTimer = new Timer();
                slidePanelTimer.schedule(new SlidePanelAnimator(), 2000);
                slidePreviewPopup.setVisible(false);
            }
            repaint();

        }

    }

    /**
     * Scrolls to the right..BUT make sure only one timer is being used
     *
     */
    private void scrollRight() {
        rightScrollTimer.cancel();
        rightScrollTimer = new Timer();
        rightScrollTimer.scheduleAtFixedRate(new Scroller(10), 0, 100);
    }

    /**
     * scrolls  the thumbnails to the left..again, mae sure only one timer is
     * in use
     */
    private void scrollLeft() {
        leftScrollTimer.cancel();
        leftScrollTimer = new Timer();
        leftScrollTimer.scheduleAtFixedRate(new Scroller(-10), 0, 100);
    }

    @Override
    public void mouseMoved(MouseEvent evt) {
        processMouseMoved(evt);
    }

    @Override
    public void keyPressed(KeyEvent evt) {
        if (evt.getKeyCode() == Event.DELETE) {

            deleteSelectedItem();
        }
    }

    private class SlidePanelAnimator extends TimerTask {

        public void run() {
            showThumbNails = false;
            repaint();

        }
    }

    private class Scroller extends TimerTask {

        int c = 0;
        int dx;

        public Scroller(int dx) {
            this.dx = dx;
        }

        public void run() {

            for (int i = 0; i < thumbNails.size(); i++) {
                ThumbNail th = thumbNails.elementAt(i);
                th.setX(th.getX() + dx);
                if (i == 0) {
                    if (th.getX() > slidesRect.getX() + 10) {
                        cancel();
                    }
                }
                /*
                if(i == thumbNails.size() - 1){
                if(th.getX()+100 > slidesRect.getX()+slidesRect.getWidth()){
                cancel();
                }
                }*/

            }
            if (c > 10) {
                cancel();
            }
            c++;
            repaint();
        }
    }

    @Override
    public void setPointer(int type) {
        pointer = type;
        pointerLocations.clear();
        setCurrentPointer(pointer);
    }

    public void deleteSelectedItem() {
        if (selectedItem != null) {
            mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                    selectedItem, Constants.REMOVE_ITEM, selected.getId()));
            removeItem(selectedItem);
        }
    }

    public void actionPerformed(ActionEvent evt) {

        if (evt.getActionCommand().equals("insertPresentation")) {
            mf.getMenuManager().insertPresentation();
        }
        if (evt.getActionCommand().equals("insertGraphic")) {
            mf.getMenuManager().insertGraphic();
        }
        if (evt.getActionCommand().equals("color-chooser")) {
            WBColorChooser.createAndShowGUI(this);
        }
        if (evt.getActionCommand().equals("handLeft")) {

            setPointer(Constants.HAND_LEFT);
        }
        if (evt.getActionCommand().equals("handRight")) {
            setPointer(Constants.HAND_RIGHT);
        }
        if (evt.getActionCommand().equals("arrowUp")) {
            setPointer(Constants.ARROW_UP);
        }
        if (evt.getActionCommand().equals("arrowSide")) {
            setPointer(Constants.ARROW_SIDE);
        }
        if (evt.getActionCommand().equals("paintBrush")) {
            setPointer(Constants.PAINT_BRUSH);
        }
        if (evt.getActionCommand().equals("whiteBoard")) {
            setPointer(Constants.WHITEBOARD);
        }
        if (evt.getActionCommand().equals("delete")) {
            deleteSelectedItem();

        }
        if (evt.getActionCommand().equals("clear")) {
            if (drawingEnabled) {
                int n = JOptionPane.showConfirmDialog(this, "This action will clear whiteboard items.", "Clear", JOptionPane.YES_NO_OPTION);
                if (n == JOptionPane.YES_OPTION) {
                    clearWhiteboard();
                }
            } else {
                mf.showErrorMessage("You dont have privileges to modify the whiteboard");
            }
        }
        if (evt.getActionCommand().equals("undo")) {
            if (lastItem != null) {
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(),
                        lastItem, Constants.REMOVE_ITEM, lastItem.getId()));
            }
            repaint();
        }

        if (evt.getActionCommand().equals("line")) {
            colour = new Color(255, 255, 0);
            brush = BRUSH_LINE;
        }



        if (evt.getActionCommand().equals("move")) {
            colour = new Color(255, 255, 0);
            brush = BRUSH_MOVE;
            setPointer(Constants.WHITEBOARD);
            // mf.getSurface().setPointer(Constants.NO_POINTER);
        }

        if (evt.getActionCommand().equals("select")) {
            brush = BRUSH_SELECT;
            selectedItems.clear();
            currentSelectionArea = new Rectangle();
            setPointer(Constants.WHITEBOARD);
            repaint();
            colour = new Color(255, 255, 0, 80);
        }
        if (evt.getActionCommand().equals("pen")) {
            colour = new Color(255, 255, 0);
            setPointer(Constants.WHITEBOARD);
            brush = BRUSH_PEN;
        }

        if (evt.getActionCommand().equals("rect_nofill")) {
            colour = new Color(255, 255, 0);
            setPointer(Constants.WHITEBOARD);
            brush = BRUSH_RECT;
        }

        if (evt.getActionCommand().equals("rect_fill")) {
            setPointer(Constants.WHITEBOARD);
            colour = new Color(255, 255, 0);
            brush = BRUSH_RECT_FILLED;
        }

        if (evt.getActionCommand().equals("oval_fill")) {
            setPointer(Constants.WHITEBOARD);
            colour = new Color(255, 255, 0);
            brush = BRUSH_OVAL_FILLED;
        }

        if (evt.getActionCommand().equals("oval_nofill")) {
            setPointer(Constants.WHITEBOARD);
            colour = new Color(255, 255, 0);
            brush = BRUSH_OVAL;
        }

        if (evt.getActionCommand().equals("texttool")) {
            setPointer(Constants.WHITEBOARD);
            colour = new Color(255, 255, 0);
            brush = BRUSH_TEXT;
        }

        if (evt.getActionCommand().equals("text")) {

            commitStroke(startX, startY, prevX, prevY, true);
            popup.setVisible(false);
        }


        if (evt.getActionCommand().equals("edit")) {

            if (selectedItem != null) {
                if (selectedItem instanceof Txt) {
                    Txt old = (Txt) selectedItem;
                    Rectangle r = old.getRect(graphics);
                    Txt txt = new Txt(r.x, r.y, editTextField.getForeground(), editTextField.getText(),
                            new Font((String) fontName, fontStyle, fontSize),
                            false);

                    mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), txt, Constants.REPLACE_ITEM, old.getId()));

                    editPopup.setVisible(false);
                    selectedItem = null;
                    repaint();
                }
            }
        }

    }

    public static int getBrush() {
        return brush;
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
                WBLine line = new WBLine(x1 - pointerSurface.x, y1 - pointerSurface.y, x2, y2, colour,
                        strokeWidth);
                mf.getConnector().sendPacket(
                        new WhiteboardPacket(mf.getUser().getSessionId(), line, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = line;
                //selectedItem = lastItem;
                addItem(lastItem);
                break;

            case BRUSH_RECT:
                Rect rect = new Rect(x - pointerSurface.x, y - pointerSurface.y, w, h, colour, false,
                        strokeWidth);
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), rect, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = rect;
                // selectedItem = lastItem;
                addItem(lastItem);
                break;

            case BRUSH_OVAL:
                Oval oval = new Oval(x - pointerSurface.x, y - pointerSurface.y, w, h, colour, false,
                        strokeWidth);
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), oval, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = oval;
                //  selectedItem = lastItem;
                addItem(lastItem);
                break;

            case BRUSH_RECT_FILLED:
                Rect filledRect = new Rect(x - pointerSurface.x, y - pointerSurface.y, w, h, colour, true,
                        strokeWidth);
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), filledRect, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = filledRect;
                //selectedItem = lastItem;
                addItem(lastItem);
                break;

            case BRUSH_SELECT:
                Rect selectRect = new Rect(x - pointerSurface.x, y - pointerSurface.y, w, h, colour, true,
                        strokeWidth);
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), selectRect, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = selectRect;
                //selectedItem = lastItem;
                addItem(lastItem);
                break;

            case BRUSH_OVAL_FILLED:
                Oval filledOval = new Oval(x - pointerSurface.x, y - pointerSurface.y, w, h, colour, true,
                        strokeWidth);
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), filledOval, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = filledOval;
                // selectedItem = lastItem;
                addItem(lastItem);
                break;

            case BRUSH_TEXT:
                Txt txt = new Txt(x - pointerSurface.x, y - pointerSurface.y, textField.getForeground(), textField.getText(),
                        new Font((String) fontName, fontStyle, fontSize),
                        false);
                mf.getConnector().sendPacket(new WhiteboardPacket(mf.getUser().getSessionId(), txt, Constants.ADD_NEW_ITEM, GenerateUUID.getId()));
                lastItem = txt;
                //  selectedItem = lastItem;
                addItem(lastItem);
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

        return new Font(fontName,
                fontStyle, fontSize);
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

    /**
     * Create the tool bar.
     */
    public void createToolBar() {
        try {
            mainToolbar.setBorder(BorderFactory.createEtchedBorder());


            pixelButton.addActionListener(this);
            pixelButton.setActionCommand(COMMAND_PIXEL);
            pixelButton.setSize(81, 27);
            /**
             * probably not the best place to place the pixel button..but now with the
             * toolbar layout...where else?
             */
            mainToolbar.add(pixelButton);
            JPanel p = new JPanel();
            p.setLayout(new BorderLayout());
//            p.add(fontSizeField, BorderLayout.WEST);
            p.setPreferredSize(new Dimension(18, 25));
            mainToolbar.add(p);
            mainToolbar.setEnabled(false);

            whiteboardUtil.createPixelOptionFrame(strokeWidth, pixelButton);

        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    private JMenuItem createFontNameMenuItem(String txt) {
        final JMenuItem item = new JMenuItem(txt);
        item.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                fontName = item.getText();
                textFontMenuItem.setText("Text Font - " + fontName);
            }
        });
        return item;
    }

    private JMenuItem createFontSizeMenuItem(String txt) {
        final JMenuItem item = new JMenuItem(txt);
        item.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                fontSize = Integer.parseInt(item.getText());
                textSizeMenuItem.setText("Text Size - " + fontSize);
            }
        });
        return item;
    }

    private JMenuItem createLineSizeMenuItem(String txt) {
        final JMenuItem item = new JMenuItem(txt);
        item.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                strokeWidth = Integer.parseInt(item.getText());
                lineSizeMenuItem.setText("Line Size - " + strokeWidth);
            }
        });
        return item;
    }

    private JMenuItem createFontStyle(String txt) {
        final JMenuItem item = new JMenuItem(txt);
        item.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                String val = item.getText();
                if (val.equals("PLAIN")) {
                    fontStyle = 0;
                    textStyleMenuItem.setText("Text Font - PLAIN");
                }
                if (val.equals("BOLD")) {
                    fontStyle = 1;
                    textStyleMenuItem.setText("Text Font - BOLD");

                }
                if (val.equals("ITALIC")) {
                    fontStyle = 2;
                    textStyleMenuItem.setText("Text Font - ITALIC");

                }
                if (val.equals("BOLD ITALIC")) {
                    fontStyle = 3;
                    textStyleMenuItem.setText("Text Font - BOLD ITALIC");

                }
            }
        });
        item.setFont(new Font(fontName, fontStyle, 12));

        return item;
    }

    private void initFont() {
        textStyleMenuItem.add(createFontStyle("PLAIN"));
        textStyleMenuItem.add(createFontStyle("BOLD"));
        textStyleMenuItem.add(createFontStyle("ITALIC"));
        textStyleMenuItem.add(createFontStyle("BOLD ITALIC"));
        for (int i = 8; i <
                50; i++) {

            textSizeMenuItem.add(createFontSizeMenuItem(i + ""));
        }
        for (int i = 1; i <
                9; i++) {

            lineSizeMenuItem.add(createLineSizeMenuItem(i + ""));
        }

        //  fontSizeField.setSelectedItem("22");
        GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
        String[] fontFamilies = {"Monospaced", "Dialog", "SansSerif", "System", "Serif"}; //ge.getAvailableFontFamilyNames();
        for (int i = 0; i <
                fontFamilies.length; i++) {
            textFontMenuItem.add(createFontNameMenuItem(fontFamilies[i]));
        }

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
                int xx = (getWidth() - pointerSurface.width) / 2;
                slidesRect = new RoundRectangle2D.Double(xx - 10, slideHeight + slideYOffset, slideWidth + 20, 60, 40, 40);

            }

        }
        graphics = g2;
        if (XOR) {
            g2.setXORMode(getBackground());
        }
        int xx = (getWidth() - pointerSurface.width) / 2;
        int yy = (getHeight() - pointerSurface.height) / 2;

        /*   if (showLogo) {

        g2.drawImage(introLogo, xx + 50, yy + 50, (int) (pointerSurface.width * 0.8), (int) (pointerSurface.height * 0.8), this);
        }
         */
        g2.setColor(Color.BLACK);
        //g2.drawRect(xx, yy, pointerSurface.width, pointerSurface.height);

        paintSlides(g2);
        drawStroke(g2);
        if (image != null) {
            graphics.drawImage(image.getImage(), 100, 100, this);
        }
        whiteboardUtil.paintCurrentItem(g2, selectedItem, imgs);
        whiteboardUtil.paintItems(g2, items, dashed, dragging, selectedItem, pointerSurface, imgs, ovalRect1, ovalRect2, ovalRect3, ovalRect4);

        paintSelection(g2);
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
        if (super.currentPointer != null) {
            /* graphics.drawImage(currentPointer.getIcon().getImage(),
            (int) pointerSurface.x + currentPointer.getPoint().x - 10,
            (int) pointerSurface.y + currentPointer.getPoint().y - 10, this);
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
        if (showThumbNails && thumbNails.size() > 0 && brush == BRUSH_MOVE) {
            paintThumbNails(g2, xx);
        }

    }

    private void checkIfShouldScrollThumbNails(MouseEvent evt) {

        for (int i = 0; i < thumbNails.size(); i++) {
            ThumbNail thumbNail = thumbNails.elementAt(i);
            Rectangle rect = new Rectangle(thumbNail.getX(), 20 + slideHeight + slideYOffset,
                    thumbNail.getWidth(), thumbNail.getHeight());

            if (rect.contains(evt.getPoint())) {
                if (!prevThumbNailArea.equals(evt.getPoint())) {
                    prevThumbNailArea = evt.getPoint();
                    slidePreviewPopup.setVisible(false);
                    selectedThumbNail = thumbNail;
                    selectedIndex = i;
                    if ((selectedThumbNail.getX() + 120) > slidesRect.getX() + slidesRect.getWidth()) {
                        scrollLeft();
                        break;
                    }
                    if ((selectedThumbNail.getX() - 60) < slidesRect.getX() && selectedThumbNail.index > 0) {
                        scrollRight();
                        break;
                    }
                    int index = selectedIndex;
                    String slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + stripExt(mf.getSelectedFile()) + "/img" + index + ".jpg";

                    if (mf.isWebPresent()) {
                        slidePath = Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId() + "/img" + index + ".jpg";

                    }
                    Image img = whiteboardUtil.getScaledImage(new ImageIcon(slidePath).getImage(), 250, 200);
                    slidesPreviewLabel.setIcon(new ImageIcon(img));
                    if (!slidePreviewPopup.isVisible()) {
                        slidePreviewPopup.show(this, selectedThumbNail.getX(), slideHeight - 150);
                    }
                }
                break;
            }
        }
        repaint();

    }

    private void paintThumbNails(Graphics2D g2, int xx) {
        g2.setColor(new Color(0, 0, 0));//, 100));

        RoundRectangle2D rect = new RoundRectangle2D.Double(xx - 10, slideHeight + slideYOffset, slideWidth + 20, 60, 40, 40);
        GradientPaint gp = new GradientPaint(0, 0, Color.BLUE, slideWidth, 0, Color.BLACK);
        //g2.setPaint(gp);
        g2.setColor(Color.BLACK);
        g2.fill(rect);

        g2.setStroke(new BasicStroke(4));
        g2.setColor(Color.RED);
        g2.drawRoundRect(xx - 10, slideHeight + slideYOffset, slideWidth + 20, 60, 40, 40);
        g2.setStroke(new BasicStroke());
        for (int i = 0; i < thumbNails.size(); i++) {
            ThumbNail th = thumbNails.elementAt(i);
            Color c = Color.WHITE;
            if (selectedThumbNail != null) {
                if (th.getIndex() == selectedThumbNail.getIndex()) {
                    c = Color.GREEN;
                }
            }

            if (th.getX() > rect.getX() && th.getX() < rect.getX() + rect.getWidth() - spacing) {
                g2.drawImage(th.getImage(), th.getX(), slideHeight + slideYOffset + 10, th.getWidth(), th.getHeight(), this);
                g2.setColor(c);
                g2.drawRect(th.getX(), slideHeight + slideYOffset + 10, th.getWidth(), th.getHeight());

                g2.drawString((i + 1) + "", th.getX(), slideHeight + slideYOffset + th.getHeight() + 20);
            }
        }
        g2.setColor(Color.BLACK);
    }

    public Vector<Item> getSelectedItems() {
        return selectedItems;
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">
    private void initComponents() {

        mainToolbar = new javax.swing.JToolBar();
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

        mainToolbar.setRollover(true);

        buttonsToolbar.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        buttonsToolbar.setOrientation(1);
        buttonsToolbar.setRollover(true);
        buttonsToolbar.setPreferredSize(new java.awt.Dimension(4, 25));

        colorPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Color Panel"));

        colorToolbar.setOrientation(1);
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

        setLayout(new java.awt.BorderLayout());
    }// </editor-fold>

    private void blackButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        //colour = new Color(0, 0, 0, alpha);
        //if (brush == BRUSH_PEN) {
        colour =
                new Color(0, 0, 0);
        //}
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void lightGrayButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        //colour = new Color(192, 192, 192, alpha);
        //if (brush == BRUSH_PEN) {
        colour =
                new Color(192, 192, 192);
        //}
        textField.setForeground(colour);
        currentColorField.setBackground(colour);
    }

    private void grayButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        //colour = new Color(128, 128, 128, alpha);
        //if (brush == BRUSH_PEN) {
        colour =
                new Color(128, 128, 128);
        //}
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void whiteButttonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        //colour = new Color(255, 255, 255, alpha);
        //if (brush == BRUSH_PEN) {
        colour =
                new Color(255, 255, 255);
        //}
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void redButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        //colour = new Color(255, 0, 0, alpha);
        // if (brush == BRUSH_PEN) {
        colour =
                new Color(255, 0, 0);
        //}
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void yellowButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        colour = new Color(255, 255, 0, alpha);
        if (brush == BRUSH_PEN) {
            colour =
                    new Color(255, 255, 0);
        }
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void blueButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        if (brush == BRUSH_PEN) {
            colour =
                    new Color(0, 0, 255);
        } else {
            colour = new Color(0, 0, 255, alpha);
        }
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void cyanButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        colour = new Color(0, 255, 255, alpha);
        if (brush == BRUSH_PEN) {
            colour =
                    new Color(0, 255, 255);
        }
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }

    private void greenButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        colour = new Color(0, 255, 0, alpha);
        if (brush == BRUSH_PEN) {
            colour =
                    new Color(0, 255, 0);
        }
        currentColorField.setBackground(colour);
        textField.setForeground(colour);

    }

    private void orangeButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        if (brush == BRUSH_PEN) {
            colour =
                    new Color(255, 200, 0);
        }
        colour = new Color(255, 200, 0, alpha);

        currentColorField.setBackground(colour);
        textField.setForeground(colour);

    }

    private void magentaButtonActionPerformed(java.awt.event.ActionEvent evt) {
        fixPenTransparency();
        if (brush == BRUSH_PEN) {
            colour =
                    new Color(255, 0, 255);
        } else {
            colour = new Color(255, 0, 255, alpha);
        }
        currentColorField.setBackground(colour);
        textField.setForeground(colour);
    }    // Variables declaration - do not modify
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
    // End of variables declaration
}
