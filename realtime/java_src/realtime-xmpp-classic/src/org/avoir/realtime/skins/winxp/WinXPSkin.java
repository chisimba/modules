/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.skins.winxp;

import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import com.l2fprod.common.swing.JTaskPane;
import com.l2fprod.common.swing.JTaskPaneGroup;
import com.pagosoft.plaf.PlafOptions;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.util.ArrayList;
import javax.swing.JButton;
import javax.swing.JScrollPane;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;
import javax.swing.JTextArea;
import javax.swing.SwingUtilities;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.SkinManager;
import org.avoir.realtime.gui.main.Speaker;

/**
 *
 * @author david
 */
public class WinXPSkin implements SkinManager {

    private JTaskPane taskPane = new JTaskPane();
    //private JTaskPane chatRoomTaskPane = new JTaskPane();
    private JTaskPaneGroup participantsPanel = new JTaskPaneGroup();
    private JTaskPaneGroup toolsPanel = new JTaskPaneGroup();
    private JTaskPaneGroup wbToolsPanel = new JTaskPaneGroup();
    private JTaskPaneGroup roomResourcesPanel = new JTaskPaneGroup();
    private JTaskPaneGroup chatRoomPanel = new JTaskPaneGroup();
    private JTaskPaneGroup audioVideoPanel = new JTaskPaneGroup();
    private JTaskPaneGroup slidesScrollerPanel = new JTaskPaneGroup();
    private JTaskPaneGroup resourcesPanel = new JTaskPaneGroup();
    private WhiteboardToolsPanel whiteboardToolsPanel;
    private JTabbedPane roomResourcesTabbedPane = new JTabbedPane();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private RoomToolsPanel roomToolsPanel;

    public void init() {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                PlafOptions.setAsLookAndFeel();

                JSplitPane mainSplitPane = GUIAccessManager.mf.getMainSplitPane();
                mainSplitPane.setDividerLocation((ss.width / 4));
                mainSplitPane.setLeftComponent(taskPane);

                roomToolsPanel = new RoomToolsPanel();
                mainSplitPane.setRightComponent(GUIAccessManager.mf.getTabbedPane());

                // GUIAccessManager.mf.getTabbedPane().insertTab("Browser", null, GUIAccessManager.mf.getGeneralWebBrowser(), "Web Browser", 1);
                // GUIAccessManager.mf.getTabbedPane().setTabPlacement(JTabbedPane.LEFT);

                participantsPanel.setTitle("Participants");
                participantsPanel.add(GUIAccessManager.mf.getUserListPanel().getParticipantListTable());
                taskPane.add(participantsPanel);

                wbToolsPanel.setTitle("Whiteboard Tools");
                whiteboardToolsPanel = new WhiteboardToolsPanel();
                wbToolsPanel.add(whiteboardToolsPanel);
                taskPane.add(wbToolsPanel);
                toolsPanel.setTitle("Room Tools");
                toolsPanel.setName("toolsPanel");
                toolsPanel.setExpanded(false);

                toolsPanel.add(roomToolsPanel);
                taskPane.add(toolsPanel);
                resourcesPanel.setTitle("Room Resources");
                resourcesPanel.setExpanded(false);

                JTextArea inf = new JTextArea("Help");
                inf.setEnabled(false);
                GUIAccessManager.mf.getWebPresentNavigator().setPreferredSize(new Dimension(100, 150));
                roomResourcesTabbedPane.addTab("Info", new JScrollPane(inf));
                roomResourcesTabbedPane.addTab("Slides", GUIAccessManager.mf.getWebPresentNavigator());
                roomResourcesTabbedPane.addChangeListener(new ChangeListener() {

                    public void stateChanged(ChangeEvent e) {
                        if (roomResourcesTabbedPane.getSelectedIndex() == 1) {
                            GUIAccessManager.mf.getWebPresentNavigator().populateWithRoomResources();

                        }
                    }
                });
                resourcesPanel.add(roomResourcesTabbedPane);
                taskPane.add(resourcesPanel);



                audioVideoPanel.setTitle("Audio Video");
                GUIAccessManager.mf.getUserListPanel().getAudioVideoPanel().setPreferredSize(new Dimension(150, 350));
                audioVideoPanel.add(GUIAccessManager.mf.getUserListPanel().getAudioVideoPanel());
                audioVideoPanel.setExpanded(false);
                taskPane.add(audioVideoPanel);

                GUIAccessManager.mf.getChatRoomManager().getChatRoom().setOpaque(false);
                //chatRoomTaskPane.setOpaque(false);
                audioVideoPanel.setOpaque(false);
                chatRoomPanel.setOpaque(false);

                chatRoomPanel.setTitle("Chat Room");
                GUIAccessManager.mf.getChatRoomManager().getChatRoom().setSize(new Dimension(ss.width / 6, ss.height / 3));
                chatRoomPanel.setExpanded(true);
                GUIAccessManager.mf.getChatRoomManager().getChatRoom().getChatTranscriptField().setPreferredSize(new Dimension(100, 200));
                chatRoomPanel.add(GUIAccessManager.mf.getChatRoomManager().getChatRoom());
                taskPane.add(chatRoomPanel);
                ArrayList<Speaker> speakers = GUIAccessManager.mf.getSpeakers();
                int speakerCols = 2;
                int speakerRows = 2;
                for (int i = 0; i < speakerCols; i++) {
                    for (int j = 0; j < speakerRows; j++) {
                        String speakerName = "free";
                        JWebBrowser browser = new JWebBrowser();
                        browser.setMenuBarVisible(false);
                        browser.setBarsVisible(false);
                        browser.setButtonBarVisible(false);
                        Speaker speaker = new Speaker(browser, speakerName,new JButton());
                        speakers.add(speaker);
                        GUIAccessManager.mf.getSpeakersPanel().add(browser);

                    }
                }

            /*JDialog control = new JDialog(GUIAccessManager.mf, "Chat Room", false);
            control.setDefaultCloseOperation(JDialog.DO_NOTHING_ON_CLOSE);
            control.setSize(350, 500);
            control.setContentPane(chatRoomTaskPane);
            control.setName("chatRoom");
            control.setLocation(ss.width - control.getWidth(), 100);
            control.setVisible(true);*/


            }
        });
        GUIAccessManager.mf.setVisible(true);
    }
}
