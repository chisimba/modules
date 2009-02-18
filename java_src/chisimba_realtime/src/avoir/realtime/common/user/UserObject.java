/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.user;

import java.awt.Color;
import javax.swing.ImageIcon;
import javax.swing.JLabel;

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
    private ImageIcon chatIcon;
    private ImageIcon activeIcon;
    private ImageIcon speakingIcon;
    private ImageIcon resultIcon;
    private boolean online;
    private JLabel score=new JLabel();
    private int correctScores;
    private int wrongScores;

    public UserObject(User user, Color color, boolean active, boolean handRaised, boolean online) {
        this.user = user;
        this.color = color;
        this.active = active;
        this.handRaised = handRaised;
        this.online = online;
    }

    public int getCorrectScores() {
        return correctScores;
    }

    public void setCorrectScores(int correctScores) {
        this.correctScores = correctScores;
    }

    public int getWrongScores() {
        return wrongScores;
    }

    public void setWrongScores(int wrongScores) {
        this.wrongScores = wrongScores;
    }

    public JLabel getScore() {
        return score;
    }

    public void setScore(JLabel score) {
        this.score = score;
    }

    public String toString() {
        return user.getUserName();
    }

    public void setResultIcon(ImageIcon resultIcon) {
        this.resultIcon = resultIcon;
    }

    public ImageIcon getResultIcon() {
        return resultIcon;
    }

    public ImageIcon getSpeakingIcon() {
        return speakingIcon;
    }

    public void setSpeakingIcon(ImageIcon speakingIcon) {
        this.speakingIcon = speakingIcon;
    }

    public ImageIcon getActiveIcon() {
        return activeIcon;
    }

    public void setActiveIcon(ImageIcon activeIcon) {
        this.activeIcon = activeIcon;
    }

    public boolean isOnline() {
        return online;
    }

    public void setOnline(boolean online) {
        this.online = online;
    }

    public ImageIcon getChatIcon() {
        return chatIcon;
    }

    public void setChatIcon(ImageIcon chatIcon) {
        this.chatIcon = chatIcon;
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

    public void setUser(User user) {
        this.user = user;
    }
}
