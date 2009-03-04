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

import avoir.realtime.common.packet.PresencePacket;
import avoir.realtime.common.Constants;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.PresenceConstants;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JToggleButton;

/**
 *
 * @author developer
 */
public class UserInteractionManager implements ActionListener {

    private TButton stepOutButton = new TButton(new ImageIcon(Constants.getRealtimeHome() + "/icons/stepout.jpeg"));//new TButton(ImageUtil.createImageIcon(this, "/icons/stepout.jpeg"));
    private TButton handButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand.png"));
    private TButton laughterButton = new TButton(ImageUtil.createImageIcon(this, "/icons/laugh.jpeg"));
    private TButton applaudButton = new TButton(ImageUtil.createImageIcon(this, "/icons/applaud.jpeg"));
    private TButton micButton = new TButton(ImageUtil.createImageIcon(this, "/icons/micro.png"));
    private TButton spkrButton = new TButton(ImageUtil.createImageIcon(this, "/icons/speaker.png"));
    private ClassroomMainFrame mf;
    private String userName;
    private String sessionId;
    private ButtonGroup bg = new ButtonGroup();

    public UserInteractionManager(ClassroomMainFrame mf) {
        this.mf = mf;
        userName = mf.getUser().getUserName();
        sessionId = mf.getUser().getSessionId();
        stepOutButton.addActionListener(this);
        stepOutButton.setActionCommand("stepout");
        handButton.addActionListener(this);
        handButton.setActionCommand("hand");
        laughterButton.addActionListener(this);
        laughterButton.setActionCommand("laughter");
        applaudButton.addActionListener(this);
        applaudButton.setActionCommand("applaud");
        micButton.setActionCommand("mic");
        micButton.addActionListener(this);
        spkrButton.setActionCommand("spkr");
        spkrButton.addActionListener(this);
        //bg.add(stepOutButton);
        bg.add(handButton);
        bg.add(laughterButton);
        bg.add(applaudButton);
    }

    public TButton getApplaudButton() {
        return applaudButton;
    }

    public TButton getHandButton() {
        return handButton;
    }

    public TButton getLaughterButton() {
        return laughterButton;
    }

    public TButton getStepOutButton() {
        return stepOutButton;
    }

    public TButton getSpkrButton() {
        return spkrButton;
    }

    public String getUserName() {
        return userName;
    }

    public TButton getMicButton() {
        return micButton;
    }

    /**
     * React to action events
     * @param e
     */
    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("mic")) {
            JToggleButton b = (JToggleButton) e.getSource();
            if (b.isSelected()) {
            } else {
            }
        }
        if (e.getActionCommand().equals("spkr")) {
            JToggleButton b = (JToggleButton) e.getSource();
            if (b.isSelected()) {
            } else {
            }
        }
        if (e.getActionCommand().equals("stepout")) {
            if (stepOutButton.isSelected()) {

                // stepOutButton.setText("Resume");
                mf.getTcpConnector().sendPacket(new PresencePacket(sessionId,
                        PresenceConstants.STEP_OUT_ICON, true,
                        userName));
                laughterButton.setSelected(false);
                applaudButton.setSelected(false);
                handButton.setSelected(false);
            } else {
                //   stepOutButton.setText("Step Out");
                mf.getTcpConnector().sendPacket(new PresencePacket(sessionId,
                        PresenceConstants.STEP_OUT_ICON, false,
                        userName));
            }
        }

        if (e.getActionCommand().equals("laughter")) {
            mf.getTcpConnector().sendPacket(new PresencePacket(sessionId,
                    PresenceConstants.LAUGHTER_ICON, laughterButton.isSelected(),
                    userName));
            if (laughterButton.isSelected()) {
                stepOutButton.setSelected(false);
                applaudButton.setSelected(false);
                handButton.setSelected(false);
            }
        }
        if (e.getActionCommand().equals("applaud")) {
            mf.getTcpConnector().sendPacket(new PresencePacket(sessionId,
                    PresenceConstants.APPLAUD_ICON, applaudButton.isSelected(),
                    userName));
            if (applaudButton.isSelected()) {
                stepOutButton.setSelected(false);
                laughterButton.setSelected(false);
                handButton.setSelected(false);
            }
        }
        if (e.getActionCommand().equals("hand")) {
            mf.getTcpConnector().sendPacket(new PresencePacket(sessionId,
                    PresenceConstants.HAND_ICON, handButton.isSelected(),
                    userName));
            if (handButton.isSelected()) {
                stepOutButton.setSelected(false);
                laughterButton.setSelected(false);
                applaudButton.setSelected(false);
            }
        }

    }

    class TButton extends JToggleButton {

        public TButton(ImageIcon icon) {
            super(icon);

            setBorderPainted(false);
            setContentAreaFilled(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 8));
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
