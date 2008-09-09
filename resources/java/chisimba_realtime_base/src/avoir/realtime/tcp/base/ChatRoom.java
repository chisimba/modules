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
package avoir.realtime.tcp.base;

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
import javax.swing.JSplitPane;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;

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
    private int chatSize = 0;
    private LineBreakMeasurer lineMeasurer;
    private MessagePanel messagePanel = new MessagePanel();
    // index of the first character in the paragraph.
    private int paragraphStart;
    private String chatLogFile;
    // index of the first character after the end of the paragraph.
    int yValue = 10;
    private int paragraphEnd;
    private String sessionId;
    Font font = new Font("Dialog", 1, 12);
    private LinkedList<ChatPacket> chatLog = new LinkedList<ChatPacket>();
    private int xValue = 10;
    RealtimeBase base;
    private javax.swing.JLabel titleLabel = new javax.swing.JLabel("Use" +
            " SHIFT+ENTER to move to next line", javax.swing.JLabel.LEADING);
    JSplitPane splitPane = new JSplitPane(JSplitPane.VERTICAL_SPLIT);

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

        chatSubmit = new JButton("Send");
        chatScroll = new JScrollPane();
        chatInputPanel = new JPanel();

        chatIn.setEditable(true);
        chatIn.setColumns(20);

        chatSubmit.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                sendChat();
            }
        });


        chatScroll.setVerticalScrollBarPolicy(ScrollPaneConstants.VERTICAL_SCROLLBAR_AS_NEEDED);
        messagePanel.setPreferredSize(new Dimension(getWidth(), 100));
        chatScroll.setViewportView(messagePanel);
       // chatScroll.setMinimumSize(new Dimension(300, 300));
        chatInputPanel.setLayout(new BorderLayout());

        chatIn.setMinimumSize(new Dimension(300, 100));
        chatInputPanel.add(titleLabel, BorderLayout.NORTH);
        chatInputPanel.add(new JScrollPane(chatIn), BorderLayout.CENTER);
        JPanel p = new JPanel();
        p.add(chatSubmit);
        chatInputPanel.add(p, BorderLayout.EAST);

        this.setLayout(new BorderLayout());// new java.awt.GridLayout(2, 0));
        splitPane.setTopComponent(chatScroll);
        splitPane.setBottomComponent(chatInputPanel);
        this.add(splitPane, BorderLayout.CENTER);
        splitPane.setDividerLocation(250);
        //this.add(chatInputPanel, BorderLayout.SOUTH);
        chatIn.getDocument().addDocumentListener(new DocumentListener() {

            public void changedUpdate(DocumentEvent arg0) {
            }

            public void insertUpdate(DocumentEvent arg0) {
                showUserEnteredText();
            }

            public void removeUpdate(DocumentEvent arg0) {
                if (chatIn.getText().trim().equals("")) {
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
        // if (chatIn.getText().trim().length() > 0) {
        ChatPacket p = new ChatPacket(ChatRoom.this.usr.getFullName(), chatIn.getText() + "\n",
                getTime(), ChatRoom.this.chatLogFile, ChatRoom.this.sessionId);
        ChatRoom.this.base.getTcpClient().addChat(p);
        chatIn.setText("");
    // }
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

        formatter = new SimpleDateFormat("MH:mm:ss",
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
        movePanel(0, 1, yValue);
        messagePanel.repaint();
    }

    /**
     * basically repaint to show the latest of the messages
     * @param chatPacket
     */
    public void update(ChatPacket chatPacket) {
        chatPacket.setTime(getTime());
        this.chatLog.add(chatPacket);
        movePanel(0, 1, chatLog.size());
        messagePanel.repaint();
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
        messagePanel.repaint();
    }

    /**
     * Use this class to display the messages
     * */
    class MessagePanel
            extends JPanel {

        public MessagePanel() {
            setBackground(Color.white);

        }

        /**
         * Do actual painting here
         * @param g Graphics
         */
        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);

            int diff = chatLog.size() - chatSize;

            Graphics2D g2 = (Graphics2D) g;
            yValue = 10;
            for (int i = 0; i < chatLog.size(); i++) {
                ChatPacket p = (ChatPacket) chatLog.get(i);
                font = new Font("Dialog", 1, 12);
                AttributedString timeAS = new AttributedString("[" + p.getTime() + "]");
                timeAS.addAttribute(TextAttribute.FONT, font);
                timeAS.addAttribute(TextAttribute.FOREGROUND, Color.GRAY);
                drawText(g2, timeAS, 3, true);

                AttributedString userAS = new AttributedString("<" + p.getUsr() + "> ");
                userAS.addAttribute(TextAttribute.FONT, font);
                userAS.addAttribute(TextAttribute.FOREGROUND, new Color(0, 131, 0));

                FontMetrics fm = g2.getFontMetrics(font);

                int timeStrLength = fm.stringWidth("[" + p.getTime() + "]");
                drawText(g2, userAS, timeStrLength + 3, true);

                font = new Font("Dialog", 0, 12);
                String content = p.getContent();
                String[] lines = content.split("\n");
                for (int j = 0; j < lines.length; j++) {
                    AttributedString txtAS = new AttributedString(lines[j]);
                    txtAS.addAttribute(TextAttribute.FONT, font);
                    txtAS.addAttribute(TextAttribute.FOREGROUND, Color.BLACK);

                    int timeUserStrLen = fm.stringWidth("[" + p.getTime() + "]" + "<" + p.getUsr() + ">");
                    drawText(g2, txtAS, timeUserStrLen + 3, false);
                }
                diff--;
            }
            chatSize = chatLog.size();

            this.setPreferredSize(new Dimension(300, yValue + 30));
            this.revalidate();
        }
    }

    /**
     * draws multiple lines of text
     * @param g2d
     * @param mText
     */
    private void drawText(Graphics2D g2d, AttributedString mText, int margin, boolean strictOneLine) {
        // Create a new LineBreakMeasurer from the paragraph.
        AttributedCharacterIterator paragraph = mText.getIterator();
        paragraphStart = paragraph.getBeginIndex();
        paragraphEnd = paragraph.getEndIndex();

        FontRenderContext frc = g2d.getFontRenderContext();
        lineMeasurer = new LineBreakMeasurer(paragraph, frc);
        // Set break width 
        float breakWidth = 0;
        int lines = 0;


        float drawPosY = 0;
        // Set position to the index of the first character in the paragraph.
        lineMeasurer.setPosition(paragraphStart);

        // Get lines until the entire paragraph has been displayed.

        long startTime = System.currentTimeMillis();
        while (lineMeasurer.getPosition() < paragraphEnd) {
            long endTime = System.currentTimeMillis();
            //some deadlock: no idea why, so if differrence is greater just
            //warn the user and break
            if (endTime - startTime > 2) {

                g2d.setColor(Color.RED);
                g2d.drawString(" ** oops, broken line, press ENTER to fix this **", 3, yValue);
                g2d.setColor(Color.BLACK);
                yValue += 20;
                break;
            }
            if (lines < 1) {
                breakWidth = (float) ((getSize().width - margin + 10) * 0.9);
            } else {
                breakWidth = (float) (getSize().width * 0.9);

            }
            try {
                // Retrieve next layout. A cleverer program would also cache
                // these layouts until the component is re-sized.
                TextLayout layout = lineMeasurer.nextLayout(breakWidth);
                // Compute pen x position. If the paragraph is right-to-left we
                // will align the TextLayouts to the right edge of the panel.
                // Note: this won't occur for the English text in this sample.
                // Note: drawPosX is always where the LEFT of the text is placed.
                if (layout != null) {
                    float drawPosX = layout.isLeftToRight()
                            ? xValue : breakWidth - layout.getAdvance();

                    drawPosX += margin;
                    if (lines > 0 && !strictOneLine) {
                        drawPosX = 3;
                    }
                    // Move y-coordinate by the ascent of the layout.
                    drawPosY += layout.getAscent();

                    // Draw the TextLayout at (drawPosX, drawPosY).

                    layout.draw(g2d, drawPosX, yValue);//drawPosY);
                    // Move y-coordinate in preparation for next layout.
                    drawPosY += layout.getDescent() + layout.getLeading();
                    if (!strictOneLine) {
                        yValue += 20;
                    }
                    lines++;

                }

            } catch (Exception ex) {
                // ex.printStackTrace();
                break;
            }
        }//while
    //  System.out.println(endTime+"- "+startTime+" = "+(endTime - startTime));
    }
}
