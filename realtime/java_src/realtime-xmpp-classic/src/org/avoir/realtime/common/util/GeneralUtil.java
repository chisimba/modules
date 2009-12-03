/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.common.util;

import org.avoir.realtime.gui.main.RealtimeSysTray;

import java.awt.BorderLayout;

import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.GraphicsConfiguration;
import java.awt.GraphicsDevice;
import java.awt.GraphicsEnvironment;
import java.awt.HeadlessException;
import java.awt.Image;
import java.awt.RenderingHints;
import java.awt.Toolkit;
import java.awt.Transparency;
import java.awt.TrayIcon;
import java.awt.image.BufferedImage;
import java.awt.image.ColorModel;
import java.awt.image.PixelGrabber;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Collection;
import java.util.Date;
import java.util.Locale;
import java.util.Properties;
import java.util.Random;
import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JWindow;
import javax.swing.SwingConstants;

import org.avoir.realtime.common.Constants;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.jivesoftware.smack.util.Base64;
import org.jivesoftware.smackx.muc.Affiliate;
import org.jivesoftware.smackx.muc.Occupant;
import org.jivesoftware.smackx.packet.VCard;

/**
 *
 * @author developer
 */
public class GeneralUtil {

    static Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    final static String version = "1.0.2 beta";
    public final static String about = "<html><h1>Chisimba Realtime Tools<h1><br>Version: " + version +
            "<br><h3><strong>" +
            "Credits:</strong></h3>" +
            "<br>" +
            "David Wafula<br>" +
            "Feroz Zaidi" +
            "<br>" +
            "Simoni Crause<br>" +
            "Dean Wookey<br>" +
            "Sheng Yan Lim";
    final static JWindow intro = new JWindow();
    final static JLabel info = new JLabel("", JLabel.CENTER);
    static Properties props;
    //user can install the app in any folder..so use the System class to figure out where
    static String fileName = System.getProperty("user.home") +
            "/avoir-realtime-1.0.2/conf/realtime.properties";
    static String tmpDir = System.getProperty("user.home") +
            "/avoir-realtime-1.0.2/tmp/";

    static {
        init();
    }

    public static void showChatPopup(final String user, final String message, final boolean showHeader) {
        Thread t = new Thread() {

            @Override
            public void run() {
                GUIAccessManager.mf.getChatRoomManager().getChatPopup().setMessage(user, message, showHeader);
                GUIAccessManager.mf.getChatRoomManager().getChatPopup().setLocation(ss.width - 200, ss.height - GUIAccessManager.mf.getChatRoomManager().getChatPopup().getHeight() - 100);
                GUIAccessManager.mf.getChatRoomManager().getChatPopup().setVisible(true);
                //trayIcon.setImage(newimage);
                if (showHeader) {
                    GUIAccessManager.mf.getRealtimeSysTray().updateTrayIcon();
                }
            }
        };
        t.start();
    }

    public static int getCurrentYear() {
        Calendar c = Calendar.getInstance();
        return c.get(c.YEAR);
    }

    public static String getUsername(String nickname) {
        /**
         * user - the room occupant to search for his presence. 
         * The format of user must be: roomName@service/nickname
         * (e.g. darkcave@macbeth.shakespeare.lit/thirdwitch).
         */
        String xjid = ConnectionManager.getRoomName() + "@" + ConnectionManager.getConnection().getServiceName() + "/" + nickname;
        Occupant occupant = GUIAccessManager.mf.getChatRoomManager().getMuc().getOccupant(xjid);
        String jid = occupant.getJid();
        int at = jid.indexOf("@");
        return jid.substring(0, at);
    }

    public static String generateRandomStr(int n) {
        char[] pw = new char[n];
        int c = 'A';
        int r1 = 0;
        for (int i = 0; i < n; i++) {
            r1 = (int) (Math.random() * 3);
            switch (r1) {
                case 0:
                    c = '0' + (int) (Math.random() * 10);
                    break;
                case 1:
                    c = 'a' + (int) (Math.random() * 26);
                    break;
                case 2:
                    c = 'A' + (int) (Math.random() * 26);
                    break;
            }
            pw[i] = (char) c;
        }
        return new String(pw);
    }

