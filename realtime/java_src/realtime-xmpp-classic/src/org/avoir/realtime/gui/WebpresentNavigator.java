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
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.util.Enumeration;
import javax.swing.ImageIcon;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JScrollPane;
import javax.swing.JTree;
import javax.swing.ListSelectionModel;
import javax.swing.event.TreeSelectionEvent;
import javax.swing.event.TreeSelectionListener;
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
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.providers.SlideShowProcessor;
import org.avoir.realtime.slidebuilder.Slide;

public class WebpresentNavigator extends JPanel implements ActionListener {

    protected DefaultMutableTreeNode rootNode;
    protected DefaultTreeModel treeModel;
    protected JTree tree;
    private Toolkit toolkit = Toolkit.getDefaultToolkit();
    private ImageIcon fileIcon = ImageUtil.createImageIcon(this, "/images/file_small.png");
    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem deleteItem = new JMenuItem("Remove Presentation");
    private JMenuItem clearItem = new JMenuItem("Clear Whiteboard");
    private JMenuItem refreshItem = new JMenuItem("Refresh");
    public static String selectedPresentation;
    public static int slideCount = 0;
    private boolean popuateLocalDone = false;
    private int selectedIndex = 0;
    private int currentSlideIndex = 0;

    public WebpresentNavigator() {
        super(new GridLayout(1, 0));

        rootNode = new DefaultMutableTreeNode("Slides");
        treeModel = new DefaultTreeModel(rootNode);

        tree = new JTree(treeModel);
        tree.setDragEnabled(true);
        tree.setTransferHandler(new RoomResourceTreeTransferHandler());
        tree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
        tree.setShowsRootHandles(true);

        tree.setCellRenderer(new FileRenderer());
        tree.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.getButton() == MouseEvent.BUTTON3) {
                    deleteItem.setEnabled(enableRemoveResource());
                    popup.show(tree, e.getX(), e.getY());
                } else {
                    displaySlide();
                }
            }
        });
        tree.addKeyListener(new KeyAdapter() {

            @Override
            public void keyPressed(KeyEvent e) {
            }
        });
        TreeSelectionModel listSelectionModel = tree.getSelectionModel();
        listSelectionModel.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        listSelectionModel.addTreeSelectionListener(new TreeSelectionListener() {

            public void valueChanged(TreeSelectionEvent e) {
                TreeSelectionModel lsm = (TreeSelectionModel) e.getSource();

                if (lsm.isSelectionEmpty()) {
                } else {
                    currentSlideIndex = lsm.getMinSelectionRow();
                    displaySlide();
                }
            }
        });
        JScrollPane scrollPane = new JScrollPane(tree);
        add(scrollPane);
        // populateWithRoomResources();
        deleteItem.addActionListener(this);
        deleteItem.setActionCommand("remove");

        clearItem.addActionListener(this);
        clearItem.setActionCommand("clear");

        refreshItem.addActionListener(this);
        refreshItem.setActionCommand("refresh");

        popup.add(refreshItem);
        popup.addSeparator();
        popup.add(deleteItem);
        popup.add(clearItem);
    }

    public String getSelectedPresentation() {
        return selectedPresentation;
    }

    private boolean enableRemoveResource() {
        TreePath parentPath = tree.getSelectionPath();
        if (parentPath != null) {
            DefaultMutableTreeNode selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

            Object obj = selectedNode.getUserObject();
            boolean state = false;
            if (obj instanceof RealtimeFile) {
                RealtimeFile file = (RealtimeFile) obj;
                state = file.isSlideName();
            }
            return state;
        }
        return false;
    }

    public void removeRoomResource() {
        TreePath parentPath = tree.getSelectionPath();
        //if (parentPath != null) {
        DefaultMutableTreeNode selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

        Object obj = selectedNode.getUserObject();
        if (obj instanceof RealtimeFile) {
            RealtimeFile file = (RealtimeFile) obj;
            String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
            String targetDir = resourceDir.trim() + "/" + file.getFileName().trim() + "/server_path.txt";
            /// JOptionPane.showInputDialog("debug", targetDir);
            String serverPath = GeneralUtil.readTextFile(targetDir);

            String filePath = file.getFilePath();
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.DELETE_ROOM_RESOURCE);
            StringBuilder sb = new StringBuilder();
            sb.append("<local-file-path>").append(filePath).append("</local-file-path>");
            sb.append("<server-file-path>").append(serverPath).append("</server-file-path>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
        }
    //}
    }

    public void actionPerformed(ActionEvent evt) {
        if (evt.getActionCommand().equals("clear")) {
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideImage(null);
        }
        if (evt.getActionCommand().equals("remove")) {
            removeRoomResource();
        }
        if (evt.getActionCommand().equals("refresh")) {
            popuateLocalDone = false;
            populateWithRoomResources();
        }
    }

    public void resetSlideCount() {
        slideCount = 0;
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String files[] = new File(resourceDir + "/" + WebpresentNavigator.selectedPresentation.replaceAll("\n", "")).list();
        if (files != null) {
            slideCount = 0;
            for (int i = 0; i < files.length; i++) {

                if (!files[i].endsWith(".tr") && !files[i].endsWith(".txt")) {
                    slideCount++;

                }
            }
        }
    }

    public int getCurrentSlideIndex() {
        return currentSlideIndex;
    }

    public static int getSlideCount() {
        return slideCount;
    }

    public void moveToNextSlide() {

        if (currentSlideIndex < slideCount) {
            currentSlideIndex++;
            sendPacket(selectedPresentation, "Slide " + currentSlideIndex);
        }
    }

    public void moveToPrevSlide() {
        if (currentSlideIndex > 1) {
            currentSlideIndex--;
            sendPacket(selectedPresentation, "Slide " + currentSlideIndex);
        }
    }

    private void displaySlide() {
        TreePath parentPath = tree.getSelectionPath();
        if (parentPath != null) {
            DefaultMutableTreeNode selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

            Object obj = selectedNode.getUserObject();
            if (obj instanceof RealtimeFile) {
                RealtimeFile f = (RealtimeFile) obj;
                DefaultMutableTreeNode parent = (DefaultMutableTreeNode) selectedNode.getParent();
                Object pFileObj = parent.getUserObject();
                if (pFileObj instanceof RealtimeFile) {
                    RealtimeFile pFile = (RealtimeFile) pFileObj;
                    sendPacket(pFile.getName(), GeneralUtil.removeExt(f.getFileName()));
                    selectedPresentation = pFile.getName();
                    resetSlideCount();
                } else {
                    resetSlideCount();
                }
            }
        }
    }

    public void sendPacket(String presentationName, String slideName) {
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().clearWhiteboard();
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.BROADCAST_SLIDE);
        StringBuilder sb = new StringBuilder();
        sb.append("<slide-show-name>").append(presentationName).append("</slide-show-name>");
        sb.append("<slide-title>").append(slideName).append("</slide-title>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    /** Remove all nodes except the root node. */
    public void clear() {
        rootNode.removeAllChildren();
        treeModel.reload();
    }

    public void populateWithRoomResources() {
        if (popuateLocalDone) {
            return;
        }
        clear();
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;

        String files[] = new File(resourceDir).list();
        String standByPresentationId = "";
        java.util.Arrays.sort(files);
        if (files != null) {

            for (int i = 0; i < files.length; i++) {
                String path = resourceDir + "/" + files[i];
                String presentationName = GeneralUtil.readTextFile(path + "/presentationname.txt");
                standByPresentationId = files[i];
                RealtimeFile f = new RealtimeFile(presentationName == null ? files[i] : presentationName, path, false, false);
                f.setSlideName(true);
                f.setName(files[i]);

                DefaultMutableTreeNode p = addResource(f);

                String[] slides = new File(path).list();
                if (i == 0) {

                    WebPresentManager.presentationId = standByPresentationId;
                    SlideShowProcessor.displayInitialSlide(WebPresentManager.presentationId);
                    selectedPresentation = presentationName;
                    popuateLocalDone = true;
                    slideCount = slides.length;
                }
                if (slides != null) {
                    int slideNo = 0;
                    for (int j = 0; j < slides.length; j++) {

                        if (!slides[j].endsWith(".tr") && !slides[j].endsWith(".txt")) {
                            String trExt = ".tr";
                            String slideTranscriptPath = resourceDir + "/" + files[i] + "/Slide " + slideNo + trExt;
                            Slide slide = SlideShowProcessor.getSlideFromFile(slideTranscriptPath);
                            String slideTitle = slide == null ? "" : " - " + slide.getText();
                            RealtimeFile rs = new RealtimeFile("Slide " + (slideNo + 1), null, false, false);
                            rs.setName(slideTitle.equals(" - null") ? "" : slideTitle);
                            rs.setSlide(true);
                            addResource(p, rs);
                            /*Image img=GeneralUtil.getScaledImage(new ImageIcon(resourceDir + "/" + files[i] + "/Slide " + slideNo).getImage(), 50,50);
                            GUIAccessManager.mf.getWhiteboardPanel().
                            getWhiteboard().addThumbNail(img, i, slideNo, true, files[i], "Slide "+slideNo);

                             */ slideNo++;


                        }
                    }
                }


            }
        }


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
///setToolTipText(file.getName()+" :) ");
                    setIcon(fileIcon);
                }
                String name = file.getName() == null ? "" : (file.getName().equals("null") ? "" : file.getName());
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
