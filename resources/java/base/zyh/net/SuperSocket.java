package zyh.net;

import java.io.InputStream;
import java.io.OutputStream;
import java.io.IOException;

import java.net.Socket;
import java.net.SocketImpl;
import java.net.SocketImplFactory;
import java.net.InetAddress;
import java.net.SocketException;
import java.net.SocketOptions;
 
/**
 * SuperSocket class implements the common interface for client sockets through 
 * SOCKS server, http proxy server and HttpURL proxy
 */
public class SuperSocket extends Socket
{
	/*
	 * The factory for all client Sockets.
	 */
	private static SuperSocketImplFactory factory=null;
	
	/*
	 * The actual Socket.
	 */
	private Socket clientSocket=null;

	/*
	 * The implementation of this Socket.
	 */
	private SocketImpl impl=null;

	/**
	 * Sets the client socket implementation factory for the
	 * application. The factory can be specified only once.
	 * <p>
	 * When an application creates a new client socket, the socket
	 * implementation factory's <code>createSocketImpl</code> method is
	 * called to create the actual socket implementation.
	 * 
	 * <p>If there is a security manager, this method first calls
	 * the security manager's <code>checkSetFactory</code> method 
	 * to ensure the operation is allowed. 
	 * This could result in a SecurityException.
	 *
	 * @param	fac   the desired factory.
	 * @exception  IOException  if an I/O error occurs when setting the
	 *			   socket factory.
	 */
	public static synchronized void setSocketImplFactory(SuperSocketImplFactory fac)throws IOException
	{
		factory=fac;
	}

	/**
	 * Creates an unconnected socket, with the
	 * system-default type of SocketImpl.
	 */
	protected SuperSocket()
	{
		impl = (factory != null) ? factory.createSocketImpl() : null;
	}

	/**
	 * Creates an unconnected Socket with a user-specified
	 * SocketImpl.
	 * <P>
	 * The <i>impl</i> parameter is an instance of a <B>SocketImpl</B>
	 * the subclass wishes to use on the Socket.
	 */
	protected SuperSocket(SocketImpl impl)throws SocketException
	{
		this.impl = impl;
	}

	/**
	 * Creates a stream socket and connects it to the specified port
	 * number on the named host.
	 * <p>
	 * If the application has specified a server socket factory, that
	 * factory's <code>createSocketImpl</code> method is called to create
	 * the actual socket implementation. Otherwise a "plain" socket is created.
	 * <p>
	 * If there is a security manager, its
	 * <code>checkConnect</code> method is called
	 * with the host address and <code>port</code> 
	 * as its arguments. This could result in a SecurityException.
	 *
	 * @param	host   the host name.
	 * @param	port   the port number.
	 * @exception  IOException  if an I/O error occurs when creating the socket.
	 */
	public SuperSocket(String host, int port) throws IOException
	{
		this(host, port,null,0);
	}

	/**
	 * Creates a stream socket and connects it to the specified port
	 * number at the specified IP address.
	 * <p>
	 * If the application has specified a socket factory, that factory's
	 * <code>createSocketImpl</code> method is called to create the
	 * actual socket implementation. Otherwise a "plain" socket is created.
	 * <p>
	 * If there is a security manager, its
	 * <code>checkConnect</code> method is called
	 * with the host address and <code>port</code> 
	 * as its arguments. This could result in a SecurityException.
	 * 
	 * @param	address   the IP address.
	 * @param	port	 the port number.
	 * @exception  IOException  if an I/O error occurs when creating the socket.
	 */
	public SuperSocket(InetAddress address, int port) throws IOException
	{
		this(address, port,null,0);
	}
	/**
	 * Creates a socket and connects it to the specified remote host on
	 * the specified remote port. The Socket will also bind() to the local
	 * address and port supplied.
	 * <p>
	 * If there is a security manager, its
	 * <code>checkConnect</code> method is called
	 * with the host address and <code>port</code> 
	 * as its arguments. This could result in a SecurityException.
	 * 
	 * @param host the name of the remote host
	 * @param port the remote port
	 * @param localAddr the local address the socket is bound to
	 * @param localPort the local port the socket is bound to
	 * @exception  IOException  if an I/O error occurs when creating the socket.
	 */
	public SuperSocket(String host, int port, InetAddress localAddr, int localPort) throws IOException
	{
		this();
		if(impl==null)
			clientSocket=new Socket(host, port, localAddr, localPort);
		else if(impl instanceof zyh.net.SocksSocketImpl)
		{
			try
			{
				clientSocket=new SocksSocket((SocksSocketImpl)impl);
				((SocksSocketImpl)impl).create(true);
				((SocksSocketImpl)impl).bind(localAddr, localPort);
				((SocksSocketImpl)impl).connect(host, port);
			}
			catch (SocketException e)
			{
				((SocksSocketImpl)impl).close();
				throw e;
			}
		}
		else if(impl instanceof zyh.net.HttpSocketImpl)
		{
			try
			{
				clientSocket=new HttpSocket((HttpSocketImpl)impl);
				((HttpSocketImpl)impl).create(true);
				((HttpSocketImpl)impl).bind(localAddr, localPort);
				((HttpSocketImpl)impl).connect(host, port);
			}
			catch (SocketException e)
			{
				((HttpSocketImpl)impl).close();
				throw e;
			}
		}
		else if(impl instanceof zyh.net.HttpURLSocketImpl)
		{
			try
			{
				clientSocket=new HttpURLSocket((HttpURLSocketImpl)impl);
				((HttpURLSocketImpl)impl).create(true);
				((HttpURLSocketImpl)impl).bind(localAddr, localPort);
				((HttpURLSocketImpl)impl).connect(host, port);
			}
			catch (SocketException e)
			{
				((HttpURLSocketImpl)impl).close();
				throw e;
			}
		}
		else
			throw new IOException("SocketImpl is an unkonwn type: "+impl.getClass().getName());
	}

