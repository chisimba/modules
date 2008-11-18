/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;


import java.awt.Dimension;
import javax.swing.tree.DefaultMutableTreeNode;

/**
 *
 * @author developer
 */
public class ArchiveManager {

    private Classroom mf;
    private DynamicTree archiveTree;
    public ArchiveManager(Classroom mf) {
        this.mf=mf;
        initComponents();
    }

    public void addDefaultArchive(String title) {
        archiveTree.clear();
        if (title == null) {
            title = "Slides";
        }
        if (title.trim().equals("")) {
            title = "";
        }
        addArchive(new String[0], "Whiteboard");
        DefaultMutableTreeNode p = archiveTree.addObject(null, new Slide(-1, "", title, 0, true));
        mf.setSelectedFile(title);
        for (int i = 0; i <mf.getSessionManager().getSlideCount(); i++) {

            archiveTree.addObject(p, new Slide(i, "Item " + (i + 1), title, mf.getSessionManager().getSlideCount(), true), false);
        }

    }

    public void addArchive(String[] outline, String title) {

        if (outline == null) {
            return;
        }
        DefaultMutableTreeNode p = archiveTree.addObject(null, new Slide(-1, "", title, 0, false));
        mf.setSelectedFile(title);
        for (int i = 0; i < outline.length; i++) {
            if (outline[i] == null) {
                archiveTree.addObject(p, new Slide(i + 1, "Item" + (1 + 1), title, outline.length, false), false);
            } else {
                archiveTree.addObject(p, new Slide(i, outline[i], title, outline.length, false), false);

            }
        }
    }

    private void initComponents() {
        archiveTree = new DynamicTree(mf);
        archiveTree.setPreferredSize(new Dimension(250, 200));
    }

    public DynamicTree getArchiveTree() {
        return archiveTree;
    }

    public void renameArchiveItem(String item, int index) {
   
    }
}
