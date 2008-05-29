package zyh.net;

import java.io.InputStream;
import java.io.OutputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InterruptedIOException;

import java.net.SocketAddress;
import java.net.URLEncoder;
import java.net.URL;
import java.net.Socket;
import java.net.SocketImpl;
import java.net.InetAddress;
import java.net.SocketException;
import java.net.UnknownHostException;

import java.util.Properties;
import java.util.Hashtable;
import java.util.ArrayList;//Replace it with java.util.Vector if you are using Java1.0.X

/**
 *
 *  HttpURLSocketImpl: A Socket implementation class support HTTP URL Tunneling
 *
 *  @version 1.0, 05/10/2000
 *
 *  @author  Zhao Yonghong (zhaoyh@bigfoot.com)
 *
 *	Copyright 2000 by HXKM Inc., 9 Station Road, Xiangtan, 411100, P.R. China
 *	All rights reserved.
 * 
 *  You can distribute freely it without modification.
 *  You also can modify and distribute it but please keep our statements unchanged.
 *  For more licences conditions, please refer to
 *  <A HREF="http://www.fsf.org/copyleft/gpl.html">GNU General Public License (GPL)</A>
 *	or
 *  <A HREF="http://language.perl.com/misc/Artistic.html">Artistic License</A>
 *	 
 */

/**
 * HttpURLSocketImpl: A Socket Implementation class support HTTP URL Tunneling
 *
 *  <A HREF="http://www.w3.org/Protocols/">HTTP - Hypertext Transfer Protocol Overview:</A><BR>
 *	 <DD>[1] <A HREF="http://www.rfc-editor.org/rfc/rfc1945.txt">Hypertext Transfer Protocol -- HTTP/1.0</A><BR>
 *	 <DD>[2] <A HREF="http://www.w3.org/Protocols/rfc2616/rfc2616.html">Hypertext Transfer Protocol -- HTTP/1.1</A><BR>
 */

class HttpURLSocketImpl extends SocketImpl
{

