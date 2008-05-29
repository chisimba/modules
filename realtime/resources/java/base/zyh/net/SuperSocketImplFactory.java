package zyh.net;

import java.io.IOException;

import java.net.URL;
import java.net.Socket;
import java.net.SocketImpl;
import java.net.SocketImplFactory;
import java.net.InetAddress;
import java.net.UnknownHostException;

import java.util.Properties;

/**
 * SuperSocketImplFactory class defines a factory for SuperSocket implementations.
 * SuperSocket class implements the common interface for client sockets through 
 * SOCKS server, http proxy server and HttpURL proxy 
 */
public class SuperSocketImplFactory implements SocketImplFactory
{
	/*
	 * Socket type
	 */
	private static final int DIRECT=0;//direct link
	private static final int SOCKS=1;
	private static final int HTTP=2;
	private static final int HTTPURL=3;

	/*
	 * The IP address of the proxy server.
	 */
	private InetAddress proxyAddress=null;
	/*
	 * The port number on the proxy serer.
	 */
	private int proxyPort=HttpSocket.HTTP_PROXY_PORT;
	/*
	 *  a boolean indicating whether this is a stream socket or a datagram socket.
	 *  If the stream argument is true, this creates a stream socket. If the stream argument is
     *  false, it creates a datagram socket.
	 *  Now HttpSocket and HttpURLSocket can't support datagram socket.
	 */
	private boolean stream=true;
	/*
	 * Some proxy settings for the proxy serer.
	 */
	private Properties properties=null;

	/*
	 * The HTTP URL proxy serer's URL.
	 */
	private URL httpURLProxyURL=null;
	/*
	 * the actual socket
	 */
	private	int socketType=DIRECT;

	public SuperSocketImplFactory(Properties properties)throws IOException
	{
		if(properties==null)return;//DIRECT

		String value=properties.getProperty(SuperSocket.PROXY_TYPE);
		if(value==null)return;//DIRECT

		if(value.equalsIgnoreCase(SuperSocket.SOCKS))
		{
			socketType=SOCKS;
			proxyPort=SuperSocket.SOCKS_PORT;
		}
		else if(value.equalsIgnoreCase(SuperSocket.HTTP))
		{
			socketType=HTTP;
			proxyPort=SuperSocket.HTTP_PROXY_PORT;
		}
		else if(value.equalsIgnoreCase(SuperSocket.HTTPURL))
		{
			socketType=HTTPURL;
			proxyPort=SuperSocket.HTTP_PROXY_PORT;
		}
		else
			return;//socketType=DIRECT;//Ignore simply other values
		
		value=properties.getProperty(SuperSocket.PROXY_HOST);
		if(value!=null && value.length()>0)
			proxyAddress=InetAddress.getByName(value);
		else if(socketType!=HTTPURL)//HTTPURL can work without http proxy
			return;//DIRECT

		value=properties.getProperty(SuperSocket.PROXY_PORT);
		if(value!=null && value.length()>0)
			proxyPort=Integer.parseInt(value);

		if(socketType==HTTPURL || socketType==HTTP)
		{
			//Automatically choose between HTTP and HTTPURL
			value=properties.getProperty(SuperSocket.httpURLProxyURL);
			if(value!=null && value.length()>0)
			{
				httpURLProxyURL=new URL(value);
				if(properties.getProperty(SuperSocket.NOTEST)==null)
				{		
					if(proxyAddress!=null && testHttpSocket(proxyAddress,proxyPort,properties,httpURLProxyURL))
						socketType=HTTP;
					else
						socketType=HTTPURL;
				}
			}
			else if(proxyAddress!=null)
				socketType=HTTP;
			else
				socketType=DIRECT;
		}
	}
	private static final boolean testHttpSocket(InetAddress proxyAddress,int proxyPort, Properties properties,URL httpURLProxyURL)
	{
		HttpSocketImplFactory factory;
		try
		{
			// Create a HttpSocketImpl factory.
			factory=new HttpSocketImplFactory(proxyAddress,proxyPort,properties);
			// Used it for all HttpURLSockets create from now on.
			HttpSocket.setSocketImplFactory(factory);
		}
		catch(Exception e)
		{
			return false;
		}

		//Test HttpSocket.
		String[] webServerHosts={httpURLProxyURL.getHost(),"www.yahoo.com","www.javasoft.com"};
		int[] webServerPorts={httpURLProxyURL.getPort(),80,80};
		String[] pathes={"/","/","/"};
		for(int i=0;i<pathes.length;i++)
		{
//			System.out.println("http://"+webServerHosts[i]+":"+webServerPorts[i]+pathes[i]);
			try
			{
				Socket httpSocket=new HttpSocket(webServerHosts[i],webServerPorts[i]);
				return true;
			}
			catch(Exception ioe)
			{
//				System.out.println(ioe.toString());
			}
		}
		return false;
	}

	/**
	 * Creates a new <code>SocketImpl</code> instance.
	 *
	 * @return  a new instance of <code>SocketImpl</code>.
	 * @see	 java.net.SocketImpl
	 */
	public SocketImpl createSocketImpl()
	{
		SocketImpl impl=null;
		switch(socketType)
		{
		case SOCKS:		
			impl= new SocksSocketImpl(proxyAddress,proxyPort,properties,stream);
			break;
		case HTTP:		
			impl= new HttpSocketImpl(proxyAddress,proxyPort,properties,stream);
			break;
		case HTTPURL:
			impl= new HttpURLSocketImpl(proxyAddress,proxyPort,httpURLProxyURL,properties,stream);
			break;
		}
		return impl;
	}
}