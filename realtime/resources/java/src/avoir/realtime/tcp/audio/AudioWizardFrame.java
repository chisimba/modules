/*
 * AudioWizardFrame.java
 *
 * Created on 13 May 2008, 09:13
 */
package avoir.realtime.tcp.audio;

import avoir.realtime.tcp.client.applet.TCPClient;
import javax.sound.sampled.AudioFormat;
import javax.sound.sampled.AudioSystem;
import javax.sound.sampled.DataLine;
import javax.sound.sampled.LineUnavailableException;
import javax.sound.sampled.TargetDataLine;
import avoir.realtime.tcp.common.packet.AudioPacket;
import java.awt.BorderLayout;
import java.io.ByteArrayOutputStream;
import java.util.Vector;
import javax.sound.sampled.AudioInputStream;
import javax.sound.sampled.BooleanControl;
import javax.sound.sampled.FloatControl;
import javax.sound.sampled.SourceDataLine;

/**
 *
 * @author  developer
 */
public class AudioWizardFrame extends javax.swing.JFrame {

    FloatControl gainControl;
    FloatControl volumeControl;
    BooleanControl muteControl;
    public float sampleRate = AudioResource.SAMPLE_RATE;
    public int sampleSizeInBits = AudioResource.FRAME_BITS;
    //8,16
    public int channels = AudioResource.CHANNELS;
    //1,2
    public boolean signed = true;
    //true,false
    public boolean bigEndian = false;
    public TargetDataLine line;
    public SourceDataLine sourceDataLine;
    boolean capturing = false;
    int bufferSize;
    byte buffer[];
    String txt = "<center><h3>Realtime Audio Wizard.</h3> " +
            "Click on the <b>'Test Audio'</b> button." +
            "<br>Then say something. If you hear your own sound, then your audio system" +
            " is working properly</center><hr>";
    TCPClient tcpclient;
    String username, sessionId;
    Vector<AudioPacket> testBuffer = new Vector<AudioPacket>();
    AudioInputStream audioInputStream;
    ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
    DetectSound detectSound = new DetectSound(sampleSizeInBits);

    /** Creates new form AudioWizardFrame */
    public AudioWizardFrame(TCPClient tcpclient, String username, String sessionId) {
        this.tcpclient = tcpclient;
        this.username = username;
        this.sessionId = sessionId;
        this.tcpclient.setAudioPacketHandler(this);
        initComponents();

        infoField.setText(txt);
        cPanel.add(detectSound, BorderLayout.SOUTH);
        Thread t = new Thread() {

            @Override
            public void run() {
                initCaptureLine();
                initReceiverLine();
            }
        };
        t.start();
    }

    public boolean initReceiverLine() {
        try {
            AudioFormat format = getAudioFormat();
            sourceDataLine = (SourceDataLine) AudioSystem.getSourceDataLine(format, AudioSystem.getMixer(null).getMixerInfo());
            sourceDataLine.open(format);
            sourceDataLine.start();
            bufferSize = (int) format.getSampleRate() * format.getFrameSize();
            buffer = new byte[bufferSize];
            txt += "<html> <font color=\"green\">Sound receiver ready.</font><br>";
            infoField.setText(txt);
            addSampledControls();
            return true;
        } catch (LineUnavailableException e) {
            txt += "<html> <font color=\"red\">FATAL Cannot receive sound.</font><br>";
            infoField.setText(txt);

            e.printStackTrace();
            return false;

        }

    }

    /**
     * Get the time duration of a single audio data packet.
     * @param packetSize length of audio packet data buffer in bytes.
     * @return the length of the packet in nanoseconds given the sampling rate.
     */
    public long getAudioLength(int packetSize) {
        int bytesPerSample = getAudioFormat().getSampleSizeInBits() / 8;
        double SamplesPerSec = getAudioFormat().getSampleRate();
        long time = (long) (1000000000.0 * packetSize / (bytesPerSample * SamplesPerSec));
        return time;
    }

