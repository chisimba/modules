/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.flashplayer;

import chrriis.dj.nativeswing.swtimpl.components.JFlashPlayer;
import java.awt.BorderLayout;
import java.io.File;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.SwingUtilities;

/**
 *
 * @author developer
 */
public class FlashPlayer {

    private String filename;

    public FlashPlayer(final String xfilename) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {

                filename = xfilename;
                JFlashPlayer flashPlayer = new JFlashPlayer();
                flashPlayer.load(filename);
                JPanel panel = new JPanel(new BorderLayout());
                panel.add(flashPlayer, BorderLayout.CENTER);
                JFrame frame = new JFrame(new File(filename).getName());
                frame.setContentPane(panel);
                frame.setSize(400, 300);
                frame.setLocationByPlatform(true);
                frame.setVisible(true);
            }
        });
    }
}
