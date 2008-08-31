/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.packet.PresencePacket;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.net.URL;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;

/**
 *
 * @author developer
 */
public class ToolbarManager extends JToolBar {

    private Version v = new Version();
    private JToolBar slidesToolBar = new JToolBar();
    private JToolBar controlToolbar = new JToolBar();
    private JToolBar generalToolbar = new JToolBar();
    private MButton firstSlideButton,  nextSlideButton,  backSlideButton,  lastSlideButton;
    private TButton sessionButton;
    private javax.swing.JToggleButton yesButton;
    private javax.swing.JButton surveyButton;
    private javax.swing.JButton voiceOptionsButton;
    private javax.swing.JToggleButton noButton;
    private javax.swing.JToggleButton wbButton;
    private javax.swing.JButton optionsButton;
    private javax.swing.JButton refreshButton;
    private javax.swing.JButton fileTransferButton;
    private javax.swing.ButtonGroup actionsBG;
    private javax.swing.JButton chatButton;
    private javax.swing.JToggleButton handButton;
    private RealtimeBase base;

    public ToolbarManager(RealtimeBase base) {
        this.base = base;


    }

    public MButton getBackSlideButton() {
        return backSlideButton;
    }

    public MButton getFirstSlideButton() {
        return firstSlideButton;
    }

    public void setButtonsEnabled(boolean isPresenter) {
        surveyButton.setEnabled(isPresenter);
        firstSlideButton.setEnabled(isPresenter);
        backSlideButton.setEnabled(base.isPresenter());
        nextSlideButton.setEnabled(base.isPresenter());
        lastSlideButton.setEnabled(base.isPresenter());
        yesButton.setEnabled(base.isPresenter());
        noButton.setEnabled(base.isPresenter());
        wbButton.setEnabled(base.isPresenter());
    }

    public MButton getLastSlideButton() {
        return lastSlideButton;
    }

    public MButton getNextSlideButton() {
        return nextSlideButton;
    }

    public JToolBar createToolbar() {
        initComponents();
        return this;
    }

    /**
     * Starts or stops vote session
     * @param status if true starts vote session
     * @param privateVote if vote result is visible to all or not
     */
    public void setVoteButtonsEnabled(boolean status, boolean privateVote) {
        //as long as voting is on, all hands should be down! :)
        handButton.setEnabled(false);
        yesButton.setEnabled(status);
        noButton.setEnabled(status);
        base.getSessionManager().setPrivateVote(privateVote);
    }

