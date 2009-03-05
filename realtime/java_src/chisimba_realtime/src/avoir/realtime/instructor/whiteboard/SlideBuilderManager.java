/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * SlideBuilderManager.java
 *
 * Created on 2009/03/03, 12:15:16
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.common.BuilderSlide;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.packet.FileVewRequestPacket;
import avoir.realtime.common.packet.QuestionPacket;
import avoir.realtime.common.packet.SlideBuilderPacket;
import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.common.Base64;
import avoir.realtime.common.packet.SlideShowPopulateRequest;
import avoir.realtime.instructor.SlideBuilderTextFormatPanel;
import avoir.realtime.survey.AnsweringFrame;
import avoir.realtime.survey.Value;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;

import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.font.FontRenderContext;
import java.awt.font.LineBreakMeasurer;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.awt.image.BufferedImage;
import java.io.ByteArrayOutputStream;
import java.text.AttributedCharacterIterator;
import java.text.AttributedString;
import java.util.ArrayList;
import java.util.Hashtable;
import java.util.Map;
import javax.imageio.ImageIO;
import javax.swing.BorderFactory;
import javax.swing.DefaultListModel;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.ListCellRenderer;
import javax.swing.ListSelectionModel;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;

/**
 *
 * @author developer
 */
public class SlideBuilderManager extends javax.swing.JFrame {

    private Color textColor = Color.BLACK;
    private int textSize = 18;
    private DefaultListModel model = new DefaultListModel();
    private JList list;
    private ImageIcon slideImage;
    private Surface surface = new Surface();
    private Classroom mf;
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private XmlQuestionPacket question;
    int slideCount = 0;
    private ImageIcon fileIcon = ImageUtil.createImageIcon(this, "/icons/file.png");
    private String mode = "add-slide";
    boolean saved = false;
    private String customSlideText;
    int textXPos = 100;
    int textYPos = 100;
    int offSetX = 0;
    int offSetY = 0;
    private Graphics2D graphics;
    private boolean dragText = false;

    /** Creates new form SlideBuilderManager */
    public SlideBuilderManager(Classroom mf) {
        initComponents();
        surface.setBorder(BorderFactory.createLoweredBevelBorder());
        this.mf = mf;
        list = new JList(model);
        listPanel.add(new JScrollPane(list), BorderLayout.CENTER);
        list.setCellRenderer(new TitleListRenderer());
        imagePanel.add(new JScrollPane(surface), BorderLayout.CENTER);
        ListSelectionModel listSelectionModel = list.getSelectionModel();
        listSelectionModel.addListSelectionListener(
                new SharedListSelectionHandler());
        textField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent e) {
                customSlideText = textField.getText();
                surface.repaint();
            }

            public void removeUpdate(DocumentEvent e) {
                customSlideText = textField.getText();
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
        mode = "save-slide";
        addButton.setText("Save Slide");
        titleField.requestFocus();
    }

    public ImageIcon getSlideImage() {
        return slideImage;
    }

    public void setSlideImage(ImageIcon slideImage) {
        this.slideImage = slideImage;
        surface.repaint();
        splitPane2.setDividerLocation(splitPane2.getDividerLocation() + 5);
        splitPane2.setDividerLocation(splitPane2.getDividerLocation() - 5);
    }

    public XmlQuestionPacket getQuestion() {
        return question;
    }

    public void setQuestion(XmlQuestionPacket question) {
        this.question = question;
        questionField.setText(((TCPConnector) mf.getTcpConnector()).getSelectedFilePath());
    }

    class Surface extends JPanel implements MouseListener, MouseMotionListener {

        int startX, startY, currentX, currentY;
        // The LineBreakMeasurer used to line-break the paragraph.
        private LineBreakMeasurer lineMeasurer;
        // index of the first character in the paragraph.
        private int paragraphStart;
        // index of the first character after the end of the paragraph.
        private int paragraphEnd;
        private final Hashtable<TextAttribute, Object> map =
                new Hashtable<TextAttribute, Object>();

        public Surface() {
            setBackground(Color.WHITE);
            map.put(TextAttribute.FAMILY, "Serif");
            map.put(TextAttribute.SIZE, new Float(18.0));
            addMouseListener(this);
            addMouseMotionListener(this);
        }

