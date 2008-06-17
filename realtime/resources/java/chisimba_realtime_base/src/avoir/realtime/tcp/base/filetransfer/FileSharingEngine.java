/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.common.packet.BinaryFileChunkPacket;
import java.io.File;
import java.io.FileInputStream;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
 */
public class FileSharingEngine {

    RealtimeBase base;

    public FileSharingEngine(RealtimeBase base) {
        this.base = base;
    }

    private byte[] readFile(String filePath) {
        File f = new File(filePath);
        try {
            if (f.exists()) {
                FileChannel fc = new FileInputStream(f.getAbsolutePath()).getChannel();

                ByteBuffer buff = ByteBuffer.allocate((int) fc.size());
                fc.read(buff);
                if (buff.hasArray()) {
                    byte[] byteArray = buff.array();
                    fc.close();
                    return byteArray;
                } else {
                    JOptionPane.showMessageDialog(null, "Error reading the file");
                    return null;
                }
            } else {
                JOptionPane.showMessageDialog(null, filePath + " doesn't exist.");
                return null;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }

    }

    public void readBinaryFile(String filepath, int index, String rec) {
        String filename = "unknown";
        byte[] byteArray = readFile(filepath);
        base.getTcpClient().sendPacket(new BinaryFileChunkPacket(base.getSessionId(), filename, byteArray, rec, false));
        //notify that this is the last chunk
        BinaryFileChunkPacket lastChunk = new BinaryFileChunkPacket(base.getSessionId(), filename, new byte[0], rec, true);
        base.getTcpClient().sendPacket(lastChunk);
        base.getFileTransferPanel().setProgress(index, "Complete");

    /*int bufferSize = 16 * 1024;
    try {
    File f = new File(filepath);
    filename = f.getName();
    byte[] buffer = new byte[bufferSize];
    long size = f.length();
    int noOfChuncks = (int) (size / bufferSize);
    System.out.print("\nSize: ");
    System.out.println(size);
    
    if (noOfChuncks == 0) {
    Long b=new Long(size);
    buffer = new byte[b.byteValue()];
    noOfChuncks = 1;
    }
    if (size % noOfChuncks != 0) {
    noOfChuncks += 1;
    }
    System.out.println("No of chuncks: " + noOfChuncks);
    
    FileInputStream fIn = new FileInputStream(f);
    
    int read = fIn.read(buffer);
    int totalRead = 0;
    int chuncksRead = 0;
    while (read > 0) {
    chuncksRead++;
    if (chuncksRead < noOfChuncks) {
    System.out.println("hunk size:" + bufferSize);
    read = fIn.read(buffer);
    } else {
    long lastChunkSize =  size - totalRead;
    buffer = new byte[new Long(lastChunkSize).byteValue()];
    System.out.println("Last chunk size:" + lastChunkSize);
    read = fIn.read(buffer);
    }
    totalRead += read;
    base.getTcpClient().sendPacket(new BinaryFileChunkPacket(base.getSessionId(), filename, buffer, rec, false));
    base.getFileTransferPanel().setProgress(index, totalRead + "/" + size);
    }
    System.out.println(totalRead + " read of " + size);
    //notify that this is the last chunk
    BinaryFileChunkPacket lastChunk = new BinaryFileChunkPacket(base.getSessionId(), filename, new byte[0], rec, true);
    base.getTcpClient().sendPacket(lastChunk);
    } catch (IOException ex) {
    ex.printStackTrace();
    JOptionPane.showMessageDialog(null, "Cannot read file " + filename);
    }*/
    }
}