    private void setCustomActions() {
        wbButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                base.showPointerToolBar(wbButton.isSelected());
            }
        });

        surveyButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                base.showSurveyManagerFrame();
            }
        });
        nextSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (base.getTcpClient().isNetworkAlive()) {
                    int slideIndex = base.getSessionManager().getSlideIndex();
                    if (slideIndex < base.getSessionManager().getSlideCount() - 1) {
                        base.recordXml(slideIndex);
                        base.getSessionManager().setSlideIndex(++slideIndex);
                        base.getTcpClient().requestNewSlide(base.getSiteRoot(), slideIndex, base.isPresenter(), base.getSessionId(), base.getUser().getUserName(), base.getControl());
                    }
                } else {
                    base.getTcpClient().setUserOffline();
                }
            }
        });

        backSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (base.getTcpClient().isNetworkAlive()) {
                    int slideIndex = base.getSessionManager().getSlideIndex();
                    base.recordXml(slideIndex);
                    if (slideIndex > 0) {
                        base.getSessionManager().setSlideIndex(--slideIndex);
                        base.getTcpClient().requestNewSlide(base.getSiteRoot(), slideIndex, base.isPresenter(), base.getSessionId(), base.getUser().getUserName(), base.getControl());

                    }
                } else {
                    base.getTcpClient().setUserOffline();
                }
            }
        });
        firstSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (base.getTcpClient().isNetworkAlive()) {
                    int slideIndex = 0;
                    base.getSessionManager().setSlideIndex(slideIndex);
                    base.getTcpClient().requestNewSlide(base.getSiteRoot(), slideIndex, base.isPresenter(), base.getSessionId(), base.getUser().getUserName(), base.getControl());
                    base.recordXml(slideIndex);
                } else {
                    base.getTcpClient().setUserOffline();
                }
            }
        });
        lastSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (base.getTcpClient().isNetworkAlive()) {
                    int slideIndex = base.getSessionManager().getSlideCount() - 1;
                    base.getSessionManager().setSlideIndex(slideIndex);
                    base.getTcpClient().requestNewSlide(base.getSiteRoot(), slideIndex, base.isPresenter(), base.getSessionId(), base.getUser().getUserName(), base.getControl());
                    base.recordXml(slideIndex);
                } else {
                    base.getTcpClient().setUserOffline();
                }
            }
        });

    }

    public JButton getVoiceOptionsButton() {
        return voiceOptionsButton;
    }

    public JToggleButton getYesButton() {
        return yesButton;
    }

    public JButton getChatButton() {
        return chatButton;
    }

    public JToggleButton getHandButton() {
        return handButton;
    }

    public JToggleButton getNoButton() {
        return noButton;
    }

    public JButton getOptionsButton() {
        return optionsButton;
    }

    public JButton getRefreshButton() {
        return refreshButton;
    }

    public TButton getSessionButton() {
        return sessionButton;
    }

    public JButton getSurveyButton() {
        return surveyButton;
    }

    public void enableButtons(boolean state) {

        chatButton.setEnabled(state);
        handButton.setEnabled(state);
        voiceOptionsButton.setEnabled(state);
        surveyButton.setEnabled(true);
    }

    public void createWhiteboard() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    // <editor-fold defaultstate="collapsed" desc="Generated Code">                          
    private void initComponents() {
        firstSlideButton = new MButton(createImageIcon(this, "/icons/first.png"));
        firstSlideButton.setText("First");
        backSlideButton = new MButton(createImageIcon(this, "/icons/back.png"));
        backSlideButton.setText("Back");
        nextSlideButton = new MButton(createImageIcon(this, "/icons/next.png"));
        nextSlideButton.setText("Next");
        lastSlideButton = new MButton(createImageIcon(this, "/icons/last.png"));
        lastSlideButton.setText("Last");


        slidesToolBar.setBorder(BorderFactory.createEtchedBorder());
        slidesToolBar.setRollover(true);
        slidesToolBar.setPreferredSize(new java.awt.Dimension(18, 25));
        slidesToolBar.add(firstSlideButton);
        slidesToolBar.add(backSlideButton);
        slidesToolBar.add(nextSlideButton);
        slidesToolBar.add(lastSlideButton);


        add(slidesToolBar);

        actionsBG = new javax.swing.ButtonGroup();
        slidesToolBar = new javax.swing.JToolBar();
        handButton = new javax.swing.JToggleButton();
        yesButton = new javax.swing.JToggleButton();
        noButton = new javax.swing.JToggleButton();
        chatButton = new javax.swing.JButton();
        voiceOptionsButton = new javax.swing.JButton();
        refreshButton = new javax.swing.JButton();
        optionsButton = new javax.swing.JButton();

        slidesToolBar.setRollover(true);
        slidesToolBar.setPreferredSize(new java.awt.Dimension(18, 25));

        handButton.setFont(new java.awt.Font("Dialog", 0, 9));
        handButton.setText("Hand");
        handButton.setToolTipText("Raise Hand");
        handButton.setBorderPainted(false);



        handButton.setFocusable(false);
        handButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        handButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        handButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                handButtonActionPerformed(evt);
            }
        });

        /*
        handButton.addMouseListener(
        new java.awt.event.MouseAdapter() {
        
        @Override
        public void mouseEntered(java.awt.event.MouseEvent evt) {
        handButtonMouseEntered(evt);
        }
        
        @Override
        public void mouseExited(java.awt.event.MouseEvent evt) {
        handButtonMouseExited(evt);
        }
        });*/
        controlToolbar.add(handButton);

        yesButton.setFont(new java.awt.Font("Dialog", 0, 9));
        yesButton.setText("Yes");
        yesButton.setToolTipText("Say Yes");
        yesButton.setBorderPainted(false);
        yesButton.setEnabled(false);
        yesButton.setFocusable(false);
        yesButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        yesButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        yesButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                yesButtonActionPerformed(evt);
            }
        });
        controlToolbar.add(yesButton);
        controlToolbar.setBorder(BorderFactory.createEtchedBorder());

        noButton.setFont(new java.awt.Font("Dialog", 0, 9));
        noButton.setText("No");
        noButton.setToolTipText("Say No");
        noButton.setBorderPainted(false);
        noButton.setEnabled(false);
        noButton.setFocusable(false);
        noButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        noButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        noButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                noButtonActionPerformed(evt);
            }
        });
        controlToolbar.add(noButton);

        add(controlToolbar);

        generalToolbar.setRollover(true);
        generalToolbar.setPreferredSize(new java.awt.Dimension(66, 25));

        chatButton.setFont(new java.awt.Font("Dialog", 0, 9));
        chatButton.setText("Chat");
        chatButton.setBorderPainted(false);
        chatButton.setContentAreaFilled(false);
        chatButton.setFocusable(false);
        chatButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        chatButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        chatButton.addMouseListener(new java.awt.event.MouseAdapter() {

            @Override
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                chatButtonMouseEntered(evt);
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent evt) {
                chatButtonMouseExited(evt);
            }
        });
        chatButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                chatButtonActionPerformed(evt);
            }
        });
        generalToolbar.add(chatButton);

        voiceOptionsButton.setFont(new java.awt.Font("Dialog", 0, 9));
        voiceOptionsButton.setText("Voice");
        voiceOptionsButton.setBorderPainted(false);
        voiceOptionsButton.setContentAreaFilled(false);
        voiceOptionsButton.setFocusable(false);
        voiceOptionsButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        voiceOptionsButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        voiceOptionsButton.addMouseListener(new java.awt.event.MouseAdapter() {

            @Override
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                voiceOptionsButtonMouseEntered(evt);
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent evt) {
                voiceOptionsButtonMouseExited(evt);
            }
        });
        voiceOptionsButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                voiceOptionsButtonActionPerformed(evt);
            }
        });
        generalToolbar.add(voiceOptionsButton);
        refreshButton.setFont(new java.awt.Font("Dialog", 0, 9));
        refreshButton.setText("Refresh");
        refreshButton.setToolTipText("Reload");
        refreshButton.setBorderPainted(false);
        refreshButton.setContentAreaFilled(false);
        refreshButton.setFocusable(false);
        refreshButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        refreshButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        refreshButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                refreshButtonActionPerformed(evt);
            }
        });
        refreshButton.addMouseListener(
                new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        refreshButtonMouseEntered(evt);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        refreshButtonMouseExited(evt);
                    }
                });

        generalToolbar.add(refreshButton);
        fileTransferButton = new JButton();
        fileTransferButton.setFont(new java.awt.Font("Dialog", 0, 9));
        fileTransferButton.setText("File Transfer");
        fileTransferButton.setToolTipText("File Transfer");
        fileTransferButton.setBorderPainted(false);
        fileTransferButton.setContentAreaFilled(false);
        fileTransferButton.setFocusable(false);
        fileTransferButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        fileTransferButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        fileTransferButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                base.showFileTransferFrame();
            }
        });
        fileTransferButton.addMouseListener(
                new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        fileTransferButton.setContentAreaFilled(true);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        fileTransferButton.setContentAreaFilled(false);
                    }
                });

        generalToolbar.add(fileTransferButton);

        optionsButton.setFont(new java.awt.Font("Dialog", 0, 9));
        optionsButton.setText("Config");
        optionsButton.setToolTipText("Options");
        optionsButton.setBorderPainted(false);
        optionsButton.setContentAreaFilled(false);
        optionsButton.setFocusable(false);
        optionsButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        optionsButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        optionsButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsButtonActionPerformed(evt);
            }
        });
        optionsButton.addMouseListener(
                new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        optionsButtonMouseEntered(evt);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        optionsButtonMouseExited(evt);
                    }
                });
        generalToolbar.add(optionsButton);

        fileTransferButton.setIcon(createImageIcon(this, "/icons/filetransfer.gif"));
        refreshButton.setIcon(createImageIcon(this, "/icons/refresh.png"));
        optionsButton.setIcon(createImageIcon(this, "/icons/options.png"));

        add(generalToolbar);
        surveyButton = new JButton(createImageIcon(this, "/icons/survey.png"));
        surveyButton.setText("Survey");
        surveyButton.setBorderPainted(false);
        surveyButton.setContentAreaFilled(false);
        surveyButton.setFocusable(false);
        surveyButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        surveyButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        surveyButton.setIcon(createImageIcon(this, "/icons/survey.png"));
        surveyButton.setFont(new java.awt.Font("Dialog", 0, 9));
        surveyButton.addMouseListener(
                new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        surveyButton.setContentAreaFilled(true);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        surveyButton.setContentAreaFilled(false);
                    }
                });



        sessionButton = new TButton(createImageIcon(this, "/icons/session_off.png"));
        sessionButton.setText("Start");
        surveyButton.setToolTipText("Conduct Survey");

        chatButton.setIcon(createImageIcon(this, "/icons/chat.png"));
        handButton.setIcon(createImageIcon(this, "/icons/hand.png"));
        yesButton.setIcon(createImageIcon(this, "/icons/yes.png"));
        noButton.setIcon(createImageIcon(this, "/icons/no.png"));
        voiceOptionsButton.setIcon(createImageIcon(this, "/icons/voice.png"));
        yesButton.setEnabled(false);
        noButton.setEnabled(false);
        chatButton.setToolTipText("Chat");
        if (base.isPresenter()) {
            slidesToolBar.add(sessionButton);
            generalToolbar.add(surveyButton);
        }
        generalToolbar.addSeparator();
        generalToolbar.setBorder(BorderFactory.createEtchedBorder());

        wbButton = new javax.swing.JToggleButton();
        wbButton.setFont(new java.awt.Font("Dialog", 0, 9));
        wbButton.setText("Whiteboard");
        wbButton.setBorderPainted(false);
        //wbButton.setContentAreaFilled(false);
        wbButton.setFocusable(false);
        wbButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        wbButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        wbButton.setIcon(createImageIcon(this, "/icons/wb_icon.png"));
        wbButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                base.showPointerToolBar(wbButton.isSelected());
            }
        });

       // generalToolbar.add(wbButton);

        setCustomActions();
    }// </editor-fold>                        

    private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {
        base.showOptionsFrame();
    }

    private void optionsButtonMouseEntered(java.awt.event.MouseEvent evt) {
        optionsButton.setContentAreaFilled(true);
    }

    private void optionsButtonMouseExited(java.awt.event.MouseEvent evt) {
        optionsButton.setContentAreaFilled(false);
    }

    private void refreshButtonMouseEntered(java.awt.event.MouseEvent evt) {
        refreshButton.setContentAreaFilled(true);
    }

    private void refreshButtonMouseExited(java.awt.event.MouseEvent evt) {
        refreshButton.setContentAreaFilled(false);
    }

    private void refreshButtonActionPerformed(java.awt.event.ActionEvent evt) {

        base.refreshConnection();
    }

    public int getVersion() {
        return (int) v.version;
    }

    private void chatButtonActionPerformed(java.awt.event.ActionEvent evt) {
        base.showChatRoom();
    }

    private void handButtonMouseEntered(java.awt.event.MouseEvent evt) {
        handButton.setContentAreaFilled(true);
    }

    private void handButtonMouseExited(java.awt.event.MouseEvent evt) {
        handButton.setContentAreaFilled(false);
    }

    private void handButtonActionPerformed(java.awt.event.ActionEvent evt) {
        User usr = base.getUser();
        base.getTcpClient().sendPacket(new PresencePacket(usr.getSessionId(),
                PresenceConstants.HAND_ICON, handButton.isSelected(),
                usr.getUserName()));
    }

    private void yesButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (noButton.isSelected()) {
            noButton.setSelected(false);
        }
        User usr = base.getUser();
        base.getTcpClient().sendPacket(new PresencePacket(usr.getSessionId(),
                PresenceConstants.YES_ICON, yesButton.isSelected(),
                usr.getUserName()));

    }

    private void noButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (yesButton.isSelected()) {
            yesButton.setSelected(false);
        }
        User usr = base.getUser();
        base.getTcpClient().sendPacket(new PresencePacket(usr.getSessionId(),
                PresenceConstants.NO_ICON, noButton.isSelected(),
                usr.getUserName()));
    }

    private void voiceOptionsButtonActionPerformed(java.awt.event.ActionEvent evt) {
        base.showAudioWizardFrame();
    }

    private void voiceOptionsButtonMouseEntered(java.awt.event.MouseEvent evt) {
        voiceOptionsButton.setContentAreaFilled(true);
    }

    private void voiceOptionsButtonMouseExited(java.awt.event.MouseEvent evt) {
        voiceOptionsButton.setContentAreaFilled(false);
    }

    private void chatButtonMouseEntered(java.awt.event.MouseEvent evt) {
        chatButton.setContentAreaFilled(true);
    }

    private void chatButtonMouseExited(java.awt.event.MouseEvent evt) {
        chatButton.setContentAreaFilled(false);
    }

    /**
     * Our own button behavoir
     */
    class MButton extends JButton {

        public MButton(ImageIcon icon) {
            super(icon);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 9));
            setEnabled(false);
            addMouseListener(new MouseAdapter() {

                @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                }
            });
        }
    }

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

    /**
     * Our own button behavoir
     */
    class TButton extends JToggleButton {

        public TButton(ImageIcon icon) {
            super(icon);
            setContentAreaFilled(false);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 9));
            setEnabled(false);
            this.addMouseListener(new MouseAdapter() {

                @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                }
            });
        }
    }
}