        public void mouseDragged(MouseEvent evt) {
            if (dragText) {
                textXPos = evt.getX() - offSetX;
                textYPos = evt.getY() - offSetY;
                repaint();
            }
        }

        public void mouseMoved(MouseEvent e) {
        }

        public void mouseClicked(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
        }

        public void mouseExited(MouseEvent e) {
        }

        public void mousePressed(MouseEvent evt) {
            startX = evt.getX();
            startY = evt.getY();

            if (graphics != null) {
                FontMetrics fm = graphics.getFontMetrics();
                int totalLines = customSlideText.split("\n").length;
                Rectangle rect = new Rectangle(textXPos, textYPos - fm.getHeight(),
                        fm.stringWidth(customSlideText), fm.getAscent() * (totalLines + 1));

                if (rect.contains(evt.getPoint())) {
                    dragText = true;
                    offSetX = evt.getX() - textXPos;
                    offSetY = evt.getY() - textYPos;
                } else {
                    dragText = false;
                }
                //System.out.println(dragText);
            }
            repaint();
        }

        public void mouseReleased(MouseEvent e) {
            dragText = false;
            repaint();
        }

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            Graphics2D g2 = (Graphics2D) g;
            graphics = g2;
            if (slideImage != null) {
                g2.drawImage(slideImage.getImage(), 50, 50, this);
                setPreferredSize(new Dimension(slideImage.getIconWidth(), slideImage.getIconHeight()));
            }
            if (customSlideText != null) {
                FontMetrics fm = graphics.getFontMetrics();
                if (!customSlideText.trim().equals("")) {
                    g2.setColor(textColor);
                    g2.setFont(new Font("Dialog", 0, textSize));
                    String lines[] = customSlideText.split("\n");
                    int yy = textYPos;
                    int longest = 0;
                    for (int i = 0; i < lines.length; i++) {
                        g2.drawString(lines[i], textXPos, yy);
                        if (fm.stringWidth(lines[i]) > longest) {
                            longest = fm.stringWidth(lines[i]);
                        }
                        yy += fm.getHeight();
                    }

                    //drawCustomText(g2);
                    if (dragText) {

                        g2.drawRect(textXPos, textYPos - fm.getHeight(),
                                longest, (fm.getHeight() * (lines.length + 1)) + 10);
                    }
                }
            }
            revalidate();

        }

        private void drawCustomText(Graphics2D g2) {
            if (customSlideText.trim().equals("")) {
                return;
            }

            // Create a new LineBreakMeasurer from the paragraph.
            // It will be cached and re-used.
            if (lineMeasurer == null) {
                AttributedString str = new AttributedString(customSlideText);
                AttributedCharacterIterator paragraph = str.getIterator();
                paragraphStart = paragraph.getBeginIndex();
                paragraphEnd = paragraph.getEndIndex();
                FontRenderContext frc = g2.getFontRenderContext();
                lineMeasurer = new LineBreakMeasurer(paragraph, frc);
            }

            // Set break width to width of Component.
            float breakWidth = (float) getSize().width;
            float drawPosY = textYPos;
            lineMeasurer.setPosition(paragraphStart);

            // Get lines until the entire paragraph has been displayed.
            while (lineMeasurer.getPosition() < paragraphEnd) {
                TextLayout layout = lineMeasurer.nextLayout(breakWidth);

                float drawPosX = layout.isLeftToRight()
                        ? textXPos : breakWidth - layout.getAdvance();
                drawPosY += layout.getAscent();
                layout.draw(g2, drawPosX, drawPosY);
                drawPosY += layout.getDescent() + layout.getLeading();
            }
        }
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
            // RealtimeFile file = (RealtimeFile) value;
            BuilderSlide slide = (BuilderSlide) value;
            ImageIcon icon = fileIcon;// file.isDirectory() ? folderIcon : fileIcon;

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

    public void setSlideText(String txt) {
        textField.setText(txt);
        customSlideText = txt;
        surface.repaint();
    }

    public void setQuestionPath(String txt) {
        questionField.setText(txt);
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

    }

