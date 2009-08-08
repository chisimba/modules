package org.avoir.realtime.privatechat;

import java.util.HashMap;
import java.util.Map;

import org.avoir.realtime.net.ConnectionManager;

public class PrivateChatManager {
  private static Map<String, Object> privateChats = new HashMap<String, Object>();
  
  public static void initPrivateChat(String receiverUsername, String receiverName) {
    PrivateChatFrame privateChat = (PrivateChatFrame)privateChats.get(receiverUsername);
    if (privateChat == null) {
        PrivateChatPanel privateChatPanel = new PrivateChatPanel(receiverUsername, receiverName);
        PrivateChatFrame privateChatFrame = new PrivateChatFrame(privateChatPanel);
        privateChatFrame.setContentPane(privateChatPanel);
        privateChatFrame.setTitle(ConnectionManager.fullnames + ": Chat with " + receiverName);
        privateChatFrame.setSize(400, 300);
        privateChatFrame.setLocationRelativeTo(null);
        privateChatFrame.setVisible(true);
        privateChats.put(receiverUsername, privateChatFrame);
    } else {
        privateChat.setSize(400, 300);
        privateChat.setVisible(true);
    }

  }
  
  public static void receiveMessage(String sender, String senderName, String message) {
    PrivateChatFrame privateChat = ((PrivateChatFrame)privateChats.get(sender));
    if (privateChat == null) {
      initPrivateChat(sender, senderName);
      privateChat = ((PrivateChatFrame)privateChats.get(sender));
    }
    privateChat.receivePrivateChat(message);
  }
}
