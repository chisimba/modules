/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui;

//package components;

/*
 * This code is based on an example provided by Richard Stanford,
 * a tutorial reader.
 */
import org.avoir.realtime.questions.*;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.util.ArrayList;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JScrollPane;
import javax.swing.JTree;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeCellRenderer;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.MutableTreeNode;
import javax.swing.tree.TreePath;
import javax.swing.tree.TreeSelectionModel;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.FileChooserListener;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.slidebuilder.SlideBuilderManager;
import org.jivesoftware.smack.util.Base64;

public class Navigator extends JPanel implements ActionListener, FileChooserListener {

    protected DefaultMutableTreeNode rootNode;
    protected DefaultTreeModel treeModel;
    protected JTree tree;
    private Toolkit toolkit = Toolkit.getDefaultToolkit();
    private DefaultMutableTreeNode selectedNode;
    private JButton closeButton = new JButton(ImageUtil.createImageIcon(this, "/images/cross16.png"));
    private JButton addButton = new JButton(ImageUtil.createImageIcon(this, "/images/add.png"));
    private ImageIcon privIcon = ImageUtil.createImageIcon(this, "/images/im_unavailable.png");
    private ImageIcon fileIcon = ImageUtil.createImageIcon(this, "/images/file.png");
    private ImageIcon pubIcon = ImageUtil.createImageIcon(this, "/images/im_available_stale.png");
    private ImageIcon resourceIcon = ImageUtil.createImageIcon(this, "/images/im_available.png");
    private JButton refreshButton = new JButton(ImageUtil.createImageIcon(this, "/images/refresh.png"));
    private String mode = "slideshows";
    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem newItem = new JMenuItem("New");
    private JMenuItem editItem = new JMenuItem("Open");
    private JMenuItem deleteItem = new JMenuItem("Delete");
    private JMenuItem publicItem = new JMenuItem("Make Public");
    private JMenuItem addResourceItem = new JMenuItem("Add As Classroom Resource");
    private JMenuItem removeResourceItem = new JMenuItem("Remove As Classroom Resource");
    protected MainFrame mf;
    protected Dimension ss = toolkit.getScreenSize();
    protected DefaultMutableTreeNode privateNode;
    protected DefaultMutableTreeNode publicNode;
    protected DefaultMutableTreeNode resourceNode;
    private QuestionFrame questionFrame = null;
    private ArrayList<RealtimeFile> roomResources = new ArrayList<RealtimeFile>();