    private void reset() {
        setControlsEnabled(true);
        textField.setText("");
        questionField.setText("");
        question = null;
        setSlideImage(null);
        titleField.setText("");
        titleField.requestFocus();

    }

    private void processAdd() {
        saved = false;
        if (mode.equals("edit-slide")) {
            String title = titleField.getText();
            if (title.trim().equals("")) {
                title = "Untitled" + (slideCount++);
            }
            String text = textField.getText();
            String questionPath = questionField.getText();
            if (question != null) {
                question.setQuestionPath(questionPath);
            }

            BuilderSlide slide = (BuilderSlide) model.elementAt(list.getSelectedIndex());
            //title, text, textColor, textSize, question, slideImage
            slide.setTitle(title);
            slide.setText(text);
            slide.setTextColor(textColor);
            slide.setTextSize(textSize);
            slide.setQuestion(question);
            slide.setImage(slideImage);


            model.setElementAt(slide, list.getSelectedIndex());
            mode = "add-slide";
            addButton.setText("Add New Slide");
            setControlsEnabled(false);
            editSlideButton.setEnabled(false);
            return;
        }
        if (mode.equals("add-slide")) {
            reset();
            mode = "save-slide";
            addButton.setText("Save Slide");
            return;
        }
        if (mode.equals("save-slide")) {
            String title = titleField.getText();
            if (title.trim().equals("")) {
                title = "Untitled" + (slideCount++);
            }
            String text = textField.getText();
            String questionPath = questionField.getText();
            if (question != null) {
                question.setQuestionPath(questionPath);
            }
            BuilderSlide slide = new BuilderSlide(title, text, textColor, textSize, question, slideImage,
                    ((TCPConnector) mf.getTcpConnector()).getSelectedFilePath(),
                    model.getSize(), textXPos, textYPos);
            model.addElement(slide);
            mode = "add-slide";
            addButton.setText("Add New Slide");
            setControlsEnabled(false);
            return;
        }

        setControlsEnabled(true);
        mode = "save-slide";
        addButton.setEnabled(false);
        titleField.requestFocus();
    }

    private String encodeImage(Image image, int i) {
        try {

            if (image == null) {
                return "";
            }

            BufferedImage bu = new BufferedImage(image.getWidth(null), image.getHeight(null), BufferedImage.TYPE_INT_RGB);
            Graphics g = bu.getGraphics();
            g.drawImage(image, 0, 0, null);
            g.dispose();
            ByteArrayOutputStream bas = new ByteArrayOutputStream();
            ImageIO.write(bu, "png", bas);
            byte[] data = bas.toByteArray();
            return Base64.encode(data);
        } catch (Exception ex) {
            ex.printStackTrace();

            return null;
        }

    }

    private void save() {
        if (mode.equals("save-slide")) {
            int n = JOptionPane.showConfirmDialog(null, "There is unsaved slide, save it?", "Unsaved Slide",
                    JOptionPane.YES_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                processAdd();
            }
        }
        String name = JOptionPane.showInputDialog("File Name:", slideNameField.getText());
        ArrayList<XmlBuilderSlide> slides = new ArrayList<XmlBuilderSlide>();
        if (name != null) {
            for (int i = 0; i < model.size(); i++) {
                BuilderSlide slide = (BuilderSlide) model.elementAt(i);
                ImageIcon im = slide.getImage();

                Image image = null;
                if (im != null) {
                    image = slide.getImage().getImage();
                }

                XmlQuestionPacket qn = slide.getQuestion();
                String imageData = encodeImage(image, i);

                ImageIcon sll = null;
                String qnStr = "";

                ArrayList<Value> answerOptions = new ArrayList<Value>();
                int qnType = -1;
                String essayAns = "";
                String sender = "";
                String qnId = "";
                String sessionId = "";
                String qname = "";
                String qpath = "";
                boolean isAnswered = false;
                if (qn != null) {
                    sll = qn.getImage();
                    qnStr = qn.getQuestion();
                    answerOptions = qn.getAnswerOptions();
                    qnType = qn.getType();
                    essayAns = qn.getEssayAnswer();
                    sender = qn.getSender();
                    qnId = qn.getId();
                    sessionId = qn.getSessionId();
                    isAnswered = qn.isAnswered();
                    qname = qn.getName();
                    qpath = qn.getImagePath();
                }
                Image qnImage = sll == null ? null : sll.getImage();
                String qnImageData = qnImage == null ? "" : encodeImage(qnImage, i);
                XmlBuilderSlide xmlSlide = new XmlBuilderSlide(
                        slide.getTitle(),
                        slide.getText(),
                        textColor,
                        textSize,
                        imageData,
                        qnStr,
                        answerOptions,
                        qnType,
                        essayAns,
                        sender,
                        qnId,
                        sessionId,
                        isAnswered,
                        qname,
                        qnImageData,
                        qpath,
                        slide.getIndex(),
                        textXPos,
                        textYPos);
                slides.add(xmlSlide);
            }
            mf.getTcpConnector().setFileManagerMode("slide-show-list");
            mf.getTcpConnector().sendPacket(new SlideBuilderPacket(slides, name));
        }
        setTitle("Slide Builder: " + name);
        slideNameField.setText(name);
        saved = true;

    }

