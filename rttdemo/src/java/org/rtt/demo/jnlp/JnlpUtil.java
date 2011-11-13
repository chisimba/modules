/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
package org.rtt.demo.jnlp;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.io.Writer;
import java.util.ArrayList;
import javax.servlet.ServletContext;
import org.rtt.demo.domain.Property;
import org.rtt.demo.service.UserService;
import org.rtt.demo.util.GeneralUtil;

/**
 *
 * @author davidwaf
 */
public class JnlpUtil {

    public static String generateJnlp(
            ServletContext servletContext,
            String codebase,
            String title,
            String vendor,
            String description,
            String homePageRef,
            String iconPath,
            String jnlpPath,
            String rawbasecode,
            ArrayList<Property> properties,
            UserService userService,
            JnlpService jnlpService,
            String paramsBaseJnlp) {


        String content =
                "<jnlp spec=\"1.0+\" codebase=\"" + codebase + "\">\n"
                + "    <information>\n"
                + "        <title>" + title + "</title>\n"
                + "        <vendor>" + vendor + "</vendor>\n"
                + "        <description>" + description + "</description>\n"
                + "        <homepage href=\"" + homePageRef + "\"/>\n"
                + "        <description kind=\"short\">rtt</description>\n"
                + "        <icon href=\"" + iconPath + "\"/>\n"
                + "        <icon kind=\"splash\" href=\"" + iconPath + "\"/>\n"
                + "        <offline-allowed/>\n"
                + "    </information>\n";


        content += "<resources os=\"Windows\" arch=\"x86\">\n"
                + "     <jar href=\"swt-win.jar\" />\n"
                + "</resources>\n"
                + " <resources os=\"Linux\">\n"
                + "   <jar href=\"swt-linux.jar\" />\n"
                + "  </resources>\n"
                + " <resources os=\"Mac OS X\">\n"
                + "   <j2se version=\"1.6*\" java-vm-args=\"-XstartOnFirstThread\"/>\n"
                + "   <jar href=\"swt-mac.jar\"/>\n"
                + " </resources>";

        content += "<resources>\n";
        String clientJars[] = new File(jnlpPath).list();
        
        //if(clientJars != null)
        for (String jar : clientJars) {
            if (jar.endsWith(".jar") && !jar.startsWith("swt-")) {
                content += "<jar href=\"" + jar + "\" />\n";
            }
        }

        String userId = GeneralUtil.generateRandomStr(32);
        String password = GeneralUtil.generateRandomStr(32);
        userService.addRestDemoUser(userId, password);
        content += "</resources>\n"
                + "   <application-desc    main-class=\"org.avoir.rtt.core.Main\">\n"
                + "    <argument>" + userId + "</argument>\n"
                + "    <argument>" + password + "</argument>\n"
                + "    <argument>" + rawbasecode + "</argument>\n"
                + "    <argument>" + paramsBaseJnlp + "</argument>\n"
                + "    </application-desc>\n"
                + "    <security>\n"
                + "        <all-permissions/>\n"
                + "    </security>\n"
                + "</jnlp>\n";
        for (Property property : properties) {
           
            jnlpService.saveJnlpParam(userId, property.getPropkey(), property.getPropvalue());
        }
        return content;
    }

    public static void writeTextFile(String fileName, String txt) {
        Writer out = null;
        File f = new File(fileName);

        try {
            if (!f.exists()) {
                f.createNewFile();
            }
            out = new OutputStreamWriter(new FileOutputStream(f));
            out.write(txt);
        } catch (IOException e) {

            /// JOptionPane.showMessageDialog(null, getStackTrace(e));
            e.printStackTrace();
        } finally {
            if (out != null) {
                try {
                    out.close();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
            }

        }
    }

    /**
     * returns string representation of the stacktrace
     * @param aThrowable
     * @return
     */
    public static String getStackTrace(Throwable aThrowable) {
        final Writer result = new StringWriter();
        final PrintWriter printWriter = new PrintWriter(result);
        aThrowable.printStackTrace(printWriter);
        return result.toString();
    }
}