    @Override
    protected void connect(SocketAddress address, int timeout) throws IOException {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    @Override
    protected void sendUrgentData(int data) throws IOException {
        throw new UnsupportedOperationException("Not supported yet.");
    }
	/*
	 *  a boolean indicating whether this is a stream socket or a datagram socket.
	 *  If the stream argument is true, this creates a stream socket. If the stream argument is
	 *  false, it creates a datagram socket. 
	 */
	private boolean stream=true;
	/*
	 * The Socket between local host and remote host when there isn't a proxy server.
	 */
	private Socket clientSocket=null;

	/*
	 * The HTTP URL proxy serer's URL.
	 */
	private URL httpURLProxyURL=null;
	/**
	 * Returns the URL of this HTTP URL proxy proxy.
	 */
	protected URL getHttpURLProxyURL()
	{
		return httpURLProxyURL;
	}

	/*
	 * The IP address of the HTTP proxy server.
	 */
	private InetAddress proxyAddress=null;
	/**
	 * Returns the value of this socket's <code>proxy address</code> field.
	 *
	 * @return  the value of this socket's <code>proxy address</code> field.
	 * @see	 java.net.SocketImpl#address
	 */
	protected InetAddress getProxyAddress()
	{
		return proxyAddress;
	}
	/*
	 * The port number on the HTTP proxy serer.
	 */
	private int proxyPort=HttpSocket.HTTP_PROXY_PORT;
	/**
	 * Returns the value of this socket's <code>proxy port</code> field.
	 *
	 * @return  the value of this socket's <code>proxy port</code> field.
	 */
	protected int getProxyPort()
	{
		return proxyPort;
	}

	/*
	 * The IP address of the local end of this socket.
	 * instance variable for SO_BINDADDR
	 */
	/*
	 */
	private InetAddress localAddress;
	/**
	 * Returns the value of this socket's <code>proxy address</code> field.
	 *
	 * @return  the value of this socket's <code>proxy address</code> field.
	 * @see	 java.net.SocketImpl#address
	 */
	protected InetAddress getLocalAddress()
	{
		return localAddress;
	}
	/**
	 * Returns the value of this socket's <code>localport</code> field.
	 *
	 * @return  the value of this socket's <code>localport</code> field.
	 * @see	 java.net.SocketImpl#localport
	 */
	protected int getLocalPort()
	{
		return localport;
	}
	/**
	 * Returns the value of this socket's <code>address</code> field.
	 *
	 * @return  the value of this socket's <code>address</code> field.
	 * @see	 java.net.SocketImpl#address
	 */
	protected InetAddress getInetAddress()
	{
		return address;
	}

	/**
	 * Returns the value of this socket's <code>port</code> field.
	 *
	 * @return  the value of this socket's <code>port</code> field.
	 * @see	 java.net.SocketImpl#port
	 */
	protected int getPort()
	{
		return port;
	}


	/*
	 * An ojbect for output buffer
	 */
	private ByteArrayOutputStream outputBuffer;

	protected HttpURLSocketImpl(){}
	protected HttpURLSocketImpl(boolean stream)
	{
		this.stream=stream;
	}
	protected HttpURLSocketImpl(InetAddress proxyAddress,int proxyPort,URL httpURLProxyURL)throws java.net.UnknownHostException
	{
		this(proxyAddress,proxyPort,httpURLProxyURL,null,true);
	}
	protected HttpURLSocketImpl(InetAddress proxyAddress,int proxyPort,URL httpURLProxyURL,Properties properties)
	{
		this(proxyAddress,proxyPort,httpURLProxyURL,properties,true);
	}
	protected HttpURLSocketImpl(InetAddress proxyAddress,int proxyPort,URL httpURLProxyURL,Properties properties,boolean stream)
	{
		this.proxyAddress=proxyAddress;
		this.proxyPort=proxyPort;
		this.httpURLProxyURL=httpURLProxyURL;
		this.stream=stream;
		if(httpURLProxyURL!=null)
			outputBuffer= new ByteArrayOutputStream(1024);
		if(properties!=null)
			initProperties(properties);
	}

	private void initProperties(Properties properties)
	{
		String value=properties.getProperty(HttpURLSocket.USERNAME);
		if(value!=null)username=value;
		value=properties.getProperty(HttpURLSocket.PASSWORD);
		if(value!=null)password=value;
	}

	/**
	 * Creates either a stream or a datagram socket. 
	 * Creates a socket with a boolean that specifies whether this
	 * is a stream socket (true) or an unconnected UDP socket (false).
	 *
	 * @param	stream   if <code>true</code>, create a stream socket;
	 *					  otherwise, create a datagram socket.
	 * @exception  IOException  if an I/O error occurs while creating the
	 *			   socket.
	 */
	protected void create(boolean stream) throws IOException
	{
		//Nothing since it's always a socket connection between local host and proxy server.
	}

	/**
	 * Binds this socket to the specified port number flag the specified host. 
	 *
	 * @param	address   the IP address of the specified host.
	 * @param	port   the port number.
	 * @exception  IOException  if an I/O error occurs when binding this socket.
	 */
	protected void bind(InetAddress address, int port)throws IOException
	{
		this.localAddress=address;
		this.localport=port;
	}

	/**
	 * Connects this stream socket to the specified port flag the named host. 
	 *
	 * @param	host   the name of the remote host.
	 * @param	port   the port number.
	 * @exception  IOException  if an I/O error occurs when connecting to the
	 *			   remote host.
	 */
	protected void connect(String host, int port)throws UnknownHostException, IOException
	{
		try
		{
			if(httpURLProxyURL==null)//Direct Socket Connection
			{
				clientSocket=new Socket(InetAddress.getByName(host),port,localAddress,localport);
				localAddress=clientSocket.getLocalAddress();
			}
			else//HTTP URL Tunneling Connection
			{
				initRemoteHost(host,port);
				doHTTPURLconnect();
			}
		}
		catch (IOException e)
		{
			close();
			throw e;
		}
	}

	/**
	 * Connects this stream or datagram socket to the specified port number flag the specified host.
	 *
	 * @param	address   the IP address of the remote host.
	 * @param	port	 the port number.
	 * @exception  IOException  if an I/O error occurs when attempting a
	 *			   connection.
	 */
	protected void connect(InetAddress address, int port) throws IOException
	{
		try
		{
			if(stream)// Stream socket connection
			{
				boolean isConnected=clientSocket!=null;
				if(isConnected)return;
				if(httpURLProxyURL==null)//Direct Socket Connection
				{
					clientSocket=new Socket(address,port,localAddress,localport);
					localAddress=clientSocket.getLocalAddress();
					localport=clientSocket.getLocalPort();
				}
				else//HTTP URL Tunneling Connection
				{
					initRemoteHost(address,port);
					doHTTPURLconnect();
				}
			}
			else
				throw new IOException("HTTPURLSocket can't support datagram socket now.");
		}
		catch (IOException e)
		{
			close();
			throw e;
		}
	}

	/**
	 * Returns an input stream for this socket.
	 *
	 * @return	 a stream for reading from this socket.
	 * @exception  IOException  if an I/O error occurs when creating the
	 *			   input stream.
	 */
	protected synchronized InputStream getInputStream() throws IOException
	{
		if(clientSocket!=null)
			return clientSocket.getInputStream();
		else
			throw new IOException("Error: Try to access an unconnected or closed HTTP URL tunneling server.");
	}

	/**
	 * Returns an output stream for this socket.
	 *
	 * @return	 an output stream for writing to this socket.
	 * @exception  IOException  if an I/O error occurs when creating the
	 *			   output stream.
	 */
	protected synchronized OutputStream getOutputStream() throws IOException
	{
		if(clientSocket!=null)
			return clientSocket.getOutputStream();
		else
			throw new IOException("Error: Try to access an unconnected or closed HTTP URL tunneling server.");
	}

	/**
	 * Returns the number of bytes that can be read from this socket
	 * without blocking.
	 *
	 * @return	 the number of bytes that can be read from this socket
	 *			 without blocking.
	 * @exception  IOException  if an I/O error occurs when determining the
	 *			   number of bytes available.
	 */
	protected synchronized int available() throws IOException
	{
		return getInputStream().available();
	}


	/**
	 * Set username for the specific HTTP proxy serer.
	 */
	private String username=null;

	/**
	 * Set password for the specific HTTP proxy serer.
	 */
	private String password=null;

	/**
	 * Set the IP address and port number of the remote end of this socket.
	 */
	private String remoteHost=null;
	private String remotePort=null;
	private void initRemoteHost(String host,int port)throws UnknownHostException
	{
		try
		{
			this.port = port;
			remotePort=URLEncoder.encode(""+port);
			remoteHost=URLEncoder.encode(host);
			this.address= InetAddress.getByName(host);
		}
		catch(Exception uhe)//Use it to catch security exception
//		catch(UnknownHostException uhe)
		{
			this.address=null;
		}
	}
	private void initRemoteHost(InetAddress address,int port)
	{
		this.address= address;
		this.port = port;
		remotePort=URLEncoder.encode(""+port);
		remoteHost=URLEncoder.encode(address.getHostAddress());
	}

	/**
	 * Setup a HTTP URL tunneling connection to the remote host through the HTPP URL server
	 */
	private synchronized void doHTTPURLconnect()throws IOException
	{
		if(remoteHost==null || remotePort==null)
			throw new IOException("Error: You Cann't connect without defining the IP address and port of remote host.");

		//Construct a HTTP POST request
		outputBuffer.reset();
		outputBuffer.write(("POST "+httpURLProxyURL+" HTTP/1.0\r\n").getBytes());

		if(proxyAddress!=null)
			outputBuffer.write("Proxy-Connection: Keep-Alive\r\n".getBytes());//Keep-Alive is only available for HTTP/1.1
		else
			outputBuffer.write("Connection: Keep-Alive\r\n".getBytes());
		outputBuffer.write("User-Agent: HttpURLSocket\r\n".getBytes());
		outputBuffer.write("Pragma: no-cache\r\n".getBytes());
		outputBuffer.write(("Host: "+httpURLProxyURL.getHost()+":"+httpURLProxyURL.getPort()+"\r\n").getBytes());
		outputBuffer.write("Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*\r\n".getBytes());
		outputBuffer.write("Content-type: application/x-www-form-urlencoded\r\n".getBytes());

		StringBuffer sb=new StringBuffer(200);
		sb.append("sessionHost=");
		sb.append(URLEncoder.encode(remoteHost));
		sb.append("&sessionPort=");
		sb.append(remotePort);
		//Can be provided simple HttpURLProxy Authorization
		sb.append(username==null?"":"&sessionUser="+URLEncoder.encode(username));
		sb.append(password==null?"":"&sessionPasswd="+URLEncoder.encode(password));

		byte[] buffer=sb.toString().getBytes();

		outputBuffer.write(("Content-length: "+buffer.length+"\r\n").getBytes());
		outputBuffer.write("\r\n".getBytes());
		outputBuffer.write(buffer);

		buffer=outputBuffer.toByteArray();

		for(int i=0;i<5;i++)//Try five time since both of the http proxy and httpURLProxy server are possible very busy.
		{
			try
			{

				if(proxyAddress!=null)
					clientSocket=new Socket(proxyAddress,proxyPort,localAddress,localport);
				else
					clientSocket=new Socket(httpURLProxyURL.getHost(),httpURLProxyURL.getPort(),localAddress,localport);
				
				getOutputStream().write(buffer);
				
				getOutputStream().flush();

				initHeaders();
				if(getResponseCode()==200)//HttpURLConnection.HTTP_OK
					break;
				if((responseCode==504 //HttpURLConnection.HTTP_GATEWAY_TIMEOUT
					|| responseCode==408)//HttpURLConnection.HTTP_CLIENT_TIMEOUT
					&& i<4)
					continue;
				else
				{
					i=100;
					throw new IOException(responseMessage);
				}
			}
			catch(IOException e)
			{
				if(i<4)
				{
					responseMessage=e.toString();
					try
					{
						Thread.sleep(200);
					}
					catch (InterruptedException ie){} 
				}
				else
				{
					close();
					throw new IOException("("+httpURLProxyURL+") "+e.getMessage());
				}
			}
		}
	}

	private int responseCode = -1;
	private String responseMessage = null;
	private int getResponseCode() throws IOException
	{
		String resp = getHeaderField(0);
		int ind;
		try
		{
			ind = resp.indexOf(' ');
			while(resp.charAt(ind) == ' ')
				ind++;
			responseCode = Integer.parseInt(resp.substring(ind, ind + 3));
			responseMessage = resp.substring(ind + 4).trim();
			return responseCode;
		}
		catch (Exception e)
		{
			return responseCode;
		}
	}

	//The private variables methods hereinafter are copied from
	// zyh.net.http.HttpURLConnection
	private Hashtable headers=null;
	private ArrayList headerFields=null;
	private ArrayList headerFieldKeys=null;
	private String getHeaderField(int n)
	{
		return headers==null?null:(headerFields.size()>n?(String)headerFields.get(n):null);
	}
	private void initHeaders()throws IOException
	{
		InputStream inputStream=  getInputStream();

		headers= new Hashtable(20);
		headerFields=new ArrayList(20);
		headerFieldKeys=new ArrayList(20);
		String line;
		String key,field;

		byte bArray[] = new byte[4096];

		while((line = readLine(inputStream,bArray)) != null
			&& line.length()>0)
		{
			int index = line.indexOf(": ");
			if (index > 0)
			{
				key=line.substring(0, index).toLowerCase();
				field=line.substring(index+2);
				headers.put(key,field);
			}
			else
			{
				key=null;
				field=line;
			}
			headerFields.add(field);
			headerFieldKeys.add(key);
		}
	}
	private static final String readLine(InputStream inputStream,
		byte bArray[])throws IOException
	{
		int readByteNumber;
		int offset=0;
		byte priorByte=-1;
		while(true)
		{
			readByteNumber=inputStream.read(bArray,offset,1);
			if(readByteNumber<0)
			{
				throw new IOException("Invalid HTTP header: Maybe socket time out.");
			}
			else if(readByteNumber>0)
			{
				if(bArray[offset]== '\n'
					&& priorByte=='\r')
					break;
				priorByte=bArray[offset];
				offset++;//offset+=readByteNumber;
			}
		}
		if(offset>=1 && bArray[offset]=='\n')
			return new String(bArray,0,offset-1);
		else
			return new String(bArray,0,offset+1);
	}

	/**
	 * Close the HTTPURLSocket() connection to the HTTP URL proxy server.
	 *
	 * @exception  IOException  if an I/O error occurs when closing this socket.
	 */
	protected void close() throws IOException
	{
		if (clientSocket != null)
		{
			try
			{
				clientSocket.close();
			}
			catch(java.io.IOException e)
			{
			}
			clientSocket=null;
		}
	}

	/*
	 * instance variable for SO_TIMEOUT
	 */
	private int timeout=18000;//A timeout of zero is interpreted as an infinite timeout.
	/*
	 * instance variable for TCP_NODELAY
	 */
	private boolean tcpNoDelay=false;
	/*
	 * instance variable for SO_LINGER
	 */
	private int soLinger=-1;
	/*
	 * instance variable for SO_SNDBUF
	 */
	private int soSndBuf=0;
	/*
	 * instance variable for SO_SNDBUF
	 */
	private int soRCVndBuf=0;


	/**
	 * Sets the maximum queue length for incoming connection indications 
	 * (a request to connect) to the <code>count</code> argument. If a 
	 * connection indication arrives when the queue is full, the 
	 * connection is refused. 
	 *
	 * @param	backlog   the maximum length of the queue.
	 * @exception  IOException  if an I/O error occurs when creating the queue.
	 */
	protected synchronized void listen(int backlog) throws IOException
	{
	}

	/**
	 * Accepts a connection. 
	 *
	 * @param	s   the accepted connection.
	 * @exception  IOException  if an I/O error occurs when accepting the
	 *			   connection.
	 */
	protected synchronized void accept(SocketImpl s) throws IOException
	{
	}



	/**
	 * Returns the address and port of this socket as a <code>String</code>.
	 *
	 * @return  a string representation of this socket.
	 */
	public String toString()
	{
		return "HTTPURLSocket[addr=" + getInetAddress() +
			",port=" + getPort() +
			",localaddr=" 
			+(getLocalAddress()!=null?getLocalAddress().toString():"127.0.0.1")
			+",localport=" + getLocalPort()
			+(getHttpURLProxyURL()!=null?",httpURLProxyURL=" + getHttpURLProxyURL():"")
			+"]";
	}

	/**
	 * Cleans up if the user forgets to close it.
	 */
	protected void finalize() throws IOException
	{
		close();
	}


	/**
	 * Fetch the value of an option.
	 * Binary options will return java.lang.Boolean(true)
	 * if enabled, java.lang.Boolean(false) if disabled, e.g.:
	 * <BR><PRE>
	 * SocketImpl s;
	 * ...
	 * Boolean noDelay = (Boolean)(s.getOption(TCP_NODELAY));
	 * if (noDelay.booleanValue()) {
	 *	 // true if TCP_NODELAY is enabled...
	 * ...
	 * }
	 * </PRE>
	 * <P>
	 * For options that take a particular type as a parameter,
	 * getOption(int) will return the paramter's value, else
	 * it will return java.lang.Boolean(false):
	 * <PRE>
	 * Object o = s.getOption(SO_LINGER);
	 * if (o instanceof Integer) {
	 *	 System.out.print("Linger time is " + ((Integer)o).intValue());
	 * } else {
	 *   // the true type of o is java.lang.Boolean(false);
	 * }
	 * </PRE>
	 *
	 * @throws SocketException if the socket is closed
	 * @throws SocketException if <I>optID</I> is unknown along the
	 *		 protocol stack (including the SocketImpl)
	 */
	public Object getOption(int opt) throws SocketException
	{
		switch (opt)
		{
		case SO_TIMEOUT:
			if(clientSocket!=null)
				timeout=clientSocket.getSoTimeout();
			return new Integer(timeout);
		case TCP_NODELAY:
			if(clientSocket!=null)
				tcpNoDelay=clientSocket.getTcpNoDelay();
			return new Boolean(tcpNoDelay);
		case SO_LINGER:
			if(clientSocket!=null)
				soLinger=clientSocket.getSoLinger();
			return new Integer(soLinger);
		case SO_BINDADDR:
			return localAddress;
		case SO_SNDBUF:
			if(clientSocket!=null)
				soSndBuf=clientSocket.getSendBufferSize();
			return new Integer(soSndBuf);
		case SO_RCVBUF:
			if(clientSocket!=null)
				soRCVndBuf=clientSocket.getReceiveBufferSize();
			return new Integer(soRCVndBuf);

		default:
			throw new SocketException("unrecognized TCP option: " + opt);
		}
	}

	/**
	 * Enable/disable the option specified by <I>optID</I>.  If the option
	 * is to be enabled, and it takes an option-specific "value",  this is
	 * passed in <I>value</I>.  The actual type of value is option-specific,
	 * and it is an error to pass something that isn't of the expected type:
	 * <BR><PRE>
	 * SocketImpl s;
	 * ...
	 * s.setOption(SO_LINGER, new Integer(10));
	 *	// OK - set SO_LINGER w/ timeout of 10 sec.
	 * s.setOption(SO_LINGER, new Double(10));
	 *	// ERROR - expects java.lang.Integer
	 *</PRE>
	 * If the requested option is binary, it can be set using this method by
	 * a java.lang.Boolean:
	 * <BR><PRE>
	 * s.setOption(TCP_NODELAY, new Boolean(true));
	 *	// OK - enables TCP_NODELAY, a binary option
	 * </PRE>
	 * <BR>
	 * Any option can be disabled using this method with a Boolean(false):
	 * <BR><PRE>
	 * s.setOption(TCP_NODELAY, new Boolean(false));
	 *	// OK - disables TCP_NODELAY
	 * s.setOption(SO_LINGER, new Boolean(false));
	 *	// OK - disables SO_LINGER
	 * </PRE>
	 * <BR>
	 * For an option that requires a particular parameter,
	 * setting its value to anything other than
	 * <I>Boolean(false)</I> implicitly enables it.
	 * <BR>
	 * Throws SocketException if the option is unrecognized,
	 * the socket is closed, or some low-level error occurred
	 * <BR>
	 * @param optID identifies the option
	 * @param value the parameter of the socket option
	 * @throws SocketException if the option is unrecognized,
	 * the socket is closed, or some low-level error occurred
	 */
	public void setOption(int opt, Object val) throws SocketException
	{
		boolean flag = true;
		switch (opt)
		{
		case SO_LINGER:
			if (val == null || (!(val instanceof Integer) && !(val instanceof Boolean)))
				throw new SocketException("Bad parameter for option");
			if (val instanceof Boolean)
			{
				/* true only if disabling - enabling should be Integer */
				flag = false;
			}
			if(clientSocket!=null)
			{
				if(flag)				
					soLinger=((Integer)val).intValue();
				else
					soLinger=clientSocket.getSoLinger();
				clientSocket.setSoLinger(flag,soLinger);
			}
			break;
		case SO_TIMEOUT:
			if (val == null || (!(val instanceof Integer)))
				throw new SocketException("Bad parameter for SO_TIMEOUT");
			int t = ((Integer) val).intValue();
			timeout=(t<0)?0:t;
			if(clientSocket!=null)
				clientSocket.setSoTimeout(timeout);
			return;
		case SO_BINDADDR:
			throw new SocketException("Cannot re-bind socket");
		case TCP_NODELAY:
			if (val == null || !(val instanceof Boolean))
				throw new SocketException("bad parameter for TCP_NODELAY");
			flag = ((Boolean)val).booleanValue();
			tcpNoDelay=flag;
			if(clientSocket!=null)
				clientSocket.setTcpNoDelay(tcpNoDelay);
			break;
		case SO_SNDBUF:
		case SO_RCVBUF:
			if (val == null || !(val instanceof Integer) ||
				!(((Integer)val).intValue() > 0))
			{
				throw new SocketException("bad parameter for SO_SNDBUF " +
					"or SO_RCVBUF");
			}
			if(clientSocket!=null)
			{
				int size=((Integer)val).intValue();
				if(opt==SO_SNDBUF)
				{
					soSndBuf=size;
					clientSocket.setSendBufferSize(size);
				}
				else
				{
					soRCVndBuf=size;
					clientSocket.setReceiveBufferSize(size);
				}
			}
			break;
		
		default:
			throw new SocketException("unrecognized TCP option: " + opt);
		}
	}
}