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
import java.awt.event.FocusEvent;
import java.awt.event.FocusListener;
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
import javax.swing.JPopupMenu;
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


import javax.swing.text.MutableAttributeSet;
import org.avoir.realtime.gui.main.GUIAccessManager;

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
    private Color currentColor = Color.BLACK; // Default color for chat room
    private int currentTextsize = 17;         // Default text size for chat room
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


    /////// Kevin's declaration ////////////////////
    private JPopupMenu fontPopup = new JPopupMenu();  // Popup menu for fonts
    private JMenu submenu;                            // Creates the menus inside the popup
    private JMenuItem item;                           // items for the submenus
    private String currentStyle ="dialog";         // Default style for chat room
    private int currentStyleEmphasis = Font.PLAIN;     // Default style emphasis for chat room
    ////////////////////////////////////////////////


    public ChatRoom() {
       
        init();
    }

    /** Creates new form ChatRoom */
    public ChatRoom(ChatRoomManager chatRoomManager) {
        this.chatRoomManager = chatRoomManager;
        init();
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

        createFonts();
    }


    /////// Kevin's Code - Adding submenu to change fonts ////////////
    private void createFonts()
    {
        submenu = new JMenu("Font Color");
        item = new JMenuItem("Red");
        item.addActionListener(this);
        item.setActionCommand("Font Color");
        submenu.add(item);
        item = new JMenuItem("Blue");
        item.addActionListener(this);
        item.setActionCommand("Font Color");
        submenu.add(item);
        item = new JMenuItem("Black");
        item.addActionListener(this);
        item.setActionCommand("Font Color");
        submenu.add(item);
        item = new JMenuItem("Green");
        item.addActionListener(this);
        item.setActionCommand("Font Color");
        submenu.add(item);
        item = new JMenuItem("Light Gray");
        item.addActionListener(this);
        item.setActionCommand("Font Color");
        submenu.add(item);
        fontPopup.add(submenu);

        submenu = new JMenu("Font Style");
        item = new JMenuItem("Regular");
        item.addActionListener(this);
        item.setActionCommand("Font Style");
        submenu.add(item);
        item = new JMenuItem("Bold");
        item.addActionListener(this);
        item.setActionCommand("Font Style");
        submenu.add(item);
        submenu.setActionCommand("Font Style");
        item = new JMenuItem("Italic");
        item.addActionListener(this);
        item.setActionCommand("Font Style");
        submenu.add(item);
        fontPopup.add(submenu);

        submenu = new JMenu("Font Size");
        for(int i=8;i<=30;i++)
        {
            item = new JMenuItem("" + i);
            item.addActionListener(this);
            item.setActionCommand("Font Size");
            submenu.add(item);
        }        
        fontPopup.add(submenu);
    }

    private void setSelectedFont(String text, String menu)
    {
        if (menu.equalsIgnoreCase("Font Color"))
        {
            if(text.equalsIgnoreCase("BLACK"))
                currentColor = Color.BLACK;
            else if(text.equalsIgnoreCase("RED"))
                currentColor = Color.RED;
            else if(text.equalsIgnoreCase("BLUE"))
                currentColor = Color.BLUE;
            else if(text.equalsIgnoreCase("GREEN"))
                currentColor = Color.GREEN;
            else if(text.equalsIgnoreCase("LIGHT GRAY"))
                currentColor = Color.LIGHT_GRAY;
        }

        else if (menu.equalsIgnoreCase("Font Style"))
        {
            if(text.equalsIgnoreCase("REGULAR"))
                currentStyleEmphasis = Font.PLAIN;
            else if(text.equalsIgnoreCase("BOLD"))
                currentStyleEmphasis = Font.BOLD;
            else if(text.equalsIgnoreCase("ITALIC"))
                currentStyleEmphasis = Font.ITALIC;
        }

        else if (menu.equalsIgnoreCase("Font Size"))
        {
            currentTextsize = Integer.parseInt(text);
        }
        Font font = new Font(currentStyle, currentStyleEmphasis, currentTextsize);
        chatInputField.setFont(font);
        chatInputField.setForeground(currentColor);
        setJTextPaneFont(chatTranscriptField,font);
        sizesMenu.setText("Size: " + currentTextsize);
    }

    public void setJTextPaneFont(JTextPane jtp,Font font)
    {
        MutableAttributeSet attrs = jtp.getInputAttributes();
        
        StyleConstants.setFontSize(attrs,font.getSize());
        if (font.isBold())
            StyleConstants.setBold(attrs, true);
        else if (font.isPlain())
            StyleConstants.setBold(attrs, false);
        if (font.isItalic())
            StyleConstants.setItalic(attrs, true);
        StyleConstants.setForeground(attrs, currentColor);
        
        StyledDocument doc = jtp.getStyledDocument();
        doc.setCharacterAttributes(0, doc.getLength()+1, attrs, false);
    }
    //////// End of Kevin's Code for the Font changin ////////////////////

    

    public JTextPane getChatTranscriptField() {
        return chatTranscriptField;
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

        Object source = e.getSource();
        if(source instanceof JMenuItem)
        {
            JMenuItem item = (JMenuItem)source;
            String text = item.getText();
            setSelectedFont(text,e.getActionCommand());
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
            GUIAccessManager.mf.getRealtimeSysTray().revertTrayIcon();
        }

    }

    protected void sendChat() {
        if (chatRoomManager != null) {
            if (chatRoomManager.sendMessage(chatInputField.getText(), currentTextsize, currentColor)) {
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
        fontButton = new javax.swing.JButton();
        chatInputScrollPane = new javax.swing.JScrollPane();
        chatInputField = new javax.swing.JTextArea();
        sysTextField = new javax.swing.JTextField();
        mainPanel = new javax.swing.JPanel();
        chatScrollpane = new javax.swing.JScrollPane();
        chatTranscriptField = new javax.swing.JTextPane();

        setLayout(new java.awt.BorderLayout());
        add(topPanel, java.awt.BorderLayout.PAGE_START);

        southPanel.setLayout(new java.awt.BorderLayout());

        chatUtilPanel.setLayout(new java.awt.BorderLayout());

        jToolBar1.setFloatable(false);
        jToolBar1.setRollover(true);

        emotButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/emoticons/emoticon_smile.png"))); // NOI18N
        emotButton.setFocusable(false);
        emotButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        emotButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        emotButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                emotButtonActionPerformed(evt);
            }
        });
        jToolBar1.add(emotButton);

        fontButton.setText("Set Font");
        fontButton.setFocusable(false);
        fontButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        fontButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        fontButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                fontButtonActionPerformed(evt);
            }
        });
        jToolBar1.add(fontButton);

        chatUtilPanel.add(jToolBar1, java.awt.BorderLayout.PAGE_END);

        southPanel.add(chatUtilPanel, java.awt.BorderLayout.PAGE_START);

        chatInputField.setColumns(20);
        chatInputField.setFont(new java.awt.Font("SansSerif", 0, 16));
        chatInputField.setRows(3);
        chatInputScrollPane.setViewportView(chatInputField);

        southPanel.add(chatInputScrollPane, java.awt.BorderLayout.CENTER);

        sysTextField.setEditable(false);
        southPanel.add(sysTextField, java.awt.BorderLayout.PAGE_END);

        add(southPanel, java.awt.BorderLayout.PAGE_END);

        mainPanel.setLayout(new java.awt.BorderLayout());

        chatTranscriptField.setEditable(false);
        chatScrollpane.setViewportView(chatTranscriptField);

        mainPanel.add(chatScrollpane, java.awt.BorderLayout.CENTER);

        add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

    private void emotButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_emotButtonActionPerformed
        emotPopup.show(emotButton, emotButton.getX(), emotButton.getY() - 100);
    }//GEN-LAST:event_emotButtonActionPerformed

    private void fontButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_fontButtonActionPerformed
        fontPopup.show(fontButton, fontButton.getX(), fontButton.getY() - 75);
}//GEN-LAST:event_fontButtonActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    protected javax.swing.JTextArea chatInputField;
    private javax.swing.JScrollPane chatInputScrollPane;
    private javax.swing.JScrollPane chatScrollpane;
    private javax.swing.JTextPane chatTranscriptField;
    private javax.swing.JPanel chatUtilPanel;
    private javax.swing.JButton emotButton;
    private javax.swing.JButton fontButton;
    private javax.swing.JToolBar jToolBar1;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JPanel southPanel;
    private javax.swing.JTextField sysTextField;
    private javax.swing.JPanel topPanel;
    // End of variables declaration//GEN-END:variables
    }
