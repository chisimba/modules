/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.plugins.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.net.InetAddress;
import java.net.UnknownHostException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.jivesoftware.admin.AuthCheckFilter;

/**
 *
 * @author david
 */
public class ClientWebstartServlet extends HttpServlet {

    @Override
    public void init(ServletConfig servletConfig) throws ServletException {
        super.init(servletConfig);
        // Exclude this servlet from requering the user to login
        AuthCheckFilter.addExclude("avoirrealtime/chisimba");
    }

    private String getIp() {
        try {
            InetAddress inetAddress = InetAddress.getLocalHost();
            return inetAddress.getHostAddress();
        } catch (UnknownHostException e) {
        }
        return "localhost";
    }

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        PrintWriter out = response.getWriter();
        String username = request.getParameter("username");
        response.setContentType("application/x-java-jnlp-file");
        response.setHeader("Content-Disposition", "attachment; filename=" + username + ".jnlp");

        try {
            String port = request.getParameter("port");
            String host = request.getParameter("host");
            String roomName = request.getParameter("roomname");
            String audioVideoUrl = request.getParameter("audiovideourl");
            String slidesDir = request.getParameter("slidesdir");
            String isPresenter = request.getParameter("ispresenter");
            String presentationId = request.getParameter("presentationid");
            String presentationName = request.getParameter("presentationname");
            String names = request.getParameter("names");
            String email = request.getParameter("email");
            String inviteUrl = request.getParameter("inviteurl");
            String useEc2 = request.getParameter("useec2");
            String joinMeetingId = request.getParameter("joinid");
            String codebase = request.getParameter("codebase");
            String skinClass = request.getParameter("skinclass");
            String skinJar = request.getParameter("skinjar");
            String mainClass = request.getParameter("mainclass");
            if (mainClass == null) {
                mainClass = "org.avoir.realtime.gui.main.Main";
            }
            out.println("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
            out.println("<jnlp spec=\"1.0+\" codebase=\"" + codebase + "\">");
            out.println("<information>");
            out.println("<title>Realtime Communication Tools</title>");
            out.println("<vendor>Realtime</vendor>");
            out.println("<description>Realtime Classroom</description>");
            out.println("<homepage href=\"http://avoir.uwc.ac.za\"/>");
            out.println("<description kind=\"short\">Realtime Virtual Classroom</description>");
            out.println("<icon href=\"" + codebase + "/images/logo.png\"/>");
            out.println("<icon kind=\"splash\" href=\"" + codebase + "/images/splash_realtime.png\"/>");
            out.println("<offline-allowed/>");
            out.println("</information>");
            out.println("<resources>");
            out.println("<j2se version=\"1.5+\" />");
            out.println("<jar href=\"realtime-xmpp.jar\" />");
            out.println("<jar href=\"commons-collections-3.1-rt.jar\" />");
            out.println("<jar href=\"jna-3.0.7.jar\" />");
            out.println("<jar href=\"quartz-all-1.6.0.jar\" />");
            out.println("<jar href=\"commons-logging-api-rt.jar\" />");
            out.println("<jar href=\"jta-rt.jar \" />");
            out.println("<jar href=\"DJNativeSwing.jar  \" />");
            out.println("<jar href=\"kunstsoff-rt.jar \" />");
            out.println("<jar href=\"smack.jar\" />");
            out.println("<jar href=\"DJNativeSwing-SWT.jar \" />");
            out.println("<jar href=\"smackx.jar\" />");
            out.println("<jar href=\"systray4j.jar\" />");

            if (skinClass != null && skinJar != null) {
                String[] jarFiles = skinJar.split(",");
                for (String jar : jarFiles) {
                    out.println("<jar href=\"" + jar + "\" />");
                }
            }
            out.println("</resources>");
            out.println("<resources os=\"Windows\" arch=\"x86\">");
            out.println("<jar href=\"swt32-win-x86.jar\" />");
            out.println("<jar href=\"win-libsystray4j.jar\" />");
            out.println("</resources>");
            out.println("<resources os=\"Linux\">");
            out.println("<jar href=\"swt-linux-x32.jar\" />");
            out.println("<jar href=\"linux-libsystray4j.jar\" />");
            out.println("</resources>");
            out.println("<resources os=\"Mac OS X\">");
            out.println("<j2se version=\"1.5*\" java-vm-args=\"-XstartOnFirstThread\"/>");
            out.println("<jar href=\"swt-osx.jar\"/>");
            out.println("</resources>");
            out.println("<application-desc main-class=\"" + mainClass + "\">");
            out.println("<argument>" + host + "</argument>");//0
            out.println("<argument>" + port + "</argument>");//1
            out.println("<argument>" + audioVideoUrl + "</argument>");//2
            out.println("<argument>" + roomName + "</argument>");//3
            out.println("<argument>" + username + "</argument>");//4
            out.println("<argument>" + slidesDir + "</argument>");//5
            out.println("<argument>" + isPresenter + "</argument>");//6
            out.println("<argument>" + presentationId + "</argument>");//7
            out.println("<argument>" + presentationName + "</argument>");//8
            out.println("<argument>" + names + "</argument>");//9
            out.println("<argument>" + email + "</argument>");//10
            out.println("<argument>" + inviteUrl + "</argument>");//11
            out.println("<argument>undefined</argument>");//12
            out.println("<argument>" + useEc2 + "</argument>");//13
            out.println("<argument>" + joinMeetingId + "</argument>");//14
            out.println("<argument>" + skinClass + "</argument>");//15
            out.println("</application-desc>");
            out.println("<security>");
            out.println("<all-permissions/>");
            out.println("</security>");
            out.println("</jnlp>");


        } catch (Exception ex) {
            ex.printStackTrace();
        } finally {
            out.close();
        }
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doGet(request, response);
    }

    @Override
    public void destroy() {
        super.destroy();
        // Release the excluded URL
        AuthCheckFilter.removeExclude("avoirrealtime/chisimba");
    }
}
