package org.avoir.realtime.privatechat;

import java.awt.Color;

import javax.swing.JFrame;

import org.avoir.realtime.net.ConnectionManager;


public class PrivateChatFrame extends JFrame {

  private PrivateChatPanel chatPanel;

  public PrivateChatFrame(PrivateChatPanel chatPanel) {
      this.chatPanel = chatPanel;
  }

  public PrivateChatPanel getPrivateChatPanel() {
      return chatPanel;
  }
  public void receivePrivateChat(String msg) {
    chatPanel.receivePrivateChat(msg);
    if (!isVisible()) {
      setSize(400, 300);
      setVisible(true);
    }
  }
}
