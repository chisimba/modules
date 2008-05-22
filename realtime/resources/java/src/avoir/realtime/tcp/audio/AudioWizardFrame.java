/*
 * AudioWizardFrame.java
 *
 * Created on 13 May 2008, 09:13
 */
package avoir.realtime.tcp.audio;

import avoir.realtime.tcp.client.applet.TCPClient;
import javax.sound.sampled.AudioFormat;
import avoir.realtime.tcp.common.packet.AudioPacket;
import java.awt.BorderLayout;
import java.awt.Color;
import java.util.Vector;

/**
 *
 * @author  developer
 */
public class AudioWizardFrame extends javax.swing.JFrame {

    private VoiceMicrophoneInput micInput;
    private VoicePushTextCallControl callControl = new VoicePushTextCallControl();
    //private FloatControl gainControl;
    //private FloatControl volumeControl;
    //private BooleanControl muteControl;
    private int sampleSizeInBits = AudioResource.FRAME_BITS;
    //private TargetDataLine targetLine;
    // private SourceDataLine sourceDataLine;
    // private int bufferSize;
    private String txt =
            "<center><h1><b><font color=\"orange\">Beta Version</font></b></center>" +
            "</h1><br><center><h3>Realtime Audio Wizard.</h3> " +
            "Click on the <b>'Test Audio'</b> button." +
            "<br>Then say something. If you hear your own sound, then your audio system" +
            " is working properly</center><hr><br>";
    private TCPClient tcpclient;
    private String username,  sessionId;
    private Vector<AudioPacket> testBuffer = new Vector<AudioPacket>();
    private DetectSound detectSound = new DetectSound(sampleSizeInBits);
    private String slideServerId;
    private String resourcesPath;
    boolean encode = false;
    private VoiceSpeakerOutput voiceSpeakerOutput;
    private boolean testing = false;
    private boolean micinit = false;

    /** Creates new form AudioWizardFrame */
    public AudioWizardFrame(TCPClient tcpclient, String username, String sessionId,
            String slideServerId, String resoursesPath) {
        this.tcpclient = tcpclient;
        this.username = username;
        this.sessionId = sessionId;
        this.tcpclient.setAudioPacketHandler(this);
        this.slideServerId = slideServerId;
        this.resourcesPath = resoursesPath;
        initComponents();
        infoField.setText(txt);
        cPanel.add(detectSound, BorderLayout.SOUTH);
        micInput = new VoiceMicrophoneInput(this, callControl);

        if (micInput.obtainHardware()) {
            displayInfo("MIC Ready");
        } else {
            displayError("FATAL: Cannot find audio capture device");
        }
        initReceiverLine();

    }

    public DetectSound getSoundDetector() {
        return detectSound;
    }

    private void stopAudio() {
        if (voiceSpeakerOutput != null) {
            voiceSpeakerOutput.releaseHardware();
        }
        if (micInput != null) {
            micInput.releaseHardware();
        }
    }

    /**
     * Encapsulates audio data and session command into a dialogMessage.
     * Diapatches to remote peer.
     */
    public void dispatchVoiceData(byte[] speexData) {
        AudioPacket packet = new AudioPacket(sessionId, username, speexData);
        packet.setTest(testing);
        packet.setEncoded(encode);
        tcpclient.sendPacket(packet);

    }