	/**
	 * Creates a socket and connects it to the specified remote address on
	 * the specified remote port. The Socket will also bind() to the local
	 * address and port supplied.
	 * <p>
	 * If there is a security manager, its
	 * <code>checkConnect</code> method is called
	 * with the host address and <code>port</code> 
	 * as its arguments. This could result in a SecurityException.
	 * 
	 * @param address the remote address
	 * @param port the remote port
	 * @param localAddr the local address the socket is bound to
	 * @param localPort the local port the socket is bound to
	 * @exception  IOException  if an I/O error occurs when creating the socket.
	 */
	public SuperSocket(InetAddress address, int port, InetAddress localAddr,int localPort) throws IOException
	{
		this();
		if(impl==null)
			clientSocket=new Socket(address, port, localAddr, localPort);
		else if(impl instanceof zyh.net.SocksSocketImpl)
		{
			try
			{
				clientSocket=new SocksSocket((SocksSocketImpl)impl);
				((SocksSocketImpl)impl).create(true);
				((SocksSocketImpl)impl).bind(localAddr, localPort);
				((SocksSocketImpl)impl).connect(address, port);
			}
			catch (SocketException e)
			{
				((SocksSocketImpl)impl).close();
				throw e;
			}
		}
		else if(impl instanceof zyh.net.HttpSocketImpl)
		{
			try
			{
				clientSocket=new HttpSocket((HttpSocketImpl)impl);
				((HttpSocketImpl)impl).create(true);
				((HttpSocketImpl)impl).bind(localAddr, localPort);
				((HttpSocketImpl)impl).connect(address, port);
			}
			catch (SocketException e)
			{
				((HttpSocketImpl)impl).close();
				throw e;
			}
		}
		else if(impl instanceof zyh.net.HttpURLSocketImpl)
		{
			try
			{
				clientSocket=new HttpURLSocket((HttpURLSocketImpl)impl);
				((HttpURLSocketImpl)impl).create(true);
				((HttpURLSocketImpl)impl).bind(localAddr, localPort);
				((HttpURLSocketImpl)impl).connect(address, port);
			}
			catch (SocketException e)
			{
				((HttpURLSocketImpl)impl).close();
				throw e;
			}
		}
		else
			throw new IOException("SocketImpl is an unkonwn type: "+impl.getClass().getName());
	}

	/**
	 * Closes this socket.
	 *
	 * @exception  IOException  if an I/O error occurs when closing this socket.
	 */
	public synchronized void close() throws IOException
	{
		if(clientSocket!=null)
			clientSocket.close();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			((SocksSocketImpl)impl).close();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			((HttpSocketImpl)impl).close();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			((HttpURLSocketImpl)impl).close();
		clientSocket=null;
	}

	/**
	 * Returns an input stream for this socket.
	 *
	 * @return	 an input stream for reading bytes from this socket.
	 * @exception  IOException  if an I/O error occurs when creating the
	 *			   input stream.
	 */
	public InputStream getInputStream() throws IOException
	{
		if(clientSocket!=null)
			return clientSocket.getInputStream();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			return ((SocksSocketImpl)impl).getInputStream();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			return ((HttpSocketImpl)impl).getInputStream();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			return ((HttpURLSocketImpl)impl).getInputStream();
	}

