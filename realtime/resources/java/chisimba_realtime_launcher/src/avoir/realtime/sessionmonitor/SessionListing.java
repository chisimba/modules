/*
 * SessionListing.java
 *
 * Created on 25 May 2008, 10:41
 */
package avoir.realtime.sessionmonitor;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.FileNotFoundException;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JMenuItem;
import javax.swing.JPopupMenu;
import javax.swing.JProgressBar;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;

/**
 *
 * @author  developer
 */
public class SessionListing extends javax.swing.JApplet {

    private SessionListingTableModel model = new SessionListingTableModel();
    private JTable table = new JTable(model);
    private TCPConnector tcpConnector;
    private SSLHttpTunnellingClient sslClient;
    private Vector<SessionPresenter> presenters;
    private String sessionId;
    private int selectedRow = -1;
    private Vector<String> sessionIds = new Vector<String>();
    private JPopupMenu popup = new JPopupMenu();
    private String siteRoot;
    JMenuItem joinSessionMenuItem = new JMenuItem("Join Session");

    /** Initializes the applet SessionListing */
    @Override
    public void init() {
        try {
            java.awt.EventQueue.invokeAndWait(new Runnable() {

                public void run() {
                    //System.setProperty("javax.net.ssl.trustStore", "keystore");
                    //System.setProperty("javax.net.ssl.trustStorePassword", "exitwounds");
                    initComponents();
                    initCustomComponents();
                    initConnection();
                }
            });
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void initCustomComponents() {
        table.setShowHorizontalLines(false);
        table.setFont(new Font("Dialog", 0, 11));
        popup.add(joinSessionMenuItem);
        viewRecordedButton.setIcon(createImageIcon(this,"/icons/view.jpeg"));
        scheduleButton.setIcon(createImageIcon(this,"/icons/schedule.jpeg"));
        add(statusBar, BorderLayout.SOUTH);
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
                } else {
                    selectedRow = lsm.getMinSelectionIndex();
                }
            }
        });
        table.addMouseListener(new MouseAdapter() {

            public void mouseClicked(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    popup.show(table, e.getX(), e.getY());
                }
                if (e.getClickCount() == 2) {
                    displayLiveSessionApplet(sessionIds.elementAt(selectedRow));
                }

            }
        });
        joinSessionMenuItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                displayLiveSessionApplet(sessionIds.elementAt(selectedRow));
            }
        });
        sessionId = this.getParameter("sessionId");
        siteRoot = this.getParameter("siteRoot");
    }

    private void initConnection() {
        Thread t = new Thread() {

            public void run() {
                connect();
            }
        };
        t.start();
    }

    public void showTableListing() {

        setText("Connected.", false);
        mainPanel.removeAll();
        mainPanel.add(cPanel,BorderLayout.NORTH);
        mainPanel.add(new JScrollPane(table), BorderLayout.CENTER);
        this.resize(600, 405);
    }

    private void connect() {
        RealtimeOptions.init();
        tcpConnector = new TCPConnector(this);
        //for non proxy connections...just connect direct
        if (RealtimeOptions.useDirectConnection()) {


            pb.setIndeterminate(true);

            if (tcpConnector.connect()) {
                
            } else {
                setText("Error connecting to server.", true);
            }
        }
        //here we use manual proxy..and do the tunnelling through the proxy
        if (RealtimeOptions.useManualProxy()) {
            sslClient = new SSLHttpTunnellingClient(RealtimeOptions.getProxyHost(),
                    RealtimeOptions.getProxyPort(), this);
        }

    }

    @Override
    public void destroy() {
        tcpConnector.requestUserRemoval(sessionId);
    }

    public String getSessionId() {
        return sessionId;
    }

    public TCPConnector getTcpConnector() {
        return tcpConnector;
    }

    public JProgressBar getPb() {
        return pb;
    }

    public void refreshTable(Vector<SessionParticipant> participants,
            Vector<SessionPresenter> presenters) {
        this.presenters = presenters;
        String sessionId = "";
        Vector<SessionParticipant> users = new Vector<SessionParticipant>();
        Vector<Vector> sessions = new Vector<Vector>();
        for (int i = 0; i < participants.size(); i++) {
            SessionParticipant p = participants.elementAt(i);
            if (!p.getSessionId().equals(sessionId)) {
                sessionId = p.getSessionId();
                if (i == 0) {
                    users.add(p);

                } else {
                    sessions.add(users);
                    users.clear();
                    users.add(p);
                }

                if (i == participants.size() - 1) {
                    sessions.add(users);
                }
            } else {
                users.add(p);
                if (i == participants.size() - 1) {
                    sessions.add(users);
                }
            }
        }
        model = new SessionListingTableModel(sessions);
        table.setModel(model);

    }

    private void displayLiveSessionApplet(String sessionId) {
        try {
            String url = siteRoot + "/index.php?module=webpresent&action=showaudienceapplet&id=" + sessionId;
            URL liveURL = new URL(url);
            getAppletContext().showDocument(liveURL);
        } catch (MalformedURLException e) {
            e.printStackTrace();
        }
    }

    public void setText(String txt, boolean error) {
        if (error) {
            infoField.setForeground(Color.RED);
            statusBar.setForeground(Color.RED);
            statusBar.setText(txt);
            showOptionsReconnectPanel();
        } else {
            infoField.setForeground(Color.BLACK);
            statusBar.setForeground(Color.BLACK);
        }
        infoField.setText(txt);
        statusBar.setText(txt);
    }

    private void showOptionsReconnectPanel() {
        retryButton.setEnabled(true);
        optionsButton.setEnabled(true);
        pb.setIndeterminate(false);
        java.awt.GridBagConstraints gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        connectingPanel.add(optionsPanel, gridBagConstraints);
    }

    class SessionListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Session", //0
            "Presenter",
            "Start Time",
            "Details"
        };

        public SessionListingTableModel() {
            try {
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        private String[] getSessionDetails(String id) {
            String[] d = new String[2];
            d[0] = "None";
            d[1] = "N/A";

            for (int i = 0; i < presenters.size(); i++) {
                SessionPresenter presenter = presenters.elementAt(i);
                if (presenter.getSessionId().equals(id)) {
                    d[0] = presenter.getFullName();
                    d[1] = presenter.getTime();
                }
            }
            return d;
        }

        public SessionListingTableModel(Vector<Vector> sessions) {
            try {
                data = new Object[sessions.size()][columnNames.length];
                sessionIds.clear();
                for (int i = 0; i < sessions.size(); i++) {
                    Vector<SessionParticipant> session = sessions.elementAt(i);
                    String[] d = getSessionDetails(session.elementAt(i).getSessionId());
                    Object[] row = {session.elementAt(0).getSessionName(), d[0], d[1], "N/A"};
                    sessionIds.addElement(session.elementAt(i).getSessionId());
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

    private void showOptionsFrame() {

        avoir.realtime.sessionmonitor.RealtimeOptionsFrame optionsFrame = new avoir.realtime.sessionmonitor.RealtimeOptionsFrame(this);
        optionsFrame.setSize(400, 300);
        optionsFrame.setLocationRelativeTo(null);
        optionsFrame.setVisible(true);
    }

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {
        java.awt.GridBagConstraints gridBagConstraints;

        cPanel = new javax.swing.JPanel();
        scheduleButton = new javax.swing.JButton();
        viewRecordedButton = new javax.swing.JButton();
        optionsPanel = new javax.swing.JPanel();
        retryButton = new javax.swing.JButton();
        optionsButton = new javax.swing.JButton();
        statusBar = new javax.swing.JLabel();
        titleLabel = new javax.swing.JLabel();
        mainPanel = new javax.swing.JPanel();
        connectingPanel = new javax.swing.JPanel();
        infoField = new javax.swing.JLabel();
        pb = new javax.swing.JProgressBar();

        scheduleButton.setText("Schedule");
        scheduleButton.setToolTipText("Schedule a presentation");
        scheduleButton.setBorderPainted(false);
        scheduleButton.setContentAreaFilled(false);
        scheduleButton.setEnabled(false);
        scheduleButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                scheduleButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                scheduleButtonMouseExited(evt);
            }
        });
        scheduleButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                scheduleButtonActionPerformed(evt);
            }
        });
        cPanel.add(scheduleButton);

        viewRecordedButton.setText("View Recorded");
        viewRecordedButton.setToolTipText("View recorded sessions");
        viewRecordedButton.setBorderPainted(false);
        viewRecordedButton.setContentAreaFilled(false);
        viewRecordedButton.setEnabled(false);
        viewRecordedButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                viewRecordedButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                viewRecordedButtonMouseExited(evt);
            }
        });
        cPanel.add(viewRecordedButton);

        retryButton.setText("Retry");
        retryButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                retryButtonActionPerformed(evt);
            }
        });
        optionsPanel.add(retryButton);

        optionsButton.setText("Options");
        optionsButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsButtonActionPerformed(evt);
            }
        });
        optionsPanel.add(optionsButton);

        statusBar.setText("Ready");
        statusBar.setBorder(javax.swing.BorderFactory.createBevelBorder(javax.swing.border.BevelBorder.LOWERED));
        optionsPanel.add(statusBar);

        titleLabel.setFont(new java.awt.Font("Dialog", 1, 18));
        titleLabel.setForeground(new java.awt.Color(255, 153, 102));
        titleLabel.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        titleLabel.setText(" Live Session Listing -Beta");
        getContentPane().add(titleLabel, java.awt.BorderLayout.PAGE_START);

        mainPanel.setLayout(new java.awt.BorderLayout());

        connectingPanel.setLayout(new java.awt.GridBagLayout());

        infoField.setText("Connecting to server...");
        connectingPanel.add(infoField, new java.awt.GridBagConstraints());

        pb.setIndeterminate(true);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        connectingPanel.add(pb, gridBagConstraints);

        mainPanel.add(connectingPanel, java.awt.BorderLayout.CENTER);

        getContentPane().add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

