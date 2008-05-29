package zyh.net;

import java.io.PrintStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.ByteArrayOutputStream;

import java.net.ServerSocket;
import java.net.Socket;
import java.net.InetAddress;

import java.util.StringTokenizer;
import zyh.util.ThreadPool;

import java.util.Hashtable;


/**
 * An HttpURL Proxy which helps Applications to through firewall 
 */
public class HttpURLProxy implements Runnable
{
	private ThreadPool threadPool=null;

	private ServerSocket serverSocket;
	private int listeningPort;
	private int backlog;

	/*
	 * An ojbect for output buffer
	 */
	private ByteArrayOutputStream outputBuffer;

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


	public HttpURLProxy(
		zyh.util.ThreadPool threadPool,
		int listeningPort,
		int backlog)throws IOException
	{this(threadPool,listeningPort,backlog,System.out);}

	public HttpURLProxy(
		zyh.util.ThreadPool threadPool,
		int listeningPort,
		int backlog,
		PrintStream logStream
		)throws IOException
	{
		this.threadPool =threadPool;

		this.listeningPort =listeningPort;
		this.backlog =backlog;

		this.logStream =logStream;

		serverSocket = new ServerSocket(listeningPort,backlog);
		log("HttpURLProxy will relay all HTTP URL requests on "+InetAddress.getLocalHost().getHostAddress()+":"+listeningPort+".");

		outputBuffer= new ByteArrayOutputStream(1024);

		this.threadPool.run(this);
	}

	private boolean isStopped=false;
	public void stop()
	{
		isStopped=true;
	}

	//Complement or modify directly login function to refuse any uninvited request
	protected boolean login(String user,String passwd)
	{
		//if(...)return false;
		return true;
	}

