/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.RealtimeBase;
import java.awt.Dimension;
import javax.swing.tree.DefaultMutableTreeNode;

/**
 *
 * @author developer
 */
public class AgendaManager {

    private RealtimeBase base;
    private AgendaTree agendaTree;

    public AgendaManager(RealtimeBase base) {
        this.base = base;
        initComponents();
    }

    public void addDefaultAgenda(String title) {
        agendaTree.clear();
        if (title == null) {
            title = "Slides";
        }
        if (title.trim().equals("")) {
            title = "";
        }
         addAgenda(new String[0], "Whiteboard");
        DefaultMutableTreeNode p = agendaTree.addObject(null, title);
        for (int i = 0; i < base.getSessionManager().getSlideCount(); i++) {
            agendaTree.addObject(p, "Item " + (i + 1), true);
        }
        
    }

    public void addNewAgenda(String[] outline, String title) {

        if (outline == null) {
            return;
        }
        if (title == null) {
            title = "Slides";
        }
        if (title.trim().equals("")) {
            title = "";
        }
        agendaTree.clear();
        addAgenda(new String[0], "Whiteboard");
        DefaultMutableTreeNode p = agendaTree.addObject(null, title);

        for (int i = 0; i < outline.length; i++) {
            if (outline[i] == null) {
                agendaTree.addObject(p, "Item " + (i + 1), true);
            } else {
                agendaTree.addObject(p, outline[i], true);

            }
        }
       
    }

    public void addAgenda(String[] outline, String title) {

        if (outline == null) {
            return;
        }
        DefaultMutableTreeNode p = agendaTree.addObject(null, title);

        for (int i = 0; i < outline.length; i++) {
            if (outline[i] == null) {
                agendaTree.addObject(p, "Item " + (i + 1), true);
            } else {
                agendaTree.addObject(p, outline[i], true);

            }
        }
    }

    private void initComponents() {
        agendaTree = new AgendaTree(base);
        agendaTree.setPreferredSize(new Dimension(250, 200));
    }

    public AgendaTree getAgendaTree() {
        return agendaTree;
    }

    public void renameAgendaItem(String item, int index) {
        base.getTcpClient().sendAgendaItem(item, base.getSessionId(), index, base.getSessionManager().getSlideCount());

    }
}
