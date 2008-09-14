/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.appsharing.DesktopUtil;
import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.common.packet.DesktopPacket;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

/**
 *
 * @author developer
 */
public class BaseManager {

    private RealtimeBase base;
    private DesktopUtil desktopUtil;
    private boolean screenCapture;
    private boolean paused = false;

    public BaseManager(RealtimeBase base) {
        this.base = base;
        desktopUtil = new DesktopUtil();

    }

    public void startApplicationSharing() {
        screenCapture = true;
        Thread t = new Thread() {

            public void run() {
                while (screenCapture) {
                    base.getTcpClient().sendPacket(
                            new DesktopPacket(desktopUtil.getScreenShot(), base.getSessionId()));
                }
            }
        };
        t.start();
    }

    public boolean isScreenCapture() {
        return screenCapture;
    }

    public void stopApplicationSharing() {
        screenCapture = false;
    }

    public void setScreenCapture(boolean screenCapture) {
        this.screenCapture = screenCapture;
    }

    /**
     * When the application is close..remove user from the list
     * @param frame
     * @param user
     */
    public void setApplicationClosedOperation(JFrame frame, final User user) {
        if (frame != null) {
            frame.addWindowListener(new WindowAdapter() {

                @Override
                public void windowClosing(WindowEvent e) {
                    base.getTcpClient().removeUser(user);

                    paused = true;
                }
            });
        }
    }

 
}
