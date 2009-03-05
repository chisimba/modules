/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor;

//package components;

/*
 * This code is based on an example provided by Richard Stanford,
 * a tutorial reader.
 */
import avoir.realtime.common.BuilderSlide;
import avoir.realtime.common.Constants;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.RealtimeFile;
import avoir.realtime.common.XmlUtil;
import avoir.realtime.common.packet.FileDownloadRequest;
import avoir.realtime.common.packet.FileVewRequestPacket;
import avoir.realtime.common.packet.QuestionPacket;
import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.instructor.whiteboard.Classroom;
import java.awt.BorderLayout;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.util.ArrayList;
import javax.swing.JButton;
import javax.swing.JOptionPane;
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

public class SlideShowNavigator extends JPanel {

    protected DefaultMutableTreeNode rootNode;
    protected DefaultTreeModel treeModel;
    protected JTree tree;
    private Toolkit toolkit = Toolkit.getDefaultToolkit();
    private Classroom mf;
    private DefaultMutableTreeNode selectedNode;
    private JButton closeButton = new JButton(ImageUtil.createImageIcon(this, "/icons/cross16.png"));
    private JButton addButton = new JButton(ImageUtil.createImageIcon(this, "/icons/add.png"));
    private JButton refreshButton = new JButton(ImageUtil.createImageIcon(this, "/icons/refresh.png"));
    private String mode = "slide-show";

    public SlideShowNavigator(Classroom xmf) {
        super(new BorderLayout());
        this.mf = xmf;
        rootNode = new DefaultMutableTreeNode("Slide Show Navigator");
        treeModel = new DefaultTreeModel(rootNode);

        tree = new JTree(treeModel);
        //    tree.setEditable(true);
        tree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
        tree.setShowsRootHandles(true);
        tree.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                TreePath parentPath = tree.getSelectionPath();

                selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
                Object obj = selectedNode.getUserObject();

                if (obj instanceof BuilderSlide) {
                    BuilderSlide slide = (BuilderSlide) obj;
                    mf.getWhiteboard().setSlideTextXPos(slide.getTextXPos());
                    mf.getWhiteboard().setSlideTextYPos(slide.getTextYPos());
                    mf.getWhiteboard().setSlideText(slide.getText());
                    mf.getWhiteboard().setTextColor(slide.getTextColor());
                    mf.getWhiteboard().setCurrentSlide(slide.getImage(), slide.getIndex(), 0, true);
                    XmlQuestionPacket qn = slide.getQuestion();

                    if (qn.getType() != -1) {
                        mf.initAnswerFrame(qn.getQuestionPacket(), qn.getName(),true);
                    }

                }
                if (e.getClickCount() == 2) {
                    if (obj instanceof RealtimeFile) {
                        RealtimeFile file = (RealtimeFile) obj;
                        String targetPath = file.getPath();
                        File f = new File(Constants.REALTIME_HOME + "/classroom/documents/" + mf.getUser().getSessionId() + "/" + new File(targetPath).getName());
                        if (f.exists()) {
                            if (mode.equals("slide-show")) {
                                ArrayList<BuilderSlide> list = XmlUtil.readXmlSlideShowFile(f.getAbsolutePath());
                                mf.getSlideShowNavigator().addSlides(list);
                            }
                            if (mode.equals("question-manager")) {
                                XmlQuestionPacket pac = XmlUtil.readXmlQuestionFile(f.getAbsolutePath());
                                mf.initAnswerFrame(pac.getQuestionPacket(), pac.getName(),true);
                            }
                        } else {
                            if (mode.equals("slide-show")) {
                                mf.getTcpConnector().sendPacket(new FileDownloadRequest(targetPath, Constants.SLIDE_SHOW_NAV));
                            }
                            if (mode.equals("question-manager")) {
                                mf.getTcpConnector().sendPacket(new FileDownloadRequest(targetPath, Constants.QUESTION_NAV));
                            }
                            mf.getTcpConnector().setSelectedFilePath(targetPath);
                        }
                    }

                }
            }
        });
        //addButton.setContentAreaFilled(false);
        addButton.setBorderPainted(false);
        addButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                addButton.setContentAreaFilled(true);
            }
        });
        addButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (mode.equals("slide-show")) {
                    mf.showSlideBuilder();
                }
                if (mode.equals("question-manager")) {
                    mf.showQuestionsManager();
                }
            }
        });
        // closeButton.setContentAreaFilled(false);
        closeButton.setBorderPainted(false);
        closeButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                closeButton.setContentAreaFilled(true);
            }
        });
        closeButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                mf.setSlideShowNavigatorVisible(false, "");
            }
        });
        refreshButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (mode.equals("slide-show")) {
                    mf.getTcpConnector().setFileManagerMode("slide-show-list");
                    String targetPath = mf.getUser().getUserName() + "/slides";
                    mf.getTcpConnector().sendPacket(new FileVewRequestPacket(targetPath));
                }
                if (mode.equals("question-manager")) {
                 //   mf.getTcpConnector().setFileManagerMode("question-list");
                    String targetPath = mf.getUser().getUserName() + "/questions";
                    mf.getTcpConnector().sendPacket(new FileVewRequestPacket(targetPath));
                }
            }
        });
        //refreshButton.setContentAreaFilled(false);
        refreshButton.setBorderPainted(false);
        refreshButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                refreshButton.setContentAreaFilled(true);
            }
        });
        JScrollPane scrollPane = new JScrollPane(tree);
        JPanel p = new JPanel();
        p.add(addButton);
        p.add(refreshButton);
        p.add(closeButton);
        add(p, BorderLayout.NORTH);
        add(scrollPane, BorderLayout.CENTER);
    }

    public void setRootText(String text) {
        rootNode.setUserObject(text);
    }

    public void setFiles(ArrayList<RealtimeFile> files) {
        clear();
        for (int i = 0; i < files.size(); i++) {
            if (!files.get(i).getFileName().equals("..")) {
                addObject(null, files.get(i), true);
            }
        }
    }

    public String getMode() {
        return mode;
    }

    public void setMode(String mode) {
        this.mode = mode;
    }

    public void addSlides(ArrayList<BuilderSlide> slides) {
        if (selectedNode != null) {
            selectedNode.removeAllChildren();
            treeModel.reload();
            for (int i = 0; i < slides.size(); i++) {
                addObject(selectedNode, slides.get(i));
            }
        }
    }

    public void requestFileList() {
        String path = mf.getUser().getUserName() + "/slides";
        if (mode.equals("slide-show")) {
            mf.getTcpConnector().setFileManagerMode("slide-show-list");
        } else {
            mf.getTcpConnector().setFileManagerMode("question-nav-list");
            path = mf.getUser().getUserName() + "/questions";
        }
        mf.getTcpConnector().sendPacket(new FileVewRequestPacket(path));
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

            System.out.println("The user has finished editing the node.");
            System.out.println("New value: " + node.getUserObject());
        }

        public void treeNodesInserted(TreeModelEvent e) {
        }

        public void treeNodesRemoved(TreeModelEvent e) {
        }

        public void treeStructureChanged(TreeModelEvent e) {
        }
    }
}
