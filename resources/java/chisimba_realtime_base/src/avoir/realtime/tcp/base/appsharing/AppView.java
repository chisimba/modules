/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.appsharing;

import avoir.realtime.tcp.base.RealtimeBase;
import java.awt.Canvas;
import java.awt.Color;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.FontMetrics;
import java.awt.Frame;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.MediaTracker;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.ScrollPane;
import java.awt.Toolkit;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.image.MemoryImageSource;
import java.util.Hashtable;
import java.util.ResourceBundle;
import java.util.Vector;

public class AppView extends ScrollPane {

    RealtimeBase base;

    public AppView(int width, int height,RealtimeBase base) {
        panel = new AppViewPanel(width, height);
        this.base=base;
        add(panel);
    }

    public Dimension getPreferredSize() {
        return panel.getPreferredSize();
    }

    public void pixelUpdate(ScreenScrapeData data) {//byte[] baPixels){
        panel.pixelUpdate(data);
    }

    public void showSharersView(boolean bSharing) {
        //System.out.println(getClass().getName() + ".showSharersView");
        panel.showShare(bSharing);
    }

    public void showViewersView() {
        //System.out.println(getClass().getName() + ".showViewersView");
        panel.showView();
    }

    public void showCantShareView() {
        panel.showCantShareView();
    }

    public void setBundle(ResourceBundle bundle) {
        panel.setBundle(bundle);
    }

    public Rectangle getScrapeRegion() {
        return panel.getScrapeRegion();
    }

    public void setScrapeMode(ScrapeModeMessage mode) {
        panel.setScrapeMode(mode);
    }
    AppViewPanel panel;

}

