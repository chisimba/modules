package avoir.realtime.instructor.whiteboard;

/*
 * This code is based on an example provided by Richard Stanford, 
 * a tutorial reader.
 */
import avoir.realtime.common.Constants;
import avoir.realtime.common.packet.ClearSlidesPacket;
import java.awt.GridLayout;
import java.awt.Image;
import java.awt.Toolkit;
import javax.swing.ImageIcon;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTree;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.MutableTreeNode;
import javax.swing.tree.TreePath;
import javax.swing.tree.TreeSelectionModel;
import javax.swing.event.TreeModelEvent;
import javax.swing.event.TreeModelListener;
import javax.swing.event.TreeSelectionEvent;
import javax.swing.event.TreeSelectionListener;

public class DynamicTree extends JPanel {

    protected DefaultMutableTreeNode rootNode;
    protected DefaultTreeModel treeModel;
    protected JTree tree;
    private Toolkit toolkit = Toolkit.getDefaultToolkit();
    private Classroom mf;
    private String currentPresentationName = "";

    public DynamicTree(Classroom mf) {
        super(new GridLayout(1, 0));
        this.mf = mf;

        init();
    }

    public DynamicTree() {
        super(new GridLayout(1, 0));
        init();
    }

    public void init() {

        rootNode = new DefaultMutableTreeNode(new Slide(-1, "", "Archive List", 0, false));
        treeModel = new DefaultTreeModel(rootNode);

        tree = new JTree(treeModel);
        tree.setEditable(true);
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
                    if (selectedNode.getPresentationName().equals("Whiteboard")) {
                        mf.getWhiteboard().setCurrentSlide(null, 0, 0, false);
                        mf.getConnector().sendPacket(new ClearSlidesPacket(mf.getUser().getSessionId()));
                    }
                    if (index > -1) {

                        if (node.isLeaf()) {
                            //index--;

                            //Object[] p = node.getUserObjectPath();
                            String presentationName = selectedNode.getPresentationName();
                            int dot = presentationName.lastIndexOf(".");
                            if (dot > 0) {
                                presentationName = presentationName.substring(0, dot);
                            }

                            mf.getSessionManager().setSlideCount(selectedNode.getSlideCount());
                            String slidePath = "";
                            if (selectedNode.isWebPresent()) {

                                slidePath = Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId() + "/img" + index + ".jpg";
                                mf.getSessionManager().setCurrentSlide(index, mf.getUser().isPresenter(), slidePath);
                                showThumbNails(presentationName, Constants.getRealtimeHome() + "/presentations/" + mf.getUser().getSessionId());

                                mf.setSelectedFile(presentationName);
                            } else {
                                mf.getSessionManager().setSlideCount(selectedNode.getSlideCount());
                                slidePath = Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + presentationName + "/img" + index + ".jpg";
                                mf.getSessionManager().setCurrentSlide(index, mf.getUser().isPresenter(), slidePath);
                                showThumbNails(presentationName, Constants.getRealtimeHome() + "/classroom/slides/" + mf.getUser().getSessionId() + "/" + presentationName);
                                mf.setSelectedFile(presentationName);
                            }
                            mf.setWebPresent(selectedNode.isWebPresent());

                        }
                        mf.getSessionManager().updateParticipants(index);


                    }


                }
            }
        });

        JScrollPane scrollPane = new JScrollPane(tree);
        add(scrollPane);
    }

    private void showThumbNails(String presentationName, String path) {
        if (!currentPresentationName.equals(presentationName)) {
            currentPresentationName = presentationName;
            int[] slidesList = mf.getConnector().getImageFileNames(path);

            mf.getWhiteboard().clearThumbNails();
            for (int i = 0; i < slidesList.length; i++) {
                String imgPath = path + "/img" + slidesList[i] + ".jpg";
                Image srcImg = new ImageIcon(imgPath).getImage();
                mf.getWhiteboard().addThumbNail(
                        mf.getWhiteboard().getWhiteboardManager().getScaledImage(srcImg),
                        i, Constants.MAX_THUMBNAIL_INDEX, true);
            }
           
        }
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
        return addObject(parent, child, false);
    }

    public DefaultMutableTreeNode addObject(DefaultMutableTreeNode parent,
            Object child,
            boolean shouldBeVisible) {
        DefaultMutableTreeNode childNode =
                new DefaultMutableTreeNode(child);

        if (parent == null) {
            parent = rootNode;
        }

        //It is key to invoke this on the TreeModel, and NOT DefaultMutableTreeNode
        treeModel.insertNodeInto(childNode, parent,
                parent.getChildCount());

        //Make sure the user can see the lovely new node.
        if (shouldBeVisible) {
            tree.scrollPathToVisible(new TreePath(childNode.getPath()));
        }
        return childNode;
    }

    class MyTreeModelListener implements TreeModelListener {

        public void treeNodesChanged(TreeModelEvent e) {
            DefaultMutableTreeNode node;
            node = (DefaultMutableTreeNode) (e.getTreePath().getLastPathComponent());

            /*
             * If the event lists children, then the changed
             * node is the child of the node we've already
             * gotten.  Otherwise, the changed node and the
             * specified node are the same.
             */

            int index = e.getChildIndices()[0];
            node = (DefaultMutableTreeNode) (node.getChildAt(index));


        }

        public void treeNodesInserted(TreeModelEvent e) {
        }

        public void treeNodesRemoved(TreeModelEvent e) {
        }

        public void treeStructureChanged(TreeModelEvent e) {
        }
    }
}
