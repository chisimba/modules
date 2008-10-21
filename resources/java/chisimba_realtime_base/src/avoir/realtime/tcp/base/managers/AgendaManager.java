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
    private DynamicTree agendaTree;

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
        DefaultMutableTreeNode p = agendaTree.addObject(null, new Slide(-1, "", title, 0, true));
        base.setSelectedFile(title);
        for (int i = 0; i < base.getSessionManager().getSlideCount(); i++) {

            agendaTree.addObject(p, new Slide(i, "Item " + (i + 1), title, base.getSessionManager().getSlideCount(), true), false);
        }

    }

    public void addAgenda(String[] outline, String title) {

        if (outline == null) {
            return;
        }
        DefaultMutableTreeNode p = agendaTree.addObject(null, new Slide(-1, "", title, 0, false));
        base.setSelectedFile(title);
        for (int i = 0; i < outline.length; i++) {
            if (outline[i] == null) {
                agendaTree.addObject(p, new Slide(i + 1, "Item" + (1 + 1), title, outline.length, false), false);
            } else {
                agendaTree.addObject(p, new Slide(i, outline[i], title, outline.length, false), false);

            }
        }
    }

    private void initComponents() {
        agendaTree = new DynamicTree(base);
        agendaTree.setPreferredSize(new Dimension(250, 200));
    }

    public DynamicTree getAgendaTree() {
        return agendaTree;
    }

    public void renameAgendaItem(String item, int index) {
        base.getTcpClient().sendAgendaItem(item, base.getSessionId(), index, base.getSessionManager().getSlideCount());

    }
}
