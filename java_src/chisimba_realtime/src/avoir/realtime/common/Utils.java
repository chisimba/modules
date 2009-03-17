package avoir.realtime.common;

import java.io.*;

import java.text.ParseException;
import java.text.SimpleDateFormat;

import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;
import java.util.zip.*;
import java.util.Vector;

import javax.swing.*;
import java.awt.*;
import java.text.DateFormat;

/**
 *
 */
public class Utils {

    final static JWindow intro = new JWindow();
    static JProgressBar pb = new JProgressBar();
    static JDialog errorFrame = new JDialog();// , true,
    // true, true, true);
    static JTextArea errorField = new JTextArea();
    final static JLabel info = new JLabel("", JLabel.CENTER);
    public final static String jpeg = "jpeg";
    public final static String jpg = "jpg";
    public final static String gif = "gif";
    public final static String tiff = "tiff";
    public final static String tif = "tif";
    public final static String png = "png";
    public final static String odp = "odp";
    public final static String xls = "xls";
    private static ImageIcon loadingIcon = ImageUtil.createImageIcon("/icons/loading.gif");

    /*
     * Get the extension of a file.
     */
    public static String getExtension(File f) {
        String ext = null;
        String s = f.getName();
        int i = s.lastIndexOf('.');

        if (i > 0 && i < s.length() - 1) {
            ext = s.substring(i + 1).toLowerCase();
        }
        return ext;
    }

    public static ImageIcon createImageIcon(String path) {
        try {
            java.net.URL imageURL = Utils.class.getResource(path);
            if (imageURL == null) {
                imageURL = Utils.class.getClassLoader().getResource(path);
            }
            if (imageURL == null) {
                imageURL = ClassLoader.getSystemResource(path);
            }
            if (imageURL != null) {
                ImageIcon icon = new ImageIcon(imageURL);
                return icon;
            }
        } catch (Exception ex) {
            return null;
        }
        return null;
    }

    /*  public static void sendMail(String subject, String content, String from) {
    try {
    // Create a mail session
    java.util.Properties props = new java.util.Properties();
    props.put("mail.smtp.host", AppProps
    .getConnectionProperty("smtp_host"));
    props.put("mail.smtp.port", ""
    + AppProps.getConnectionProperty("smtp_port"));
    Session session = Session.getDefaultInstance(props, null);

    // Construct the message
    Message msg = new MimeMessage(session);

    msg.setFrom(new InternetAddress(from));
    msg.setRecipient(Message.RecipientType.TO, new InternetAddress(
    AppProps.getConnectionProperty("to")));
    msg.setSubject(subject);
    msg.setText(content);

    // Send the message
    Transport.send(msg);
    msg("Message Sent");
    } catch (Exception ex) {
    err("Error: " + ex.getMessage());
    }

    }
     */
    public InputStream getPropertiesFile(String fileName) {
        ClassLoader cl = this.getClass().getClassLoader();

        InputStream in = cl.getResourceAsStream(fileName);
        return in;
    }

    public String getPropertiesFileName(String fileName) {
        ClassLoader cl = this.getClass().getClassLoader();

        return cl.getResource(fileName).toString();

    }

    public static void showStatusWindow(String msg, boolean showPb) {
        intro.setSize(300, 100);
        info.setText(msg);
        intro.getContentPane().setLayout(new BorderLayout());
        intro.getContentPane().add(info, BorderLayout.CENTER);

        if (showPb) {
            intro.getContentPane().add(pb, BorderLayout.SOUTH);
        }

        intro.setVisible(true);
        intro.setLocationRelativeTo(null);
    }

    public static void setStatusMessage(String txt) {
        info.setHorizontalTextPosition(SwingConstants.CENTER);
        info.setText(txt);


    }

    public static void setStatusMin(int val) {
        pb.setMinimum(val);
    }

    public static void setStatusStringPainted(boolean stringPainted) {
        pb.setStringPainted(stringPainted);
    }

