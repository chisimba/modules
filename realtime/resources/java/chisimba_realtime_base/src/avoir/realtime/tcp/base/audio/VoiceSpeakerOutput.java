/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.audio;

import javax.sound.sampled.*;

public final class VoiceSpeakerOutput extends Thread {

    /**
     * speaker line out
     */
    private SourceDataLine sourceLine = null;
    /**
     * wrapper form speex decoder
     */
    private Decoder decoder = null;
    /**
     * static values for now till first testing phase is over.
     * represents the number of blocks that a buffer will hold
     */
    private static final int DEFAULT_BUFFER_SIZE_MULTIPLIER = 50;
    /**
     * static values for now till first testing phase is over.
     * represents the number of blocks in a message that a buffer will hold
     * increasing this value will reduce the number of messages and possibly
     * increase the latency of audio to the endpoint
     */
    private static final int DEFAULT_MESSAGE_SIZE_MULTIPLIER = 10;
    /**
     * denots state for this output line
     * ON := line started (line must be in open state)
     * PAUSE := line stoped (line must be opend and started)
     * OFF := line stopped and closed
     */
    private static final int STATE_ON = 1;
    private static final int STATE_OFF = 0;
    private static final int STATE_PAUSE = 2;
    /**
     * denotes the current speaker state
     */
    private int speakerState = STATE_PAUSE;
    /**
     * incomming message buffer, these messages contain encoded speex bytes
     */
    private VoiceDataBuffer recvBuffer = null;
    /**
     * statistic
     */
    private long receivedMessages = 0;
    /**
     * statistic
     */
    private long receivedBytes = 0;    //dubugging .. remove when finished
    final boolean firstReceivedMessage = true;
    final byte[] firstReceivedBytes = null;
    /**
     * wait lock - pauses data flow to this hardware resource (speaker)
     */
    private Object pauseLock = null;
    /**
     * wait lock - waits on received messages to fill buffer sufficiently
     */
    private Object bufferLock = null;
    /**
     * wait lock - waits on pcm blocks to fill the  sourceLine buffer.
     */
    private Object sourceLineThreadLock = null;
    /**
     * true if we are holding on the sourceLinebuffer to fill
     */
    private Boolean sourceLineThreadWaiting = null;
    /**
     * true if we are holding on the encoded message buffer to fill up
     */
    private Boolean messageThreadWaiting = null;
    /**
     * basic block size of raw audio data that speex encode operates on. On
     * decode this is the block size of the decoded speex bytes
     */
    private final int rawChunkSize = AudioResource.BLOCK_SIZE;
    /**
     * basic block size of incoming encoded speex bytes. this value is
     * dependent on the quality level of speex encoding.
     */
    private int speexChunkSize = 0;
    /**
     * represents the number of speex chunks to pack into one message. this
     * value can affect latency if too large. should never be larger than
     * speexBufferSize
     */
    private int speexMessageSize = 0;
    /**
     * donotes the size in bytes of the lines (speakers) buffer
     */
    private final int rawBufferSize = rawChunkSize * 60;
    /**
     * represents the size of the buffer holding incoming speex  bytes
     */
    private int speexBufferSize = 0;
    /**
     * donotes the default mixer obtained from the line (speaker). we obtain
     * controls such as volume, mute from this object
     */
    private Mixer speakerMixer = null;
    /**
     * denotes the gain control on this line
     */
    private FloatControl gainControl = null;
    /**
     * denotes the mute control on this line
     */
    private BooleanControl muteControl = null;
    /**
     * statistic
     */
    private long averageDecodeTime = 0;
    /**
     * Human readable peer endpoint name
     */
    private String originator = null;
    /**
     * System time in milliseconds last voice message came in
     */
    private long timeOfLastVoiceMessage = 0;
    /**
     * controls writing to the sourceLine (speaker). Writing small blocks
     * (ie 640b) will produce backlogs and latency.  This thread scopes the
     * complexity of dealing with the sourceLine buffer and performs the actual
     * writing to sourceLine.
     */
    private WriteToSourceLineThread sourceLineThread = null;
    private boolean clearBuffers = false;
    private AudioWizardFrame audioWizardFrame;

