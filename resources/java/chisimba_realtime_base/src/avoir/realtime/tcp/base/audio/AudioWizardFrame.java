/*
 * AudioWizardFrame.java
 *
 * Created on 13 May 2008, 09:13
 */
package avoir.realtime.tcp.base.audio;
//import avoir.realtime.tcp.client.applet.TCPClient;
import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.packet.AudioPacket;
import javax.sound.sampled.AudioFormat;
//import avoir.realtime.tcp.common.packet.AudioPacket;
import java.awt.BorderLayout;
import java.awt.Color;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.util.Vector;
import javax.sound.sampled.AudioFileFormat;
import javax.sound.sampled.AudioInputStream;
import javax.sound.sampled.AudioSystem;
import javax.sound.sampled.DataLine;
import javax.sound.sampled.LineUnavailableException;
import javax.sound.sampled.SourceDataLine;

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
    private int sampleSizeInBits = 16;//AudioResource.FRAME_BITS;
    //private TargetDataLine targetLine;
    // private SourceDataLine sourceDataLine;
    // private int bufferSize;
    private String txt =
            "<center><h1><b><font color=\"orange\">Beta Version</font></b></center>" +
            "</h1><br><center><h3>Realtime Audio Wizard.</h3> " +
            "Use <b>'Test Speaker'</b> button to test your speaker. A recorded message will be " +
            "played. During this time, adjust volume to" +
            "to your satisfactory level.<br>" +
            "<br></center><hr><br>";
    private RealtimeBase base;
    private String username,  sessionId;
    private Vector<AudioPacket> testBuffer = new Vector<AudioPacket>();
    private DetectSound detectSound = new DetectSound(sampleSizeInBits);
    private String slideServerId;
    private String resourcesPath;
    boolean encode = false;
    private VoiceSpeakerOutput voiceSpeakerOutput;
    private boolean testing = false;
    private boolean micinit = false;
    private boolean micWorking = false;
    private boolean speakerWorking = false;

    /** Creates new form AudioWizardFrame */
    public AudioWizardFrame(RealtimeBase base, String username, String sessionId,
            String slideServerId, String resoursesPath) {
        this.base = base;
        this.username = username;
        this.sessionId = sessionId;
        this.base.getTcpClient().setAudioHandler(this);
        this.slideServerId = slideServerId;
        this.resourcesPath = resoursesPath;
        initComponents();
        infoField.setText(txt);
        cPanel.add(detectSound, BorderLayout.SOUTH);
        micInput = new VoiceMicrophoneInput(this, callControl);

        if (micInput.obtainHardware()) {
            displayInfo("MIC Ready");
            micWorking = true;
            base.setMicEnabled(true);
        } else {
            displayError("FATAL: Cannot find audio capture device");
            base.setMicEnabled(false);
            micWorking = false;
        }
        initReceiverLine();

    }

    public VoiceMicrophoneInput getMicInput() {
        return micInput;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public void setSlideServerId(String slideServerId) {
        this.slideServerId = slideServerId;
    }

    public void setResourcesPath(String resourcesPath) {
        this.resourcesPath = resourcesPath;
    }

    public DetectSound getSoundDetector() {
        return detectSound;
    }

    public RealtimeBase getBase() {
        return base;
    }

    public void stopAudio() {
        if (voiceSpeakerOutput != null) {
            voiceSpeakerOutput.releaseHardware();
        }
        if (micInput != null) {
            micInput.endMic();
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
        base.getTcpClient().sendPacket(packet);

    }

    private boolean initReceiverLine() {
        voiceSpeakerOutput = new VoiceSpeakerOutput(this);

        if (voiceSpeakerOutput.obtainHardware()) {
            displayInfo("Sound receiver ready");
            base.setSpeakerEnabled(true);
            speakerWorking = true;

            voiceSpeakerOutput.beginSpeaker();
            volumeSlider.setMinimum((int) voiceSpeakerOutput.getMinGainValue());
            volumeSlider.setMaximum((int) voiceSpeakerOutput.getMaxGainValue());
            volumeSlider.setValue((int) voiceSpeakerOutput.getGainValue());
            muteOpt.setSelected(voiceSpeakerOutput.isMute());


            base.getVolumeSlide().setMinimum((int) voiceSpeakerOutput.getMinGainValue());
            base.getVolumeSlide().setMaximum((int) voiceSpeakerOutput.getMaxGainValue());
            base.getVolumeSlide().setValue((int) voiceSpeakerOutput.getGainValue());

            base.getMuteOpt().setSelected(voiceSpeakerOutput.isMute());
            return true;
        } else {
            displayError("FATAL Cannot receive sound.");
            base.setSpeakerEnabled(false);
            speakerWorking = false;
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
        speakerTestButton.setEnabled(false);
        stopButton.setEnabled(true);
        micTestButton.setEnabled(false);
        micTestButton.setText("Capturing...");
        testing = true;
        micTestButton.setForeground(new Color(0, 131, 0));
        micTestButton.setBackground(Color.YELLOW);
        if (!micinit) {
            micInput.beginMic();
            micinit = true;
        } else {
            micInput.resumeMic();
        }
    }

    public void stopCapture() {
        if (testing) {
            micInput.pauseMic();
            micTestButton.setText("Playing...");
            micTestButton.setBackground(Color.ORANGE);
            Thread t = new Thread() {

                public void run() {
                    playTestPackets();
                }
            };
            t.start();
         
            testing = false;
        } else {
            speakerTestButton.setEnabled(true);
            micTestButton.setEnabled(true);
            micTestButton.setSelected(false);
            stopButton.setEnabled(false);
            displayWarn("Streaming stopped.Use Talk button to restart");
            micInput.pauseMic();
          getMicInput().setAudioClipFileName("testing");
           getMicInput().recordAudioClip();
        }
    }

    public void playPacket(final AudioPacket packet) {
        byte[] data = packet.getPacket();
        //if (packet.isTest()) {
        //  testBuffer.add(packet);
        //} else {
        voiceSpeakerOutput.receiveVoicePushTextData(data);
    //}
    }

    private void playTestPackets() {
        displayWarn("Playing test audio...");
        for (int i = 0; i < testBuffer.size(); i++) {
            byte[] data = testBuffer.elementAt(i).getPacket();
            voiceSpeakerOutput.receiveVoicePushTextData(data);
        }
        displayWarn("Test Streaming stopped");
        /*ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream(len);
        len = 0;
        int prev=0;
        for (int i = 0; i < testBuffer.size(); i++) {
        byte[] data = testBuffer.elementAt(i).getPacket();
        prev += data.length;
        byteArrayOutputStream.write(data, prev, data.length);
        }*/
        speakerTestButton.setEnabled(true);
        micTestButton.setEnabled(true);
        micTestButton.setText("Test Microphone");
        micTestButton.setBackground(getBackground());
        stopButton.setEnabled(false);
        // byte audioData[] = byteArrayOutputStream.toByteArray();
        // saveAudio(audioData);
        testBuffer.clear();
        micInput.pauseMic();

    }

    public boolean isTesting() {
        return testing;
    }

    private void saveAudio(byte audioData[]) {
        try {

            InputStream byteArrayInputStream = new ByteArrayInputStream(
                    audioData);
            AudioFormat audioFormat =
                    getAudioFormat();
            AudioInputStream audioInputStream =
                    new AudioInputStream(
                    byteArrayInputStream,
                    audioFormat,
                    audioData.length / audioFormat.getFrameSize());

            Thread saveThread =
                    new Thread(new SaveThread(audioInputStream, Constants.getRealtimeHome() + "sounds.test"));
            saveThread.start();
        } catch (Exception e) {
            System.out.println(e);
            System.exit(0);
        }//end catch
    }//end playAudio

    class SaveThread extends Thread {

        byte tempBuffer[] = new byte[10000];
        AudioInputStream audioInputStream;
        String fileName;

        public SaveThread(AudioInputStream audioInputStream, String fileName) {
            this.audioInputStream = audioInputStream;
            this.fileName = fileName;
        }

        public void run() {
            try {


                if (AudioSystem.isFileTypeSupported(AudioFileFormat.Type.AU,
                        audioInputStream)) {
                    AudioSystem.write(audioInputStream, AudioFileFormat.Type.AU, new File(fileName + ".au"));

                }

            } catch (Exception e) {
                System.out.println(e);
                System.exit(0);
            }//end catch
        }//end run
    }//end inner class PlayThread
//===================================//

    public void talk() {
        speakerTestButton.setEnabled(false);
        micTestButton.setEnabled(false);
        stopButton.setEnabled(true);
        if (!micinit) {
            micInput.beginMic();
            micinit = true;
        } else {
            micInput.resumeMic();
        }
    }

    private void xplayAudioFile() {
        AudioInputStream audioInputStream = null;
        String testFile = avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/sounds/test.wav";
        File file = new File(testFile);
        try {
            audioInputStream = AudioSystem.getAudioInputStream(file);

            //   audioInputStream = AudioSystem.getAudioInputStream(getAudioFormat(), audioInputStream);
            int nBytesRead = 0;
            byte[] abData = new byte[1024];
            while (nBytesRead != -1) {
                try {
                    nBytesRead = audioInputStream.read(abData, 0, abData.length);
                } catch (IOException e) {
                    e.printStackTrace();
                }
                if (nBytesRead >= 0) {

                    voiceSpeakerOutput.getSourceDataLine().write(abData, 0, abData.length);
                }
            }

            speakerTestButton.setEnabled(true);
        } catch (Exception ex) {
            ex.printStackTrace();
            speakerTestButton.setEnabled(true);
        }
    }

    private void testSpeaker() {
        speakerTestButton.setEnabled(false);
        Thread t = new Thread() {

            public void run() {
                playAudioFile();
            }
        };
        t.start();
    }

    public void playAudioFile() {
        //close main speaker system
        voiceSpeakerOutput.releaseHardware();
        int EXTERNAL_BUFFER_SIZE = 128000;
        String strFilename = Constants.getRealtimeHome() + "/sounds/test.wav";
        File soundFile = new File(strFilename);

        /*
        We have to read in the sound file.
         */
        AudioInputStream audioInputStream = null;
        try {
            audioInputStream = AudioSystem.getAudioInputStream(soundFile);
        } catch (Exception e) {
            /*
            In case of an exception, we dump the exception
            including the stack trace to the console output.
            Then, we exit the program.
             */
            e.printStackTrace();

        }


        AudioFormat audioFormat = audioInputStream.getFormat();
        SourceDataLine line = null;
        DataLine.Info info = new DataLine.Info(SourceDataLine.class,
                audioFormat);
        try {
            line = (SourceDataLine) AudioSystem.getLine(info);

            line.open(audioFormat);
        } catch (LineUnavailableException e) {
            e.printStackTrace();
            System.exit(1);
        } catch (Exception e) {
            e.printStackTrace();
            System.exit(1);
        }


        line.start();
        int nBytesRead = 0;
        byte[] abData = new byte[EXTERNAL_BUFFER_SIZE];
        while (nBytesRead != -1) {
            try {
                nBytesRead = audioInputStream.read(abData, 0, abData.length);
            } catch (IOException e) {
                e.printStackTrace();
            }
            if (nBytesRead >= 0) {
                int nBytesWritten = line.write(abData, 0, nBytesRead);
            }
        }


        line.drain();
        line.close();
        //then restart it 
        initReceiverLine();
        speakerTestButton.setEnabled(true);
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
        speakerTestButton = new javax.swing.JButton();
        micTestButton = new javax.swing.JButton();
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

        speakerTestButton.setText("Test Speaker");
        speakerTestButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                speakerTestButtonActionPerformed(evt);
            }
        });
        sPanel.add(speakerTestButton);

        micTestButton.setText("Test Microphone");
        micTestButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                micTestButtonActionPerformed(evt);
            }
        });
        sPanel.add(micTestButton);

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

