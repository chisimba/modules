/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

/**
 *
 * @author developer
 */
/**
 * Our own button behavoir
 */
public class MButton extends javax.swing.JButton {

    public MButton(javax.swing.ImageIcon icon) {
        super(icon);
        setBorderPainted(false);
        setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        setFont(new java.awt.Font("Dialog", 0, 9));
        setEnabled(false);
        this.addMouseListener(new java.awt.event.MouseAdapter() {

            @Override
            public void mouseEntered(java.awt.event.MouseEvent e) {
                setContentAreaFilled(true);
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent e) {
                setContentAreaFilled(false);
            }
            });
    }
    }