class AppViewPanel extends Canvas
        implements
        MouseListener,
        MouseMotionListener {

//    private static final int[] X_POINTS = {0, 0, 20, 20};
//    private static final int[] Y_POINTS = {0, 20, 20, 0};
    public AppViewPanel(int width, int height) {
        rectBox = new Rectangle(0, 0, width, height);
        setBackground(Color.white);
        addMouseListener(this);
        addMouseMotionListener(this);
    }

    public Rectangle getScrapeRegion() {
        synchronized (rectBox) {

            if (this.mode instanceof DesktopModeMessage) {
                return new Rectangle(this.getToolkit().getScreenSize());
            }

            Point p = null;
            try {
                p = getLocationOnScreen();
            } catch (Exception e) {    //IllegalComponentStateException
                e.printStackTrace();
            }
            //Point p = appView.panel.getLocationOnScreen();
            if (null != p) {
                p.translate(0, getMessagesHeight());
                if (p.x > -PixelUtil.MAX_WIDTH && p.y > -PixelUtil.MAX_HEIGHT) {
                    rectScrape.setLocation(p);
                    rectScrape.setSize(rectBox.width, rectBox.height);
                }
            }
            // make defensive copy so its not altered
            // as bounding box is resized
            return new Rectangle(rectScrape);
        }
    }

    public void setScrapeMode(ScrapeModeMessage mode) {
        // System.out.println("setting scrape mode:" + mode);
        this.mode = mode;
        this.repaint();
    }

    public void mouseClicked(MouseEvent me) {
    }

    public void mouseEntered(MouseEvent me) {
    }

    public void mouseExited(MouseEvent me) {
    }

    public void mousePressed(MouseEvent me) {
        if (isOnVerticalBorder(me)) {
            //startSideDrag(me);
            bDraggingSide = true;
        }
        if (isOnHorizontalBorder(me)) {
            //startBottomDrag(me);
            bDraggingBottom = true;
        }
    }

    public void mouseReleased(MouseEvent me) {
        stopDrag(me);
    }

    public void mouseDragged(MouseEvent me) {
        if (bDraggingSide || bDraggingBottom) {
            //drawRubber();
            synchronized (rectBox) {
                if (bDraggingSide) {
                    rectBox.width = Math.max(
                            Math.min(me.getX(), PixelUtil.MAX_WIDTH),
                            PixelUtil.MIN_WIDTH);
                }
                if (bDraggingBottom) {
                    rectBox.height = Math.max(
                            Math.min(me.getY() - getMessagesHeight(), PixelUtil.MAX_HEIGHT),
                            PixelUtil.MIN_HEIGHT);
                }
            }
            //updateRects();
            this.forceLayout();
            repaint();//drawRubber();
        }
    }

    public void mouseMoved(MouseEvent me) {
        if (isOnVerticalBorder(me) && isOnHorizontalBorder(me)) {
            updateCursor(Cursor.SE_RESIZE_CURSOR);
        } else if (isOnHorizontalBorder(me)) {
            updateCursor(Cursor.N_RESIZE_CURSOR);
        } else if (isOnVerticalBorder(me)) {
            updateCursor(Cursor.W_RESIZE_CURSOR);
        } else {
            updateCursor(Cursor.DEFAULT_CURSOR);
        }
    }

    public void setBundle(ResourceBundle bundle) {
        this.bundle = bundle;
    }

    public void paint(Graphics g) {
        //	System.out.println("entering paint");
        if (bClearPending && null != this.graphicsOffscreen) {
            bClearPending = false;
            // System.out.println("clearing offscreen rect");
            this.graphicsOffscreen.clearRect(0, 0,
                    PixelUtil.MAX_WIDTH,
                    PixelUtil.MAX_HEIGHT + getMessagesHeight());//width, height);
        }
        //      System.out.println("Vect Images: "+vectLocatedImages);
        bSharer = false;
        if (bSharer) {

            super.paint(g);
            // System.out.println("in bsharer");
            paintSharerView(g, getColor());
        } else if (null != this.vectLocatedImages) {
            //      System.out.println("8**************888888888********************************");
            // synchronize so no drawing happens while rects
            // are being updated?
            int iImageCount = this.vectLocatedImages.size();
            for (int i = 0; i < iImageCount; i++) {
                //g.drawImage(
                LocatedImage li = (LocatedImage) this.vectLocatedImages.elementAt(i);
                // System.out.println("drawing image offscreen");
                this.graphicsOffscreen.drawImage(
                        li.getImage(),
                        li.x,//this.aRectTiles[i].x,//this.compRects[i].getOrigin().x,
                        li.y,//this.aRectTiles[i].y,//this.compRects[i].getOrigin().y,
                        this);
            }
            //	System.out.println("blitting offscreen to onscreen...");
            g.drawImage(this.imageOffscreen, 0, 0, this);
        //	System.out.println("...returned from blitting offscreen to onscreen.");
        }
    }

    public void update(Graphics g) {
        paint(g);
    }

    public void showShare(boolean bSharing) {
        //this.getParent().setBackground(Color.white);
        this.setBackground(Color.white);
        this.bSharer = true;
        this.bSharing = bSharing;
        //getGraphics().clearRect(0, 0, width, height);
        //invalidate();
        bClearPending = true;
        repaint();
    }

    public void showView() {
        //this.getParent().setBackground(Color.black);
        this.setBackground(Color.black);
        bSharer = false;
        bClearPending = true;
        repaint();
    }

    public void showCantShareView() {
        bCantShare = true;
        bClearPending = true;
        repaint();
    }

    public Dimension getPreferredSize() {
        //Dimension dim = null;
        //return new Dimension(this.lastDim);
//    	System.out.println(getClass().getName() + ".getPreferredSize: ");
        if (!this.bSharer) {
            return new Dimension(this.lastDim);//getParent().getSize();
        } else {
            return new Dimension(rectBox.width, rectBox.height + getMessagesHeight());
        }
    //return dim;
    }

//    private void updateBandwidthMetric(int iBytes){
//    	if(this.lDateStart == 0l){
//    		this.lDateStart = System.currentTimeMillis();
//    	}
//    	this.lBytesReceived += (long)iBytes;
//    	long lElapsed = System.currentTimeMillis() - this.lDateStart;
//    	System.out.println(this.lBytesReceived + " bytes received in "
//    			+ (lElapsed/1000) + " seconds (" +
//    			((this.lBytesReceived*8)/lElapsed) + "kbps)");
//    }
    private void cleanup() {
        if (null != this.vectLocatedImages) {
            while (!this.vectLocatedImages.isEmpty()) {
                LocatedImage li = (LocatedImage) this.vectLocatedImages.elementAt(0);
                this.vectLocatedImages.removeElementAt(0);
                li.getImage().flush();
            }
        }
        if (null != this.graphicsOffscreen) {
            this.graphicsOffscreen.dispose();
            this.graphicsOffscreen = null;
        }
        if (null != this.imageOffscreen) {
            this.imageOffscreen.flush();
            this.imageOffscreen = null;
        }
        this.vectLocatedImages = new Vector();
    }

    private void forceLayout() {
        this.invalidate();
        this.getParent().invalidate();
        this.getParent().validate();
    }

    void pixelUpdate(ScreenScrapeData data) {
        // System.out.println(getClass().getName() + ".pixelUpdate: "
        //  + "w: " + data.getWidth() + " h: " + data.getHeight());
//        this.compRects = data.getCompressedRects();

        if (data.isKeyFrame()) {
            this.bGotKeyFrame = true;
            //	System.out.println("$KEY FRAME$");
            cleanup();

            this.imageOffscreen = this.createImage(
                    //data.getWidth(), data.getHeight()
                    PixelUtil.MAX_WIDTH, PixelUtil.MAX_HEIGHT);
            //System.out.println("created: "+imageOffscreen);
            this.graphicsOffscreen = this.imageOffscreen.getGraphics();
            Dimension dim = new Dimension(data.getWidth(), data.getHeight());
            if (!dim.equals(this.lastDim)) {
                this.lastDim = dim;
                this.forceLayout();
                this.bClearPending = true;
            }
        } else {
            if (!this.bGotKeyFrame) {
                // System.out.println("waiting for key frame...");
                return;
            } else {
                //	System.out.println("#UPDATE#");
            }
        }

        CompressedScreenScrapeRect[] compRects = data.getCompressedRects();
        for (int i = 0; i < compRects.length; i++) {
            vectLocatedImages.addElement(
                    this.compressedScrape2LocatedImage(compRects[i]));
        // System.out.println("added rect");
        }
        repaint();
    /*
    if(null == this.imageSources
    || data.getWidth() != lastDim.width
    || data.getHeight() != lastDim.height){

    if(!data.isKeyFrame()){
    // ignore update if keyframe
    // has not been received yet
    System.out.println("waiting for key frame...");
    return;
    }

    this.aRectTiles = TileMaker.makeTiles(
    new Dimension(data.getWidth(), data.getHeight()),
    PixelUtil.SCRAPE_CHUNK_SIZE
    );

    lastDim.width = data.getWidth();
    lastDim.height = data.getHeight();

    if(null != this.imageOffscreen){
    this.graphicsOffscreen.dispose();
    this.imageOffscreen.flush();
    }
    this.imageOffscreen = this.createImage(
    data.getWidth(), data.getHeight());
    this.graphicsOffscreen = this.imageOffscreen.getGraphics();

    bSetProducer = false;
    bClearPending = true;
    this.vectPixelArrays = new Vector();
    this.imageSources = new MemoryImageSource[aRectTiles.length];
    this.images = new Image[aRectTiles.length];
    for(int i = 0; i < aRectTiles.length; i++){
    int[] iaPixels = new int[
    aRectTiles[i].width*aRectTiles[i].height
    ];
    this.vectPixelArrays.addElement(iaPixels);
    this.imageSources[i] = new MemoryImageSource(
    aRectTiles[i].width,//iLastWidth,//PixelUtil.MAX_WIDTH,//width, // MAX_WIDTH ?
    aRectTiles[i].height,//iLastHeight,//PixelUtil.MAX_HEIGHT,//height,
    iaPixels,
    0,
    aRectTiles[i].width);
    this.imageSources[i].setAnimated(true);
    this.imageSources[i].setFullBufferUpdates(false);
    this.images[i] = createImage(this.imageSources[i]);
    }
    //            System.out.println("trying to force scroll layout");
    this.invalidate();
    this.getParent().invalidate();
    this.getParent().validate();
    }

    // if keyframe, decompress single byte array
    // then grab sub-images into this.vectPixelArrays.elementAt

    if(data.isKeyFrame()){
    //    		System.out.println("handling key frame: " + data);
    int[] iaKeyFrame = new int[data.getWidth() * data.getHeight()];
    if(compRects[0] instanceof ScreenScrapeJPEGData){
    decompressJpegScrape(
    compRects[0].getBytes(),
    compRects[0].getWidth(),
    compRects[0].getHeight(),
    iaKeyFrame
    );
    }else{
    decompressAppScrape(compRects[0].getBytes(), iaKeyFrame);
    }
    MemoryImageSource misKeyFrame = new MemoryImageSource(
    data.getWidth(),
    data.getHeight(),
    iaKeyFrame,
    0,
    data.getWidth()
    );
    // loop through tiles, grabbing sub-pixels
    for(int i = 0; i < aRectTiles.length; i++){
    try{
    PixelUtil.getSubImagePixels(
    misKeyFrame,
    (int[])vectPixelArrays.elementAt(i),
    aRectTiles[i]
    );
    this.imageSources[i].newPixels();
    //this.images[i] = createImage(this.imageSources[i]);
    }catch(Exception e){
    e.printStackTrace();
    }
    }
    }else{
    //    		System.out.println("handling update: " + data);
    for(int i = 0; i < compRects.length; i++){
    decompressScrape(compRects[i]);
    //this.imageSources[compRects[i].getIndex()].newPixels();
    }
    }
    if(!bSetProducer){
    repaint();
    bSetProducer = true;
    }
    //repaint();

     */
    }

    private LocatedImage compressedScrape2LocatedImage(CompressedScreenScrapeRect compRect) {
//    	System.out.println(compRect);
        Image imgRet = null;
        if (compRect instanceof ScreenScrapeJPEGData) {
            imgRet = Toolkit.getDefaultToolkit().createImage(
                    compRect.getBytes());
        } else {
            int[] ia = new int[compRect.width * compRect.height];
            decompressAppScrape(compRect, ia);
            MemoryImageSource mis = new MemoryImageSource(
                    compRect.width,
                    compRect.height,
                    ia,
                    0,
                    compRect.width);
            imgRet = Toolkit.getDefaultToolkit().createImage(mis);
        }
        MediaTracker tracker = new MediaTracker(this);
        tracker.addImage(imgRet, 0);
        try {
            tracker.waitForAll();
            tracker.removeImage(imgRet, 0);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return new LocatedImage(imgRet, new Rectangle(compRect));
    }

//    private int[] decompressScrape(CompressedScreenScrapeRect compRect){
//    	int[] ia = new int[compRect.width*compRect.height];
//        if(compRect instanceof ScreenScrapeJPEGData){
//        	decompressJpegScrape(compRect, ia);
//        }else{
//        	decompressAppScrape(compRect, ia);
//        }
//    }
//
    private void decompressAppScrape(
            CompressedScreenScrapeRect compRect, int[] iaDest) {
        byte[] ba = PixelUtil.decompressPixels(compRect.getBytes());
        PixelUtil.upscalePixels(ba, iaDest);
    }

//    private void decompressJpegScrape(
//    		CompressedScreenScrapeRect compRect, int[] aiDest){
//
//        Image imgTmp = Toolkit.getDefaultToolkit().createImage(
//        										compRect.getBytes());
//		PixelGrabber pg = new PixelGrabber(
//				imgTmp,
//				0, 0,
//				iWidth,//lastDim.width,//PixelUtil.WIDTH,
//				iHeight,//lastDim.height,//PixelUtil.HEIGHT,
//				aiDest,//(int[])this.vectPixelArrays.elementAt(i),
//				0,
//				iWidth//lastDim.width//PixelUtil.WIDTH
//		);
//		try{
//			pg.grabPixels();
//		}catch(InterruptedException ie){
//			ie.printStackTrace();
//		}
//    }
//
/*
    private void drawCursor(Point point){
    if(null != point){
    Graphics graphicsCursor = this.createImage(this.mis).getGraphics();
    Polygon polygon = new Polygon(X_POINTS, Y_POINTS, X_POINTS.length);
    polygon.translate(point.x, point.y);
    graphicsCursor.fillPolygon(polygon);
    }
    }
     */
    private synchronized void updateCursor(int type) {
        //System.out.println("setting cursor -- requested type: " + type);
        //System.out.println("setting cursor -- current type: "
        //                                        + getCursor().getType());
        if (getCursor().getType() != type) {
            //System.out.println("changing cursor: " + type);
            setCursor(Cursor.getPredefinedCursor(type));
        //repaint();
        }
    }

    private boolean isOnHorizontalBorder(MouseEvent me) {
        Point p = me.getPoint();
        Rectangle rect = new Rectangle(
                0,
                getMessagesHeight() + rectBox.height - BORDER_THICKNESS,
                rectBox.width,
                BORDER_THICKNESS);
        return rect.contains(p);//p.x <= dim.x
    //&& p.y//rects[2].contains(me.getPoint());
    }

    private boolean isOnVerticalBorder(MouseEvent me) {
        // this creates a new object with every mouse move event
        // should be changed to create rects (or updat rects) only on
        // dragging right or bottom border
        Point p = me.getPoint();
        Rectangle rect = new Rectangle(
                rectBox.width - BORDER_THICKNESS,
                getMessagesHeight(),
                BORDER_THICKNESS,
                rectBox.height);
        return rect.contains(p);
    //return rects[1].contains(me.getPoint());
    }

    private void stopDrag(MouseEvent me) {
        bDraggingSide = false;
        bDraggingBottom = false;
        updateCursor(Cursor.DEFAULT_CURSOR);
        repaint();
    // force scrollpane parent to redo layout here
    }

    private Color getColor() {
        if (bSharing) {
            return Color.green.darker().darker();
        } else {
            return Color.red;
        }
    }

    private void paintSharerView(Graphics g, Color color) {
        initMessagesHeight(g);
        g.clearRect(0, 0, rectBox.width, rectBox.height + getMessagesHeight());
        //color = color.darker();
        drawMessages(getMessages(), g, new Point(0, 0), color);
        if (this.mode instanceof RegionModeMessage) {
            drawBorder(g, new Point(0, getMessagesHeight()), color);
        }
    //String[] astr = new String[1];
    //astr[0] = "Message1";

    }

    private void drawBorder(Graphics g, Point pStart, Color color) {
        //System.out.println(getClass().getName() + ".drawBorder: "
        //  + dim);
        Color colorOld = g.getColor();
        g.setColor(color);
        /*
        g.fillRect(0, pStart.y, rectBox.width, rectBox.height);
        g.clearRect(
        BORDER_THICKNESS,
        BORDER_THICKNESS + getMessagesHeight(),
        rectBox.width - (2 * BORDER_THICKNESS),
        rectBox.height - (2 * BORDER_THICKNESS)
        );
         */
        g.fillRect(rectBox.x, rectBox.y, rectBox.width, BORDER_THICKNESS);
        g.fillRect(rectBox.width - BORDER_THICKNESS, rectBox.y, BORDER_THICKNESS, rectBox.height);
        g.fillRect(rectBox.x, rectBox.y + rectBox.height - BORDER_THICKNESS, rectBox.width, BORDER_THICKNESS);
        g.fillRect(rectBox.x, rectBox.y, BORDER_THICKNESS, rectBox.height);

        g.setColor(colorOld);
    }

    private void drawMessages(String[] strings, Graphics g, Point p, Color color) {
        Color colorOld = g.getColor();
        g.setColor(color);
        FontMetrics fm = g.getFontMetrics();
        for (int i = 0; i < strings.length; i++) {
            g.drawString(
                    strings[i],
                    //(BORDER_THICKNESS*2) + p.x,
                    (BORDER_THICKNESS) + p.x,
                    //(BORDER_THICKNESS*2) + p.y + fm.getMaxAscent()
                    p.y + fm.getMaxAscent() + (i * (fm.getMaxAscent() + fm.getMaxDescent())));
        }
        g.setColor(colorOld);
    /*
    if(0 == iMessagesHeight){
    int iMessageCount = getMessages().length;
    iMessagesHeight = iMessageCount *
    (g.getFontMetrics().getMaxAscent()
    +g.getFontMetrics().getMaxDescent());
    rectBox.y = iMessagesHeight;
    }
     */
    }

    private void initMessagesHeight(Graphics g) {
        if (-1 == iMessagesHeight) {
            int iMessageCount = getMessages().length;
            iMessagesHeight = iMessageCount *
                    (g.getFontMetrics().getMaxAscent() + g.getFontMetrics().getMaxDescent());
            rectBox.y = iMessagesHeight;
        }
    }

    private String[] getMessages() {
        String[] astrKeys = null;

        if (bSharing) {
            if (mode instanceof RegionModeMessage) {
                astrKeys = astrSharingRegionKeys;
            } else {
                astrKeys = astrSharingDesktopKeys;
            }
        } else if (bCantShare) {
            astrKeys = astrCantShareKeys;
        } else {
            if (mode instanceof RegionModeMessage) {
                astrKeys = astrReadyRegionKeys;
            } else {
                astrKeys = astrReadyDesktopKeys;
            }
        }
        //System.out.println("new version");
        if (null == bundle) {
            return new String[]{"empty messages"};
        } else if (!hashMessageSets.containsKey(astrKeys)) {
            String[] messages = new String[astrKeys.length];
            for (int i = 0; i < astrKeys.length; i++) {
                messages[i] = bundle.getString(astrKeys[i]);
            }
            hashMessageSets.put(astrKeys, messages);
        }
        return (String[]) hashMessageSets.get(astrKeys);
    }

    private int getMessagesHeight() {
        return iMessagesHeight;
    //int iRet = 0;
    //String[][] aastrAllKeys =
    }
    private static final int BORDER_THICKNESS = 6;
    //private static final int MAX_WIDTH = 800;
    //private static final int MAX_HEIGHT = 600;
    private boolean bSharing;
    private boolean bCantShare;
    private boolean bClearPending;
    private boolean bSharer;
//    private boolean bSetProducer;
    //private int[] iaPixels;
    //private MemoryImageSource mis;
//    private MemoryImageSource[] imageSources;
//    private Vector vectPixelArrays = new Vector();
//    private CompressedScreenScrapeRect[] compRects;
    //private Image image;
//    private Image[] images;
    private Vector vectLocatedImages;
    private boolean bGotKeyFrame = false;
    private Image imageOffscreen;
    private Graphics graphicsOffscreen;//    private Rectangle[] aRectTiles;
    private int iMessagesHeight = -1;
    //private int width;
    //private int height;

    //private Dimension dim;
    private Rectangle rectBox;
    private ResourceBundle bundle;
    private boolean bDraggingSide = false;
    private boolean bDraggingBottom = false;
    private Rectangle rectScrape = new Rectangle();
    //private int iLastWidth = 0;
    //private int iLastHeight = 0;
    private Dimension lastDim = new Dimension(3000, 3000);
//    private Rectangle rectScreen;
    //= ResourceBundle.getBundle(
    //                "com.sts.webmeet.client.Resources");
    private ScrapeModeMessage mode = new DesktopModeMessage();//RegionModeMessage();
    private Hashtable hashMessageSets = new Hashtable();
    private static final String[] astrReadyRegionKeys = {
        "i18n.appshare.region.start",
        "i18n.appshare.region.red",
        "i18n.appshare.region.note"
    };
    private static final String[] astrSharingRegionKeys = {
        "i18n.appshare.region.stop",
        "i18n.appshare.region.green",
        "i18n.appshare.region.note"
    };
    private static final String[] astrReadyDesktopKeys = {
        "i18n.appshare.desktop.start",
        "i18n.appshare.desktop.note1",
        "i18n.appshare.desktop.note2"
    };
    private static final String[] astrSharingDesktopKeys = {
        "i18n.appshare.desktop.stop",
        "i18n.appshare.desktop.note1",
        "i18n.appshare.desktop.note2"
    };
    private static final String[] astrCantShareKeys = {
        "i18n.appshare.cant.1",
        "i18n.appshare.cant.2",
        "i18n.appshare.cant.3"
    };    //private Rectangle[] rects;
}

class LocatedImage extends Rectangle {

    private Image image;

    public LocatedImage(Image image, Rectangle rect) {
        //super(rect);
        super(rect);
        this.image = image;
    }

    public Image getImage() {
        return this.image;
    }
}