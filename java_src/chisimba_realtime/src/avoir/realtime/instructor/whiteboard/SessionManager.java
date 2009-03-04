/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.common.Constants;
import java.io.File;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
 */
public class SessionManager {

    private Classroom mf;
    private int slideIndex = 0;
    private int slideCount = 0;
    private boolean isPresenter = false;
    private boolean privateVote;

    public SessionManager(Classroom mf) {
        this.mf = mf;
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

    private String stripExt(String filename) {
        int index = filename.lastIndexOf(".");
        if (index > -1) {
            return filename.substring(0, index);
        }
        return filename;
    }

    public void startSession() {

        System.out.println("in start session");
        String slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + stripExt(mf.getUser().getSessionTitle());

        final File f = new File(slidePath);//avoir.realtime.common.Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId());
        //first check if it exist in local cache, if so dont bother with downloads
        //unless the 'UseCache option is off, so it forces downloads every time
        if (f.exists() && f.list().length > 1) {
            Constants.log("Slides detected in cache");
            if (mf.getRealtimeOptions().useCache()) {
                slideCount = f.list().length;
                Constants.log(slideCount + " slides found");
                mf.getArchiveManager().addDefaultArchive(mf.getUser().getSessionTitle());
                mf.setSelectedFile(mf.getUser().getSessionTitle());
                mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(),
                        slideIndex, true,
                        mf.getUser().getSessionId(), mf.getUser().getUserName(),
                        true, mf.getSelectedFile(), true);

                return;
            }
        } else {
            System.out.println("Nothing in cache..requesting for slides from " + mf.getUser().getSlideServerId() + " session " + mf.getUser().getSessionId());
            Constants.log("Nothing in cache..requesting for slides from " + mf.getUser().getSlideServerId() + " session " + mf.getUser().getSessionId());
            f.mkdirs();
            mf.getConnector().requestForSlides(mf.getUser().getSessionId(), mf.getUser().getSlideServerId(),
                    mf.getUser().getSlidesDir(), mf.getUser().getUserName());
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

    public void updateParticipants(int index) {
        slideIndex = index;
        mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(),
                slideIndex, mf.getUser().isPresenter(),
                mf.getUser().getSessionId(), mf.getUser().getUserName(),
                true, mf.getSelectedFile(), mf.isWebPresent());

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
        /*if (!mf.isPresenter()) {
        mf.getAgendaManager().getAgendaTree().setSelectedRow(slideIndex + 1);
        }*/
        try {
            java.awt.Image img = javax.imageio.ImageIO.read(new File(slidePath));
            ImageIcon slide = new ImageIcon(img);

            mf.getWhiteboard().setCurrentSlide(slide, this.slideIndex + 1, slideCount, fromPresenter);
            mf.getWhiteboard().showMessage("", false);
            mf.showInfoMessage("Slide " + (this.slideIndex + 1) + " of " + slideCount);

        } catch (Exception ex) {
            ex.printStackTrace();
            mf.showErrorMessage("FATAL: Cannot find requested slide!");
        }
    }
}