    /**
     */
    public VoiceSpeakerOutput(AudioWizardFrame audioWizardFrame) {
        this.audioWizardFrame = audioWizardFrame;
        this.setPriority(Thread.NORM_PRIORITY);
        pauseLock = new Object();
        bufferLock = new Object();
        sourceLineThreadLock = new Object();
        messageThreadWaiting = new Boolean(false);
        sourceLineThreadWaiting = new Boolean(false);
    }

    /**
     * Seeks out a sourceline (speaker) of the specified format. Opens that line
     * and finds its mute and gain controls.
     */
    public boolean obtainHardware() {

        AudioFormat format = AudioResource.audioFormat;
        DataLine.Info info = new DataLine.Info(SourceDataLine.class, format, AudioSystem.NOT_SPECIFIED);//rawBufferSize);

        try {

            sourceLine = (SourceDataLine) AudioSystem.getLine(info);

        } catch (LineUnavailableException e) {

            e.printStackTrace();
            return false;

        }

        try {

            sourceLine.open();

        } catch (LineUnavailableException e) {

            e.printStackTrace();
            return false;

        }

        if (sourceLine.isControlSupported(FloatControl.Type.MASTER_GAIN)) {

            gainControl = (FloatControl) sourceLine.getControl(FloatControl.Type.MASTER_GAIN);

        }

        if (sourceLine.isControlSupported(BooleanControl.Type.MUTE)) {

            muteControl = (BooleanControl) sourceLine.getControl(BooleanControl.Type.MUTE);
        }

        return true;

    }

    /**
     * Starts source line (speaker). Set thread state to Start. Initialize
     * incoming buffer. start sourceline thread.
     */
    public void beginSpeaker() {

        decoder = new Decoder();
        speexChunkSize = ((Integer) VoicePushTextCallControl.qualityByteMap.get(AudioResource.QUALITY));

        if (speexMessageSize == 0) {

            speexMessageSize = speexChunkSize * DEFAULT_MESSAGE_SIZE_MULTIPLIER;

        }
        if (speexBufferSize == 0) {

            speexBufferSize = speexChunkSize * DEFAULT_BUFFER_SIZE_MULTIPLIER;
        }

        if (this.recvBuffer == null) {

            this.recvBuffer = new VoiceDataBuffer(speexBufferSize);
        }


        if (getSpeakerState() == STATE_PAUSE) {

            synchronized (pauseLock) {

                pauseLock.notify();
            }

            sourceLine.start();

            if (sourceLine.isControlSupported(FloatControl.Type.MASTER_GAIN)) {

                gainControl = (FloatControl) sourceLine.getControl(FloatControl.Type.MASTER_GAIN);

            }

            if (sourceLine.isControlSupported(FloatControl.Type.MASTER_GAIN)) {

                muteControl = (BooleanControl) sourceLine.getControl(BooleanControl.Type.MUTE);

            }
        }

        setSpeakerState(this.STATE_ON);

        sourceLineThread = new WriteToSourceLineThread();

        sourceLineThread.start();

        super.start();
    }

    /**
     * Stops writting to line. Race condition exists. Fix
     */
    public void endSpeaker() {

        setSpeakerState(this.STATE_OFF);
        if (sourceLine != null) {
            sourceLine.stop();
        }

    }

    public SourceDataLine getSourceDataLine() {
        return sourceLine;
    }

    /**
     * Gives speaker control back to the system. Null's resources.
     */
    public void releaseHardware() {

        gainControl = null;

        muteControl = null;

        if (sourceLine != null) {
            sourceLine.flush();

            sourceLine.drain();

            sourceLine.stop();

            sourceLine.close();
        }


    }

    /**
     * Sets the human readable name of the remote peer
     */
    protected void setOriginator(String originator) {

        this.originator = originator;
    }

    /**
     * Gets the human readable name of the remote peer
     */
    public String getOriginator() {

        return this.originator;
    }

    /**
     * Returns a long representing the system time in milliseconds a voice data
     * message was received from remote host.
     */
    public long getTimeOfLastVoiceMessage() {

        return this.timeOfLastVoiceMessage;
    }

