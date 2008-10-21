/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher;

import java.io.*;
import java.util.zip.*;

class UnZip {

    public static void main(String args[]) {
        try {
            InputStream in =
                    new BufferedInputStream(new FileInputStream(args[0]));
            ZipInputStream zin = new ZipInputStream(in);
            ZipEntry e;

            while ((e = zin.getNextEntry()) != null) {
                if (args.length > 1) {
                    if (e.getName().equals(args[1])) {
                        unzip(zin, args[1]);
                        break;
                    }
                }
                unzip(zin, e.getName());
            }
            zin.close();
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public static void unzip(ZipInputStream zin, String s) {
        try {
            System.out.println("unzipping " + s);

            FileOutputStream out = new FileOutputStream(Constants.getRealtimeHome() +"/" + s);
            byte[] b = new byte[512];
            int len = 0;
            while ((len = zin.read(b)) != -1) {
                out.write(b, 0, len);
            }
            out.close();
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }
}