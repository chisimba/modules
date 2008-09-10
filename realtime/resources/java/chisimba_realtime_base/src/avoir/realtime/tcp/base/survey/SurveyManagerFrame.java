/*
 * SurveyFrame.java
 *
 * Created on 21 April 2008, 04:25
 */
package avoir.realtime.tcp.base.survey;

import avoir.realtime.tcp.base.ImageUtil;
import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.packet.ClearVotePacket;
import avoir.realtime.tcp.common.packet.SurveyPackPacket;
import avoir.realtime.tcp.common.packet.VotePacket;
import java.awt.BorderLayout;
import java.util.Vector;
import javax.swing.JTable;
import javax.swing.JScrollPane;
import javax.swing.JOptionPane;
import javax.swing.event.*;
import javax.swing.ListSelectionModel;
import javax.swing.JFileChooser;
import java.io.File;
import java.io.FileInputStream;
import java.io.ObjectInputStream;
import java.io.FileOutputStream;
import java.io.ObjectOutputStream;
import java.awt.event.*;
import javax.swing.JComponent;
import java.util.EventObject;
import java.io.Serializable;
import java.awt.Component;
import javax.swing.table.*;
import javax.swing.tree.*;
import java.awt.Color;
import java.awt.Font;
import javax.swing.JProgressBar;
import javax.swing.JPanel;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.text.DecimalFormat;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JLabel;

/**
 *
 * @author  developer
 */
public class SurveyManagerFrame extends javax.swing.JFrame {

