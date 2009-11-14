/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.whiteboard;

import java.awt.BasicStroke;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.GridLayout;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.InputEvent;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseWheelEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.event.MouseWheelListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.awt.geom.Line2D;
import java.awt.geom.Point2D;
import java.awt.geom.RoundRectangle2D;
import java.awt.geom.AffineTransform;
import java.awt.geom.NoninvertibleTransformException;
import java.awt.image.BufferedImage;
import java.util.ArrayList;
import java.util.List;
import javax.swing.BorderFactory;
import javax.swing.Icon;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JCheckBox;
import javax.swing.JCheckBoxMenuItem;
import javax.swing.JColorChooser;
import javax.swing.JDialog;
import javax.swing.JFrame;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JScrollPane;

import javax.swing.JTextField;
import javax.swing.KeyStroke;
import javax.swing.SwingConstants;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.PointerListPanel;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.whiteboard.items.Img;
import org.avoir.realtime.gui.whiteboard.items.Item;
import org.avoir.realtime.gui.whiteboard.items.Line;

import org.avoir.realtime.gui.whiteboard.items.Oval;
import org.avoir.realtime.gui.whiteboard.items.Pen;
import org.avoir.realtime.gui.whiteboard.items.Rect;
import org.avoir.realtime.gui.whiteboard.items.Text;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import static org.avoir.realtime.common.Constants.Whiteboard.*;

/**
 *
 * @author developer
 */
