package zyh.net;

import java.io.IOException;

import java.net.DatagramSocket;
import java.net.DatagramPacket;
import java.net.InetAddress;
import java.net.SocketOptions;
import java.net.SocketException;

/**
 * SocksDatagramSocket class implements a datagramSockets  for sending and receiving datagram packets
 * through proxy server
 *
 * <p>A datagram socket is the sending or receiving point for a packet
 * delivery service. Each packet sent or received on a datagram socket
 * is individually addressed and routed. Multiple packets sent from
 * one machine to another may be routed differently, and may arrive in
 * any order.
 *
 * <p>UDP broadcasts sends and receives are always enabled on a
 * DatagramSocket.
 */
public class SocksDatagramSocket extends DatagramSocket
{
	/*
	 * The factory for all client SocksSockets.
	 */
	private static SocksSocketImplFactory factory=null;
	/*
	 * The implementation of this SocksDatagramSocket.
	 */
	private SocksSocketImpl impl;

	/**
	 * An extended method for DatagramSocket
	 *
	 * Sets the client socket implementation factory for the
	 * application. The factory can be specified only once.
	 * <p>
	 * When an application creates a new client socket, the socket
	 * implementation factory's <code>createSocketImpl</code> method is
	 * called to create the actual socket implementation.
	 * 
	 *
	 * @param	fac   the desired factory.
	 * @exception  IOException  if an I/O error occurs when setting the
	 *			   socket factory.
	 * @exception  SocketException  if the factory is already defined.
	 * @see		java.net.SocketImplFactory#createSocketImpl()
	 */
	public static synchronized void setSocketImplFactory(SocksSocketImplFactory fac)throws IOException
	{
		factory = fac;
	}
	/**
	 * Constructs a datagram socket and binds it to any available port
	 * on the local host machine.
	 */
	public SocksDatagramSocket() throws SocketException
	{
		this(0,null);
	}
	/**
	 * Constructs a datagram socket and binds it to the specified port
	 * on the local host machine.
	 * 
	 * <p>If there is a security manager, 
	 * its <code>checkListen</code> method is first called
	 * with the <code>port</code> argument
	 * as its argument to ensure the operation is allowed. 
	 * This could result in a SecurityException.
	 *
	 * @param	  localPort local port to use.
	 * @exception  SocketException  if the socket could not be opened,
	 *			   or the socket could not bind to the specified local port.
	 */
	public SocksDatagramSocket(int localPort) throws SocketException
	{
		this(localPort, null);
	}
	/**
	 * Creates a datagram socket, bound to the specified local
	 * address.  The local port must be between 0 and 65535 inclusive.
	 * 
	 * <p>If there is a security manager, 
	 * its <code>checkListen</code> method is first called
	 * with the <code>port</code> argument
	 * as its argument to ensure the operation is allowed. 
	 * This could result in a SecurityException.
	 * 
	 * @param localPort local port to use
	 * @param localAddr local address to bind
	 * 
	 * @exception  SocketException  if the socket could not be opened,
	 *			   or the socket could not bind to the specified local port.
	 */
	public SocksDatagramSocket(int localPort, InetAddress localAddr) throws SocketException
	{
		try
		{
			impl = (factory != null) ? (SocksSocketImpl)factory.createSocketImpl() : new SocksSocketImpl(false);
			//creates a udp socket
			impl.create();
			//binds the udp socket to desired port + address
			impl.bind(localAddr, localPort);
		}
		catch (SocketException e)
		{
			close();
			throw e;
		}
		catch(IOException ioe)
		{
			close();
			throw new SocketException(ioe.getMessage());
		}
	}

	/**
	 * Closes this datagram socket.
	 */
	public void close()
	{
		try
		{
			impl.close();
		}
		catch(IOException ioe)
		{
		}
		impl=null;
	}