    public static String getJID(String nickname) {
        if (nickname.endsWith("(me)")) {
            int me = nickname.indexOf("(me)");
            nickname = nickname.substring(0, me).trim();

        }
        String str = ConnectionManager.getRoomName() + "@" + ConnectionManager.getConnection().getServiceName() + "/" + nickname;

        Occupant occupant = GUIAccessManager.mf.getChatRoomManager().getMuc().getOccupant(str);
        return occupant.getJid();
    }

    private static int generateRandomInteger(int aStart, int aEnd) {
        if (aStart > aEnd) {
            int temp = aStart;
            aStart = aEnd;
            aEnd = temp;

        }
        Random aRandom = new Random();
        //get the range, casting to long to avoid overflow problems
        long range = (long) aEnd - (long) aStart + 1;
        // compute a fraction of the range, 0 <= frac < range
        long fraction = (long) (range * aRandom.nextDouble());
        int randomNumber = (int) (fraction + aStart);
        return randomNumber;
    }

    private static boolean isMyRoom() {
        return ConnectionManager.getUsername().equals(getThisRoomOwner());
    }

    public static boolean isAdmin(String from) {
        return GUIAccessManager.mf.getUserListPanel().getParticipantListTable().isAdmin(from);
    }

    public static boolean isOwner(String from) {
        return GUIAccessManager.mf.getUserListPanel().getParticipantListTable().isOwner(from);
    }

