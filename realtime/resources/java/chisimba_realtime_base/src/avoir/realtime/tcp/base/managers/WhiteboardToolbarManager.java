/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.RealtimeBase;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.net.URL;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;

/**
 *
 * @author developer
 */
public class WhiteboardToolbarManager extends JToolBar implements ActionListener {

    private TButton selectButton = new TButton(createImageIcon(this, "/icons/whiteboard/move.png"));
    private TButton freeHandButton = new TButton(createImageIcon(this, "/icons/whiteboard/paintbrush.png"));
    private TButton lineButton = new TButton(createImageIcon(this, "/icons/whiteboard/color_line.png"));
    private TButton textButton = new TButton(createImageIcon(this, "/icons/whiteboard/text.png"));
    private RealtimeBase base;

    public WhiteboardToolbarManager(RealtimeBase base) {
        this.base = base;
        selectButton.setActionCommand("select");
        selectButton.addActionListener(this);

        freeHandButton.setActionCommand("freeHand");
        freeHandButton.addActionListener(this);
        freeHandButton.setSelected(true);
        lineButton.setActionCommand("line");
        lineButton.addActionListener(this);
        textButton.setActionCommand("text");
        textButton.addActionListener(this);
        add(selectButton);
        add(freeHandButton);
        add(lineButton);
        add(textButton);

        ButtonGroup bg = new ButtonGroup();
        bg.add(selectButton);
        bg.add(freeHandButton);
        bg.add(lineButton);
        bg.add(textButton);
    }

    public JToolBar getWhiteboardToolbar() {
        return this;
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("move")) {
        }

        if (e.getActionCommand().equals("text")) {
        }
        if (e.getActionCommand().equals("freeHand")) {
        }
        if (e.getActionCommand().equals("line")) {
        }
    }

    /**
     * Creates an ImageIcon, retrieving the Image from the system classpath.
     *
     * @param path String location of the image file
     * @return Returns and ImageIcon object with the supplied image
     * @throws FileNotFoundException File can't be found
     */
    public static ImageIcon createImageIcon(String path) {
        try {
            URL imageURL = ClassLoader.getSystemResource(path);
            if (imageURL != null) {
                return new ImageIcon(imageURL);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    /**
     * Creates an ImageIcon, retrieving the image from the classes' classpath or 
     * the system classpath (searched in that order).
     *
     * @param classToLoadFrom Class to use to search classpath for image.
     * @param path String location of the image file
     * @return Returns and ImageIcon object with the supplied image
     * @throws FileNotFoundException File can't be found
     */
    public static ImageIcon createImageIcon(Object classToLoadFrom, String path) {
        try {
            URL imageURL = classToLoadFrom.getClass().getResource(path);
            if (imageURL == null) {
                imageURL = classToLoadFrom.getClass().getClassLoader().getResource(
                        path);
            }
            if (imageURL == null) {
                return createImageIcon(path);
            } else {
                return new ImageIcon(imageURL);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    /**
     * Our own button behavoir
     */
    class TButton extends JToggleButton {

        public TButton(ImageIcon icon) {
            super(icon);
            //setContentAreaFilled(false);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 9));
            //setEnabled(false);
            this.addMouseListener(new  

                  MouseAdapter( ) {

                    @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                }
            });
        }
    }
}
