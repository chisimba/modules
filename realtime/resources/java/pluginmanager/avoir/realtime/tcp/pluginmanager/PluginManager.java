/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.pluginmanager;

import avoir.realtime.tcp.launcher.RealtimePlugin;
import javax.swing.JPanel;
import avoir.realtime.tcp.base.RealtimeBase;

/**
 *
 * @author developer
 */
public class PluginManager implements RealtimePlugin {

    RealtimeBase base = new RealtimeBase();

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
            String resourcesPath) {
        
        
        return base.init(userLevel, fullname, userName, host,
                port, isPresenter, sessionId, slidesDir, isSlidesHost,
                siteRoot, slideServerId, resourcesPath, false);
    }

    public void setSessionTitle(String title) {
        base.setSessionTitle(title);
    }
    
    public JPanel createBase(String arg0, String arg1, String arg2, String arg3, int arg4, boolean arg5, String arg6, String arg7, boolean arg8, String arg9, String arg10, String arg11, boolean arg12) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void removeUser(String arg0, String arg1) {
        base.removeUser(arg0, arg1);
    }

    public int getVersion() {
        return Version.version;
    }
}