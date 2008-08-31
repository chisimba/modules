/*
 * This is used as a bridge to use the plugin interface to load external jar files
 * 
 */
package avoir.realtime.tcp.pluginmanager;

import avoir.realtime.tcp.launcher.RealtimePlugin;
import javax.swing.JApplet;
import javax.swing.JPanel;
import avoir.realtime.tcp.base.RealtimeBase;
import javax.swing.JMenuBar;

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

    public void setSupernodeHost(String host) {
        base.setSupernodeHost(host);
    }

    public void setSupernodePort(int port) {
        base.setSupernodePort(port);
    }

    public void setMode(int mode) {
    }

    public void setSessionTitle(String title) {
        base.setSessionTitle(title);
    }

    public void setGlassPaneHandler(JApplet applet) {
        base.setGlassPaneHandler(applet);
    }

    public void setApplectCodeBase(String appletCode) {
        base.setAppletCodeBase(appletCode);
    }

    public JPanel createBase(String arg0, String arg1, String arg2, String arg3, int arg4, boolean arg5, String arg6, String arg7, boolean arg8, String arg9, String arg10, String arg11, boolean arg12) {
        return new JPanel();
    }

    public JPanel createClassroomBase(String host,int port,int mode) {
        base.setMODE(mode);
        return base.initAsClassroom(host,port);
    }

    public void removeUser(String arg0, String arg1) {
        base.removeUser(arg0, arg1);
    }

    public JMenuBar getMenuBar() {
        return base.getMenuMananger();
    }

    public int getVersion() {
        return (int) Version.version;
    }
}