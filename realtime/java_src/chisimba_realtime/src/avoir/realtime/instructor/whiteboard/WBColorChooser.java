/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;

import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.*;
import javax.swing.event.*;

public class WBColorChooser extends JPanel
        implements ChangeListener {

    protected JColorChooser tcc;
    private static JFrame frame = new JFrame("Color Chooser");
    private Whiteboard wb;

    public WBColorChooser(final Whiteboard wb) {
        super(new BorderLayout());
        this.wb = wb;

        //Set up color chooser for setting text color
        tcc = new JColorChooser(wb.getColour());
        tcc.getSelectionModel().addChangeListener(this);
        tcc.setBorder(BorderFactory.createTitledBorder(
                "Choose Text Color"));

        JPanel cPanel = new JPanel();
        JButton okButton = new JButton("OK");
        okButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                wb.setColour(tcc.getColor());
                wb.setTextColor(tcc.getColor());
                frame.dispose();
            }
        });
        JButton cancelButton = new JButton("Cancel");
        cancelButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                frame.dispose();
            }
        });
        cPanel.add(okButton);
        cPanel.add(cancelButton);

        add(tcc, BorderLayout.CENTER);
        add(cPanel, BorderLayout.SOUTH);
    }

    public void stateChanged(ChangeEvent e) {
        Color newColor = tcc.getColor();
        wb.setTextColor(tcc.getColor());
        wb.setColour(newColor);
    }

    /**
     * Create the GUI and show it.  For thread safety,
     * this method should be invoked from the
     * event-dispatching thread.
     */
    public static void createAndShowGUI(Whiteboard wb) {
        //Create and set up the window.

        //Create and set up the content pane.
        JComponent newContentPane = new WBColorChooser(wb);
        newContentPane.setOpaque(true); //content panes must be opaque
        frame.setContentPane(newContentPane);

        //Display the window.
        frame.pack();
        frame.setVisible(true);
    }
}