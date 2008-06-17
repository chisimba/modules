/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.base.audio;

import org.xiph.speex.SpeexEncoder;
import org.xiph.speex.spi.SpeexEncoding;

import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * @author Ravi
 * @author jamoore
 * @modified 2005-03-14 jamoore inject quality into contructor/quality look up table
 * @modified 2005-03-14 jamoore remove AudioResource interface
 *
 */
public final class Encoder {

    static final Logger LOG = Logger.getLogger(Encoder.class.getName());
    private final SpeexEncoder encoder;

    /**
     *  quality arg makes this encoder dynamic
     */
    public Encoder(int quality) {

        super();

        LOG.setLevel(Level.INFO);
        int speexQuality = getSpeexQuality(quality);
        LOG.info("SPEEXquality " + speexQuality);
        encoder = new SpeexEncoder();

        encoder.init(AudioResource.MODE, speexQuality, AudioResource.SAMPLE_RATE, AudioResource.CHANNELS);
    }
	
    protected int getSpeexQuality(int quality) {
        
        int speexQuality = SpeexEncoding.SPEEX_Q0.getQuality ();
        
        switch(quality) {
            case 0 : speexQuality = SpeexEncoding.SPEEX_Q0.getQuality (); break;
            case 1 : speexQuality = SpeexEncoding.SPEEX_Q1.getQuality ();break;
            case 2 : speexQuality = SpeexEncoding.SPEEX_Q2.getQuality ();break;
            case 3 : speexQuality = SpeexEncoding.SPEEX_Q3.getQuality ();break;
            case 4 : speexQuality = SpeexEncoding.SPEEX_Q4.getQuality ();break;
            case 5 : speexQuality = SpeexEncoding.SPEEX_Q5.getQuality ();break;
            case 6 : speexQuality = SpeexEncoding.SPEEX_Q6.getQuality ();break;
            case 7 : speexQuality = SpeexEncoding.SPEEX_Q7.getQuality ();break;
            case 8 : speexQuality = SpeexEncoding.SPEEX_Q8.getQuality ();break;
            case 9 : speexQuality = SpeexEncoding.SPEEX_Q9.getQuality ();break;
            case 10 : speexQuality = SpeexEncoding.SPEEX_Q10.getQuality ();break;
        }
        
        return speexQuality;
    }
    
    /**
     * 
     * @param buf
     * @return speex encode byte array
     */
    public byte[] encode(byte[] buf) {

        encoder.processData(buf, 0, AudioResource.BLOCK_SIZE);

        byte[] encoded = new byte[encoder.getProcessedDataByteSize()];

        encoder.getProcessedData(encoded, 0);

        return encoded;
    }
}
