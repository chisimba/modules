/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.appsharing;

import avoir.realtime.common.appshare.CompressedScreenScrapeRect;
import avoir.realtime.common.packet.DesktopPacket;
import avoir.realtime.common.TCPSocket;
import java.awt.Dimension;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.image.ImageProducer;
import java.awt.image.MemoryImageSource;
import java.util.Date;
import java.util.Vector;

/**
 *
 * @author developer
 */
public abstract class ScreenScraper implements Runnable {

    private boolean bContinue = false;
    Thread thread;
    private boolean bScrapeRequested = false;
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private int iNextWait;
    private Date dateLast;
    private boolean bPaused;
    private static final int DEFAULT_KEY_FRAME_SECONDS = 30;
    private static final int DEFAULT_MINIMUM_SLEEP_MILLIS = 750;
    private static final float DEFAULT_KILOBITS_PER_SECOND = 150.0f;
    private int iMinSleepMillis = DEFAULT_MINIMUM_SLEEP_MILLIS;
    private int iKeyFrameSeconds = DEFAULT_KEY_FRAME_SECONDS;
    private BandwidthController bandwidthController =
            new BandwidthController(DEFAULT_KILOBITS_PER_SECOND);
    private ScrapeCompressionMessage compType =
             new JPEGCompression();
            //new APPCompression();
    private TCPSocket tcpclient;
    private String sessionId;
    private Rectangle fullScrapeRect = new Rectangle(ss);
    private boolean record = false;

    public ScreenScraper(TCPSocket tcpclient, String sessionId, boolean record) {
        appView = new AppView(PixelUtil.DEFAULT_WIDTH, PixelUtil.DEFAULT_HEIGHT);
        this.tcpclient = tcpclient;
        this.record = record;
        this.sessionId = sessionId;
    }

    public void setFullScrapeRect(Rectangle fullScrapeRect) {
        this.fullScrapeRect = fullScrapeRect;
    }

