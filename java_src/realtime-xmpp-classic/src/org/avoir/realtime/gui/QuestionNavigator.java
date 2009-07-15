/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.avoir.realtime.gui;

import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.net.RPacketListener;

/**
 *
 * @author developer
 */
public class QuestionNavigator extends Navigator{

    public QuestionNavigator(MainFrame xmf) {
        super(xmf, "questions", "Questions Navigator");
          RPacketListener.addQuestionNavigatorFileVewListener(this);
    }

}
