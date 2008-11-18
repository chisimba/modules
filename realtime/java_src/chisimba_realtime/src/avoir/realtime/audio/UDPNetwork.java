/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.audio;

import java.io.InputStream;
import java.io.OutputStream;
import java.io.IOException;

import java.net.InetAddress;

public class UDPNetwork
        extends BaseNetwork {

    public UDPNetwork(MasterModel masterModel) {
        super(masterModel);
// 		try
// 		{
// 			m_serverSocket = new ServerSocket(getPort());
// 			m_serverSocket.setSoTimeout(2000);
// 		}
// 		catch (IOException e)
// 		{
// 			Debug.out(e);
// 		}
    }

    public void connect(InetAddress addr, int port) {
// 		try
// 		{
// 			m_commSocket = new Socket(addr, getPort());
// 		}
// 		catch (IOException e)
// 		{
// 			Debug.out(e);
// 		}
    }

    public void disconnect() {
// 		try
// 		{
// 			m_commSocket.close();
// 		}
// 		catch (IOException e)
// 		{
// 			Debug.out(e);
// 		}
    }

    public boolean isConnected() {
        return false; //m_commSocket != null && m_commSocket.isConnected();
    }

    public InetAddress getPeer() {
        return null;
    }

    public void setListen(boolean bListen) {
    }

    public boolean listen() {
// 		try
// 		{
// 			m_commSocket = m_serverSocket.accept();
// 		}
// 		catch (IOException e)
// 		{
// 			Debug.out(e);
// 		}
        return isConnected();
    }

    public InputStream createReceiveStream()
            throws IOException {
        return null; //m_commSocket.getInputStream();
    }

    public OutputStream createSendStream()
            throws IOException {
        return null; //m_commSocket.getOutputStream();
    }
}
/*** UDPNetwork.java ***/