    public void startScraping() {
        thread = new Thread(this, getClass().getName());
        try {
            thread.setPriority(Thread.MIN_PRIORITY + 1);
        } catch (Throwable t) {
            t.printStackTrace();
        }
        bContinue = true;
        try {
            thread.start();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void stopScraping() {
        bContinue = false;
        try {
            thread.join();
            thread = null;
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void scrapeRequested() {
        //System.out.println("got scrape request");
        bScrapeRequested = true;
    }

    public int getScrapeInterval() {
//    	System.out.println("saying to wait " + this.iNextWait + " millis");
        return this.iNextWait;
    }

    public void run() {
        try {

            bScrapeRequested = true;
            int iMode = 0;

            while (bContinue) {
                if (bScrapeRequested) {
                    bScrapeRequested = false;
                    //          System.out.print(".");
                    doScrape();
                }
                try {
                    Thread.currentThread().sleep(
                            //ScreenScraperContext.APP_MODE == iMode
                            //? SCRAPE_INTERVAL_APP
                            //: SCRAPE_INTERVAL_SNAPSHOT
                            getScrapeInterval());
                } catch (Exception e) {
                    e.printStackTrace();
                    break;
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        //bRunning = false;
    }

    public synchronized ScrapeCompressionMessage getScrapeCompression() {
        return this.compType;
    }

    public byte[] compressPixels(int[] pixels, int iWidth, int iHeight) {
        byte[] baRet = null;
        if (getScrapeCompression() instanceof APPCompression) {
            baRet = PixelUtil.compressPixels(PixelUtil.downscalePixels(pixels));
        } else {
            baRet = doJpegCompression(pixels, iWidth, iHeight);
        }
        return baRet;
    }

    public abstract byte[] doJpegCompression(
            int[] pixels, int iWidth, int iHeight);

    public boolean isKeyFrameDue() {
        Date now = new Date();
        if (null == dateLast || ((now.getTime() - dateLast.getTime()) > (this.iKeyFrameSeconds * 1000))) {
            return true;
        } else {
            return false;
        }
    }

    private void doScrape()
            throws Exception {

        boolean bDoKeyFrame = isKeyFrameDue();
        if (null == currentPixels || null == this.lastRect || !this.lastRect.equals(fullScrapeRect)) {

            currentPixels = new int[fullScrapeRect.width * fullScrapeRect.height];
            bDoKeyFrame = true;
            this.lastRect = fullScrapeRect;
            this.scrapeRects = TileMaker.makeTiles(
                    new Dimension(fullScrapeRect.width, fullScrapeRect.height),
                    PixelUtil.SCRAPE_CHUNK_SIZE);
            this.currentScrapes = new RawScrape[scrapeRects.length];
            this.previousScrapes = new RawScrape[scrapeRects.length];
        }

        this.grabPixels(this.currentPixels, fullScrapeRect);
        MemoryImageSource misFullScrape = new MemoryImageSource(
                fullScrapeRect.width,
                fullScrapeRect.height,
                this.currentPixels,
                0,
                fullScrapeRect.width);

        for (int i = 0; i < scrapeRects.length; i++) {
            int[] ai = new int[scrapeRects[i].width * scrapeRects[i].height];
            PixelUtil.getSubImagePixels(misFullScrape, ai, scrapeRects[i]);
            this.currentScrapes[i] = new RawScrape(scrapeRects[i], ai, i);
        }

        // OK now we have the raw sub rects for the current
        // screen snapshot.  If no keyframe is required already,
        // and previous sub rects exist, compare them

        RawScrape[] aChangedRawScrapes = null;
        CompressedScreenScrapeRect[] aRectsToSend = null;//new CompressedScreenScrapeRect[0];

        if (!bDoKeyFrame) {
            aChangedRawScrapes = this.getChangedRawScrapesBelowThreshold();
            if (null == aChangedRawScrapes) {
                // means send key frame because threshold was exceeded
                aRectsToSend = getCompressedKeyFrameRect();
                bDoKeyFrame = true;
                //  System.out.println("sending keyframe anyway (threshold exceeded)");
            } else {
                aRectsToSend = compressChangedRects(aChangedRawScrapes, misFullScrape);
                // System.out.println("sending update: " + aRectsToSend.length + "/" + this.currentScrapes.length);
            }
        } else {
            aRectsToSend = getCompressedKeyFrameRect();
        }

        for (int i = 0; i < this.currentScrapes.length; i++) {
            this.previousScrapes[i] = this.currentScrapes[i];
        }

        dataReady(
                new ScreenScrapeData(aRectsToSend, fullScrapeRect, bDoKeyFrame));
    }

    public void dataReady(ScreenScrapeData data) {//byte[] ba, int width, int height){
        appView.showSharersView(true);
        // if calling into isKeyFrameDue gives false,
        // scraper decides if update is needed. if no update is needed,
        // call into here anyway, with zero-length rects
        // so that pump still gets primed
        if (data.getCompressedRects().length > 0) {

            // System.out.println(getClass().getName() + ".dataReady: sending scrape.");
//            router.sendMessage(new ScreenScrapeMessage(data));
//            this.updateBandwidthMetric(data.getByteCount());
            // appView.pixelUpdate(data);
            //if (tcpclient != null) {
            tcpclient.sendPacket(new DesktopPacket(data, sessionId, record, tcpclient.getMf().getUser().getUserName()));
            //}

            this.iNextWait = Math.max(
                    bandwidthController.getWaitPeriod(data.getByteCount()),
                    this.iMinSleepMillis//MIN_SCRAPE_DELAY_MILLIS
                    );
            if (data.isKeyFrame()) {
                // System.out.println("is key frame");
                resetKeyframe();
            }
            // scrapeRequested();
            // System.out.println("sending scrape");
        } else {
            // b/c server only requests scrape after
            // having received one
            // System.out.println("scrape requested...");
            scrapeRequested();

        }
        //baHash = baNewDigest;

        //new ScreenScrapeData(ba, width, height)));
    }

    public abstract int[] grabPixels(int[] pixels, Rectangle rect)
            throws Exception;

    private void resetKeyframe() {
        dateLast = new Date();
    }
    // nasty nasty nasty: return zero-length array if no rects changed
    // return null if key frame called for

    private RawScrape[] getChangedRawScrapesBelowThreshold() {
        //RawScrape[] changedScrapes = null;
        Vector vectChanged = new Vector();
        //boolean bRectsChanged = false;
        for (int i = 0; i < this.currentScrapes.length; i++) {
            if (this.changeChecker.dataChanged(
                    this.previousScrapes[i].getPixels(),
                    this.currentScrapes[i].getPixels())) {
                vectChanged.addElement(this.currentScrapes[i]);
                if (thresholdExceeded(vectChanged.size())) {
                    return null;
                }
            }
        }
        RawScrape[] changed =
                new RawScrape[vectChanged.size()];
        for (int i = 0; i < changed.length; i++) {
            changed[i] = (RawScrape) vectChanged.elementAt(i);
        }
        return changed;
    }

    private boolean thresholdExceeded(int iCount) {
        return (iCount > 0) && ((((float) iCount) / ((float) this.currentScrapes.length) * 100.0f) > this.KEY_FRAME_THRESHOLD_PERCENT);
    }

    private CompressedScreenScrapeRect[] compressChangedRects(
            RawScrape[] scrapes, MemoryImageSource mis)
            throws Exception {

        // merge neighboring rectangles so compression is more efficient
        Rectangle[] mergedRects = RectMerger.mergeRectangles(scrapes);

        CompressedScreenScrapeRect[] aRet =
                new CompressedScreenScrapeRect[mergedRects.length];//scrapes.length];
        for (int i = 0; i < mergedRects.length; i++) {
            aRet[i] = this.makeCompressedScreenScrapeRect(mergedRects[i], mis);//scrapes[i]);
        }
        return aRet;
    }

    private CompressedScreenScrapeRect makeCompressedScreenScrapeRect(
            Rectangle rect,
            ImageProducer mis) throws Exception {
        int[] iaPixels = getPixelsFromImageProducer(rect, mis);
        return this.makeCompressedScreenScrapeRect(rect, iaPixels);
    }

    private CompressedScreenScrapeRect makeCompressedScreenScrapeRect(
            Rectangle rect,
            int[] iaPixels) throws Exception {

        CompressedScreenScrapeRect cssr = null;
        byte[] ba = null;
        if (getScrapeCompression() instanceof APPCompression) {
            ba = PixelUtil.compressPixels(
                    PixelUtil.downscalePixels(
                    iaPixels));
            cssr = new ScreenScrapeAppData(ba, rect);
        } else {
            ba = this.doJpegCompression(
                    iaPixels,
                    rect.width,
                    rect.height);
            cssr = new ScreenScrapeJPEGData(ba, rect);
        }
        return cssr;
    }

    private int[] getPixelsFromImageProducer(Rectangle rect, ImageProducer ip)
            throws Exception {
        int[] ia = new int[rect.width * rect.height];
        PixelUtil.getSubImagePixels(ip, ia, rect);
        return ia;
    }

    private CompressedScreenScrapeRect[] getCompressedKeyFrameRect()
            throws Exception {

        CompressedScreenScrapeRect[] aRet = new CompressedScreenScrapeRect[1];
        aRet[0] = this.makeCompressedScreenScrapeRect(
                new Rectangle(0, 0, this.lastRect.width, this.lastRect.height),
                this.currentPixels);
        return aRet;
    }
    static final float KEY_FRAME_THRESHOLD_PERCENT = 50.0f;
    private RawScrape[] previousScrapes;
    private RawScrape[] currentScrapes;
    private Rectangle[] scrapeRects;
    int[] currentPixels;
    //int[] previousPixels;
    //private boolean bRunning = false;
    //private Point pLastOrigin;
    private Rectangle lastRect;
    private DataChangeChecker changeChecker = new SimpleChecker();
    private AppView appView;
}
