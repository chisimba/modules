/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.audio;

import java.io.*;

import javax.sound.sampled.*;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * @author Ravi
 * @author jamoore
 * @author francois daniels
 * @modified 2005-03-05 jamoore add producer/consumer threads, line state
 * @modified 2005-03-05 jamoore audio encoding is now dynamic based on quality
 * @modified 2005-03-10 jamoore rework encoding to fit dynamic block sizes
 * @modified 2005-03-12 jamoore add statistical accessors
 * @modified 2005-03-18 jamoore refactor
 * @modified 2005-03-18 jamoore add in/out buffer size/capacity accessor/stats
 * @modified 2008       francois added voicemail recording feature, 
 * @modified 2008       francois fixed pause - resume functionality
 * @modified 2008       francois added remote quality control
 * 
 */
public final class VoiceMicrophoneInput extends Thread implements AudioResource {

    static final Logger LOG = Logger.getLogger(VoiceMicrophoneInput.class.getName());
    /** default number of speex encoded chunks to place in each message*/
    private static final int DEFAULT_MESSAGE_SIZE_MULTIPLIER = 10;
    /** default number of speex encoded chunks the out going buffer will hold*/
    private static final int DEFAULT_BUFFER_SIZE_MULTIPLIER = 50;
    /** these represetn the logical state of the hardware to the call control*/
    public static final int STATE_ON = 20;
    public static final int STATE_OFF = 10;
    public static final int STATE_PAUSE = 30;
    /** represents the current state of the audio targetline */
    private int micState = STATE_PAUSE;
    /** type of audio we want from the target line */
    private final AudioFileFormat.Type targetType;
    /** data line to microphone*/
    private TargetDataLine targetLine = null;
    /** wrapper to the speex encoder */
    private Encoder encoder = null;
    /** call control object - performs protocol navigation */
    private VoicePushTextCallControl callControl = null;
    /** outgoing buffer that holds que'd up speex audio blocks */
    private VoiceDataBuffer sendBuffer = null;
    /** wait lock to pause the target line from reading*/
    private Object pauseLock = null;
    /** wait lock till the buffer recovers from starvation*/
    private Object messageThreadLock = null;
    // debugging
    boolean firstSentMessage = true;
    byte[] firstSentBytes = null;
    /** statistic*/
    private long sentMessages = 0;
    /** statistic*/
    private long sentBytes = 0;
    /** true if message thread is waiting for the buffer to fill up*/
    private boolean messageThreadWaiting = false;
    /** size of audio chucks speex can encode at the current sample rate*/
    private final int rawChunkSize = BLOCK_SIZE; // basically at the sample rate this is 640 bytes
    /** size of the speex encoded audio. this depends on the quality setting*/
    private int speexChunkSize = 0;
    ;
    /** the size in bytes each outgoing message contains*/
    private int speexMessageSize = 0;
    /** size in bytes of the outgoing buffer holding speex encoded bytes*/
    private int speexBufferSize = 0;
    /** target line buffer size*/
    private final int rawBufferSize = rawChunkSize * 10;
    /** size in bytes of buffer read from target Line */
    private final int audioReadChunkSize = rawChunkSize * 5;
    /** default mixer on target line */
    private Mixer micMixer = null;
    /** default gain control on target line*/
    private FloatControl gainControl = null;
    /** statistic */
    private int averageEncodeTime = 0;
    /** speex quality level */
    private int encodeQuality = 0;
    /** consumer thread, dispatches messages to dialog*/
    private Thread messageThread = null;
    private boolean writingFile = false;
    public static final AudioFileFormat.Type tmpFileFormatType = AudioFileFormat.Type.WAVE;
    public static final String tmpFileExtention = "." + tmpFileFormatType.getExtension();
    public static final String fileExtention = "." + "ogg";
    private File voicemail = null;
    private static final String base = "/sounds/";
    private static final File voicemessagedir = new File(".");//base + File.separator + Env.VOICE_MESSAGE_UNSENT_DIR);
    String filename = null;
    String pipeID = null;
    protected boolean voiceCaptureSupported = false;
    private static final int DIALOG_DISCONNECTED = 25;
    private int failedDispatches = 0;
    private AudioWizardFrame wizard;

