/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.common.Constants;
import java.io.File;
import javax.swing.ImageIcon;

/**
 *
 * @author developer
 */
public class SessionManager {

    private RealtimeBase base;
    private int slideIndex = 0;
    private int slideCount = 0;
    private boolean isPresenter = false;
    private boolean privateVote;

    public SessionManager(RealtimeBase base) {
        this.base = base;
    }

    public boolean isPrivateVote() {
        return privateVote;
    }

    public void setPrivateVote(boolean privateVote) {
        this.privateVote = privateVote;
    }

    public boolean isIsPresenter() {
        return isPresenter;
    }

    public void startSession() {
        base.getToolbarManager().enableButtons(true);
        //if its a white board, then dont request for slides, rather, load the whiteboard
        if (base.getSessionId().equals("whiteboard")) {
            base.getSurface().setStatusMessage("Welcome to realtime whiteboard. ");
        } else {
            final File f = new File(avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/presentations/" + base.getSessionId());
            //first check if it exist in local cache, if so dont bother with downloads
            //unless the 'UseCache option is off, so it forces downloads every time
            if (f.exists() && f.list().length > 0) {
                if (base.getRealtimeOptions().useCache()) {
                    slideCount = f.list().length;
                    base.getAgendaManager().addDefaultAgenda(base.getSessionTitle());
                    base.getTcpClient().requestNewSlide(base.getSiteRoot(),
                            slideIndex, base.isPresenter(),
                            base.getSessionId(), base.getUser().getUserName(), base.getControl(),base.getSelectedFile());
                    return;
                }
            } else {
                //  System.out.println("Nothing in cache..requesting for slides");
                f.mkdirs();
                base.getTcpClient().requestForSlides(base.getSessionId(), base.getSlideServerId(),
                        base.getSlidesDir(), base.getUser().getUserName());
            }
        }
    }

    public void setIsPresenter(boolean isPresenter) {
        this.isPresenter = isPresenter;
    }

    public int getSlideIndex() {
        return slideIndex;
    }

    public void setSlideIndex(int slideIndex) {
        this.slideIndex = slideIndex;
    }

    public int getSlideCount() {
        return slideCount;
    }

    public void setSlideCount(int slideCount) {
        this.slideCount = slideCount;
    }

    public void updateSlide(int index) {
        slideIndex = index;
        base.getTcpClient().requestNewSlide(base.getSiteRoot(),
                slideIndex, base.isPresenter(),
                base.getSessionId(), base.getUser().getUserName(), base.getControl(),base.getSelectedFile());

    }

    /**
     * Updates the current slide and index to these values. The slide is picked from
     * the preloaded local cache
     * @param slide
     * @param slideIndex
     */
    public void setCurrentSlide(int slideIndex, boolean fromPresenter, String slidePath) {
        this.slideIndex = slideIndex;
        System.out.println(slidePath);
        /*if (!base.isPresenter()) {
        base.getAgendaManager().getAgendaTree().setSelectedRow(slideIndex + 1);
        }*/
        try {
            java.awt.Image img = javax.imageio.ImageIO.read(new File(slidePath));
            ImageIcon slide = new ImageIcon(img);
            if (base.getMODE() == Constants.APPLET) {
                base.getSurface().setCurrentSlide(slide, this.slideIndex + 1, slideCount, fromPresenter);
                base.getSurface().setStatusMessage(" ");
            }else{
                base.getWhiteboardSurface().setCurrentSlide(slide, this.slideIndex + 1, slideCount, fromPresenter);
                base.getWhiteboardSurface().showMessage("",false);
                
            }
            base.setText("Slide " + (this.slideIndex + 1) + " of " + slideCount, false);
            base.getToolbarManager().enableButtons(true);
        } catch (Exception ex) {
            ex.printStackTrace();
            base.getSurface().setStatusMessage("FATAL: Cannot find requested slide!");
        }
    }

    /**
     * Connects to the super node
     */
    public void connect() {
        Thread t = new Thread() {

            @Override
            public void run() {
                base.getSurface().setConnectingString("Connecting...This might take some" +
                        " few minutes, please wait...");
                base.getSurface().setConnecting(true);
                if (base.getRealtimeOptions().useDirectConnection()) {
                    if (base.getTcpClient().connect()) {
                        base.getSurface().setStatusMessage("Initializing session, please wait ...");
                        base.getSurface().setShowSplashScreen(false);
                        base.getToolbarManager().getVoiceOptionsButton().setEnabled(true);
                        base.getToolbarManager().getSessionButton().setEnabled(true);
                        base.getToolbarManager().getFirstSlideButton().setEnabled(base.isPresenter());
                        base.getToolbarManager().getBackSlideButton().setEnabled(base.isPresenter());
                        base.getToolbarManager().getNextSlideButton().setEnabled(base.isPresenter());
                        base.getToolbarManager().getLastSlideButton().setEnabled(base.isPresenter());
                        base.getToolbarManager().getYesButton().setEnabled(base.isPresenter());
                        base.getToolbarManager().getNoButton().setEnabled(base.isPresenter());

                        base.getSurface().setConnecting(false);
                        base.setText("Connected", false);
                        base.setConnected(true);
                        base.publish();

                        startSession();
                        base.initAudio();

                    } else {
                        String msg = "Cannot Connect to the server.";
                        base.setText(msg, true);
                        base.getSurface().setConnectingString(msg);
                        base.getSurface().setConnecting(false);
                        base.setConnected(false);

                    }
                }
                //here we use manual proxy
                if (base.getRealtimeOptions().useManualProxy()) {
                    new SSLHttpTunnellingClient(base.getRealtimeOptions().getProxyHost(),
                            base.getRealtimeOptions().getProxyPort(), base);
                }
            }
        };
        t.start();
    }
}
