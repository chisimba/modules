/**
 * 	$Id: ChatRoom.java,v 1.3 2007/02/02 10:59:15 davidwaf Exp $
 *
 *  Copyright (C) GNU/GPL AVOIR 2007
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.tcp.base.chat;

import avoir.realtime.tcp.common.ImageUtil;
import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.common.PresenceConstants;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.Date;
import java.util.LinkedList;
import java.awt.Dimension;
import java.awt.BorderLayout;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Color;
import java.text.SimpleDateFormat;
import java.awt.FontMetrics;

import javax.swing.JButton;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.ScrollPaneConstants;
import javax.swing.JPanel;
import java.awt.event.KeyEvent;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.text.AttributedCharacterIterator;
import java.text.AttributedString;
import java.awt.Font;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.PresencePacket;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import javax.swing.AbstractAction;
import javax.swing.ActionMap;
import javax.swing.ImageIcon;
import javax.swing.InputMap;
import javax.swing.JOptionPane;
import javax.swing.JSplitPane;
import javax.swing.JTextPane;
import javax.swing.KeyStroke;
import javax.swing.SwingUtilities;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.text.AbstractDocument;
import javax.swing.text.BadLocationException;
import javax.swing.text.SimpleAttributeSet;
import javax.swing.text.StyleConstants;
import javax.swing.text.StyledDocument;

/**
 * Window that diplays a chat session
 */
