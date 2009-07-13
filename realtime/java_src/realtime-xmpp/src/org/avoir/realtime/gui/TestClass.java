/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui;

import com.l2fprod.common.swing.JTaskPane;
import com.l2fprod.common.swing.JTaskPaneGroup;
import com.pagosoft.plaf.PgsLookAndFeel;
import javax.swing.JFrame;
import org.avoir.realtime.chat.ChatRoom;

/**
 *
 * @author david
 */
public class TestClass {

    public TestClass() {
        JTaskPane taskPane = new JTaskPane();
        JTaskPaneGroup group1 = new JTaskPaneGroup();
        group1.setTitle("Participants");
        taskPane.add(group1);
        JTaskPaneGroup group2 = new JTaskPaneGroup();
        group2.setTitle("Presence");
        taskPane.add(group2);

        JTaskPaneGroup group3 = new JTaskPaneGroup();
        group3.setTitle("Chat");
        taskPane.add(group3);
        group3.add(new ChatRoom());

        JTaskPaneGroup roomResourcesGroup = new JTaskPaneGroup();
        roomResourcesGroup.setTitle("Room Resources");
        taskPane.add(roomResourcesGroup);
        roomResourcesGroup.setExpanded(false);

        JTaskPaneGroup group4 = new JTaskPaneGroup();
        group4.setTitle("Audio/Video");
        taskPane.add(group4);
        group4.setExpanded(false);



        JFrame fr = new JFrame();
        fr.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        fr.setContentPane(taskPane);
        fr.setSize(400, 300);
        fr.setVisible(true);
    /* JXCollapsiblePane cp = new JXCollapsiblePane(Direction.UP);
    //cp.getContentPane().setBackground(Color.RED);
    // JXCollapsiblePane can be used like any other container

    cp.setLayout(new BorderLayout());

    // the Controls panel with a textfield to filter the tree
    JPanel controls = new JPanel(new FlowLayout(FlowLayout.LEFT, 4, 0));
    controls.add(new JButton("Hand"));
    controls.add(new JButton("Laugh"));
    controls.add(new JButton("Applaud"));
    //controls.setBorder(new TitledBorder("Filters"));
    cp.add("Center", controls);

    JFrame frame = new JFrame();
    frame.setLayout(new BorderLayout());

    // Put the "Controls" first
    //frame.add("North", cp);

    JPanel bgp = new JPanel();
    bgp.setPreferredSize(new Dimension(200, 200));
    bgp.setBorder(BorderFactory.createEtchedBorder());
    JPanel p = new JPanel(new BorderLayout());
    cp.setBorder(BorderFactory.createEtchedBorder());
    p.add(cp, BorderLayout.NORTH);
    p.add(bgp, BorderLayout.CENTER);

    frame.add("Center", p);


    // Show/hide the "Controls"
    JButton toggle = new JButton(cp.getActionMap().get(JXCollapsiblePane.TOGGLE_ACTION));
    toggle.setText("Show/Hide Search Panel");
    frame.add("North", toggle);
    frame.setSize(300, 300);
    frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    frame.setBackground(Color.RED);
    frame.pack();
    frame.setVisible(true);
     */
    }

    public static void main(String args[]) {
        PgsLookAndFeel.setAsLookAndFeel();
        new TestClass();
    }
}
