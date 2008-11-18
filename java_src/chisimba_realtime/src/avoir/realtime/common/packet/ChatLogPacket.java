/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.RealtimePacket;
import java.util.LinkedList;

/**
 *
 * @author developer
 */
public class ChatLogPacket implements RealtimePacket {

    private String id;
    private LinkedList<ChatPacket> list;
    private int size;

    public ChatLogPacket(LinkedList<ChatPacket> list) {
        this.list = list;
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public int getSize() {
        return size;
    }

    public void setSize(int size) {
        this.size = size;
    }

    public String getId() {
        return id;
    }

    public LinkedList<ChatPacket> getList() {
        return list;
    }

    public void setList(LinkedList<ChatPacket> list) {
        this.list = list;
    }

    public void setId(String id) {
        this.id = id;
    }
}