    /**
     * 
     */
    public VoiceMicrophoneInput(AudioWizardFrame wizard, VoicePushTextCallControl callControl) {

        LOG.setLevel(Level.INFO);
        //LOG.setLevel (Level.SEVERE);
        pauseLock = new Object();
        this.wizard = wizard;
        messageThreadLock = new Object();

        this.callControl = callControl;


        targetType = AudioFileFormat.Type.WAVE;

        /** This thread dispatches messages to the vojxta dialog and  
         *  blocks on outgoing buffer starvation */
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

    }//constructor

    /**
     *  Retain the mic line from the audiosubsystem. Try for a gain control too.
     *  For some raeson target lines do not have available controls. we might have
     * to add some amplitude to the line oourselve.
     */
    public boolean obtainHardware() {

        //AudioFormat  audioFormat = new AudioFormat (AudioFormat.Encoding.PCM_SIGNED, 16000.0F, 16, 1, 2, 16000.0F, false);
        // AudioFormat audioFormat = new AudioFormat(AudioFormat.Encoding.PCM_SIGNED, AudioResource.SAMPLE_RATE, 16, AudioResource.CHANNELS, AudioResource.FRAME_BITS, AudioResource.SAMPLE_RATE, false);
        AudioFormat audioFormat = AudioResource.audioFormat;
        voiceCaptureSupported = true;

        DataLine.Info info = new DataLine.Info(TargetDataLine.class, audioFormat);

        try {

            targetLine = (TargetDataLine) AudioSystem.getLine(info);

            targetLine.open(audioFormat, rawBufferSize);

        } catch (LineUnavailableException e) {
            voiceCaptureSupported = false;
            return false;
        } catch (IllegalArgumentException e) {
            voiceCaptureSupported = false;
            return false;
        }

        if (targetLine.isControlSupported(FloatControl.Type.MASTER_GAIN)) {
            gainControl = (FloatControl) targetLine.getControl(FloatControl.Type.MASTER_GAIN);
        }

        /*
        try {
        pipeID = getCallControl().getRemotePipeAdvertisement().getPipeID().toString();
        // Don't really care rigt now. Going to change it to set this sort of thing in the CallControl Anyway!
        } catch (Exception e) {}
         */

        if (!voiceCaptureSupported) {
            LOG.info("Could not obtain microphone");
            return false;
        } else {
            LOG.info("Obtained microphone!");
        }

        return true;
    }

    /**
     * Return block size of encoded audio bytes
     */
    public int getEncodedBlockSize() {

        return speexChunkSize;
    }

    /**
     *  Return the size in byte of voice data in a message
     */
    public int getEncodedMessageSize() {

        return speexMessageSize;

    }

    /**
     *  Set the size in bytes of voice data in a message
     */
    public void setEncodedMessageSize(int encodedMessageSize) {

        this.speexMessageSize = encodedMessageSize;
    }

    /**
     *  Returns the size in bytes of the buffer holding outgoing encoded voice
     *  bytes
     */
    public int getEncodedBufferSize() {

        return speexBufferSize;
    }

    /**
     *  Sets the size in bytes of the buffer that holds encoded voice bytes. 
     *  The current buffer impl does not allow dynamic resizing
     */
    public void setEncodedBufferSize(int encodedBufferSize) {

        this.speexBufferSize = encodedBufferSize;

    }