    public void setSlides(ArrayList<BuilderSlide> slides, String name) {
        model.clear();
        slideNameField.setText(name);
        setTitle("Slide Builder: " + name);
        for (int i = 0; i < slides.size(); i++) {
            model.addElement(slides.get(i));
            if (i == 0) {
                textColor = slides.get(0).getTextColor();
                textSize = slides.get(0).getTextSize();
                list.setSelectedIndex(0);

            }
        }
        saved = true;
    }

    class SharedListSelectionHandler implements ListSelectionListener {

        public void valueChanged(ListSelectionEvent e) {
            ListSelectionModel lsm = (ListSelectionModel) e.getSource();

            int firstIndex = e.getFirstIndex();
            int lastIndex = e.getLastIndex();
            boolean isAdjusting = e.getValueIsAdjusting();

            if (lsm.isSelectionEmpty()) {
                editSlideButton.setEnabled(false);
            } else {
                // Find out which indexes are selected.
                int minIndex = lsm.getMinSelectionIndex();
                int maxIndex = lsm.getMaxSelectionIndex();
                editSlideButton.setEnabled(true);
                BuilderSlide slide = (BuilderSlide) model.elementAt(minIndex);
                question = slide.getQuestion();
                textColor = slide.getTextColor();
                textSize = slide.getTextSize();
                textField.setForeground(textColor);
                textField.setFont(new Font("Dialog", 0, textSize));
                titleField.setText(slide.getTitle());
                textField.setText(slide.getText());

                if (question != null) {
                    questionField.setText(question.getQuestionPath());
                }
                setSlideImage(slide.getImage());
            }
        }
    }