private void speakerTestButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_speakerTestButtonActionPerformed
    testSpeaker();
}//GEN-LAST:event_speakerTestButtonActionPerformed

private void stopButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_stopButtonActionPerformed
    stopCapture();
}//GEN-LAST:event_stopButtonActionPerformed

private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
    stopCapture();
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

    public void setVolume(float val) {
        voiceSpeakerOutput.adjustGainValue(val);
    }
private void volumeSliderStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volumeSliderStateChanged
//    gainControl.setValue(volumeSlider.getValue());
    setVolume(volumeSlider.getValue());
}//GEN-LAST:event_volumeSliderStateChanged

    public void mute(boolean mute) {
        voiceSpeakerOutput.setMute(mute);
    }

private void muteOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_muteOptActionPerformed
    // muteControl.setValue(muteOpt.isSelected());
    mute(muteOpt.isSelected());
}//GEN-LAST:event_muteOptActionPerformed

private void volume2SliderStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volume2SliderStateChanged
    // volumeControl.setValue(volume2Slider.getValue());
}//GEN-LAST:event_volume2SliderStateChanged

private void micTestButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_micTestButtonActionPerformed

    testAudio();
}//GEN-LAST:event_micTestButtonActionPerformed

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
    private javax.swing.JButton micTestButton;
    private javax.swing.JCheckBox muteOpt;
    private javax.swing.JPanel sPanel;
    private javax.swing.JButton speakerTestButton;
    private javax.swing.JButton stopButton;
    private javax.swing.JPanel vPanel;
    private javax.swing.JSlider volume2Slider;
    private javax.swing.JSlider volumeSlider;
    private javax.swing.JToolBar volumeToolbar;
    // End of variables declaration//GEN-END:variables
}
