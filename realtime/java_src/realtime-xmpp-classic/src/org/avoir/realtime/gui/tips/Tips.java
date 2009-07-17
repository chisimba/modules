/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.tips;

import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;

/**
 *
 * @author david
 */
public class Tips {

    private static String newLineStart = "<br>";
    public static String[] tips = {
        "<html>" +
        "<img src=\"" + ImageUtil.createImageIcon(GUIAccessManager.mf, "/images/lightbulb.png") + "\"><b>Did you know ...</b>" +
        newLineStart +
        newLineStart +
        "If you are an instructor, in order to use audio functionality, you need to" +
        " <b>enable</b> it before " +
        "you can communicate with others verbarlly." +
        newLineStart +
        newLineStart +
        "To do this, click in the <b>'Audio/Video'</b> tab on top left of your main screen," +
        "then click on <b>'Enable'</b> button." +
        newLineStart +
        newLineStart +
        "The <b>flash</b> window might pop up requesting access to <b>microphone</b>. Grant it" +
        "</html>"
    };
}
