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

import java.util.ArrayList;
import javax.swing.ImageIcon;
import org.avoir.realtime.plugins.Value;



/**
 *
 * @author developer
 */
public class RealtimeQuestionPacket {
 public final static int ESSAY_QUESTION = 0;
    public final static int MCQ_QUESTION = 1;
    public final static int TRUE_FALSE_QUESTION = 2;
    private String question;
    private ArrayList<Value> answerOptions = new ArrayList<Value>();
    private int type = MCQ_QUESTION;
    private String filename;
    private String imageData;
    private String username;
    private String access = "private";
    private String imagePath;
    private ImageIcon image;
    private String instructor;
    private String questionPath;

    public RealtimeQuestionPacket() {
    }

    public String getQuestionPath() {
        return questionPath;
    }

    public void setQuestionPath(String questionPath) {
        this.questionPath = questionPath;
    }

    public String getInstructor() {
        return instructor;
    }

    public void setInstructor(String instructor) {
        this.instructor = instructor;
    }

    public ImageIcon getImage() {
        return image;
    }

    public void setImage(ImageIcon image) {
        this.image = image;
    }

    public String getAccess() {
        return access;
    }

    public String getImagePath() {
        return imagePath;
    }

    public void setImagePath(String imagePath) {
        this.imagePath = imagePath;
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

    public String getImageData() {
        return imageData;
    }

    public void setImageData(String imageData) {
        this.imageData = imageData;
    }

    public int getType() {
        return type;
    }

    public void setType(int type) {
        this.type = type;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public ArrayList<Value> getAnswerOptions() {
        return answerOptions;
    }

    public void setAnswerOptions(ArrayList<Value> answerOptions) {
        this.answerOptions = answerOptions;
    }

    public String getQuestion() {
        return question;
    }

    public void setQuestion(String question) {
        this.question = question;
    }

    public int getQuestionType() {
        return type;
    }

    public void setQuestionType(int type) {
        this.type = type;
    }
}