    private void initCaptureLine() {
        try {
            AudioFormat format = getAudioFormat();
            try {
                if (line != null) {
                    line.close();
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
            DataLine.Info info = new DataLine.Info(
                    TargetDataLine.class, format);
            line = (TargetDataLine) AudioSystem.getLine(info);
            line.open(format);
            line.start();
            txt += "<html> <font color=\"green\">MIC ready</font><br>";
            infoField.setText(txt);

        } catch (LineUnavailableException ex) {
            txt += "<html> <font color=\"red\">FATAL Cannot find sound capture devices.</font><br>";
            infoField.setText(txt);
            ex.printStackTrace();
        }
    }

    private void testAudio() {
        if (testAudioButton.isSelected()) {
            talkButton.setEnabled(false);
            testAudioButton.setText("Capturing...Click to stop.");
            captureAudio();
        } else {
            talkButton.setEnabled(true);
            capturing = false;
            txt += "<html> <font color=\"red\">Streaming stopped.</font><br>";
            infoField.setText(txt);
            testAudioButton.setText("Test Audio");
        }
    }

    private void stopCapture() {
        talkButton.setEnabled(true);
        testAudioButton.setEnabled(true);
        testAudioButton.setSelected(false);
        stopButton.setEnabled(false);
        capturing = false;
        txt += "<html><b> <font color=\"red\">Streaming stopped.Use Talk button to restart.</font><b><br>";
        infoField.setText(txt);
    }

    public void playPacket(final AudioPacket packet) {
        byte[] data = packet.getPacket();
        if (sourceDataLine != null) {
            sourceDataLine.write(data, 0, data.length);
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

        jPanel1 = new javax.swing.JPanel();
        closeButton = new javax.swing.JButton();
        advancedButton = new javax.swing.JButton();
        sPanel = new javax.swing.JPanel();
        talkButton = new javax.swing.JButton();
        stopButton = new javax.swing.JButton();
        testAudioButton = new javax.swing.JToggleButton();
        cPanel = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        infoField = new javax.swing.JTextPane();
        vPanel = new javax.swing.JPanel();
        masterToolbar = new javax.swing.JToolBar();
        volumeSlider = new javax.swing.JSlider();
        muteOpt = new javax.swing.JCheckBox();
        volumeToolbar = new javax.swing.JToolBar();
        volume2Slider = new javax.swing.JSlider();

        setTitle("Realtime Audio Wizard");
        addWindowFocusListener(new java.awt.event.WindowFocusListener() {
            public void windowGainedFocus(java.awt.event.WindowEvent evt) {
            }
            public void windowLostFocus(java.awt.event.WindowEvent evt) {
                formWindowLostFocus(evt);
            }
        });
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
            public void windowDeiconified(java.awt.event.WindowEvent evt) {
                formWindowDeiconified(evt);
            }
            public void windowIconified(java.awt.event.WindowEvent evt) {
                formWindowIconified(evt);
            }
        });
        addFocusListener(new java.awt.event.FocusAdapter() {
            public void focusGained(java.awt.event.FocusEvent evt) {
                formFocusGained(evt);
            }
            public void focusLost(java.awt.event.FocusEvent evt) {
                formFocusLost(evt);
            }
        });

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        jPanel1.add(closeButton);

        advancedButton.setText("Advanced");
        jPanel1.add(advancedButton);

        getContentPane().add(jPanel1, java.awt.BorderLayout.PAGE_END);

        talkButton.setText("Talk");
        talkButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                talkButtonActionPerformed(evt);
            }
        });
        sPanel.add(talkButton);

        stopButton.setText("Stop");
        stopButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                stopButtonActionPerformed(evt);
            }
        });
        sPanel.add(stopButton);

        testAudioButton.setText("Test Audio");
        testAudioButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                testAudioButtonActionPerformed(evt);
            }
        });
        sPanel.add(testAudioButton);

        getContentPane().add(sPanel, java.awt.BorderLayout.PAGE_START);

        cPanel.setLayout(new java.awt.BorderLayout());

        infoField.setContentType("text/html");
        infoField.setEditable(false);
        jScrollPane1.setViewportView(infoField);

        cPanel.add(jScrollPane1, java.awt.BorderLayout.CENTER);

        vPanel.setLayout(new java.awt.BorderLayout());

        masterToolbar.setRollover(true);

        volumeSlider.setPaintLabels(true);
        volumeSlider.setPaintTicks(true);
        volumeSlider.setBorder(javax.swing.BorderFactory.createTitledBorder("Master Volume Control"));
        volumeSlider.setPreferredSize(new java.awt.Dimension(300, 51));
        volumeSlider.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                volumeSliderStateChanged(evt);
            }
        });
        masterToolbar.add(volumeSlider);

        vPanel.add(masterToolbar, java.awt.BorderLayout.LINE_START);

        muteOpt.setText("Mute");
        muteOpt.setFocusable(false);
        muteOpt.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        muteOpt.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        muteOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                muteOptActionPerformed(evt);
            }
        });
        vPanel.add(muteOpt, java.awt.BorderLayout.CENTER);

        volumeToolbar.setRollover(true);

        volume2Slider.setPaintLabels(true);
        volume2Slider.setPaintTicks(true);
        volume2Slider.setBorder(javax.swing.BorderFactory.createTitledBorder("Volume"));
        volume2Slider.setPreferredSize(new java.awt.Dimension(100, 51));
        volume2Slider.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                volume2SliderStateChanged(evt);
            }
        });
        volumeToolbar.add(volume2Slider);

        vPanel.add(volumeToolbar, java.awt.BorderLayout.PAGE_START);

        cPanel.add(vPanel, java.awt.BorderLayout.PAGE_START);

        getContentPane().add(cPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

private void testAudioButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_testAudioButtonActionPerformed
    testAudio();
}//GEN-LAST:event_testAudioButtonActionPerformed