    private boolean initReceiverLine() {
        voiceSpeakerOutput = new VoiceSpeakerOutput(this);

        if (voiceSpeakerOutput.obtainHardware()) {
            displayInfo("Sound receiver ready");
            voiceSpeakerOutput.beginSpeaker();
            volumeSlider.setMinimum((int) voiceSpeakerOutput.getMinGainValue());
            volumeSlider.setMaximum((int) voiceSpeakerOutput.getMaxGainValue());
            volumeSlider.setValue((int) voiceSpeakerOutput.getGainValue());
            muteOpt.setSelected(voiceSpeakerOutput.isMute());
            return true;
        } else {
            displayError("FATAL Cannot receive sound.");
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

    private void displayInfo(String msg) {
        txt += "<font color=\"green\">" + msg + "</font><br>";
        infoField.setText(txt);
    }

    private void displayError(String msg) {
        txt += "<font color=\"red\">" + msg + "</font><br>";
        infoField.setText(txt);
    }

    private void displayWarn(String msg) {
        txt += "<font color=\"orange\">" + msg + "</font><br>";
        infoField.setText(txt);
    }

    private void testAudio() {
        //   initCaptureLine();
        talkButton.setEnabled(false);
        stopButton.setEnabled(true);
        testAudioButton.setEnabled(false);
        testAudioButton.setText("Capturing...");
        testing = true;
        testAudioButton.setForeground(new Color(0, 131, 0));
        testAudioButton.setBackground(Color.YELLOW);
        micInput.beginMic();
        if (!micinit) {
            //    micInput.start();
            micinit = false;
        }
    }

    private void stopCapture() {
        if (testing) {
            micInput.pauseMic();
            testAudioButton.setText("Playing...");
            testAudioButton.setBackground(Color.ORANGE);
            Thread t = new Thread() {

                public void run() {
                    playTestPackets();
                }
            };
            t.start();
        } else {
            talkButton.setEnabled(true);
            testAudioButton.setEnabled(true);
            testAudioButton.setSelected(false);
            stopButton.setEnabled(false);
            displayWarn("Streaming stopped.Use Talk button to restart");
            micInput.endMic();
        }
    }

    public void playPacket(final AudioPacket packet) {
        //System.out.println(".");
        byte[] data = packet.getPacket();
        //if (packet.isTest()) {
        //  testBuffer.addElement(packet);

        // } else {
        voiceSpeakerOutput.receiveVoicePushTextData(data);
    // }
    //if (sourceDataLine != null) {
    //  sourceDataLine.write(data, 0, data.length);
    // }

    }

    private void playTestPackets() {
        displayWarn("Playing test audio...");
        for (int i = 0; i < testBuffer.size(); i++) {
            byte[] data = testBuffer.elementAt(i).getPacket();
            voiceSpeakerOutput.receiveVoicePushTextData(data);
        }
        displayWarn("Test Streaming stopped");

        talkButton.setEnabled(true);
        testAudioButton.setEnabled(true);
        testAudioButton.setText("Test Audio");
        testAudioButton.setBackground(getBackground());
        stopButton.setEnabled(false);
        testBuffer.clear();
        micInput.endMic();
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        volume2Slider = new javax.swing.JSlider();
        jPanel1 = new javax.swing.JPanel();
        closeButton = new javax.swing.JButton();
        advancedButton = new javax.swing.JButton();
        sPanel = new javax.swing.JPanel();
        talkButton = new javax.swing.JButton();
        testAudioButton = new javax.swing.JButton();
        stopButton = new javax.swing.JButton();
        cPanel = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        infoField = new javax.swing.JTextPane();
        vPanel = new javax.swing.JPanel();
        masterToolbar = new javax.swing.JToolBar();
        volumeSlider = new javax.swing.JSlider();
        muteOpt = new javax.swing.JCheckBox();
        volumeToolbar = new javax.swing.JToolBar();

        volume2Slider.setPaintLabels(true);
        volume2Slider.setPaintTicks(true);
        volume2Slider.setBorder(javax.swing.BorderFactory.createTitledBorder("Volume"));
        volume2Slider.setPreferredSize(new java.awt.Dimension(100, 51));
        volume2Slider.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                volume2SliderStateChanged(evt);
            }
        });

        setTitle("Realtime Audio Wizard - Beta");
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

