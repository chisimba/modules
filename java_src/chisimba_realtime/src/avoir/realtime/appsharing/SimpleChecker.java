/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.appsharing;

public class SimpleChecker implements DataChangeChecker {

	public boolean dataChanged(int[] iaOld, int[] iaNew) {
		boolean bRet = false;
		if((null != iaOld) && (iaOld.length == iaNew.length)){
			for(int i = 0; i < iaNew.length; i++){
				if(iaNew[i] != iaOld[i]){
					bRet = true;
					break;
				}
			}
		}else{
			bRet = true;
		}
		return bRet;
	}

}
