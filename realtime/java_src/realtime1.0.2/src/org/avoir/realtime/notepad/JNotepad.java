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

/*
 * JNotepad.java
 *
 * Created on 2008/12/05, 08:31:39
 */
package org.avoir.realtime.notepad;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;

import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Insets;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutput;
import java.io.ObjectOutputStream;
import java.io.Reader;
import java.io.Serializable;
import java.io.Writer;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Hashtable;
import java.util.List;
import javax.swing.AbstractAction;
import javax.swing.Action;
import javax.swing.ActionMap;
import javax.swing.Icon;
import javax.swing.InputMap;
import javax.swing.JButton;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;
import javax.swing.KeyStroke;
import javax.swing.SwingUtilities;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.UndoableEditEvent;
import javax.swing.event.UndoableEditListener;
import javax.swing.text.BadLocationException;
import javax.swing.text.Document;
import javax.swing.text.Segment;
import javax.swing.text.SimpleAttributeSet;
import javax.swing.text.StyledEditorKit;
import javax.swing.text.TextAction;
import javax.swing.undo.CannotRedoException;
import javax.swing.undo.CannotUndoException;
import javax.swing.undo.UndoManager;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.RealtimeFileChooser;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimeNotepadPacket;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.util.Base64;

/**
 *
 * @author developer
 */
public class JNotepad extends javax.swing.JFrame implements ActionListener {

    /**
     * Listener for the edits on the current document.
     */
    protected UndoableEditListener undoHandler = new UndoHandler();
    /** UndoManager that we add edits to. */
    protected UndoManager undo = new UndoManager();
    private Hashtable commands;
    private JPanel statusBar = new JPanel();
    private final List<String> words = null;
    private static final String COMMIT_ACTION = "commit";
    private boolean saved = true;
    private String state = "new";
    private String openedFilename = "";
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private RealtimeFileChooser realtimeFileChooser = new RealtimeFileChooser("notepad");

    private static enum Mode {

        INSERT, COMPLETION
    };
    private Mode mode = Mode.INSERT;

    public JNotepad(Document doc, String title) {
        this();
        setContent(doc, title);
    }

    /** Creates new form JNotepad */
    public JNotepad() {
        initComponents();

        add(statusBar, BorderLayout.SOUTH);
        // install the command table
        commands = new Hashtable();
        Action[] actions = getActions();
        for (int i = 0; i < actions.length; i++) {
            Action a = actions[i];
            commands.put(a.getValue(Action.NAME), a);
        }
        buildButtons();
        contentField.setFont(new Font("SansSerif", 0, 18));
        createFileMenu();
        createColorMenu();
        InputMap im = contentField.getInputMap();
        ActionMap am = contentField.getActionMap();
        im.put(KeyStroke.getKeyStroke("ENTER"), COMMIT_ACTION);
        am.put(COMMIT_ACTION, new CommitAction());
        //words = readFile(Constants.getRealtimeHome() + "/org/avoir/realtime/notepad/resources/englishwords.png");
        contentField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent evt) {
                monitorTyping(evt);
                saved = false;
            }

            public void removeUpdate(DocumentEvent arg0) {
                saved = false;
            }

