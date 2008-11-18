/**
 *  Copyright (C) GNU/GPL AVOIR 2008
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.common.user.User;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.PresenceConstants;
import avoir.realtime.classroom.packets.PresencePacket;
import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.ItemEvent;
import java.awt.event.ItemListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.net.URL;
import java.text.DecimalFormat;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JPanel;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;

/**
 * This managers the toolbar of the application
 * @author David Wafula
 */
public class ToolbarManager {

    private JToolBar slidesNavigationToolBar = new JToolBar();
    // private JToolBar controlToolbar = new JToolBar();
    private JToolBar whiteboardToolbar = new JToolBar(JToolBar.VERTICAL);
    private JToolBar generalToolbar = new JToolBar();
    private MButton firstSlideButton,  nextSlideButton,  backSlideButton,  lastSlideButton,  magViewPlus,  magViewMinus;
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
    private javax.swing.JButton backButton;
    private javax.swing.JToggleButton handButton;
    private Classroom mf;
    private double factor = 0.1;
    JComboBox magsField = new JComboBox();

    public ToolbarManager(Classroom mf) {
        this.mf = mf;
        initComponents();

    }

    public MButton getBackSlideButton() {
        return backSlideButton;
    }

    public MButton getFirstSlideButton() {
        return firstSlideButton;
    }

    public MButton getLastSlideButton() {
        return lastSlideButton;
    }

    public MButton getNextSlideButton() {
        return nextSlideButton;
    }

    public JToolBar getWhiteboardToolbar() {
        return whiteboardToolbar;
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
        mf.getSessionManager().setPrivateVote(privateVote);
    }

    private void setCustomActions() {
        wbButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                //  mf.showPointerToolBar(wbButton.isSelected());
            }
        });

        surveyButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
