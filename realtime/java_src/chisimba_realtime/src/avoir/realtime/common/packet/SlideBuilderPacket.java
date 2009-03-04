/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.BuilderSlide;
import avoir.realtime.instructor.whiteboard.XmlBuilderSlide;
import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class SlideBuilderPacket implements RealtimePacket {

    private String sessionId;
    private ArrayList<XmlBuilderSlide> slides;
    private String filename;

    public SlideBuilderPacket(ArrayList<XmlBuilderSlide> slides, String filename) {
        this.slides = slides;
        this.filename = filename;

    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public ArrayList<XmlBuilderSlide> getSlides() {
        return slides;
    }

    public void setSlides(ArrayList<XmlBuilderSlide> slides) {
        this.slides = slides;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