private void retryButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_retryButtonActionPerformed
    Thread t = new Thread() {

        public void run() {
            retryButton.setEnabled(false);
            optionsButton.setEnabled(false);
            pb.setIndeterminate(true);
            connect();
        }
    };
    t.start();
}//GEN-LAST:event_retryButtonActionPerformed

private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_optionsButtonActionPerformed
    showOptionsFrame();
}//GEN-LAST:event_optionsButtonActionPerformed

private void scheduleButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_scheduleButtonActionPerformed
// TODO add your handling code here:
}//GEN-LAST:event_scheduleButtonActionPerformed

private void scheduleButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_scheduleButtonMouseEntered
scheduleButton.setContentAreaFilled(true);
}//GEN-LAST:event_scheduleButtonMouseEntered

private void scheduleButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_scheduleButtonMouseExited
scheduleButton.setContentAreaFilled(false);
}//GEN-LAST:event_scheduleButtonMouseExited

private void viewRecordedButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_viewRecordedButtonMouseEntered
viewRecordedButton.setContentAreaFilled(true);
}//GEN-LAST:event_viewRecordedButtonMouseEntered

private void viewRecordedButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_viewRecordedButtonMouseExited
viewRecordedButton.setEnabled(false);
}//GEN-LAST:event_viewRecordedButtonMouseExited
  /**
     * Creates an ImageIcon, retrieving the Image from the system classpath.
     *
     * @param path String location of the image file
     * @return Returns and ImageIcon object with the supplied image
     * @throws FileNotFoundException File can't be found
     */
    public static ImageIcon createImageIcon(String path) {
        try {
            URL imageURL = ClassLoader.getSystemResource(path);
            if (imageURL != null) {
                return new ImageIcon(imageURL);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    /**
     * Creates an ImageIcon, retrieving the image from the classes' classpath or 
     * the system classpath (searched in that order).
     *
     * @param classToLoadFrom Class to use to search classpath for image.
     * @param path String location of the image file
     * @return Returns and ImageIcon object with the supplied image
     * @throws FileNotFoundException File can't be found
     */
    public static ImageIcon createImageIcon(Object classToLoadFrom, String path) {
        try {
            URL imageURL = classToLoadFrom.getClass().getResource(path);
            if (imageURL == null) {
                imageURL = classToLoadFrom.getClass().getClassLoader().getResource(
                        path);
            }
            if (imageURL == null) {
                return createImageIcon(path);
            } else {
                return new ImageIcon(imageURL);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel cPanel;
    private javax.swing.JPanel connectingPanel;
    private javax.swing.JLabel infoField;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JButton optionsButton;
    private javax.swing.JPanel optionsPanel;
    private javax.swing.JProgressBar pb;
    private javax.swing.JButton retryButton;
    private javax.swing.JButton scheduleButton;
    private javax.swing.JLabel statusBar;
    private javax.swing.JLabel titleLabel;
    private javax.swing.JButton viewRecordedButton;
    // End of variables declaration//GEN-END:variables
}