//                mf.showSurveyManagerFrame();
            }
        });
        nextSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int slideIndex = mf.getSessionManager().getSlideIndex();
                if (slideIndex < mf.getSessionManager().getSlideCount() - 1) {
                    // mf.recordXml(slideIndex);
                    mf.getSessionManager().setSlideIndex(++slideIndex);
                    mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(), slideIndex, mf.getUser().isPresenter(),
                            mf.getUser().getSessionId(), mf.getUser().getUserName(),
                            true, mf.getSelectedFile(), mf.isWebPresent());
                }

            }
        });

        backSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int slideIndex = mf.getSessionManager().getSlideIndex();
                if (slideIndex > 0) {
                    mf.getSessionManager().setSlideIndex(--slideIndex);
                    mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(), slideIndex,
                            mf.getUser().isPresenter(), mf.getUser().getSessionId(), mf.getUser().getUserName(),
                            true, mf.getSelectedFile(), mf.isWebPresent());

                }

            }
        });
        firstSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int slideIndex = 0;
                mf.getSessionManager().setSlideIndex(slideIndex);
                //   mf.setSelectedFile(presentationName);
                mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(), slideIndex,
                        mf.getUser().isPresenter(), mf.getUser().getSessionId(), mf.getUser().getUserName(),
                        true, mf.getSelectedFile(), mf.isWebPresent());


            }
        });
        lastSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {

                int slideIndex = mf.getSessionManager().getSlideCount() - 1;
                mf.getSessionManager().setSlideIndex(slideIndex);
                mf.getConnector().requestNewSlide(mf.getUser().getSiteRoot(), slideIndex, mf.getUser().isPresenter(),
                        mf.getUser().getSessionId(), mf.getUser().getUserName(),
                        true, mf.getSelectedFile(), mf.isWebPresent());

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
        ButtonGroup bg = new ButtonGroup();


        firstSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/first.png"));
        //firstSlideButton.setText("First");
        backSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/back.png"));
        //backSlideButton.setText("Back");
        nextSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/next.png"));
        //nextSlideButton.setText("Next");
        lastSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/last.png"));
        //lastSlideButton.setText("Last");
        magViewPlus = new MButton(ImageUtil.createImageIcon(this, "/icons/viewmag+.png"));
        magViewPlus.setEnabled(false);//!mf.getControl());
        magViewMinus = new MButton(ImageUtil.createImageIcon(this, "/icons/viewmag-.png"));
        magsField.setEnabled(false);//!mf.getControl());
        magViewMinus.setEnabled(false);//!mf.getControl());
        //  slidesNavigationToolBar.setBorder(BorderFactory.createEtchedBorder());
        slidesNavigationToolBar.setRollover(true);
        slidesNavigationToolBar.setPreferredSize(new java.awt.Dimension(18, 25));
        slidesNavigationToolBar.add(firstSlideButton);
        slidesNavigationToolBar.add(backSlideButton);
        slidesNavigationToolBar.add(nextSlideButton);
        slidesNavigationToolBar.add(lastSlideButton);
        //   slidesNavigationToolBar.add(new JSeparator(JSeparator.VERTICAL));
        slidesNavigationToolBar.add(magViewMinus);
        magsField.setPreferredSize(new Dimension(100, 21));
        slidesNavigationToolBar.add(magsField);


        JPanel p = new JPanel();
        p.setLayout(new BorderLayout());
        p.add(magViewPlus, BorderLayout.WEST);
        p.setPreferredSize(new Dimension(18, 25));
        slidesNavigationToolBar.add(p);

        actionsBG = new javax.swing.ButtonGroup();
        handButton = new javax.swing.JToggleButton();
        yesButton = new javax.swing.JToggleButton();
        noButton = new javax.swing.JToggleButton();
        chatButton = new javax.swing.JButton();
        backButton = new javax.swing.JButton();
        voiceOptionsButton = new javax.swing.JButton();
        refreshButton = new javax.swing.JButton();
        optionsButton = new javax.swing.JButton();

        magsField.setEditable(true);
        magsField.addItem("Fit Window");
        magsField.addItem("Fit Width");
        magsField.addItem("Fit Height");

        for (int i = 0; i < 21; i++) {
            magsField.addItem((i * 10) + "%");
        }
        magsField.setSelectedItem(100 + "%");
        magsField.addItemListener(new ItemListener() {

            public void itemStateChanged(ItemEvent arg0) {

                String item = (String) magsField.getSelectedItem();
                int index = magsField.getSelectedIndex();
                if (index == 0) {
                    mf.getWhiteBoardSurface().setMagX(1);
                    mf.getWhiteBoardSurface().setMagY(1);
                    mf.getWhiteBoardSurface().repaint();
                } else if (index == 1) {
                    mf.getWhiteBoardSurface().setMagX(1);
                    mf.getWhiteBoardSurface().repaint();
                } else if (index == 2) {
                    mf.getWhiteBoardSurface().setMagY(1);
                    mf.getWhiteBoardSurface().repaint();
                } else {
                    try {
                        double mag = 1;
                        int perc = item.indexOf("%");
                        if (perc > -1) {
                            mag = Double.parseDouble(item.substring(0, item.indexOf("%")).trim());

                        } else {
                            mag = Double.parseDouble(item.trim());

                        }
                        mf.getWhiteBoardSurface().setMagX(mag / 100);
                        mf.getWhiteBoardSurface().setMagY(mag / 100);
                        mf.getWhiteBoardSurface().repaint();
                    } catch (NumberFormatException ex) {
                        //ignore
                    }
                }

            }
        });
        magViewPlus.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                double xx = mf.getWhiteBoardSurface().getMagX();
                double yy = mf.getWhiteBoardSurface().getMagY();
                mf.getWhiteBoardSurface().setMagX(xx += factor);
                mf.getWhiteBoardSurface().setMagY(yy += factor);
                magsField.setSelectedItem(new DecimalFormat("##.##").format(xx * 100) + "%");
                mf.getWhiteBoardSurface().repaint();
            }
        });

        magViewMinus.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                double xx = mf.getWhiteBoardSurface().getMagX();
                double yy = mf.getWhiteBoardSurface().getMagY();
                magsField.setSelectedItem(new DecimalFormat("##.##").format(xx * 100) + "%");
                mf.getWhiteBoardSurface().setMagX(xx -= factor);
                mf.getWhiteBoardSurface().setMagY(yy -= factor);
                mf.getWhiteBoardSurface().repaint();
            }
        });

        handButton.setFont(new java.awt.Font("Dialog", 0, 9));
        //handButton.setText("Hand");
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


        yesButton.setFont(new java.awt.Font("Dialog", 0, 9));
        //  yesButton.setText("Yes");
        yesButton.setToolTipText("Say Yes");
        yesButton.setBorderPainted(false);
        yesButton.setContentAreaFilled(false);
        yesButton.setEnabled(false);
        yesButton.setFocusable(false);
        yesButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        yesButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        yesButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                yesButtonActionPerformed(evt);
            }
        });
        yesButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                yesButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                yesButton.setContentAreaFilled(false);
            }
        });

        noButton.setFont(new java.awt.Font("Dialog", 0, 9));
        //noButton.setText("No");
        noButton.setToolTipText("Say No");
        noButton.setBorderPainted(false);
        noButton.setContentAreaFilled(false);
        noButton.setEnabled(false);
        noButton.setFocusable(false);
        noButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        noButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        noButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                noButtonActionPerformed(evt);
            }
        });
        noButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                noButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(MouseEvent e) {
                noButton.setContentAreaFilled(false);
            }
        });



        generalToolbar.setRollover(true);
        generalToolbar.setPreferredSize(new java.awt.Dimension(380, 40));


        backButton.setFont(new java.awt.Font("Dialog", 0, 9));
        backButton.setText("Home");
        backButton.setIcon(ImageUtil.createImageIcon(this, "/icons/back.png"));
        backButton.setBorderPainted(false);
        backButton.setContentAreaFilled(false);
        backButton.setFocusable(false);
        backButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        backButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        backButton.addMouseListener(new java.awt.event.MouseAdapter() {

            @Override
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                backButton.setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent evt) {
                backButton.setContentAreaFilled(false);
            }
        });
        backButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                // revertBack();
            }
        });

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
//                mf.showFileTransferFrame();
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

        fileTransferButton.setIcon(ImageUtil.createImageIcon(this, "/icons/filetransfer.gif"));
        refreshButton.setIcon(ImageUtil.createImageIcon(this, "/icons/refresh.png"));
        optionsButton.setIcon(ImageUtil.createImageIcon(this, "/icons/options.png"));


        surveyButton = new JButton(ImageUtil.createImageIcon(this, "/icons/survey.png"));
        surveyButton.setText("Survey");
        surveyButton.setBorderPainted(false);
        surveyButton.setContentAreaFilled(false);
        surveyButton.setFocusable(false);
        surveyButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        surveyButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        surveyButton.setIcon(ImageUtil.createImageIcon(this, "/icons/survey.png"));
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

        surveyButton.setToolTipText("Conduct Survey");


        sessionButton = new TButton(ImageUtil.createImageIcon(this, "/icons/session_off.png"));
        //sessionButton.setText("Start");


        chatButton.setIcon(ImageUtil.createImageIcon(this, "/icons/chat.png"));
        handButton.setIcon(ImageUtil.createImageIcon(this, "/icons/hand.png"));
        yesButton.setIcon(ImageUtil.createImageIcon(this, "/icons/yes.png"));
        noButton.setIcon(ImageUtil.createImageIcon(this, "/icons/no.png"));
        voiceOptionsButton.setIcon(ImageUtil.createImageIcon(this, "/icons/voice.png"));
        yesButton.setEnabled(false);
        noButton.setEnabled(false);
        chatButton.setToolTipText("Home");
        generalToolbar.add(surveyButton);
        generalToolbar.addSeparator();
