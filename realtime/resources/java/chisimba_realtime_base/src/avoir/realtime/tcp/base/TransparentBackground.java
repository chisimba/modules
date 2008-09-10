/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import javax.swing.*;

import java.awt.*;

import java.awt.event.*;

import java.awt.image.*;

public class TransparentBackground extends JComponent
        implements ComponentListener, WindowFocusListener {

    private BufferedImage _background;
    private long _lastUpdate = 0;
    private boolean _refreshRequested = true;
    private Robot _robot;
    private Rectangle _screenRect;
    private ConvolveOp _blurOp;
    private int dimW;
    private int dimH;
    // constructor -------------------------------------------------------------
    public TransparentBackground(int dimW, int dimH) {
        this.dimH = dimH;
        this.dimW = dimW;

        try {

            _robot = new Robot();

        } catch (AWTException e) {

            e.printStackTrace();

            return;

        }

        _screenRect = new Rectangle(dimW, dimH);



        float[] my_kernel = {
            0.10f, 0.10f, 0.10f,
            0.10f, 0.20f, 0.10f,
            0.10f, 0.10f, 0.10f
        };

        _blurOp = new ConvolveOp(new Kernel(3, 3, my_kernel));



        updateBackground();


    }
    // protected ---------------------------------------------------------------
    protected void updateBackground() {

        _background = _robot.createScreenCapture(_screenRect);

    }

    protected void refresh() {

        if (this.isVisible()) {

            repaint();

            _refreshRequested = true;

            _lastUpdate = System.currentTimeMillis();

        }

    }
    // JComponent --------------------------------------------------------------
    protected void paintComponent(Graphics g) {

        Graphics2D g2 = (Graphics2D) g;



        Point pos = this.getLocationOnScreen();



        BufferedImage buf = new BufferedImage(getWidth(), getHeight(), BufferedImage.TYPE_INT_RGB);

        buf.getGraphics().drawImage(_background, -pos.x, -pos.y, null);



        Image img = _blurOp.filter(buf, null);

        g2.drawImage(img, 0, 0, null);



        g2.setColor(new Color(255, 255, 255, 192));

        g2.fillRect(0, 0, getWidth(), getHeight());

    }
    // ComponentListener -------------------------------------------------------
    public void componentHidden(ComponentEvent e) {
    }

    public void componentMoved(ComponentEvent e) {

        repaint();

    }

    public void componentResized(ComponentEvent e) {

        repaint();

    }

    public void componentShown(ComponentEvent e) {

        repaint();

    }
    // WindowFocusListener -----------------------------------------------------
    public void windowGainedFocus(WindowEvent e) {

        refresh();

    }

    public void windowLostFocus(WindowEvent e) {

        refresh();

    }
}

