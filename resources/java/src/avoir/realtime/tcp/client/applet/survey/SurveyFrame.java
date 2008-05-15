/*
 * SurveyFrame.java
 *
 * Created on 21 April 2008, 05:43
 */
package avoir.realtime.tcp.client.applet.survey;

import avoir.realtime.tcp.client.applet.*;
import avoir.realtime.tcp.common.packet.SurveyAnswerPacket;
import java.util.Vector;
import java.awt.Component;
import java.awt.event.*;
import javax.swing.table.*;
import javax.swing.event.*;
import java.util.EventObject;
import javax.swing.tree.*;
import java.io.Serializable;
import javax.swing.*;
import java.awt.Color;

/**
 *
 * @author  developer
 */
public class SurveyFrame extends javax.swing.JFrame {

    private Vector<String> questions = new Vector<String>();
    
    private QuestionsTableModel model = new QuestionsTableModel(questions);
    private JTable table = new JTable(model) {

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
    private TCPTunnellingApplet client;

    public SurveyFrame(TCPTunnellingApplet client) {
        initComponents();
        this.client = client;
        table.setShowGrid(false);
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        mPanel.add(new JScrollPane(table));

    }

    public void setTitle(String title) {
        super.setTitle("Survey: " + title);
        titleField.setText(title);

    }

    public void setQuestions(Vector<String> questions) {
        this.questions = questions;
       
        refresh();
    }

    public void refresh() {
        model = new QuestionsTableModel(this.questions);
      
        table.setModel(model);
        decorateTable();
    }

    class QuestionsTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "No", //0
            "Question",
            "Yes", "No"
        };

        public QuestionsTableModel(Vector<String> questions) {
            try {

                data = new Object[questions.size()][columnNames.length];
                double total = 0;

                for (int i = 0; i < questions.size(); i++) {
                    JRadioButton yesRB = new JRadioButton();
                    yesRB.setBackground(Color.WHITE);
                    JRadioButton noRB = new JRadioButton();
                    noRB.setBackground(Color.WHITE);
                    ButtonGroup bg = new ButtonGroup();
                    bg.add(noRB);
                    bg.add(yesRB);
                    Object ob[] = {
                        new Integer(i + 1),
                        questions.elementAt(i),
                        yesRB, noRB
                    };
                    data[i] = ob;
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

        public boolean isCellEditable(int row, int col) {
            if (col > 1) {
                return true;
            } else {
                return false;
            }

        }
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

    private void processSubmit() {
        for (int i = 0; i < model.getRowCount(); i++) {
            JRadioButton yes = (JRadioButton) model.getValueAt(i, 2);
            JRadioButton no = (JRadioButton) model.getValueAt(i, 3);

            boolean ans = false;
            /**
             * Only process answered questions
             */
            if (yes.isSelected()) {
                ans = true;
                client.getTCPClient().sendPacket(
                        new SurveyAnswerPacket(client.getSessionId(), i, ans));

            }
            if (no.isSelected()) {

                ans = false;
                client.getTCPClient().sendPacket(
                        new SurveyAnswerPacket(client.getSessionId(), i, ans));

            }
        }
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    // <editor-fold defaultstate="collapsed" desc="Generated Code">                          
    private void initComponents() {

        titleField = new javax.swing.JLabel();
        cPanel = new javax.swing.JPanel();
        submitButton = new javax.swing.JButton();
        mPanel = new javax.swing.JPanel();

        titleField.setFont(new java.awt.Font("Dialog", 1, 18));
        titleField.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        titleField.setText("Title");
        getContentPane().add(titleField, java.awt.BorderLayout.PAGE_START);

        submitButton.setText("Submit");
        submitButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                submitButtonActionPerformed(evt);
            }
        });
        cPanel.add(submitButton);

        getContentPane().add(cPanel, java.awt.BorderLayout.PAGE_END);

        mPanel.setLayout(new java.awt.BorderLayout());
        getContentPane().add(mPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>                        

    private void submitButtonActionPerformed(java.awt.event.ActionEvent evt) {
        int n = JOptionPane.showConfirmDialog(null,
                "Are you sure you want to submit the result?\n",
                // "Once you submit,you wont be able to answer again",
                "Submit", JOptionPane.YES_NO_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            processSubmit();
        }
        dispose();
    }

    // Variables declaration - do not modify                     
    private javax.swing.JPanel cPanel;
    private javax.swing.JPanel mPanel;
    private javax.swing.JButton submitButton;
    private javax.swing.JLabel titleField;
    // End of variables declaration       

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

}