public class Whiteboard extends JPanel implements MouseListener, MouseMotionListener,
        MouseWheelListener,
        KeyListener,
        ActionListener {

    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private java.text.DecimalFormat df = new java.text.DecimalFormat("#.####");
    private ArrayList<Item> items = new ArrayList<Item>();
    private int startX, startY;
    private boolean dragging = false;
    private int ITEM_TYPE = PEN;
    private Item currentItem = null;
    private Item selectedItem = null;
    private Item currentSelectedItem = null;
    private Item tempSelectedItem = null;
    int selectOffset = 4;
    private Color currentColor = new Color(0, 0, 0);
    private WhiteboardPanel whiteboardPanel;
    int initW, initH, lastW, lastH, prevX, prevY;
    private ArrayList<Line2D.Double> points = new ArrayList<Line2D.Double>();
    private boolean defaultRoom = true;
    int width = 200;
    int height = 200;
    private ImageIcon arrowUP = ImageUtil.createImageIcon(this, "/images/pointer-arrow_up.png");
    private ImageIcon arrowSide = ImageUtil.createImageIcon(this, "/images/pointer-arrow_side.png");
    private ImageIcon handLeft = ImageUtil.createImageIcon(this, "/images/pointer-hand_left.png");
    private ImageIcon handRight = ImageUtil.createImageIcon(this, "/images/pointer-hand_right.png");
    private int currentPointer = PointerListPanel.NO_POINTER;
    private Image currentPointerImage;
    private int currentPointerX, currentPointerY;
    private boolean firstTime = true;
    private Rectangle whiteboardSize = new Rectangle(600, 600);
    private boolean gotSize = false;
    private boolean sendNoPointer = false;
    private String prevImageId = null;
    private String fontName = "SansSerif";
    private int fontStyle = 0;
    private int fontSize = 22;
    private JMenuItem clearMenuItem = new JMenuItem("Clear Whiteboard");
    private JMenuItem removeResourceMenuItem = new JMenuItem("Remove This Resource");
    private JMenuItem deleteMenuItem = new JMenuItem("Delete");
    private JMenuItem insertGraphicMenuItem = new JMenuItem("Insert Graphic");
    private JMenuItem insertPresentationMenuItem = new JMenuItem("Insert Presentation");
    private JPopupMenu whiteboardPopup = new JPopupMenu();
    private String textFontStr = "Text Font -";
    private String textSizeStr = "Text Size -";
    private String textStyleStr = "Text Style -";
    private String scaleStr = "Scale Slide -";
    private JMenu textFontMenuItem = new JMenu();
    private JMenu textSizeMenuItem = new JMenu();
    private JMenu textStyleMenuItem = new JMenu();
    private float strokeWidth = 5;
    private JMenu lineSizeMenuItem = new JMenu("Line Size - " + strokeWidth);
    private JMenuItem colorMenuItem = new JMenuItem("Color");
    private JMenu scaleSlideMenuItem = new JMenu("Scale Slide - Off");
    private JMenuItem zoomInMenuItem = new JMenuItem("Zoom -");
    private JMenuItem zoomOutMenuItem = new JMenuItem("Zoom +");
    private boolean RESIZE = false;
    private ImageIcon slideImage;
    private String slideText;
    private Color slideTextColor;
    private int slideTextSize;
    private JPopupMenu textPopup = new JPopupMenu();
    private JTextField textField = new JTextField(20);
    private String textFontName = "dialog";
    private int textFontStyle = Font.PLAIN;
    private int textFontSize = 17;
    private Graphics2D graphics2D;
    private String resizeType;
    private String textMode = "add";
    private boolean drawEnabled = false;
    private String infoMessage = "";
    private boolean showInfoMessage = false;
    private Image webSnapshot = null;
    private FontMetrics fm;
    private boolean showGrid = true;
    private JCheckBoxMenuItem showGridMenuItem = new JCheckBoxMenuItem("Show Grid", showGrid);
    private ColorIcon colorIcon = new ColorIcon(currentColor);
    private ImageIcon eraseIcon = ImageUtil.createImageIcon(this, "/images/whiteboard/erase_all_annotations.gif");
    Cursor eraseC = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(eraseIcon.getImage(),
            new java.awt.Point(5, 5), "circle");
    private ImageIcon writePen = ImageUtil.createImageIcon(this, "/images/whiteboard/draw_Scribble.gif");
    Cursor writeC = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(writePen.getImage(),
            new java.awt.Point(5, 5), "circle");
    private ImageIcon moveIc = ImageUtil.createImageIcon(this, "/images/whiteboard/movearrow.gif");
    Cursor moveC = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(moveIc.getImage(),
            new java.awt.Point(5, 5), "circle");
    private String[] styles = {"PLAIN", "BOLD", "ITALIC", "BOLD ITALIC"};
    private int[] styleVal = {0, 1, 2, 3};
    private ArrayList<ThumbNail> thumbNails = new ArrayList<ThumbNail>();
    private float scaleSlideFactor = 100;
    private JColorChooser chooser = new JColorChooser(currentColor);
    private boolean scale = false;
    private boolean fitWBSize = false;
    private boolean scaleOff = false;
    private Grid grid = new Grid();
    private double zoomFactor = 1.0;
    private static final int SIDE = 64;
    private BufferedImage image = new BufferedImage(SIDE, SIDE, BufferedImage.TYPE_INT_RGB);
    private JCheckBoxMenuItem zoomOpt = new JCheckBoxMenuItem("Zoom");
    public boolean zoomEnabled = false;
    private JDialog zoomControl;
    public double translateX = 0;
    public double translateY = 0;
    ZoomListener zoomlistener;
    private boolean initWB = true;
    private int fullScreenX = (int) ss.getWidth();
    private int fullScreenY = (int) ss.getHeight();
    public boolean fullScreen = false;
    private JFrame fullScreenFrame;
    private boolean showingToolbox = false;
    private JDialog toolboxDlg;
    public static final double DEFAULT_ZOOM_MULTIPLICATION_FACTOR = 0.1;
    private double zoomMultiplicationFactor = DEFAULT_ZOOM_MULTIPLICATION_FACTOR;
    private Point dragStartScreen;
    private Point dragEndScreen;
    private AffineTransform coordTransform = new AffineTransform();
    private boolean chatBoxShowing = false;
    private JDialog chatBox;
    private JDialog toolsDialog;
    private boolean firstTimeFullscreenMouePresss = true;
    private boolean autoShowToolsDialog = true;
    private JCheckBox autoShowToolsDialogOpt = new JCheckBox("Auto show");

    public Whiteboard(WhiteboardPanel whiteboardPanel) {

        this.whiteboardPanel = whiteboardPanel;
        grid.setGrid(50, 50);
        setBackground(Color.WHITE);
        addMouseListener(this);
        addMouseMotionListener(this);
        deleteMenuItem.setActionCommand("delete");
        deleteMenuItem.addActionListener(this);
        deleteMenuItem.setEnabled(false);
        whiteboardPopup.add(insertGraphicMenuItem);
        insertGraphicMenuItem.addActionListener(this);
        insertGraphicMenuItem.setActionCommand("insertgraphic");
        insertPresentationMenuItem.addActionListener(this);
        insertPresentationMenuItem.setActionCommand("insertPresentation");
        showGridMenuItem.setActionCommand("showgrid");
        showGridMenuItem.addActionListener(this);
        whiteboardPopup.add(insertPresentationMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(deleteMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(textFontMenuItem);
        whiteboardPopup.add(textSizeMenuItem);
        whiteboardPopup.add(textStyleMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(lineSizeMenuItem);
        autoShowToolsDialogOpt.setSelected(true);
        autoShowToolsDialogOpt.setActionCommand("autoshowtoolsdialog");
        autoShowToolsDialogOpt.addActionListener(this);

        for (float i = 0; i < 20.0; i += 0.5) {
            JMenuItem sizeItem = new JMenuItem(i + "");
            sizeItem.addActionListener(this);
            sizeItem.setActionCommand("strokeWidth");
            lineSizeMenuItem.add(sizeItem);
        }

        for (int i = 6; i < 100; i += 2) {
            JMenuItem sizeItem = new JMenuItem(i + "");
            sizeItem.addActionListener(this);
            sizeItem.setActionCommand("textsize");
            textSizeMenuItem.add(sizeItem);
        }
        for (String style : styles) {
            JMenuItem sizeItem = new JMenuItem(style);
            sizeItem.addActionListener(this);
            sizeItem.setActionCommand("textstyle");

            textStyleMenuItem.add(sizeItem);
        }
        String[] fontNames = {"Dialog", "SansSerif"};
        for (String xfontName : fontNames) {
            JMenuItem sizeItem = new JMenuItem(xfontName);
            sizeItem.addActionListener(this);
            sizeItem.setActionCommand("textfont");
            textFontMenuItem.add(sizeItem);
        }
        whiteboardPopup.add(colorMenuItem);
        textField.setFont(new Font(fontName, fontStyle, fontSize));
        textField.setOpaque(false);

        textField.setBorder(BorderFactory.createEmptyBorder());
        textField.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                sendText();
            }
        });

        colorMenuItem.setIcon(colorIcon);
        colorMenuItem.setHorizontalTextPosition(SwingConstants.TRAILING);
        colorMenuItem.addActionListener(this);
        colorMenuItem.setActionCommand("color");
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(scaleSlideMenuItem);
        whiteboardPopup.add(showGridMenuItem);
        whiteboardPopup.addSeparator();
        whiteboardPopup.add(clearMenuItem);
        whiteboardPopup.add(removeResourceMenuItem);

        //whiteboardPopup.addSeparator();
        // whiteboardPopup.add(zoomInMenuItem);
        // whiteboardPopup.add(zoomOutMenuItem);
        //whiteboardPopup.add(zoomOpt);

        //zoomOpt.addActionListener(this);
        //zoomOpt.setActionCommand("zoom");

        //zoomInMenuItem.addActionListener(this);
        //zoomInMenuItem.setActionCommand("zoomin");

        //zoomOutMenuItem.addActionListener(this);
        //zoomOutMenuItem.setActionCommand("zoomout");

        clearMenuItem.addActionListener(this);
        clearMenuItem.setActionCommand("clear");

        removeResourceMenuItem.addActionListener(this);
        removeResourceMenuItem.setActionCommand("removeRoomResource");
        int[] scales = {10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 120, 150, 200};
        JMenuItem scaleOffitem = new JMenuItem("Off");
        scaleOffitem.addActionListener(this);
        scaleOffitem.setActionCommand("scale-slide");
        scaleSlideMenuItem.add(scaleOffitem);

        JMenuItem fitWBSizeItem = new JMenuItem("Fit Whiteboard Size");
        fitWBSizeItem.addActionListener(this);
        fitWBSizeItem.setActionCommand("fit-wb-size");
        scaleSlideMenuItem.add(fitWBSizeItem);
        for (int xscale : scales) {
            JMenuItem item = new JMenuItem(xscale + " %");
            item.addActionListener(this);
            item.setActionCommand("scale-slide");
            scaleSlideMenuItem.add(item);
        }

        textPopup.setOpaque(false);
        JScrollPane sp = new JScrollPane(textField);
        sp.setOpaque(false);
        sp.getViewport().setOpaque(true);
        textPopup.add(textField);
        addKeyListener(this);
        refreshPopup();
        repaint();

        fullScreenFrame = new JFrame();
        fullScreenFrame.setBounds(0, 0, fullScreenX, fullScreenY);
        fullScreenFrame.setUndecorated(true);
        fullScreenFrame.addKeyListener(this);
        addMouseWheelListener(this);

    }

    private void refreshPopup() {
        textFontMenuItem.setText(textFontStr + textFontName);
        textSizeMenuItem.setText(textSizeStr + textFontSize);
        textStyleMenuItem.setText(textStyleStr + getStyleName(textFontStyle));
        scaleSlideMenuItem.setText(scaleStr + (scaleSlideFactor + "%"));

    }

    public boolean isDrawEnabled() {
        return drawEnabled;
    }

    public void setDrawEnabled(boolean drawEnabled) {
        this.drawEnabled = drawEnabled;
    }

    private void sendText() {
        if (textMode.equals("add")) {
            if (textField.getText().trim().equals("")) {
                return;
            }
            Text text = new Text(startX, startY, textField.getText());
            text.setGraphic(this.getGraphics());
            text.setFontSize(textFontSize);
            text.setFontStyle(textFontStyle);
            text.setFontName(textFontName);
            text.setRed(currentColor.getRed());
            text.setGreen(currentColor.getGreen());
            text.setBlue(currentColor.getBlue());
            textField.setText("");
            sendItem(text, "new");
            textPopup.setVisible(false);
            textMode = "add";
        }
        if (textMode.equals("edit")) {

            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.UPDATE_TEXT_ITEM);
            StringBuilder sb = new StringBuilder();
            sb.append("<item-content>");
            sb.append("<text-content>").append(textField.getText()).append("</text-content>");
            sb.append("<text-id>").append(tempSelectedItem.getId()).append("</text-id>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("</item-content>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
            textPopup.setVisible(false);
            textField.setText("");
            textMode = "add";

        }
    }

    private int getFontStyle(String val) {
        int rval = 0;
        for (int i = 0; i < styles.length; i++) {
            if (styles[i].equals(val)) {
                return styleVal[i];
            }
        }
        return rval;
    }

    public String getSlideText() {
        return slideText;

    }

    public void setSlideText(String slideText) {
        this.slideText = slideText;
        repaint();
    }

    public Color getSlideTextColor() {
        return slideTextColor;
    }

    public void setSlideTextColor(Color slideTextColor) {
        this.slideTextColor = slideTextColor;
        repaint();
    }

    public int getSlideTextSize() {
        return slideTextSize;
    }

    public void setSlideTextSize(int slideTextSize) {
        this.slideTextSize = slideTextSize;
        repaint();
    }

    public ImageIcon getSlideImage() {
        return slideImage;
    }

    public void setSlideImage(ImageIcon slideImage) {
        this.slideImage = slideImage;
        repaint();
    }

    public boolean isFullScreen() {
        return fullScreen;
    }

    private void showZoomDialog(boolean show) {
        if (zoomControl == null) {
            zoomControl = new JDialog(GUIAccessManager.mf, "Zoom", false);
            zoomControl.addWindowListener(new WindowAdapter() {

                @Override
                public void windowClosing(WindowEvent e) {
                    zoomOpt.setSelected(false);
                    zoomOriginal();
                    zoomEnabled = false;
                    translateX = 0;
                    translateY = 0;
                }
            });
            ZoomListener zoomListener = new ZoomListener();
            JButton zoomInButton = new JButton("-");
            zoomInButton.addMouseListener(zoomListener);
            zoomInButton.setActionCommand("zoomin");

            JButton zoomOutButton = new JButton("+");
            zoomOutButton.addMouseListener(zoomListener);
            zoomOutButton.setActionCommand("zoomout");


            JButton scrollLeftButton = new JButton("<");
            scrollLeftButton.addMouseListener(zoomListener);
            scrollLeftButton.setActionCommand("scrollleft");

            JButton scrollRightButton = new JButton(">");
            scrollRightButton.addMouseListener(zoomListener);
            scrollRightButton.setActionCommand("scrollright");

            JButton scrollUpButton = new JButton("Up");
            scrollUpButton.addMouseListener(zoomListener);
            scrollUpButton.setActionCommand("scrollup");

            JButton scrollDownButton = new JButton("Down");
            scrollDownButton.addMouseListener(zoomListener);
            scrollDownButton.setActionCommand("scrolldown");

            JPanel p = new JPanel(new GridLayout(3, 3));
            p.add(zoomInButton);
            p.add(zoomOutButton);
            p.add(scrollLeftButton);
            p.add(scrollRightButton);
            p.add(scrollUpButton);
            p.add(scrollDownButton);
            zoomControl.add(p);
            //zoomControl.setSize(100, 50);
            zoomControl.pack();
            zoomControl.setLocation((ss.width / 2), ss.height / 2);
        }
        zoomControl.setVisible(show);

    }

    class ZoomListener implements MouseListener, MouseMotionListener, MouseWheelListener, KeyListener {
        //class variable declaration

        //constructor
        public ZoomListener() {
        }

        public void mouseClicked(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
        }

        public void mouseExited(MouseEvent e) {
        }

        public void mousePressed(MouseEvent e) {
            dragStartScreen = e.getPoint();
            dragEndScreen = null;
            /*
            JButton b = (JButton) e.getSource();
            if (b.getActionCommand().equals("zoomin")) {
            zoomOut();
            }
            if (b.getActionCommand().equals("zoomout")) {
            zoomIn();
            }
            if (b.getActionCommand().equals("scrolldown")) {
            translateY += 10;
            repaint();
            }
            if (b.getActionCommand().equals("scrollup")) {
            translateY -= 10;
            repaint();
            }
            if (b.getActionCommand().equals("scrollright")) {
            translateX += 10;
            repaint();
            }
            if (b.getActionCommand().equals("scrollleft")) {
            translateX -= 10;
            repaint();
            }
             */
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseMoved(MouseEvent e) {
        }

        public void mouseDragged(MouseEvent e) {
            moveCamera(e);
        }

        public void mouseWheelMoved(MouseWheelEvent e) {
            zoomCamera(e);
        }

        public void keyPressed(KeyEvent e) {
            if (e.getKeyCode() == KeyEvent.VK_PAGE_UP) {
                zoomOut();
            }
            if (e.getKeyCode() == KeyEvent.VK_PAGE_DOWN) {
                zoomIn();
            }
        }

        public void keyReleased(KeyEvent e) {
        }

        public void keyTyped(KeyEvent e) {
        }
    }

    private void moveCamera(MouseEvent e) {
        try {
            dragEndScreen = e.getPoint();
            Point2D.Float dragStart = transformPoint(dragStartScreen);
            Point2D.Float dragEnd = transformPoint(dragEndScreen);
            double dx = dragEnd.getX() - dragStart.getX();
            double dy = dragEnd.getY() - dragStart.getY();
            translateX = dragEndScreen.getX() - dragStartScreen.getX();
            translateY = dragEndScreen.getY() - dragStartScreen.getY();
            coordTransform.translate(translateX, translateY);
            dragStartScreen = dragEndScreen;
            dragEndScreen = null;
            repaint();
        } catch (NoninvertibleTransformException ex) {
            ex.printStackTrace();
        }

    }

    private void zoomCamera(MouseWheelEvent e) {
        zoomEnabled = true;
        try {
            int wheelRotation = e.getWheelRotation();
            Point p = e.getPoint();
            if (wheelRotation > 0) {
                Point2D p1 = transformPoint(p);
                Point2D p2 = transformPoint(p);
                //translateX = p.getX();// - p1.getX();
                //translateY = p.getY();// - p1.getY();
                zoomFactor -= zoomMultiplicationFactor;
                //coordTransform.translate(translateX, translateY);
                if (zoomFactor < 0.5) {
                    zoomFactor += zoomMultiplicationFactor;
                }
                //coordTransform.scale(1/zoomMultiplicationFactor, 1/zoomMultiplicationFactor);
                repaint();
            } else {
                Point2D p1 = transformPoint(p);
                Point2D p2 = transformPoint(p);
                //translateX = p.getX();// - p1.getX();
                //translateY = p.getY();// - p1.getY();
                //coordTransform.scale(zoomMultiplicationFactor, zoomMultiplicationFactor);
                zoomFactor += zoomMultiplicationFactor;
                //coordTransform.translate(translateX, translateY);
                if (zoomFactor > 4.5) {
                    zoomFactor -= zoomMultiplicationFactor;
                }
                repaint();
            }
        } catch (NoninvertibleTransformException ex) {
            ex.printStackTrace();
        }
    }

    private Point2D.Float transformPoint(Point p1) throws NoninvertibleTransformException {
        AffineTransform inverse = coordTransform.createInverse();
        Point2D.Float p2 = new Point2D.Float();
        inverse.transform(p1, p2);
        return p2;
    }

    public AffineTransform getTransform() {
        return coordTransform;
    }

    public void setCoordTransform(AffineTransform coordTransform) {
        this.coordTransform = coordTransform;
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("autoshowtoolsdialog")) {
            autoShowToolsDialog = autoShowToolsDialogOpt.isSelected();
        }
        if (e.getActionCommand().equals("zoom")) {

            zoomEnabled = zoomOpt.isSelected();
            showZoomDialog(zoomEnabled);

            repaint();
        }
        if (e.getActionCommand().equals("showgrid")) {
            showGrid = showGridMenuItem.isSelected();
            repaint();
        }
        if (e.getActionCommand().equals("fit-wb-size")) {
            fitWBSize = true;
            scale = false;
            repaint();

        }
        if (e.getActionCommand().equals("removeRoomResource")) {
            GUIAccessManager.mf.getWebPresentNavigator().removeRoomResource();
        }

        if (e.getActionCommand().equals("clear")) {
            clearWhiteboard();
        }
        if (e.getActionCommand().equals("insertPresentation")) {
            GUIAccessManager.mf.insertPresentation();
        }
        if (e.getActionCommand().equals("scale-slide")) {

            JMenuItem item = (JMenuItem) e.getSource();
            String text = item.getText();
            if (text.toLowerCase().indexOf("off") > -1) {
                fitWBSize = false;
                scale = false;
                scaleOff = true;
            } else {
                int index = text.indexOf("%");
                try {
                    text = text.substring(0, index).trim();
                    scaleSlideFactor = Float.parseFloat(text.trim());
                    scale = true;
                } catch (Exception ex) {
                }
                scaleOff = false;
            }
            refreshPopup();
            repaint();
        }
        if (e.getActionCommand().equals("delete")) {
            if (tempSelectedItem != null) {
                sendDeleteBroadcast();
            }
        }
        if (e.getActionCommand().equals("color")) {
            //  colorChooserDialog.setVisible(true);
            currentColor = JColorChooser.showDialog(this, "Select Color", currentColor);
            colorIcon = new ColorIcon(currentColor);
            colorMenuItem.setIcon(colorIcon);
            textField.setForeground(currentColor);
            if (currentSelectedItem != null) {
                sendItem(currentSelectedItem, "edit");
            }
        }
        if (e.getActionCommand().equals("strokeWidth")) {
            JMenuItem item = (JMenuItem) e.getSource();
            strokeWidth = Float.parseFloat(item.getText().trim());

            refreshPopup();
            if (currentSelectedItem != null) {
                sendItem(currentSelectedItem, "edit");
            }
        }
        if (e.getActionCommand().equals("textsize")) {
            JMenuItem item = (JMenuItem) e.getSource();
            textFontSize = Integer.parseInt(item.getText().trim());
            textField.setFont(new Font(textFontName, textFontStyle, textFontSize));
            textSizeMenuItem.setText("Text Size - " + textFontSize);
            refreshPopup();
            if (currentSelectedItem != null) {
                sendItem(currentSelectedItem, "edit");
            }
        }
        if (e.getActionCommand().equals("textstyle")) {
            JMenuItem item = (JMenuItem) e.getSource();
            textFontStyle = getFontStyle(item.getText());
            textField.setFont(new Font(textFontName, textFontStyle, textFontSize));
            textFontMenuItem.setText("Text Font -" + getStyleName(textFontStyle));
            refreshPopup();
            if (currentSelectedItem != null) {
                sendItem(currentSelectedItem, "edit");
            }
        }
        if (e.getActionCommand().equals("textfont")) {
            JMenuItem item = (JMenuItem) e.getSource();
            textFontName = item.getText();
            textField.setFont(new Font(textFontName, textFontStyle, textFontSize));
            refreshPopup();
            if (currentSelectedItem != null) {
                sendItem(currentSelectedItem, "edit");
            }
        }
        if (e.getActionCommand().equals("insertgraphic")) {
            GUIAccessManager.mf.showImageFileChooser();
        }
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

    public boolean isDefaultRoom() {
        return defaultRoom;
    }

    public int getCurrentPointer() {
        return currentPointer;
    }

    public void setCurrentPointer(int currentPointer) {
        zoomEnabled = false;
        this.currentPointer = currentPointer;
        sendNoPointer = false;
        ITEM_TYPE = MOVE;
        textPopup.setVisible(false);
        textField.setText("");

        paintCurrentPointer();
        repaint();
    }

    public void paintPointer(int pointer, int x, int y) {
        currentPointerX = x;
        currentPointerY = y;
        firstTime = false;
        if (pointer == PointerListPanel.ARROW_UP) {
            currentPointerImage = arrowUP.getImage();
        } else if (pointer == PointerListPanel.ARROW_SIDE) {
            currentPointerImage = arrowSide.getImage();

        } else if (pointer == PointerListPanel.HAND_LEFT) {

            currentPointerImage = handLeft.getImage();

        } else if (pointer == PointerListPanel.HAND_RIGHT) {
            currentPointerImage = handRight.getImage();
        } else if (pointer == PointerListPanel.NO_POINTER) {
            currentPointerImage = null;
            currentPointerX = 0;
            currentPointerY = 0;

        }
        repaint();
    }

    private void paintCurrentPointer() {
        if (currentPointer == PointerListPanel.ARROW_UP) {

            Cursor curCircle = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(arrowUP.getImage(),
                    new java.awt.Point(5, 5), "circle");
            setCursor(curCircle);

        } else if (currentPointer == PointerListPanel.ARROW_SIDE) {

            Cursor curCircle = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(arrowSide.getImage(),
                    new java.awt.Point(5, 5), "circle");
            setCursor(curCircle);

        } else if (currentPointer == PointerListPanel.HAND_LEFT) {

            Cursor curCircle = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(handLeft.getImage(),
                    new java.awt.Point(5, 5), "circle");
            setCursor(curCircle);

        } else if (currentPointer == PointerListPanel.HAND_RIGHT) {

            Cursor curCircle = java.awt.Toolkit.getDefaultToolkit().createCustomCursor(handRight.getImage(),
                    new java.awt.Point(5, 5), "circle");
            setCursor(curCircle);

        } else if (currentPointer == PointerListPanel.NO_POINTER) {
            if (ITEM_TYPE == ERASE) {
                setCursor(eraseC);
                /*} else if (ITEM_TYPE == MOVE) {
                setCursor(moveC);
                } else if (ITEM_TYPE == LINE) {
                setCursor(writeC);

                } else if (ITEM_TYPE == DRAW_OVAL) {
                setCursor(writeC);
                } else if (ITEM_TYPE == FILL_OVAL) {
                setCursor(writeC);
                } else if (ITEM_TYPE == DRAW_RECT) {
                setCursor(writeC);
                } else if (ITEM_TYPE == FILL_OVAL) {
                setCursor(writeC);
                } else if (ITEM_TYPE == PEN) {
                setCursor(writeC);
                 */
            } else {
                setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
            }
        }
    }

    public void setDefaultRoom(boolean defaultRoom) {
        this.defaultRoom = defaultRoom;
        width = (getWidth() / 4) * 3;
        height = (getHeight() / 4) * 3;

        repaint();
    }

    public void addItem(Item item) {
        GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
        synchronized (items) {
            items.add(item);
            firstTime = false;
            repaint();
        }
    }

    public void resizeItem(int x, int y, String id, String type) {
        synchronized (items) {
            GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
            for (int i = 0; i < items.size(); i++) {
                Item item = items.get(i);
                if (item.getId().equals(id)) {
                    if (type.equals("NW")) {
                        item.northWestResize(x, y);
                    } else if (type.equals("NE")) {
                        item.northEastResize(x, y);
                    } else if (type.equals("SE")) {
                        item.southEastResize(x, y);
                    } else if (type.equals("SW")) {
                        item.southWestResize(x, y);
                    }
                    repaint();
                    break;
                }
            }
        }
    }

    public void updateItemPostion(int x, int y, String id) {
        synchronized (items) {
            GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
            for (int i = 0; i < items.size(); i++) {
                Item item = items.get(i);
                if (item.getId().equals(id)) {
                    item.setPosition(x, y);
                    repaint();
                    break;
                }
            }
        }
    }

    public void updateItem(Item updatedItem) {
        synchronized (items) {
            GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
            for (int i = 0; i < items.size(); i++) {
                Item item = items.get(i);
                if (item.getId().equals(updatedItem.getId())) {
                    items.set(i, updatedItem);
                    repaint();
                    break;
                }
            }
        }
    }

    public void updateText(String content, String id) {
        synchronized (items) {
            GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
            for (int i = 0; i < items.size(); i++) {
                Item item = items.get(i);
                if (item.getId().equals(id)) {
                    Text text = (Text) item;
                    text.setContent(content);
                    items.set(i, text);
                    repaint();
                    break;
                }
            }
        }
    }

    public void updateItemPostion(int x1, int y1, int x2, int y2, String id) {
        GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
        synchronized (items) {
            for (int i = 0; i < items.size(); i++) {
                Item item = items.get(i);
                if (item.getId().equals(id)) {
                    item.setPosition(x1, y1, x2, y2);
                    repaint();
                    break;
                }
            }
        }
    }

    public String getInfoMessage() {
        return infoMessage;
    }

    public void setInfoMessage(String infoMessage) {
        this.infoMessage = infoMessage;
        repaint();
    }

    public boolean isShowInfoMessage() {
        return showInfoMessage;
    }

    public void setShowInfoMessage(boolean showInfoMessage) {
        this.showInfoMessage = showInfoMessage;
        repaint();
    }

    public void deleteItem(String id) {
        synchronized (items) {
            for (int i = 0; i < items.size(); i++) {
                Item item = items.get(i);
                if (item.getId().equals(id)) {
                    items.remove(i);
                    currentSelectedItem = null;
                    repaint();
                    break;
                }
            }
        }
    }

    public Image getWebSnapshot() {
        return webSnapshot;
    }

    public void setWebSnapshot(Image webSnapshot) {
        this.webSnapshot = webSnapshot;
    }

    public void addImage(ImageIcon image, String id) {
        if (prevImageId == null) {
            prevImageId = id;
            Img img = new Img(10, 10, image.getIconWidth(), image.getIconHeight(), image.getImage(), id);
            addItem(img);
        } else {
            if (!prevImageId.equals(id)) {
                prevImageId = id;
                Img img = new Img(10, 10, image.getIconWidth(), image.getIconHeight(), image.getImage(), id);
                addItem(img);
            }
        }
    }

    public void setItem(Item item) {
        synchronized (items) {
            for (int i = 0; i < items.size(); i++) {
                if (items.get(i).getId().equals(item.getId())) {
                    items.set(i, item);
                }
            }
        }
    }

    public void setItemType(int type) {
        //ensure only draw on whiteboard
        GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);
        ITEM_TYPE = type;
        if (ITEM_TYPE != MOVE) {
            currentSelectedItem = null;
        }
    }

    public Graphics2D getGraphics2D() {
        return graphics2D;
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);

        int ww = (int) (getWidth());
        int hh = (int) (getHeight());
        int xx = (ww - 600) / 2;
        int yy = (hh - 600) / 2;

        whiteboardSize = new Rectangle(0, 0, ww, hh);
        gotSize = false;
        Graphics2D g2 = (Graphics2D) g;

        if (initWB && !zoomEnabled) {
            if (zoomlistener != null) {
                //  zoomlistener.setCoordTransform(g2.getTransform());
            }
            initWB = false;
        }
        if (zoomEnabled) {
            //edit here!!
            g2.setTransform(getTransform());
            g2.scale(zoomFactor, zoomFactor);
            g2.translate(translateX, translateY);

        }
        if (showGrid) {
            grid.draw(g2, new Rectangle(0, 0, fullScreenX, fullScreenY));
        }
        graphics2D = g2;
        if (fullScreen) {
            g2.drawString("Press Esc to exit", 0, fullScreenY - 20);
        }
        g2.setColor(Color.WHITE);
        g2.draw(whiteboardSize);
        g2.setColor(Color.BLACK);
        if (slideImage != null) {
            firstTime = false;
            xx = ((int) (whiteboardSize.width) - slideImage.getIconWidth()) / 2;
            yy = ((int) (whiteboardSize.height) - slideImage.getIconHeight()) / 2;
            if (xx < 10) {
                xx = 10;
            }
            if (yy < 10) {
                yy = 10;
            }
            /* if (scale) {
            g2.drawImage(slideImage.getImage(), 20, 70, (int) (whiteboardSize.width * scaleSlideFactor / 100), (int) (whiteboardSize.height * scaleSlideFactor / 100), this);
            g2.drawRoundRect(15, 65, ((int) (whiteboardSize.width * scaleSlideFactor / 100) + 10), ((int) (whiteboardSize.height * scaleSlideFactor / 100) + 10), 10, 10);
            } else if (fitWBSize) {
             */
            //g2.drawImage(slideImage.getImage(), 0, 0, getWidth() - 10, getHeight() - 10, this);
            //g2.drawRoundRect(3, 3, getWidth() - 6, getHeight() - 6, 10, 10);
            //  } else if (scaleOff) {
            g2.drawImage(slideImage.getImage(), xx, yy, this);
            g2.drawRoundRect(xx - 5, yy - 5, slideImage.getIconWidth() + 10, slideImage.getIconHeight() + 10, 10, 10);
            /*} else {
            g2.drawImage(slideImage.getImage(), xx, yy, this);
            g2.drawRoundRect(xx - 5, yy - 5, slideImage.getIconWidth() + 10, slideImage.getIconHeight() + 10, 10, 10);
            }*/
        }
        if (firstTime) {
            //  g2.drawImage(GUIAccessManager.mf.getLogo().getImage(), 100, 100, this);
        }

        synchronized (items) {
            for (Item item : items) {

                g2.setColor(item.getColor());
                g2.setStroke(new BasicStroke(item.getStrokeWidth()));
                if (item instanceof Img) {
                    Img img = (Img) item;
                    g2.drawImage(img.getImage(), img.getX(), img.getY(), img.getWidth(), img.getHeight(), this);
                } else {

                    item.render(g2);
                }
            }
        }
        g2.setStroke(new BasicStroke());
        if (currentItem != null) {
            g2.setStroke(new BasicStroke(strokeWidth));

            g2.setColor(currentColor);
            currentItem.render(g2);
        }
        if (currentSelectedItem != null) {
            Rectangle bounds = currentSelectedItem.getBounds();

            Rectangle r1 = new Rectangle(bounds.x - selectOffset, bounds.y - selectOffset, selectOffset * 2, selectOffset * 2);
            Rectangle r2 = new Rectangle(bounds.x + bounds.width - selectOffset, bounds.y + bounds.height - selectOffset, selectOffset * 2, selectOffset * 2);
            Rectangle r3 = new Rectangle(bounds.x + bounds.width - selectOffset, bounds.y - selectOffset, selectOffset * 2, selectOffset * 2);
            Rectangle r4 = new Rectangle(bounds.x - selectOffset, bounds.y + bounds.height - selectOffset, selectOffset * 2, selectOffset * 2);
            g2.setColor(Color.RED);
            if (currentSelectedItem instanceof Line) {
                Line line = (Line) tempSelectedItem;
                List<Point2D> pts = line.getSelectionPoints();
                r1 = new Rectangle((int) pts.get(0).getX() - selectOffset, (int) pts.get(0).getY() - selectOffset, selectOffset * 2, selectOffset * 2);
                r2 = new Rectangle((int) pts.get(1).getX() - selectOffset, (int) pts.get(1).getY() - selectOffset, selectOffset * 2, selectOffset * 2);
                g2.fill(r1);
                g2.fill(r2);
            } else {
                g2.fill(r1);
                g2.fill(r2);
                g2.fill(r3);
                g2.fill(r4);
            }
            g2.setColor(Color.BLACK);

        }
        if (currentPointerImage != null) {
            g2.drawImage(currentPointerImage, whiteboardSize.x + currentPointerX, whiteboardSize.y + currentPointerY, this);

        }
        if (showInfoMessage) {
            g2.setFont(new Font("Dialog", 1, 17));
            FontMetrics fm = g2.getFontMetrics();
            int msgWidth = fm.stringWidth(infoMessage);
            int msgHeight = fm.getHeight();
            g2.setColor(Color.WHITE);
            g2.fill(new RoundRectangle2D.Double(100, 20, msgWidth, msgHeight, 5, 5));
            g2.setColor(Color.ORANGE);
            g2.draw(new RoundRectangle2D.Double(100, 20, msgWidth, msgHeight, 5, 5));
            g2.drawString(infoMessage, 100, 20 + msgHeight);
        }
        if (webSnapshot != null) {
            g2.drawImage(webSnapshot, 0, 0, this);
        }
        revalidate();
    }

    public void zoomIn() {
        zoomFactor += 0.1;
        repaint();
    }

    public void zoomOut() {
        zoomFactor -= 0.1;
        /*
        if (imgZoom <= zoom) {
        if (zoom >= 1.0) {
        imgZoom = 1.0;
        } else {
        zoomIn(zoom);
        }
        }*/
        repaint();
    }

    public void zoomOriginal() {
        zoomFactor = 1.0;
        repaint();
    }

    public void zoomPan() {
        zoomEnabled = true;
        zoomlistener = new ZoomListener();
        addMouseListener(zoomlistener);
        addMouseMotionListener(zoomlistener);
        addMouseWheelListener(zoomlistener);
        addKeyListener(zoomlistener);
    }

    public void setFullScreen() {
        fullScreen = true;
        fullScreenFrame.add(whiteboardPanel);
        fullScreenFrame.setVisible(true);
        repaint();

    }

    public JFrame getFullScreenFrame() {
        return fullScreenFrame;
    }

    public void unSetFullScreen() {
        fullScreenFrame.remove(whiteboardPanel);
        fullScreenFrame.setVisible(false);
        GUIAccessManager.mf.getTabbedPane().insertTab("Whiteboard", null, whiteboardPanel, "Whiteboard", 0);
        GUIAccessManager.mf.getUserListPanel().resetAlerterField();
        if (showingToolbox) {
            showingToolbox = false;
            GUIAccessManager.mf.getSurfacePanel().add(GUIAccessManager.mf.getSurfaceTopTabbedPane(), BorderLayout.NORTH);

        }
        fullScreen = false;
        repaint();

    }

    public void mouseClicked(MouseEvent evt) {

        if (evt.getButton() == MouseEvent.BUTTON3) {

            insertGraphicMenuItem.setEnabled(drawEnabled);
            insertPresentationMenuItem.setEnabled(drawEnabled);
            clearMenuItem.setEnabled(drawEnabled);
            deleteMenuItem.setEnabled(drawEnabled);
            textFontMenuItem.setEnabled(drawEnabled);
            textSizeMenuItem.setEnabled(drawEnabled);
            deleteMenuItem.setEnabled(currentSelectedItem != null);
            colorMenuItem.setEnabled(drawEnabled);
            whiteboardPopup.show(this, evt.getX(), evt.getY());
        }
    }

    public void mouseEntered(MouseEvent e) {
    }

    public void mouseExited(MouseEvent e) {
    }

    public void sendDeleteBroadcast() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.DELETE_ITEM);
        StringBuilder sb = new StringBuilder();
        sb.append("<item-id>").append(tempSelectedItem.getId()).append("</item-id>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");

        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public void broadcastPointer(MouseEvent e) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.POINTER);
        StringBuilder sb = new StringBuilder();
        double x = e.getX() / whiteboardSize.getWidth();
        double y = e.getY() / whiteboardSize.getHeight();
        sb.append("<pointer>").append(currentPointer).append("</pointer>");
        sb.append("<x>").append(x).append("</x>");
        sb.append("<y>").append(y).append("</y>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);

    }

    public void displayToolsDialog() {
        if (toolsDialog == null) {
            toolsDialog = new JDialog(GUIAccessManager.mf);
            toolsDialog.setLayout(new BorderLayout());
            toolsDialog.add(whiteboardPanel.getWbToolbar(), BorderLayout.CENTER);
            toolsDialog.add(autoShowToolsDialogOpt, BorderLayout.SOUTH);
            toolsDialog.pack();
            toolsDialog.setLocation(ss.width - toolsDialog.getWidth(), 100);
        }
        if (fullScreen) {
            if (firstTimeFullscreenMouePresss) {
                toolsDialog = new JDialog(fullScreenFrame);
                toolsDialog.setLayout(new BorderLayout());
                toolsDialog.add(whiteboardPanel.getWbToolbar(), BorderLayout.CENTER);
                toolsDialog.add(autoShowToolsDialogOpt, BorderLayout.SOUTH);
                toolsDialog.pack();
                toolsDialog.setLocation(ss.width - toolsDialog.getWidth(), 100);
                firstTimeFullscreenMouePresss = false;
            }
        }
        toolsDialog.setVisible(true);
    }

    public void mousePressed(MouseEvent e) {

        dragStartScreen = e.getPoint();
        dragEndScreen = null;

        this.requestFocusInWindow();

        if (!drawEnabled || zoomEnabled) {
            return;
        }
        if (autoShowToolsDialog) {
            displayToolsDialog();
        }
        points.clear();


        startX = e.getX();
        startY = e.getY();
        prevX = startX;
        prevY = startY;
        if (currentPointer != PointerListPanel.NO_POINTER) {
            broadcastPointer(e);
        } else {
            if (!sendNoPointer) {
                broadcastPointer(e);
                sendNoPointer = true;
            }
        }
        if (ITEM_TYPE == MOVE) {
            firstTime = false;
            boolean select = false;
            for (int i = 0; i < items.size(); i++) {
                try {
                    synchronized (items) {
                        if (items.get(i).contains(e.getPoint())) {
                            dragging = true;
                            select = true;
                            selectedItem = items.get(i);
                            currentSelectedItem = selectedItem;
                            Rectangle bounds = selectedItem.getBounds();
                            initH = bounds.height;
                            initW = bounds.width;
                            if (currentPointer != PointerListPanel.NO_POINTER) {
                                setCursor(Cursor.getPredefinedCursor(Cursor.MOVE_CURSOR));
                            }
                            tempSelectedItem = selectedItem;
                            repaint();

                        }
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
            if (!select) {
                currentSelectedItem = null;
            }
            if (tempSelectedItem != null) {
                if (tempSelectedItem.contains(e.getPoint()) && tempSelectedItem instanceof Text) {
                    if (e.getClickCount() == 2) {
                        textMode = "edit";
                        Text text = (Text) tempSelectedItem;
                        textField.setText(text.getContent());
                        textPopup.show(this, e.getX(), e.getY());
                    }
                }
            }

        } else if (ITEM_TYPE == TEXT) {
            textPopup.show(this, e.getX(), e.getY());
            textField.requestFocus();
        } else if (ITEM_TYPE == ERASE) {

            for (Item item : items) {
                if (item.contains(e.getPoint())) {
                    tempSelectedItem = currentSelectedItem = item;
                    repaint();
                    RealtimePacket p = new RealtimePacket();
                    p.setMode(RealtimePacket.Mode.DELETE_ITEM);
                    StringBuilder sb = new StringBuilder();
                    sb.append("<item-id>").append(item.getId()).append("</item-id>");
                    sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                    p.setContent(sb.toString());
                    ConnectionManager.sendPacket(p);
                }
            }
        }
    }

    public Rectangle getWhiteboardSize() {
        return whiteboardSize;
    }

    public void clearWhiteboard() {
        synchronized (items) {
            for (Item item : items) {
                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.DELETE_ITEM);
                StringBuilder sb = new StringBuilder();
                sb.append("<item-id>").append(item.getId()).append("</item-id>");
                sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);
            }
            repaint();
        }
    }

    public void undo() {
        synchronized (items) {
            int lastIndex = items.size() - 1;
            if (lastIndex > -1) {
                Item lastAddedItem = items.get(lastIndex);
                repaint();
                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.DELETE_ITEM);
                StringBuilder sb = new StringBuilder();
                sb.append("<item-id>").append(lastAddedItem.getId()).append("</item-id>");
                sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);
            }
        }
    }

    private String getCurrentItemType() {
        String type = "";
        if (tempSelectedItem instanceof Line) {
            type = "line";
        }
        if (tempSelectedItem instanceof Rect) {
            type = "rect";
        }

        if (tempSelectedItem instanceof Img) {
            type = "image";
        }
        if (tempSelectedItem instanceof Oval) {
            type = "oval";
        }

        if (tempSelectedItem instanceof Pen) {
            type = "pen";
        }
        if (tempSelectedItem instanceof Text) {
            type = "text";
        }
        return type;
    }

    public void mouseReleased(MouseEvent e) {

        if (!drawEnabled || zoomEnabled) {

            return;
        }
        paintCurrentPointer();
        dragging = false;
        currentPointerImage = null;
        if (ITEM_TYPE == MOVE && tempSelectedItem != null && !RESIZE) {
            StringBuilder sb = new StringBuilder();
            sb.append("<item-id>").append(tempSelectedItem.getId()).append("</item-id>");
            sb.append("<item-type>").append(getCurrentItemType()).append("</item-type>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            Rectangle bounds = tempSelectedItem.getBounds();
            if (getCurrentItemType().equals("line")) {
                Line line = (Line) tempSelectedItem;
                List<Point2D> pts = line.getSelectionPoints();
                double x1 = pts.get(0).getX() / whiteboardSize.getWidth();
                double y1 = pts.get(0).getY() / whiteboardSize.getHeight();
                double x2 = pts.get(1).getX() / whiteboardSize.getWidth();
                double y2 = pts.get(1).getY() / whiteboardSize.getHeight();
                sb.append("<x1>").append(x1).append("</x1>");
                sb.append("<y1>").append(y1).append("</y1>");
                sb.append("<x2>").append(x2).append("</x2>");
                sb.append("<y2>").append(y2).append("</y2>");
            } else if (getCurrentItemType().equals("pen")) {
                Pen pen = (Pen) tempSelectedItem;
                List<Point2D> pts = pen.getSelectionPoints();
                double x1 = pts.get(0).getX() / whiteboardSize.getWidth();
                double y1 = pts.get(0).getY() / whiteboardSize.getHeight();
                double x2 = pts.get(1).getX() / whiteboardSize.getWidth();
                double y2 = pts.get(1).getY() / whiteboardSize.getHeight();
                sb.append("<x1>").append(x1).append("</x1>");
                sb.append("<y1>").append(y1).append("</y1>");
                sb.append("<x2>").append(x2).append("</x2>");
                sb.append("<y2>").append(y2).append("</y2>");
            } else {
                double x = bounds.getX() / getWidth();
                double y = bounds.getY() / getHeight();
                sb.append("<x>").append(x).append("</x>");
                sb.append("<y>").append(y).append("</y>");

            }

            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.UPDATE_ITEM_POSITION);
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);

        }
        if (RESIZE) {
            StringBuilder sb = new StringBuilder();
            sb.append("<item-id>").append(tempSelectedItem.getId()).append("</item-id>");
            sb.append("<item-type>").append(getCurrentItemType()).append("</item-type>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            if (getCurrentItemType().equals("line")) {
                Line line = (Line) tempSelectedItem;
                List<Point2D> pts = line.getSelectionPoints();

                double x1 = pts.get(0).getX() / whiteboardSize.getWidth();
                double y1 = pts.get(0).getY() / whiteboardSize.getHeight();
                double x2 = pts.get(1).getX() / whiteboardSize.getWidth();
                double y2 = pts.get(1).getY() / whiteboardSize.getHeight();
                sb.append("<x1>").append(x1).append("</x1>");
                sb.append("<y1>").append(y1).append("</y1>");
                sb.append("<x2>").append(x2).append("</x2>");
                sb.append("<y2>").append(y2).append("</y2>");
            } else {

                double x = e.getX() / whiteboardSize.getWidth();
                double y = e.getY() / whiteboardSize.getHeight();

                sb.append("<x>").append(x).append("</x>");
                sb.append("<y>").append(y).append("</y>");

            }

            sb.append("<r-type>").append(resizeType).append("</r-type>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.RESIZE_ITEM);
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
            RESIZE = false;

        }
        if (ITEM_TYPE != MOVE) {
            if (ITEM_TYPE == PEN) {
                Pen pen = new Pen(points);
                pen.setStrokeWidth(3);
                currentItem = pen;
            }
            if (ITEM_TYPE == TEXT) {
            }
            sendItem(currentItem, "new");
        }
        currentItem = null;
        if (ITEM_TYPE != MOVE) {
            currentSelectedItem = null;
        }
        repaint();
    }

    public void setZoomEnabled(boolean zoomEnabled) {
        this.zoomEnabled = zoomEnabled;
    }

    public void mouseWheelMoved(MouseWheelEvent e) {
        if (ITEM_TYPE == TRANSFORM) {
            zoomCamera(e);
        }
    }

    public void mouseDragged(MouseEvent e) {
        if (ITEM_TYPE == TRANSFORM) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
            zoomEnabled = true;
            moveCamera(e);
            repaint();
            return;
        } else {
            zoomEnabled = false;
            translateX = 0;
            translateY = 0;
        }
        if (!drawEnabled) {
            return;
        }
        int endX = e.getX();
        int endY = e.getY();
        currentSelectedItem = tempSelectedItem;
        int xx, yy;
        if (ITEM_TYPE == LINE) {
            Line line = new Line("", startX, startY, endX, endY);
            line.setStrokeWidth(strokeWidth);
            currentItem = line;
            repaint();
            return;
        }

        if (ITEM_TYPE == DRAW_RECT || ITEM_TYPE == FILL_RECT) {
            int width = endX - startX;
            int height = endY - startY;

            if (width < 0) {
                xx = startX + width;
                width = Math.abs(width);
            } else {
                xx = startX;
            }
            if (height < 0) {
                yy = startY + height;
                height = Math.abs(height);
            } else {
                yy = startY;
            }
            Rect rect = new Rect(xx, yy, width, height);
            rect.setFilled(ITEM_TYPE == FILL_RECT);
            currentItem = rect;
            repaint();
            return;
        }
        if (ITEM_TYPE == DRAW_OVAL || ITEM_TYPE == FILL_OVAL) {
            int width = endX - startX;
            int height = endY - startY;

            if (width < 0) {
                xx = startX + width;
                width = Math.abs(width);
            } else {
                xx = startX;
            }
            if (height < 0) {
                yy = startY + height;
                height = Math.abs(height);
            } else {
                yy = startY;
            }
            Oval oval = new Oval(xx, yy, width, height);
            oval.setFilled(ITEM_TYPE == FILL_OVAL);
            currentItem = oval;
            repaint();
            return;
        }
        if (ITEM_TYPE == ERASE) {

            for (Item item : items) {
                if (item.contains(e.getPoint())) {
                    RealtimePacket p = new RealtimePacket();
                    p.setMode(RealtimePacket.Mode.DELETE_ITEM);
                    StringBuilder sb = new StringBuilder();
                    sb.append("<item-id>").append(item.getId()).append("</item-id>");
                    sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                    p.setContent(sb.toString());
                    ConnectionManager.sendPacket(p);
                }
            }
        }
        if (ITEM_TYPE == PEN) {
            Line2D.Double line = new Line2D.Double(prevX, prevY, e.getX(), e.getY());

            points.add(line);

            prevX = e.getX();
            prevY = e.getY();
            Pen pen = new Pen(points);
            pen.setStrokeWidth(strokeWidth);
            currentItem = pen;
            repaint();
            return;
        }
        if (ITEM_TYPE == MOVE) {

            if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.NW_RESIZE_CURSOR)) {
                tempSelectedItem.northWestResize(e.getX(), e.getY());
                RESIZE = true;
                repaint();
                return;
            }

            if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR)) {
                tempSelectedItem.northEastResize(e.getX(), e.getY());
                RESIZE = true;
                repaint();
                return;
            }
            if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR)) {
                tempSelectedItem.southEastResize(e.getX(), e.getY());
                RESIZE = true;
                repaint();
                return;
            }
            if (this.getCursor() == Cursor.getPredefinedCursor(Cursor.SW_RESIZE_CURSOR)) {
                tempSelectedItem.southWestResize(e.getX(), e.getY());
                RESIZE = true;
                repaint();
                return;
            }
            if (dragging) {

                int dx = e.getX() - startX;
                int dy = e.getY() - startY;
                Item newItem = selectedItem.translate(dx, dy);
                tempSelectedItem = newItem;
                setItem(newItem);
                repaint();
                return;
            }
            repaint();
        }
    }

    public void keyPressed(KeyEvent e) {
        KeyStroke ctrlZ = KeyStroke.getKeyStroke(KeyEvent.VK_Z,
                InputEvent.CTRL_MASK);
        if (e.getKeyCode() == ctrlZ.getKeyCode()) {
            undo();
        }
        if (e.getKeyCode() == KeyEvent.VK_F2) {
            if (!chatBoxShowing) {
                if (chatBox == null) {
                    chatBox = new JDialog(fullScreenFrame, false);
                }
                chatBox.setContentPane(GUIAccessManager.mf.getChatRoomManager().getChatRoom());
                chatBox.setSize(300, 250);
                chatBox.addWindowListener(new WindowAdapter() {

                    @Override
                    public void windowClosing(WindowEvent e) {
                        GUIAccessManager.mf.getChatTabbedPane().addTab("Chat", GUIAccessManager.mf.getChatRoomManager().getChatRoom());
                        chatBoxShowing = false;
                        if (fullScreen) {
                            fullScreenFrame.setVisible(true);
                            fullScreenFrame.toFront();
                        }
                    }
                });
                chatBox.setVisible(true);
                chatBoxShowing = true;
            }
        }
        if (e.getKeyCode() == KeyEvent.VK_DELETE) {
            if (tempSelectedItem != null) {
                sendDeleteBroadcast();
            }
        }
        if (fullScreen) {
            if (e.getKeyCode() == KeyEvent.VK_ESCAPE) {
                unSetFullScreen();
            }
        }
        if (e.getKeyCode() == KeyEvent.VK_LEFT) {
            GUIAccessManager.mf.getWebPresentNavigator().moveToPrevSlide();
        }
        if (e.getKeyCode() == KeyEvent.VK_RIGHT) {
            GUIAccessManager.mf.getWebPresentNavigator().moveToNextSlide();
        }
        if (e.getKeyCode() == KeyEvent.VK_F3) {
            GUIAccessManager.mf.showImageFileChooser();
        }

        if (e.getKeyCode() == KeyEvent.VK_F9) {
            GUIAccessManager.mf.getGlass().setVisible(!GUIAccessManager.mf.getGlass().isVisible());
        }
        if (e.getKeyCode() == KeyEvent.VK_F5) {
            if (fullScreen) {
                if (!showingToolbox) {
                    if (toolboxDlg == null) {
                        toolboxDlg = new JDialog(fullScreenFrame, false);

                        toolboxDlg.setContentPane(GUIAccessManager.mf.getSurfaceTopTabbedPane());
                        toolboxDlg.pack();
                        toolboxDlg.addWindowListener(new WindowAdapter() {

                            @Override
                            public void windowClosing(WindowEvent e) {
                                GUIAccessManager.mf.getSurfacePanel().add(toolboxDlg.getContentPane(), BorderLayout.NORTH);

                            }
                        });
                    }
                    toolboxDlg.setVisible(true);
                    showingToolbox = true;
                }
            } else {
                setFullScreen();
            }
        }
    }

    public void keyReleased(KeyEvent e) {
    }

    public void keyTyped(KeyEvent e) {
    }

    public void mouseMoved(MouseEvent e) {

        if (!drawEnabled) {
            return;
        }
        if (ITEM_TYPE != MOVE) {
            return;
        }
        synchronized (items) {
            for (Item item : items) {
                try {
                    if (item.contains(e.getPoint())) {
                        tempSelectedItem = currentSelectedItem = item;

                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }


        // GUIAccessManager.mf.getWbInfoField().setText("x =" + e.getX() + " y= " + e.getY() + " Click button to show pointer to participants.");
        if (tempSelectedItem != null) {
            List<Point2D> pts = tempSelectedItem.getSelectionPoints();
            boolean contains = false;
            for (int i = 0; i < pts.size(); i++) {
                Rectangle point = new Rectangle((int) pts.get(i).getX() - selectOffset, (int) pts.get(i).getY() - selectOffset, selectOffset * 2, selectOffset * 2);

                if (point.contains(e.getPoint())) {

                    setCursor(i);
                    contains = true;
                    break;
                }
            }

            if (!contains) {
                paintCurrentPointer();

            }


        }

        repaint();

    }

    private void setCursor(int pointNo) {
        if (ITEM_TYPE != MOVE) {
            return;
        }
        if (pointNo == 0) {
            setCursor(Cursor.getPredefinedCursor(Cursor.NW_RESIZE_CURSOR));
            resizeType = "NW";
        } else if (pointNo == 1) {
            setCursor(Cursor.getPredefinedCursor(Cursor.NE_RESIZE_CURSOR));
            resizeType = "NE";
        } else if (pointNo == 2) {
            setCursor(Cursor.getPredefinedCursor(Cursor.SE_RESIZE_CURSOR));
            resizeType = "SE";
        } else if (pointNo == 3) {
            setCursor(Cursor.getPredefinedCursor(Cursor.SW_RESIZE_CURSOR));
            resizeType = "SW";
        } else {

            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }
    }

    public boolean sendItem(Item item, String itemMode) {

        if (item instanceof Line) {
            Line line = (Line) item;
            StringBuilder sb = new StringBuilder();
            sb.append("<item-content>");
            double x1 = line.getX1() / whiteboardSize.getWidth();
            double y1 = line.getY1() / whiteboardSize.getHeight();
            double x2 = line.getX2() / whiteboardSize.getWidth();
            double y2 = line.getY2() / whiteboardSize.getHeight();
            sb.append("<item-type>").append("line").append("</item-type>");
            sb.append("<item-mode>").append(itemMode).append("</item-mode>");
            sb.append("<x1>").append(x1).append("</x1>");
            sb.append("<y1>").append(y1).append("</y1>");
            sb.append("<x2>").append(x2).append("</x2>");
            sb.append("<y2>").append(y2).append("</y2>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("<color>");
            sb.append("<red>").append(currentColor.getRed()).append("</red>");
            sb.append("<green>").append(currentColor.getGreen()).append("</green>");
            sb.append("<blue>").append(currentColor.getBlue()).append("</blue>");
            sb.append("</color>");
            sb.append("<stroke-width>").append(strokeWidth).append("</stroke-width>");
            sb.append("</item-content>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_WB_ITEM);
            p.setContent(sb.toString());
            if (itemMode.equals("edit")) {
                p.setPacketID(item.getId());
            }
            ConnectionManager.sendPacket(p);
        }
        if (item instanceof Text) {
            Text text = (Text) item;
            double x = text.getX() / whiteboardSize.getWidth();
            double y = text.getY() / whiteboardSize.getHeight();
            StringBuilder sb = new StringBuilder();
            sb.append("<item-content>");
            sb.append("<item-type>").append("text").append("</item-type>");
            sb.append("<item-mode>").append(itemMode).append("</item-mode>");
            sb.append("<x>").append(x).append("</x>");
            sb.append("<y>").append(y).append("</y>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("<text-content>").append(text.getContent()).append("</text-content>");
            sb.append("<red>").append(currentColor.getRed()).append("</red>");
            sb.append("<green>").append(currentColor.getGreen()).append("</green>");
            sb.append("<blue>").append(currentColor.getBlue()).append("</blue>");
            sb.append("<font-name>").append(textFontName).append("</font-name>");
            sb.append("<font-size>").append(textFontSize).append("</font-size>");
            sb.append("<font-style>").append(textFontStyle).append("</font-style>");
            sb.append("</item-content>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_WB_ITEM);
            p.setContent(sb.toString());
            if (itemMode.equals("edit")) {
                p.setPacketID(item.getId());
            }
            ConnectionManager.sendPacket(p);
        }
        if (item instanceof Rect) {
            Rect rect = (Rect) item;
            double x = rect.getX() / whiteboardSize.getWidth();
            double y = rect.getY() / whiteboardSize.getHeight();

            StringBuilder sb = new StringBuilder();
            sb.append("<item-content>");
            sb.append("<item-type>").append("rect").append("</item-type>");
            sb.append("<item-mode>").append(itemMode).append("</item-mode>");
            sb.append("<x>").append(x).append("</x>");
            sb.append("<y>").append(y).append("</y>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("<color>");
            sb.append("<red>").append(currentColor.getRed()).append("</red>");
            sb.append("<green>").append(currentColor.getGreen()).append("</green>");
            sb.append("<blue>").append(currentColor.getBlue()).append("</blue>");
            sb.append("</color>");
            sb.append("<filled>").append(rect.isFilled() + "").append("</filled>");
            sb.append("<width>").append(rect.getWidth()).append("</width>");
            sb.append("<height>").append(rect.getHeight()).append("</height>");
            sb.append("<stroke-width>").append(strokeWidth).append("</stroke-width>");
            sb.append("</item-content>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_WB_ITEM);
            p.setContent(sb.toString());
            if (itemMode.equals("edit")) {
                p.setPacketID(item.getId());
            }
            ConnectionManager.sendPacket(p);

        }
        if (item instanceof Oval) {
            Oval oval = (Oval) item;
            double x = oval.getX() / whiteboardSize.getWidth();
            double y = oval.getY() / whiteboardSize.getHeight();

            StringBuilder sb = new StringBuilder();
            sb.append("<item-content>");
            sb.append("<item-type>").append("oval").append("</item-type>");
            sb.append("<item-mode>").append(itemMode).append("</item-mode>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("<color>");
            sb.append("<red>").append(currentColor.getRed()).append("</red>");
            sb.append("<green>").append(currentColor.getGreen()).append("</green>");
            sb.append("<blue>").append(currentColor.getBlue()).append("</blue>");
            sb.append("</color>");
            sb.append("<x>").append(x).append("</x>");
            sb.append("<y>").append(y).append("</y>");
            sb.append("<width>").append(oval.getWidth()).append("</width>");
            sb.append("<filled>").append(oval.isFilled() + "").append("</filled>");
            sb.append("<height>").append(oval.getHeight()).append("</height>");
            sb.append("<stroke-width>").append(strokeWidth).append("</stroke-width>");
            sb.append("</item-content>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_WB_ITEM);
            p.setContent(sb.toString());
            if (itemMode.equals("edit")) {
                p.setPacketID(item.getId());
            }
            ConnectionManager.sendPacket(p);

        }
        if (item instanceof Pen) {
            Pen pen = (Pen) item;
            ArrayList<Line2D.Double> pts = pen.getPoints();

            StringBuilder sb = new StringBuilder();
            sb.append("<item-content>");
            sb.append("<item-type>").append("pen").append("</item-type>");
            sb.append("<item-mode>").append(itemMode).append("</item-mode>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("<point-data>");
            for (Line2D pt : pts) {
                try {
                    double x1 = df.parse(df.format(pt.getX1() / whiteboardSize.getWidth())).doubleValue();
                    double y1 = df.parse(df.format(pt.getY1() / whiteboardSize.getHeight())).doubleValue();
                    double x2 = df.parse(df.format(pt.getX2() / whiteboardSize.getWidth())).doubleValue();
                    double y2 = df.parse(df.format(pt.getY2() / whiteboardSize.getHeight())).doubleValue();
                    sb.append(x1);
                    sb.append(",");
                    sb.append(y1);
                    sb.append(",");
                    sb.append(x2);
                    sb.append(",");
                    sb.append(y2);
                    sb.append("#");
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
            sb.append("<color>");
            sb.append("<red>").append(currentColor.getRed()).append("</red>");
            sb.append("<green>").append(currentColor.getGreen()).append("</green>");
            sb.append("<blue>").append(currentColor.getBlue()).append("</blue>");
            sb.append("</color>");
            sb.append("</point-data>");
            sb.append("<stroke-width>").append(strokeWidth).append("</stroke-width>");
            sb.append("</item-content>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_WB_ITEM);
            p.setContent(sb.toString());
            if (itemMode.equals("edit")) {
                p.setPacketID(item.getId());
            }
            ConnectionManager.sendPacket(p);

        }
        return false;
    }

    class ColorIcon implements Icon, SwingConstants {

        private int width = 12;
        private int height = 12;
        private Color color;

        public ColorIcon(Color color) {
            this.color = color;
        }

        public int getIconHeight() {
            return height;
        }

        public int getIconWidth() {
            return width;
        }

        public void paintIcon(Component c, Graphics g, int x, int y) {
            g.setColor(color);

            g.fillRect(x, y, width, height);
        }
    }
}