	/** 
	 * Connects the socket to a remote address for this socket. When a
	 * socket is connected to a remote address, packets may only be
	 * sent to or received from that address. By default a datagram
	 * socket is not connected.
	 *
	 * <p>A caller's permission to send and receive datagrams to a
	 * given host and port are checked at connect time. When a socket
	 * is connected, receive and send <b>will not
	 * perform any security checks</b> on incoming and outgoing
	 * packets, other than matching the packet's and the socket's
	 * address and port. On a send operation, if the packet's address
	 * is set and the packet's address and the socket's address do not
	 * match, an IllegalArgumentException will be thrown. A socket
	 * connected to a multicast address may only be used to send packets.
	 *
	 * @param address the remote address for the socket
	 * @param port the remote port for the socket.
	 */
	public void connect(InetAddress address, int port)
	{
		try
		{
			impl.connect(address,port);
		}
		catch(IOException ioe)
		{
			close();
		}
	}

	/** 
	 * Disconnects the socket. This does nothing if the socket is not
	 * connected.
	 *
	 * @see #connect
	 */
	public void disconnect()
	{
		impl.disconnect();
	}

	/**
	 * Returns the address to which this socket is connected. Returns null
	 * if the socket is not connected.
	 *
	 * @return the address to which this socket is connected.
	 */
	public InetAddress getInetAddress()
	{
		return impl.getInetAddress();
	}

	/**
	 * Returns the port for this socket. Returns -1 if the socket is not
	 * connected.
	 *
	 * @return the port to which this socket is connected.
	 */
	public int getPort()
	{
		return impl.getPort();
	}

	/**
	 * Sends a datagram packet from this socket. The
	 * <code>DatagramPacket</code> includes information indicating the
	 * data to be sent, its length, the IP address of the remote host,
	 * and the port number on the remote host.
	 *
	 * @param	  p   the <code>DatagramPacket</code> to be sent.
	 * 
	 * @exception  IOException  if an I/O error occurs.
	 */
	public void send(DatagramPacket p) throws IOException
	{
		impl.send(p);
	}

	/**
	 * Receives a datagram packet from this socket. When this method
	 * returns, the <code>DatagramPacket</code>'s buffer is filled with
	 * the data received. The datagram packet also contains the sender's
	 * IP address, and the port number on the sender's machine.
	 * <p>
	 * This method blocks until a datagram is received. The
	 * <code>length</code> field of the datagram packet object contains
	 * the length of the received message. If the message is longer than
	 * the packet's length, the message is truncated.
	 * <p>
	 * If there is a security manager, a packet cannot be received if the
	 * security manager's <code>checkAccept</code> method
	 * does not allow it.
	 * 
	 * @param	  p   the <code>DatagramPacket</code> into which to place
	 *				 the incoming data.
	 * @exception  IOException  if an I/O error occurs.
	 * @see		java.net.DatagramPacket
	 * @see		java.net.DatagramSocket
	 */
	public void receive(DatagramPacket p) throws IOException
	{
		impl.receive(p);
	}

	/**
	 * Gets the local address to which the socket is bound.
	 *
	 * <p>If there is a security manager, its
	 * <code>checkConnect</code> method is first called
	 * with the host address and <code>-1</code> 
	 * as its arguments to see if the operation is allowed.
	 */
	public InetAddress getLocalAddress()
	{
		return impl.getLocalAddress();
	}

	/**
	 * Returns the port number on the local host to which this socket is bound.
	 *
	 * @return  the port number on the local host to which this socket is bound.
	 */
	public int getLocalPort()
	{
		return impl.getLocalPort();
	}

	/** Enable/disable SO_TIMEOUT with the specified timeout, in
	 *  milliseconds. With this option set to a non-zero timeout,
	 *  a call to receive() for this DatagramSocket
	 *  will block for only this amount of time.  If the timeout expires,
	 *  a <B>java.io.InterruptedIOException</B> is raised, though the
	 *  ServerSocket is still valid.  The option <B>must</B> be enabled
	 *  prior to entering the blocking operation to have effect.  The
	 *  timeout must be > 0.
	 *  A timeout of zero is interpreted as an infinite timeout.
	 *
	 */
	public synchronized void setSoTimeout(int timeout) throws SocketException
	{
		impl.setOption(SocketOptions.SO_TIMEOUT, new Integer(timeout));
	}

