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
import java.awt.Color;
import java.util.Vector;
import javax.swing.JOptionPane;

/**
 *
 * @author  developer
 */
public class AudioWizardFrame extends javax.swing.JFrame {

    /** wait lock till the buffer recovers from starvation*/
    private Object messageThreadLock = null;
    /** wait lock to pause the target line from reading*/
    private Object pauseLock = null;
    /** true if message thread is waiting for the buffer to fill up*/
    private boolean messageThreadWaiting = false;
    /** size of audio chucks speex can encode at the current sample rate*/
    private final int rawChunkSize = AudioResource.BLOCK_SIZE; // basically at the sample rate this is 640 bytes

    /** default number of speex encoded chunks the out going buffer will hold*/
    private static final int DEFAULT_BUFFER_SIZE_MULTIPLIER = 50;
    /** default number of speex encoded chunks to place in each message*/
    private static final int DEFAULT_MESSAGE_SIZE_MULTIPLIER = 10;
    /** size of the speex encoded audio. this depends on the quality setting*/
    private int speexChunkSize = AudioResource.QUALITY;
    /** the size in bytes each outgoing message contains*/
    private int speexMessageSize = 0;
    /** size in bytes of the outgoing buffer holding speex encoded bytes*/
    private int speexBufferSize = 0;
    /** target line buffer size*/
    private final int rawBufferSize = rawChunkSize * 10;
    /** size in bytes of buffer read from target Line */
    private final int audioReadChunkSize = rawChunkSize * 5;
    /** consumer thread, dispatches messages tcp client*/
    private Thread messageThread = null;
    private Encoder encoder = null;
    /** outgoing buffer that holds que'd up speex audio blocks */
    private VoiceDataBuffer sendBuffer = null;
    //private FloatControl gainControl;
    //private FloatControl volumeControl;
    //private BooleanControl muteControl;
    private int sampleSizeInBits = AudioResource.FRAME_BITS;
    private TargetDataLine targetLine;
    // private SourceDataLine sourceDataLine;
    // private int bufferSize;
    /** these represetn the logical state of the hardware to the call control*/
    public static final int STATE_ON = 20;
    public static final int STATE_OFF = 10;
    public static final int STATE_PAUSE = 30;
    /** represents the current state of the audio targetline */
    private int micState = STATE_PAUSE;
    private String txt = "<center><h3>Realtime Audio Wizard.</h3> " +
            "Click on the <b>'Test Audio'</b> button." +
            "<br>Then say something. If you hear your own sound, then your audio system" +
            " is working properly</center><hr>";
    private TCPClient tcpclient;
    private String username,  sessionId;
    private Vector<AudioPacket> testBuffer = new Vector<AudioPacket>();
    private DetectSound detectSound = new DetectSound(sampleSizeInBits);
    private String slideServerId;
    private String resourcesPath;
    boolean encode = false;
    protected boolean voiceCaptureSupported = false;
    private VoiceSpeakerOutput voiceSpeakerOutput;

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
        messageThreadLock = new Object();
        pauseLock = new Object();
        infoField.setText(txt);
        cPanel.add(detectSound, BorderLayout.SOUTH);
        Thread t = new Thread() {

            @Override
            public void run() {
              if (!encoderExists()) {
                    stopAudio();
                    String msg = "No suitable encoder found. Audio disabled";
                    JOptionPane.showMessageDialog(null, msg);
                    displayError(msg);
                }
                  initDispatch();
                initReceiverLine();
              
            }
        };
        t.start();
    }

    private void stopAudio() {
        releaseHardware();
        if (voiceSpeakerOutput != null) {
            voiceSpeakerOutput.releaseHardware();
        }
    }

    /**
     *  Restarts the target line and message send thread.
     */
    public void resumeMic() {
        if (getMicState() != STATE_PAUSE) {
            return;
        }
        if (!voiceCaptureSupported) {
            return;
        }
        if (getMicState() == STATE_PAUSE) {

            setMicState(this.STATE_ON);

            synchronized (pauseLock) {

                pauseLock.notify();
            }

            synchronized (messageThreadLock) {

                messageThreadLock.notify();
            }
        }

    }

    public int getMicState() {

        return this.micState;
    }

    private void setMicState(int state) {

        this.micState = state;
    }

    /**
     *   Disallows reading to the target line (mic). sets thread state to
     *   off
     */
    public void endMic() {
        if (getMicState() == STATE_OFF) {
            return;
        }
        if (!voiceCaptureSupported) {
            return;
        }
        setMicState(this.STATE_OFF);
        if (targetLine != null) {
            this.targetLine.stop();
        }
    }

    /**
     *  Halts the reading of data to this targetline. pauses audio line read
     *  thread.
     */
    public void pauseMic() {
        if (!voiceCaptureSupported) {
            return;
        }
        if (getMicState() != STATE_ON) {
            return;
        }
        setMicState(this.STATE_PAUSE);

    /*if(targetLine != null) {
    this.targetLine.stop ();
    }*/
    }

    private void initDispatch() {
        /** This thread dispatches messages to the tcpclient and  
         *  blocks on outgoing buffer starvation
         */
        messageThread = new Thread(new Runnable() {

            public void run() {
                while (true) {
                    if (getMicState() == STATE_OFF) {
                        //we should send any remaining data in buffer first
                        break;
                    }
                    if (sendBuffer.size() >= speexMessageSize) {
                        dispatchVoiceData(sendBuffer.get(speexMessageSize));
                    } else {
                        try {
                            synchronized (messageThreadLock) {
                                messageThreadWaiting = true;
                                messageThreadLock.wait();
                            }
                        } catch (InterruptedException ix) {
                            ix.printStackTrace();
                        }
                    }
                }
            }
        });
    }

    /**
     * Encapsulates audio data and session command into a dialogMessage.
     * Diapatches to remote peer.
     */
    public void dispatchVoiceData(byte[] speexData) {
        AudioPacket packet = new AudioPacket(sessionId, username, speexData);
        packet.setTest(testAudioButton.isSelected());
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

    private void initCaptureLine() {
        if (voiceCaptureSupported) {
            return;
        }
        try {
            AudioFormat format = getAudioFormat();
            try {
                if (targetLine != null) {
                    targetLine.close();
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
            DataLine.Info info = new DataLine.Info(
                    TargetDataLine.class, format);
            targetLine = (TargetDataLine) AudioSystem.getLine(info);
            targetLine.open(format, rawBufferSize);
            displayInfo("MIC ready");
            voiceCaptureSupported = true;
            testAudioButton.setEnabled(false);
        } catch (LineUnavailableException ex) {
            displayError("FATAL Cannot find sound capture devices.");
            talkButton.setEnabled(false);
            voiceCaptureSupported = false;
            setMicState(STATE_OFF);
            ex.printStackTrace();
        }
    }

    private void testAudio() {
       // encode = encoderExists();
        initCaptureLine();
        if (testAudioButton.getText().equals("Test Audio")) {
            talkButton.setEnabled(false);
            testAudioButton.setText("Capturing...Click to stop.");
            testAudioButton.setForeground(new Color(0, 131, 0));
            testAudioButton.setBackground(Color.YELLOW);
            beginMic();
        }
        if (testAudioButton.getText().equals("Capturing...Click to stop.")) {
            testAudioButton.setEnabled(false);
            playTestPackets();
        }
    }

    private void stopCapture() {
        talkButton.setEnabled(true);
        testAudioButton.setEnabled(true);
        testAudioButton.setSelected(false);
        stopButton.setEnabled(false);
        endMic();
        displayWarn("Streaming stopped.Use Talk button to restart");

    }

    public void playPacket(final AudioPacket packet) {
        byte[] data = packet.getPacket();
        if (packet.isTest()) {
            testBuffer.addElement(packet);
            return;
        }
        //if (sourceDataLine != null) {
        //  sourceDataLine.write(data, 0, data.length);
        // }
        voiceSpeakerOutput.receiveVoicePushTextData(data);
    }

    private void playTestPackets() {
        for (int i = 0; i < testBuffer.size(); i++) {
            byte[] data = testBuffer.elementAt(i).getPacket();
            voiceSpeakerOutput.receiveVoicePushTextData(data);
        }
        displayWarn("Test Streaming stopped");
        talkButton.setEnabled(true);
        testAudioButton.setEnabled(true);
        testAudioButton.setText("Test Audio");
        endMic();
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
    initCaptureLine();
    beginMic();
   // encode = encoderExists();
    talkButton.setEnabled(false);
    testAudioButton.setEnabled(false);
    stopButton.setEnabled(true);
    startCapture();
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

    /**
     *  Starts the tagetLine. sets up the buffer and message size.
     */
    public void beginMic() {
        if (!voiceCaptureSupported) {
            return;
        // We don't need a syncrhonization construct here since, we only initiate
        // the microphone once. Threaded access is only possible hereafter
        }
        encoder = new Encoder(AudioResource.QUALITY);
        if (speexMessageSize == 0) {
            speexMessageSize = speexChunkSize * DEFAULT_MESSAGE_SIZE_MULTIPLIER;
        }
        if (speexBufferSize == 0) {

            speexBufferSize = speexChunkSize * DEFAULT_BUFFER_SIZE_MULTIPLIER;
        }
        this.sendBuffer = new VoiceDataBuffer(speexBufferSize);
        if (getMicState() == STATE_PAUSE) {
            setMicState(this.STATE_ON);
            synchronized (pauseLock) {
                pauseLock.notify();
            }
            synchronized (messageThreadLock) {
                messageThreadLock.notify();
            }
        }
        this.targetLine.start();
        startCapture();

    }

    /* 
     *  producer thread. read from target line blocks till buffer size can be 
     *  filled. data is encoded then added to the outgoing buffer.
     */
    public void capture() {
        /** start the message dispatch thread*/
        messageThread.start();
        /** local buff to read raw audio into */
        byte[] buff = new byte[audioReadChunkSize];
        /** bytes read from targetline... if zero after read that mean the line
        has been stopped */
        int bytesRead = 0;
        try {
            while (voiceCaptureSupported) {
                if (getMicState() == this.STATE_PAUSE) {
                    synchronized (pauseLock) {
                        pauseLock.wait();
                    }
                }
                if (getMicState() == this.STATE_OFF) {
                    //we should send any remaining data in buffer first
                    break;
                }
                bytesRead = this.targetLine.read(buff, 0, buff.length);
                if (bytesRead == -1) {
                    //targetLine is closed
                    break;
                }
                if (bytesRead > 0) {
                    encodeAndStore(buff);
                    if (messageThreadWaiting && sendBuffer.size() >= speexMessageSize) {

                        messageThreadWaiting = false;

                        synchronized (messageThreadLock) {

                            messageThreadLock.notify();
                        }
                    }
                } else {
                    break;
                }
            }//while

        } catch (InterruptedException ix) {
            setMicState(this.STATE_OFF);
            ix.printStackTrace();
        }

    }

    /**
     *  encodes the raw pcm audio from target line then stores it in the 
     *  outgoing buffer. at the current sample rate speex encodes 640byte
     *  blocks. we break the buff up into 640byte chunks -> encode -> store
     */
    private void encodeAndStore(byte[] buff) {
        int chunks = buff.length / rawChunkSize;
        int[] startPos = new int[chunks];
        for (int j = 0; j < chunks; j++) {
            startPos[j] = j * rawChunkSize;
        }

        for (int i = 0; i < chunks; i++) {
            byte[] preEncodeBuff = new byte[rawChunkSize];
            System.arraycopy(buff, startPos[i], preEncodeBuff, 0, rawChunkSize);
            byte[] postEncodeBuff = null;
            synchronized (encoder) {
                postEncodeBuff = encoder.encode(preEncodeBuff);
            }
            sendBuffer.append(postEncodeBuff);
        }
    }

    private void startCapture() {
        try {
            Runnable runner = new Runnable() {

                public void run() {
                    displayInfo("Capturing audio ...");
                    capture();
                }
            };
            Thread captureThread = new Thread(runner);
            captureThread.start();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    /**
     *  Releases hardware resources. tagetline, mixers and controls.
     */
    public void releaseHardware() {
        if (!voiceCaptureSupported) {
            return;
        }
        if (targetLine != null) {
            targetLine.flush();

            targetLine.stop();

            targetLine.close();
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
    private javax.swing.JToggleButton testAudioButton;
    private javax.swing.JPanel vPanel;
    private javax.swing.JSlider volume2Slider;
    private javax.swing.JSlider volumeSlider;
    private javax.swing.JToolBar volumeToolbar;
    // End of variables declaration//GEN-END:variables
}
