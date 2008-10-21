/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.server;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.util.jar.Attributes;
import java.util.jar.JarFile;
import java.util.jar.Manifest;

/**
 *
 * @author developer
 */
public class Utils {

    static int BUFFER_SIZE = 1000;
    static int MAX_CHUNK_SIZE = 1 * BUFFER_SIZE;
    static int nChunks;
    static int currentChunkNo = 0;
    static long bytesRemaining;

    public static void splitFile(String filepath, final String destFileName,int size) {

        try {

            File file = new File(filepath);
            long fileSize = file.length();

            final FileInputStream in = new FileInputStream(file);
            MAX_CHUNK_SIZE = size;
            nChunks = (int) (fileSize / MAX_CHUNK_SIZE);
            if (fileSize % MAX_CHUNK_SIZE > 0) {
                nChunks++;
            }


            bytesRemaining = fileSize;

            String clientID = String.valueOf((long) (Long.MIN_VALUE *
                    Math.random()));

            for (int i = 0; i < nChunks; i++) {
                final int c = i;
                      read(in, c,destFileName);
              
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static boolean read(FileInputStream in, int i,String destFileName) {
        try {
            int chunkSize = (int) ((bytesRemaining > MAX_CHUNK_SIZE) ? MAX_CHUNK_SIZE : bytesRemaining);
            bytesRemaining -= chunkSize;
            byte[] buf = new byte[chunkSize];

            int read = in.read(buf);

            if (read == -1) {
                return false;
            } else if (read > 0) {
                // FileOutputStream out = new FileOutputStream("swt/File" + i + ".jar");
                // out.write(buf);
                joinFile(buf, "temp_swt", destFileName, nChunks, i);
            }
        } catch (IOException ex) {
            ex.printStackTrace();
            return false;
        }
        return true;
    }

    public static synchronized void joinFile(byte[] buf, String tempName, String filename, int totalChunks, int chunkNo) {
        try {
            FileOutputStream out = new FileOutputStream(tempName, true);
            out.write(buf);
            out.close();
            if (++currentChunkNo == totalChunks) {

                File tmpFile = new File(tempName);
                File destFile = new File(filename);
                if (destFile.exists()) {
                    destFile.delete();
                }
                if (tmpFile.renameTo(destFile)) {
                    System.out.println("Done");
                } else {
                    System.out.println("Error joining the file");

                }
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    private static byte[] readFile(String filePath) {
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
                    System.out.println("Error reading the file");
                    return null;
                }
            } else {
                System.out.println(filePath + " doesn't exist.");
                return null;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }

    }

    public static void startJoin(String filename) {
        File f = new File("swt");
        String[] list = f.list();
        if (list != null) {
            for (int i = 0; i < list.length; i++) {
                joinFile(readFile("swt/" + list[i]), "swt_temp", filename, list.length, i);
            }
        }
    }
  public static int getJarVersionNo(String jarFileName) {
        try {

            JarFile jar = new JarFile(jarFileName);

            final Manifest manifest = jar.getManifest();
            final Attributes mattr = manifest.getMainAttributes();
            String verAttr = mattr.getValue("JarVersion");
           
            if (verAttr != null) {
                String[] toks = verAttr.split(",");
                int v = 0;
                for (int i = 0; i < toks.length; i++) {
                    int c = Integer.parseInt(toks[i]);
                    if (i > 0) {
                        c = c * 100;
                    }
                    v += c;
                }
                return v;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return -1;
    }
    public static void main(String[] args) {
            Utils.splitFile(args[0],args[1], Integer.parseInt(args[2]));
        
        
    }
}
