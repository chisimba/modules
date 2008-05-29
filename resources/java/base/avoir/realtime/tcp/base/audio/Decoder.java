/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.base.audio;

import org.xiph.speex.SpeexDecoder;

import java.io.StreamCorruptedException;

/**
 * @author Ravi
 *
 * TODO To change the template for this generated type comment go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
public final class Decoder {

    private final SpeexDecoder decoder;

    /**
     * 
     */
    public Decoder() {
        super();
        decoder = new SpeexDecoder();
        decoder.init(AudioResource.MODE, AudioResource.SAMPLE_RATE, AudioResource.CHANNELS, false);	// boolean enhanced = false
    }

    /**
     * 
     * @param buf
     * @return speex decoded byte array
     */
    public byte[] decode(byte[] buf) {

        try {
            decoder.processData(buf, 0, buf.length);
        } catch (StreamCorruptedException e) {
            //e.printStackTrace();
            System.out.println(e.getMessage() + "@" + System.currentTimeMillis());
            return null;
        }

        byte[] decoded = new byte[decoder.getProcessedDataByteSize()];
        decoder.getProcessedData(decoded, 0);
        return decoded;
    }
}
