/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher;

import javax.swing.JMenuBar;
import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public interface RealtimePlugin {

    public JPanel createBase(
            String userLevel,
            String fullname,
            String userName,
            String host,
            int port,
            boolean isPresenter,
            String sessionId,
            String slidesDir,
            boolean isSlidesHost,
            String siteRoot,
            String slideServerId,
            String resourcesPath);

    public void setSessionTitle(String title);

    public int getVersion();

    public void removeUser(String userId, String sessionId);
    public JMenuBar getMenuBar();
}
