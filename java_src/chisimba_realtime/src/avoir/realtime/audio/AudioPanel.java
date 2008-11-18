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
package avoir.realtime.audio;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.common.ImageUtil;
import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.GradientPaint;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Rectangle;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.geom.GeneralPath;
import java.awt.geom.RoundRectangle2D;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public class AudioPanel extends JPanel implements MouseListener, MouseMotionListener {

    private Image spkrFailIcon = ImageUtil.createImageIcon(this, "/icons/speaker_fail.png").getImage();
    private Image spkrIcon = ImageUtil.createImageIcon(this, "/icons/speaker.png").getImage();
    private Image micIcon = ImageUtil.createImageIcon(this, "/icons/micro.png").getImage();
    private Image micFailIcon = ImageUtil.createImageIcon(this, "/icons/mic_fail.png").getImage();
    private Image currentMicIcon = micIcon;
    private Image currentSpeakerIcon = spkrIcon;
    private Image micSelectedIcon = ImageUtil.createImageIcon(this, "/icons/micro_selected.png").getImage();
    private Image speakerSelectedIcon = ImageUtil.createImageIcon(this, "/icons/speaker_selected.png").getImage();
    private Image volAdj = ImageUtil.createImageIcon(this, "/icons/volAdj.png").getImage();    //private Image micIcon2 = ImageUtil.createImageIcon(this, "/icons/micro2.png").getImage();
    private Timer timer = new Timer();
    private int slideXValue = getWidth();
    private int count = 0;
    private RoundRectangle2D micAdjOpt = new RoundRectangle2D.Double();
    private RoundRectangle2D spkrAdjOpt = new RoundRectangle2D.Double();
    private int micValue = 25;
    private int spkrValue = 75;
    private RoundRectangle2D currentSelector = null;
    private Timer disposeAudioControlTimer = new Timer();
    private int prevAdjValue = 0;
    private boolean spkrSelected = false;
    int startX = 45;
    private ClassroomMainFrame mf;
    private String sessionId;
    private String username;
    private boolean micButtonSelected = false;
    private boolean micButtonClicked = false;
    private boolean spkrButtonSelected = false;
    private boolean spkrButtonClicked = false;
    private int micSoundLevel = 0;
    private int speakerSoundLevel = 0;
    private MasterModel micModel;
    private MasterModel speakerModel;
    private String status = "";

    public AudioPanel(ClassroomMainFrame mf) {
        this.mf = mf;
        setBackground(Color.WHITE);
        addMouseListener(this);
        addMouseMotionListener(this);
        sessionId = mf.getUser().getSessionId();
        username = mf.getUser().getUserName();
        micModel = new MasterModel("m");
        speakerModel = new MasterModel("s");

    }

    public void repaintMicMeter() {
        int yValue = 55;
        int xValue = 15;
        Rectangle rect = new Rectangle(xValue - 1, yValue - 1, 51 * 4, 13);
        repaint(rect);
    }

    public void repaintSpeakerMeter() {
        int yValue = 15;
        int xValue = 15;
        Rectangle rect = new Rectangle(xValue - 1, yValue - 1, 51 * 4, 13);
        repaint(rect);
    }

    private void startMIC() {
        Thread t = new Thread() {

            public void run() {
                status = "Initialzing audio output...";
                System.out.println("Initializing mic on "+mf.getMediaServerHost()+":"+
                        mf.getAudioMICPort());
                repaint();
                micModel.getAudioManager().connect(mf.getMediaServerHost(),
                        mf.getAudioMICPort());
                micButtonClicked = true;
                status = "";
                startMicLevelMeterThread();
                repaint();
            }
        };
        t.start();
         repaint();
    }

    private void startSPKR() {
        Thread t = new Thread() {

            public void run() {
                status = "Initialzing audio input...";
                System.out.println("Initializing speaker on "+mf.getMediaServerHost()+":"+
                        mf.getAudioSpeakerPort());
                repaint();
                speakerModel.getAudioManager().connect(mf.getMediaServerHost(),
                        mf.getAudioSpeakerPort());
                spkrButtonClicked = true;
                startSpeakerLevelMeterThread();
                status = "";
                repaint();
            }
        };
        t.start();
        repaint();
    }

    private void stopSPKR() {
        speakerModel.getAudioManager().closeSpkr();
        spkrButtonClicked = false;
        repaint();
    }

    private void stopMIC() {
        micModel.getAudioManager().closeMIC();
        micButtonClicked = false;
        repaint();
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);

        Graphics2D g2 = (Graphics2D) g;
        g2.setPaint(new GradientPaint(0, 0, Color.LIGHT_GRAY, getWidth(),
                getHeight(), Color.WHITE, false));

        if (count < 2) {
            slideXValue = getWidth();
        }
        Rectangle r = new Rectangle(0, 0, getWidth(), getHeight());
        g2.fill(r);
        startX = 45;
        int xValue = startX;
        int yValue = 15;
        int green = 255;
        int spacing = 4;
        int maxColor = 51;



        // draw GeneralPath (polyline)
        int x2Points[] = {60, 100, 120, 140, 180, 200};
        int y2Points[] = {10, 50, 30, 60, 10, 80};
        GeneralPath polyline =
                new GeneralPath(GeneralPath.WIND_EVEN_ODD, x2Points.length);

        polyline.moveTo(x2Points[0], y2Points[0]);

        for (int index = 1; index < x2Points.length; index++) {
            polyline.lineTo(x2Points[index], y2Points[index]);
        }
        g2.setColor(new Color(0, 0, 0));
        g2.setStroke(new BasicStroke(0.5f));
        g2.draw(polyline);
        g2.setStroke(new BasicStroke(2));


        if (spkrButtonSelected) {
            g2.setColor(Color.YELLOW);
            g2.drawRoundRect(3, yValue - 12, 36, 36, 5, 5);
        }
        if (spkrButtonClicked) {
            g2.setColor(Color.GRAY);
            g2.fillRoundRect(3, yValue - 12, 36, 36, 8, 8);
            g2.drawImage(speakerSelectedIcon, 7, 7, this);

        } else {
            g2.drawImage(currentSpeakerIcon, 5, 5, this);

        }
        g2.setStroke(new BasicStroke());
        for (int i = 0; i < speakerSoundLevel; i++) {
            g2.setColor(new Color(255, green, 0));
            g2.fillRect(xValue, yValue, 3, 10);
            xValue += 4;
            green -= 5;
            if (green < 1) {
                green = 1;
            }
        }
        xValue = startX;
        g2.setStroke(new BasicStroke(2));
        g2.setColor(new Color(0, 151, 0));
        g2.drawRoundRect(xValue - 1, yValue - 1, spacing * maxColor, 13, 4, 4);
        g2.setColor(Color.BLACK);
        g2.drawString(status, startX + 10, yValue + 30);
        xValue = startX;
        yValue = 55;
        if (micButtonSelected) {
            g2.setColor(Color.GREEN);
            g2.drawRoundRect(3, yValue - 12, 36, 36, 5, 5);
        }
        if (micButtonClicked) {
            g2.setColor(Color.ORANGE);
            g2.fillRoundRect(3, yValue - 12, 36, 36, 8, 8);
            g2.drawImage(micSelectedIcon, 7, yValue - 7, this);
        } else {
            g2.drawImage(currentMicIcon, 5, yValue - 10, this);
        }
        green = 255;
        for (int i = 0; i < micSoundLevel; i++) {
            g2.setColor(new Color(255, green, 0));
            g2.fillRect(xValue, yValue, 3, 10);
            xValue += 4;
            green -= 5;
            if (green < 1) {
                green = 1;
            }
        }
        xValue = startX;

        g2.setColor(new Color(0, 0, 0));
        g2.drawRoundRect(xValue - 1, yValue - 1, spacing * maxColor, 13, 4, 4);
        count++;
        g2.setColor(new Color(0, 0, 0, 200));
        g2.fillRoundRect(slideXValue, 10, 200, 70, 20, 20);
        g2.drawImage(volAdj, slideXValue + 5, 20, 36, 36, this);
        g2.setColor(Color.WHITE);
        g2.fillRoundRect(slideXValue + 45, 25, 150, 8, 3, 3);
        g2.setColor(Color.RED);
        spkrAdjOpt = new RoundRectangle2D.Double(slideXValue + 45 + spkrValue, 20, 5, 20, 2, 4);
        g2.fill(spkrAdjOpt);
        g2.setColor(Color.WHITE);
        g2.fillRoundRect(slideXValue + 45, 45, 150, 8, 3, 3);
        g2.setColor(Color.RED);
        micAdjOpt = new RoundRectangle2D.Double(slideXValue + 45 + micValue, 40, 5, 20, 2, 4);
        g2.fill(micAdjOpt);
    }

    public void mouseClicked(MouseEvent evt) {
        Rectangle micButton = new Rectangle(5, 45, 32, 32);
        if (micButton.contains(evt.getPoint())) {
            if (!micButtonClicked) {
                startMIC();
                micButtonClicked = true;
            } else {
                stopMIC();
                micButtonClicked = false;
            }
        }
        Rectangle spkrButton = new Rectangle(5, 5, 32, 32);
        if (spkrButton.contains(evt.getPoint())) {
            if (!spkrButtonClicked) {
                startSPKR();
                spkrButtonClicked = true;
            } else {
                stopSPKR();
                spkrButtonClicked = false;
            }
        }
        repaint();
    }

    public void mouseEntered(MouseEvent evt) {
    }

    public void mouseExited(MouseEvent evt) {
        timer.cancel();
        disposeAudioControlTimer.cancel();
        disposeAudioControlTimer = new Timer();
        disposeAudioControlTimer.scheduleAtFixedRate(new SlideDisposer(), 1000, 50);
    }

    public void mousePressed(MouseEvent evt) {
        prevAdjValue = evt.getX();
        if (micAdjOpt.contains(evt.getPoint())) {
            currentSelector = micAdjOpt;
            spkrSelected = false;
        }
        if (spkrAdjOpt.contains(evt.getPoint())) {
            currentSelector = spkrAdjOpt;
            spkrSelected = true;
        }
    }

    public void mouseReleased(MouseEvent evt) {
        currentSelector = null;
        repaint();
    }

    public void mouseDragged(MouseEvent evt) {
        if (currentSelector != null) {


            int diff = evt.getX() - prevAdjValue;
            prevAdjValue = evt.getX();

            if (spkrSelected) {
                spkrValue += diff;

                currentSelector = new RoundRectangle2D.Double(spkrValue - slideXValue, 20, 5, 20, 2, 4);
            } else {
                if (micValue <= 146 && micValue >= -1) {
                    micValue += diff;
                }
                currentSelector = new RoundRectangle2D.Double(micValue - slideXValue, 40, 5, 20, 2, 4);
            }
            if (spkrValue > 146) {
                spkrValue = 145;
            }
            if (micValue > 146) {
                micValue = 145;
            }

            if (spkrValue < -1) {
                spkrValue = 0;
            }

            if (micValue < -1) {
                micValue = 0;
            }


            repaint();

        }
    }

    public void mouseMoved(MouseEvent evt) {
        if (evt.getX() > startX && evt.getX() < startX + 150) {
            timer.cancel();
            disposeAudioControlTimer.cancel();
            timer = new Timer();

            timer.scheduleAtFixedRate(new SlideAnimator(), 1000, 50);
        } else {
            timer.cancel();
            disposeAudioControlTimer.cancel();
            disposeAudioControlTimer = new Timer();
            disposeAudioControlTimer.scheduleAtFixedRate(new SlideDisposer(), 1000, 50);
        }
        Rectangle micButton = new Rectangle(5, 45, 32, 32);
        if (micButton.contains(evt.getPoint())) {
            micButtonSelected = true;
        } else {
            micButtonSelected = false;

        }
        Rectangle spkrButton = new Rectangle(5, 5, 32, 32);
        if (spkrButton.contains(evt.getPoint())) {
            spkrButtonSelected = true;
        } else {
            spkrButtonSelected = false;

        }
        repaint();
    }

    private class SlideDisposer extends TimerTask {

        public void run() {


            if (slideXValue < getWidth()) {
                slideXValue += 20;
            } else {
                cancel();
            }
            repaint();

        }
    }

    private class SlideAnimator extends TimerTask {

        public void run() {

            if (slideXValue > 60) {
                slideXValue -= 20;
            } else {
                cancel();
            }
            repaint();

        }
    }

    private void startMicLevelMeterThread() {
        new Thread(new Runnable() {

            public void run() {

                try {
                    if (!micModel.getAudioManager().getAudio(0).isMicStarted()) {
                        currentMicIcon = micFailIcon;
                        status="Cannot transmit sound";
                    } else {
                        currentMicIcon = micIcon;
                    }
                    while (micModel.getAudioManager().isAudioActive()) {
                        AudioBase ab = micModel.getAudioManager().getAudio(0);
                        if (ab != null) {
                            int level = ab.getLevel();
                            if (level >= 0) {

                                micSoundLevel = level;
                            } else {

                                micSoundLevel = 0;
                            }
                            repaintMicMeter();
                        }



                        Thread.sleep(30);
                    }

                } catch (Exception e) {
                    e.printStackTrace();
                }
                micSoundLevel = 0;
                micButtonClicked = false;
                repaintMicMeter();
            }
        }).start();
    }

    private void startSpeakerLevelMeterThread() {
        new Thread(new Runnable() {

            public void run() {

                try {
                    if (!speakerModel.getAudioManager().getAudio(1).isSpeakerStarted()) {
                        currentSpeakerIcon = spkrFailIcon;
                        status="Cannot receive sound";
                    } else {
                        currentSpeakerIcon= spkrIcon;

                    }
                    while (speakerModel.getAudioManager().isAudioActive()) {
                        AudioBase ab = speakerModel.getAudioManager().getAudio(1);
                        if (ab != null) {
                            int level = ab.getLevel();
                            if (level >= 0) {

                                speakerSoundLevel = level;
                            } else {

                                speakerSoundLevel = 0;
                            }
                            repaintSpeakerMeter();
                        }

                        Thread.sleep(30);
                    }

                } catch (Exception e) {
                    e.printStackTrace();
                }
                speakerSoundLevel = 0;
                spkrButtonClicked = false;
                repaintSpeakerMeter();
            }
        }).start();
    }
}