	/**
	 * Returns an output stream for this socket.
	 *
	 * @return	 an output stream for writing bytes to this socket.
	 * @exception  IOException  if an I/O error occurs when creating the
	 *			   output stream.
	 */
	public OutputStream getOutputStream() throws IOException
	{
		if(clientSocket!=null)
			return clientSocket.getOutputStream();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			return ((SocksSocketImpl)impl).getOutputStream();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			return ((HttpSocketImpl)impl).getOutputStream();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			return ((HttpURLSocketImpl)impl).getOutputStream();
	}

	/**
	 * Gets the local address to which the socket is bound.
	 */
	public InetAddress getLocalAddress()
	{
		if(clientSocket!=null)
			return clientSocket.getLocalAddress();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			return ((SocksSocketImpl)impl).getLocalAddress();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			return ((HttpSocketImpl)impl).getLocalAddress();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			return ((HttpURLSocketImpl)impl).getLocalAddress();
	}
	/**
	 * Returns the local port to which this socket is bound.
	 *
	 * @return  the local port number to which this socket is connected.
	 */
	public int getLocalPort()
	{
		if(clientSocket!=null)
			return clientSocket.getLocalPort();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			return ((SocksSocketImpl)impl).getLocalPort();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			return ((HttpSocketImpl)impl).getLocalPort();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			return ((HttpURLSocketImpl)impl).getLocalPort();
	}

	/**
	 * Returns setting for SO_TIMEOUT.  0 returns implies that the
	 * option is disabled (i.e., timeout of infinity).
	 */
	public synchronized int getSoTimeout() throws SocketException
	{
		if(clientSocket!=null)
			return clientSocket.getSoTimeout();
		Object o = impl.getOption(SocketOptions.SO_TIMEOUT);
		if (o instanceof Integer)
			return ((Integer) o).intValue();
		else
			return 0;
	}

	/**
	 * Tests if TCP_NODELAY is enabled.
	 *
	 * @since   JDK1.1
	 */
	public boolean getTcpNoDelay() throws SocketException
	{
		if(clientSocket!=null)
			return clientSocket.getTcpNoDelay();
		return ((Boolean) impl.getOption(SocketOptions.TCP_NODELAY)).booleanValue();
	}

	/**
	 * Returns setting for SO_LINGER. -1 returns implies that the
	 * option is disabled.
	 */
	public int getSoLinger() throws SocketException
	{
		if(clientSocket!=null)
			return clientSocket.getSoLinger();
		Object o = impl.getOption(SocketOptions.SO_LINGER);
		if (o instanceof Integer)
			return ((Integer) o).intValue();
		else
			return -1;
	}

	/**
	 * Get value of the SO_SNDBUF option for this socket, that is the
	 * buffer size used by the platform for output on the this Socket.
	 *
	 * @see #setSendBufferSize
	 */
	public synchronized int getSendBufferSize() throws SocketException
	{
		if(clientSocket!=null)
			return clientSocket.getSendBufferSize();
		Object o = impl.getOption(SocketOptions.SO_SNDBUF);
		if (o instanceof Integer)
			return ((Integer)o).intValue();
		else
			return 0;
	}

	/**
	 * Returns the address to which the socket is connected.
	 *
	 * @return  the remote IP address to which this socket is connected.
	 */
	public InetAddress getInetAddress()
	{
		if(clientSocket!=null)
			return clientSocket.getInetAddress();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			return ((SocksSocketImpl)impl).getInetAddress();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			return ((HttpSocketImpl)impl).getInetAddress();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			return ((HttpURLSocketImpl)impl).getInetAddress();
	}

	/**
	 * Returns the remote port to which this socket is connected.
	 *
	 * @return  the remote port number to which this socket is connected.
	 */
	public int getPort()
	{
		if(clientSocket!=null)
			return clientSocket.getPort();
		else if(impl instanceof zyh.net.SocksSocketImpl)
			return ((SocksSocketImpl)impl).getPort();
		else if(impl instanceof zyh.net.HttpSocketImpl)
			return ((HttpSocketImpl)impl).getPort();
		else //if(impl instanceof zyh.net.HttpURLSocketImpl)
			return ((HttpURLSocketImpl)impl).getPort();
	}