@SuppressWarnings("serial")
public class ChatRoom
        extends JPanel implements ActionListener {

    private JTextArea chatIn;
    private JButton chatSubmit;
    private JScrollPane chatScroll;
    private JPanel chatInputPanel;
    private User usr;
    private String chatLogFile;
    // index of the first character after the end of the paragraph.
    int yValue = 10;
    private String sessionId;
    private LinkedList<ChatPacket> chatLog = new LinkedList<ChatPacket>();
    private RealtimeBase base;
    private javax.swing.JLabel titleLabel = new javax.swing.JLabel("Use" +
            " SHIFT+ENTER to move to next line", javax.swing.JLabel.LEADING);
    private JTextPane textPane = new JTextPane();
    private AbstractDocument doc;
    private SimpleAttributeSet st = new SimpleAttributeSet();
    private ImageIcon smileIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/smile.png");
    private ImageIcon yesIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/thumbs_up.png");
    private ImageIcon sadIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/sad.png");
    private ImageIcon noIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/thumbs_down.png");
    private Emot emots[] = {
        new Emot(smileIcon, ":)"),
        new Emot(sadIcon, ":("),
        new Emot(yesIcon, "(y)"),
        new Emot(noIcon, "(n)")
    };
    private static final String COMMIT_ACTION = "commit";

    private static enum Mode {

        INSERT, COMPLETION
    };
    private final List<String> words;
    private Mode mode = Mode.INSERT;

    /**
     * Constructor
     * @param sl SocketList
     * @param usr User
     */
    public ChatRoom(RealtimeBase base, User usr, String chatLogFile, String sessionId) {

        this.base = base;
        this.usr = usr;
        this.chatLogFile = chatLogFile;
        this.sessionId = sessionId;
        chatIn = new JTextArea();
        textPane.setEditable(false);
        chatSubmit = new JButton("Send");
        chatScroll = new JScrollPane();
        chatInputPanel = new JPanel();

        chatIn.setEditable(true);
        chatIn.setLineWrap(true);


        chatSubmit.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                sendChat();
            }
        });

        StyledDocument styledDoc = textPane.getStyledDocument();
        if (styledDoc instanceof AbstractDocument) {
            doc = (AbstractDocument) styledDoc;

        } else {
            System.err.println("Text pane's document isn't an AbstractDocument!");

        }
        chatScroll.setVerticalScrollBarPolicy(ScrollPaneConstants.VERTICAL_SCROLLBAR_AS_NEEDED);
        chatScroll.setViewportView(textPane);

        chatInputPanel.setLayout(new BorderLayout());

        chatInputPanel.add(titleLabel, BorderLayout.NORTH);
        chatInputPanel.add(new JScrollPane(chatIn), BorderLayout.CENTER);
        JPanel p = new JPanel();
        p.add(chatSubmit);
        chatInputPanel.add(p, BorderLayout.EAST);
        chatIn.setWrapStyleWord(true);
        this.setLayout(new BorderLayout());
        this.add(chatScroll, BorderLayout.CENTER);
        this.add(chatInputPanel, BorderLayout.SOUTH);
        chatIn.setPreferredSize(new Dimension(100, 50));
        chatIn.getDocument().addDocumentListener(new DocumentListener() {

            public void changedUpdate(DocumentEvent arg0) {
            }

            public void insertUpdate(DocumentEvent arg0) {
                showUserEnteredText();
            }

            public void removeUpdate(DocumentEvent evt) {
                if (chatIn.getText().trim().equals("")) {
                    monitorTyping(evt);
                    showUserRemovedText();
                }
            }
        });


        chatIn.addKeyListener(new java.awt.event.KeyAdapter() {

            @Override
            public void keyTyped(KeyEvent e) {
                if ((e.getKeyChar() == KeyEvent.VK_ENTER) && (e.isShiftDown())) {
                    chatIn.append("\n");
                } else if ((e.getKeyChar() == KeyEvent.VK_ENTER) && (!e.isShiftDown())) {
                    sendChat();

                }
            }
        });

        InputMap im = chatIn.getInputMap();
        ActionMap am = chatIn.getActionMap();
        im.put(KeyStroke.getKeyStroke("ENTER"), COMMIT_ACTION);
        am.put(COMMIT_ACTION, new CommitAction());

        words = new ArrayList<String>(5);
        words.add("spark");
        words.add("special");
        words.add("spectacles");
        words.add("spectacular");
        words.add("swing");

    }

    private void monitorTyping(DocumentEvent ev) {
        if (ev.getLength() != 1) {
            return;
        }

        int pos = ev.getOffset();
        String content = null;
        try {
            content = chatIn.getText(0, pos + 1);
        } catch (BadLocationException e) {
            e.printStackTrace();
        }

        // Find where the word starts
        int w;
        for (w = pos; w >= 0; w--) {
            if (!Character.isLetter(content.charAt(w))) {
                break;
            }
        }
        if (pos - w < 2) {
            // Too few chars
            return;
        }

        String prefix = content.substring(w + 1).toLowerCase();
        int n = Collections.binarySearch(words, prefix);
        if (n < 0 && -n <= words.size()) {
            String match = words.get(-n - 1);
            if (match.startsWith(prefix)) {
                // A completion is found
                String completion = match.substring(pos - w);
                // We cannot modify Document from within notification,
                // so we submit a task that does the change later
                SwingUtilities.invokeLater(
                        new CompletionTask(completion, pos + 1));
            }
        } else {
            // Nothing found
            mode = Mode.INSERT;
        }

    }

    private class CompletionTask implements Runnable {

        String completion;
        int position;

        CompletionTask(String completion, int position) {
            this.completion = completion;
            this.position = position;
        }

        public void run() {
            chatIn.insert(completion, position);
            chatIn.setCaretPosition(position + completion.length());
            chatIn.moveCaretPosition(position);
            mode = Mode.COMPLETION;
        }
    }

    private class CommitAction extends AbstractAction {

        public void actionPerformed(ActionEvent ev) {
            if (mode == Mode.COMPLETION) {
                int pos = chatIn.getSelectionEnd();
                chatIn.insert(" ", pos);
                chatIn.setCaretPosition(pos + 1);
                mode = Mode.INSERT;
            } else {
                chatIn.replaceSelection("\n");
            }
        }
    }

    class Emot {

        ImageIcon icon;
        String symbol;

        public Emot(ImageIcon icon, String symbol) {
            this.icon = icon;
            this.symbol = symbol;
        }

        public ImageIcon getIcon() {
            return icon;
        }

        public void setIcon(ImageIcon icon) {
            this.icon = icon;
        }

        public String getSymbol() {
            return symbol;
        }

        public void setSymbol(String symbol) {
            this.symbol = symbol;
        }
    }

    /**
     * this sends an edit icon to show that the user has entered some text
     */
    private void showUserEnteredText() {
        base.getTcpClient().sendPacket(new PresencePacket(sessionId,
                PresenceConstants.EDIT_ICON, PresenceConstants.TEXT_AVAILABLE,
                usr.getUserName()));
    }

    /**
     * this sends a signal to show that the user is removing text
     */
    private void showUserRemovedText() {
        base.getTcpClient().sendPacket(new PresencePacket(sessionId,
                PresenceConstants.EDIT_ICON, PresenceConstants.NO_TEXT,
                usr.getUserName()));
    }

    /**
     * sends the chat packet
     */
    private void sendChat() {
        ChatPacket p = new ChatPacket(ChatRoom.this.usr.getFullName(), chatIn.getText() + "\n",
                getTime(), ChatRoom.this.chatLogFile, ChatRoom.this.sessionId);
        ChatRoom.this.base.getTcpClient().addChat(p);
        chatIn.setText("");
   
    }

    /**
     * Event handler for the chat room
     * @param ae ActionEvent
     */
    public void actionPerformed(java.awt.event.ActionEvent ae) {
        if (ae.getActionCommand().equals("chat")) {
        }
        if (ae.getActionCommand().equals("log")) {
        }
    }

    /**
     * gets the time message was received (send?)
     * @return String
     */
    public String getTime() {
        Date today;
        String result;
        SimpleDateFormat formatter;

        formatter = new SimpleDateFormat("MM-d-yyyy h:mm:ss",
                new java.util.Locale("en_US"));
        today = new Date();
        result = formatter.format(today);
        return result;

    }

    /**
     * Updates the contents of the chat window
     */
    public void update(ChatLogPacket chatLogPacket) {
        this.chatLog = chatLogPacket.getList();
   
        try {
            for (int i = 0; i < chatLog.size(); i++) {
                ChatPacket chatPacket = chatLog.get(i);
                formatStr(chatPacket);
            }
            textPane.setCaretPosition(doc.getLength());
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void formatStr(ChatPacket chatPacket) {
        StyleConstants.setFontFamily(st, "SansSerif");
        StyleConstants.setFontSize(st, 11);
        StyleConstants.setForeground(st, Color.GRAY);
        insertString("[" + getTime() + "]", st);
        StyleConstants.setForeground(st, new Color(0, 131, 0));
        insertString("<" + chatPacket.getUsr() + ">", st);
        StyleConstants.setForeground(st, new Color(0, 0, 0));
        String content = chatPacket.getContent();
        parseEmoticons(content);

    }

    private ImageIcon getEmoticon(String symbol) {
        for (int i = 0; i < emots.length; i++) {
            if (emots[i].getSymbol().equals(symbol)) {
                return emots[i].getIcon();
            }
        }
        JOptionPane.showMessageDialog(this, symbol + ": null icon");
        return null;
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

    private EmotIcon determineEmotIcon(String str) {
        int index = -1;
        for (int i = 0; i < emots.length; i++) {
            index = str.indexOf(emots[i].getSymbol());
            if (index > -1) {
                return new EmotIcon(index, emots[i].getSymbol().length());
            }
        }
        return new EmotIcon(index, 0);
    }

    private void parseEmoticons(String str) {
        EmotIcon emot = determineEmotIcon(str);
        int index = emot.getIndex();
        int length = emot.getLength();
        while (index > -1) {
            st = new SimpleAttributeSet();
            String beforeIconStr = str.substring(0, index);
            insertString(beforeIconStr, st);
            String iconStr = str.substring(index, index + length);

            StyleConstants.setIcon(st, getEmoticon(iconStr));
            insertString(iconStr, st);
            str = str.substring(index + length);
            emot = determineEmotIcon(str);
            index = emot.getIndex();
            length = emot.getLength();
        }
        if (str.trim().length() > 0) {
            st = new SimpleAttributeSet();
            insertString(str, st);
        }
        st = new SimpleAttributeSet();
        insertString("\n", st);
    }

    private void insertString(String txt, SimpleAttributeSet st) {
        try {
            StyleConstants.setFontSize(st, 10);
            doc.insertString(doc.getLength(), txt, st);
            textPane.setCaretPosition(doc.getLength());
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    /**
     * basically repaint to show the latest of the messages
     * @param chatPacket
     */
    public void update(ChatPacket chatPacket) {
        formatStr(chatPacket);
    /*    chatPacket.setTime(getTime());
    this.chatLog.add(chatPacket);
    movePanel(0, 1, chatLog.size());
    messagePanel.repaint();
     */
    }

    /**
     * scrolls the message panel to latest message
     * @param xmove int
     * @param ymove int
     * @param size int
     */
    protected void movePanel(int xmove, int ymove, int size) {
        java.awt.Point pt = new java.awt.Point(0, size * 30);
        chatScroll.getViewport().setViewPosition(pt);
        chatScroll.repaint();

    }
}