    public static void setStatusMax(int val) {
        pb.setMaximum(val);
    }
    /**
     * Get todays datetime
     * @return
     */
    private static DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");

    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
    }

    /**
     * Logs the chat of a specific session
     * @param fileName
     * @param txt
     */
    public static void log(String txt) {
        try {

            FileWriter outFile = new FileWriter("JExamLog.txt", true);
            PrintWriter printWriter = new PrintWriter(outFile);
            printWriter.println(getDateTime() + ": " + txt);
            printWriter.close();
        } catch (IOException e) {
            e.printStackTrace();
        }


    }

    /**
     * Logs the chat of a specific session
     * @param fileName
     * @param txt
     */
    public static void log(String fileName, String txt) {
        try {
            BufferedWriter out = new BufferedWriter(new FileWriter(fileName, true));
            out.write(txt);
            out.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static java.util.Vector readFile(String file) {
        Vector lines = new Vector();

        try {
            BufferedReader in = new BufferedReader(new FileReader(file));
            String line;
            while ((line = in.readLine()) != null) {
                lines.addElement(line);
            }

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return lines;
        /*
         * outputStream = new BufferedWriter(new
         * FileWriter("characteroutput.txt"));
         */
    }

    public static void setStatusValue(int val) {
        pb.setValue(val);
    }

    public static void disposeStatusWindow() {
        if (intro != null) {
            intro.dispose();
        }
    }

    public static int getCurrentYear() {
        Calendar c = Calendar.getInstance();
        return c.get(c.YEAR);
    }

    public static String formatDate(Date date) {
        try {
            SimpleDateFormat formatter = new SimpleDateFormat(
                    "yyyy-MM-dd H:mm:ss", new Locale("en_US"));

            return formatter.format(date);
        } catch (Exception e) {
            return date + "";
        }
    }

    public static String formatDate(Date date, String pattern) {
        try {
            SimpleDateFormat formatter = new SimpleDateFormat(pattern,
                    new Locale("en_US"));

            return formatter.format(date);
        } catch (Exception e) {
            return date + "";
        }
    }

    public static void msg(String msg) {
        JOptionPane.showMessageDialog(null, msg, "JExam",
                JOptionPane.INFORMATION_MESSAGE);
    }

    public static void err(String msg) {
        JOptionPane.showMessageDialog(null, msg, "JExam Error",
                JOptionPane.ERROR_MESSAGE);
    }

    public static void warn(String msg) {
        JOptionPane.showMessageDialog(null, msg, "JExam",
                JOptionPane.WARNING_MESSAGE);
    }

    public static void zip(String[] filenames, String outFilename) {
        // Create a buffer for reading the files
        byte[] buf = new byte[1024];

        try {
            // Create the ZIP file
            ZipOutputStream out = new ZipOutputStream(new FileOutputStream(
                    outFilename));

            // Compress the files
            for (int i = 0; i < filenames.length; i++) {
                FileInputStream in = new FileInputStream(filenames[i]);

                // Add ZIP entry to output stream.
                out.putNextEntry(new ZipEntry(filenames[i]));

                // Transfer bytes from the file to the ZIP file
                int len;

                while ((len = in.read(buf)) > 0) {
                    out.write(buf, 0, len);
                }

                // Complete the entry
                out.closeEntry();
                in.close();
            }

            // Complete the ZIP file
            out.close();
        } catch (IOException e) {
        }
    }

    public static Date addDays(Date date, String DATE_FORMAT, int days) {
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat(
                DATE_FORMAT);
        Calendar c1 = Calendar.getInstance();
        c1.setTime(date);
        c1.add(Calendar.DATE, days);

        return c1.getTime();
    }

    public static int getDateDiff(String sdate1, String sdate2, String fmt,
            TimeZone tz, String type) {
        SimpleDateFormat df = new SimpleDateFormat(fmt);
        Date date1 = null;
        Date date2 = null;

        try {
            date1 = df.parse(sdate1);
            date2 = df.parse(sdate2);
        } catch (ParseException pe) {
            pe.printStackTrace();
        }

        Calendar cal1 = null;
        Calendar cal2 = null;

        if (tz == null) {
            cal1 = Calendar.getInstance();
            cal2 = Calendar.getInstance();
        } else {
            cal1 = Calendar.getInstance(tz);
            cal2 = Calendar.getInstance(tz);
        }

        // different date might have different offset
        cal1.setTime(date1);

        long ldate1 = date1.getTime() + cal1.get(Calendar.ZONE_OFFSET) + cal1.get(Calendar.DST_OFFSET);

        cal2.setTime(date2);

        long ldate2 = date2.getTime() + cal2.get(Calendar.ZONE_OFFSET) + cal2.get(Calendar.DST_OFFSET);

        // Use integer calculation, truncate the decimals
        int hr1 = (int) (ldate1 / 3600000); // 60*60*1000
        int hr2 = (int) (ldate2 / 3600000);

        int days1 = (int) hr1 / 24;
        int days2 = (int) hr2 / 24;

        int dateDiff = days2 - days1;
        int weekOffset = ((cal2.get(Calendar.DAY_OF_WEEK) - cal1.get(Calendar.DAY_OF_WEEK)) < 0) ? 1 : 0;
        int weekDiff = (dateDiff / 7) + weekOffset;
        int yearDiff = cal2.get(Calendar.YEAR) - cal1.get(Calendar.YEAR);
        int monthDiff = ((yearDiff * 12) + cal2.get(Calendar.MONTH)) - cal1.get(Calendar.MONTH);

        if (type.equalsIgnoreCase("date")) {
            return dateDiff;
        }

        if (type.equalsIgnoreCase("week")) {
            return weekDiff;
        }

        if (type.equalsIgnoreCase("month")) {
            return monthDiff;
        }

        if (type.equalsIgnoreCase("year")) {
            return yearDiff;
        } else {
            return dateDiff;
        }
    }
}
