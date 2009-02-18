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
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.classroom.ClassicToolbar;
import avoir.realtime.common.Constants;
import avoir.realtime.survey.SurveyManagerFrame;
import com.sun.org.apache.bcel.internal.generic.IFEQ;
import java.awt.Color;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JToggleButton;
import javax.swing.border.Border;

/**
 *
 * @author developer
 */
public class ClassicInstructorToolbar extends ClassicToolbar {

    Classroom mf;
    private boolean record = false;
    private int count = 0;
    private boolean bold = false;
    private boolean italic = false;
    private boolean under = false;
    private Timer desktopTimer = new Timer();

    public ClassicInstructorToolbar(Classroom mf) {
        super(mf);
        this.mf = mf;
    }

    @Override
    protected void showWebpage() {
        mf.getMenuManager().insertWebpage();
    }

    class SharingAlerter extends TimerTask {

        JToggleButton b;

        public SharingAlerter(JToggleButton b) {
            this.b = b;
        }
        boolean showText = false;

        public void run() {
           b.setText("Sharing");
            if (showText) {
                b.setForeground(Color.RED);
            } else {
                b.setForeground(Color.LIGHT_GRAY);
            }
            showText = !showText;
        }
    }

    @Override
    protected void startDesktopshare(JToggleButton b) {
        if (b.isSelected()) {
            b.setFont(new Font("dialog", 1, 12));
            b.setForeground(Color.RED);
            desktopTimer = new Timer();
            desktopTimer.scheduleAtFixedRate(new SharingAlerter(b), 0, 1000);
        } else {
            desktopTimer.cancel();
            b.setText("");
        }
        mf.getMenuManager().initAppshare();
    }

    @Override
    public void showQuestionsManager() {
        int offset = 30 * count;
        SurveyManagerFrame fr = new SurveyManagerFrame(mf);
        fr.setSize((ss.width / 8) * 6, (ss.height / 8) * 6);
        fr.setLocation(((ss.width - fr.getWidth()) / 2) + offset, ((ss.height - fr.getHeight()) / 2) + offset);
        fr.setVisible(true);
        count++;
    }

    public void setRecording(boolean recording) {
        this.recording = recording;
        repaint();
    }

    @Override
    public void showRecorderFrame() {
        record = !record;
        if (record) {
            mf.getMenuManager().initRecord();
        } else {
            mf.getMenuManager().stopRecord();
        }
    }

    @Override
    protected void setBold() {
        bold = !bold;
        mf.getWhiteboard().getBoldButton().setSelected(bold);

    }

    @Override
    protected void setItalic() {
        italic = !italic;
        mf.getWhiteboard().getItalicButton().setSelected(italic);
    }

    @Override
    protected void setUnder() {
        under = !under;
        mf.getWhiteboard().getUnderButton().setSelected(under);
    }

    @Override
    public void showFonts() {
    }

    @Override
    protected void showPointerOptions(int xx, int yy) {
        if (firsTimeArrowShow) {
            final ButtonGroup bg = new ButtonGroup();
            for (int i = 0; i < arrows.length; i++) {
                ImageIcon img = (ImageIcon) arrows[i][0];
                final JToggleButton b = new JToggleButton(img);
                b.setActionCommand((String) arrows[i][1]);
                b.addActionListener(new ActionListener() {

                    public void actionPerformed(ActionEvent evt) {
                        if (evt.getActionCommand().equals("arrowSide")) {
                            mf.getWhiteboard().setPointer(Constants.ARROW_SIDE);

                        }
                        if (evt.getActionCommand().equals("arrowUp")) {
                            mf.getWhiteboard().setPointer(Constants.ARROW_UP);
                        }
                        if (evt.getActionCommand().equals("handLeft")) {
                            mf.getWhiteboard().setPointer(Constants.HAND_LEFT);
                        }
                        if (evt.getActionCommand().equals("handRight")) {
                            mf.getWhiteboard().setPointer(Constants.HAND_RIGHT);
                        }
                    }
                });
                bg.add(b);
                toolbarManager.getPopupPanel().add(b);
            }
            toolbarManager.getPointerPopup().add(toolbarManager.getPopupPanel());
            firsTimeArrowShow = false;
        }
        toolbarManager.getPointerPopup().show(this, xx, yy);
    }
}