            public void changedUpdate(DocumentEvent arg0) {
                saved = false;
            }
        });
        this.addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                close();
            }
        });
    }

    public static ArrayList<String> readFile(String file) {
        ArrayList<String> lines = new ArrayList<String>();

        try {
            BufferedReader in = new BufferedReader(new FileReader(file));
            String line;
            while ((line = in.readLine()) != null) {
                lines.add(line);
            }

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return lines;

    }

    protected void createToolbarButton(String imagePath, String astr, String tip) {

        final JButton b = new JButton(ImageUtil.createImageIcon(this, imagePath));

        b.setRequestFocusEnabled(false);
        b.setMargin(new Insets(1, 1, 1, 1));


        Action a = getAction(astr);
        if (a != null) {
            b.setActionCommand(astr);
            b.addActionListener(a);
        } else {
            b.setEnabled(false);
        }

        if (tip != null) {
            b.setToolTipText(tip);
        }

        toolbar.add(b);
    }

    private void createButton(String imagePath, String action, String tooltip) {
        final JButton b = new JButton(ImageUtil.createImageIcon(this, imagePath));
        b.setActionCommand(action);
        b.addActionListener(this);
        b.setToolTipText(tooltip);
        b.setRequestFocusEnabled(false);
        b.setMargin(new Insets(1, 1, 1, 1));
        toolbar.add(b);
    }

    protected Action getAction(String cmd) {

        return (Action) commands.get(cmd);
    }

    private void setContent(Document doc, String filename) {
        try {
            if (contentField.getDocument() != null) {
                contentField.getDocument().removeUndoableEditListener(undoHandler);
            }
            contentField.setDocument(doc);
            doc.addUndoableEditListener(undoHandler);
            resetUndoManager();
            setTitle(filename);
            validate();
            openedFilename = filename;
            state = "edit";
            contentField.getDocument().addDocumentListener(new DocumentListener() {

                public void insertUpdate(DocumentEvent evt) {
                    monitorTyping(evt);
                    saved = false;
                }

                public void removeUpdate(DocumentEvent arg0) {
                    saved = false;
                }

                public void changedUpdate(DocumentEvent arg0) {
                    saved = false;
                }
            });
        } catch (Exception cnf) {
            // should put in status panel
            System.err.println("Class not found: " + cnf.getMessage());
        }

    }

    private String saveLocal(File f) {
        File tmpfile = new File(System.getProperty("user.home") + "/tmp");
        tmpfile.mkdirs();
        try {
            String path = tmpfile.getAbsolutePath();
            String name = f.getName();
            if (!name.endsWith(".np")) {
                name += ".np";
            }
            String savePath = new File(path + "/" + name).getAbsolutePath();
            FileOutputStream fstrm = new FileOutputStream(savePath);
            ObjectOutput ostrm = new ObjectOutputStream(fstrm);
            ostrm.writeObject(contentField.getDocument());
            ostrm.flush();
            setTitle(name);
            saved = true;
            return savePath;
        } catch (IOException io) {
            // should put in status panel
            JOptionPane.showMessageDialog(this, "Error: could not save the notepad");
            System.err.println("IOException: " + io.getMessage());
        }
        return null;
    }

    private void showOpenDialog() {
     /*   RealtimeFileViewPacket fvh = new RealtimeFileViewPacket();
        fvh.setDirPath(ConnectionManager.getUsername());
        fvh.setFileType("notepads");
        realtimeFileChooser.setSelectButtonText("Open");
        fvh.setType(IQ.Type.GET);
//        ConnectionManager.getRealtimePacketProvider().addRealtimePacketListener(realtimeFileChooser);
        ConnectionManager.sendPacket(fvh);
        if (realtimeFileChooser.showDialog() == RealtimeFileChooser.APPROVE_OPTION) {

            RealtimeFile file = realtimeFileChooser.getSelectedFile();

            RealtimeNotepadPacket p = new RealtimeNotepadPacket();
            p.setPacketType("notepad-open");
            p.setFilename(file.getFileName());
            p.setUsername(ConnectionManager.getUsername());
            p.setType(IQ.Type.GET);*/

            //listen for reply
/*            ConnectionManager.getRealtimePacketProvider().addRealtimePacketListener(new RealtimePacketListener() {

                public void processPacket(RealtimePacket packet) {

                    if (packet instanceof RealtimeNotepadPacket) {
                        RealtimeNotepadPacket p = (RealtimeNotepadPacket) packet;
                        String content = p.getContent();
                        String tmpFile = System.getProperty("user.home") + "/tmp/tmp.np";
                        Base64.decodeToFile(content, tmpFile);
                        openTmpFile(tmpFile, p.getFilename());
                    }
                }
            });
            ConnectionManager.sendPacket(p);

        }*/
    }

    private void openTmpFile(String filePath, String title) {

        File f = new File(filePath);
        if (f.exists()) {

            try {
                FileInputStream fin = new FileInputStream(f);
                ObjectInputStream istrm = new ObjectInputStream(fin);
                Document doc = (Document) istrm.readObject();
                GUIAccessManager.mf.showNotepad(doc, title);
            } catch (IOException io) {
                // should put in status panel
                io.printStackTrace();
            } catch (ClassNotFoundException cnf) {
                // should put in status panel
                cnf.printStackTrace();
            }
        } else {
            System.out.println("file " + f.getAbsolutePath() + " not found");
        }
    }

    private void initSave() {

        String filename = JOptionPane.showInputDialog("Notepad Name:", openedFilename);
        if (filename == null) {
            return;
        }
        if (filename.trim().equals("")) {
            JOptionPane.showMessageDialog(this, "Empty name not allowed");
            return;
        }
        String savePath = saveLocal(new File(filename));
        if (savePath != null) {
            saved = true;
            openedFilename = filename;
            setTitle(filename);
            RealtimeNotepadPacket p = new RealtimeNotepadPacket();

            p.setPacketType("notepad-save");
            p.setFilename(filename);
            p.setUsername(ConnectionManager.getUsername());
            String content = Base64.encodeFromFile(savePath);
            p.setContent(content);
            p.setType(IQ.Type.GET);
            //ConnectionManager.getRealtimePacketProvider().addRealtimePacketListener(GUIAccessManager.mf.getNavigator());
            ConnectionManager.sendPacket(p);
        }
    }

    class Doc implements Serializable {

        private Document doc;

        public Doc(Document doc) {
            this.doc = doc;
        }

        public Document getDoc() {
            return doc;
        }

        public void setDoc(Document doc) {
            this.doc = doc;
        }
    }

    private void close() {
        if (!saved) {
            int n = JOptionPane.showConfirmDialog(this, getTitle() + " notepad is not saved. Save it?",
                    "Save", JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                initSave();
            } else {
                dispose();
            }
        } else {
            dispose();
        }
    }

    public void actionPerformed(
            ActionEvent evt) {

        if (evt.getActionCommand().equals("new")) {
            GUIAccessManager.mf.showNotepad();
        }
        if (evt.getActionCommand().equals("exit")) {

            close();
        }
        if (evt.getActionCommand().equals("save")) {
            initSave();
        }
        if (evt.getActionCommand().equals("open")) {
            showOpenDialog();
        }
    }

    private void createFileMenu() {
        fileMenu.add(createMenuItem("New", "new"));
        fileMenu.add(createMenuItem("Open", "open"));
        fileMenu.add(createMenuItem("Save", "save"));
        fileMenu.addSeparator();
        fileMenu.add(createMenuItem("Exit", "exit"));
    }

    private void createColorMenu() {
        ActionListener a;
        JMenuItem mi;

        mi = new JMenuItem("Red");
        mi.setHorizontalTextPosition(JButton.RIGHT);
        mi.setIcon(new ColoredSquare(Color.red));
        a = new StyledEditorKit.ForegroundAction("set-foreground-red", Color.red);
        //a = new ColorAction(se, Color.red);
        mi.addActionListener(a);
        colorMenu.add(mi);
        mi = new JMenuItem("Green");
        mi.setHorizontalTextPosition(JButton.RIGHT);
        mi.setIcon(new ColoredSquare(Color.green));
        a = new StyledEditorKit.ForegroundAction("set-foreground-green", Color.green);
        //a = new ColorAction(se, Color.green);
        mi.addActionListener(a);
        colorMenu.add(mi);
        mi = new JMenuItem("Blue");
        mi.setHorizontalTextPosition(JButton.RIGHT);
        mi.setIcon(new ColoredSquare(Color.blue));
        a = new StyledEditorKit.ForegroundAction("set-foreground-blue", Color.blue);
        //a = new ColorAction(se, Color.blue);
        mi.addActionListener(a);
        colorMenu.add(mi);

        mi = new JMenuItem("Black");
        mi.setHorizontalTextPosition(JButton.RIGHT);
        mi.setIcon(new ColoredSquare(Color.BLACK));
        a = new StyledEditorKit.ForegroundAction("set-foreground-black", Color.BLACK);
        //a = new ColorAction(se, Color.blue);
        mi.addActionListener(a);
        colorMenu.add(mi);

    }

    private void buildButtons() {

        String[][] buttons = {
            {"/org/avoir/realtime/notepad/resources/new.gif", "new", "New note"},
            {"/org/avoir/realtime/notepad/resources/open.gif", "open", "Open note"},
            {"/org/avoir/realtime/notepad/resources/save.gif", "save", "Save note"},};
        String[][] editorbuttons = {
            {"/org/avoir/realtime/notepad/resources/cut.gif", "cut-to-clipboard", "Cut"},
            {"/org/avoir/realtime/notepad/resources/paste.gif", "paste-from-clipboard", "Paste"},
            {"/org/avoir/realtime/notepad/resources/bold.gif", "font-bold", "Bold"},
            {"/org/avoir/realtime/notepad/resources/italic.gif", "font-italic", "Italic"},
            {"/org/avoir/realtime/notepad/resources/underline.gif", "font-underline", "Underline"},
            {"/org/avoir/realtime/notepad/resources/left.gif", "left-justify", "Left justify"},
            {"/org/avoir/realtime/notepad/resources/center.gif", "center-justify", "Center justify"},
            {"/org/avoir/realtime/notepad/resources/right.gif", "right-justify", "Right justify"},};
        for (int i = 0; i < buttons.length; i++) {
            createButton(buttons[i][0], buttons[i][1], buttons[i][2]);
        }

        for (int i = 0; i < editorbuttons.length; i++) {
            createToolbarButton(editorbuttons[i][0], editorbuttons[i][1], editorbuttons[i][2]);
        }

    }
    private UndoAction undoAction = new UndoAction();
    private RedoAction redoAction = new RedoAction();
    /**
     * Actions defined by the Notepad class
     */
    private Action[] defaultActions = {
        undoAction,
        redoAction
    };

    /**
     * Fetch the list of actions supported by this
     * editor.  It is implemented to return the list
     * of actions supported by the embedded JTextComponent
     * augmented with the actions defined locally.
     */
    public Action[] getActions() {
        return TextAction.augmentList(contentField.getActions(), defaultActions);
    }

    class UndoAction extends AbstractAction {

        public UndoAction() {
            super("Undo");
            setEnabled(false);
        }

        public void actionPerformed(ActionEvent e) {
            try {
                undo.undo();
            } catch (CannotUndoException ex) {
                System.out.println("Unable to undo: " + ex);
                ex.printStackTrace();
            }
            update();
            redoAction.update();
        }

        protected void update() {
            if (undo.canUndo()) {
                setEnabled(true);
                putValue(Action.NAME, undo.getUndoPresentationName());
            } else {
                setEnabled(false);
                putValue(Action.NAME, "Undo");
            }
        }
    }

    class RedoAction extends AbstractAction {

        public RedoAction() {
            super("Redo");
            setEnabled(false);
        }

        public void actionPerformed(ActionEvent e) {
            try {
                undo.redo();
            } catch (CannotRedoException ex) {
                System.out.println("Unable to redo: " + ex);
                ex.printStackTrace();
            }
            update();
            undoAction.update();
        }

        protected void update() {
            if (undo.canRedo()) {
                setEnabled(true);
                putValue(Action.NAME, undo.getRedoPresentationName());
            } else {
                setEnabled(false);
                putValue(Action.NAME, "Redo");
            }
        }
    }

    class UndoHandler implements UndoableEditListener {

        /**
         * Messaged when the Document has created an edit, the edit is
         * added to <code>undo</code>, an instance of UndoManager.
         */
        public void undoableEditHappened(UndoableEditEvent e) {
            undo.addEdit(e.getEdit());
            undoAction.update();
            redoAction.update();
        }
    }

    /**
     * Resets the undo manager.
     */
    protected void resetUndoManager() {
        undo.discardAllEdits();
        undoAction.update();
        redoAction.update();
    }

    private JMenuItem createMenuItem(String txt, String action) {
        JMenuItem menuItem = new JMenuItem(txt);
        menuItem.setActionCommand(action);
        menuItem.addActionListener(this);
        return menuItem;
    }

    /**
     * Thread to load a file into the text storage model
     */
    class FileLoader extends Thread {

        FileLoader(File f, Document doc) {
            setPriority(4);
            this.f = f;
            this.doc = doc;
        }

        public void run() {
            try {
                // initialize the statusbar
                statusBar.removeAll();
                JProgressBar progress = new JProgressBar();
                progress.setMinimum(0);
                progress.setMaximum((int) f.length());
                statusBar.add(progress);
                statusBar.revalidate();

                // try to start reading
                Reader in = new FileReader(f);
                char[] buff = new char[4096];
                int nch;
                while ((nch = in.read(buff, 0, buff.length)) != -1) {
                    doc.insertString(doc.getLength(), new String(buff, 0, nch), null);
                    progress.setValue(progress.getValue() + nch);
                }
            } catch (IOException e) {
                final String msg = e.getMessage();
                SwingUtilities.invokeLater(new Runnable() {

                    public void run() {
                        JOptionPane.showMessageDialog(JNotepad.this,
                                "Could not open file: " + msg,
                                "Error opening file",
                                JOptionPane.ERROR_MESSAGE);
                    }
                });
            } catch (BadLocationException e) {
                System.err.println(e.getMessage());
            }
            doc.addUndoableEditListener(undoHandler);
            // we are done... get rid of progressbar
            statusBar.removeAll();
            statusBar.revalidate();

            resetUndoManager();

        }
        Document doc;
        File f;
    }

    /**
     * Thread to save a document to file
     */
    class FileSaver extends Thread {

        Document doc;
        File f;

        FileSaver(File f, Document doc) {
            setPriority(4);
            this.f = f;
            this.doc = doc;
        }

        public void run() {
            try {
                // initialize the statusbar
                statusBar.removeAll();
                JProgressBar progress = new JProgressBar();
                progress.setMinimum(0);
                progress.setMaximum((int) doc.getLength());
                statusBar.add(progress);
                statusBar.revalidate();

                // start writing
                Writer out = new FileWriter(f);
                Segment text = new Segment();
                text.setPartialReturn(true);
                int charsLeft = doc.getLength();
                int offset = 0;
                while (charsLeft > 0) {
                    doc.getText(offset, Math.min(4096, charsLeft), text);
                    out.write(text.array, text.offset, text.count);
                    charsLeft -= text.count;
                    offset += text.count;
                    progress.setValue(offset);
                    try {
                        Thread.sleep(10);
                    } catch (InterruptedException e) {
                        e.printStackTrace();
                    }
                }
                out.flush();
                out.close();
            } catch (IOException e) {
                final String msg = e.getMessage();
                SwingUtilities.invokeLater(new Runnable() {

                    public void run() {
                        JOptionPane.showMessageDialog(JNotepad.this,
                                "Could not save file: " + msg,
                                "Error saving file",
                                JOptionPane.ERROR_MESSAGE);
                    }
                });
            } catch (BadLocationException e) {
                System.err.println(e.getMessage());
            }
            // we are done... get rid of progressbar
            statusBar.removeAll();
            statusBar.revalidate();
        }
    }

    class ColoredSquare implements Icon {

        Color color;

        public ColoredSquare(Color c) {
            this.color = c;
        }

        public void paintIcon(Component c, Graphics g, int x, int y) {
            Color oldColor = g.getColor();
            g.setColor(color);
            g.fill3DRect(x, y, getIconWidth(), getIconHeight(), true);
            g.setColor(oldColor);
        }

        public int getIconWidth() {
            return 12;
        }

        public int getIconHeight() {
            return 12;
        }
    }

    private void monitorTyping(DocumentEvent ev) {
        if (ev.getLength() != 1) {
            return;
        }
        if (words == null) {
            return;
        }
        int pos = ev.getOffset();
        String content = null;
        try {
            content = contentField.getText(0, pos + 1);
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
            int pos = contentField.getSelectionEnd();
            SimpleAttributeSet st = new SimpleAttributeSet();
            // StyleConstants.setFontSize(st, 10);
            // StyleConstants.setForeground(st, Color.GRAY);
            try {
                contentField.getDocument().insertString(pos, completion, st);
            } catch (Exception ex) {
                ex.printStackTrace();
            }

            contentField.setCaretPosition(position + completion.length());
            contentField.moveCaretPosition(position);
            mode = Mode.COMPLETION;
        }
    }

    private class CommitAction extends AbstractAction {

        public void actionPerformed(ActionEvent ev) {
            if (mode == Mode.COMPLETION) {
                int pos = contentField.getSelectionEnd();
                SimpleAttributeSet st = new SimpleAttributeSet();
                // StyleConstants.setFontSize(st, 10);
                // StyleConstants.setForeground(st, Color.GRAY);
                try {
                    contentField.getDocument().insertString(pos, "", st);
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
                contentField.setCaretPosition(pos + 1);
                mode = Mode.INSERT;
            } else {
                contentField.replaceSelection("\n");
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

        toolbar = new javax.swing.JToolBar();
        jScrollPane1 = new javax.swing.JScrollPane();
        contentField = new javax.swing.JTextPane();
        menuBar = new javax.swing.JMenuBar();
        fileMenu = new javax.swing.JMenu();
        editMenuItem = new javax.swing.JMenu();
        colorMenu = new javax.swing.JMenu();

        setTitle("Notepad");

        toolbar.setRollover(true);
        getContentPane().add(toolbar, java.awt.BorderLayout.PAGE_START);

        jScrollPane1.setViewportView(contentField);

        getContentPane().add(jScrollPane1, java.awt.BorderLayout.CENTER);

        fileMenu.setText("File");
        menuBar.add(fileMenu);

        editMenuItem.setText("Edit");
        menuBar.add(editMenuItem);

        colorMenu.setText("Color");
        menuBar.add(colorMenu);

        setJMenuBar(menuBar);

        pack();
    }// </editor-fold>//GEN-END:initComponents
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JMenu colorMenu;
    private javax.swing.JTextPane contentField;
    private javax.swing.JMenu editMenuItem;
    private javax.swing.JMenu fileMenu;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JMenuBar menuBar;
    private javax.swing.JToolBar toolbar;
    // End of variables declaration//GEN-END:variables
}
