/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.audio;

import javax.sound.sampled.AudioFormat;
import org.xiph.speex.spi.SpeexEncoding;

public interface AudioResource {

    public static final int FRAME_BITS = 2;         // default = 2
    public static final int BLOCK_SIZE = 640;	// default = 640
    public static final int SAMPLE_RATE = 44100;	// default = 16000
    //8000,11025,16000,22050,44100
    public static final int MODE = 1;	// default = 1
    public static final int QUALITY = SpeexEncoding.SPEEX_Q8.getQuality();//SPEEX_Q8.getQuality();	// default = SPEEX_Q8
    public static final int CHANNELS = 2;	// default = 1
    public static final int LINE_BUFFER_SIZE = 8192;

    public int getBufferSize();
    public static AudioFormat audioFormat = new AudioFormat(
            AudioFormat.Encoding.PCM_SIGNED,
            44100.0f, // sampleRate
            16, // sampleSizeInBits
            1, // channels
            2, // frameSize
            44100.0f, // frameRate
            true);         // bigEndian
}