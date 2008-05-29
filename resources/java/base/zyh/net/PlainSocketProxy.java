package zyh.net;

import java.io.PrintStream;
import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.InetAddress;

import zyh.util.ThreadPool;

/**
 *	@(#)PlainSocketProxy.java 1.1 1999/10/2
 *	@autohr Zhao Yonghong (zhaoyh@bigfoot.com)
 *
 *	Copyright 1998 by HXKM Inc., 9 Station Road, Xiangtan, 411100, P.R. China
 *	All rights reserved.
 *
 *	PlainSocketProxy is provided as Open Source freeware by HXKM Inc.
 *	``AS IS'' and any express or implied warranties are disclaimed.
 *	You are using PlainSocketProxy at your own risk!
 *
 *  You can distribute freely it without modification.
 *  You also can modify and disturbute it but please keep our statements unchanged.
 *  For more licences conditions, please refer to
 *  <A HREF="http://www.fsf.org/copyleft/gpl.html">GNU General Public License (GPL)</A>
 *	or
 *  <A HREF="http://language.perl.com/misc/Artistic.html">Artistic License</A>
 *
 *	@Usage: java zyh.net.PlainSocketProxy [listeningPort(int,default value:8029) serverMachineName( or IP address default:localhost) severPort(int,default value:80) [the_maximum_length_of_the_queue(default value:100)]]
 *
 *
 *  PlainSocketProxy is a good tool for one isolated LAN to access internet through one proxy on one semi-isolated WAN.
 *
 *	|----------------LAN----------------|        |----------------Internet---------------|
 *
 * 	Computer A<-------->Proxy Computer B<-------->Proxy Computer C<-------->Internet Sites
 *			                       (PlainSocketProxy)      (MS Proxy,Wingate,etc.) 
 *						|-----------------WAN---------------------|
 *
 *	In the above diagram, the browser on Computer B can use directly
 *  Computer C as proxy to access internet. Computer A can access WAN
 *  by using MS Proxy or Wingate on Computer B. Computer A can also access
 *  internet by using PlainSocketProxy on Computer B to relay all requests
 *  to the proxy on Computer C.
 *
 * 
 *	For Windows95 or NT, even in jsdk2.1(1999-4-27), there is still a Servlet failure where
 *	doPost() caused Netscape to report "A network error occurred while Netscape was receiving
 *	data (Network Error: Connection reset by peer.)" IE4.0 fails with a different message.
 *
 *	I wrote PlainSocketProxy to because I found there was not "Connection reset by peer" when browser and servlet ran on the same PC.
 *	Maybe it's a better temporary solution if you have to use doPost method like me.
 *	We wish jsdk2.2 will fix this annoying bug for Windows.
 */

public class PlainSocketProxy implements Runnable
{
	private ThreadPool threadPool=null;

	private ServerSocket serverSocket;
	private int listeningPort;
	private int backlog;

	private InetAddress destinationInetAddress;
	private int destinationPort;

	private PrintStream logStream;
	public PrintStream getLog()
	{return logStream; }
	public void setLog(PrintStream logStream)
	{this.logStream=logStream;}
	public void log(String msg)
	{
		if(logStream!=null)
			logStream.println(
				(new java.util.Date(System.currentTimeMillis())).toString()
				+": "+msg);
	}


	public PlainSocketProxy(
		zyh.util.ThreadPool threadPool,
		int listeningPort,
		int backlog,
		InetAddress destinationInetAddress,
		int destinationPort
		)throws IOException
	{this(threadPool,listeningPort,backlog,destinationInetAddress,destinationPort,System.out);}

	public PlainSocketProxy(
		zyh.util.ThreadPool threadPool,
		int listeningPort,
		int backlog,
		InetAddress destinationInetAddress,
		int destinationPort,
		PrintStream logStream
		)throws IOException
	{
		this.threadPool =threadPool;

		this.listeningPort =listeningPort;
		this.backlog =backlog;

		this.destinationInetAddress =destinationInetAddress;
		this.destinationPort =destinationPort;

		this.logStream =logStream;

		serverSocket = new ServerSocket(listeningPort,backlog);
		log("PlainSocketProxy will relay all requests on "+InetAddress.getLocalHost().getHostAddress()+":"+listeningPort+" to "+destinationInetAddress.getHostAddress()+":"+destinationPort+".");

		this.threadPool.run(this);
	}

	private boolean isStopped=false;
	public void stop()
	{
		isStopped=true;
	}

	public final void run()
	{		
		int requestCount=0;
		Socket clientSocket,destinationSocket;

		while(!isStopped)
		{
			try
			{
				clientSocket=serverSocket.accept();
				requestCount++;
				log("Request"+requestCount+" from "
					+clientSocket.getInetAddress().getHostAddress()+".");
				
				destinationSocket = new Socket(destinationInetAddress, destinationPort);

				PlainSocketRelay tr1= new PlainSocketRelay(threadPool,clientSocket,destinationSocket);
				PlainSocketRelay tr2= new PlainSocketRelay(threadPool,destinationSocket,clientSocket);
			}
			catch(IOException ioe)
			{
				log("Request"+requestCount+": "+ioe.getMessage());
			}
		}
		try
		{
			serverSocket.close();
		}
		catch (Exception e)
		{
			log(e.getMessage());
		}
	}

	public static void main(String args[]) throws Exception
	{
		try
		{
			int listeningPort=8029;
			int destinationPort=80;
			String destinationHost="localhost";
			int backlog=100;
			if(args.length>=3)
			{
				listeningPort=Integer.parseInt(args[0]);
				destinationHost=args[1];
				destinationPort=Integer.parseInt(args[2]);
				if(args.length>=4)
					backlog=Integer.parseInt(args[3]);
			}
			else if(args.length>0)
			{
				System.out.println("Useage: java zyh.net.PlainSocketProxy [listeningPort(int,default value:8029) destinationHostName( or IP address default:localhost) destinationHostPort(int,default value:80) [the_maximum_length_of_the_queue(default value:100)]]");
			}

			InetAddress destinationInetAddress=InetAddress.getByName(destinationHost);
			if(listeningPort==destinationPort
				&& destinationInetAddress.equals(InetAddress.getLocalHost()))
				throw new Exception("It's impossible to set listeningPort and destinationPort at the same port:"+listeningPort+" of one machine.");

			ThreadPool threadPool=new ThreadPool(5,5);

			PlainSocketProxy plainSocketProxy=new PlainSocketProxy(
				threadPool,
				listeningPort,backlog,
				destinationInetAddress,destinationPort,
				System.out);
		}
		catch(Exception e)
		{
			e.printStackTrace();
			System.out.println(e.getMessage());
		}
    }
}