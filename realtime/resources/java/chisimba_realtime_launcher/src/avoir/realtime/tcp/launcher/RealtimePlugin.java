/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher;

import javax.swing.JApplet;
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

    public void setSupernodeHost(String host);

    public void setSupernodePort(int port);

    public void setApplectCodeBase(String codeBase);

    public void setGlassPaneHandler(JApplet applet);

    public int getVersion();

    public void removeUser(String userId, String sessionId);

    public JMenuBar getMenuBar();

    public void setMode(int mode);

    public JPanel createClassroomBase(String host,int port,int mode);
}
