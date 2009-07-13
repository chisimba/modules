/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * ChatRoom.java
 *
 * Created on 2009/03/24, 01:30:11
 */
package org.avoir.realtime.chat;

import java.awt.Color;
import java.awt.Component;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.Icon;
import javax.swing.ImageIcon;
import javax.swing.JColorChooser;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JTextArea;
import javax.swing.JTextPane;
import javax.swing.SwingConstants;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.text.AbstractDocument;
import javax.swing.text.SimpleAttributeSet;
import javax.swing.text.StyleConstants;
import javax.swing.text.StyledDocument;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.jivesoftware.smack.packet.Message;

/**
 *
 * @author developer
 */
public class ChatRoom extends javax.swing.JPanel implements ActionListener {

    private AbstractDocument doc;
    private ChatRoomManager chatRoomManager;
 
    private JPopupMenu popup = new JPopupMenu();
    private JMenu sizesMenu = new JMenu();
    private JMenuItem colorMenuItem = new JMenuItem("Color");
    private Color currentColor = Color.BLACK;
    private int currentTextsize = 17;
    private ColorIcon colorIcon = new ColorIcon(currentColor);
    private ImageIcon fontIcon = ImageUtil.createImageIcon(this, "/images/font_go.png");
    private SimpleAttributeSet st = new SimpleAttributeSet();
    private ImageIcon saveIcon = ImageUtil.createImageIcon(this, "/images/save.png");
    private ImageIcon smileIcon = ImageUtil.createImageIcon(this, "/images/emoticons/emoticon_smile.png");
    private ImageIcon yesIcon = ImageUtil.createImageIcon(this, "/images/emoticons/thumb_up.png");
    private ImageIcon sadIcon = ImageUtil.createImageIcon(this, "/images/emoticons/emoticon_unhappy.png");
    private ImageIcon grinIcon = ImageUtil.createImageIcon(this, "/images/emoticons/emoticon_grin.png");
    private ImageIcon surprisedIcon = ImageUtil.createImageIcon(this, "/images/emoticons/emoticon_surprised.png");
    private ImageIcon wailIcon = ImageUtil.createImageIcon(this, "/images/emoticons/emoticon_waii.png");
    private ImageIcon noIcon = ImageUtil.createImageIcon(this, "/images/emoticons/thumb_down.png");
    private Timer typingTimer = new Timer();
    private Emot emots[] = {
        new Emot(smileIcon, ":)", "Smile"),
        new Emot(sadIcon, ":(", "Sad"),
        new Emot(grinIcon, ":D", "Grin"),
        new Emot(surprisedIcon, ":S", "Surpised"),
        new Emot(wailIcon, ":W", "Waii"),
        new Emot(yesIcon, "(y)", "Thumb up"),
        new Emot(noIcon, "(n)", "Thumb down")
    };
    private JPopupMenu emotPopup = new JPopupMenu();
    private IconsSurface iconsSurface;
    private boolean typing = false;
    private long lasttime = 0;
    private long duration = 0;

    public ChatRoom() {
       
        init();
    }

    /** Creates new form ChatRoom */
    public ChatRoom(ChatRoomManager chatRoomManager) {
        this.chatRoomManager = chatRoomManager;
        init();
    }

    public JTextArea getChatInputField() {
        return chatInputField;
    }

    public JTextPane getChatTranscriptField() {
        return chatTranscriptField;
    }

    public JPanel getChatUtilPanel() {
        return chatUtilPanel;
    }

