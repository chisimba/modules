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
	javax.swing.tree.DefaultMutableTreeNode questionnode[];

    public AnswerNavigator(MainFrame xmf) {
        super(xmf, "answers", "Answers Navigator");
          RPacketListener.addAnswerNavigatorFileVewListener(this);
    }
    
    public void processFileView(ArrayList<RealtimeFile> files) {
    	addObject(rootNode, "M23ine", true);
    	javax.swing.tree.DefaultMutableTreeNode qap = addObject(privateNode, "Mi4ne", true);
    	addObject(qap, "pu", true);
    	clear();
        addAccessTypes();
        if(files!=null){
        	questionnode=new javax.swing.tree.DefaultMutableTreeNode[files.size()];
	        for (int i = 0; i < files.size(); i++) {
	            if (files.get(i).isPublicAccessible()) {
		            questionnode[i] = addObject(publicNode, files.get(i), true);
		            ArrayList<RealtimeFile> files2 = files.get(i).getFiles();
		            if(files2!=null){
		            	for (int j = 0; j < files2.size(); j++) {
		            		javax.swing.tree.DefaultMutableTreeNode q = addObject(questionnode[i], files2.get(j), true);
		            		addObject(q, files2.get(j), true);
		            	}
		            } 
	            }
	            else {
	            	questionnode[i] = addObject(privateNode, files.get(i), true);
	            	ArrayList<RealtimeFile> files2 = files.get(i).getFiles();
		            if(files2!=null){
		            	for (int j = 0; j < files2.size(); j++) {
		            		javax.swing.tree.DefaultMutableTreeNode q = addObject(questionnode[i], files2.get(j), true);
		            		addObject(q, files2.get(j), true);
	            		}
		            }
	            }
	        }
	    }
    }

}
