/*
 *  Copyright (C) GNU/GPL AVOIR 2008
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */
package avoir.realtime.tcp.pluginmanager;

import avoir.realtime.tcp.launcher.RealtimePlugin;
import javax.swing.JApplet;
import javax.swing.JPanel;
import avoir.realtime.tcp.base.RealtimeBase;
import java.applet.AppletContext;
import javax.swing.JFrame;
import javax.swing.JMenuBar;

/**
 * Interface that allows to use reflection to load up real applets
 * @author David Wafula
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
            String resourcesPath,
            AppletContext appletContext) {


        return base.init(userLevel, fullname, userName, host,
                port, isPresenter, sessionId, slidesDir, isSlidesHost,
                siteRoot, slideServerId, resourcesPath, false, appletContext);
    }

    public void setSupernodeHost(String host) {
        base.setSupernodeHost(host);
    }

    public void setUserDetails(String userDetails) {
        base.setUserDetails(userDetails);
    }

    public void setUserImagePath(String userImagePath) {
        base.setUserImagePath(userImagePath);
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

    public JPanel createClassroomBase(String host, int port, int mode, String username,
            String fullnames,
            boolean isPresenter,
            String sessionId, String userLevel,
            String slidesDir,
            String siteRoot,
            String slidesServerId,
            String resourcesPath, JFrame parent) {
        //base.setMODE(mode);
        return base.initAsClassroom(host, port, username, fullnames, isPresenter, sessionId, userLevel,
                slidesDir,
                siteRoot,
                slidesServerId,
                resourcesPath, parent);
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