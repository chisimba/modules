/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

/**
 *
 * @author developer
 */
public class Slide {

    String slidesDir;
    int index;

    public Slide(String slidesDir, int index) {
        this.index = index;
        this.slidesDir = slidesDir;
    }

    public int getSlideIndex() {
        return index;
    }

    public String getSlidesDir() {
        return slidesDir;
    }
}
