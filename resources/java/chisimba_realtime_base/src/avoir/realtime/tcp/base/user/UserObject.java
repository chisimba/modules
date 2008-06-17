/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.user;

import java.awt.Color;
import javax.swing.ImageIcon;

/**
 *
 * @author developer
 */
public class UserObject {

    private User user;
    private Color color;
    private boolean active;
    private boolean handRaised;
    private String display;
    private ImageIcon speakerIcon;
    private ImageIcon micIcon;
    private ImageIcon presenceIcon;

    public UserObject(User user, Color color, boolean active, boolean handRaised) {
        this.user = user;
        this.color = color;
        this.active = active;
        this.handRaised = handRaised;
    }

    // @Override
    // public String toString() {
    //   return display;
    // }
    public ImageIcon getPresenceIcon() {
        return presenceIcon;
    }

    public void setPresenceIcon(ImageIcon presenceIcon) {
        this.presenceIcon = presenceIcon;
    }

    public ImageIcon getMicIcon() {
        return micIcon;
    }

    public void setMicIcon(ImageIcon micIcon) {
        this.micIcon = micIcon;
    }

    public ImageIcon getSpeakerIcon() {
        return speakerIcon;
    }

    public void setSpeakerIcon(ImageIcon speakerIcon) {
        this.speakerIcon = speakerIcon;
    }

    public String getDisplay() {
        return display;
    }

    public void setDisplay(String display) {
        this.display = display;
    }

    public boolean isHandRaised() {
        return handRaised;
    }

    public void setHandRaised(boolean handRaised) {
        this.handRaised = handRaised;
    }

    public boolean isActive() {
        return active;
    }

    public void setActive(boolean active) {
        this.active = active;
    }

    public Color getColor() {
        return color;
    }

    public void setColor(Color color) {
        this.color = color;
    }

    public User getUser() {
        return user;
    }
}
