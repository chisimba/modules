/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.base.audio;

import java.util.Hashtable;

/**
 *
 * @author developer
 */
public class VoicePushTextCallControl {
  public static final Hashtable qualityByteMap = new Hashtable();

    /**  This maps quality level to encoded message byte size gleaned from
     *  speex. Do it this way allows us to dynamically change quality in a session
     *  as well as alowing the user to select quality at session start
     */
    static {
        qualityByteMap.put(new Integer(0), new Integer(10));
        qualityByteMap.put(new Integer(1), new Integer(15));
        qualityByteMap.put(new Integer(2), new Integer(20));
        qualityByteMap.put(new Integer(3), new Integer(25));
        qualityByteMap.put(new Integer(4), new Integer(32));
        qualityByteMap.put(new Integer(5), new Integer(42));
        qualityByteMap.put(new Integer(6), new Integer(52));
        qualityByteMap.put(new Integer(7), new Integer(60));
        qualityByteMap.put(new Integer(8), new Integer(70));
        qualityByteMap.put(new Integer(9), new Integer(86));
        qualityByteMap.put(new Integer(10), new Integer(106));

    }
    
}