    private static String getThisRoomOwner() {
        String owner = null;
        try {
            Collection<Affiliate> owners = GUIAccessManager.mf.getChatRoomManager().getMuc().getOwners();
            for (Affiliate affiliate : owners) {
                String jid = affiliate.getJid();
                int at = jid.indexOf("@");
                owner = jid.substring(0, at);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return owner;
    }

    public static String formatDate(Date date) {
        try {
            SimpleDateFormat formatter = new SimpleDateFormat("yyyy/MM/dd", new Locale("en_US"));

            return formatter.format(date);
        } catch (Exception e) {
            return date + "";
        }
    }
    // Copies all files under srcDir to dstDir.
    // If dstDir does not exist, it will be created.

    public static void copyDirectory(File srcDir, File dstDir) throws IOException {
        if (srcDir.isDirectory()) {
            if (!dstDir.exists()) {
                dstDir.mkdir();
            }

            String[] children = srcDir.list();
            for (int i = 0; i < children.length; i++) {
                copyDirectory(new File(srcDir, children[i]),
                        new File(dstDir, children[i]));
            }
        } else {
            // This method is implemented in e1071 Copying a File
            copy(srcDir, dstDir);
        }
    }
// Copies src file to dst file.
    // If the dst file does not exist, it is created

    public static void copy(File src, File dst) throws IOException {
        InputStream in = new FileInputStream(src);
        OutputStream out = new FileOutputStream(dst);

        // Transfer bytes from in to out
        byte[] buf = new byte[1024];
        int len;
        while ((len = in.read(buf)) > 0) {
            out.write(buf, 0, len);
        }
        in.close();
        out.close();
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
    // Deletes all files and subdirectories under dir.
    // Returns true if all deletions were successful.
    // If a deletion fails, the method stops attempting to delete and returns false.

    public static boolean deleteDir(File dir) {
        if (dir.isDirectory()) {
            String[] children = dir.list();
            for (int i = 0; i < children.length; i++) {
                boolean success = deleteDir(new File(dir, children[i]));
                if (!success) {
                    return false;
                }
            }
        }

        // The directory is now empty so delete it
        return dir.delete();
    }

    public static String[] getIllegalCharacters() {
        String[] illegalCharacters = {"!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "+", "|",
            "{", "}", ":", "\"", "\'", "/", "\\", "~", "`", "[", "]", "="};
        return illegalCharacters;
    }

    public static String[] getIllegalXmlCharacters() {
        String[] illegalCharacters = {"&"};
        return illegalCharacters;
    }

    public static boolean isInvalidRoomName(String roomName) {
        boolean invalid = false;
        String badChars[] = getIllegalCharacters();
        for (String badChar : badChars) {
            if (roomName.indexOf(badChar) > -1) {
                return true;
            }
        }
        return invalid;
    }

    public static String formatStr(String str, String separator) {
        String[] words = str.split(separator);
        String result = "";
        if (str.toLowerCase().startsWith("anonymous")) {
            result = "anonymous";
        }
        for (String word : words) {
            result += word + "_";
        }
        if (result.indexOf("_") > -1) {
            result = result.substring(0, result.lastIndexOf("_"));
        }
        /* String[] badChars = getIllegalCharacters();
        for (String badChar : badChars) {
        int indexOfBadChar=result.indexOf(badChar);
        if (indexOfBadChar > -1) {
        String leftString=result.substring(0,indexOfBadChar);
        String rightString=result.substring(indexOfBadChar+1);
        result=leftString+"_"+rightString;
        }
        }*/

        if (result.startsWith("_") && result.length() > 1) {
            result = result.substring(1);
        }
        return result;
    }

    public static String formatStrUsingIllegalChars(String str, String separator) {
        String[] words = str.split(separator);
        String result = "";
        if (str.toLowerCase().startsWith("anonymous")) {
            result = "anonymous";
        }
        for (String word : words) {
            result += word + "_";
        }
        if (result.indexOf("_") > -1) {
            result = result.substring(0, result.lastIndexOf("_"));
        }
        String[] badChars = getIllegalCharacters();
        for (String badChar : badChars) {
            int indexOfBadChar = result.indexOf(badChar);
            if (indexOfBadChar > -1) {
                String leftString = result.substring(0, indexOfBadChar);
                String rightString = result.substring(indexOfBadChar + 1);
                result = leftString + "_" + rightString;
            }
        }


        return result;
    }

    public static String removeIllegalXmlChars(String str) {

        String result = str;


        String[] badChars = getIllegalXmlCharacters();
        for (String badChar : badChars) {
            int indexOfBadChar = result.indexOf(badChar);
            if (indexOfBadChar > -1) {
                String leftString = result.substring(0, indexOfBadChar);
                String rightString = result.substring(indexOfBadChar + 1);
                result = leftString + "_" + rightString;
            }
        }


        return result;
    }

    public static boolean isInstructor() {
        return WebPresentManager.isPresenter || StandAloneManager.isAdmin;
    }

    public static Image toImage(BufferedImage bufferedImage) {
        return Toolkit.getDefaultToolkit().createImage(bufferedImage.getSource());
    }

    // This method returns true if the specified image has transparent pixels
    public static boolean hasAlpha(Image image) {
        // If buffered image, the color model is readily available
        if (image instanceof BufferedImage) {
            BufferedImage bimage = (BufferedImage) image;
            return bimage.getColorModel().hasAlpha();
        }

        // Use a pixel grabber to retrieve the image's color model;
        // grabbing a single pixel is usually sufficient
        PixelGrabber pg = new PixelGrabber(image, 0, 0, 1, 1, false);
        try {
            pg.grabPixels();
        } catch (InterruptedException e) {
        }

        // Get the image's color model
        ColorModel cm = pg.getColorModel();
        return cm.hasAlpha();
    }

    public static String getTagText(String xmlContent, String tag) {
        String content = null;
        int start = (xmlContent.indexOf("<" + tag + ">")) + (("<" + tag + ">").length());
        int end = xmlContent.indexOf("</" + tag + ">");

        if (start > -1 && end > -1) {
            content = xmlContent.substring(start, end);
        }

        return content;
    }

    public static String makeFirstLetterUpperCase(String str) {
        if (str.length() == 1) {
            return str.toUpperCase();
        }
        String firstLetter = str.substring(0, 1);
        String rest = str.substring(1);
        return firstLetter.toUpperCase() + rest;
    }
    // This method returns a buffered image with the contents of an image

    public static BufferedImage toBufferedImage(Image image) {
        if (image instanceof BufferedImage) {
            return (BufferedImage) image;
        }

        // This code ensures that all the pixels in the image are loaded
        image = new ImageIcon(image).getImage();

        // Determine if the image has transparent pixels; for this method's
        // implementation, see e661 Determining If an Image Has Transparent Pixels
        boolean hasAlpha = hasAlpha(image);

        // Create a buffered image with a format that's compatible with the screen
        BufferedImage bimage = null;
        GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
        try {
            // Determine the type of transparency of the new buffered image
            int transparency = Transparency.OPAQUE;
            if (hasAlpha) {
                transparency = Transparency.BITMASK;
            }

            // Create the buffered image
            GraphicsDevice gs = ge.getDefaultScreenDevice();
            GraphicsConfiguration gc = gs.getDefaultConfiguration();
            bimage = gc.createCompatibleImage(
                    image.getWidth(null), image.getHeight(null), transparency);
        } catch (HeadlessException e) {
            e.printStackTrace();
            // The system does not have a screen
        }

        if (bimage == null) {
            // Create a buffered image using the default color model
            int type = BufferedImage.TYPE_INT_RGB;
            if (hasAlpha) {
                type = BufferedImage.TYPE_INT_ARGB;
            }
            bimage = new BufferedImage(image.getWidth(null), image.getHeight(null), type);
        }

        // Copy image to buffered image
        Graphics g = bimage.createGraphics();

        // Paint the image onto the buffered image
        g.drawImage(image, 0, 0, null);
        g.dispose();

        return bimage;
    }

    static public String getContents(File aFile) {
        StringBuilder contents = new StringBuilder();
        try {
            BufferedReader input = new BufferedReader(new FileReader(aFile));
            try {
                String line = null;
                while ((line = input.readLine()) != null) {
                    contents.append(line);
                    //contents.append(System.getProperty("line.separator"));
                }
            } finally {
                input.close();
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }
        return contents.toString();
    }

    public static void showMessage(String msg) {
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setShowInfoMessage(true);
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setInfoMessage(msg);
    }

    public static void showStatusWindow(final String msg) {
        Thread t = new Thread() {

            public void run() {
                intro.setSize(300, 100);
                info.setText(msg);
                intro.getContentPane().setLayout(new BorderLayout());
                intro.getContentPane().add(info, BorderLayout.CENTER);
                intro.setVisible(true);
                intro.setLocationRelativeTo(null);
            }
        };
        t.start();
    }

    public static ImageIcon getAvator(VCard vcard) {
        try {
            byte[] avatarBytes = vcard.getAvatar();
            if (avatarBytes != null) {
                ImageIcon icon = new ImageIcon(avatarBytes);
                return new ImageIcon(GeneralUtil.getScaledImage(icon.getImage(), 48, 48));

            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static String getTmpDir() {
        return tmpDir;
    }

    public static void init() {
        File tmp = new File(tmpDir);
        if (!tmp.exists()) {
            tmp.mkdirs();
        }
        if (!new File(fileName).exists()) {
            new File(new File(fileName).getParent()).mkdirs();
            try {
                BufferedWriter out = new BufferedWriter(new FileWriter(fileName));
                out.write("username=admin\n");
                out.write("server=localhost\n");
                out.write("port=5222\n");
                out.write("rooms=default\n");
                out.write("audio.video.url=localhost\n");
                out.write("connection.type=" + Constants.Proxy.NO_PROXY + "\n");
                out.write("proxy.username=\n");
                out.write("proxy.password=\n");
                out.write("proxy.require.auth=false\n");
                out.write("debug.enabled=false\n");
                out.write("browser.proxy.required=false\n");
                out.close();
            } catch (IOException e) {
                e.printStackTrace();

            }
        }
    }

    public static Image getScaledImage(Image srcImg, int w, int h) {
        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();

        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public static BufferedImage getScaledBufferedImage(Image srcImg, int w, int h) {
        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();

        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public static String removeExt(String str) {
        if (str == null) {
            return null;
        }
        int i = str.lastIndexOf(".");
        if (i > -1) {
            return str.substring(0, i);
        }
        return str;
    }

    public static String getExt(String str) {
        if (str == null) {
            return null;
        }
        int i = str.lastIndexOf(".");
        if (i > -1) {
            return str.substring(i);
        }
        return str;
    }

    static {

        loadProperties();
    }

    public static void saveProperty(String prop, String value) {
        try {
            FileOutputStream out = new FileOutputStream(fileName);
            props.put(prop, value);
            props.store(out,
                    "---DO NOT EDIT THIS FILE IT IS SYSTEM GENERATED---");
            out.close();
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public static String getProperty(String prop) {
        return props.getProperty(prop);
    }

    public static void loadProperties() {
        try {

            // create and load default properties
            Properties defaultProps = new Properties();
            FileInputStream in = new FileInputStream(fileName);
            defaultProps.load(in);
            in.close();

            // create program properties with default
            props = new Properties(defaultProps);

            // now load properties from last invocation
            in = new FileInputStream(fileName);
            props.load(in);
            in.close();

        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public static void setStatusMessage(String txt) {
        info.setHorizontalTextPosition(SwingConstants.CENTER);
        info.setText(txt);
    }

    public static String imageToBase64(BufferedImage img) {
        try {
            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            ImageIO.write(img, "jpg", baos);
            byte[] dataToEncode = baos.toByteArray();
            return Base64.encodeBytes(dataToEncode);
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
    }

    public static Object convertStringToObject(String str) {
        try {
            ByteArrayInputStream bais = new ByteArrayInputStream(Base64.decode(str));
            ObjectInputStream ois = new ObjectInputStream(bais);
            Object obj = ois.readObject();
            return obj;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static String convertObjectToString(Object obj) {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        try {
            ObjectOutputStream xoos = new ObjectOutputStream(baos);
            xoos.writeObject(obj);
            byte[] bytes = baos.toByteArray();
            return Base64.encodeBytes(bytes);
        } catch (IOException ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static void writeTextFile(String fileName, String txt, boolean append) {
        try {
            BufferedWriter out = new BufferedWriter(new FileWriter(fileName));
            out.write(txt);
            out.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static byte[] readFile(String filePath) {
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
                    System.out.println("error reading the file");
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

    public static void writeBinaryFile(String filename, byte[] byteArray) {
        try {
            FileChannel fc =
                    new FileOutputStream(filename).getChannel();
            fc.write(ByteBuffer.wrap(byteArray));
            fc.close();

        } catch (Exception ex) {
            ex.printStackTrace();
            //delete if errors
            new File(filename).delete();
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

    public static void debug(Class cl, Exception ex) {
        System.out.println(cl.getClass() + ": " + ex.getMessage() + " at line " + getLineNumber());
    }

    public static int getLineNumber() {
        return Thread.currentThread().getStackTrace()[2].getLineNumber();
    }

    public static String readTextFile(String file) {
        StringBuilder builder = new StringBuilder();

        try {
            BufferedReader in = new BufferedReader(new FileReader(file));
            String line;
            while ((line = in.readLine()) != null) {
                builder.append(line + "\n");
            }

        } catch (Exception ex) {
            System.out.println(ex.getMessage());
        }
        return builder.toString();
        /*
         * outputStream = new BufferedWriter(new
         * FileWriter("characteroutput.txt"));
         */
    }
}
