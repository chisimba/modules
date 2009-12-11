/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

/**
 *
 * @author kim
 */
public class MainFrameUtils {

    private MainFrame mainFrame;

    public MainFrameUtils(MainFrame mainFrame) {
        this.mainFrame = mainFrame;
    }

    /**
     * this configures the screen share view
     */
    public void configureScreenShareView() {
        mainFrame.getScreenShareView().setBarsVisible(false);
        mainFrame.getScreenShareView().setMenuBarVisible(false);
        mainFrame.getScreenShareView().setButtonBarVisible(false);
        mainFrame.getScreenShareView().setStatusBarVisible(true);
    }

    
}
