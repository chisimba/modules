/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.appsharing;

public interface DataChangeChecker {

	public boolean dataChanged(int[] iaOld, int[] iaNew);

}