package org.avoir.realtime.privatechat;

import java.awt.Color;

import org.avoir.realtime.chat.ChatRoom;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

public class PrivateChatPanel extends ChatRoom {

  private String receiver;
  private String receiverName;

  public PrivateChatPanel(String receiver, String receiverName) {
      super();
      this.receiver = receiver;
      this.receiverName = receiverName;
  }

  @Override
  protected void sendChat() {
      String msg = chatInputField.getText();
      if (sendPrivateChat(msg)) {
          chatInputField.setText("");
      } else {
          insertErrorMessage("Your message was not sent.\n");
      }
  }

  public boolean sendPrivateChat(String msg) {
    if (!isVisible()) {
      setSize(400, 300);
      setVisible(true);
    }
    try {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.PRIVATE_CHAT_SEND);
        StringBuilder sb = new StringBuilder();
        sb.append("<private-chat-receiver>").append(receiver).append("</private-chat-receiver>");
        sb.append("<private-chat-sender>").append(ConnectionManager.getUsername()).append("</private-chat-sender>");
        sb.append("<private-chat-sender-name>").append(ConnectionManager.getFullnames()).append("</private-chat-sender-name>");
        sb.append("<private-chat-msg>").append(msg).append("</private-chat-msg>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
        insertParticipantName("(" + (ConnectionManager.getFullnames()) + ") ");
        insertParticipantMessage(ConnectionManager.getFullnames(), msg, 17, Color.BLACK);
        return true;
    } catch (Exception ex) {
        ex.printStackTrace();
    }
    return false;
  }
  
  public void receivePrivateChat(String msg) {
    insertParticipantName("(" + (receiverName) + ") ");
    insertParticipantMessage(receiverName, msg, 17, Color.BLACK);
  }
}
