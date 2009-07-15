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
package org.avoir.realtime.plugins.packets;

import java.awt.Color;
import javax.swing.ImageIcon;


/**
 *
 * @author developer
 */
public class Slide {

    private String title;
    private String text;
    private Color textColor;
    private int textSize;
    private String questionPath;
    private String imagePath;
    private String url;
    private ImageIcon image;
    private RealtimeQuestionPacket question;
    private int slideIndex;
    private boolean contentModified = false;
    private boolean titleModified = false;
    private String oldTitle;
    boolean questionSlide;
    boolean imageSlide;
    boolean urlSlide;

    public Slide(String title, String text, Color textColor, int textSize,
            String questionPath, String imagePath, String url, ImageIcon image,
            RealtimeQuestionPacket question, int slideIndex) {
        this.title = title;
        this.text = text;
        this.textColor = textColor;
        this.textSize = textSize;
        this.questionPath = questionPath;
        this.imagePath = imagePath;
        this.url = url;
        this.image = image;
        this.question = question;
        this.slideIndex = slideIndex;
    }

    public Slide() {
    }

    public boolean isImageSlide() {
        return imageSlide;
    }

    public void setImageSlide(boolean imageSlide) {
        this.imageSlide = imageSlide;
    }

    public boolean isQuestionSlide() {
        return questionSlide;
    }

    public void setQuestionSlide(boolean questionSlide) {
        this.questionSlide = questionSlide;
    }

    public boolean isUrlSlide() {
        return urlSlide;
    }

    public void setUrlSlide(boolean urlSlide) {
        this.urlSlide = urlSlide;
    }

    public String getOldTitle() {
        return oldTitle;
    }

    public void setOldTitle(String oldTitle) {
        this.oldTitle = oldTitle;
    }

    public boolean isContentModified() {
        return contentModified;
    }

    public void setContentModified(boolean contentModified) {
        this.contentModified = contentModified;
    }

    public boolean isTitleModified() {
        return titleModified;
    }

    public void setTitleModified(boolean titleModified) {
        this.titleModified = titleModified;
    }

    public int getSlideIndex() {
        return slideIndex;
    }

    public void setSlideIndex(int slideIndex) {
        this.slideIndex = slideIndex;
    }

    public Color getTextColor() {
        return textColor;
    }

    public void setTextColor(Color textColor) {
        this.textColor = textColor;
    }

    public int getTextSize() {
        return textSize;
    }

    public void setTextSize(int textSize) {
        this.textSize = textSize;
    }

    public ImageIcon getImage() {
        return image;
    }

    public void setImage(ImageIcon image) {
        this.image = image;
    }

    public String getImagePath() {
        return imagePath;
    }

    public void setImagePath(String imagePath) {
        this.imagePath = imagePath;
    }

    public RealtimeQuestionPacket getQuestion() {
        return question;
    }

    public void setQuestion(RealtimeQuestionPacket question) {
        this.question = question;
    }

    public String getQuestionPath() {
        return questionPath;
    }

    public void setQuestionPath(String questionPath) {
        this.questionPath = questionPath;
    }

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

    @Override
    public String toString() {

        if (title == null || title.trim().equals("")) {
            return "Untitled";
        }
        return title;
    }

    public boolean titleModified(Slide slide1, Slide slide2) {
        return !slide1.getTitle().equals(slide2.getTitle());
    }

    public boolean contentModified(Slide slide1, Slide slide2) {

        boolean result = false;
      
        if (!slide1.getText().equals(slide2.getText())) {

            return true;
        }
        if (!slide1.getTextColor().equals(slide2.getTextColor())) {
            return true;
        }
        if (slide1.getTextSize() != slide2.getTextSize()) {
            return true;
        }
        if (slide1.getQuestionPath() == null && slide2.getQuestionPath() != null) {
            return true;
        }
        if (slide1.getQuestionPath() != null && slide2.getQuestionPath() == null) {
            return true;
        }

        if (!slide1.getQuestionPath().equals(slide2.getQuestionPath())) {
            return true;
        }
        if (!slide1.getUrl().equals(slide2.getUrl())) {
            return true;
        }
        if (slide1.getImagePath() == null && slide2.getImagePath() != null) {
            return true;
        }
        if (slide1.getImagePath() != null && slide2.getImagePath() == null) {
            return true;
        }
        if (!slide1.getImagePath().equals(slide2.getImagePath())) {
            return true;
        }
        if (slide1.getSlideIndex() != slide2.getSlideIndex()) {
            return true;
        }

        return result;
    }
}
