/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.chat;

import java.awt.Image;

/**
 *
 * @author developer
 */
public interface RoomListener {

    public void joined(String jid);

    public void left(String jid);

    public void kicked(String jid);

    public void processMessage(String from, String time, String message);

    public void processSlide(String imagePath);
}