    RealtimeBase base;
    private boolean saved = true;
    Vector<String> questions = new Vector<String>();
    private Vector<Answer> answers = new Vector<Answer>();
    private JLabel timerField = new JLabel();
    QuestionsTableModel model;//= new QuestionsTableModel(questions);
    private Timer timer;
    boolean paused = false;
    private JButton pauseButton = new JButton("Pause");
    JTable table = new JTable() {

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
    int selectedRow = 0;
    JFileChooser fc = new JFileChooser();

    /** Creates new form SurveyFrame */
    public SurveyManagerFrame(RealtimeBase base) {
        this.base = base;
        initComponents();
        pauseButton.setEnabled(false);
        model = new QuestionsTableModel(questions);
        questionsPanel.add(new JScrollPane(table));
        table.setShowGrid(false);
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        this.addWindowListener(new WindowAdapter() {

            public void windowClosing(WindowEvent e) {
                close();
            }
        });

        //Ask to be notified of selection changes.
        ListSelectionModel rowSM = table.getSelectionModel();
        rowSM.addListSelectionListener(new ListSelectionListener() {

            public void valueChanged(ListSelectionEvent e) {
                //Ignore extra messages.
                if (e.getValueIsAdjusting()) {
                    return;
                }

                ListSelectionModel lsm =
                        (ListSelectionModel) e.getSource();
                if (lsm.isSelectionEmpty()) {
                    delButton.setEnabled(false);
                } else {
                    selectedRow = lsm.getMinSelectionIndex();
                    delButton.setEnabled(true);
                }
            }
        });
        table.setModel(model);
        openButton.setIcon(ImageUtil.createImageIcon(this, "/icons/open.gif"));
        openButton.setBorderPainted(false);
        openButton.setContentAreaFilled(false);
        openButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        openButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        openButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                openButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                openButton.setContentAreaFilled(false);
            }
        });
        openButton.setFont(new java.awt.Font("Dialog", 0, 9));

        saveButton.setIcon(ImageUtil.createImageIcon(this, "/icons/save.gif"));
        saveButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        saveButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        saveButton.setFont(new java.awt.Font("Dialog", 0, 9));
        saveButton.setBorderPainted(false);
        saveButton.setContentAreaFilled(false);
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
        newButton.setIcon(ImageUtil.createImageIcon(this, "/icons/new.gif"));
        newButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        newButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        newButton.setFont(new java.awt.Font("Dialog", 0, 9));
        newButton.setBorderPainted(false);
        newButton.setContentAreaFilled(false);
        newButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                newButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                newButton.setContentAreaFilled(false);
            }
        });
        delButton.setIcon(ImageUtil.createImageIcon(this, "/icons/no.png"));
        delButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        delButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        delButton.setFont(new java.awt.Font("Dialog", 0, 9));
        delButton.setBorderPainted(false);
        delButton.setContentAreaFilled(false);
        delButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                delButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                delButton.setContentAreaFilled(false);
            }
        });
        decorateTable();
    }

    private void decorateTable() {
        TableColumn column = null;
        for (int i = 0; i < model.getColumnCount(); i++) {
            column = table.getColumnModel().getColumn(i);
            if (i == 1) {
                column.setPreferredWidth(350);
            }
        }

    }

    private void saveSurvey() {
        String surveyTitle = titleField.getText();
        if (surveyTitle.trim().equals("")) {
            JOptionPane.showMessageDialog(null, "Provide the title of the survey",
                    "Title", JOptionPane.ERROR_MESSAGE);
            return;
        }
        if (fc.showSaveDialog(this) == JFileChooser.APPROVE_OPTION) {
            File f = fc.getSelectedFile();
            String filename = f.getAbsolutePath();
            String ext = ".suv";
            if (!filename.endsWith(ext)) {
                filename += ext;
            }
            try {
                FileOutputStream f_out = new FileOutputStream(filename);
                ObjectOutputStream obj_out = new ObjectOutputStream(f_out);
                obj_out.writeObject(new Survey(questions, surveyTitle));
                f_out.close();
                saved = true;
                setTitle("Survey Manager - " + f.getName());
            } catch (Exception ex) {
                ex.printStackTrace();
                JOptionPane.showMessageDialog(this, "Error saving the survey");
            }
        }
    }

    private void clearSurvey() {
        int n = JOptionPane.showConfirmDialog(this, "Are you sure you want to clear" +
                " this survey?", "Clear", JOptionPane.YES_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            questions.clear();

            model = new QuestionsTableModel(questions);
            table.setModel(model);
            decorateTable();
            saved = false;
        }
    }

    private void openSurvey() {
        if (fc.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {
            File f = fc.getSelectedFile();
            String filename = f.getAbsolutePath();
            try {
                FileInputStream f_in = new FileInputStream(filename);
                ObjectInputStream obj_in =
                        new ObjectInputStream(f_in);
                Survey survey = (Survey) obj_in.readObject();
                questions = survey.getQuestions();
                model = new QuestionsTableModel(questions);
                table.setModel(model);
                startButton.setEnabled(questions.size() > 0);
                decorateTable();
                titleField.setText(survey.getTitle());
                setTitle("Survey Manager - " + survey.getTitle());
            } catch (Exception ex) {
                ex.printStackTrace();
                JOptionPane.showMessageDialog(this, "Error opening survey");
            }
        }
    }

    public void setSurveyAnswer(int row, boolean yes) {
        //  JOptionPane.showMessageDialog(null, "Received "+yes+" for question "+row);
       /* Integer yesCount = (Integer) model.getValueAt(row, 2);
        Integer noCount = (Integer) model.getValueAt(row, 3);
        if (yes) {
        int val = yesCount.intValue() + 1;
        model.setValueAt(new Integer(val), row, 2);
        } else {
        int val = noCount.intValue() + 1;
        model.setValueAt(new Integer(val), row, 3);
        
        }*/
        JProgressBar p = (JProgressBar) model.getValueAt(row, 2);
        p.setMinimum(0);
        p.setMaximum(base.getUserManager().getUserCount() - 1);

        Answer ans = answers.elementAt(row);
        if (yes) {
            ans.setYes(ans.getYes() + 1);
        } else {
            ans.setNo(ans.getNo() + 1);
        }
        answers.set(row, ans);
        int yesCount = answers.elementAt(row).getYes();
        int noCount = answers.elementAt(row).getNo();

        if (yesCount == noCount) {
            p.setForeground(new Color(0, 131, 0));
            p.setValue(yesCount);
        }
        if (yesCount < noCount) {
            p.setForeground(Color.RED);
            p.setValue(noCount);
        }
        if (yesCount > noCount) {
            p.setForeground(new Color(0, 131, 0));
            p.setValue(yesCount);
        }

        model.setValueAt(p, row, 2);
    }

    private void close() {
        if (!saved) {
            int n = JOptionPane.showConfirmDialog(null, "There is unsaved survey" +
                    ". Save? ", "Save", JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                saveSurvey();
            }
        }
        if (timer != null) {
            timer.cancel();
        }
        dispose();
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    // <editor-fold defaultstate="collapsed" desc="Generated Code">                          
    private void initComponents() {
        java.awt.GridBagConstraints gridBagConstraints;

        bg = new javax.swing.ButtonGroup();
        bg2 = new javax.swing.ButtonGroup();
        jLabel1 = new javax.swing.JLabel();
        tabbedPane = new javax.swing.JTabbedPane();
        questionsPanel = new javax.swing.JPanel();
        cPanel = new javax.swing.JPanel();
        startButton = new javax.swing.JButton();
        endButton = new javax.swing.JButton();
        saveButton = new javax.swing.JButton();
        clearButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        buttonsToolbar = new javax.swing.JToolBar();
        openButton = new javax.swing.JButton();
        newButton = new javax.swing.JButton();
        delButton = new javax.swing.JButton();
        jLabel2 = new javax.swing.JLabel();
        titleField = new javax.swing.JTextField();
        votesPanel = new javax.swing.JPanel();
        allOpt = new javax.swing.JRadioButton();
        presenterOpt = new javax.swing.JRadioButton();
        voteCPanel = new javax.swing.JPanel();
        voteStartButton = new javax.swing.JButton();
        voteStopButton = new javax.swing.JButton();
        voteResetButton = new javax.swing.JButton();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);

        jLabel1.setFont(new java.awt.Font("Dialog", 1, 18));
        jLabel1.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        jLabel1.setText("Survey Manager");
        getContentPane().add(jLabel1, java.awt.BorderLayout.PAGE_START);

        questionsPanel.setLayout(new java.awt.BorderLayout());

        startButton.setText("Start");
        startButton.setEnabled(false);
        startButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                startButtonActionPerformed(evt);
            }
        });
        cPanel.add(startButton);

        endButton.setText("End");
        endButton.setEnabled(false);
        endButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                endButtonActionPerformed(evt);
            }
        });
        cPanel.add(endButton);
        newButton.setText("New Question");
        newButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addQuestionButtonActionPerformed(evt);
            }
        });
        buttonsToolbar.add(newButton);
        clearButton.setText("Clear");
        clearButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                clearButtonActionPerformed(evt);
            }
        });
        cPanel.add(clearButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        cPanel.add(closeButton);

        questionsPanel.add(cPanel, java.awt.BorderLayout.PAGE_END);

        openButton.setText("Open Survey");
        openButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                openButtonActionPerformed(evt);
            }
        });
        buttonsToolbar.add(openButton);



        saveButton.setText("Save Survey");
        saveButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                saveButtonActionPerformed(evt);
            }
        });
        buttonsToolbar.add(saveButton);

        delButton.setText("Delete Question");
        delButton.setEnabled(false);
        delButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                delButtonActionPerformed(evt);
            }
        });
        buttonsToolbar.add(delButton);

        jLabel2.setText("Survey Title:");
        //buttonsToolbar.add(jLabel2);

        titleField.setPreferredSize(new java.awt.Dimension(400, 21));
        //buttonsToolbar.add(titleField);

        JPanel surveyTitlePanel = new JPanel();
        surveyTitlePanel.setLayout(new BorderLayout());
        surveyTitlePanel.add(buttonsToolbar, BorderLayout.NORTH);

        JPanel titlePanel = new JPanel();
        titlePanel.add(jLabel2);
        titlePanel.add(titleField);
        surveyTitlePanel.add(titlePanel, BorderLayout.CENTER);
        timerField.setFont(new Font("Dialog", 1, 18));
        timerField.setBorder(BorderFactory.createEtchedBorder());

        pauseButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                if (pauseButton.getText().equals("Pause")) {
                    paused = true;
                    pauseButton.setText("Resume");
                } else {
                    pauseButton.setText("Pause");
                    paused = false;
                }
            }
        });
        JPanel timerPanel = new JPanel();
        timerPanel.add(timerField);
        timerPanel.add(pauseButton);
        surveyTitlePanel.add(timerPanel, BorderLayout.SOUTH);
        questionsPanel.add(surveyTitlePanel, java.awt.BorderLayout.PAGE_START);

        tabbedPane.addTab("Questions", questionsPanel);

        votesPanel.setLayout(new java.awt.GridBagLayout());

        bg2.add(allOpt);
        allOpt.setSelected(true);
        allOpt.setText("Open Voting- Users see each others' results");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        votesPanel.add(allOpt, gridBagConstraints);

        bg2.add(presenterOpt);
        presenterOpt.setText("Closed Voting - Only Presenter sees each participants's vote");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        votesPanel.add(presenterOpt, gridBagConstraints);

        voteCPanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());

        voteStartButton.setText("Start");
        voteStartButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                voteStartButtonActionPerformed(evt);
            }
        });
        voteCPanel.add(voteStartButton);

        voteStopButton.setText("Stop");
        voteStopButton.setEnabled(false);
        voteStopButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                voteStopButtonActionPerformed(evt);
            }
        });
        voteCPanel.add(voteStopButton);

        voteResetButton.setText("Reset");
        voteResetButton.setEnabled(false);
        voteResetButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                voteResetButtonActionPerformed(evt);
            }
        });
        voteCPanel.add(voteResetButton);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        votesPanel.add(voteCPanel, gridBagConstraints);

        tabbedPane.addTab("Vote", votesPanel);

        getContentPane().add(tabbedPane, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>                        

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {
        close();

    }

    private void resetAnswers() {
        for (int i = 0; i < answers.size(); i++) {
            answers.elementAt(i).setNo(0);
            answers.elementAt(i).setYes(0);
            JProgressBar p = (JProgressBar) model.getValueAt(i, 2);
            p.setValue(0);
            model.setValueAt(p, i, 2);
        }
    }

    private void startButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (titleField.getText().trim().equals("")) {
            JOptionPane.showMessageDialog(null, "Provide the title of the survey",
                    "Title", JOptionPane.ERROR_MESSAGE);
            return;
        }

        resetAnswers();
        if (timer != null) {
            timer.cancel();
        }
        timer = new Timer();
        pauseButton.setEnabled(true);
        timer.scheduleAtFixedRate(new SurveyTimer(), 0, 1000);
        base.getTcpClient().sendPacket(new SurveyPackPacket(base.getSessionId(),
                questions, titleField.getText()));
        startButton.setEnabled(false);
        endButton.setEnabled(true);
    }

    private void endButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (timer != null) {
            timer.cancel();
        }
        endButton.setEnabled(false);
        startButton.setEnabled(true);
        pauseButton.setEnabled(false);
    }

    private void addQuestionButtonActionPerformed(java.awt.event.ActionEvent evt) {
        String qn = JOptionPane.showInputDialog("Enter Survey Question");
        if (qn != null) {
            questions.addElement(qn);
            model = new QuestionsTableModel(questions);
            table.setModel(model);
            decorateTable();
            if (questions.size() > 0) {
                startButton.setEnabled(true);
                saved = false;
            }
        }
    // TODO add your handling code here:
    }

    private void delButtonActionPerformed(java.awt.event.ActionEvent evt) {
        questions.remove(selectedRow);
        model = new QuestionsTableModel(questions);
        table.setModel(model);
        decorateTable();
        if (questions.size() > 0) {
            startButton.setEnabled(true);
        }
    }

    private void voteStartButtonActionPerformed(java.awt.event.ActionEvent evt) {
        base.getTcpClient().sendPacket(
                new VotePacket(base.getSessionId(), true, false, !allOpt.isSelected(),
                base.isSpeakerEnabled(), base.isMicEnabled()));
        voteStartButton.setEnabled(false);
        voteStopButton.setEnabled(true);
    }

    private void voteStopButtonActionPerformed(java.awt.event.ActionEvent evt) {
        base.getTcpClient().sendPacket(
                new VotePacket(base.getSessionId(), false, false, allOpt.isSelected(), base.isSpeakerEnabled(), base.isMicEnabled()));

        voteStopButton.setEnabled(false);
        voteResetButton.setEnabled(true);
    }

    private void voteResetButtonActionPerformed(java.awt.event.ActionEvent evt) {
        base.getTcpClient().sendPacket(new ClearVotePacket(base.getSessionId()));
        voteResetButton.setEnabled(false);
        voteStartButton.setEnabled(true);
    }

    private void openButtonActionPerformed(java.awt.event.ActionEvent evt) {
        openSurvey();
    }

    private void saveButtonActionPerformed(java.awt.event.ActionEvent evt) {
        saveSurvey();
    }

    private void clearButtonActionPerformed(java.awt.event.ActionEvent evt) {
        clearSurvey();
    }

    class QuestionsTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "No", //0
            "Question",
            "Result"
        };

        public QuestionsTableModel(Vector<String> questions) {
            try {
                answers.clear();
                data = new Object[questions.size()][columnNames.length];
                double total = 0;
                Result label = new Result();
                JProgressBar p = new JProgressBar();
                p.setMinimum(0);
                p.setMaximum(base.getUserManager().getUserCount() - 1);
                for (int i = 0; i < questions.size(); i++) {
                    Object ob[] = {
                        new Integer(i + 1),
                        questions.elementAt(i), p
                    };
                    data[i] = ob;
                    answers.add(new Answer(0, 0));
                    total++;
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

        public String getColumnName(int col) {
            return columnNames[col];
        }

        public Object getValueAt(int row, int col) {
            return data[row][col];

        }

        @Override
        public void setValueAt(Object value, int row, int col) {

            data[row][col] = value;
            fireTableCellUpdated(row, col);
        }

        /*
         * JTable uses this method to determine the default renderer/
         * editor for each cell.  If we didn't implement this method,
         * then the last column would contain text ("true"/"false"),
         * rather than a check box.
         */
        public Class getColumnClass(int c) {
            return getValueAt(0, c).getClass();
        }
    }

    class JComponentCellRenderer implements TableCellRenderer {

        public Component getTableCellRendererComponent(JTable table, Object value,
                boolean isSelected, boolean hasFocus, int row, int column) {
            return (JComponent) value;
        }
    }

    class Result extends JPanel {

        Color color = Color.red;// this.getBackground();

        public void setColor(Color color) {
            this.color = color;
            repaint();
        }

        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            Graphics2D g2 = (Graphics2D) g;
            g2.setColor(color);
            g2.fillRect(0, 0, getWidth(), getHeight());

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
                Component dispatchComponent = javax.swing.SwingUtilities.getDeepestComponentAt(editorComponent, 3, 3);
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
        public Component getTreeCellEditorComponent(javax.swing.JTree tree, Object value,
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

    class SurveyTimer extends TimerTask {

        int secs = 0;
        int mins = 0;
        int hrs = 0;

        public void run() {
            if (!paused) {
                if (secs == 60) {
                    mins++;
                    secs = 0;
                }
                if (mins == 60) {
                    hrs++;
                    mins = 0;
                }

                String secsVal = "" + secs;
                String minsVal = "" + mins;
                String hrsVal = "" + hrs;
                if (secs < 10) {
                    secsVal = "0" + secs;
                }
                if (mins < 10) {
                    minsVal = "0" + mins;

                }
                if (hrs < 10) {
                    hrsVal = "0" + hrs;
                }
                timerField.setText("Survey Duration: " + hrsVal + ":" + minsVal + ":" + secsVal);
                secs++;
            }
        }
    }
    // Variables declaration - do not modify                     
    private javax.swing.JButton newButton;
    private javax.swing.JRadioButton allOpt;
    private javax.swing.ButtonGroup bg;
    private javax.swing.ButtonGroup bg2;
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton clearButton;
    private javax.swing.JButton closeButton;
    private javax.swing.JButton delButton;
    private javax.swing.JButton endButton;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JToolBar buttonsToolbar;
    private javax.swing.JButton openButton;
    private javax.swing.JRadioButton presenterOpt;
    private javax.swing.JPanel questionsPanel;
    private javax.swing.JButton saveButton;
    private javax.swing.JButton startButton;
    private javax.swing.JTabbedPane tabbedPane;
    private javax.swing.JTextField titleField;
    private javax.swing.JPanel voteCPanel;
    private javax.swing.JButton voteResetButton;
    private javax.swing.JButton voteStartButton;
    private javax.swing.JButton voteStopButton;
    private javax.swing.JPanel votesPanel;
    // End of variables declaration                   
}
