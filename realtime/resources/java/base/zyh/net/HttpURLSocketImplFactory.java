package zyh.net;

import java.io.IOException;

import java.net.URL;
import java.net.Socket;
import java.net.SocketImpl;
import java.net.SocketImplFactory;
import java.net.InetAddress;
import java.net.MalformedURLException;
import java.net.UnknownHostException;

import java.util.Properties;

/**
 * HttpURLSocketImplFactory class defines a factory for HttpURLSocket implementations.
 * HttpSocket class implements client sockets through http proxy server with SSL Tunneling Support
 */
public class HttpURLSocketImplFactory implements SocketImplFactory
{
	/*
	 * The IP address of the HTTP proxy server.
	 */
	private InetAddress httpProxyAddress=null;
	/*
	 * The port number on the HTTP proxy serer.
	 */
	private int httpProxyPort=HttpURLSocket.HTTP_PROXY_PORT;
	/*
	 * The HTTP URL proxy serer's URL.
	 */
	private URL httpURLProxyURL=null;
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

	public HttpURLSocketImplFactory(){}
	public HttpURLSocketImplFactory(boolean stream)
	{
		this.stream=stream;
	}
	public HttpURLSocketImplFactory(String proxyHost,int httpProxyPort,String urlProxySpec)throws MalformedURLException,UnknownHostException
	{
		this(proxyHost,httpProxyPort,urlProxySpec,null,true);
	}
	public HttpURLSocketImplFactory(String proxyHost,int httpProxyPort,String urlProxySpec,boolean stream)throws MalformedURLException,UnknownHostException
	{
		this(proxyHost,httpProxyPort,urlProxySpec,null,stream);
	}
	public HttpURLSocketImplFactory(String proxyHost,int httpProxyPort,String urlProxySpec,Properties properties)throws MalformedURLException,UnknownHostException
	{
		this(proxyHost,httpProxyPort,urlProxySpec,properties,true);
	}
	public HttpURLSocketImplFactory(String proxyHost,int httpProxyPort,String urlProxySpec,Properties properties,boolean stream)throws MalformedURLException,UnknownHostException
	{
		this(proxyHost==null?null:InetAddress.getByName(proxyHost),httpProxyPort,urlProxySpec==null?null:new URL(urlProxySpec),properties,stream);
	}
	public HttpURLSocketImplFactory(InetAddress httpProxyAddress,int httpProxyPort,URL urlProxyURL)
	{
		this(httpProxyAddress,httpProxyPort,urlProxyURL,null,true);
	}
	public HttpURLSocketImplFactory(InetAddress httpProxyAddress,int httpProxyPort,URL urlProxyURL,boolean stream)
	{
		this(httpProxyAddress,httpProxyPort,urlProxyURL,null,stream);
	}
	public HttpURLSocketImplFactory(InetAddress httpProxyAddress,int httpProxyPort,URL urlProxyURL,Properties properties)
	{
		this(httpProxyAddress,httpProxyPort,urlProxyURL,properties,true);
	}
	public HttpURLSocketImplFactory(InetAddress httpProxyAddress,int httpProxyPort,URL urlProxyURL,Properties properties, boolean stream)
	{
		this.httpProxyAddress=httpProxyAddress;
		this.httpProxyPort=httpProxyPort;
		this.httpURLProxyURL=urlProxyURL;
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
		return new HttpURLSocketImpl(httpProxyAddress,httpProxyPort,httpURLProxyURL,properties,stream);
	}
}