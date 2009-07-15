/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.net;

import org.jivesoftware.smack.packet.Presence;

/**
 *
 * @author developer
 */
public interface SubscribePacketInt {

    public void processSubscription(Presence presence);
}
