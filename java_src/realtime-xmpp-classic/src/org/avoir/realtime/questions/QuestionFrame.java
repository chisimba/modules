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
 * QuestionFrame.java
 *
 * Created on 2008/12/09, 01:52:24
 */
package org.avoir.realtime.questions;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.EventObject;
import javax.swing.BorderFactory;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComponent;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JRadioButton;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextArea;
import javax.swing.JTree;
import javax.swing.SwingUtilities;
import javax.swing.event.CellEditorListener;
import javax.swing.event.ChangeEvent;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.EventListenerList;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.TableCellEditor;
import javax.swing.table.TableCellRenderer;
import javax.swing.table.TableColumn;
import javax.swing.tree.TreeCellEditor;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.RealtimeFileChooser;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.QuestionFrameListener;
import org.avoir.realtime.net.RPacketListener;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.net.providers.DownloadedImage;

/**
 *
 * @author developer
 */
public class QuestionFrame extends javax.swing.JFrame implements QuestionFrameListener {

    private RealtimeFileChooser realtimeFileChooser = new RealtimeFileChooser("images");
    private ImageIcon indicator = ImageUtil.createImageIcon(this, "/images/indicator.gif");
    private boolean saved = false;
    private ImageIcon questionImage = null;
    private String questionImagePath = null;
    private ArrayList<Value> oldMCQAnswers;
    private ArrayList<Value> oldTrueFalseAnswers;
    private ArrayList<Value> answers = new ArrayList<Value>();
    private String[] options = {"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N",
        "O", "P", "Q", "R", "S", "U", "V", "W", "X", "Y", "Z"
    };
    private String mode = "add";
    private boolean expand = false;
    private String access="private";
    AnswersTableModel model = new AnswersTableModel();
    String questionID = String.valueOf((long) (Long.MIN_VALUE *
            Math.random()));
    private JTable answersTable = new JTable(model) {

        @Override
        public TableCellRenderer getCellRenderer(int row, int column) {
            TableColumn tableColumn = getColumnModel().getColumn(column);
            TableCellRenderer renderer = tableColumn.getCellRenderer();
            if (renderer == null) {
                Class c = getColumnClass(column);
                if (c.equals(Object.class)) {
                    Object o = getValueAt(row, column);
                    if (o != null) {
                        c = getValueAt(row, column).getClass();
                    }
                }
                renderer = getDefaultRenderer(c);
            }
            return renderer;
        }

        @Override
        public TableCellEditor getCellEditor(int row, int column) {
            TableColumn tableColumn = getColumnModel().getColumn(column);
            TableCellEditor editor = tableColumn.getCellEditor();
            if (editor == null) {
                Class c = getColumnClass(column);
                if (c.equals(Object.class)) {
                    Object o = getValueAt(row, column);
                    if (o != null) {
                        c = getValueAt(row, column).getClass();
                    }
                }
                editor = getDefaultEditor(c);
            }
            return editor;
        }
    };
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private ImageIcon cancelIcon = ImageUtil.createImageIcon(this, "/images/cross16.png");
    private Surface surface = new Surface();
    boolean firstTimeType = true;
    private String questionName = null;
    private Question question = new Question();

    public String getAccess() {
        return access;
    }

    public void setAccess(String access) {
        this.access = access;
    }

    public boolean isSaved() {
        return saved;
    }

    public void setSaved(boolean saved) {
        this.saved = saved;
    }

    public String getQuestionName() {
        return questionName;
    }

    public void setQuestionName(String questionName) {
        this.questionName = questionName;
    }

