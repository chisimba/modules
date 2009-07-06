/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

 */
package org.avoir.realtime.net.packets;

import java.awt.Color;
import java.util.ArrayList;
import java.util.Map;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.slidebuilder.Slide;

/**
 *
 * @author developer
 */
public class RealtimeSlideShowPacket {

    private String filename;
    private String filePath;
    private ArrayList<Slide> slides = new ArrayList<Slide>();
    private ArrayList<Map> titleChanges = new ArrayList<Map>();
    private String username = ConnectionManager.getUsername();
    private String access;
    private boolean modified;
    private int version;

    public RealtimeSlideShowPacket() {
    }

    public String getFilePath() {
        return filePath;
    }

    public void setFilePath(String filePath) {
        this.filePath = filePath;
    }

    public int getVersion() {
        return version;
    }

    public void setVersion(int version) {
        this.version = version;
    }

    public boolean isModified() {
        return modified;
    }

    public void setModified(boolean modified) {
        this.modified = modified;
    }

    public String getAccess() {
        return access;
    }

    public void setAccess(String access) {
        this.access = access;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getFilename() {
        return filename;
    }

    public ArrayList<Slide> getSlides() {
        return slides;
    }

    public ArrayList<Map> getTitleChanges() {
        return titleChanges;
    }

    public void setTitleChanges(ArrayList<Map> titleChanges) {
        this.titleChanges = titleChanges;
    }

    public void setSlides(ArrayList<Slide> slides) {
        this.slides = slides;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public String getChildElementXML() {
        StringBuilder buf = new StringBuilder();
        buf.append("<username>").append(username).append("</username>");
        buf.append("<filename>").append(filename).append("</filename>");
        buf.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        buf.append("<file-path>").append(filePath).append("</file-path>");
        buf.append("<modified>").append(modified).append("</modified>");
        buf.append("<access>").append(access).append("</access>");
        buf.append("<slides>");
        for (int i = 0; i < slides.size(); i++) {
            Slide slide = slides.get(i);
            buf.append("<slide>");
            buf.append("<title>").append(slide.getTitle() + "</title>");
            buf.append("<index>").append(slide.getSlideIndex() + "</index>");
            buf.append("<text>").append(slide.getText() + "</text>");
            buf.append("<text-color>");
            Color col = slide.getTextColor();
            buf.append("<red>").append(col.getRed() + "</red>");
            buf.append("<green>").append(col.getGreen() + "</green>");
            buf.append("<blue>").append(col.getBlue() + "</blue>");
            buf.append("</text-color>");
            buf.append("<text-size>").append(slide.getTextSize() + "</text-size>");

            buf.append("<question-path>").append(slide.getQuestionPath() + "</question-path>");
            buf.append("<url>").append(slide.getUrl() + "</url>");
            buf.append("<image-path>").append(slide.getImagePath() + "</image-path>");
            buf.append("<title-modified>").append(slide.isTitleModified()).append("</title-modified>");
            buf.append("<content-modified>").append(slide.isContentModified()).append("</content-modified>");
            buf.append("<old-title>").append(slide.getOldTitle()).append("</old-title>");
            buf.append("<is-question-slide>").append(slide.isQuestionSlide()).append("</is-question-slide>");
            buf.append("<is-image-slide>").append(slide.isImageSlide()).append("</is-image-slide>");
            buf.append("<is-url-slide>").append(slide.isUrlSlide()).append("</is-url-slide>");
            buf.append("</slide>");
        }
        buf.append("</slides>");

        return buf.toString();
    }
}