    public Navigator(MainFrame xmf, String xmode, String title) {
        super(new BorderLayout());
        this.mf = xmf;
        this.mode = xmode;
        rootNode = new DefaultMutableTreeNode(title);
        treeModel = new DefaultTreeModel(rootNode);
        popup.add(newItem);
        popup.add(editItem);
        popup.add(deleteItem);
        popup.addSeparator();
        popup.add(publicItem);
        popup.addSeparator();
        popup.add(addResourceItem);
        popup.add(removeResourceItem);


        tree = new JTree();
        tree.setModel(treeModel);

        tree.setCellRenderer(new FileRenderer());
        newItem.addActionListener(this);
        newItem.setActionCommand("new");
        deleteItem.addActionListener(this);
        deleteItem.setActionCommand("delete");
        editItem.addActionListener(this);
        editItem.setActionCommand("open");

        publicItem.addActionListener(this);
        publicItem.setActionCommand("make-public");

        addResourceItem.addActionListener(this);
        addResourceItem.setActionCommand("add-resource");
        addResourceItem.setEnabled(false);

        removeResourceItem.addActionListener(this);
        removeResourceItem.setActionCommand("remove-resource");
        removeResourceItem.setEnabled(false);

        tree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
        tree.setShowsRootHandles(true);
        tree.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                TreePath parentPath = tree.getSelectionPath();
                try {
                    selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

                    Object obj = selectedNode.getUserObject();
                    if (obj instanceof RealtimeFile) {
                        RealtimeFile f = (RealtimeFile) obj;
                        if (f.isSlide()) {
                            DefaultMutableTreeNode parent = (DefaultMutableTreeNode) selectedNode.getParent();
                            RealtimeFile pFile = (RealtimeFile) parent.getUserObject();

                            RealtimePacket p = new RealtimePacket();
                            p.setMode(RealtimePacket.Mode.BROADCAST_SLIDE);
                            StringBuilder sb = new StringBuilder();
                            sb.append("<slide-show-name>").append(pFile.getFileName()).append("</slide-show-name>");
                            sb.append("<slide-title>").append(GeneralUtil.removeExt(f.getFileName())).append("</slide-title>");
                            p.setContent(sb.toString());
                            ConnectionManager.sendPacket(p);
                            return;
                        }
                    }
                    if (e.getButton() == e.BUTTON3) {
                        if (selectedNodeIsEditable() && !selectedNodeIsResource()) {
                            publicItem.setEnabled(selectedNodeIsPrivate());
                            deleteItem.setEnabled(selectedNodeIsEditable());
                            addResourceItem.setEnabled(!selectedNodeIsResource());
                            popup.show(Navigator.this, e.getX(), e.getY());
                        }
                        return;
                    }
                    if (e.getClickCount() == 2) {
                        if (obj instanceof String) {
                            String str = (String) obj;
                            if (str.equals("Clear")) {
                            }
                        }
                        if (obj instanceof RealtimeFile) {
                            RealtimeFile f = (RealtimeFile) obj;
                            if (!f.isSlideName() || !f.isSlide()) {
                                if (mode.equals("questions")) {
                                    openQuestion((RealtimeFile) obj);
                                }
                            }
                            if (mode.equals("slideshows")) {
                                openSlideShow((RealtimeFile) obj);
                            }
                        }
                    }
                } catch (Exception ex) {
                    System.out.println(getClass() + ": Error at   " + ex.getStackTrace()[2].getLineNumber());

                }
            }
        });

        addButton.setBorderPainted(false);
        addButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                addButton.setContentAreaFilled(true);
            }
        });
        addButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                processAdd();
            }
        });

        closeButton.setBorderPainted(false);
        closeButton.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseEntered(MouseEvent e) {
                closeButton.setContentAreaFilled(true);
            }
        });
        closeButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                mf.removeNavigator();
            }
        });
        refreshButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (mode.equals("slideshows")) {
                }
                if (mode.equals("questions")) {
                }
            }
        });

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
        //p.add(closeButton);
        add(p, BorderLayout.NORTH);
        add(scrollPane, BorderLayout.CENTER);
        addAccessTypes();
    /*
    if (mode.equals("questions")) {
    populateNodes("questions");
    }
    if (mode.equals("slideshows")) {
    populateNodes("slideshows");

    }
     */
    }

    public ArrayList<RealtimeFile> getRoomResources() {
        return roomResources;
    }

    private void addAccessTypes() {
        privateNode = addObject(rootNode, "Mine", true);
        publicNode = addObject(rootNode, "Public", true);
    //if (mode.equals("slideshows")) {
    //  resourceNode = addObject(rootNode, "Current Room Resource", true);
    // }
    }

    private void openQuestion(RealtimeFile file) {
        String access = "private";
        if (file.isPublicAccessible()) {
            access = "public";
        }
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.OPEN_QUESTION);
        StringBuilder buf = new StringBuilder();
        buf.append("<question-path>").append(file.getFilePath()).append("</question-path>");
        buf.append("<filename>").append(file.getFileName()).append("</filename>");
        buf.append("<access>").append(access).append("</access>");
        buf.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        p.setContent(buf.toString());
        ConnectionManager.sendPacket(p);
    }

    private void openSlideShow(RealtimeFile file) {
        String access = "private";
        if (file.isPublicAccessible()) {
            access = "public";
        }
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.OPEN_SLIDE_SHOW);
        StringBuilder buf = new StringBuilder();
        buf.append("<slide-show-path>").append(file.getFilePath()).append("</slide-show-path>");
        buf.append("<filename>").append(file.getFileName()).append("</filename>");
        buf.append("<access>").append(access).append("</access>");
        buf.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        p.setContent(buf.toString());
        ConnectionManager.sendPacket(p);
    }

    public void populateNodes(String fileType) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_FILE_VIEW);
        StringBuilder sb = new StringBuilder();
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<room-name>").append(ChatRoomManager.currentRoomName).append("</room-name>");
        sb.append("<file-type>").append(fileType).append("</file-type>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);

    }

    public void processResourceFileView(ArrayList<RealtimeFile> fileView) {
        resourceNode.removeAllChildren();
        treeModel.reload();
        for (int i = 0; i < fileView.size(); i++) {
            addObject(resourceNode, fileView.get(i), true);
        }
    }

    public void processFileView(ArrayList<RealtimeFile> files) {
        clear();
        addAccessTypes();

        for (int i = 0; i < files.size(); i++) {

            if (files.get(i).isPublicAccessible()) {
                addObject(publicNode, files.get(i), true);
            } else {
                addObject(privateNode, files.get(i), true);
            }
        }
        if (mode.equals("slideshows")) {
            populateWithRoomResources();
        }
    }

    public void enablePostButton() {
        questionFrame.getPostButton().setEnabled(true);
        questionFrame.getPostButton().setIcon(null);
    }

    public void showQuestionFrame(RealtimeQuestionPacket p) {
        if (questionFrame == null) {
            questionFrame = new QuestionFrame();
        }
        questionFrame.getQuestion().setQuestion(p.getQuestion());
        questionFrame.setQuestionName(p.getFilename());

        questionFrame.getQuestion().setType(p.getQuestionType());
        questionFrame.getQuestion().setAnswerOptions(p.getAnswerOptions());
        questionFrame.setAccess(p.getAccess());
        String imagePath = p.getImagePath();
        questionFrame.setQuestionImagePath(imagePath);
        ImageIcon image = new ImageIcon(Base64.decode(p.getImageData()));
        questionFrame.setQuestionImage(image);
        questionFrame.populateFields();
        questionFrame.setSize((ss.width / 8) * 7, (ss.height / 8) * 7);
        questionFrame.setLocationRelativeTo(null);
        questionFrame.setVisible(true);

    }

    private void processAdd() {
        if (mode.equals("questions")) {
            QuestionFrame fr = new QuestionFrame();
            fr.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }
        if (mode.equals("slideshows")) {
            SlideBuilderManager fr = new SlideBuilderManager();
            fr.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }
    }

    private boolean selectedNodeIsEditable() {
        TreePath currentSelection = tree.getSelectionPath();

        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            if (parent != null) {
                Object obj = currentNode.getUserObject();
                if (obj instanceof RealtimeFile) {
                    return true;
                }
            }
        }
        return false;

    }

    private boolean selectedNodeIsPrivate() {
        TreePath currentSelection = tree.getSelectionPath();

        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            if (parent != null) {
                Object obj = currentNode.getUserObject();
                if (obj instanceof RealtimeFile) {
                    RealtimeFile f = (RealtimeFile) obj;
                    return !f.isPublicAccessible();
                }
            }
        }
        return false;

    }

    private boolean selectedNodeIsResource() {
        TreePath currentSelection = tree.getSelectionPath();

        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            return parent == resourceNode;
        }
        return false;

    }

    private void processEdit() {
        TreePath currentSelection = tree.getSelectionPath();
        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            if (parent != null) {
                Object obj = currentNode.getUserObject();

            }
        }
    }

    private void populateWithRoomResources() {
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String files[] = new File(resourceDir).list();
        roomResources.clear();
        if (files != null) {

            for (int i = 0; i < files.length; i++) {
                
                String path = resourceDir + "/" + files[i];
                String filePath = GeneralUtil.readTextFile(resourceDir + "/" + files[i] + "/server_path.txt").trim();

                RealtimeFile f = new RealtimeFile(files[i], filePath, false, false);
                f.setSlideName(true);
                DefaultMutableTreeNode p = mf.getRoomResourceNavigator().addResource(f);
                roomResources.add(f);
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
                        mf.getRoomResourceNavigator().addResource(p, rs);
                    }

                }


            }
        }

    }

    private void processDelete() {
        TreePath currentSelection = tree.getSelectionPath();
        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            if (parent != null) {
                Object obj = currentNode.getUserObject();

            }
        }

    }

    private void changeAccess(String type) {
        DefaultMutableTreeNode node = getCurrentNode();
        if (node != null) {
            RealtimeFile selectedFile = (RealtimeFile) node.getUserObject();
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.CHANGE_ACCESS);
            StringBuilder sb = new StringBuilder();
            sb.append("<access>").append(type).append("</access>");
            sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
            sb.append("<filename>").append(selectedFile.getFileName()).append("</filename>");
            sb.append("<file-type>").append(mode).append("</file-type>");
            p.setContent(sb.toString());
            ConnectionManager.getConnection().sendPacket(p);
        }
    }

    private void addSelectedAsRoomResource() {
        TreePath currentSelection = tree.getSelectionPath();

        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            if (parent != null) {
                Object obj = currentNode.getUserObject();
                if (obj instanceof RealtimeFile) {
                    RealtimeFile file = (RealtimeFile) obj;
                    RealtimePacket p = new RealtimePacket();
                    p.setMode(RealtimePacket.Mode.ADD_SLIDE_SHOW_CLASS_RESOURCE);
                    StringBuilder sb = new StringBuilder();
                    sb.append("<file-name>").append(file.getFileName()).append("</file-name>");
                    sb.append("<room-name>").append(ChatRoomManager.currentRoomName).append("</room-name>");
                    sb.append("<file-path>").append(file.getFilePath()).append("</file-path>");
                    p.setContent(sb.toString());
                    ConnectionManager.sendPacket(p);
                }
            }
        }


    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("add-resource")) {
            addSelectedAsRoomResource();
        }
        if (e.getActionCommand().equals("make-public")) {
            changeAccess("public");
        }
        if (e.getActionCommand().equals("new")) {
            processAdd();
        }
        if (e.getActionCommand().equals("delete")) {
            processDelete();
        }
        if (e.getActionCommand().equals("open")) {
            DefaultMutableTreeNode node = getCurrentNode();
            if (node != null) {
                Object obj = node.getUserObject();
                if (mode.equals("questions")) {
                    openQuestion((RealtimeFile) obj);
                }

                if (mode.equals("slideshows")) {
                    openSlideShow((RealtimeFile) obj);
                }
            }
        }
    }

    public void setRootText(String text) {
        rootNode.setUserObject(text);

    }

    public String getMode() {
        return mode;
    }

    public void setMode(String mode) {
        this.mode = mode;
    }

    /** Remove all nodes except the root node. */
    public void clear() {
        rootNode.removeAllChildren();
        treeModel.reload();
    }

    public DefaultMutableTreeNode getCurrentNode() {
        TreePath currentSelection = tree.getSelectionPath();
        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            return currentNode;
        }
        return null;
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
                if (file.isPublicAccessible()) {
                    setIcon(pubIcon);
                } else if (file.isSlide()) {
                    setIcon(fileIcon);
                } else if (file.isSlideName()) {
                    setIcon(resourceIcon);
                } else {
                    setIcon(privIcon);
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