    /** Creates new form QuestionFrame */
    public QuestionFrame() {

        initComponents();
        RPacketListener.addQuestionImageDownloadListener(this);
        mPanel.add(new JScrollPane(answersTable), BorderLayout.CENTER);

        questionNameField.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                if (firstTimeType && mode.equals("add")) {
                    questionNameField.setText("");
                    firstTimeType = false;
                }
            }
        });
        questionNameField.addKeyListener(new KeyAdapter() {

            @Override
            public void keyPressed(KeyEvent e) {
                if (firstTimeType && mode.equals("add")) {
                    questionNameField.setText("");
                    firstTimeType = false;
                }
            }
        });

        questionNameField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent arg0) {
                saved = false;
                previewButton.setEnabled(true);
                postButton.setEnabled(true);
                question.setQuestion(questionNameField.getText());
            }

            public void removeUpdate(DocumentEvent arg0) {
                saved = false;

                question.setQuestion(questionNameField.getText());
            }

            public void changedUpdate(DocumentEvent arg0) {
                question.setQuestion(questionNameField.getText());
            }
        });
        answersTable.setGridColor(new Color(238, 238, 238));
        answersTable.setShowHorizontalLines(false);
        answersTable.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        answersTable.setDefaultEditor(JComponent.class, new JComponentCellEditor());
        answersTable.setFont(new Font("Dialog", 0, 17));
        answersTable.setRowHeight(21);
        decorateTable();
        imagePanel.add(new JScrollPane(surface), BorderLayout.CENTER);

    }

    public void populateFields() {
        mode = "edit";
        postButton.setEnabled(true);
        questionNameField.setText(question.getQuestion());
        setTitle(questionName);
        if (question.getType() == Constants.ESSAY_QUESTION) {
            essayQnOpt.setSelected(true);
        }
        if (question.getType() == Constants.MCQ_QUESTION) {
            mcqQnOpt.setSelected(true);
            answers = question.getAnswerOptions();
            refresh();

        }
        if (question.getType() == Constants.TRUE_FALSE_QUESTION) {

            trueFalseOpt.setSelected(true);
            if (question.getAnswerOptions().size() == 2) {
                Value trueOption = question.getAnswerOptions().get(0);
                Value falseOption = question.getAnswerOptions().get(1);
                trueOpt.setSelected(trueOption.isCorrectAnswer());
                trueOpt.setEnabled(true);
                falseIOpt.setSelected(falseOption.isCorrectAnswer());
                falseIOpt.setEnabled(true);

            }
        }
    }

    public JTextArea getQuestionNameField() {
        return questionNameField;
    }

    public JButton getNewButton() {
        return newButton;
    }

    public ImageIcon getQuestionImage() {
        return questionImage;
    }

    public Question getQuestion() {
        return question;
    }

    private void refresh() {
        model = new AnswersTableModel();
        answersTable.setModel(model);
        decorateTable();

    }

    public void setQuestionImagePath(String questionImagePath) {
        this.questionImagePath = questionImagePath;
    }

    public void setQuestionImage(ImageIcon icon) {
        questionImage = icon;
        surface.repaint();
    }

    private void addNewAnswer() {
        if (question.getQuestion() == null) {
            JOptionPane.showMessageDialog(this, "Please supply the question first");
            return;
        }
        if (question.getQuestion().equals("")) {
            JOptionPane.showMessageDialog(this, "Please supply the question first");
            return;
        }

        String ans = JOptionPane.showInputDialog(this, "Enter the answer");
        if (ans != null) {
            if (ans.trim().equals("")) {
                return;
            }
            answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], ans, false));
            model = new AnswersTableModel();
            answersTable.setModel(model);
            question.setAnswerOptions(answers);
            decorateTable();
        }
    }

    private void decorateTable() {
        TableColumn column = null;
        for (int i = 0; i < model.getColumnCount(); i++) {
            column = answersTable.getColumnModel().getColumn(i);
            if (i == 1) {
                column.setPreferredWidth(350);
            } else if (i == 3) {
                column.setPreferredWidth(10);
            }
        }

    }

    class AnswersTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Option", //0
            "Value",
            "Select Correct Answer",
            "Delete"
        };
        int i = 0;

        public AnswersTableModel() {
            try {
                data = new Object[answers.size()][columnNames.length];

                ButtonGroup bg = new ButtonGroup();

                for (i = 0; i < answers.size(); i++) {
                    final int answerCount = i;
                    final JRadioButton ansRB = new JRadioButton();
                    bg.add(ansRB);
                    ansRB.addActionListener(new ActionListener() {

                        public void actionPerformed(ActionEvent arg0) {
                            int oldWidth = getWidth();
                            int oldHeight = getHeight();
                            if (expand) {
                                setSize(oldWidth + 1, oldHeight + 1);
                                expand = false;
                            } else {
                                setSize(oldWidth - 1, oldHeight - 1);
                                expand = true;
                            }

                            answers.get(answerCount).setCorrectAnswer(ansRB.isSelected());
                        }
                    });
                    ansRB.setBackground(Color.WHITE);


                    ansRB.setSelected(answers.get(i).isCorrectAnswer());
                    final JButton delButton = new JButton(cancelIcon);
                    delButton.setContentAreaFilled(false);
                    delButton.setBorder(BorderFactory.createEtchedBorder());
                    delButton.addActionListener(new ActionListener() {

                        public void actionPerformed(ActionEvent arg0) {
                            answers.remove(answerCount);
                            question.setAnswerOptions(answers);
                            refresh();
                        }
                    });

                    Object[] row = {answers.get(i).getOption(), answers.get(i).getValue(),
                        ansRB, delButton
                    };
                    data[i] = row;

                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        private Object[][] data = new Object[0][columnNames.length];

        public int getColumnCount() {
            return columnNames.length;
        }

        public int getRowCount() {
            return data.length;
        }

        @Override
        public String getColumnName(int col) {
            return columnNames[col];
        }

        public Object getValueAt(int row, int col) {
            return data[row][col];

        }

        @Override
        public boolean isCellEditable(int row, int col) {
            return col > 0 ? true : false;
        }

        @Override
        public void setValueAt(Object value, int row, int col) {
            if (col == 1) {
                answers.get(row).setValue(value + "");
            }
            int oldWidth = getWidth();
            int oldHeight = getHeight();
            if (expand) {
                setSize(oldWidth + 1, oldHeight + 1);
                expand = false;
            } else {
                setSize(oldWidth - 1, oldHeight - 1);
                expand = true;
            }
            data[row][col] = value;
            fireTableCellUpdated(row, col);
        }


        /*
         * JTable uses this method to determine the default renderer/
         * editor for each cell.  If we didn't implement this method,
         * then the last column would contain text ("true"/"false"),
         * rather than a check box.
         */
        @Override
        public Class getColumnClass(int c) {
            return getValueAt(0, c).getClass();
        }
    }

    class Surface extends JPanel {

        public Surface() {
            setBackground(Color.WHITE);
        }

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            Graphics2D g2 = (Graphics2D) g;
            if (questionImage != null) {
                g2.drawImage(questionImage.getImage(), 0, 0, this);
                setPreferredSize(new Dimension(questionImage.getIconWidth(), questionImage.getIconHeight()));
                revalidate();
            }
        }
    }

    class JComponentCellRenderer implements TableCellRenderer {

        public Component getTableCellRendererComponent(JTable table, Object value,
                boolean isSelected, boolean hasFocus, int row, int column) {
            return (JComponent) value;
        }
    }

    class JComponentCellEditor implements TableCellEditor, TreeCellEditor,
            Serializable {

        protected EventListenerList listenerList = new EventListenerList();
        transient protected ChangeEvent changeEvent = null;
        protected JComponent editorComponent = null;
        protected JComponent container = null;		// Can be tree or table

        public Component getComponent() {
            return editorComponent;
        }

        public Object getCellEditorValue() {
            return editorComponent;
        }

        public boolean isCellEditable(EventObject anEvent) {
            return true;
        }

        public boolean shouldSelectCell(EventObject anEvent) {
            if (editorComponent != null && anEvent instanceof MouseEvent && ((MouseEvent) anEvent).getID() == MouseEvent.MOUSE_PRESSED) {
                Component dispatchComponent = SwingUtilities.getDeepestComponentAt(editorComponent, 3, 3);
                MouseEvent e = (MouseEvent) anEvent;
                MouseEvent e2 = new MouseEvent(dispatchComponent, MouseEvent.MOUSE_RELEASED,
                        e.getWhen() + 100000, e.getModifiers(), 3, 3, e.getClickCount(),
                        e.isPopupTrigger());
                dispatchComponent.dispatchEvent(e2);
                e2 = new MouseEvent(dispatchComponent, MouseEvent.MOUSE_CLICKED,
                        e.getWhen() + 100001, e.getModifiers(), 3, 3, 1,
                        e.isPopupTrigger());
                dispatchComponent.dispatchEvent(e2);
            }
            return false;
        }

        public boolean stopCellEditing() {
            fireEditingStopped();
            return true;
        }

        public void cancelCellEditing() {
            fireEditingCanceled();
        }

        public void addCellEditorListener(CellEditorListener l) {
            listenerList.add(CellEditorListener.class, l);
        }

        public void removeCellEditorListener(CellEditorListener l) {
            listenerList.remove(CellEditorListener.class, l);
        }

        protected void fireEditingStopped() {
            Object[] listeners = listenerList.getListenerList();
            // Process the listeners last to first, notifying
            // those that are interested in this event
            for (int i = listeners.length - 2; i >= 0; i -= 2) {
                if (listeners[i] == CellEditorListener.class) {
                    // Lazily create the event:
                    if (changeEvent == null) {
                        changeEvent = new ChangeEvent(this);
                    }
                    ((CellEditorListener) listeners[i + 1]).editingStopped(changeEvent);
                }
            }
        }

        protected void fireEditingCanceled() {
            // Guaranteed to return a non-null array
            Object[] listeners = listenerList.getListenerList();
            // Process the listeners last to first, notifying
            // those that are interested in this event
            for (int i = listeners.length - 2; i >= 0; i -= 2) {
                if (listeners[i] == CellEditorListener.class) {
                    // Lazily create the event:
                    if (changeEvent == null) {
                        changeEvent = new ChangeEvent(this);
                    }
                    ((CellEditorListener) listeners[i + 1]).editingCanceled(changeEvent);
                }
            }
        }

        // implements javax.swing.tree.TreeCellEditor
        public Component getTreeCellEditorComponent(JTree tree, Object value,
                boolean isSelected, boolean expanded, boolean leaf, int row) {
            String stringValue = tree.convertValueToText(value, isSelected,
                    expanded, leaf, row, false);

            editorComponent = (JComponent) value;
            container = tree;
            return editorComponent;
        }

        // implements javax.swing.table.TableCellEditor
        public Component getTableCellEditorComponent(JTable table, Object value,
                boolean isSelected, int row, int column) {

            editorComponent = (JComponent) value;
            container = table;
            return editorComponent;
        }
    } // End of class JComponentCellEditor

    public JPanel getMainPanel() {
        return mainPanel;
    }

    public void processImage(DownloadedImage downloadedImage) {
        questionImagePath = downloadedImage.getImagePath();

        questionImage = downloadedImage.getImage();
        question.setImage(downloadedImage.getImage().getImage());
        surface.repaint();
    }

    private void showImageFileChooser() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_FILE_VIEW);
        StringBuilder sb = new StringBuilder();
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<file-type>").append("images").append("</file-type>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
        if (realtimeFileChooser.showDialog() == RealtimeFileChooser.APPROVE_OPTION) {
            RealtimeFile file = realtimeFileChooser.getSelectedFile();
            p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.DOWNLOAD_QUESTION_IMAGE);
            StringBuilder buf = new StringBuilder();
            buf.append("<image-path>").append(file.getFilePath()).append("</image-path>");
            p.setContent(buf.toString());
            ConnectionManager.sendPacket(p);
        }
    }

    private void save() {
    	//needs to check if question name already exists
        if (validQuestion()) {
            questionName = JOptionPane.showInputDialog("Question Name", questionName);
            if (questionName != null) {
                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.SAVE_QUESTION);
                StringBuilder sb = new StringBuilder();
                sb.append("<question-filename>").append(questionName).append("</question-filename>");
                sb.append("<question-type>").append(question.getType()).append("</question-type>");
                sb.append("<question-image-path>").append(questionImagePath).append("</question-image-path>");
                sb.append("<question>").append(question.getQuestion()).append("</question>");
                sb.append("<access>").append(access).append("</access>");
                sb.append("<question-username>").append(ConnectionManager.getUsername()).append("</question-username>");
                sb.append("<answers>");
                ArrayList<Value> ans = question.getAnswerOptions();
                for (int i = 0; i < ans.size(); i++) {
                    Value val = ans.get(i);
                    sb.append("<answer>");
                    sb.append("<option>").append(val.getOption()).append("</option>");
                    sb.append("<value>").append(val.getValue()).append("</value>");
                    sb.append("<correct-answer>").append(val.isCorrectAnswer() + "").append("</correct-answer>");
                    sb.append("</answer>");
                }
                sb.append("</answers>");
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);
                dispose();
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
        java.awt.GridBagConstraints gridBagConstraints;

        anserTypePanel = new javax.swing.JPanel();
        addAnserPanel = new javax.swing.JPanel();
        addAnswersButton = new javax.swing.JButton();
        bg = new javax.swing.ButtonGroup();
        bg2 = new javax.swing.ButtonGroup();
        mainPanel = new javax.swing.JPanel();
        splitPane3 = new javax.swing.JSplitPane();
        mPanel = new javax.swing.JPanel();
        answersTopPanel = new javax.swing.JPanel();
        jPanel1 = new javax.swing.JPanel();
        splitPane2 = new javax.swing.JSplitPane();
        jScrollPane1 = new javax.swing.JScrollPane();
        questionNameField = new javax.swing.JTextArea();
        imagePanel = new javax.swing.JPanel();
        bottomImagePanel = new javax.swing.JPanel();
        addAnswerButton = new javax.swing.JButton();
        imageButton = new javax.swing.JButton();
        clearButton = new javax.swing.JButton();
        optionsPanel = new javax.swing.JPanel();
        trueFalseOpt = new javax.swing.JRadioButton();
        essayQnOpt = new javax.swing.JRadioButton();
        mcqQnOpt = new javax.swing.JRadioButton();
        jLabel1 = new javax.swing.JLabel();
        jPanel2 = new javax.swing.JPanel();
        trueOpt = new javax.swing.JRadioButton();
        falseIOpt = new javax.swing.JRadioButton();
        questionNoField = new javax.swing.JLabel();
        cPanel = new javax.swing.JPanel();
        newButton = new javax.swing.JButton();
        previewButton = new javax.swing.JButton();
        saveButton = new javax.swing.JButton();
        postButton = new javax.swing.JButton();
        cancelButton = new javax.swing.JButton();
        tPanel = new javax.swing.JToolBar();

        anserTypePanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Select Question Type"));
        anserTypePanel.setLayout(new java.awt.BorderLayout());

        addAnswersButton.setText("Add Answer");
        addAnswersButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addAnswersButtonActionPerformed(evt);
            }
        });
        addAnserPanel.add(addAnswersButton);

        anserTypePanel.add(addAnserPanel, java.awt.BorderLayout.PAGE_END);

        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });

        mainPanel.setLayout(new java.awt.BorderLayout());

        splitPane3.setDividerLocation(250);
        splitPane3.setOrientation(javax.swing.JSplitPane.VERTICAL_SPLIT);

        mPanel.setPreferredSize(new java.awt.Dimension(193, 300));
        mPanel.setLayout(new java.awt.BorderLayout());

        answersTopPanel.setLayout(new java.awt.BorderLayout());
        mPanel.add(answersTopPanel, java.awt.BorderLayout.PAGE_START);

        splitPane3.setRightComponent(mPanel);

        jPanel1.setPreferredSize(new java.awt.Dimension(530, 300));
        jPanel1.setLayout(new java.awt.BorderLayout());

        questionNameField.setColumns(20);
        questionNameField.setFont(new java.awt.Font("Dialog", 0, 18));
        questionNameField.setRows(5);
        questionNameField.setText("Type the question here:");
        jScrollPane1.setViewportView(questionNameField);

        splitPane2.setLeftComponent(jScrollPane1);

        imagePanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        imagePanel.setPreferredSize(new java.awt.Dimension(200, 200));
        imagePanel.setLayout(new java.awt.BorderLayout());

        bottomImagePanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());

        addAnswerButton.setFont(new java.awt.Font("Dialog", 0, 12));
        addAnswerButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/add.png"))); // NOI18N
        addAnswerButton.setText("Add Answer");
        addAnswerButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addAnswerButtonActionPerformed(evt);
            }
        });
        bottomImagePanel.add(addAnswerButton);

        imageButton.setText("Add Image");
        imageButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                imageButtonActionPerformed(evt);
            }
        });
        bottomImagePanel.add(imageButton);

        clearButton.setText("Clear");
        clearButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                clearButtonActionPerformed(evt);
            }
        });
        bottomImagePanel.add(clearButton);

        imagePanel.add(bottomImagePanel, java.awt.BorderLayout.PAGE_END);

        optionsPanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        optionsPanel.setLayout(new java.awt.GridBagLayout());

        bg.add(trueFalseOpt);
        trueFalseOpt.setText("True/False:");
        trueFalseOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                trueFalseOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        optionsPanel.add(trueFalseOpt, gridBagConstraints);

        bg.add(essayQnOpt);
        essayQnOpt.setText("Essay Question");
        essayQnOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                essayQnOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        optionsPanel.add(essayQnOpt, gridBagConstraints);

        bg.add(mcqQnOpt);
        mcqQnOpt.setSelected(true);
        mcqQnOpt.setText("Multiple Choice Question");
        mcqQnOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                mcqQnOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        optionsPanel.add(mcqQnOpt, gridBagConstraints);

        jLabel1.setText("Question Type:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 7, 0);
        optionsPanel.add(jLabel1, gridBagConstraints);

        jPanel2.setBorder(javax.swing.BorderFactory.createTitledBorder("Select correct answer"));

        bg2.add(trueOpt);
        trueOpt.setText("True");
        trueOpt.setEnabled(false);
        trueOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                trueOptActionPerformed(evt);
            }
        });
        jPanel2.add(trueOpt);

        bg2.add(falseIOpt);
        falseIOpt.setText("False");
        falseIOpt.setEnabled(false);
        falseIOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                falseIOptActionPerformed(evt);
            }
        });
        jPanel2.add(falseIOpt);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 5;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        optionsPanel.add(jPanel2, gridBagConstraints);

        imagePanel.add(optionsPanel, java.awt.BorderLayout.LINE_START);

        splitPane2.setRightComponent(imagePanel);

        jPanel1.add(splitPane2, java.awt.BorderLayout.CENTER);

        questionNoField.setFont(new java.awt.Font("Dialog", 1, 18));
        questionNoField.setText("Question:");
        jPanel1.add(questionNoField, java.awt.BorderLayout.PAGE_START);

        splitPane3.setLeftComponent(jPanel1);

        mainPanel.add(splitPane3, java.awt.BorderLayout.CENTER);

        newButton.setText("New");
        newButton.setEnabled(false);
        newButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                newButtonActionPerformed(evt);
            }
        });
        cPanel.add(newButton);

        previewButton.setText("Preview");
        previewButton.setEnabled(false);
        previewButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                previewButtonActionPerformed(evt);
            }
        });
        cPanel.add(previewButton);

        saveButton.setText("Save");
        saveButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                saveButtonActionPerformed(evt);
            }
        });
        cPanel.add(saveButton);

        postButton.setText("Post Now");
        postButton.setEnabled(false);
        postButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                postButtonActionPerformed(evt);
            }
        });
        cPanel.add(postButton);

        cancelButton.setText("Close");
        cancelButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cancelButtonActionPerformed(evt);
            }
        });
        cPanel.add(cancelButton);

        mainPanel.add(cPanel, java.awt.BorderLayout.PAGE_END);

        tPanel.setFloatable(false);
        tPanel.setRollover(true);
        mainPanel.add(tPanel, java.awt.BorderLayout.PAGE_START);

        getContentPane().add(mainPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void saveButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_saveButtonActionPerformed
        save();

    }//GEN-LAST:event_saveButtonActionPerformed

    private void cancelButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cancelButtonActionPerformed
        RPacketListener.removeQuestionImageDownloadListener(this);
        dispose();
    }//GEN-LAST:event_cancelButtonActionPerformed

    private void addAnswersButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addAnswersButtonActionPerformed
        addNewAnswer();
    }//GEN-LAST:event_addAnswersButtonActionPerformed

    private void essayQnOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_essayQnOptActionPerformed
        question.setType(Constants.ESSAY_QUESTION);
        addAnswerButton.setEnabled(!essayQnOpt.isSelected());
        /* if (mode.equals("edit")) {
        if (qn.getType() == Constants.MCQ_QUESTION) {
        oldMCQAnswers = answers;
        }
        if (qn.getType() == Constants.TRUE_FALSE_QUESTION) {
        oldTrueFalseAnswers = answers;
        }*/
        oldMCQAnswers = answers;
        trueOpt.setEnabled(false);
        falseIOpt.setEnabled(false);
        answers.clear();
        refresh();

    }//GEN-LAST:event_essayQnOptActionPerformed

    private void mcqQnOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_mcqQnOptActionPerformed
        addAnswerButton.setEnabled(!essayQnOpt.isSelected());
        trueOpt.setEnabled(false);
        falseIOpt.setEnabled(false);
        if (oldMCQAnswers != null) {
            answers = oldMCQAnswers;
        }
        refresh();
    }//GEN-LAST:event_mcqQnOptActionPerformed

    private boolean validQuestion() {
        if (question.getQuestion() == null) {
            JOptionPane.showMessageDialog(this, "No Question was supplied");
            return false;
        }
        if (question.getQuestion().trim().equals("")) {
            JOptionPane.showMessageDialog(this, "No Question was supplied");
            return false;
        }
        if (question.getAnswerOptions().size() < 1 && question.getType() != Constants.ESSAY_QUESTION) {
            JOptionPane.showMessageDialog(this, "No answer options were supplied");
            return false;
        }
        ArrayList<Value> answerOptions = question.getAnswerOptions();
        boolean answerProvided = false;
        for (int i = 0; i < answerOptions.size(); i++) {
            if (answerOptions.get(i).isCorrectAnswer()) {
                answerProvided = true;
                break;
            }
        }
        if (!answerProvided && question.getType() != Constants.ESSAY_QUESTION) {
            JOptionPane.showMessageDialog(this, "No correct answer provided for the answer options");
            return false;
        }

        return true;
    }

    public JButton getPostButton() {
        return postButton;
    }

    private void postButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_postButtonActionPerformed
        if (validQuestion()) {
            postButton.setIcon(indicator);
            postButton.setEnabled(false);
        	questionName = JOptionPane.showInputDialog("Question Name", questionName);
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.POST_QUESTION);
            StringBuilder sb = new StringBuilder();
            sb.append("<filename>").append(questionName).append("</filename>");
            sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            sb.append("<access>").append(access).append("</access>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
        }
    }//GEN-LAST:event_postButtonActionPerformed

    private void trueFalseOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_trueFalseOptActionPerformed
        addAnswerButton.setEnabled(false);
        trueOpt.setEnabled(true);
        falseIOpt.setEnabled(true);
        oldMCQAnswers = answers;
        answers.clear();
        /*
        if (mode.equals("edit")) {
        
        if (oldTrueFalseAnswers != null) {
        answers = oldTrueFalseAnswers;
        }
        } else {

        answers.clear();
        answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], "True", false));
        answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], "False", false));
        }*/
        model = new AnswersTableModel();
        answersTable.setModel(model);
        decorateTable();
    }//GEN-LAST:event_trueFalseOptActionPerformed

    private void previewQuestion() {
        if (validQuestion()) {
            RealtimeQuestionPacket p = new RealtimeQuestionPacket();
            p.setQuestion(question.getQuestion());
            p.setQuestionType(question.getType());
            p.setFilename(questionName);
            p.setAnswerOptions(question.getAnswerOptions());

            p.setImage(questionImage);
            AnsweringFrame fr = new AnsweringFrame(p, false);
            fr.setSize(getSize());
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }
    }

    public void clearImage() {
        questionImage = null;
        surface.repaint();

        int x = splitPane2.getDividerLocation();
        splitPane2.setDividerLocation(x + 5);
        splitPane2.setDividerLocation(x - 5);
        splitPane2.repaint();
        question.setImage(null);
    }
    private void newButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_newButtonActionPerformed
    }//GEN-LAST:event_newButtonActionPerformed

    private void imageButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_imageButtonActionPerformed
        showImageFileChooser();
    }//GEN-LAST:event_imageButtonActionPerformed

    private void clearButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_clearButtonActionPerformed
        clearImage();

    }//GEN-LAST:event_clearButtonActionPerformed

    private void addAnswerButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addAnswerButtonActionPerformed
        addNewAnswer();
    }//GEN-LAST:event_addAnswerButtonActionPerformed

    private void trueOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_trueOptActionPerformed
        answers.clear();
        question.setType(Constants.TRUE_FALSE_QUESTION);
        answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], "True", true));
        answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], "False", false));
        question.setAnswerOptions(answers);
    }//GEN-LAST:event_trueOptActionPerformed

    private void falseIOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_falseIOptActionPerformed
        question.setType(Constants.TRUE_FALSE_QUESTION);
        answers.clear();
        answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], "True", false));
        answers.add(new Value(options[answers.size() > 0 ? (answers.size()) : 0], "False", true));
        question.setAnswerOptions(answers);
    }//GEN-LAST:event_falseIOptActionPerformed

    private void previewButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_previewButtonActionPerformed
        previewQuestion();
    }//GEN-LAST:event_previewButtonActionPerformed

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
        RPacketListener.removeQuestionImageDownloadListener(this);
        dispose();
    }//GEN-LAST:event_formWindowClosing

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel addAnserPanel;
    private javax.swing.JButton addAnswerButton;
    private javax.swing.JButton addAnswersButton;
    private javax.swing.JPanel anserTypePanel;
    private javax.swing.JPanel answersTopPanel;
    private javax.swing.ButtonGroup bg;
    private javax.swing.ButtonGroup bg2;
    private javax.swing.JPanel bottomImagePanel;
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton cancelButton;
    private javax.swing.JButton clearButton;
    private javax.swing.JRadioButton essayQnOpt;
    private javax.swing.JRadioButton falseIOpt;
    private javax.swing.JButton imageButton;
    private javax.swing.JPanel imagePanel;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JPanel mPanel;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JRadioButton mcqQnOpt;
    private javax.swing.JButton newButton;
    private javax.swing.JPanel optionsPanel;
    private javax.swing.JButton postButton;
    private javax.swing.JButton previewButton;
    private javax.swing.JTextArea questionNameField;
    private javax.swing.JLabel questionNoField;
    private javax.swing.JButton saveButton;
    private javax.swing.JSplitPane splitPane2;
    private javax.swing.JSplitPane splitPane3;
    private javax.swing.JToolBar tPanel;
    private javax.swing.JRadioButton trueFalseOpt;
    private javax.swing.JRadioButton trueOpt;
    // End of variables declaration//GEN-END:variables
}
