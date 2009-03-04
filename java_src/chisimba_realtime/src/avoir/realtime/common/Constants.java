/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 *
 * @author developer
 */
public class Constants {

    private static DateFormat dateFormat = new SimpleDateFormat("yyy-MM-ddd-H-mm-ss");
    public static final String REALTIME_HOME = System.getProperty("user.home") + "/avoir-realtime-1.0.2";

    public static String getRealtimeHome() {
        return REALTIME_HOME;
    }
    public static final int ADD_NEW_ITEM = 1;
    public static final int REMOVE_ITEM = 2;
    public static final int REPLACE_ITEM = 3;
    public static final int CLEAR_ITEMS = 4;
    public static final int PAINT_BRUSH = 10;
    public static final int HAND_LEFT = 11;
    public static final int HAND_RIGHT = 12;
    public static final int ARROW_UP = 13;
    public static final int ARROW_SIDE = 14;
    public static final int WHITEBOARD = 15;
    public static final int NO_POINTER = 16;
    public static final int PRESENTATION = 17;
    public static final int IMAGE = 18;
    public static final int FILE_UPLOAD = 22;
    public static final int QUESTION_IMAGE = 23;
    public static final int ESSAY_QUESTION = 24;
    public static final int MCQ_QUESTION = 25;
    public static final int TRUE_FALSE_QUESTION = 26;
    public static final int DOCUMENT = 27;
    public static final int SLIDE_BUILDER_TEXT = 28;
    public static final int QUESTION_FILE = 29;
    public static final int SLIDE_BUILDER_IMAGE = 30;
    public static final int SLIDE_SHOW = 31;
    public static final int SLIDE_SHOW_VIEW = 32;
    public static final int NOTEPAD = 33;
    public static final int ANY = 19;
    public static final int FLASH = 20;
    public static final int WEBPAGE = 21;
    public static final int MAX_THUMBNAIL_INDEX = 6;
    public static final boolean TEMPORARY_MESSAGE = true;
    public static final boolean LONGTERM_MESSAGE = false;
    public static final boolean INFORMATION_MESSAGE = false;
    public static final boolean ERROR_MESSAGE = true;

    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
    }
    public static int APPLET = 0;
    public static int WEBSTART = 1;

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

    public static void log(Exception e) {
        try {
            StringWriter sw = new StringWriter();
            PrintWriter pw = new PrintWriter(sw);
            e.printStackTrace(pw);
            write("-------\r\n" + getDateTime() + "------\r\n" + sw.toString() + "------\r\n", REALTIME_HOME + "/log/errors.txt");
        } catch (Exception e2) {
        }
    }
}