//        generalToolbar.setBorder(BorderFactory.createEtchedBorder());

        wbButton = new javax.swing.JToggleButton();
        wbButton.setFont(new java.awt.Font("Dialog", 0, 9));
        //wbButton.setText("Whiteboard");
        wbButton.setBorderPainted(false);
        //wbButton.setContentAreaFilled(false);
        wbButton.setFocusable(false);
        wbButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        wbButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        wbButton.setIcon(ImageUtil.createImageIcon(this, "/icons/wb_icon.png"));
        wbButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                //  mf.showPointerToolBar(wbButton.isSelected());
            }
        });

        // generalToolbar.add(wbButton);
        // generalToolbar.add(noButton);
        // generalToolbar.add(yesButton);

        setCustomActions();
    }// </editor-fold>                        

    public JToolBar getGeneralToolbar() {
        return generalToolbar;
    }

    public JToolBar getSlidesNavigationToolBar() {
        return slidesNavigationToolBar;
    }

    private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {
//        mf.showOptionsFrame();
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

    private void refreshButtonActionPerformed(java.awt.event.ActionEvent evt) {//        mf.refreshConnection();
    }

    private void chatButtonActionPerformed(java.awt.event.ActionEvent evt) {
        mf.showChatRoom();
    }

    private void handButtonMouseEntered(java.awt.event.MouseEvent evt) {
        handButton.setContentAreaFilled(true);
    }

    private void handButtonMouseExited(java.awt.event.MouseEvent evt) {
        handButton.setContentAreaFilled(false);
    }

    private void handButtonActionPerformed(java.awt.event.ActionEvent evt) {
        User usr = mf.getUser();
        mf.getConnector().sendPacket(new PresencePacket(usr.getSessionId(),
                PresenceConstants.HAND_ICON, handButton.isSelected(),
                usr.getUserName()));
    }

    private void yesButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (noButton.isSelected()) {
            noButton.setSelected(false);
        }
        User usr = mf.getUser();
        mf.getConnector().sendPacket(new PresencePacket(usr.getSessionId(),
                PresenceConstants.YES_ICON, yesButton.isSelected(),
                usr.getUserName()));

    }

    private void noButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (yesButton.isSelected()) {
            yesButton.setSelected(false);
        }
        User usr = mf.getUser();
        mf.getConnector().sendPacket(new PresencePacket(usr.getSessionId(),
                PresenceConstants.NO_ICON, noButton.isSelected(),
                usr.getUserName()));
    }

    private void voiceOptionsButtonActionPerformed(java.awt.event.ActionEvent evt) {
        //  mf.showAudioWizardFrame();
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
            setContentAreaFilled(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 9));
            // setEnabled(false);
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
            //setEnabled(false);
            this.addMouseListener(new MouseAdapter() {

                @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                    if (isSelected()) {
                        setContentAreaFilled(true);
                    }
                }
            });
        }
    }
}