    /* 
     *  producer thread. read from target line blocks till buffer size can be 
     *  filled. data is encoded then added to the outgoing buffer.
     */
    public void run() {

        /** start the message dispatch thread*/
        messageThread.start();

        /** local buff to read raw audio into */
        byte[] buff = new byte[audioReadChunkSize];
        /** bytes read from targetline... if zero after read that mean the line
        has been stopped */
        int bytesRead = 0;

        try {

            //while ((bytesRead = this.targetLine.read(buff, 0, buff.length)) != -1) {
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

                //Also if we are writing to disk we don't read from here
                if (writingFile) {
                    sleep(1000);
                    continue;
                }

                bytesRead = this.targetLine.read(buff, 0, buff.length);

                if (bytesRead == -1) {
                    //targetLine is closed
                    break;
                }

                if (bytesRead > 0) {

                    encodeAndStore(buff, bytesRead);

                    if (messageThreadWaiting && sendBuffer.size() >= speexMessageSize) {

                        messageThreadWaiting = false;

                        synchronized (messageThreadLock) {

                            messageThreadLock.notify();
                        }
                    }
                } else {
                    /** if bytes read is zero something is wrong.. break */
                    /*if (Logging.SHOW_INFO && LOG.isLoggable(Level.INFO)) {
                    
                    this.printMicState();
                    
                    if (!targetLine.isOpen()) {
                    LOG.info("targetline != open");
                    }
                    
                    LOG.info("ZERO Bytes READ");
                    
                    break;
                    }*/
                }
            }//while

        } catch (InterruptedException ix) {

            setMicState(this.STATE_OFF);

//            if (Logging.SHOW_INFO && LOG.isLoggable(Level.INFO)) {
            LOG.info("run : interuptedexception");
            //      }

            ix.printStackTrace();

        }

    }//run

    public boolean isPaused() {
        return getMicState() == STATE_PAUSE;
    }

    /**
     *  encodes the raw pcm audio from target line then stores it in the 
     *  outgoing buffer. at the current sample rate speex encodes 640byte
     *  blocks. we break the buff up into 640byte chunks -> encode -> store
     */
    private void encodeAndStore(byte[] buff, int bytesRead) {
        wizard.getBase().getMicVolumeMeter().setValue(wizard.getSoundDetector().calcCurrVol(buff, 0, bytesRead));
        int chunks = buff.length / rawChunkSize;

        int[] startPos = new int[chunks];

        for (int j = 0; j < chunks; j++) {

            startPos[j] = j * rawChunkSize;
        }
        /*
        for (int i = 0; i < chunks; i++) {
        
        byte[] preEncodeBuff = new byte[rawChunkSize];
        
        System.arraycopy(buff, startPos[i], preEncodeBuff, 0, rawChunkSize);
        
        long in = System.currentTimeMillis();
        
        byte[] postEncodeBuff = null;
        synchronized (encoder) {
        postEncodeBuff = encoder.encode(preEncodeBuff);
        }
        
        long out = System.currentTimeMillis();
        
        int roundTrip = (int) (out - in);
        
        if (averageEncodeTime != 0) {
        
        averageEncodeTime = (int) (averageEncodeTime + roundTrip) / 2;
        } else {
        
        averageEncodeTime = roundTrip;
        }
        
        if (!writingFile) {
        sendBuffer.append(postEncodeBuff);
        sendBuffer.append(buff);
        }
        
        }*/
        sendBuffer.append(buff);

    }

    /**
     * Returns the current size in bytes of data stored in the send buffer
     */
    public int getBufferSize() {

        return sendBuffer.size();
    }

    /**
     * Returns the size in bytes of the capacity of the send buffer
     */
    public int getBufferCapacity() {

        return sendBuffer.getCapacity();
    }

    public int getAverageEncodeTime() {

        return averageEncodeTime;
    }

    public int getEncodeQuality() {
        return this.encodeQuality;
    }

    protected VoicePushTextCallControl getCallControl() {

        return this.callControl;

    }

    public int getAudioBlockSize() {
        return this.rawChunkSize;
    }

    public void writeToDisk() {
        /*
        if (!writingFile) {
        writingFile = true;
        LOG.info("Writing Microphone Data to Disk.");
        
        if (callControl != null) {
        callControl.displayNotificaton( callControl.getOriginator() +" has lost their link. Recording voicemail");
        callControl.displayNotificaton( "Close this window to stop recording." );
        callControl.recordVoicemail();
        }
        
        try {
        if (!voicemessagedir.exists()) {
        voicemessagedir.mkdirs();
        }
        
        File groupmessagedir = new File (voicemessagedir, getCallControl().getGroup ().getName());
        
        if (!groupmessagedir.exists()) {
        groupmessagedir.mkdirs();
        }
        
        pipeID = getCallControl().getRemotePipeAdvertisement().getPipeID().toString();
        
        if (pipeID == null) {
        LOG.severe("Cannot log to file - the pipe ID is unknown!");
        return;
        }
        
        java.util.StringTokenizer st = new java.util.StringTokenizer(pipeID, ":");
        
        st.nextToken();
        st.nextToken();
        filename = st.nextToken();
        voicemail = new File(groupmessagedir, filename + tmpFileExtention);
        AudioSystem.write(new AudioInputStream(targetLine), tmpFileFormatType, voicemail);
        } catch (Exception e) {
        e.printStackTrace();
        }
        }*/
    }

    /**
     * Encapsulates audio data and session command into a dialogMessage.
     * Diapatches to remote peer.
     */
    public void dispatchVoiceData(byte[] speexData) {
        wizard.dispatchVoiceData(speexData);
    }

    public long getNumberOfBytesSent() {
        return this.sentBytes;
    }

    public long getNumberOfMessagesSent() {

        return sentMessages;

    }

    public int getMicState() {

        return this.micState;
    }

    private void setMicState(int state) {

        this.micState = state;
    }

    public void changeQuality(int quality) {
        encoder = new Encoder(quality);

        encodeQuality = quality;

        speexChunkSize = ((Integer) VoicePushTextCallControl.qualityByteMap.get(quality));
        speexMessageSize = speexChunkSize * DEFAULT_MESSAGE_SIZE_MULTIPLIER;
        speexBufferSize = speexChunkSize * DEFAULT_BUFFER_SIZE_MULTIPLIER;

        sendBuffer = new VoiceDataBuffer(speexBufferSize);

        LOG.info("mic chunk size " + speexChunkSize);
        LOG.info("mic message size " + speexMessageSize);
        LOG.info("Microphone Quality set to " + quality);
    }

    /**
     *  Starts the tagetLine. sets up the buffer and message size.
     */
    public void beginMic() {

        if (!voiceCaptureSupported) {
            return;
        }
        encodeQuality = AudioResource.QUALITY;


        // We don't need a syncrhonization construct here since, we only initiate
        // the microphone once. Threaded access is only possible hereafter
        encoder = new Encoder(AudioResource.QUALITY);

        speexChunkSize = ((Integer) VoicePushTextCallControl.qualityByteMap.get(AudioResource.QUALITY));

        LOG.info("mic chunk size " + speexChunkSize);
        if (speexMessageSize == 0) {

            speexMessageSize = speexChunkSize * DEFAULT_MESSAGE_SIZE_MULTIPLIER;
        }
        LOG.info("mic message size " + speexMessageSize);
        if (speexBufferSize == 0) {

            speexBufferSize = speexChunkSize * DEFAULT_BUFFER_SIZE_MULTIPLIER;
        }
        LOG.info("mic buffer size " + speexBufferSize);
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

        super.start();

    // for recording
       /* try {
    this.pauseMic();
    AudioSystem.write(new AudioInputStream(targetLine), targetType, new java.io.File("TestRecordingVPT." + targetType.getExtension()));
    } catch (Exception e) {
    e.printStackTrace();
    }
     */
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

    /**
     *  Restarts the target line and message send thread.
     */
    public void resumeMic() {
        if (getMicState() != STATE_PAUSE) {
            return;
        /*if(targetLine != null) {
        this.targetLine.start ();
        }*/
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

    /**
     *  Halts the reading of data to this targetline. pauses audio line read
     *  thread.
     */
    /*public void pauseMic() {
    if (getMicState() != STATE_ON) return;
    
    setMicState(this.STATE_PAUSE);
    
    //this.targetLine.stop();
    }*/
    /**
     *  Restarts the target line and message send thread.
     */
    /*public void resumeMic() {
    if (getMicState() != STATE_PAUSE) return;
    
    this.targetLine.start();
    
    if(getMicState () == STATE_PAUSE) {
    
    setMicState (this.STATE_ON);
    
    synchronized(pauseLock) {
    
    pauseLock.notify ();
    }
    
    synchronized(messageThreadLock) {
    
    messageThreadLock.notify ();
    }
    }
    
    }*/
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
    /*
    //File is now closed
    if (voicemail != null && voicemail.canRead()) {
    if (callControl.isRecording()) {
    //We now compress the audio
    File groupmessagedir = new File(voicemessagedir, getCallControl().getGroup().getName());
    new net.jxta.myjxta.util.VorbisEncoder().encode(voicemail.getAbsolutePath(), new File(groupmessagedir, filename + ".ogg").getAbsolutePath());
    } else {
    voicemail.delete();
    }
    }
     */
    }

    /**
     *  Return true if gain is supported in line target line.
     */
    public boolean isGainControlSupported() {

        return gainControl != null;
    }

    public float getMaxGainValue() {

        return (gainControl != null) ? gainControl.getMaximum() : 0.0f;
    }

    public float getMinGainValue() {

        return (gainControl != null) ? gainControl.getMinimum() : 0.0f;
    }

    public String getGainUnits() {

        return (gainControl != null) ? gainControl.getUnits() : "";
    }

    public String getMaxLabel() {

        return (gainControl != null) ? gainControl.getMaxLabel() : "";
    }

    public String getMinLabel() {

        return (gainControl != null) ? gainControl.getMinLabel() : "";
    }

    public float getGainValue() {

        return (gainControl != null) ? gainControl.getValue() : 0.0f;
    }

    public void adjustGainControl(float newValue) {

        if (gainControl != null) {

            gainControl.setValue(newValue);
        }

    }

    private void printMicState() {

        String s = null;

        if (this.micState == STATE_PAUSE) {
            s = "PAUSE  ";
        }

        if (this.micState == STATE_ON) {
            s = "ON  ";
        }

        if (this.micState == STATE_OFF) {
            s = "OFF  ";
        }



    }

    private void printMixers() {
        System.out.println("\n\rMixers : ");
        Mixer.Info[] info = AudioSystem.getMixerInfo();
        for (int i = 0; i < info.length; i++) {

            System.out.println("\tName: " + info[i].getName() + " Description: " + info[i].getDescription());

        }

    }

    private void printControls() {
        System.out.println("\n\rControls : ");
        Control[] controls = targetLine.getControls();

        for (int i = 0; i < controls.length; i++) {

            System.out.println("\tName: " + controls[i].toString());

        }


    }

    protected void printSupportedControls() {
        LOG.info("Mic SupportedControls");
        LOG.info("mute " +
                targetLine.isControlSupported(javax.sound.sampled.BooleanControl.Type.MUTE));
        LOG.info("Balance " +
                targetLine.isControlSupported(FloatControl.Type.BALANCE));
        LOG.info("MasterGain " +
                targetLine.isControlSupported(FloatControl.Type.MASTER_GAIN));
        LOG.info("Pan " + targetLine.isControlSupported(FloatControl.Type.PAN));
        LOG.info("SampleRate " +
                targetLine.isControlSupported(FloatControl.Type.SAMPLE_RATE));
        LOG.info("Volume " +
                targetLine.isControlSupported(FloatControl.Type.VOLUME));


        Mixer.Info[] mixerInfo = AudioSystem.getMixerInfo();
        for (int i = 0; i < mixerInfo.length; i++) {
            micMixer = AudioSystem.getMixer(mixerInfo[i]);
            LOG.info("MicMixer SupportedControls for mixer" +
                    mixerInfo[i].toString());
            LOG.info("mute " +
                    micMixer.isControlSupported(javax.sound.sampled.BooleanControl.Type.MUTE));
            LOG.info("Balance " +
                    micMixer.isControlSupported(FloatControl.Type.BALANCE));
            LOG.info("MasterGain " +
                    micMixer.isControlSupported(FloatControl.Type.MASTER_GAIN));
            LOG.info("Pan " + micMixer.isControlSupported(FloatControl.Type.PAN));
            LOG.info("SampleRate " +
                    micMixer.isControlSupported(FloatControl.Type.SAMPLE_RATE));
            LOG.info("Volume " +
                    micMixer.isControlSupported(FloatControl.Type.VOLUME));
        }

    }
}