        testAudioButton.setText("Test Audio Capture");
        testAudioButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                testAudioButtonActionPerformed(evt);
            }
        });
        sPanel.add(testAudioButton);

        stopButton.setText("Stop");
        stopButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                stopButtonActionPerformed(evt);
            }
        });
        sPanel.add(stopButton);

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
        vPanel.add(volumeToolbar, java.awt.BorderLayout.PAGE_START);

        cPanel.add(vPanel, java.awt.BorderLayout.PAGE_START);

        getContentPane().add(cPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

private void talkButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_talkButtonActionPerformed
    //initCaptureLine();
    //beginMic();

    // encode = encoderExists();
    talkButton.setEnabled(false);
    testAudioButton.setEnabled(false);
    stopButton.setEnabled(true);
    //startCapture();
    micInput.beginMic();
    if (!micinit) {
        //   micInput.start();
        micinit = false;
    }
}//GEN-LAST:event_talkButtonActionPerformed

private void stopButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_stopButtonActionPerformed
    stopCapture();
}//GEN-LAST:event_stopButtonActionPerformed

private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
    stopAudio();
    setVisible(false);
}//GEN-LAST:event_closeButtonActionPerformed

private void formWindowIconified(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowIconified
    stopAudio();
}//GEN-LAST:event_formWindowIconified

private void formWindowDeiconified(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowDeiconified
}//GEN-LAST:event_formWindowDeiconified

private void formFocusLost(java.awt.event.FocusEvent evt) {//GEN-FIRST:event_formFocusLost
//    stopCapture();
}//GEN-LAST:event_formFocusLost

private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
    stopAudio();
}//GEN-LAST:event_formWindowClosing

private void formWindowLostFocus(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowLostFocus
    //  stopCapture();
}//GEN-LAST:event_formWindowLostFocus

private void formFocusGained(java.awt.event.FocusEvent evt) {//GEN-FIRST:event_formFocusGained
// TODO add your handling code here:
}//GEN-LAST:event_formFocusGained

private void volumeSliderStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volumeSliderStateChanged
//    gainControl.setValue(volumeSlider.getValue());
    voiceSpeakerOutput.adjustGainValue(volumeSlider.getValue());
}//GEN-LAST:event_volumeSliderStateChanged

private void muteOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_muteOptActionPerformed
    // muteControl.setValue(muteOpt.isSelected());
    voiceSpeakerOutput.setMute(muteOpt.isSelected());
}//GEN-LAST:event_muteOptActionPerformed

private void volume2SliderStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volume2SliderStateChanged
    // volumeControl.setValue(volume2Slider.getValue());
}//GEN-LAST:event_volume2SliderStateChanged

private void testAudioButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_testAudioButtonActionPerformed

    testAudio();
}//GEN-LAST:event_testAudioButtonActionPerformed

// For sampled sounds, add sliders to control volume and balance
   /* private void addSampledControls() {
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
    }*/
    /**
     * gets the desired format
     * @return
     */
    private AudioFormat getAudioFormat() {
        return new AudioFormat(AudioFormat.Encoding.PCM_SIGNED,
                AudioResource.SAMPLE_RATE, 16, AudioResource.CHANNELS,
                AudioResource.FRAME_BITS, AudioResource.SAMPLE_RATE, false);

    /* //true,false
    return new AudioFormat(sampleRate,
    sampleSizeInBits,
    channels,
    signed,
    bigEndian);
     */
    }//end getAudioFormat

    private boolean encoderExists() {
        try {
            Class cl = Class.forName("org.xiph.speex.SpeexEncoder");
            cl.newInstance();
            cl = Class.forName("org.xiph.speex.SpeexDecoder");
            cl.newInstance();
            return true;
        } catch (Exception ex) {
            return false;
        }
    }

    private void startCapture() {
        try {
            Runnable runner = new Runnable() {

                public void run() {
                    displayInfo("Capturing audio ...");
//                    capture();
                }
            };
            Thread captureThread = new Thread(runner);
            captureThread.start();
        } catch (Exception e) {
            e.printStackTrace();
        }
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
    private javax.swing.JButton testAudioButton;
    private javax.swing.JPanel vPanel;
    private javax.swing.JSlider volume2Slider;
    private javax.swing.JSlider volumeSlider;
    private javax.swing.JToolBar volumeToolbar;
    // End of variables declaration//GEN-END:variables
}