    public void changeQuality(int quality) {
        speexChunkSize = ((Integer) VoicePushTextCallControl.qualityByteMap.get(quality));
        speexMessageSize = speexChunkSize * DEFAULT_MESSAGE_SIZE_MULTIPLIER;
        speexBufferSize = speexChunkSize * DEFAULT_BUFFER_SIZE_MULTIPLIER;
        clearBuffers = true;
    }

    /**
     * Deconstructs the voice data message. If we are waiting on new data to
     * write to source line (speaker) out then check the buffer size for
     * sufficient bytes notify play thread.
     */
    public void receiveVoicePushTextData(byte[] voiceData) {
        //  System.out.println("received: "+voiceData.length);
        if (clearBuffers) {
            // We immediately get rid of the old data
            // this should allow us to grab whole chunks again
            recvBuffer.clear();
            clearBuffers = false;
        }


        if (voiceData != null) {

            recvBuffer.append(voiceData);

            this.receivedMessages += 1;

            this.receivedBytes += voiceData.length;

            boolean waiting = false;

            synchronized (messageThreadWaiting) {

                waiting = messageThreadWaiting.booleanValue();
            }
            if (waiting && recvBuffer.size() >= speexChunkSize) {

                messageThreadWaiting = new Boolean(false);

                synchronized (bufferLock) {

                    bufferLock.notify();
                }
            }

        } else {
            System.out.println("reveiceVoicePushTextData : voice data is null");
        }

    }

