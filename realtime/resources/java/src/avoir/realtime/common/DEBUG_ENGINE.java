/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

import java.util.Date;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.io.*;

/**
 *
 * @author developer
 */
public class DEBUG_ENGINE {
    // 0 is no debug info. 7 is full debug info
    public static int debug_level = 0;
    private static DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
    private static String filename = System.getProperty("user.home") + "/avoir-realtime-0.1/log/DEBUG.log";

    static {
        File f = new File(System.getProperty("user.home") + "/avoir-realtime-0.1/log");
        f.mkdirs();
    }

    public static void print(Class cl, String txt) {
        String line = " >>>>>>> CLASS: "+cl.getName() +"\n" + getDateTime() + "\t" + txt;
        log(line);
    }

    public static void print(String txt) {
        String line = getDateTime() + "\t" + txt;
        log(line);
    }

    /**
     * Logs the chat of a specific session
     * @param fileName
     * @param txt
     */
    private static void log(String txt) {
        try {

            FileWriter outFile = new FileWriter(filename, true);
            PrintWriter printWriter = new PrintWriter(outFile);
            printWriter.println(txt);
            printWriter.close();
        } catch (IOException e) {
            e.printStackTrace();
        }


    }

    /**
     * Get todays datetime
     * @return
     */
    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
    }
}
