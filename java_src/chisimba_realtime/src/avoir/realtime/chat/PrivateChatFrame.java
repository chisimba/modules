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
package avoir.realtime.chat;

import avoir.realtime.appsharing.tcp.TCPConnector;
import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.common.user.User;
import javax.swing.JFrame;

/**
 *
 * @author developer
 */
public class PrivateChatFrame {

    private ClassroomMainFrame mf;
    private ChatRoom chatPanel;
    private JFrame frame;


    public PrivateChatFrame(User usr, ClassroomMainFrame mf,String chatId) {
        this.mf = mf;
        frame = new JFrame("ME[" + mf.getUser().getUserName() + "] in private chat with " + usr.getFullName());
        chatPanel = new ChatRoom(mf, mf.getUser(), "chatlog.txt", usr.getSessionId(), true, usr.getUserName(),chatId);
        chatPanel.setTcpSocket(mf.getTcpConnector());
        frame.setLocationRelativeTo(null);
        frame.setContentPane(chatPanel);
        frame.setSize(400, 300);
        frame.setVisible(true);
    }

    public ChatRoom getChatRoom() {
        return chatPanel;
    }

    public void show() {
        frame.setVisible(true);
    }
}
