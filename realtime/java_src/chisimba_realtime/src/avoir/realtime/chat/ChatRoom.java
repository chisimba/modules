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
package avoir.realtime.chat;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.user.User;
import avoir.realtime.common.PresenceConstants;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.ItemEvent;
import java.util.Date;
import java.util.LinkedList;
import java.awt.BorderLayout;
import java.awt.Color;
import java.text.SimpleDateFormat;

import javax.swing.JButton;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.ScrollPaneConstants;
import javax.swing.JPanel;
import java.awt.event.KeyEvent;
import avoir.realtime.common.packet.ChatLogPacket;
import avoir.realtime.common.packet.ChatPacket;
import avoir.realtime.classroom.packets.PresencePacket;
import avoir.realtime.common.TCPSocket;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.GraphicsEnvironment;
import java.awt.event.ItemListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.AbstractAction;
import javax.swing.ActionMap;
import javax.swing.ImageIcon;
import javax.swing.InputMap;
import javax.swing.JComboBox;
import javax.swing.JFileChooser;
import javax.swing.JOptionPane;
import javax.swing.JPopupMenu;
import javax.swing.JTextPane;
import javax.swing.JToolBar;
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

    private int fontSize = 16;
    private String fontName = "SansSerif";
    private int fontStyle = 1;
    private Color color = Color.BLACK;
    private boolean bold = false;
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
    private ClassroomMainFrame mf;
    private javax.swing.JLabel titleLabel = new javax.swing.JLabel("Use" +
            " SHIFT+ENTER to move to next line", javax.swing.JLabel.LEADING);
    private JTextPane textPane = new JTextPane();
    private AbstractDocument doc;
    private SimpleAttributeSet st = new SimpleAttributeSet();
    private ImageIcon fontIcon = ImageUtil.createImageIcon(this, "/icons/font_go.png");
    private ImageIcon colorIcon = ImageUtil.createImageIcon(this, "/icons/colors_chooser.png");
    private ImageIcon saveIcon = ImageUtil.createImageIcon(this, "/icons/save.png");
    private ImageIcon smileIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/emoticon_smile.png");
    private ImageIcon yesIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/thumb_up.png");
    private ImageIcon sadIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/emoticon_unhappy.png");
    private ImageIcon grinIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/emoticon_grin.png");
    private ImageIcon surprisedIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/emoticon_surprised.png");
    private ImageIcon wailIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/emoticon_waii.png");
    private ImageIcon noIcon = ImageUtil.createImageIcon(this, "/icons/emoticons/thumb_down.png");
    private Emot emots[] = {
        new Emot(smileIcon, ":)", "Smile"),
        new Emot(sadIcon, ":(", "Sad"),
        new Emot(grinIcon, ":D", "Grin"),
        new Emot(surprisedIcon, ":S", "Surpised"),
        new Emot(wailIcon, ":W", "Waii"),
        new Emot(yesIcon, "(y)", "Thumb up"),
        new Emot(noIcon, "(n)", "Thumb down")
    };
    private static final String COMMIT_ACTION = "commit";
    private Timer typingTimer = new Timer();
    private JFileChooser fc = new JFileChooser();

    private static enum Mode {

        INSERT, COMPLETION
    };
    private TCPSocket tcpSocket;
    private final List<String> words;
    private Mode mode = Mode.INSERT;
    private boolean typing = false;
    private long lasttime = 0;
    private long duration = 0;
    private boolean firstTime = true;
    private JPopupMenu emotPopup = new JPopupMenu();
    private IconsSurface iconsSurface;
    private JComboBox fontSizeField = new JComboBox();
    private JComboBox fontNameField = new JComboBox();
    private JButton colorButton = new JButton(colorIcon);
    private JButton saveButton = new JButton(saveIcon);
    private JButton fontButton = new JButton(fontIcon);
    private boolean privateChat;
    private String receiver;
    private String chatId;

    /**
     * Constructor
     * @param sl SocketList
     * @param usr User
     */
    public ChatRoom(ClassroomMainFrame mf, User usr, String chatLogFile, String sessionId,
            final boolean privateChat, String receiver, String chatId) {
        this.chatId = chatId;
        this.mf = mf;
        this.usr = usr;
        this.receiver = receiver;
        this.privateChat = privateChat;
        this.chatLogFile = chatLogFile;
        this.sessionId = sessionId;
        if (usr.isPresenter()) {
            color = new Color(255, 153, 51);
            fontSize = 17;
        }
        colorButton.setContentAreaFilled(false);

        chatIn = new JTextArea();
        chatIn.setFont(new Font(fontName, fontStyle, fontSize));
        chatIn.setForeground(color);
        textPane.setEditable(false);
        // textPane.setContentType("text/html");
        chatSubmit = new JButton("Send");
        chatScroll = new JScrollPane();
        chatInputPanel = new JPanel();
        iconsSurface = new IconsSurface(emots, chatIn, emotPopup);

        GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
        String[] fontFamilies = ge.getAvailableFontFamilyNames();
        for (int i = 0; i <
                fontFamilies.length; i++) {
            fontNameField.addItem(fontFamilies[i]);
        }

        fontNameField.setSelectedItem(fontName);

        for (int i = 1; i < 100; i++) {
            fontSizeField.addItem(i + "");
        }

        fontButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                showFontFrame();
            }
        });
        fontButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                fontButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                fontButton.setContentAreaFilled(false);

            }
        });
        colorButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                ChatColorChooser.createAndShowGUI(ChatRoom.this);
            }
        });
        colorButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                colorButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                colorButton.setContentAreaFilled(false);

            }
        });
        saveButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                if (fc.showSaveDialog(ChatRoom.this) == JFileChooser.APPROVE_OPTION) {
                    saveChat(fc.getSelectedFile().getAbsolutePath());
                }
            }
        });
        saveButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                saveButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                saveButton.setContentAreaFilled(false);

            }
        });
        iconsSurface.setPreferredSize(new Dimension(130, 100));
        emotPopup.add(iconsSurface);
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
        JToolBar titlePanel = new JToolBar();
        titlePanel.setFloatable(false);
        final JButton emotButton = new JButton(smileIcon);
        emotButton.setContentAreaFilled(false);
        emotButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                emotButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                emotButton.setContentAreaFilled(false);

            }
        });
        emotButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent evt) {

                emotPopup.show(emotButton, emotButton.getX(), emotButton.getY() - 100);// emotPopup.getHeight());
            }
        });
        fontSizeField.setEditable(true);
        fontSizeField.setSelectedItem(fontSize + "");
        fontSizeField.addItemListener(new ItemListener() {

            public void itemStateChanged(ItemEvent arg0) {
                fontSize = Integer.parseInt((String) fontSizeField.getSelectedItem());
                chatIn.setFont(new Font(fontName, fontStyle, fontSize));
            }
        });
        fontNameField.addItemListener(new ItemListener() {

            public void itemStateChanged(ItemEvent arg0) {
                fontName = (String) fontNameField.getSelectedItem();
                chatIn.setFont(new Font(fontName, fontStyle, fontSize));
            }
        });
        titlePanel.add(emotButton);
        titlePanel.add(fontButton);
        titlePanel.add(colorButton);
        titlePanel.add(saveButton);
        JPanel spacer = new JPanel();
        spacer.setPreferredSize(new Dimension(200, 21));
        // titlePanel.add(spacer);

        chatInputPanel.add(titlePanel, BorderLayout.NORTH);
        chatInputPanel.add(new JScrollPane(chatIn), BorderLayout.CENTER);
        JPanel p = new JPanel();
        p.add(chatSubmit);
        chatInputPanel.add(p, BorderLayout.EAST);
        chatIn.setWrapStyleWord(true);
        this.setLayout(new BorderLayout());
        this.add(chatScroll, BorderLayout.CENTER);
        this.add(chatInputPanel, BorderLayout.SOUTH);
        //chatIn.setPreferredSize(new Dimension(100, 50));
        chatIn.getDocument().addDocumentListener(new DocumentListener() {

            public void changedUpdate(DocumentEvent arg0) {
            }

            public void insertUpdate(DocumentEvent evt) {
                if (chatSubmit.isEnabled() && !privateChat) {
                    lasttime = System.currentTimeMillis();
                    monitorTyping(evt);
                    showUserEnteredText();
                }
            }

            public void removeUpdate(DocumentEvent evt) {
                if (chatSubmit.isEnabled() && !privateChat) {
                    lasttime = System.currentTimeMillis();
                    if (chatIn.getText().trim().equals("")) {

                        showUserRemovedText();
                    }
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

    private void showFontFrame() {
        JPanel p = new JPanel();
        p.add(fontNameField);
        p.add(fontSizeField);
        JOptionPane.showMessageDialog(this, p);
    }

    public JTextPane getTextPane() {
        return textPane;
    }

    public JTextArea getChatIn() {
        return chatIn;
    }

    public JButton getChatSubmit() {
        return chatSubmit;
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

    private void saveChat(String fileName) {
        try {
            if (fileName.indexOf(".txt") < 0) {
                fileName += ".txt";
            }
            BufferedWriter out = new BufferedWriter(new FileWriter(fileName));
            out.write(textPane.getText(), 0, textPane.getText().length());
            out.close();

        } catch (IOException ex) {
            JOptionPane.showMessageDialog(null, "Error saving the chat");
        }
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

    public Color getColor() {
        return color;
    }

    public void setColor(Color color) {
        this.color = color;
        chatIn.setForeground(color);
    }

    /**
     * this sends an edit icon to show that the user has entered some text
     */
    private void showUserEnteredText() {
        if (!typing) {
            PresencePacket p = new PresencePacket(sessionId,
                    PresenceConstants.EDIT_ICON, PresenceConstants.TEXT_AVAILABLE,
                    usr.getUserName());
            if (tcpSocket.getName() != null) {
                if (tcpSocket.getName().equals("student")) {
                    p.setForward(true);
                }
            }
            tcpSocket.sendPacket(p);
            typing = true;
            typingTimer.cancel();
            typingTimer = new Timer();
            typingTimer.scheduleAtFixedRate(new TypingStatus(), 0, 1000);
        }

    }

    public void setTcpSocket(TCPSocket tcpSocket) {
        this.tcpSocket = tcpSocket;
    }

    /**
     * this sends a signal to show that the user is removing text
     */
    private void showUserRemovedText() {
        PresencePacket p = new PresencePacket(sessionId,
                PresenceConstants.EDIT_ICON, PresenceConstants.NO_TEXT,
                usr.getUserName());
        if (tcpSocket.getName() != null) {
            if (tcpSocket.getName().equals("student")) {
                p.setForward(true);
            }
        }
        tcpSocket.sendPacket(p);
    }

    /**
     * sends the chat packet
     */
    private void sendChat() {
        if (privateChat) {
            System.out.println("from " + usr.getUserName() + " to " + receiver);
        }
        ChatPacket p = new ChatPacket(ChatRoom.this.usr.getUserName(), chatIn.getText(),
                getTime(), ChatRoom.this.chatLogFile, ChatRoom.this.sessionId, color, fontName,
                fontStyle, fontSize, privateChat, receiver);
        p.setId(chatId);
        tcpSocket.sendPacket(p);
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

        formatter =
                new SimpleDateFormat("h:mm:ss",
                new java.util.Locale("en_US"));
        today =
                new Date();
        result =
                formatter.format(today);
        return result;

    }

    /**
     * Updates the contents of the chat window
     */
    public void update(ChatLogPacket chatLogPacket) {
        this.chatLog = chatLogPacket.getList();

        try {
            for (int i = 0; i <
                    chatLog.size(); i++) {
                ChatPacket chatPacket = chatLog.get(i);
                formatStr(chatPacket);
            }

            textPane.setCaretPosition(doc.getLength());
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    private void formatStr(ChatPacket chatPacket) {
        //  StyleConstants.setIcon(st, null);
        st = new SimpleAttributeSet();
        //color=chatPacket.getColor();
        StyleConstants.setFontFamily(st, chatPacket.getFontName());
        StyleConstants.setBold(st, chatPacket.getFontStyle() == 1 ? true : false);
        StyleConstants.setItalic(st, chatPacket.getFontStyle() == 2 ? true : false);

        StyleConstants.setFontSize(st, chatPacket.getFontSize());
        StyleConstants.setForeground(st, Color.GRAY);
        insertString("[" + getTime() + "]", st);
        StyleConstants.setForeground(st, new Color(0, 131, 0));
        if (!chatPacket.getUsr().equalsIgnoreCase("System")) {
            insertString("<" + chatPacket.getUsr() + ">", st);
        }
        StyleConstants.setForeground(st, new Color(0, 0, 0));
        String content = chatPacket.getContent();
        parseEmoticons(content, chatPacket.getColor(), chatPacket.getFontName(),
                chatPacket.getFontStyle(), chatPacket.getFontSize());

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

    private void insertString(String txt, SimpleAttributeSet st) {
        try {

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
