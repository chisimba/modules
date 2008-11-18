/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.appsharing;

import java.awt.Rectangle;

public class RawScrape extends Rectangle{

	private int[] pixels;
	//private Rectangle region;
	private int iIndex;

	public RawScrape(Rectangle rect, int[] ia, int iIndex){
		super(rect);
		//this.region = rect;
		this.pixels = ia;
		this.iIndex = iIndex;
		//this.pixels = new int[rect.width*rect.height];

		//System.arraycopy(ia, )
	}

	public int[] getPixels(){
		return this.pixels;
	}
//
//	public Rectangle getRegion(){
//		return this.region;
//	}

	public int getIndex(){
		return this.iIndex;
	}

}