    /**
     * Decode speex block, write to line out. Pause lock goes active on buffer
     * starvation. speex chunk size is wholey dependent on speex encode quality.
     * Quality is determined by message excahnge in VojxtaCallControl. The lesser
     * quality wins.
     */
    public void run() {


        while (true) {

            try {

                if (getSpeakerState() == this.STATE_OFF) {
                    //we should send any remaining data in buffer first
                    break;
                }

                if (recvBuffer.size() >= (speexMessageSize)) {

                    decodeAndPlay(recvBuffer.get(speexMessageSize));

                } else {
                    synchronized (messageThreadWaiting) {

                        messageThreadWaiting = new Boolean(true);
                    }

                    synchronized (bufferLock) {
                        bufferLock.wait();
                    }
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }


    }

    /**
     * Returns the current size in bytes of data stored in the receive buffer
     */
    public int getBufferSize() {

        return recvBuffer.size();
    }

    /**
     * Returns the size in bytes of the capacity of the receive buffer
     */
    public int getBufferCapacity() {

        return recvBuffer.getCapacity();
    }

    public boolean isPaused() {
        return getSpeakerState() == STATE_PAUSE;
    }

    /**
     * Return out local state, ON OFF PAUSE
     */
    private void setSpeakerState(int speakerState) {

        this.speakerState = speakerState;

    }

    /**
     * Decode and write decoded bytes to pcmBuffer. we pull a chunk of
     * speex bytes of speexMessageSize into this method then block it out
     * to its basic block size (determined by speex quality) and write the
     * raw pcm to the pcmBuffer. the decoded block size is 640 bytes at the
     * current sample rate.
     * ie
     * speexMessageSize = 75 bytes (5 chunks of 15bytes)
     * break the message up into 5 chunks and decode each
     * put it all together and write to pcmBufer
     * <p/>
     * SourceLine writes do large chunks better than rapid small chunks (640)
     * so we put a message back together and write it to the pcmBuffer
     */
    protected void decodeAndPlay(byte[] buf) {

        if (getSpeakerState() != this.STATE_OFF ||
                getSpeakerState() != this.STATE_PAUSE) {
            /*
            int chunks = buf.length / speexChunkSize;
            
            byte[] decodedBuff = new byte[chunks * rawChunkSize];
            
            int[] encodedStartPos = new int[chunks];
            
            int[] decodedStartPos = new int[chunks];
            
            for (int j = 0; j < chunks; j++) {
            
            encodedStartPos[j] = j * speexChunkSize;
            
            decodedStartPos[j] = j * rawChunkSize;
            }
            
            
            for (int i = 0; i < chunks; i++) {
            
            byte[] preDecodeBuff = new byte[speexChunkSize];
            
            System.arraycopy(buf, encodedStartPos[i], preDecodeBuff, 0, speexChunkSize);
            
            long in = System.currentTimeMillis();
            
            byte[] postDecodeBuff = decoder.decode(preDecodeBuff);
            
            long out = System.currentTimeMillis();
            
            long roundTrip = (out - in);
            
            if (averageDecodeTime != 0) {
            
            averageDecodeTime = (long) (averageDecodeTime + roundTrip) / 2;
            
            } else {
            
            averageDecodeTime = roundTrip;
            }
            
            try {
            System.arraycopy(postDecodeBuff, 0, decodedBuff, decodedStartPos[i], rawChunkSize);
            } catch (Exception e) {
            // What happens here is a possible null pointer exception due to change of quality
            // we ignore until the correct chunk size is set.
            return;
            }
            
            }*/

            boolean sourceLineWaiting = false;

            synchronized (sourceLineThreadWaiting) {

                sourceLineWaiting = sourceLineThreadWaiting.booleanValue();
            }

            if (sourceLineWaiting && sourceLineThread.getBufferSize() >= sourceLineThread.getWriteBlockSize()) {

                messageThreadWaiting = new Boolean(false);

                synchronized (sourceLineThreadLock) {

                    sourceLineThreadLock.notify();
                }
            }
            // sourceLineThread.put(decodedBuff);
            sourceLineThread.put(buf);
        //sourceLine.write (decodedBuff, 0, decodedBuff.length);
        } else {

            System.out.println("Unable to play audio, line closed!");
        }


    }

    /**
     * Statistical accessor
     */
    public int getPCMBufferSize() {

        return sourceLineThread != null ? sourceLineThread.getBufferSize() : -1;
    }

    /**
     * Statistical accessor
     */
    public int getSourceLineAvailable() {

        return sourceLine.available();
    }

    /**
     * This thread holds a dynamic buffer from which decoded bytes are written.
     * Largish chunks (multiples of rawPCMBlockSize) are read and written to
     * the source line when source line buffer has room and there are ample
     * bytes in the pcmBuffer. This Thread does the actual Playing to the
     * speaker.
     * TODO:
     * The pause state is recognized in this thread which will halt the
     * playing of sound but a signal needs to be sent to the main VoiceSpekaerOutput
     * run() or else hte buffers will continue to fill.
     */
    final class WriteToSourceLineThread extends Thread {

        private VoiceDataBuffer pcmBuffer = null;
        private final int writeBlockSize = rawChunkSize * 30;
        private final int pcmBufferSize = rawBufferSize * 60;

        WriteToSourceLineThread() {

            pcmBuffer = new VoiceDataBuffer(pcmBufferSize);

            setPriority(Thread.NORM_PRIORITY);
        }

        public int getWriteBlockSize() {

            return writeBlockSize;
        }

        public int getBufferSize() {

            return pcmBuffer.size();
        }

        public void put(byte[] buff) {

            pcmBuffer.append(buff);
        }

        public void run() {



            while (true) {
                try {


                    if (getSpeakerState() == VoiceSpeakerOutput.this.STATE_PAUSE) {

                        synchronized (pauseLock) {
                            pauseLock.wait();
                        }
                    }

                    if (getSpeakerState() == VoiceSpeakerOutput.this.STATE_OFF) {

                        //we should send any remaining data in buffer first
                        break;
                    }

                    //if( sourceLine.available () >= writeBlockSize &&
                    //      pcmBuffer.size () > writeBlockSize) {
                    if (pcmBuffer.size() > (640)) {
                        int l = pcmBuffer.size();
                        byte[] buff = pcmBuffer.get(l);
                        sourceLine.write(buff, 0, l);
                        audioWizardFrame.getBase().getSpeakerVolumeMeter().setValue(audioWizardFrame.getSoundDetector().calcCurrVol(buff, 0, l));
                       

                    //sourceLine.write (pcmBuffer.get (writeBlockSize), 0, writeBlockSize);
                    } else {

                        synchronized (sourceLineThreadWaiting) {

                            sourceLineThreadWaiting = new Boolean(true);
                        }

                        synchronized (sourceLineThreadLock) {

                            sourceLineThreadLock.wait();
                        }
                    }
                } catch (InterruptedException ix) {

                    ix.printStackTrace();
                }
            }


        }
    }

    private int getSpeakerState() {

        return this.speakerState;
    }

    /**
     * halts writing to the source line (speaker). race condition exists. fix
     */
    public void pauseSpeaker() {
        if (speakerState == STATE_PAUSE) {
            return;
        }
        setSpeakerState(this.STATE_PAUSE);
    }

    /**
     * Resumes writing to the sourceLine and starts the line
     */
    public void resumeSpeaker() {
        if (this.speakerState != STATE_PAUSE) {
            return;
        }
        setSpeakerState(this.STATE_ON);

        synchronized (pauseLock) {
            pauseLock.notify();
        }
    }

    /**
     * halts writing to the source line (speaker). race condition exists. fix
     */
    /*public void pauseSpeaker() {
    
    setSpeakerState(this.STATE_PAUSE);
    
    sourceLine.stop();
    
    }*/
    /**
     * Resumes writing to the sourceLine and starts the line
     */
    /*public void resumeSpeaker() {
    
    sourceLine.start();
    
    setSpeakerState(this.STATE_ON);
    
    synchronized (pauseLock) {
    
    pauseLock.notify();
    }
    }*/
    /**
     * Accessor for incoming speex buffer size
     */
    public int getEncodedBufferSize() {

        return speexBufferSize;

    }

    /**
     * Accessor for incoming speex buffer size
     */
    public void setEncodedBufferSize(int encodedBufferSize) {        // at the momment the buffer is not resizeable
        //this.speexBufferSize = encodedBufferSize;
    }

    /**
     * statistical accessor
     */
    public long getNumberOfMessagesReceived() {

        return this.receivedMessages;
    }

    /**
     * statistical accessor
     */
    public long getNumberOfBytesReceived() {

        return this.receivedBytes;
    }

    /**
     * statistical accessor
     */
    public long getAverageDecodeTime() {

        return averageDecodeTime;
    }

    /**
     * statistical accessor
     */
    public int getEncodedMessageSize() {

        return speexMessageSize;
    }

    /**
     * statistical accessor
     */
    public void setEncodedMessageSize(int encodedMessageSize) {

        this.speexMessageSize = encodedMessageSize;
    }

    /**
     * statistical accessor
     */
    public boolean isGainControlSupported() {

        return gainControl != null;
    }

    /**
     * Sets the gain on this source Line
     */
    public void adjustGainValue(float gainValue) {

        if (gainControl != null) {
            if (gainValue > gainControl.getMaximum()) {
                gainValue = gainControl.getMaximum();
            }

            gainControl.setValue(gainValue);
        }
    }

    /**
     * Returns the current gain value.
     */
    public float getGainValue() {

        return (gainControl != null) ? gainControl.getValue() : 0.0f;
    }

    /**
     * Returns the max gain value for this sourceline
     */
    public float getMaxGainValue() {

        return (gainControl != null) ? gainControl.getMaximum() : 0.0f;
    }

    /**
     * Returns the min gain value for this sourceline
     */
    public float getMinGainValue() {

        return (gainControl != null) ? gainControl.getMinimum() : 0.0f;
    }

    /**
     * Returns a string representing the units this gain control uses
     */
    public String getGainUnits() {

        return (gainControl != null) ? gainControl.getUnits() : "";
    }

    /**
     * Returns a string representing the max value as a label
     */
    public String getMaxGainLabel() {

        return (gainControl != null) ? gainControl.getMaxLabel() : "";
    }

    /**
     * Returns a string representing the min value as a label
     */
    public String getMinGainLabel() {

        return (gainControl != null) ? gainControl.getMinLabel() : "";
    }

    /**
     * Querys control state
     */
    public boolean isMuteControlSupported() {

        return muteControl != null;
    }

    /**
     * Querys control state
     */
    public boolean isMute() {

        return muteControl.getValue();
    }

    /**
     * sets control state
     */
    public void setMute(boolean mute) {

        muteControl.setValue(mute);
    }

    /**
     * Querys control unit string
     */
    public String getMuteStateLabel() {

        return muteControl.getStateLabel(muteControl.getValue());
    }
}