private void talkButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_talkButtonActionPerformed
    talkButton.setEnabled(false);
    testAudioButton.setEnabled(false);
    stopButton.setEnabled(true);
    captureAudio();
}//GEN-LAST:event_talkButtonActionPerformed

private void stopButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_stopButtonActionPerformed
    stopCapture();
}//GEN-LAST:event_stopButtonActionPerformed

private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
    setVisible(false);
}//GEN-LAST:event_closeButtonActionPerformed

private void formWindowIconified(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowIconified
    stopCapture();
}//GEN-LAST:event_formWindowIconified

private void formWindowDeiconified(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowDeiconified
}//GEN-LAST:event_formWindowDeiconified

private void formFocusLost(java.awt.event.FocusEvent evt) {//GEN-FIRST:event_formFocusLost
//    stopCapture();
}//GEN-LAST:event_formFocusLost

private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
    stopCapture();
}//GEN-LAST:event_formWindowClosing

private void formWindowLostFocus(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowLostFocus
    //  stopCapture();
}//GEN-LAST:event_formWindowLostFocus

private void formFocusGained(java.awt.event.FocusEvent evt) {//GEN-FIRST:event_formFocusGained
// TODO add your handling code here:
}//GEN-LAST:event_formFocusGained

private void volumeSliderStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volumeSliderStateChanged
    gainControl.setValue(volumeSlider.getValue());
}//GEN-LAST:event_volumeSliderStateChanged

private void muteOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_muteOptActionPerformed
    muteControl.setValue(muteOpt.isSelected());
}//GEN-LAST:event_muteOptActionPerformed

private void volume2SliderStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volume2SliderStateChanged
    volumeControl.setValue(volume2Slider.getValue());
}//GEN-LAST:event_volume2SliderStateChanged

// For sampled sounds, add sliders to control volume and balance
    private void addSampledControls() {
        try {
            gainControl =
                    (FloatControl) sourceDataLine.getControl(FloatControl.Type.MASTER_GAIN);
            muteControl = (BooleanControl) sourceDataLine.getControl(BooleanControl.Type.MUTE);
            muteOpt.setSelected(muteControl.getValue());

            if (gainControl != null) {
                volumeSlider.setMinimum((int) gainControl.getMinimum());
                volumeSlider.setMaximum((int) gainControl.getMaximum());
                volumeSlider.setValue((int) gainControl.getValue());
            }
        } catch (IllegalArgumentException e) {
            // If MASTER_GAIN volume control is unsupported, just skip it
        }

        try {
            volumeControl =
                    (FloatControl) sourceDataLine.getControl(FloatControl.Type.VOLUME);
            if (volumeControl != null) {
                volume2Slider.setMinimum((int) volumeControl.getMinimum());
                volume2Slider.setMaximum((int) volumeControl.getMaximum());
                volume2Slider.setValue((int) volumeControl.getValue());
            }
        } catch (IllegalArgumentException e) {
        }
    }

    /**
     * gets the desired format
     * @return
     */
    private AudioFormat getAudioFormat() {

        //true,false
        return new AudioFormat(sampleRate,
                sampleSizeInBits,
                channels,
                signed,
                bigEndian);
    }//end getAudioFormat

    private void captureAudio() {
        try {
            Runnable runner = new Runnable() {

                public void run() {

                    capturing = true;
                    txt += "<html> <font color=\"green\">Streaming ...</font><br>";
                    infoField.setText(txt);
                    try {
                        while (capturing) {
                            byte buffer[] = new byte[1024];
                            int count =
                                    line.read(buffer, 0, buffer.length);
                            if (count > 0) {
                                if (detectSound.isThereSound(buffer)) {
                                    AudioPacket packet = new AudioPacket(sessionId, username, buffer);
                                    packet.setTest(testAudioButton.isSelected());
                                    tcpclient.sendPacket(packet);
                                }
                            }

                        }


                    } catch (Exception e) {
                        txt += "<html> <font color=\"red\">FATAL Cannot transmit sound.</font><br>";
                        infoField.setText(txt);

                        e.printStackTrace();
                    }
                }
            };
            Thread captureThread = new Thread(runner);
            captureThread.start();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void stop() {
        sourceDataLine.drain();
        sourceDataLine.close();
        line.close();
    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton advancedButton;
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton closeButton;
    private javax.swing.JTextPane infoField;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JToolBar masterToolbar;
    private javax.swing.JCheckBox muteOpt;
    private javax.swing.JPanel sPanel;
    private javax.swing.JButton stopButton;
    private javax.swing.JButton talkButton;
    private javax.swing.JToggleButton testAudioButton;
    private javax.swing.JPanel vPanel;
    private javax.swing.JSlider volume2Slider;
    private javax.swing.JSlider volumeSlider;
    private javax.swing.JToolBar volumeToolbar;
    // End of variables declaration//GEN-END:variables
}
