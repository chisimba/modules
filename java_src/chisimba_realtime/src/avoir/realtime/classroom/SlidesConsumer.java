/*
 * 
 *  Copyright (C) GNU/GPL AVOIR 2008
 * 
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if notfchat, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.classroom;

import avoir.realtime.common.Constants;
import avoir.realtime.common.user.User;
import avoir.realtime.common.packet.AckPacket;
import avoir.realtime.common.packet.FilePacket;
import avoir.realtime.common.packet.LocalSlideCacheRequestPacket;
import avoir.realtime.common.packet.RealtimePacket;
import avoir.realtime.common.packet.RemoveMePacket;
import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.net.Socket;
import java.net.UnknownHostException;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Vector;

/**
 * this class consumes packets from the server
 * @author David Wafula
 */
public class SlidesConsumer {

    private Socket socket;
    protected ObjectInputStream reader;
    protected ObjectOutputStream writer;
    private String SUPERNODE_HOST;
    private int SUPERNODE_PORT;
    private static DateFormat dateFormat = new SimpleDateFormat("yyy-MM-ddd-H-mm-ss");
    public static final String REALTIME_HOME = "/tmp/avoir-realtime-1.0.2";// System.getProperty("user.home") + "/avoir-realtime-1.0.2";

    public SlidesConsumer(String SUPERNODE_HOST, int SUPERNODE_PORT) {
        this.SUPERNODE_HOST = SUPERNODE_HOST;
        this.SUPERNODE_PORT = SUPERNODE_PORT;
        new File(REALTIME_HOME + "/log").mkdirs();
    }

    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
    }

    /**
     * Initial connection
     * @return
     */
    public boolean connect() {
        boolean result = false;
        try {
            try {

                socket = new Socket(SUPERNODE_HOST, SUPERNODE_PORT);
                result = true;

            } catch (UnknownHostException ex) {

                ex.printStackTrace();
                return false;
            }
            OutputStream out = socket.getOutputStream();
            out.write("avoirdesktopclient".getBytes());
            out.flush();
            out.write('\n');
            out.flush();
            writer = new ObjectOutputStream(new BufferedOutputStream(out));
            writer.flush();
            reader = new ObjectInputStream(new BufferedInputStream(socket.getInputStream()));
            result = true;


            Thread t = new Thread() {

                @Override
                public void run() {


                    listen();
                }
            };
            t.start();

        } catch (IOException ex) {

            ex.printStackTrace();
        }
        return result;
    }
    boolean running = true;

    /**
     * Resquests user list, but in turn also gets published
     */
    public void publish(User user) {
        sendPacket(new AckPacket(user, user.isPresenter()));
    }

    public void removeMe(String id) {
        sendPacket(new RemoveMePacket(id));
    }

    /**
     * sends a TCP Packet to Super Node
     * @param packet
     */
    public synchronized void sendPacket(RealtimePacket packet) {
        try {
            log("Send " + packet);
            if (writer != null) {
                writer.writeObject(packet);
                writer.flush();

            } else {
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public void listen() {
        while (running) {
            try {
                Object packet = null;
                try {
                    packet = reader.readObject();
                   // log("" + packet);
                } catch (Exception ex) {
                    running = false;
                    ex.printStackTrace();
                    break;

                }
                if (packet instanceof LocalSlideCacheRequestPacket) {
                    processLocalSlideCacheRequest((LocalSlideCacheRequestPacket) packet);
                }
            } catch (Exception ex) {
                //       JOptionPane.showMessageDialog(null, "broke off\n"+ex);
                ex.printStackTrace();
                running = false;
                break;
            }
        }
    }

    private static void write(String txt, String filename) {
        try {
            FileWriter outFile = new FileWriter(filename, true);
            PrintWriter printWriter = new PrintWriter(outFile);
            printWriter.println(txt);
            printWriter.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void log(String s) {
        write("-------\r\n" + getDateTime() + "------\r\n" + s + "------\r\n", REALTIME_HOME + "/log/errors.txt");
    }

    /**
     * gets the jpg  file names generated from the presentation
     * @param contentPath
     * @param objectOut
     * @return
     */
    public int[] getImageFileNames(String contentPath) {
        File dir = new File(contentPath);
        String[] fileList = dir.list();

        if (fileList != null) {

            java.util.Arrays.sort(fileList);
            Vector newList = new Vector();
            int totalSlides = 0;
            int c = 0;
            for (int i = 0; i < fileList.length; i++) {
                if (fileList[i].endsWith(".jpg")) {
                    newList.addElement(fileList[i]);
                    totalSlides++;
                    c++;
                }
            }
            int[] imgNos = new int[newList.size()];
            for (int i = 0; i < newList.size(); i++) {
                String fn = (String) newList.elementAt(i);

                if (fn != null) {
                    for (int j = 0; j < fn.length(); j++) {
                        if (Character.isDigit(fn.charAt(j))) {
                            int imgNo = Integer.parseInt(fn.substring(fn.indexOf(fn.charAt(j)), fn.indexOf(".jpg")));
                            imgNos[i] = imgNo;
                            break;
                        }
                    }
                }
            }
            java.util.Arrays.sort(imgNos);
            return imgNos;

        }
        Constants.log(contentPath + " does not exist!!!");
        return null;
    }

    /**
     * Read file in binary mode
     * @param filePath
     * @return
     */
    public byte[] readFile(String filePath) {
        File f = new File(filePath);
        File parentFile = f.getParentFile();

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

    /***
     * ///////////////////////////////////////////////////////////////////
     * These methods are for replying back to the server
     * They all start with reply pre-appended
     * //////////////////////////////////////////////////////////////////
     */
    public void replySlide(final String filePath,
            final String sessionId,
            final String username,
            final String filename,
            final boolean lastFile,
            int currentValue, int maxValue, String presentationName) {
        byte[] byteArray = readFile(filePath);
        FilePacket packet = new FilePacket(sessionId, username,
                byteArray, filename, lastFile, presentationName);
        packet.setMaxValue(maxValue);
        packet.setCurrentValue(currentValue);
        sendPacket(packet);
        log("slide server Sending " + filename);

    }

    /**
     * Creates and returns a {@link java.lang.String} from <code>t</code>’s stacktrace
     * @param t Throwable whose stack trace is required
     * @return String representing the stack trace of the exception
     */
    public String getStackTrace(Throwable t) {
        StringWriter stringWritter = new StringWriter();
        PrintWriter printWritter = new PrintWriter(stringWritter, true);
        t.printStackTrace(printWritter);
        printWritter.flush();
        stringWritter.flush();

        return stringWritter.toString();
    }

    public void processLocalSlideCacheRequest(LocalSlideCacheRequestPacket packet) {
        try {
            log("slide server received " + packet.getSlidesServerId()+" for "+packet.getPathToSlides());
            int slides[] = getImageFileNames(packet.getPathToSlides());
            String slidesPath = packet.getPathToSlides();
            //log("slide server received request: , " + slides.length + " for " + packet.getPathToSlides());
            // i hope to use threads here..dont know if it will help
            if (slides != null) {
                for (int i = 0; i <
                        slides.length; i++) {
                    String filename = "img" + i + ".jpg";
                    String filePath = slidesPath + "/" + filename;
                    boolean lastFile = i == (slides.length - 1) ? true : false;
                    replySlide(filePath, packet.getSessionId(), packet.getUsername(), filename, lastFile, i, slides.length, new File(slidesPath).getName());
                }
            }
        } catch (Exception ex) {

            log(getStackTrace(ex));
        }
    }
}
