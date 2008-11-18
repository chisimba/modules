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

import java.io.DataInputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.net.InetAddress;
import java.net.UnknownHostException;
import javax.swing.JOptionPane;
import static avoir.realtime.audio.Constants.*;

/**
 *
 * @author developer
 */
public class AudioManager {

    private AudioBase[] audio = new AudioBase[2];
    private MasterModel m_masterModel;
    private boolean m_audioActive;
    private DataInputStream m_receiveStream;
    private OutputStream m_sendStream;    // audio related: owned by ChatModel
    private Network m_network;

    public AudioManager(MasterModel m_masterModel) {
        this.m_masterModel = m_masterModel;
        initNetwork();
        audio[DIR_MIC] = new AudioCapture(getConnectionSettings().getFormatCode(),
                getAudioSettings().getSelMixer(DIR_MIC),
                getAudioSettings().getBufferSizeMillis(DIR_MIC));
        audio[DIR_SPK] = new AudioPlayback(getConnectionSettings().getFormatCode(),
                getAudioSettings().getSelMixer(DIR_SPK),
                getAudioSettings().getBufferSizeMillis(DIR_SPK));

    }

    public void connect(String strHostname, int port) {
        InetAddress addr = null;
        try {
            addr = InetAddress.getByName(strHostname);
        } catch (UnknownHostException e) {
            Debug.out(e);
            JOptionPane.showMessageDialog(null, "Unknown host " + strHostname);
        }
        if (addr != null) {
            getNetwork().connect(addr, port);
        }
        if (!getNetwork().isConnected()) {
            JOptionPane.showMessageDialog(null, new Object[]{"Could not connect to audio server"}, "Error", JOptionPane.ERROR_MESSAGE);
        } else {
            initConnection(true);
        }
    }

    public OutputStream getSendStream() {
        return m_sendStream;
    }
    // audio related
    public DataInputStream getReceiveStream() {
        return m_receiveStream;
    }

    public void initAudioStream() {
        // only necessary for test mode on microphone side
        if (isMicrophoneTest()) {
            ((AudioPlayback) getAudio(DIR_SPK)).setAudioInputStream(((AudioCapture) getAudio(DIR_MIC)).getAudioInputStream());
        }
    }

    public boolean isMicrophoneTest() {
        return isAudioActive() && (((AudioCapture) getAudio(DIR_MIC)).getOutputStream() == null);
    }

    private void closeAudio() {
        setAudioActive(false);
        closeAudio(DIR_SPK);
        closeAudio(DIR_MIC);
    }

    public void closeMIC() {
        setAudioActive(false);
        closeAudio(DIR_MIC);
    }

    public void closeSpkr() {
        setAudioActive(false);
        closeAudio(DIR_SPK);
    }

    private void closeAudio(int d) {
        if (getAudio(d) != null) {
            getAudio(d).close(true);
        }
    }

    public boolean isAudioActive() {
        return m_audioActive;
    }
    // audio related
    /* Set up audio connections. */
    private void initNetworkAudio() {
        Debug.out("initNetworkAudio(): receiveStream: " + getReceiveStream());
        Debug.out("initNetworkAudio(): sendStream: " + getSendStream());
        try {
            if (m_masterModel.getMode().equals("m")) {
                Debug.out("MIC Mode, setting output stream...");
                ((AudioCapture) getAudio(DIR_MIC)).setOutputStream(getSendStream());
                startAudio(DIR_MIC);
            }
            if (m_masterModel.getMode().equals("s")) {
                Debug.out("Speaker Mode, setting input stream...");
                ((AudioPlayback) getAudio(DIR_SPK)).setAudioInputStream(AudioUtils.createNetAudioInputStream(getConnectionSettings().getFormatCode(), getReceiveStream()));
                Debug.out("Starting speaker audio...");
                startAudio(DIR_SPK);
            }
            setAudioActive(true);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void startAudio(int d) throws Exception {
        if (!isAudioActive()) {
            Debug.out("Set audio code");
            getAudio(d).setFormatCode(getConnectionSettings().getFormatCode());
        }
        getAudio(d).open();

        //reset the output stream if this is mic and is being restarted...some bug
        //case the above open will close it
        if (m_masterModel.getMode().equals("m")) {

            Debug.out("MIC Mode, setting output stream again...");
            ((AudioCapture) getAudio(DIR_MIC)).setOutputStream(getSendStream());
        }
        getAudio(d).start();
        Debug.out("Audio Started");
    }

    public AudioBase getAudio(int d) {
        return audio[d];
    }

    public void setAudioActive(boolean active) {
        m_audioActive = active;
    //notifyAudio();
    }

    private void streamError(String strError) {
        JOptionPane.showMessageDialog(null, new Object[]{strError, "Connection will be terminated"}, "Error", JOptionPane.ERROR_MESSAGE);
        getNetwork().disconnect();
        closeAudio();
    // notifyConnection();
    }

    private void initConnection(boolean bActive) {
        Debug.out("initConnection(" + bActive + "): begin");
        try {
            m_receiveStream = new DataInputStream(getNetwork().createReceiveStream());
            m_sendStream = getNetwork().createSendStream();
            Debug.out("initConnection(): receiveStream: " + m_receiveStream);
            Debug.out("initConnection(): sendStream: " + m_sendStream);
        } catch (IOException e) {
            Debug.out(e);
            streamError("Problems while setting up the connection");
        }
        initNetworkAudio();
    /* To agree on the audio data format, the active side sends a
    32 bit integer format code so that the passive side can
    adapt to it.

    This mechanism could be extended to a real negotiation
    where the passive side sends a list of possible formats and
    the active one chooses one of them.
    boolean bHandshakeSuccessful = false;
    if (bActive) {
    bHandshakeSuccessful = doHandshakeActive();
    } else //passive
    {
    bHandshakeSuccessful = doHandshakePassive();
    }
    if (bHandshakeSuccessful) {
    Debug.out("connection established");
    if (isConnected()) {
    initNetworkAudio();
    }
    notifyConnection();
    } else {
    m_receiveStream = null;
    m_sendStream = null;
    }
    Debug.out("initConnection(" + bActive + "): end");
     */
    }

    private Network getNetwork() {
        return m_network;
    }

    private void initNetwork() {
        if (getConnectionSettings().getConnectionType() == CONNECTION_TYPE_TCP) {
            m_network = new TCPNetwork(getMasterModel());
        } else {
            m_network = new UDPNetwork(getMasterModel());
        }
    }

    private ConnectionSettings getConnectionSettings() {
        return getMasterModel().getConnectionSettings();
    }

    private MasterModel getMasterModel() {
        return m_masterModel;
    }

    private AudioSettings getAudioSettings() {
        return getMasterModel().getAudioSettings();
    }
}
