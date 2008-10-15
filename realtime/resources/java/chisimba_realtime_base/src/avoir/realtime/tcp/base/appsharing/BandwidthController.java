/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.base.appsharing;
public class BandwidthController {

	public static final float DEFAULT_KILO_BITS_PER_SECOND = 80.0f;
	private float fBitrate = DEFAULT_KILO_BITS_PER_SECOND;
	private int iLastSendAmount = 0;

	public BandwidthController(float fBitrate){
		setBitratekbps(fBitrate);
	}

	public void setBitratekbps(float fBitrate){
		this.fBitrate = fBitrate;
	}

	public int getWaitPeriod(int iBytes){
		int iWait = 0;
		if(0 != iLastSendAmount){
			iWait = (int)((this.iLastSendAmount*8)/fBitrate);
		}
		this.iLastSendAmount = iBytes;
		iWait = Math.max(0, iWait);
//		System.out.println("returning " + iWait);
		return iWait;
	}

	public void reset(){
		this.iLastSendAmount = 0;
	}

}