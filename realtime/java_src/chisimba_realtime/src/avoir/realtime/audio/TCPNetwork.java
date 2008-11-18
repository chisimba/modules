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
import java.net.Socket;
import java.net.ServerSocket;
import java.net.SocketException;
import java.net.SocketTimeoutException;

import static avoir.realtime.audio.Constants.*;

public class TCPNetwork
        extends BaseNetwork {

    private ServerSocket m_serverSocket;
    private Socket m_commSocket;

    public TCPNetwork(MasterModel masterModel) {
        super(masterModel);
    }

    public void connect(InetAddress addr,int port) {
        Debug.out("connect(): begin "+addr.getHostAddress()+":"+port);
        try {
            m_commSocket = new Socket(addr, port);
            setSocketOptions(m_commSocket);
            
        } catch (IOException e) {
            Debug.out(e);
        }
    }

    public InetAddress getPeer() {
        return m_commSocket.getInetAddress();
    }

    public void disconnect() {
        try {
            m_commSocket.shutdownInput();
            m_commSocket.shutdownOutput();
        } catch (IOException e) {
            Debug.out(e);
        }
        try {
            m_commSocket.close();
        } catch (IOException e) {
            Debug.out(e);
        }
    }

    public boolean isConnected() {
        return m_commSocket != null && !m_commSocket.isClosed();
    }

    /** Enables listening.  This method has to be called with true
    before calling listen(). If listen() will no longer be used,
    setListen() should be called again with false to free system
    resources.
     */
    public void setListen(boolean bListen) {
        if (bListen != isListening()) {
            if (bListen) {
                try {
                    m_serverSocket = new ServerSocket(getPort());
                    m_serverSocket.setSoTimeout(2000);
                } catch (IOException e) {
                    Debug.out(e);
                }
            } else {
                try {
                    m_serverSocket.close();
                } catch (IOException e) {
                    Debug.out(e);
                }
                m_serverSocket = null;
            }
        }
    }

    private boolean isListening() {
        return m_serverSocket != null;
    }

    public boolean listen() {
        Socket s = null;
        try {
            s = m_serverSocket.accept();
            setSocketOptions(s);
        } catch (SocketTimeoutException e) {
            //Debug.out(e);
        } catch (IOException e) {
            Debug.out(e);
        }
        if (s != null) {
            m_commSocket = s;
            return true;
        } else {
            return false;
        }
    }

    public InputStream createReceiveStream()
            throws IOException {
        return m_commSocket.getInputStream();
    }

    public OutputStream createSendStream()
            throws IOException {
        return m_commSocket.getOutputStream();
    }

    private static void setSocketOptions(Socket socket) {
        try {
            socket.setTcpNoDelay(TCP_NODELAY);
            if (TCP_SEND_BUFFER_SIZE > 0) {
                socket.setSendBufferSize(8192);//TCP_SEND_BUFFER_SIZE);1024*2
            }
            if (TCP_RECEIVE_BUFFER_SIZE > 0) {
                socket.setReceiveBufferSize(8192);//TCP_RECEIVE_BUFFER_SIZE);
            }
            //Debug.out("NODELAY: " + socket.getTcpNoDelay());
            Debug.out("TCP socket send buffer size: " + socket.getSendBufferSize());
            Debug.out("TCP socket receive buffer size: " + socket.getReceiveBufferSize());
        } catch (SocketException e) {
            Debug.out(e);
        }
    }
}
/*** TCPNetwork.java ***/
