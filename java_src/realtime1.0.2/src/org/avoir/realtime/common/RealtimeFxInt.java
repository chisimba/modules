/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.common;

import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.gui.room.RoomListFrame;
import org.avoir.realtime.gui.room.RoomMemberListFrame;
import org.avoir.realtime.gui.whiteboard.Whiteboard;
import org.avoir.realtime.startup.SwingBinder;

/**
 * interface used to enable java - javafx communication, specifically for
 * accessing javafx functionality from java
 * @author david
 */
public interface RealtimeFxInt {

    public void joinRoom(String roomName, String username, String names, String email);

    public void showMainScreen(String roomName, String username, String names, String email);

    public ChatRoomManager getChatRoomManager();

    public void addUserToParticipantList(String username, String names,
            String email, String location);

    public void setSysText(String txt);

    public void setStatusText(String txt);

    public RoomListFrame getRoomListFrame();

    public RoomMemberListFrame getRoomMemberListFrame();

    public SwingBinder getSwingBinder();

    public void addSlide(String url);

    public Whiteboard getWhiteboard();

    public void addWhiteboardToScreenGraph();
}
