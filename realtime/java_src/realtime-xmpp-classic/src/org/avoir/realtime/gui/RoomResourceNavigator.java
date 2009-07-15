package org.avoir.realtime.gui;

/*
 * This code is based on an example provided by Richard Stanford, 
 * a tutorial reader.
 */
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.GridLayout;
import java.awt.Toolkit;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.util.Enumeration;
import javax.swing.ImageIcon;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTree;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeCellRenderer;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.MutableTreeNode;
import javax.swing.tree.TreeNode;
import javax.swing.tree.TreePath;
import javax.swing.tree.TreeSelectionModel;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.dnd.RoomResourceTreeTransferHandler;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

public class RoomResourceNavigator extends JPanel {

    protected DefaultMutableTreeNode rootNode;
    protected DefaultTreeModel treeModel;
    protected JTree tree;
    private Toolkit toolkit = Toolkit.getDefaultToolkit();
    private ImageIcon fileIcon = ImageUtil.createImageIcon(this, "/images/file_small.png");
    private ImageIcon urlIcon = ImageUtil.createImageIcon(this, "/images/link.gif");
    private ImageIcon qnIcon = ImageUtil.createImageIcon(this, "/images/question-small.jpg");

    public RoomResourceNavigator() {
        super(new GridLayout(1, 0));

        rootNode = new DefaultMutableTreeNode("Room Resources");
        treeModel = new DefaultTreeModel(rootNode);

        tree = new JTree(treeModel);
        tree.setDragEnabled(true);
        tree.setTransferHandler(new RoomResourceTreeTransferHandler());
        tree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
        tree.setShowsRootHandles(true);
        tree.setCellRenderer(new FileRenderer());
        tree.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                TreePath parentPath = tree.getSelectionPath();
                DefaultMutableTreeNode selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

                Object obj = selectedNode.getUserObject();
                if (obj instanceof RealtimeFile) {
                    RealtimeFile f = (RealtimeFile) obj;
                    DefaultMutableTreeNode parent = (DefaultMutableTreeNode) selectedNode.getParent();
                    Object pFileObj = parent.getUserObject();
                    if (pFileObj instanceof RealtimeFile) {
                        RealtimeFile pFile = (RealtimeFile) pFileObj;
                        RealtimePacket p = new RealtimePacket();
                        p.setMode(RealtimePacket.Mode.BROADCAST_SLIDE);
                        StringBuilder sb = new StringBuilder();
                        sb.append("<slide-show-name>").append(pFile.getFileName()).append("</slide-show-name>");
                        sb.append("<slide-title>").append(GeneralUtil.removeExt(f.getFileName())).append("</slide-title>");
                        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                        p.setContent(sb.toString());
                        ConnectionManager.sendPacket(p);
                    }
                }
            }
        });

        JScrollPane scrollPane = new JScrollPane(tree);
        add(scrollPane);
    }

    public void populateWithRoomResources() {
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String files[] = new File(resourceDir).list();
        clear();
        if (files != null) {

            for (int i = 0; i < files.length; i++) {

                String path = resourceDir + "/" + files[i];
                String filePath = GeneralUtil.readTextFile(resourceDir + "/" + files[i] + "/server_path.txt").trim();

                RealtimeFile f = new RealtimeFile(files[i], filePath, false, false);
                f.setSlideName(true);
                DefaultMutableTreeNode p = addResource(f);

                String[] slides = new File(path).list();
                for (String slide : slides) {
                    RealtimeFile rs = new RealtimeFile(slide, null, false, false);

                    if (slide.endsWith(".tr")) {

                        String trContent = GeneralUtil.readTextFile(resourceDir + "/" + files[i] + "/" + slide);
                        boolean isQuestionSlide = new Boolean(GeneralUtil.getTagText(trContent, "is-question-slide"));
                        boolean isImageSlide = new Boolean(GeneralUtil.getTagText(trContent, "is-image-slide"));
                        boolean isUrlSlide = new Boolean(GeneralUtil.getTagText(trContent, "is-url-slide"));
                        rs.setQuestionSlide(isQuestionSlide);
                        rs.setImageSlide(isImageSlide);
                        rs.setUrlSlide(isUrlSlide);
                        rs.setSlide(true);
                        addResource(p, rs);
                    }

                }


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

    public DefaultMutableTreeNode addResource(RealtimeFile resource) {
        DefaultMutableTreeNode treeNode = searchNode(resource.getFileName());
        if (treeNode != null) {
            removeNode(treeNode);
        }
        return addObject(rootNode, resource, true);
    }

    public DefaultMutableTreeNode addResource(DefaultMutableTreeNode parentNode, RealtimeFile resource) {

        return addObject(parentNode, resource, false);
    }

    public void removeResource(String resoruce) {
        DefaultMutableTreeNode treeNode = searchNode(resoruce);

        if (treeNode != null) {
            removeNode(treeNode);
        }
    }

    private MutableTreeNode getSibling(DefaultMutableTreeNode selNode) {
        //get previous sibling
        MutableTreeNode sibling = (MutableTreeNode) selNode.getPreviousSibling();

        if (sibling == null) {
            //if previous sibling is null, get the next sibling
            sibling = (MutableTreeNode) selNode.getNextSibling();
        }

        return sibling;
    }

    public void removeNode(DefaultMutableTreeNode selNode) {
        if (selNode != null) {
            //get the parent of the selected node
            MutableTreeNode parent = (MutableTreeNode) (selNode.getParent());

            // if the parent is not null
            if (parent != null) {
                //get the sibling node to be selected after removing the
                //selected node
                MutableTreeNode toBeSelNode = getSibling(selNode);

                //if there are no siblings select the parent node after removing the node
                if (toBeSelNode == null) {
                    toBeSelNode = parent;
                }

                //make the node visible by scroll to it
                TreeNode[] nodes = treeModel.getPathToRoot(toBeSelNode);
                TreePath path = new TreePath(nodes);
                tree.scrollPathToVisible(path);
                tree.setSelectionPath(path);

                //remove the node from the parent
                treeModel.removeNodeFromParent(selNode);
            }
        }
    }

    public DefaultMutableTreeNode searchNode(String nodeStr) {
        DefaultMutableTreeNode node = null;

        //Get the enumeration
        Enumeration xenum = rootNode.breadthFirstEnumeration();

        //iterate through the enumeration
        while (xenum.hasMoreElements()) {
            //get the node
            node = (DefaultMutableTreeNode) xenum.nextElement();
            Object obj = node.getUserObject();
            if (obj instanceof RealtimeFile) //match the string with the user-object of the node
            {
                RealtimeFile f = (RealtimeFile) obj;
                if (nodeStr.equals(f.getFileName())) {
                    //tree node with string found
                    return node;
                }
            }
        }

        //tree node with string node found return null
        return null;
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

    class FileRenderer extends DefaultTreeCellRenderer {

        @Override
        public Component getTreeCellRendererComponent(
                JTree tree,
                Object value,
                boolean sel,
                boolean expanded,
                boolean leaf,
                int row,
                boolean hasFocus) {

            super.getTreeCellRendererComponent(
                    tree, value, sel,
                    expanded, leaf, row,
                    hasFocus);
            DefaultMutableTreeNode node =
                    (DefaultMutableTreeNode) value;
            Object obj = node.getUserObject();
            setPreferredSize(new Dimension(150, 21));
            if (obj instanceof RealtimeFile) {

                RealtimeFile file = (RealtimeFile) obj;
                if (file.isSlide()) {
                    if (file.isQuestionSlide()) {
                        setIcon(qnIcon);
                    } else if (file.isSlide()) {
                        setIcon(fileIcon);
                    } else if (file.isUrlSlide()) {
                        setIcon(urlIcon);
                    } else {
                        setIcon(fileIcon);
                    }
                }
                setText(file.getFileName());
                setFont(new Font("Dialog", 0, 12));
            } else {
                setIcon(null);
                setFont(new Font("Dialog", 1, 12));
                setForeground(new Color(255, 153, 51));//new Color(102, 102, 255));
            }

            return this;
        }
    }
}
