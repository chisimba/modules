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

import java.util.ArrayList;

import org.avoir.realtime.common.RealtimeFile;

/**
 *
 * @author developer
 */
public class RealtimeFileViewPacket{

   
    private String fileType;
    private String dirPath = "/";
    private ArrayList<RealtimeFile> fileList = new ArrayList<RealtimeFile>();
    private String username;
    private String access;

    public RealtimeFileViewPacket() {
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

    public String getDirPath() {
        return dirPath;
    }

    public void setDirPath(String dirPath) {
        this.dirPath = dirPath;
    }

   

    public ArrayList<RealtimeFile> getFileList() {
        return fileList;
    }

    public void setFileList(ArrayList<RealtimeFile> fileList) {
        this.fileList = fileList;
    }

    public String getFileType() {
        return fileType;
    }

    public void setFileType(String fileType) {
        this.fileType = fileType;
    }
  
}