    private void init() {
        initComponents();

        StyledDocument styledDoc = chatTranscriptField.getStyledDocument();
        if (styledDoc instanceof AbstractDocument) {
            doc = (AbstractDocument) styledDoc;

        } else {
            System.err.println("Text pane's document isn't an AbstractDocument!");

        }
        chatInputField.addKeyListener(new java.awt.event.KeyAdapter() {

            @Override
            public void keyTyped(KeyEvent e) {
                System.out.println("presseed "+e.getKeyCode());
                if ((e.getKeyChar() == KeyEvent.VK_ENTER) && (e.isShiftDown())) {
                    chatInputField.append("\n");
                } else if ((e.getKeyChar() == KeyEvent.VK_ENTER) && (!e.isShiftDown())) {
                    sendChat();
                }
            }
        });
        chatInputField.setWrapStyleWord(true);
        chatInputField.setLineWrap(true);
        chatInputField.setFont(new Font("Dialog", 0, currentTextsize));
        chatInputField.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    popup.show(chatInputField, e.getX(), e.getY());
                }
            }
        });
        chatInputField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent e) {
                lasttime = System.currentTimeMillis();

                showUserEnteredText();
            }

            public void removeUpdate(DocumentEvent e) {
                lasttime = System.currentTimeMillis();
                if (chatInputField.getText().trim().equals("")) {

                    showUserRemovedText();
                }
            }

            public void changedUpdate(DocumentEvent e) {
            }
        });
        popup.add(sizesMenu);
        popup.add(colorMenuItem);
        for (int i = 0; i < 100; i += 2) {
            JMenuItem sizeItem = new JMenuItem();
            sizeItem.setText(i + "");
            sizeItem.setActionCommand("textsize");
            sizeItem.addActionListener(this);
            sizesMenu.add(sizeItem);
        }
        sizesMenu.setText("Size: " + currentTextsize);
        colorMenuItem.setActionCommand("color");
        colorMenuItem.addActionListener(this);
        colorMenuItem.setIcon(colorIcon);
        iconsSurface = new IconsSurface(emots, chatInputField, emotPopup);
        emotPopup.add(iconsSurface);

    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("color")) {
            currentColor = JColorChooser.showDialog(this, "Choose Text Color", currentColor);
            colorIcon = new ColorIcon(currentColor);
            chatInputField.setForeground(currentColor);
            colorMenuItem.setIcon(colorIcon);
        }
        if (e.getActionCommand().equals("textsize")) {
            JMenuItem source = (JMenuItem) e.getSource();
            currentTextsize = Integer.parseInt(source.getText());
            chatInputField.setFont(new Font("Dialog", 0, currentTextsize));
            sizesMenu.setText("Size: " + currentTextsize);
        }
    }

    private void showUserRemovedText() {
        if (chatRoomManager != null) {
            Message msg = chatRoomManager.getMuc().createMessage();
            msg.setBody("");
            msg.setProperty("size", new Integer(9));
            msg.setProperty("color", Color.LIGHT_GRAY);
            msg.setProperty("message-type", "sys-text");
            if (chatRoomManager != null) {
                chatRoomManager.sendMessage(msg);
            }
        }
    }

    private void showUserEnteredText() {
        if (chatRoomManager != null) {
            if (!typing) {
               
                Message msg = chatRoomManager.getMuc().createMessage();
                msg.setBody(ConnectionManager.fullnames + " is typing ..");
                msg.setProperty("size", new Integer(9));
                msg.setProperty("color", Color.LIGHT_GRAY);
                msg.setProperty("message-type", "sys-text");
                if (chatRoomManager != null) {
                    chatRoomManager.sendMessage(msg);
                    typing = true;
                    typingTimer.cancel();
                    typingTimer = new Timer();
                    typingTimer.scheduleAtFixedRate(new TypingStatus(), 0, 1000);
                }
            }
        }

    }

    protected void sendChat() {
        if (chatRoomManager != null) {
            if (chatRoomManager.sendMessage(chatInputField.getText(), currentTextsize, currentColor)) {
                System.out.println("sended "+chatInputField.getText());
                chatInputField.setText("");
            } else {
                insertErrorMessage("Your message was not send\n");
            }
        }

    }

    private void insertString(String txt, SimpleAttributeSet st) {
        try {

            doc.insertString(doc.getLength(), txt, st);
            chatTranscriptField.setCaretPosition(doc.getLength());
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public void insertErrorMessage(String str) {
        SimpleAttributeSet st = new SimpleAttributeSet();
        StyleConstants.setForeground(st, Color.RED);// new Color(0, 131, 0));
        StyleConstants.setFontFamily(st, "SansSerif");
        StyleConstants.setBold(st, true);
        StyleConstants.setItalic(st, true);
        StyleConstants.setFontSize(st, 16);
        insertString(str, st);
    }

    public void insertParticipantName(String str) {
        SimpleAttributeSet st = new SimpleAttributeSet();
        StyleConstants.setForeground(st, new Color(0, 131, 0));
        StyleConstants.setFontFamily(st, "SansSerif");
        StyleConstants.setBold(st, true);
        StyleConstants.setItalic(st, false);
        StyleConstants.setFontSize(st, 16);
        insertString(str, st);
    }

    public void insertHistoryParticipantMessage(String str) {
        SimpleAttributeSet st = new SimpleAttributeSet();
        StyleConstants.setForeground(st, Color.DARK_GRAY);
        StyleConstants.setFontFamily(st, "SansSerif");
        StyleConstants.setBold(st, false);
        StyleConstants.setItalic(st, true);
        StyleConstants.setFontSize(st, 16);
        insertString(str, st);
    }

    public void insertParticipantMessage(String username, String content, int size, Color color) {
        if (content.lastIndexOf('\n') > -1) {
            content = content.substring(0, content.lastIndexOf('\n'));
        }
        parseEmoticons(content, color, "SansSerif",
                0, size);

    /*
    SimpleAttributeSet st = new SimpleAttributeSet();
    StyleConstants.setForeground(st, color);
    StyleConstants.setFontFamily(st, "SansSerif");
    StyleConstants.setBold(st, false);
    StyleConstants.setItalic(st, false);
    StyleConstants.setFontSize(st, size);
    insertString(str, st);*/
    }

    class EmotIcon {

        private int index;
        private int length;

        public EmotIcon(int index, int length) {
            this.index = index;
            this.length = length;
        }

        public int getIndex() {
            return index;
        }

        public void setIndex(int index) {
            this.index = index;
        }

        public int getLength() {
            return length;
        }

        public void setLength(int length) {
            this.length = length;
        }
    }

    private EmotIcon determineEmotIcon(
            String str) {
        int index = -1;
        for (int i = 0; i <
                emots.length; i++) {
            index = str.indexOf(emots[i].getSymbol());
            if (index > -1) {
                return new EmotIcon(index, emots[i].getSymbol().length());
            }

        }
        return new EmotIcon(index, 0);
    }

    private void parseEmoticons(String str, Color currentColor, String currentFontName,
            int currentFontStyle, int currentFontSize) {
        EmotIcon emot = determineEmotIcon(str);
        int index = emot.getIndex();
        int length = emot.getLength();
        while (index > -1) {
            st = new SimpleAttributeSet();
            StyleConstants.setFontFamily(st, currentFontName);
            StyleConstants.setBold(st, currentFontStyle == 1 ? true : false);
            StyleConstants.setItalic(st, currentFontStyle == 2 ? true : false);
            StyleConstants.setFontSize(st, currentFontSize);
            StyleConstants.setForeground(st, currentColor);
            String beforeIconStr = str.substring(0, index);
            insertString(beforeIconStr, st);
            String iconStr = str.substring(index, index + length);

            StyleConstants.setIcon(st, getEmoticon(iconStr));
            insertString(iconStr, st);
            str =
                    str.substring(index + length);
            emot =
                    determineEmotIcon(str);
            index =
                    emot.getIndex();
            length =
                    emot.getLength();
        }

        st = new SimpleAttributeSet();
        StyleConstants.setFontFamily(st, currentFontName);
        StyleConstants.setBold(st, currentFontStyle == 1 ? true : false);
        StyleConstants.setItalic(st, currentFontStyle == 2 ? true : false);
        StyleConstants.setFontSize(st, currentFontSize);
        StyleConstants.setForeground(st, currentColor);
        if (str.trim().length() > 0) {

            insertString(str, st);
        }

        insertString("\n", st);
    }

    public void insertSystemMessage(String str) {
        /*SimpleAttributeSet st = new SimpleAttributeSet();
        StyleConstants.setForeground(st, Color.LIGHT_GRAY);// new Color(0, 131, 0));
        StyleConstants.setFontFamily(st, "SansSerif");
        StyleConstants.setBold(st, false);
        StyleConstants.setItalic(st, false);
        StyleConstants.setFontSize(st, 11);
        insertString(str, st);*/
        sysTextField.setText(str);
    }

    private ImageIcon getEmoticon(String symbol) {
        for (int i = 0; i <
                emots.length; i++) {
            if (emots[i].getSymbol().equals(symbol)) {
                return emots[i].getIcon();
            }

        }

        return null;
    }

    class ColorIcon implements Icon, SwingConstants {

        private int width = 12;
        private int height = 12;
        private Color color;

        public ColorIcon(Color color) {
            this.color = color;
        }

        public int getIconHeight() {
            return height;
        }

        public int getIconWidth() {
            return width;
        }

        public void paintIcon(Component c, Graphics g, int x, int y) {
            g.setColor(color);

            g.fillRect(x, y, width, height);
        }
    }

    private class TypingStatus extends TimerTask {

        public void run() {
            duration = System.currentTimeMillis() - lasttime;
            if (duration > 1000) {
                showUserRemovedText();
                typing = false;
                this.cancel();
            }
        }
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        topPanel = new javax.swing.JPanel();
        southPanel = new javax.swing.JPanel();
        chatUtilPanel = new javax.swing.JPanel();
        jToolBar1 = new javax.swing.JToolBar();
        emotButton = new javax.swing.JButton();
        fontButton1 = new javax.swing.JButton();
        saveButton1 = new javax.swing.JButton();
        sendButton = new javax.swing.JButton();
        chatInputScrollPane = new javax.swing.JScrollPane();
        chatInputField = new javax.swing.JTextArea();
        sysTextField = new javax.swing.JTextField();
        mainPanel = new javax.swing.JPanel();
        chatScrollpane = new javax.swing.JScrollPane();
        chatTranscriptField = new javax.swing.JTextPane();

        setOpaque(false);
        setPreferredSize(new java.awt.Dimension(100, 277));
        setLayout(new java.awt.BorderLayout());

        topPanel.setOpaque(false);
        add(topPanel, java.awt.BorderLayout.PAGE_START);

        southPanel.setOpaque(false);
        southPanel.setLayout(new java.awt.BorderLayout());

        chatUtilPanel.setLayout(new java.awt.BorderLayout());

        jToolBar1.setFloatable(false);
        jToolBar1.setRollover(true);

        emotButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/emoticons/emoticon_smile.png"))); // NOI18N
        emotButton.setBorderPainted(false);
        emotButton.setContentAreaFilled(false);
        emotButton.setFocusable(false);
        emotButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        emotButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        emotButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                emotButtonActionPerformed(evt);
            }
        });
        jToolBar1.add(emotButton);

        fontButton1.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/type_1_font.png"))); // NOI18N
        fontButton1.setBorderPainted(false);
        fontButton1.setContentAreaFilled(false);
        fontButton1.setEnabled(false);
        fontButton1.setFocusable(false);
        fontButton1.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        fontButton1.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        jToolBar1.add(fontButton1);

        saveButton1.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/print_16x16.gif"))); // NOI18N
        saveButton1.setBorderPainted(false);
        saveButton1.setContentAreaFilled(false);
        saveButton1.setEnabled(false);
        saveButton1.setFocusable(false);
        saveButton1.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        saveButton1.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        jToolBar1.add(saveButton1);

        sendButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/key_enter.png"))); // NOI18N
        sendButton.setBorderPainted(false);
        sendButton.setContentAreaFilled(false);
        sendButton.setFocusable(false);
        sendButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        sendButton.setName("enterButton"); // NOI18N
        sendButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        sendButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                sendButtonActionPerformed(evt);
            }
        });
        jToolBar1.add(sendButton);

        chatUtilPanel.add(jToolBar1, java.awt.BorderLayout.PAGE_END);

        southPanel.add(chatUtilPanel, java.awt.BorderLayout.PAGE_START);

        chatInputField.setColumns(20);
        chatInputField.setFont(new java.awt.Font("SansSerif", 0, 16));
        chatInputField.setRows(3);
        chatInputField.setName("chatInput"); // NOI18N
        chatInputField.setPreferredSize(null);
        chatInputScrollPane.setViewportView(chatInputField);

        southPanel.add(chatInputScrollPane, java.awt.BorderLayout.CENTER);

        sysTextField.setEditable(false);
        sysTextField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                sysTextFieldActionPerformed(evt);
            }
        });
        southPanel.add(sysTextField, java.awt.BorderLayout.PAGE_END);

        add(southPanel, java.awt.BorderLayout.PAGE_END);

        mainPanel.setOpaque(false);
        mainPanel.setLayout(new java.awt.BorderLayout());

        chatScrollpane.setOpaque(false);

        chatTranscriptField.setEditable(false);
        chatTranscriptField.setName("chatTranscript"); // NOI18N
        chatTranscriptField.setPreferredSize(null);
        chatScrollpane.setViewportView(chatTranscriptField);

        mainPanel.add(chatScrollpane, java.awt.BorderLayout.CENTER);

        add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

    private void emotButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_emotButtonActionPerformed
        emotPopup.show(emotButton, emotButton.getX(), emotButton.getY() - 100);
    }//GEN-LAST:event_emotButtonActionPerformed

    private void sysTextFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_sysTextFieldActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_sysTextFieldActionPerformed

    private void sendButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_sendButtonActionPerformed
        sendChat();
}//GEN-LAST:event_sendButtonActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    protected javax.swing.JTextArea chatInputField;
    private javax.swing.JScrollPane chatInputScrollPane;
    private javax.swing.JScrollPane chatScrollpane;
    private javax.swing.JTextPane chatTranscriptField;
    private javax.swing.JPanel chatUtilPanel;
    private javax.swing.JButton emotButton;
    private javax.swing.JButton fontButton1;
    private javax.swing.JToolBar jToolBar1;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JButton saveButton1;
    private javax.swing.JButton sendButton;
    private javax.swing.JPanel southPanel;
    private javax.swing.JTextField sysTextField;
    private javax.swing.JPanel topPanel;
    // End of variables declaration//GEN-END:variables
    }
