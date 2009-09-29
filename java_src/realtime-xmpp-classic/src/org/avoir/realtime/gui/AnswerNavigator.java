/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.avoir.realtime.gui;

import java.util.ArrayList;

import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.net.RPacketListener;

/**
 *
 * @author developer
 */
public class AnswerNavigator extends Navigator{

    public AnswerNavigator(MainFrame xmf) {
        super(xmf, "answers", "Answers Navigator");
          RPacketListener.addAnswerNavigatorFileVewListener(this);
    }
    
    public void processFileView(ArrayList<RealtimeFile> files) {
        clear();
        addAccessTypes();

        for (int i = 0; i < files.size(); i++) {

            if (files.get(i).isPublicAccessible()) {
            	javax.swing.tree.DefaultMutableTreeNode questionnode = addObject(publicNode, files.get(i), true);
            	ArrayList<RealtimeFile> files2 = files.get(i).getFiles();
            	for (int j = 0; j < files2.size(); j++) {
                	addObject(questionnode, files2.get(j), true);
            	}
            } else {
            	javax.swing.tree.DefaultMutableTreeNode questionnode = addObject(privateNode, files.get(i), true);
            	ArrayList<RealtimeFile> files2 = files.get(i).getFiles();
            	for (int j = 0; j < files2.size(); j++) {
                	addObject(questionnode, files2.get(j), true);
            	}
            }
        }
    }

}
