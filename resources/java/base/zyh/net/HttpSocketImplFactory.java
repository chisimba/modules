package zyh.net;

import java.io.IOException;

import java.net.Socket;
import java.net.SocketImpl;
import java.net.SocketImplFactory;
import java.net.InetAddress;
import java.net.UnknownHostException;

import java.util.Properties;

/**
 * HttpSocketImplFactory class defines a factory for HttpSocket implementations.
 * HttpSocket class implements client sockets through http proxy server with SSL Tunneling Support
 */
public class HttpSocketImplFactory implements SocketImplFactory
{
	/*
	 * The IP address of the HTTP proxy server.
	 */
	private InetAddress proxyAddress=null;
	/*
	 * The port number on the HTTP proxy serer.
	 */
	private int proxyPort=HttpSocket.HTTP_PROXY_PORT;
	/*
	 *  a boolean indicating whether this is a stream socket or a datagram socket.
	 *  If the stream argument is true, this creates a stream socket. If the stream argument is
     *  false, it creates a datagram socket.
	 *  Now HttpSocket can't support datagram socket.
	 */
	private boolean stream=true;
	/*
	 * Some proxy settings for the HTTP proxy serer.
	 */
	private Properties properties=null;

	public HttpSocketImplFactory(){}
	public HttpSocketImplFactory(boolean stream)
	{
		this.stream=stream;
	}
	public HttpSocketImplFactory(String proxyHost,int proxyPort)throws UnknownHostException
	{
		this(proxyHost,proxyPort,null,true);
	}
	public HttpSocketImplFactory(String proxyHost,int proxyPort,boolean stream)throws UnknownHostException
	{
		this(proxyHost,proxyPort,null,stream);
	}
	public HttpSocketImplFactory(String proxyHost,int proxyPort,Properties properties)throws UnknownHostException
	{
		this(proxyHost,proxyPort,properties,true);
	}
	public HttpSocketImplFactory(String proxyHost,int proxyPort,Properties properties,boolean stream)throws UnknownHostException
	{
		this(proxyHost==null?null:InetAddress.getByName(proxyHost),proxyPort,properties,stream);
	}
	public HttpSocketImplFactory(InetAddress proxyAddress,int proxyPort)
	{
		this(proxyAddress,proxyPort,null,true);
	}
	public HttpSocketImplFactory(InetAddress proxyAddress,int proxyPort,boolean stream)
	{
		this(proxyAddress,proxyPort,null,stream);
	}
	public HttpSocketImplFactory(InetAddress proxyAddress,int proxyPort,Properties properties)
	{
		this(proxyAddress,proxyPort,properties,true);
	}
	public HttpSocketImplFactory(InetAddress proxyAddress,int proxyPort,Properties properties, boolean stream)
	{
		this.proxyAddress=proxyAddress;
		this.proxyPort=proxyPort;
		this.properties=properties;
		this.stream=stream;
	}


    /**
     * Creates a new <code>SocketImpl</code> instance.
     *
     * @return  a new instance of <code>SocketImpl</code>.
     * @see     java.net.SocketImpl
     */
	public SocketImpl createSocketImpl()
	{
		return new HttpSocketImpl(proxyAddress,proxyPort,properties,stream);
	}
}