    private void play() {
        if (!saved) {
            int n = JOptionPane.showConfirmDialog(null, "Slide show not saved. Save now",
                    "Not saved",
                    JOptionPane.YES_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                save();
                //String path = ((TCPConnector) mf.getTcpConnector()).getSelectedFilePath();
                mf.getTcpConnector().sendPacket(new SlideShowPopulateRequest(mf.getUser().getSessionId(),
                        slideNameField.getText()));

            } else {
                JOptionPane.showMessageDialog(null, "Cannot not play unsaved slide");
            }
        } else {
            mf.getTcpConnector().sendPacket(new SlideShowPopulateRequest(mf.getUser().getSessionId(),
                    slideNameField.getText()));

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

        cPanel = new javax.swing.JPanel();
        infoField = new javax.swing.JLabel();
        southPanel = new javax.swing.JPanel();
        slideCPanel = new javax.swing.JPanel();
        addButton = new javax.swing.JButton();
        editSlideButton = new javax.swing.JButton();
        showControls = new javax.swing.JPanel();
        newButton = new javax.swing.JButton();
        openButton = new javax.swing.JButton();
        saveButton = new javax.swing.JButton();
        mscCPanel = new javax.swing.JPanel();
        previewButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        splitPane = new javax.swing.JSplitPane();
        splitPane2 = new javax.swing.JSplitPane();
        toprightPanel = new javax.swing.JPanel();
        slideDetailsPanel = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        titleField = new javax.swing.JTextField();
        jLabel2 = new javax.swing.JLabel();
        textFieldSP = new javax.swing.JScrollPane();
        textField = new javax.swing.JTextArea();
        jLabel3 = new javax.swing.JLabel();
        questionField = new javax.swing.JTextField();
        questionUploadButton = new javax.swing.JButton();
        qcPanel = new javax.swing.JPanel();
        prevQnButton = new javax.swing.JButton();
        clearQuestionButton = new javax.swing.JButton();
        textformatPanel = new javax.swing.JPanel();
        formatButton = new javax.swing.JButton();
        textUploadButton = new javax.swing.JButton();
        imagePanel = new javax.swing.JPanel();
        cPanel2 = new javax.swing.JPanel();
        imageButton = new javax.swing.JButton();
        clearButton = new javax.swing.JButton();
        jPanel1 = new javax.swing.JPanel();
        jLabel4 = new javax.swing.JLabel();
        listPanel = new javax.swing.JPanel();
        slideNameField = new javax.swing.JLabel();

        setTitle("Slide Builder ");

        cPanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        cPanel.setLayout(new java.awt.BorderLayout());

        infoField.setFont(new java.awt.Font("Dialog", 1, 14));
        infoField.setForeground(new java.awt.Color(249, 170, 91));
        infoField.setText("Info");
        cPanel.add(infoField, java.awt.BorderLayout.CENTER);

        getContentPane().add(cPanel, java.awt.BorderLayout.PAGE_START);

        slideCPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Slide Controls"));

        addButton.setText("Add New Slide");
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

        editSlideButton.setText("Edit Slide");
        editSlideButton.setEnabled(false);
        editSlideButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                editSlideButtonActionPerformed(evt);
            }
        });
        slideCPanel.add(editSlideButton);

        southPanel.add(slideCPanel);

        showControls.setBorder(javax.swing.BorderFactory.createTitledBorder("Slide Show"));

        newButton.setText("New");
        newButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                newButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                newButtonMouseExited(evt);
            }
        });
        newButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                newButtonActionPerformed(evt);
            }
        });
        showControls.add(newButton);

        openButton.setText("Open");
        openButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                openButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                openButtonMouseExited(evt);
            }
        });
        openButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                openButtonActionPerformed(evt);
            }
        });
        showControls.add(openButton);

        saveButton.setText("Save");
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
        showControls.add(saveButton);

        southPanel.add(showControls);

        mscCPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("General"));

        previewButton.setText("Start Slide Show ");
        previewButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                previewButtonActionPerformed(evt);
            }
        });
        mscCPanel.add(previewButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        mscCPanel.add(closeButton);

        southPanel.add(mscCPanel);

        getContentPane().add(southPanel, java.awt.BorderLayout.PAGE_END);

        splitPane.setDividerLocation(180);

        splitPane2.setOrientation(javax.swing.JSplitPane.VERTICAL_SPLIT);
        splitPane2.setPreferredSize(new java.awt.Dimension(300, 150));

        toprightPanel.setLayout(new java.awt.BorderLayout());

        slideDetailsPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Slide Details"));
        slideDetailsPanel.setLayout(new java.awt.GridBagLayout());

        jLabel1.setText("Slide Title");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTH;
        slideDetailsPanel.add(jLabel1, gridBagConstraints);

        titleField.setEditable(false);
        titleField.setPreferredSize(new java.awt.Dimension(404, 19));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(titleField, gridBagConstraints);

        jLabel2.setText("Slide Text");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTH;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        slideDetailsPanel.add(jLabel2, gridBagConstraints);

        textFieldSP.setMinimumSize(new java.awt.Dimension(122, 122));

        textField.setColumns(20);
        textField.setEditable(false);
        textField.setRows(5);
        textFieldSP.setViewportView(textField);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        slideDetailsPanel.add(textFieldSP, gridBagConstraints);

        jLabel3.setText("Slide Question");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        slideDetailsPanel.add(jLabel3, gridBagConstraints);

        questionField.setEditable(false);
        questionField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                questionFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(questionField, gridBagConstraints);

        questionUploadButton.setText("Upload From Server");
        questionUploadButton.setEnabled(false);
        questionUploadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                questionUploadButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        slideDetailsPanel.add(questionUploadButton, gridBagConstraints);

        prevQnButton.setText("Preview Question");
        prevQnButton.setEnabled(false);
        prevQnButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                prevQnButtonActionPerformed(evt);
            }
        });
        qcPanel.add(prevQnButton);

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
        gridBagConstraints.gridy = 5;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(qcPanel, gridBagConstraints);

        formatButton.setText("Format");
        formatButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                formatButtonActionPerformed(evt);
            }
        });
        textformatPanel.add(formatButton);

        textUploadButton.setText("Load from File");
        textUploadButton.setEnabled(false);
        textUploadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                textUploadButtonActionPerformed(evt);
            }
        });
        textformatPanel.add(textUploadButton);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        slideDetailsPanel.add(textformatPanel, gridBagConstraints);

        toprightPanel.add(slideDetailsPanel, java.awt.BorderLayout.CENTER);

        splitPane2.setLeftComponent(toprightPanel);

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
        cPanel2.add(clearButton);

        imagePanel.add(cPanel2, java.awt.BorderLayout.PAGE_END);

        jLabel4.setFont(new java.awt.Font("Dialog", 1, 18));
        jLabel4.setForeground(new java.awt.Color(255, 102, 0));
        jLabel4.setText("Preview");
        jPanel1.add(jLabel4);

        imagePanel.add(jPanel1, java.awt.BorderLayout.PAGE_START);

        splitPane2.setRightComponent(imagePanel);

        splitPane.setRightComponent(splitPane2);

        listPanel.setLayout(new java.awt.BorderLayout());

        slideNameField.setFont(new java.awt.Font("Dialog", 0, 18));
        slideNameField.setText("Untitled");
        slideNameField.setPreferredSize(new java.awt.Dimension(200, 15));
        listPanel.add(slideNameField, java.awt.BorderLayout.PAGE_START);

        splitPane.setLeftComponent(listPanel);

        getContentPane().add(splitPane, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void questionFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_questionFieldActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_questionFieldActionPerformed

    private void addButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addButtonActionPerformed
        processAdd();
    }//GEN-LAST:event_addButtonActionPerformed

    private void textUploadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_textUploadButtonActionPerformed
        String path = mf.getUser().getUserName()+"/documents";
        mf.getTcpConnector().setFileManagerMode("slide-builder-text");
        mf.getTcpConnector().sendPacket(new FileVewRequestPacket(path));
    }//GEN-LAST:event_textUploadButtonActionPerformed

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
        dispose();
    }//GEN-LAST:event_closeButtonActionPerformed

    private void prevQnButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_prevQnButtonActionPerformed
        previewQuestion();
    }//GEN-LAST:event_prevQnButtonActionPerformed

    private void questionUploadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_questionUploadButtonActionPerformed
        String path = mf.getUser().getUserName() + "/questions";
        mf.getTcpConnector().setFileManagerMode("slide-builder-question");
        mf.getTcpConnector().sendPacket(new FileVewRequestPacket(path));
    }//GEN-LAST:event_questionUploadButtonActionPerformed

    private void clearQuestionButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_clearQuestionButtonActionPerformed
        questionField.setText("");
        question = null;
    }//GEN-LAST:event_clearQuestionButtonActionPerformed

    private void imageButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_imageButtonActionPerformed
        String path = mf.getUser().getUserName() + "/images";
        mf.getTcpConnector().setFileManagerMode("slide-builder-image");
        mf.getTcpConnector().sendPacket(new FileVewRequestPacket(path));
    }//GEN-LAST:event_imageButtonActionPerformed

    private void saveButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_saveButtonActionPerformed
        save();
    }//GEN-LAST:event_saveButtonActionPerformed

    private void openButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_openButtonActionPerformed
        open();
    }//GEN-LAST:event_openButtonActionPerformed

    private void newButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_newButtonActionPerformed
        mf.showSlideBuilder();
    }//GEN-LAST:event_newButtonActionPerformed

    private void addButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_addButtonMouseEntered
        infoField.setText("Add New slide to current slide show");
    }//GEN-LAST:event_addButtonMouseEntered

    private void addButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_addButtonMouseExited
        infoField.setText("Info");
    }//GEN-LAST:event_addButtonMouseExited

    private void newButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_newButtonMouseEntered
        infoField.setText("Start a new slide builder");
    }//GEN-LAST:event_newButtonMouseEntered

    private void newButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_newButtonMouseExited
        infoField.setText("Info");
    }//GEN-LAST:event_newButtonMouseExited

    private void openButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_openButtonMouseEntered
        infoField.setText("Open existing slide show");
    }//GEN-LAST:event_openButtonMouseEntered

    private void openButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_openButtonMouseExited
        infoField.setText("Info");
    }//GEN-LAST:event_openButtonMouseExited

    private void saveButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_saveButtonMouseEntered
        infoField.setText("Save this slide show");
    }//GEN-LAST:event_saveButtonMouseEntered

    private void saveButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_saveButtonMouseExited
        infoField.setText("Info");
    }//GEN-LAST:event_saveButtonMouseExited

    private void previewButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_previewButtonActionPerformed
        play();
    }//GEN-LAST:event_previewButtonActionPerformed

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

    private void editSlideButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_editSlideButtonActionPerformed
        setControlsEnabled(true);
        mode = "edit-slide";
        saved = false;
        addButton.setText("Save Slide");
    }//GEN-LAST:event_editSlideButtonActionPerformed

    private void open() {
        String path = mf.getUser().getUserName() + "/slides/";
        ((TCPConnector) mf.getTcpConnector()).setFileManagerMode("slide-show");
        mf.getTcpConnector().sendPacket(new FileVewRequestPacket(path));
    }

    private void previewQuestion() {
        if (question == null) {
            return;
        }
        QuestionPacket qn = new QuestionPacket(question.getQuestion(), question.getAnswerOptions(), question.getType(),
                question.getSender(), question.getId(), question.getImage(),
                question.getImagePath());

        AnsweringFrame answerFrame = new AnsweringFrame(qn, mf, true);
        answerFrame.setTitle("Preview");
        answerFrame.setAlwaysOnTop(true);
        answerFrame.setSize((ss.width / 8) * 5, (ss.height / 8) * 5);
        answerFrame.setLocationRelativeTo(null);

        answerFrame.setQuestionImage(question.getImage());
        answerFrame.setVisible(true);
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                // new SlideBuilderManager().setVisible(true);
            }
        });
    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton addButton;
    private javax.swing.JPanel cPanel;
    private javax.swing.JPanel cPanel2;
    private javax.swing.JButton clearButton;
    private javax.swing.JButton clearQuestionButton;
    private javax.swing.JButton closeButton;
    private javax.swing.JButton editSlideButton;
    private javax.swing.JButton formatButton;
    private javax.swing.JButton imageButton;
    private javax.swing.JPanel imagePanel;
    private javax.swing.JLabel infoField;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel listPanel;
    private javax.swing.JPanel mscCPanel;
    private javax.swing.JButton newButton;
    private javax.swing.JButton openButton;
    private javax.swing.JButton prevQnButton;
    private javax.swing.JButton previewButton;
    private javax.swing.JPanel qcPanel;
    private javax.swing.JTextField questionField;
    private javax.swing.JButton questionUploadButton;
    private javax.swing.JButton saveButton;
    private javax.swing.JPanel showControls;
    private javax.swing.JPanel slideCPanel;
    private javax.swing.JPanel slideDetailsPanel;
    private javax.swing.JLabel slideNameField;
    private javax.swing.JPanel southPanel;
    private javax.swing.JSplitPane splitPane;
    private javax.swing.JSplitPane splitPane2;
    private javax.swing.JTextArea textField;
    private javax.swing.JScrollPane textFieldSP;
    private javax.swing.JButton textUploadButton;
    private javax.swing.JPanel textformatPanel;
    private javax.swing.JTextField titleField;
    private javax.swing.JPanel toprightPanel;
    // End of variables declaration//GEN-END:variables
}