	/**
	 * Get value of the SO_RCVBUF option for this socket, that is the
	 * buffer size used by the platform for input on the this Socket.
	 *
	 * @see #setReceiveBufferSize
	 */
	public synchronized int getReceiveBufferSize()throws SocketException
	{
		if(clientSocket!=null)
			return clientSocket.getReceiveBufferSize();
		int result = 0;
		Object o = impl.getOption(SocketOptions.SO_RCVBUF);
		if (o instanceof Integer)
			result = ((Integer)o).intValue();
		return result;
	}

	/**
	 * Enable/disable TCP_NODELAY (disable/enable Nagle's algorithm).
	 */
	public void setTcpNoDelay(boolean on) throws SocketException
	{
		if(clientSocket!=null)
			clientSocket.setTcpNoDelay(on);
		else
			impl.setOption(SocketOptions.TCP_NODELAY, new Boolean(on));
	}

	/**
	 * Enable/disable SO_LINGER with the specified linger time in seconds. 
	 * If the specified timeout value exceeds 65,535 it will be reduced to
	 * 65,535.
	 * 
	 * @param on	 whether or not to linger on.
	 * @param linger how to linger for, if on is true.
	 * @exception IllegalArgumentException if the linger value is negative.
	 */
	public void setSoLinger(boolean on, int linger) throws SocketException
	{
		if(clientSocket!=null)
			clientSocket.setSoLinger(on,linger);
		else if (on)
		{
			if (linger < 0)
			{
				throw new IllegalArgumentException("invalid value for SO_LINGER");
			}
			if (linger > 65535)
				linger = 65535;
			impl.setOption(SocketOptions.SO_LINGER, new Integer(linger));
		}
		else
		{
			impl.setOption(SocketOptions.SO_LINGER, new Boolean(on));
		}
	}

	/**
	 *  Enable/disable SO_TIMEOUT with the specified timeout, in
	 *  milliseconds.  With this option set to a non-zero timeout,
	 *  a read() call on the InputStream associated with this Socket
	 *  will block for only this amount of time.  If the timeout expires,
	 *  a <B>java.io.InterruptedIOException</B> is raised, though the
	 *  Socket is still valid. The option <B>must</B> be enabled
	 *  prior to entering the blocking operation to have effect. The
	 *  timeout must be > 0.
	 *  A timeout of zero is interpreted as an infinite timeout.
	 */
	public synchronized void setSoTimeout(int timeout) throws SocketException
	{
		if(clientSocket!=null)
			clientSocket.setSoTimeout(timeout);
		else
			impl.setOption(SocketOptions.SO_TIMEOUT, new Integer(timeout));
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
		if(clientSocket!=null)
			clientSocket.setSendBufferSize(size);
		else
		{
			if (!(size > 0))
				throw new IllegalArgumentException("negative send size");
			impl.setOption(SocketOptions.SO_SNDBUF, new Integer(size));
		}
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
		if(clientSocket!=null)
			clientSocket.setReceiveBufferSize(size);
		else
		{
			if (size < 0)
				throw new IllegalArgumentException("invalid receive size");
			impl.setOption(SocketOptions.SO_RCVBUF, new Integer(size));
		}
	}

	/**
	 * Converts this socket to a <code>String</code>.
	 *
	 * @return  a string representation of this socket.
	 */
	public String toString()
	{
		if(clientSocket!=null)
			return clientSocket.toString();
		else
			return impl.toString();
	}

	/**
	 * The HTTP proxy service's conventional TCP port
	 */
	public static final int HTTP_PROXY_PORT = 8080;
	/**
	 * The SOCKS service's conventional TCP port
	 */
	public static final int SOCKS_PORT = 1080;

	/**
	 * Some optional parameters
	 */
	public static final String USER ="USER";
	public static final String USERNAME =USER;

	public static final String PASSWD ="PASSWD";
	public static final String PASSWORD =PASSWD;
	public static final String USERID =PASSWD;

	/**
	 * SOCKS version and its value list
	 */
	public static final String VERSION ="VERSION";
	public static final String SOCKS4 ="4";
	public static final String SOCKS4A ="4";
	public static final String SOCKS5 ="5";

	/**
	 * PROXY_TYPE and its value list
	 */
	public static final String PROXY_TYPE ="PROXY_TYPE";
	public static final String SOCKS ="SOCKS";
	public static final String HTTP ="HTTP";
	public static final String HTTPURL ="HTTPURL";

	public static final String PROXY_HOST ="PROXY_HOST";

	public static final String PROXY_PORT ="PROXY_PORT";

	public static final String httpURLProxyURL ="httpURLProxyURL";

	//Use it to avoid the automatic choice between HTTP and HTTPURL
	public static final String NOTEST ="NOTEST";
}
