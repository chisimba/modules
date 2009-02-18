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
package avoir.realtime.classroom;

import avoir.realtime.classroom.notepad.JNotepad;
import avoir.realtime.common.Constants;
import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import javax.swing.DefaultListModel;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JList;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JScrollPane;
import javax.swing.ListSelectionModel;

/**
 *
 * @author developer
 */
public class ToolbarActionManager {

    private DefaultListModel notepadModel = new DefaultListModel();
    private final JList list = new JList(notepadModel);
    private final JButton openNotepadButton = new JButton("Open");
    private JButton newNotepadButton = new JButton("New");
    protected JPopupMenu pointerPopup = new JPopupMenu();
    protected JPanel popupPanel = new JPanel();
    private int notePadCount = 1;
    private JNotepad notepad;
    private JFrame notePadListFrame = new JFrame("Saved Notepads");
    private final String notepadHome = Constants.getRealtimeHome() + "/notepad/";
    protected Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private ClassroomMainFrame mf;

    public ToolbarActionManager(ClassroomMainFrame mf) {
        this.mf = mf;
        openNotepadButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                String filename = (String) list.getSelectedValue();
                openNotepad(filename);
            }
        });
        openNotepadButton.setEnabled(false);
        newNotepadButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                showNotepad();
            }
        });

        list.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        list.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                openNotepadButton.setEnabled(true);
                if (e.getClickCount() == 2) {
                    String filename = (String) list.getSelectedValue();
                    openNotepad(filename);
                }
            }
        });

        notePadListFrame.setAlwaysOnTop(true);
        notePadListFrame.setLayout(new BorderLayout());
        notePadListFrame.add(new JScrollPane(list), BorderLayout.CENTER);
        JPanel p = new JPanel();
        p.add(openNotepadButton);
        p.add(newNotepadButton);
        notePadListFrame.add(p, BorderLayout.SOUTH);
        notePadListFrame.setSize(300, 200);
        notePadListFrame.setLocation((ss.width) - notePadListFrame.getWidth(), 10);


    }

    public DefaultListModel getNotepadModel() {
        return notepadModel;
    }

    private void openNotepad(String filename) {
        notepad = new JNotepad(mf);
        notepad.openFile(notepadHome + "/" + filename);
        notepad.setTitle(filename);
        notepad.setAlwaysOnTop(true);
        notepad.setSize(400, 300);
        int offset = (notePadCount * 30);
        notepad.setLocation(((ss.width - notepad.getWidth())), offset);
        notepad.setVisible(true);
    }

    public JPopupMenu getPointerPopup() {
        return pointerPopup;
    }

    public void setPointerPopup(JPopupMenu pointerPopup) {
        this.pointerPopup = pointerPopup;
    }

    public JPanel getPopupPanel() {
        return popupPanel;
    }

    public void setPopupPanel(JPanel popupPanel) {
        this.popupPanel = popupPanel;
    }

   

    public void showNotepadList() {
        notepadModel.clear();
        String[] files = new File(notepadHome).list();

        if (files != null) {
            for (int i = 0; i < files.length; i++) {
                notepadModel.addElement(files[i]);
            }
        }
        notePadListFrame.setVisible(true);
    }

    public void showNotepad() {
        notepad = new JNotepad(mf);

        notepad.setTitle("Untitled " + (notePadCount++));
        notepad.setAlwaysOnTop(true);
        notepad.setSize(400, 300);
        int offset = (notePadCount * 30);
        notepad.setLocation(((ss.width - notepad.getWidth())), offset);
        notepad.setVisible(true);
    }
}