	public final void run()
	{		
		int requestCount=0;
		Socket clientSocket=null,destinationSocket=null;

		while(!isStopped)
		{
			try
			{
				clientSocket=serverSocket.accept();
				requestCount++;
				try
				{
					destinationSocket=getDestinationSocket(clientSocket);

					outputBuffer.reset();//HTTP_OK
					outputBuffer.write("HTTP/1.0 200 HTTP URL Proxy\r\n\r\n".getBytes());
					OutputStream out=clientSocket.getOutputStream();
					outputBuffer.writeTo(out);
					out.flush();

					log("Request"+requestCount+" from "
						+clientSocket.getInetAddress().getHostAddress()
						+" to "
						+destinationSocket.getInetAddress().getHostAddress()
						+destinationSocket.getPort()+".");
					PlainSocketRelay tr1= new PlainSocketRelay(threadPool,clientSocket,destinationSocket);
					PlainSocketRelay tr2= new PlainSocketRelay(threadPool,destinationSocket,clientSocket);
				}
				catch(Exception e)
				{
					outputBuffer.reset();//HTTP_BAD_REQUEST
					String errorMessage="HTTP/1.0 400 HTTP URL Proxy Bad Request\r\n"
						+"Error Report: "+e.getMessage()+"\r\n\r\n";
					outputBuffer.write(errorMessage.getBytes());

					OutputStream out=clientSocket.getOutputStream();
					outputBuffer.writeTo(out);
					out.flush();

					throw new IOException(e.toString());
				}				
			}
			catch(IOException ioe)
			{
				log("Request"+requestCount+": "+ioe.getMessage());
				if(clientSocket!=null)
				{
					try
					{
						clientSocket.close();
					}
					catch(IOException ioe1)
					{
						log("Request"+requestCount+": "+ioe1.toString());
					}
					clientSocket=null;
				}
				if(destinationSocket!=null)
				{
					try
					{
						destinationSocket.close();
					}
					catch(IOException ioe1)
					{
						log("Request"+requestCount+": "+ioe1.getMessage());
					}
					destinationSocket=null;
				}
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
		serverSocket=null;
	}

	private synchronized Socket getDestinationSocket(Socket clientSocket)throws IOException
	{
		Hashtable parameters=getParameters(clientSocket.getInputStream(),lineBuffer);

		String destinationHost=(String)parameters.get("sessionHost");
		if(destinationHost==null)
			throw new IOException("sessionHost is null value");

		InetAddress destinationInetAddress=InetAddress.getByName(destinationHost);
		String destinationPortStr=(String)parameters.get("sessionPort");
		if(destinationPortStr==null)
			throw new IOException("sessionPort is null value");
		int destinationPort=Integer.parseInt(destinationPortStr);
		if(listeningPort==destinationPort
			&& destinationInetAddress.equals(InetAddress.getLocalHost()))
			throw new IOException("It's impossible to set destinationPort as same as listeningPort at the same port:"+listeningPort+" of one machine.");

		/*
		 * Refuse uninvited request according to your extended login function
		 */
		String user=(String)parameters.get("sessionUser");
		String passwd=(String)parameters.get("sessionPasswd");

		if(login(user,passwd))
			return new Socket(destinationInetAddress, destinationPort);
		else
			throw new IOException("HttpURLProxy Login failed.");
	}

	//Note: Content-length<=4096 and per header line's size<=4096
	//Otherwise use a bigger integer than 4096
	private byte[] lineBuffer=new byte[4096];
	private static final Hashtable getParameters(InputStream inputStream,byte bArray[])throws IOException
	{
		int contentLength=getContentLength(inputStream,bArray);
		if(contentLength<=0)
			throw new IOException("Not a valid HttpURLSocket post request.");
		int readByteNumber;
		int offset=0;
		while(contentLength>0)
		{
			readByteNumber=inputStream.read(bArray,offset,contentLength);
			if(readByteNumber<0)
				throw new IOException("Not a valid HttpURLSocket post request.");
			offset+=readByteNumber;
			contentLength-=readByteNumber;
		}
		StringTokenizer st = new StringTokenizer(new String(bArray,0,offset), "&");
		Hashtable parameters=new Hashtable(20);
		int equalTokenIndex=0;
		while (st.hasMoreTokens())
		{
			String temp =st.nextToken();
			if(temp.length()<2)continue;
			equalTokenIndex=temp.indexOf('=');
			if(equalTokenIndex==0)continue;
			String name =temp.substring(0,equalTokenIndex);
			String value=equalTokenIndex==temp.length()-1?
				"":temp.substring(equalTokenIndex + 1, temp.length());
			parameters.put(name, value);
		}
		return parameters;
	}
	private static final int getContentLength(InputStream inputStream,byte bArray[])throws IOException
	{
		String line;
		int index;
		String key;
		int contenLength=-1;
		while((line = readLine(inputStream,bArray)) != null
			&& line.length()>0)
		{
			index = line.indexOf(": ");
			if (index > 0)
			{
				key=line.substring(0, index).toLowerCase();
				if(key.equalsIgnoreCase("Content-length"))
				{
					contenLength=Integer.parseInt(line.substring(index+2));
				}
			}
		}
		return contenLength;
	}
	//The private variables methods hereinafter are copied from
	// zyh.net.http.HttpURLConnection
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

	//The private variables methods hereinafter are copied from
	// zyh.net.http.HttpServletRequest
	protected static String decode(byte[] bs,int length)
	{
		int bcounter=0;
		for(int i=0;i<length;i++)
		{
			if(bs[i]==(byte)'%' && ((i+3)<=bs.length))
			{
				i+=2;
				bs[bcounter]=hatob(bs[i-1],bs[i]);
				if (bs[bcounter]==(byte)'&')
					bs[bcounter]=(byte)' ';
			}
			else
			{
				if (bs[i]==(byte)'+')
					bs[bcounter]=(byte)' ';
				else
					bs[bcounter]=bs[i];
			}
			bcounter++;
		}
		return new String(bs,0,bcounter);
    }
    private static byte hatob(byte b1,byte b2)
	{
		byte b;
		if (b1>=(byte)'0' && b1<=(byte)'9')
			b=(byte)(b1-'0');
		else
			b=(byte)(b1-'A'+10);
		if (b2>='0' && b2<='9')
			b=(byte)((b<<4)+b2-'0');
		else
			b=(byte)((b<<4)+b2-'A'+10);
		return b;
	}


	public static void main(String args[]) throws Exception
	{
		try
		{
			int listeningPort=8029;
			int backlog=100;
			if(args.length>0)
			{
				listeningPort=Integer.parseInt(args[0]);
				if(args.length>=2)
					backlog=Integer.parseInt(args[1]);
			}
			System.out.println("Useage: java zyh.net.HttpURLProxy [listeningPort(int,default value:8029) [the_maximum_length_of_the_queue(default value:100)]]");

			ThreadPool threadPool=new ThreadPool(10,30);

			HttpURLProxy httpURLProxy=new HttpURLProxy(
				threadPool,
				listeningPort,backlog,
				System.out);
		}
		catch(Exception e)
		{
			e.printStackTrace();
			System.out.println(e.getMessage());
		}
    }

}