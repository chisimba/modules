/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

/**
 *
 * @author developer
 */
public class Slide {

    private int slideIndex;
    private String slideName;
    private String presentationName;
    private int slideCount;
    private boolean webPresent;

    public Slide(int slideIndex, String slideName, String presentationName,
            int slideCount,boolean webPresent) {
        this.slideIndex = slideIndex;
        this.slideName = slideName;
        this.presentationName = presentationName;
        this.slideCount = slideCount;
        this.webPresent=webPresent;
    }

    public boolean isWebPresent() {
        return webPresent;
    }

    public void setWebPresent(boolean webPresent) {
        this.webPresent = webPresent;
    }

    public int getSlideCount() {
        return slideCount;
    }

    public void setSlideCount(int slideCount) {
        this.slideCount = slideCount;
    }

    public String getSlideName() {
        return slideName;
    }

    public void setSlideName(String slideName) {
        this.slideName = slideName;
    }

    public String getPresentationName() {
        return presentationName;
    }

    public void setPresentationName(String presentationName) {
        this.presentationName = presentationName;
    }

    public int getSlideIndex() {
        return slideIndex;
    }

    public void setSlideIndex(int slideIndex) {
        this.slideIndex = slideIndex;
    }

    public String toString() {
        if (slideIndex == -1) {
            return presentationName;
        } else {
            return slideName;
        }
    }
}
