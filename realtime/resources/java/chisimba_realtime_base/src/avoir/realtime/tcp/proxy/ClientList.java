/**
 * 	$Id: ClientList.java,v 1.5 2007/05/02 09:43:15 davidwaf Exp $
 * 
 *  Copyright (C) GNU/GPL AVOIR 2007
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
 */
package avoir.realtime.tcp.proxy;

import avoir.realtime.tcp.base.user.User;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.io.Serializable;
import java.net.Socket;
import java.util.Vector;

/**
 * List of clients using the realtime tools in this session
 */
@SuppressWarnings("serial")
public class ClientList implements Serializable {

    /**
     * Vector to store the ObjectOutputStream used by each client
     */
    protected Vector<ObjectOutputStream> streams = new Vector<ObjectOutputStream>();
    protected Vector<OutputStream> audiostreams = new Vector<OutputStream>();
    /**
     * Vector to store the names of each of the clients
     */
    protected Vector<User> users = new Vector<User>();
    protected Vector<Socket> sockets = new Vector<Socket>();

    //atleast check if this user already exists, before adding
    public synchronized User sessionPresenterExists(User user) {

        for (int i = 0; i < users.size(); i++) {
            User cUser = users.elementAt(i);
            if (cUser != null && user != null) {
                String cId = cUser.getSessionId();
                String sId = user.getSessionId();
                //  System.out.println(cUser + " id = " + sId);
                // System.out.println(user + " id = " + sId);
                if (sId != null && cId != null) {
                    if (sId.trim().equals(cId.trim())) {
                        if (cUser.isPresenter()) {
                            return cUser;
                        }
                    }
                }
            }
        }
        return null;
    }
    //atleast check if this user already exists, before adding
    public synchronized void removeUserIfExists(User user) {

        for (int i = 0; i < users.size(); i++) {
            User cUser = users.elementAt(i);
            if (cUser != null && user != null) {
                if (cUser.getFullName().trim().equals(user.getFullName())) {
                    users.remove(i);
                    streams.remove(i);
                    audiostreams.remove(i);
                    sockets.remove(i);
                }
            }
        }

    }

    /**
     * Add a new client and the ObjectOutputStream
     * @param user User
     * @param stream the ObjectOutputStream
     */
    public synchronized void addElement(Socket socket, ObjectOutputStream stream, OutputStream audiostream, User user) {
        removeUserIfExists(user);
        streams.addElement(stream);
        users.addElement(user);
        audiostreams.addElement(audiostream);
        sockets.addElement(socket);
    }

    /**
     * Add a new client and the ObjectOutputStream
     * @param user User
     * @param stream the ObjectOutputStream
     */
    public synchronized void add(int index, Socket socket, ObjectOutputStream stream, OutputStream audiostream, User user) {
        removeUserIfExists(user);
        streams.add(index, stream);
        users.add(index, user);
        audiostreams.add(index, audiostream);
        sockets.add(index, socket);
    }

    /**
     * Remove the client and stream
     * @param stream ObjectOutputStream
     */
    public synchronized void removeElement(Socket socket, ObjectOutputStream stream, OutputStream audiostream) {
        int id = streams.indexOf(stream);
        if (id != -1) {
            users.removeElementAt(id);
        }
        streams.removeElement(stream);
        audiostreams.removeElement(audiostream);
        sockets.removeElement(socket);
    }

    public synchronized void removeUser(User user) {
        int id = users.indexOf(user);
        if (id != -1) {
            users.remove(id);
            streams.remove(id);
            audiostreams.remove(id);
            sockets.remove(id);
        }
    }

    /**
     * Returns the size of the stream Vector
     * @return int
     */
    public synchronized int size() {
        return streams.size();
    }

    /**
     * Returns the stream at the specified index
     * @param i index
     * @return stream
     */
    public synchronized ObjectOutputStream elementAt(int i) {
        return streams.elementAt(i);
    }

    public synchronized OutputStream audioElementAt(int i) {
        return audiostreams.elementAt(i);
    }

    public synchronized Socket socketAt(int i) {
        return sockets.elementAt(i);
    }

    public synchronized void setSpeakerStatus(boolean state, int index) {
        users.elementAt(index).setSpeakerOn(state);
    }

    public synchronized void setMicStatus(boolean state, int index) {
        users.elementAt(index).setMicOn(state);
    }

    public synchronized void setEditStatus(boolean state, int index) {
        users.elementAt(index).setEditOn(state);
    }

    public synchronized void setHandStatus(boolean state, int index) {
        users.elementAt(index).setHandOn(state);
    }

    public synchronized void setYesStatus(boolean state, int index) {
        users.elementAt(index).setYesOn(state);
    }

    public synchronized void setNoStatus(boolean state, int index) {
        users.elementAt(index).setNoOn(state);
    }

    /**
     * Returns the name at the specified index
     * @param i index
     * @return name
     */
    public synchronized User nameAt(int i) {
        return users.elementAt(i);
    }

    /**
     * returns the name associated with the stream
     * @param stream ObjectOutputStream
     * @return name of client
     */
    public synchronized String getName(ObjectOutputStream stream) {
        int id = streams.indexOf(stream);
        return (id >= 0) ? users.elementAt(id).getFullName() : null;
    }

    /**
     * Returns the stream associated with the name
     * @return ObjectOutputStream
     * @param user User
     */
    public synchronized ObjectOutputStream getStream(User user) {
        int id = users.indexOf(user);
        return (id >= 0) ? streams.elementAt(id) : null;
    }

    public synchronized OutputStream getAudioStream(User user) {
        int id = users.indexOf(user);
        return (id >= 0) ? audiostreams.elementAt(id) : null;
    }
}


