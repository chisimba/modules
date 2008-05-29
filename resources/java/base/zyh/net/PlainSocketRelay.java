package zyh.net;

import java.io.InputStream;
import java.io.OutputStream;
import java.net.ServerSocket;
import java.net.Socket;

import zyh.util.ThreadPool;

/**
 *
 * PlainSocketRelay
 *
 */
public class PlainSocketRelay implements Runnable {

    private Socket clientSocket = null;
    private InputStream clientInput = null;
    private Socket relaySocket = null;
    private OutputStream relayOutput = null;
    private byte buffer[] = new byte[2048];

    public PlainSocketRelay(zyh.util.ThreadPool threadPool,
            Socket clientSocket, Socket relaySocket) throws java.io.IOException {
        this.clientSocket = clientSocket;
        this.clientInput = clientSocket.getInputStream();

        this.relaySocket = relaySocket;
        this.relayOutput = relaySocket.getOutputStream();

        threadPool.run(this);
    }
    private boolean isStopped = false;

    public void stop() {
        isStopped = true;
    }

    public final void run() {
        try {
            int count;
            while (!isStopped && (count = clientInput.read(buffer, 0, buffer.length)) >= 0) {
                relayOutput.write(buffer, 0, count);
                System.out.print(new String(buffer, 0, count));
//System.out.println(zyh.util.Bytes.getHexString(buffer,0,count));
            }
        } catch (Exception e) {
//			System.err.println(e.getMessage());
        }

        try {
            clientSocket.close();
            clientSocket = null;
            clientInput = null;

            relaySocket.close();
            relaySocket = null;

            relayOutput = null;
        } catch (Exception e) {
//			System.err.println(e.getMessage());
        }
    }
}