	/**
	 * Retrive setting for SO_TIMEOUT.  0 returns implies that the
	 * option is disabled (i.e., timeout of infinity).
	 */
	public synchronized int getSoTimeout() throws SocketException
	{
		Object o = impl.getOption(SocketOptions.SO_TIMEOUT);
		if (o instanceof Integer)
			return ((Integer) o).intValue();
		else
			return 0;
	}

	/**
	 * Sets the SO_SNDBUF option to the specified value for this
	 * DatagramSocket. The SO_SNDBUF option is used by the platform's
	 * networking code as a hint for the size to use to allocate set
	 * the underlying network I/O buffers.
	 *
	 * <p>Increasing buffer size can increase the performance of
	 * network I/O for high-volume connection, while decreasing it can
	 * help reduce the backlog of incoming data. For UDP, this sets
	 * the maximum size of a packet that may be sent on this socket.
	 *
	 * <p>Because SO_SNDBUF is a hint, applications that want to
	 * verify what size the buffers were set to should call
	 * <href="#getSendBufferSize>getSendBufferSize</a>.
	 *
	 * @param size the size to which to set the send buffer
	 * size. This value must be greater than 0.
	 *
	 * @exception IllegalArgumentException if the value is 0 or is
	 * negative.
	 */
	public synchronized void setSendBufferSize(int size)throws SocketException
	{
		if (!(size > 0))
			throw new IllegalArgumentException("negative send size");
		impl.setOption(SocketOptions.SO_SNDBUF, new Integer(size));
	}

	/**
	 * Get value of the SO_SNDBUF option for this socket, that is the
	 * buffer size used by the platform for output on the this Socket.
	 *
	 * @see #setSendBufferSize
	 */
	public synchronized int getSendBufferSize() throws SocketException
	{
		Object o = impl.getOption(SocketOptions.SO_SNDBUF);
		if (o instanceof Integer)
			return((Integer)o).intValue();
		else
			return 0;
	}

	/**
	 * Sets the SO_RCVBUF option to the specified value for this
	 * DatagramSocket. The SO_RCVBUF option is used by the platform's
	 * networking code as a hint for the size to use to allocate set
	 * the underlying network I/O buffers.
	 *
	 * <p>Increasing buffer size can increase the performance of
	 * network I/O for high-volume connection, while decreasing it can
	 * help reduce the backlog of incoming data. For UDP, this sets
	 * the maximum size of a packet that may be sent on this socket.
	 *
	 * <p>Because SO_RCVBUF is a hint, applications that want to
	 * verify what size the buffers were set to should call
	 * <href="#getReceiveBufferSize>getReceiveBufferSize</a>.
	 *
	 * @param size the size to which to set the receive buffer
	 * size. This value must be greater than 0.
	 *
	 * @exception IllegalArgumentException if the value is 0 or is
	 * negative.
	 */
	public synchronized void setReceiveBufferSize(int size)throws SocketException
	{
		if (size < 0)
			throw new IllegalArgumentException("invalid receive size");
		impl.setOption(SocketOptions.SO_RCVBUF, new Integer(size));
	}

	/**
	 * Get value of the SO_RCVBUF option for this socket, that is the
	 * buffer size used by the platform for input on the this Socket.
	 *
	 * @see #setReceiveBufferSize
	 */
	public synchronized int getReceiveBufferSize()throws SocketException
	{
		Object o = impl.getOption(SocketOptions.SO_RCVBUF);
		if (o instanceof Integer)
			return((Integer)o).intValue();
		else
			return 0;
	}
}
