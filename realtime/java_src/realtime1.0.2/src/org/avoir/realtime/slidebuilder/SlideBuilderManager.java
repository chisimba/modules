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
 * SlideBuilderManager.java
 *
 * Created on 2009/03/31, 12:59:11
 */
package org.avoir.realtime.slidebuilder;

import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import chrriis.dj.nativeswing.swtimpl.components.JWebBrowserWindow;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserListener;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserNavigationEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserWindowOpeningEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserWindowWillOpenEvent;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.MouseEvent;
import java.util.ArrayList;
import java.util.Map;
import javax.swing.ImageIcon;
import javax.swing.JComponent;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.ListCellRenderer;
import javax.swing.ListSelectionModel;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.event.MouseInputListener;
import javax.swing.plaf.basic.BasicTableUI;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.TableColumn;
import javax.swing.table.TableModel;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.RealtimeFileChooser;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.RPacketListener;
import org.avoir.realtime.net.SlideShowListener;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.net.packets.RealtimeSlideShowPacket;
import org.avoir.realtime.net.providers.DownloadedImage;
import org.avoir.realtime.questions.AnsweringFrame;
import org.jivesoftware.smack.util.Base64;

/**
 *
 * @author developer
 */
public class SlideBuilderManager extends javax.swing.JFrame implements SlideShowListener {

    private Color textColor = Color.BLACK;
    private int textSize = 18;
    private Surface surface;
    private String customSlideText;
    private ImageIcon fileIcon = ImageUtil.createImageIcon(this, "/images/file.png");
    private ImageIcon tickIcon = ImageUtil.createImageIcon(this, "/images/tick.png");
    private ImageIcon urlIcon = ImageUtil.createImageIcon(this, "/images/link.gif");
    private ImageIcon qnIcon = ImageUtil.createImageIcon(this, "/images/question-small.jpg");
    private ImageIcon imgIcon = ImageUtil.createImageIcon(this, "/images/image_small.png");
    private ImageIcon blankIcon = ImageUtil.createImageIcon(this, "/images/blank.png");
    private RealtimeFileChooser realtimeFileChooser = new RealtimeFileChooser("questions");
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private RealtimeQuestionPacket question;
    private String imagePath;
    private ImageIcon image;
    private String slideShowName;
    private ArrayList<Slide> slides = new ArrayList<Slide>();
    private SlideListingTableModel model = new SlideListingTableModel();
    private JTable table = new JTable();
    private int selectedRow = 0;
    private JScrollPane sp;
    private String access = "private";
    private String mode = "add";
    private boolean firstImage = true;
    private String slideShowPath;

    /** Creates new form SlideBuilderManager */
    public SlideBuilderManager() {
        initComponents();
        RPacketListener.addSlideShowListener(this);

        sp = new JScrollPane(table);
        sp.getViewport().setBackground(Color.WHITE);
        surface = new Surface(this);
        table.setModel(model);
        table.setGridColor(new Color(238, 238, 238));
        table.setShowHorizontalLines(false);
        table.setUI(new DragDropRowTableUI());
        listPanel.add(sp, BorderLayout.CENTER);
        imagePanel.add(new JScrollPane(surface), BorderLayout.CENTER);
        ListSelectionModel listSelectionModel = table.getSelectionModel();
        listSelectionModel.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        listSelectionModel.addListSelectionListener(
                new SharedListSelectionHandler());
        textField.setFont(new Font("SansSerif", 0, textSize));
        textField.setForeground(textColor);
        textField.setWrapStyleWord(true);
        textField.setLineWrap(true);
        textField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent e) {
                customSlideText = textField.getText();
                surface.setCustomSlideText(customSlideText);
                surface.repaint();
            }

            public void removeUpdate(DocumentEvent e) {
                customSlideText = textField.getText();
                surface.setCustomSlideText(customSlideText);
                surface.repaint();
            }

