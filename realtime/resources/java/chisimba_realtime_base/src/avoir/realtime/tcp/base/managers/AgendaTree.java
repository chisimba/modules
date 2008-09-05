package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.Constants;
import java.awt.*;
import java.awt.event.*;


import javax.swing.*;
import javax.swing.event.TreeModelEvent;
import javax.swing.event.TreeModelListener;
import javax.swing.event.TreeSelectionEvent;
import javax.swing.event.TreeSelectionListener;
import javax.swing.tree.*;

public class AgendaTree extends JPanel {

    public DefaultMutableTreeNode rootNode;
    public DefaultTreeModel treeModel;
    protected JTree tree;
    private Toolkit toolkit = Toolkit.getDefaultToolkit();
    private RealtimeBase base;
    int prevIndex = 0;
    JPopupMenu msgpopup = new JPopupMenu();

    public AgendaTree(RealtimeBase base) {
        super(new GridLayout(1, 0));
        this.base = base;
        init();
    }

    public AgendaTree() {
        super(new GridLayout(1, 0));

        init();
    }

    private void init() {
        rootNode = new DefaultMutableTreeNode("Outline");
        treeModel = new DefaultTreeModel(rootNode);
        treeModel.addTreeModelListener(new MyTreeModelListener());
        JLabel warnLabel = new JLabel("Only presenter can make slides change!!");
        // warnLabel.setForeground(Color.RED);
        // warnLabel.setBackground(Color.WHITE);
        msgpopup.add(warnLabel);
        tree = new JTree(treeModel);
        tree.setEditable(base.isPresenter());
        tree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
        tree.setShowsRootHandles(true);

        TreeSelectionModel rowSM = tree.getSelectionModel();
        rowSM.addTreeSelectionListener(new TreeSelectionListener() {

            public void valueChanged(TreeSelectionEvent e) {
                TreeSelectionModel lsm = (TreeSelectionModel) e.getSource();
                if (lsm.isSelectionEmpty()) {
                } else {
                    TreePath parentPath = tree.getSelectionPath();

                    DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

                    Slide selectedNode = (Slide) node.getUserObject();
                    int index = selectedNode.getSlideIndex();
                    if (index > -1) {

                        if (node.isLeaf()) {
                            //index--;

                            //Object[] p = node.getUserObjectPath();
                            String presentationName = selectedNode.getPresentationName();
                            int dot = presentationName.lastIndexOf(".");
                            if (dot > 0) {
                                presentationName = presentationName.substring(0, dot);
                            }
                            base.setSelectedFile(presentationName);
                            String slidePath = "";
                            if (base.getMODE() == Constants.APPLET) {
                                slidePath = Constants.getRealtimeHome() + "/presentations/" + base.getSessionId() + "/img" + index + ".jpg";
                                base.getSessionManager().setCurrentSlide(index, base.isPresenter(), slidePath);
                            } else {
                                slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + presentationName + "/img" + index + ".jpg";
                                base.getSessionManager().setCurrentSlide(index, base.isPresenter(), slidePath);
                            }

                        }
                        base.getSessionManager().updateSlide(index);
                    }


                }
            }
        });


        JScrollPane scrollPane = new JScrollPane(tree);
        add(scrollPane);
    }

    public void processDoubleClick() {
    }

    public void setSelectedRow(int index) {
        tree.setSelectionRow(index);
    }

    /** Remove all nodes except the root node. */
    public void clear() {
        rootNode.removeAllChildren();
        treeModel.reload();
    }

    /** Remove the currently selected node. */
    public void removeCurrentNode() {
        TreePath currentSelection = tree.getSelectionPath();

        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());

            if (parent != null) {
                treeModel.removeNodeFromParent(currentNode);

                return;
            }
        }

        // Either there was no selection, or the root was selected.
        toolkit.beep();
    }

    /** Add child to the currently selected node. */
    public DefaultMutableTreeNode addObject(Object child) {
        DefaultMutableTreeNode parentNode = null;
        TreePath parentPath = tree.getSelectionPath();

        if (parentPath == null) {
            parentNode = rootNode;
        } else {
            parentNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
        }

        return addObject(parentNode, child, true);
    }

    public DefaultMutableTreeNode addObject(DefaultMutableTreeNode parent,
            Object child) {
        return addObject(parent, child, true);
    }

    public DefaultMutableTreeNode addObject(DefaultMutableTreeNode parent,
            Object child, boolean shouldBeVisible) {
        DefaultMutableTreeNode childNode = new DefaultMutableTreeNode(child);

        if (parent == null) {
            parent = rootNode;
        }

        treeModel.insertNodeInto(childNode, parent, parent.getChildCount());

        // Make sure the user can see the lovely new node.
        if (shouldBeVisible) {
            tree.scrollPathToVisible(new TreePath(childNode.getPath()));
        }

        return childNode;
    }

    class PopupListener extends MouseAdapter {

        public void mousePressed(MouseEvent e) {
            maybeShowPopup(e);
        }

        public void mouseReleased(MouseEvent e) {
            maybeShowPopup(e);
        }

        private void maybeShowPopup(MouseEvent e) {
            if (e.isPopupTrigger()) {
                //  popup.show(e.getComponent(), e.getX(), e.getY());
            }
        }
    }

    class MyTreeModelListener implements TreeModelListener {

        public void treeNodesChanged(TreeModelEvent e) {
            DefaultMutableTreeNode node;
            node = (DefaultMutableTreeNode) (e.getTreePath().getLastPathComponent());

            /*
             * If the event lists children, then the changed node is the child
             * of the node we've already gotten. Otherwise, the changed node and
             * the specified node are the same.
             */
            try {
                int index = e.getChildIndices()[0];
                node = (DefaultMutableTreeNode) (node.getChildAt(index));
                base.getAgendaManager().renameAgendaItem((String) node.getUserObject(), index - 1);
            } catch (NullPointerException exc) {
            }


        }

        public void treeNodesInserted(TreeModelEvent e) {
        }

        public void treeNodesRemoved(TreeModelEvent e) {
        }

        public void treeStructureChanged(TreeModelEvent e) {
        }
    }

    class MyRenderer extends DefaultTreeCellRenderer {

        Icon folderIcon;
        Icon greenFolderIcon;

        public MyRenderer(Icon icon1, Icon icon2) {
            folderIcon = icon1;
            greenFolderIcon = icon2;
        }

        public Component getTreeCellRendererComponent(JTree tree,
                Object value,
                boolean sel,
                boolean expanded,
                boolean leaf,
                int row,
                boolean hasFocus) {

            super.getTreeCellRendererComponent(tree, value, sel,
                    expanded, leaf, row,
                    hasFocus);
            if (leaf) {
                setIcon(greenFolderIcon);

            } else {
                setIcon(folderIcon);
            }

            return this;
        }
    }
}
