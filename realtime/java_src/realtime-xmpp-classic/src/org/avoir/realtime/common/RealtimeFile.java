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
package org.avoir.realtime.common;

import org.avoir.realtime.common.util.GeneralUtil;
import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class RealtimeFile {

    private String fileName;
    private String filePath;
    private boolean directory;
    private boolean publicAccessible;
    private boolean slide;
    private boolean slideName;
    private int version;
    private boolean questionSlide;
    private boolean imageSlide;
    private boolean urlSlide;
    private String name;
    private ArrayList<RealtimeFile> files;


    public RealtimeFile(String fileName, String filePath, boolean directory, boolean publicAccessible) {
        this.fileName = fileName;
        this.filePath = filePath;
        this.directory = directory;
        this.publicAccessible = publicAccessible;
    }

    public ArrayList<RealtimeFile> getFiles(){
    	return files;
    }
    
    public void setFiles(ArrayList<RealtimeFile> files){
    	this.files=files;
    }
    
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
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

    public int getVersion() {
        return version;
    }

    public void setVersion(int version) {
        this.version = version;
    }

    public boolean isSlide() {
        return slide;
    }

    public void setSlide(boolean slide) {
        this.slide = slide;
    }

    public boolean isSlideName() {
        return slideName;
    }

    public void setSlideName(boolean slideName) {
        this.slideName = slideName;
    }

    public boolean isPublicAccessible() {
        return publicAccessible;
    }

    public void setPublicAccessible(boolean publicAccessible) {
        this.publicAccessible = publicAccessible;
    }

    public boolean isDirectory() {
        return directory;
    }

    public void setDirectory(boolean directory) {
        this.directory = directory;
    }

    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
    }

    public String getFilePath() {
        return filePath;
    }

    public void setFilePath(String filePath) {
        this.filePath = filePath;
    }

    @Override
    public String toString() {
        /*try {
            fileName = GeneralUtil.makeFirstLetterUpperCase(fileName);
        } catch (Exception ex) {
        }*/
        return fileName;
    }
}