            public void changedUpdate(DocumentEvent e) {
            }
        });
        titleField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent e) {
                addButton.setEnabled(true);

            }

            public void removeUpdate(DocumentEvent e) {
                addButton.setEnabled(true);

            }

            public void changedUpdate(DocumentEvent e) {
            }
        });
        setControlsEnabled(true);

        //   addButton.setText("Save Slide");
        titleField.requestFocus();
        decorateTable();
        splitPane2.setDividerLocation((getWidth() - listPanel.getWidth()) / 2);
    }

    public Color getTextColor() {
        return textColor;
    }

    public int getTextSize() {
        return textSize;
    }

    public String getSlideShowPath() {
        return slideShowPath;
    }

    public void setSlideShowPath(String slideShowPath) {
        this.slideShowPath = slideShowPath;
    }

    private void decorateTable() {

        TableColumn column = null;
        if (model != null) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                column = table.getColumnModel().getColumn(i);
                if (i == 3) {
                    column.setPreferredWidth(280);
                } else if (i == 3) {
                    column.setPreferredWidth(100);
                } else {
                    column.setPreferredWidth(20);
                }
            }
        }

    }

    private void setControlsEnabled(boolean state) {
        titleField.setEditable(state);
        textField.setEditable(state);
        textUploadButton.setEnabled(state);
        questionUploadButton.setEnabled(state);
        clearQuestionButton.setEnabled(state);
        imageButton.setEnabled(state);
        clearButton.setEnabled(state);
        prevQnButton.setEnabled(true);
        urlField.setEnabled(state);
        launchBrowserButton.setEnabled(state);

    }

    class TitleListRenderer extends JLabel
            implements ListCellRenderer {

        public TitleListRenderer() {
            setOpaque(true);
            setHorizontalAlignment(LEADING);
            setVerticalAlignment(CENTER);
        }

        /*
         * This method finds the image and text corresponding
         * to the selected value and returns the label, set up
         * to display the text and image.
         */
        public Component getListCellRendererComponent(
                JList list,
                Object value,
                int index,
                boolean isSelected,
                boolean cellHasFocus) {
            if (isSelected) {
                setBackground(list.getSelectionBackground());
                setForeground(list.getSelectionForeground());
            } else {
                setBackground(list.getBackground());
                setForeground(list.getForeground());
            }

            //Set the icon and text.  If icon was null, say so.

            Slide slide = (Slide) value;
            ImageIcon icon = fileIcon;

            setIcon(icon);
            if (icon != null) {
                setText(slide.getTitle());
                setFont(list.getFont());
            } else {
                setText(slide.getTitle());
            }

            return this;
        }
    }

    class SharedListSelectionHandler implements ListSelectionListener {

        public void valueChanged(ListSelectionEvent e) {
            ListSelectionModel lsm = (ListSelectionModel) e.getSource();

            if (lsm.isSelectionEmpty()) {
                editSlideButton.setEnabled(false);
                deleteSlideButton.setEnabled(false);
                addButton.setEnabled(true);
            } else {
                editSlideButton.setEnabled(true);
                deleteSlideButton.setEnabled(true);
                setControlsEnabled(false);
                surface.setSlideImage(null);
                image = null;
                // Find out which indexes are selected.
                int minIndex = lsm.getMinSelectionIndex();
                question = null;
                selectedRow = minIndex;
                Slide slide = slides.get(selectedRow);
                question = slide.getQuestion();
                questionField.setText("");

                if (question != null) {
                    questionField.setText(question.getQuestion());
                }
                textColor = slide.getTextColor();
                textSize = slide.getTextSize();
                textField.setForeground(textColor);
                textField.setFont(new Font("Dialog", 0, textSize));
                titleField.setText(slide.getTitle());
                textField.setText(slide.getText() == null ? "null" : (slide.getText().equals("null") ? "" : slide.getText()));
                urlField.setText(slide.getUrl() == null ? "" : (slide.getUrl().equals("null") ? "" : slide.getUrl()));
                addButton.setText("Add New Slide");
                imagePath = slide.getImagePath();
                image = slide.getImage();
                surface.setSlideImage(image);
                
                //saveButton.setEnabled(slides.size() > 0);

            }
        }
    }

    public String getAccess() {
        return access;
    }

    public void setAccess(String access) {
        this.access = access;
    }

    private void setSaved(boolean saved) {
        GUIAccessManager.saveStatus.put("SlideBuilder", false);
    }

    public void processSlideShowImage(DownloadedImage downloadedImage) {
        if (firstImage) {
            image = downloadedImage.getImage();

            setSaved(false);
            imagePath = downloadedImage.getImagePath();
            surface.setSlideImage(image);
            surface.repaint();
            firstImage = false;
        // saveSlide();
        } else {

            Slide slide = new Slide("Untitled" + slides.size(),
                    "null",
                    textColor,
                    textSize,
                    null,
                    downloadedImage.getImagePath(),
                    null, downloadedImage.getImage(), null, slides.size() - 1);
            slides.add(slide);
            model = new SlideListingTableModel();
            table.setModel(model);
            decorateTable();

        }
    }

    public void openSlideShow(RealtimeSlideShowPacket packet) {
        System.out.println(" SlideBuilder openSlideShow: not supposed to come here- this actually comes" +
                " through the navigator");
    }

    public void processSlideShowQuestion(RealtimeQuestionPacket packet) {
        question = packet;
        questionField.setText(question.getQuestion());
        setSaved(false);
    }

    private void showQuestionFileChooser() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_SLIDE_QUESTION_FILE_VIEW);
        StringBuilder sb = new StringBuilder();
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<file-type>").append("questions").append("</file-type>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
        if (realtimeFileChooser.showDialog() == RealtimeFileChooser.APPROVE_OPTION) {
            String access = "private";
            RealtimeFile file = realtimeFileChooser.getSelectedFile();
            if (file.isPublicAccessible()) {
                access = "public";
            }
            p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.OPEN_SLIDE_SHOW_QUESTION);
            StringBuilder buf = new StringBuilder();
            buf.append("<question-path>").append(file.getFilePath()).append("</question-path>");
            buf.append("<filename>").append(file.getFileName()).append("</filename>");
            buf.append("<access>").append(access).append("</access>");
            buf.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
            p.setContent(buf.toString());
            ConnectionManager.sendPacket(p);
        }

    }

    private void previewQuestion() {
        if (question == null) {
            JOptionPane.showMessageDialog(null, "No question to preview");
            return;
        }
        RealtimeQuestionPacket p = new RealtimeQuestionPacket();
        p.setQuestion(question.getQuestion());
        p.setQuestionType(question.getQuestionType());
        p.setFilename(question.getFilename());
        p.setAnswerOptions(question.getAnswerOptions());
        image = new ImageIcon(Base64.decode(question.getImageData()));
        p.setImage(image);
        p.setImagePath(question.getImagePath());

        AnsweringFrame fr = new AnsweringFrame(p, false);
        fr.setSize(getSize());
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);

    }

    private void insertImage() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_FILE_VIEW);
        StringBuilder sb = new StringBuilder();
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<file-type>").append("images").append("</file-type>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
        realtimeFileChooser.getSelectedFiles().clear();
        realtimeFileChooser.getFileList().setSelectionMode(ListSelectionModel.MULTIPLE_INTERVAL_SELECTION);
        if (realtimeFileChooser.showDialog() == RealtimeFileChooser.APPROVE_OPTION) {
            firstImage = true;
            realtimeFileChooser.getFileList().setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
            ArrayList<RealtimeFile> selectedFiles = realtimeFileChooser.getSelectedFiles();
            for (RealtimeFile file : selectedFiles) {
                requestImageDownload(file.getFilePath());
            }
            RealtimeFile selectedFile = realtimeFileChooser.getSelectedFile();
            if (selectedFiles.size() == 0 && selectedFile != null) {
                requestImageDownload(selectedFile.getFilePath());
            }
        }

    }

    private void requestImageDownload(String path) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.DOWNLOAD_SLIDE_SHOW_IMAGE);
        StringBuilder buf = new StringBuilder();
        buf.append("<image-path>").append(path).append("</image-path>");
        p.setContent(buf.toString());
        ConnectionManager.sendPacket(p);
    }

    public void setSlides(ArrayList<Slide> slides) {
        this.slides = slides;
        mode = "edit";
        image = null;
        model = new SlideListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    public void setSlideShowName(String slideShowName) {
        this.slideShowName = slideShowName;
        setTitle(slideShowName);
    }

    private void save() {
        if (!mode.equals("edit")) {
            slideShowName = JOptionPane.showInputDialog("Name:", GeneralUtil.removeExt(slideShowName));
        }
        if (slideShowName != null) {
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.SAVE_SLIDE_SHOW);

            RealtimeSlideShowPacket content = new RealtimeSlideShowPacket();
            content.setFilename(slideShowName);
            content.setFilePath(slideShowPath);
            content.setUsername(ConnectionManager.getUsername());
            content.setAccess(access);
            if (mode.equals("edit")) {
                content.setModified(true);
            }
            content.setSlides(slides);
            p.setContent(content.getChildElementXML());
            ConnectionManager.sendPacket(p);
            GUIAccessManager.saveStatus.put("SlideBuilder", true);
            dispose();

        }
    }

    private void updateSlide() {
        if (editSlideButton.getText().equals("Edit Slide")) {
            setControlsEnabled(true);
            editSlideButton.setText("Update Slide");
            addButton.setText("Add Slide");
            addButton.setEnabled(false);
            return;
        }
        if (editSlideButton.getText().equals("Update Slide")) {

            String qnPath = question == null ? null : question.getQuestionPath();
            boolean isQuestionSlide = question == null ? false : true;
            boolean isImageSlide = image == null ? false : true;
            boolean isURLSlide = urlField.getText().trim().equals("") ? false : true;
        
            Slide slide = new Slide(titleField.getText().trim().equals("") ? "Untitled" + slides.size() : titleField.getText(),
                    customSlideText,
                    textColor,
                    textSize,
                    qnPath,
                    imagePath,
                    urlField.getText(), image, question,
                    selectedRow);
            Slide oldSlide = slides.get(selectedRow);
            slide.setContentModified(oldSlide.contentModified(oldSlide, slide));
            slide.setTitleModified(oldSlide.titleModified(oldSlide, slide));
            slide.setOldTitle(oldSlide.getTitle());
            slide.setQuestionSlide(isQuestionSlide);
            slide.setImageSlide(isImageSlide);
            slide.setUrlSlide(isURLSlide);
            slides.set(selectedRow, slide);

            editSlideButton.setEnabled(false);
            addButton.setEnabled(true);
            editSlideButton.setText("Edit Slide");
            saveButton.setEnabled(true);
            clear();
        }
    }

    private void saveSlide() {
        if (addButton.getText().equals("Add New Slide")) {
            addButton.setText("Add Slide");
            setControlsEnabled(true);
            clear();
            return;
        }

        String qnPath = question == null ? null : question.getQuestionPath();
        boolean isQuestionSlide = question == null ? false : true;
        boolean isImageSlide = image == null ? false : true;
        boolean isURLSlide = urlField.getText().trim().equals("") ? false : true;
        Slide slide = new Slide(titleField.getText().trim().equals("") ? "Untitled" + slides.size() : titleField.getText(),
                customSlideText,
                textColor,
                textSize,
                qnPath,
                imagePath,
                urlField.getText(), image, question, slides.size() - 1);
        slide.setQuestionSlide(isQuestionSlide);
        slide.setImageSlide(isImageSlide);
        slide.setUrlSlide(isURLSlide);
        if (addButton.getText().equals("Save Slide")) {
            if (slides.size() == 0) {
                addButton.setText("Add Slide");

                slides.add(slide);

            } else {
                Slide oldSlide = slides.get(selectedRow);
                slide.setContentModified(oldSlide.contentModified(oldSlide, slide));
                slide.setTitleModified(oldSlide.titleModified(oldSlide, slide));
                slide.setOldTitle(oldSlide.getTitle());
                slides.set(selectedRow, slide);
                addButton.setText("Add Slide");
            }
        } else {
            slide.setContentModified(true);
            addButton.setText("Add Slide");
            slides.add(slide);
        }
        titleField.setText("");
        textField.setText("");
        urlField.setText("");
        question = null;
        questionField.setText("");
        image = null;
        imagePath = null;
        saveButton.setEnabled(true);
        surface.setSlideImage(image);
        titleField.requestFocus();
        model = new SlideListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    class SlideListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Q",
            "U", //0
            "I",
            "Title",};
        private Object[][] data = new Object[0][columnNames.length];

        public SlideListingTableModel() {
            data = new Object[slides.size()][columnNames.length];
            for (int i = 0; i < slides.size(); i++) {
                Slide slide = slides.get(i);
                Object[] row = {
                    slide.getQuestion() == null ? blankIcon : qnIcon,
                    slide.getUrl() == null ? blankIcon : urlIcon,
                    slide.getImage() == null ? blankIcon : imgIcon,
                    slide.getTitle(),};
                data[i] = row;
            }
        }

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
        public boolean isCellEditable(int rowIndex, int columnIndex) {
            if (columnIndex == 4 || columnIndex == 5) {
                return true;
            } else {
                return false;
            }
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
        @Override
        public Class getColumnClass(int c) {

            Object obj = getValueAt(0, c);
            if (obj != null) {
                return getValueAt(0, c).getClass();
            } else {
                return new Object().getClass();
            }
        }
    }

    public class DragDropRowTableUI extends BasicTableUI {

        private boolean draggingRow = false;
        private int startDragPoint;
        private int dyOffset;

        @Override
        protected MouseInputListener createMouseInputListener() {
            return new DragDropRowMouseInputHandler();
        }

        @Override
        public void paint(Graphics g, JComponent c) {
            super.paint(g, c);

            if (draggingRow) {
                g.setColor(table.getParent().getBackground());
                Rectangle cellRect = table.getCellRect(table.getSelectedRow(), 0, false);
                g.copyArea(cellRect.x, cellRect.y, table.getWidth(), table.getRowHeight(), cellRect.x, dyOffset);

                if (dyOffset < 0) {
                    g.fillRect(cellRect.x, cellRect.y + (table.getRowHeight() + dyOffset), table.getWidth(), (dyOffset * -1));
                } else {
                    g.fillRect(cellRect.x, cellRect.y, table.getWidth(), dyOffset);
                }
            }
        }

        class DragDropRowMouseInputHandler extends MouseInputHandler {

            @Override
            public void mousePressed(MouseEvent e) {
                super.mousePressed(e);
                startDragPoint = (int) e.getPoint().getY();
            }

            @Override
            public void mouseDragged(MouseEvent e) {
                int fromRow = table.getSelectedRow();

                if (fromRow >= 0) {
                    draggingRow = true;

                    int rowHeight = table.getRowHeight();
                    int middleOfSelectedRow = (rowHeight * fromRow) + (rowHeight / 2);

                    int toRow = -1;
                    int yMousePoint = (int) e.getPoint().getY();

                    if (yMousePoint < (middleOfSelectedRow - rowHeight)) {
                        // Move row up
                        toRow = fromRow - 1;
                    } else if (yMousePoint > (middleOfSelectedRow + rowHeight)) {
                        // Move row down
                        toRow = fromRow + 1;
                    }

                    if (toRow >= 0 && toRow < table.getRowCount()) {
                        TableModel model = table.getModel();

                        for (int i = 0; i < model.getColumnCount(); i++) {
                            Object fromValue = model.getValueAt(fromRow, i);
                            Object toValue = model.getValueAt(toRow, i);

                            model.setValueAt(toValue, fromRow, i);
                            model.setValueAt(fromValue, toRow, i);
                        }
                        Slide fromSlide = slides.get(fromRow);
                        Slide toSlide = slides.get(toRow);
                        slides.set(fromRow, toSlide);
                        slides.set(toRow, fromSlide);
                        table.setRowSelectionInterval(toRow, toRow);
                        startDragPoint = yMousePoint;
                        setSaved(false);
                        saveButton.setEnabled(true);
                    }

                    dyOffset = (startDragPoint - yMousePoint) * -1;
                    table.repaint();
                }
            }

            @Override
            public void mouseReleased(MouseEvent e) {
                super.mouseReleased(e);

                draggingRow = false;
                table.repaint();
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

        toprightPanel = new javax.swing.JPanel();
        cPanel = new javax.swing.JPanel();
        infoField = new javax.swing.JLabel();
        southPanel = new javax.swing.JPanel();
        slideCPanel = new javax.swing.JPanel();
        addButton = new javax.swing.JButton();
        editSlideButton = new javax.swing.JButton();
        deleteSlideButton = new javax.swing.JButton();
        slideShowCPanel = new javax.swing.JPanel();
        saveButton = new javax.swing.JButton();
        splitPane = new javax.swing.JSplitPane();
        listPanel = new javax.swing.JPanel();
        slideNameField = new javax.swing.JLabel();
        splitPane2 = new javax.swing.JSplitPane();
        slideDetailsPanel = new javax.swing.JPanel();
        slideTitleLabel = new javax.swing.JLabel();
        titleField = new javax.swing.JTextField();
        slideTextLabel = new javax.swing.JLabel();
        textFieldSP = new javax.swing.JScrollPane();
        textField = new javax.swing.JTextArea();
        slideQuestionLabel = new javax.swing.JLabel();
        questionField = new javax.swing.JTextField();
        questionUploadButton = new javax.swing.JButton();
        qcPanel = new javax.swing.JPanel();
        prevQnButton = new javax.swing.JButton();
        clearQuestionButton = new javax.swing.JButton();
        jLabel5 = new javax.swing.JLabel();
        urlField = new javax.swing.JTextField();
        textFormatPanel = new javax.swing.JToolBar();
        textUploadButton = new javax.swing.JButton();
        formatButton = new javax.swing.JButton();
        launchBrowserButton = new javax.swing.JButton();
        imagePanel = new javax.swing.JPanel();
        cPanel2 = new javax.swing.JPanel();
        imageButton = new javax.swing.JButton();
        clearButton = new javax.swing.JButton();
        jPanel1 = new javax.swing.JPanel();
        jLabel4 = new javax.swing.JLabel();

        toprightPanel.setLayout(new java.awt.BorderLayout());

        setDefaultCloseOperation(javax.swing.WindowConstants.DO_NOTHING_ON_CLOSE);
        setTitle("Slide Builder Manager");
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });

        cPanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        cPanel.setLayout(new java.awt.BorderLayout());

        infoField.setFont(new java.awt.Font("Dialog", 1, 14));
        infoField.setForeground(new java.awt.Color(249, 170, 91));
        infoField.setText("Info");
        cPanel.add(infoField, java.awt.BorderLayout.CENTER);

        getContentPane().add(cPanel, java.awt.BorderLayout.PAGE_START);

        slideCPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Current Slide"));

        addButton.setFont(new java.awt.Font("DejaVu Sans", 1, 13)); // NOI18N
        addButton.setForeground(new java.awt.Color(255, 102, 51));
        addButton.setText("Save New Slide");
        addButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                addButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                addButtonMouseExited(evt);
            }
        });
        addButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addButtonActionPerformed(evt);
            }
        });
        slideCPanel.add(addButton);

        editSlideButton.setFont(new java.awt.Font("DejaVu Sans", 1, 13)); // NOI18N
        editSlideButton.setForeground(new java.awt.Color(255, 102, 51));
        editSlideButton.setText("Edit Slide");
        editSlideButton.setEnabled(false);
        editSlideButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                editSlideButtonActionPerformed(evt);
            }
        });
        slideCPanel.add(editSlideButton);

        deleteSlideButton.setFont(new java.awt.Font("DejaVu Sans", 1, 13)); // NOI18N
        deleteSlideButton.setForeground(new java.awt.Color(255, 0, 0));
        deleteSlideButton.setText("Delete Slide");
        deleteSlideButton.setEnabled(false);
        deleteSlideButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                deleteSlideButtonActionPerformed(evt);
            }
        });
        slideCPanel.add(deleteSlideButton);

        southPanel.add(slideCPanel);

        slideShowCPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Slide Show"));

        saveButton.setForeground(new java.awt.Color(0, 130, 0));
        saveButton.setText("Save Slide Show");
        saveButton.setEnabled(false);
        saveButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                saveButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                saveButtonMouseExited(evt);
            }
        });
        saveButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                saveButtonActionPerformed(evt);
            }
        });
        slideShowCPanel.add(saveButton);

        southPanel.add(slideShowCPanel);

        getContentPane().add(southPanel, java.awt.BorderLayout.PAGE_END);

        splitPane.setDividerLocation(180);

        listPanel.setLayout(new java.awt.BorderLayout());

        slideNameField.setFont(new java.awt.Font("Dialog", 0, 18));
        slideNameField.setText("Untitled");
        slideNameField.setPreferredSize(new java.awt.Dimension(200, 15));
        listPanel.add(slideNameField, java.awt.BorderLayout.PAGE_START);

        splitPane.setLeftComponent(listPanel);

        splitPane2.setDividerLocation(500);

        slideDetailsPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Slide Details"));
        slideDetailsPanel.setLayout(new java.awt.GridBagLayout());

        slideTitleLabel.setFont(new java.awt.Font("Dialog", 1, 11));
        slideTitleLabel.setText("Slide Title");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(1, 0, 0, 0);
        slideDetailsPanel.add(slideTitleLabel, gridBagConstraints);

        titleField.setEditable(false);
        titleField.setPreferredSize(new java.awt.Dimension(404, 19));
        titleField.addKeyListener(new java.awt.event.KeyAdapter() {
            public void keyPressed(java.awt.event.KeyEvent evt) {
                titleFieldKeyPressed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        slideDetailsPanel.add(titleField, gridBagConstraints);

        slideTextLabel.setFont(new java.awt.Font("Dialog", 1, 11));
        slideTextLabel.setText("Slide Text");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        slideDetailsPanel.add(slideTextLabel, gridBagConstraints);

        textFieldSP.setMinimumSize(new java.awt.Dimension(122, 122));

        textField.setColumns(20);
        textField.setEditable(false);
        textField.setRows(5);
        textField.addKeyListener(new java.awt.event.KeyAdapter() {
            public void keyPressed(java.awt.event.KeyEvent evt) {
                textFieldKeyPressed(evt);
            }
        });
        textFieldSP.setViewportView(textField);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(textFieldSP, gridBagConstraints);

        slideQuestionLabel.setFont(new java.awt.Font("Dialog", 1, 11));
        slideQuestionLabel.setText("Slide Question");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        slideDetailsPanel.add(slideQuestionLabel, gridBagConstraints);

        questionField.setEditable(false);
        questionField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                questionFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 7;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(questionField, gridBagConstraints);

        questionUploadButton.setFont(new java.awt.Font("Dialog", 0, 11));
        questionUploadButton.setText("Upload From Server");
        questionUploadButton.setEnabled(false);
        questionUploadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                questionUploadButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 7;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        slideDetailsPanel.add(questionUploadButton, gridBagConstraints);

        prevQnButton.setFont(new java.awt.Font("Dialog", 1, 11));
        prevQnButton.setText("Preview Question");
        prevQnButton.setEnabled(false);
        prevQnButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                prevQnButtonActionPerformed(evt);
            }
        });
        qcPanel.add(prevQnButton);

        clearQuestionButton.setFont(new java.awt.Font("Dialog", 1, 11));
        clearQuestionButton.setText("Clear");
        clearQuestionButton.setEnabled(false);
        clearQuestionButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                clearQuestionButtonActionPerformed(evt);
            }
        });
        qcPanel.add(clearQuestionButton);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 8;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(qcPanel, gridBagConstraints);

        jLabel5.setFont(new java.awt.Font("Dialog", 1, 11));
        jLabel5.setText("Slide Url");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 9;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        slideDetailsPanel.add(jLabel5, gridBagConstraints);

        urlField.setEditable(false);
        urlField.addKeyListener(new java.awt.event.KeyAdapter() {
            public void keyTyped(java.awt.event.KeyEvent evt) {
                urlFieldKeyTyped(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 10;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        slideDetailsPanel.add(urlField, gridBagConstraints);

        textFormatPanel.setFloatable(false);
        textFormatPanel.setOrientation(1);
        textFormatPanel.setRollover(true);

        textUploadButton.setFont(new java.awt.Font("Dialog", 0, 11));
        textUploadButton.setText("Load from File");
        textUploadButton.setEnabled(false);
        textUploadButton.setFocusable(false);
        textUploadButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        textUploadButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        textUploadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                textUploadButtonActionPerformed(evt);
            }
        });
        textFormatPanel.add(textUploadButton);

        formatButton.setFont(new java.awt.Font("Dialog", 0, 11));
        formatButton.setText("Format");
        formatButton.setFocusable(false);
        formatButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        formatButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        formatButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                formatButtonActionPerformed(evt);
            }
        });
        textFormatPanel.add(formatButton);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(textFormatPanel, gridBagConstraints);

        launchBrowserButton.setText("Insert URL");
        launchBrowserButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                launchBrowserButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 11;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(1, 2, 38, 0);
        slideDetailsPanel.add(launchBrowserButton, gridBagConstraints);

        splitPane2.setLeftComponent(slideDetailsPanel);

        imagePanel.setLayout(new java.awt.BorderLayout());

        cPanel2.setBorder(javax.swing.BorderFactory.createEtchedBorder());

        imageButton.setText("Add Image");
        imageButton.setEnabled(false);
        imageButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                imageButtonActionPerformed(evt);
            }
        });
        cPanel2.add(imageButton);

        clearButton.setText("Clear");
        clearButton.setEnabled(false);
        clearButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                clearButtonActionPerformed(evt);
            }
        });
        cPanel2.add(clearButton);

        imagePanel.add(cPanel2, java.awt.BorderLayout.PAGE_END);

        jLabel4.setFont(new java.awt.Font("Dialog", 1, 18));
        jLabel4.setForeground(new java.awt.Color(255, 102, 0));
        jLabel4.setText("Preview");
        jPanel1.add(jLabel4);

        imagePanel.add(jPanel1, java.awt.BorderLayout.PAGE_START);

        splitPane2.setRightComponent(imagePanel);

        splitPane.setRightComponent(splitPane2);

        getContentPane().add(splitPane, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void addButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_addButtonMouseEntered
        infoField.setText("Add New slide to current slide show");
}//GEN-LAST:event_addButtonMouseEntered

    private void addButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_addButtonMouseExited
        infoField.setText("Info");
}//GEN-LAST:event_addButtonMouseExited

    private void addButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addButtonActionPerformed
        saveSlide();
}//GEN-LAST:event_addButtonActionPerformed

    private void saveButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_saveButtonMouseEntered
        infoField.setText("Save this slide show");
}//GEN-LAST:event_saveButtonMouseEntered

    private void saveButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_saveButtonMouseExited
        infoField.setText("Info");
}//GEN-LAST:event_saveButtonMouseExited

    private void saveButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_saveButtonActionPerformed
        save();
}//GEN-LAST:event_saveButtonActionPerformed

    private void questionFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_questionFieldActionPerformed
        // TODO add your handling code here:
}//GEN-LAST:event_questionFieldActionPerformed

    private void questionUploadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_questionUploadButtonActionPerformed
        showQuestionFileChooser();

}//GEN-LAST:event_questionUploadButtonActionPerformed

    private void prevQnButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_prevQnButtonActionPerformed
        previewQuestion();
}//GEN-LAST:event_prevQnButtonActionPerformed

    private void clearQuestionButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_clearQuestionButtonActionPerformed
        questionField.setText("");
        question = null;
        setSaved(false);
}//GEN-LAST:event_clearQuestionButtonActionPerformed

    private void formatButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_formatButtonActionPerformed
        SlideBuilderTextFormatPanel p = new SlideBuilderTextFormatPanel();
        JOptionPane.showMessageDialog(this, p);

        Map<String, Object> format = p.getTextFormat();

        textColor = (Color) format.get("color");
        textSize = (Integer) format.get("size");
        textField.setForeground(textColor);
        textField.setFont(new Font("Dialog", 0, textSize));
        surface.repaint();
}//GEN-LAST:event_formatButtonActionPerformed

    private void textUploadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_textUploadButtonActionPerformed

        JFileChooser fc = new JFileChooser();
        if (fc.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {
            String selectedFile = fc.getSelectedFile().getAbsolutePath();
            textField.setText(GeneralUtil.readTextFile(selectedFile));
        }

}//GEN-LAST:event_textUploadButtonActionPerformed

    private void imageButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_imageButtonActionPerformed
        insertImage();
}//GEN-LAST:event_imageButtonActionPerformed

    private void clearButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_clearButtonActionPerformed
        surface.setSlideImage(null);
        image = null;
}//GEN-LAST:event_clearButtonActionPerformed

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
        RPacketListener.removeSlideShowListener(this);
        Boolean saved = GUIAccessManager.saveStatus.get("SlideBuilder");
        if (saved != null) {
            setSaved(true);
            dispose();
        } else {
            dispose();
        }
    }//GEN-LAST:event_formWindowClosing

    private void editSlideButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_editSlideButtonActionPerformed
        updateSlide();
    }//GEN-LAST:event_editSlideButtonActionPerformed

    private void titleFieldKeyPressed(java.awt.event.KeyEvent evt) {//GEN-FIRST:event_titleFieldKeyPressed
        setSaved(false);
    }//GEN-LAST:event_titleFieldKeyPressed

    private void textFieldKeyPressed(java.awt.event.KeyEvent evt) {//GEN-FIRST:event_textFieldKeyPressed
        setSaved(false);
    }//GEN-LAST:event_textFieldKeyPressed

    private void launchBrowserButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_launchBrowserButtonActionPerformed
        final JWebBrowser wb = new JWebBrowser();
        JWebBrowserWindow wbw = new JWebBrowserWindow(wb);
        wbw.setVisible(true);
        wb.addWebBrowserListener(new WebBrowserListener() {

            public void windowWillOpen(WebBrowserWindowWillOpenEvent arg0) {
            }

            public void windowOpening(WebBrowserWindowOpeningEvent arg0) {
            }

            public void windowClosing(WebBrowserEvent arg0) {
            }

            public void locationChanging(WebBrowserNavigationEvent arg0) {
            }

            public void locationChanged(WebBrowserNavigationEvent arg0) {
                urlField.setText(wb.getResourceLocation());
            }

            public void locationChangeCanceled(WebBrowserNavigationEvent arg0) {
            }

            public void loadingProgressChanged(WebBrowserEvent arg0) {
            }

            public void titleChanged(WebBrowserEvent arg0) {
            }

            public void statusChanged(WebBrowserEvent arg0) {
            }

            public void commandReceived(WebBrowserEvent arg0, String arg1, String[] arg2) {
            }
        });
    }//GEN-LAST:event_launchBrowserButtonActionPerformed

    private void urlFieldKeyTyped(java.awt.event.KeyEvent evt) {//GEN-FIRST:event_urlFieldKeyTyped
        setSaved(false);
    }//GEN-LAST:event_urlFieldKeyTyped

    private void deleteSlideButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_deleteSlideButtonActionPerformed
        deleteSlide();
    }//GEN-LAST:event_deleteSlideButtonActionPerformed

    private void deleteSlide() {
        int n = JOptionPane.showConfirmDialog(null, "Do really want to delete these slide?", "Delete", JOptionPane.YES_NO_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            slides.remove(selectedRow);
            model = new SlideListingTableModel();
            table.setModel(model);
            decorateTable();
        }
    }

    private void clear() {
        titleField.setText("");
        questionField.setText("");
        question = null;
        textField.setText("");
        urlField.setText("");
        image = null;
        surface.setSlideImage(image);
        customSlideText = "";
        surface.setCustomSlideText(customSlideText);
        model = new SlideListingTableModel();
        table.setModel(model);
        decorateTable();
        titleField.requestFocus();
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                new SlideBuilderManager().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton addButton;
    private javax.swing.JPanel cPanel;
    private javax.swing.JPanel cPanel2;
    private javax.swing.JButton clearButton;
    private javax.swing.JButton clearQuestionButton;
    private javax.swing.JButton deleteSlideButton;
    private javax.swing.JButton editSlideButton;
    private javax.swing.JButton formatButton;
    private javax.swing.JButton imageButton;
    private javax.swing.JPanel imagePanel;
    private javax.swing.JLabel infoField;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JButton launchBrowserButton;
    private javax.swing.JPanel listPanel;
    private javax.swing.JButton prevQnButton;
    private javax.swing.JPanel qcPanel;
    private javax.swing.JTextField questionField;
    private javax.swing.JButton questionUploadButton;
    private javax.swing.JButton saveButton;
    private javax.swing.JPanel slideCPanel;
    private javax.swing.JPanel slideDetailsPanel;
    private javax.swing.JLabel slideNameField;
    private javax.swing.JLabel slideQuestionLabel;
    private javax.swing.JPanel slideShowCPanel;
    private javax.swing.JLabel slideTextLabel;
    private javax.swing.JLabel slideTitleLabel;
    private javax.swing.JPanel southPanel;
    private javax.swing.JSplitPane splitPane;
    private javax.swing.JSplitPane splitPane2;
    private javax.swing.JTextArea textField;
    private javax.swing.JScrollPane textFieldSP;
    private javax.swing.JToolBar textFormatPanel;
    private javax.swing.JButton textUploadButton;
    private javax.swing.JTextField titleField;
    private javax.swing.JPanel toprightPanel;
    private javax.swing.JTextField urlField;
    // End of variables declaration//GEN-END:variables
}
