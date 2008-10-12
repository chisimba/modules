/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import java.awt.Color;
import java.util.TimerTask;
import javax.swing.JTabbedPane;

class TabTitleAnimator extends TimerTask {

    boolean showTitle = false;
    int tabIndex;
    JTabbedPane tab;
    Color defaultColor;

    public TabTitleAnimator(int tabIndex, JTabbedPane tab, Color defaultColor) {
        this.tabIndex = tabIndex;
        this.tab = tab;
        this.defaultColor = defaultColor;
    }

    public void run() {
        try {
            if (showTitle) {
                tab.setBackgroundAt(tabIndex, defaultColor);
                showTitle = false;
            } else {
                tab.setBackgroundAt(tabIndex, Color.ORANGE);
                showTitle = true;

            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }
}