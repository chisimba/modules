/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.dnd;

import javax.swing.JComponent;
import javax.swing.JList;

/**
 *
 * @author developer
 */
public class RListTransferHandler extends StringTransferHandler {

    @Override
    protected void cleanup(JComponent c, boolean remove) {
    }

    @Override
    protected String exportString(JComponent c) {
        JList list=(JList)c;
//        RealtimeFile f = (RealtimeFile)list.getSelectedValue();
        return (String)list.getSelectedValue();
    }

    @Override
    protected void importString(JComponent c, String str) {
        JList list=(JList)c;
        if(list.getVisibleRowCount() > 0){
            list.setSelectedIndex(list.getVisibleRowCount()-1);
        }
    }
}
