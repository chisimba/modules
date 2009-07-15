/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.plugins.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.avoir.realtime.plugins.Constants;
import org.jivesoftware.util.JiveGlobals;

/**
 *
 * @author developer
 */
public class AvoirRealtimeServlet extends HttpServlet {

    private String userName = "";
    private String password = "";
    private String base = "";
    private String dn = "uid=" + userName + "," + base;
    private String ldapURL = "";
    private String siteUrl = "";
    private String sitePath = "/var/www";
    private String ec2VolId = "";
    private String ec2AmiId = "";
    private String ec2AccessId = "";
    private String ec2SecreteId = "";
    private String proxyRequired = "false";
    private String proxyHost = "";
    private String proxyPort = "";
    private String proxyUsername = "";
    private String proxyPassword = "";
    private String proxyDomain = "";

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code> methods.
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = response.getWriter();

        try {
            String action = request.getParameter("action");

            if (action != null) {
                String pre = "avoirrealtime.";
                if ("update".equals(action)) {
                    userName = request.getParameter("ldapusername");
                    password = request.getParameter("ldappassword");
                    base = request.getParameter("base");
                    ldapURL = request.getParameter("ldapurl");
                    dn = request.getParameter("dn");
                    siteUrl = request.getParameter("siteurl");
                    sitePath = request.getParameter("sitepath");

                    ec2VolId = request.getParameter("ec2volid");
                    ec2AmiId = request.getParameter("ec2amiid");
                    ec2AccessId = request.getParameter("ec2accessid");
                    ec2SecreteId = request.getParameter("ec2secreteid");

                    proxyRequired = request.getParameter("proxyrequired");
                    proxyHost = request.getParameter("proxyhost");
                    proxyPort = request.getParameter("proxyport");
                    proxyUsername = request.getParameter("proxyusername");
                    proxyPassword = request.getParameter("proxypassword");
                    proxyDomain = request.getParameter("proxydomain");

                    JiveGlobals.setProperty(Constants.LDAP_USERNAME, userName);
                    JiveGlobals.setProperty(Constants.LDAP_PASSWORD, password);
                    JiveGlobals.setProperty(Constants.LDAP_BASE, base);
                    JiveGlobals.setProperty(Constants.LDAP_URL, ldapURL);
                    JiveGlobals.setProperty(Constants.LDAP_DN, dn);
                    JiveGlobals.setProperty(Constants.SITE_URL, siteUrl);
                    JiveGlobals.setProperty(Constants.SITE_PATH, sitePath);

                    JiveGlobals.setProperty(Constants.EC2_VOL_ID, ec2VolId);
                    JiveGlobals.setProperty(Constants.EC2_AMI_ID, ec2AmiId);
                    JiveGlobals.setProperty(Constants.EC2_ACCESS_ID, ec2AccessId);
                    JiveGlobals.setProperty(Constants.EC2_SECRETE_ID, ec2SecreteId);

                    JiveGlobals.setProperty(Constants.PROXY_REQUIRED, proxyRequired);
                    JiveGlobals.setProperty(Constants.PROXY_HOST, proxyHost);
                    JiveGlobals.setProperty(Constants.PROXY_PORT, proxyPort);
                    JiveGlobals.setProperty(Constants.PROXY_USERNAME, proxyUsername);
                    JiveGlobals.setProperty(Constants.PROXY_PASSWORD, proxyPassword);
                    JiveGlobals.setProperty(Constants.PROXY_DOMAIN, proxyDomain);
                }
            }
            displayPage(out);
        } finally {
            out.close();
        }
    }

    private void displayPage(PrintWriter out) {
        userName = JiveGlobals.getProperty(Constants.LDAP_USERNAME, "");
        password = JiveGlobals.getProperty(Constants.LDAP_PASSWORD, "");
        base = JiveGlobals.getProperty(Constants.LDAP_BASE, "");
        ldapURL = JiveGlobals.getProperty(Constants.LDAP_URL, "");
        dn = JiveGlobals.getProperty(Constants.LDAP_DN, "");
        siteUrl = JiveGlobals.getProperty(Constants.SITE_URL, "");
        sitePath = JiveGlobals.getProperty(Constants.SITE_PATH, "");

        ec2VolId = JiveGlobals.getProperty(Constants.EC2_VOL_ID, "");
        ec2AmiId = JiveGlobals.getProperty(Constants.EC2_AMI_ID, "");
        ec2AccessId = JiveGlobals.getProperty(Constants.EC2_ACCESS_ID, "");
        ec2SecreteId = JiveGlobals.getProperty(Constants.EC2_SECRETE_ID, "");

        proxyRequired = JiveGlobals.getProperty(Constants.PROXY_REQUIRED, "false");
        proxyHost = JiveGlobals.getProperty(Constants.PROXY_HOST, "");
        proxyPort = JiveGlobals.getProperty(Constants.PROXY_PORT, "");
        proxyUsername = JiveGlobals.getProperty(Constants.PROXY_USERNAME, "");
        proxyPassword = JiveGlobals.getProperty(Constants.PROXY_PASSWORD, "");
        proxyDomain = JiveGlobals.getProperty(Constants.PROXY_DOMAIN, "");
        try {
            out.println("<html>");
            out.println("    <head>");
            out.println("        <title>AvoirRealtime Properties</title>");
            out.println("        <meta name=\"pageID\" content=\"avoirrealtime-props-edit-form\"/>");
            out.println("        <link rel=\"stylesheet\" type=\"text/css\" href=\"/style/global.css\"/>");
            out.println("    </head>");
            out.println("    <body>");
            out.println("");
            out.println("Use the form below to edit AvoirRealtime Properties.<br>");
            out.println("</p>");
            out.println("<form action=\"conf\" method=\"get\">");
            out.println("<input type='hidden' name='action' value='update'>");
            out.println("");
            out.println("<div class=\"jive-contentBoxHeader\">LDAP Parameters</div>");
            out.println("<div class=\"jive-contentBox\">");
            out.println("	 <table>");
            out.println("	 	<tr><td>Admin DN</td><td><input type='text' name='ldapusername' value='" + userName + "'></td>");
            out.println("	 		<td>Username to use for connecting to LDAP Server. This must be admin user.</td></tr>");
            out.println("	 	<tr><td>Admin Password</td><td><input type='password' name='ldappassword' value='" + password + "'></td>");
            out.println("	 		<td>The admin password.</td></tr>");
            out.println("	 	<tr><td>Base DN</td><td><input type='text' name='base' value='" + base + "'></td>");
            out.println("	 		<td>Base DN where to search from</td></tr>");
            out.println("	 	<tr><td>LDAP URL</td><td><input type='text' name='ldapurl' value='" + ldapURL + "'></td>");
            out.println("	 		<td>The server dns/IP. Must start with ldpa://.</td></tr>");
            out.println("	 </table>");
            out.println("");
            out.println("</div>");
            out.println("<div class=\"jive-contentBoxHeader\">Short URL Properties</div>");
            out.println("<div class=\"jive-contentBox\">");
            out.println("	 <table>");
            out.println("	 	<tr><td>Site URL</td><td><input type='text' name='siteurl' value='" + siteUrl + "'></td>");
            out.println("	 		<td>Base site from which short urls will be generated.</td></tr>");
            out.println("	 	<tr><td>Site Path</td><td><input type='text' name='sitepath' value='" + sitePath + "'></td>");
            out.println("	 		<td>The site path to the above site url e.g. /var/www/avoirrealtime/.</td></tr>");
            out.println("	 </table>");
            out.println("");
            out.println("</div>");

            out.println("<div class=\"jive-contentBoxHeader\">Amazon EC2 Properties</div>");
            out.println("<div class=\"jive-contentBox\">");
            out.println("<table>");

            out.println("<tr><td>Volume Id</td><td><input type='text' name='ec2volid' value='" + ec2AccessId + "'></td>");
            out.println("<td>The id of EBS to attach to an instance.</td></tr>");

            out.println("<tr><td>Image Id</td><td><input type='text' name='ec2amiid' value='" + ec2AmiId + "'></td>");
            out.println("<td>The id of the image to launch instance from.</td></tr>");

            out.println("<tr><td>Amazon Access ID</td><td><input type='text' name='ec2accessid' value='" + ec2AccessId + "'></td>");
            out.println("<td>The access id to the amazon account.</td></tr>");

            out.println("<tr><td>Secrete Key</td><td><input type='password' name='ec2secreteid' value='" + ec2SecreteId + "'></td>");
            out.println("<td>The screte id.</td></tr>");

            out.println("<tr><td>Http Proxy Required</td><td><input type='checkbox' name='proxyrequired' value='" + proxyRequired + "'></td>");
            out.println("<td>Is the proxy required.</td></tr>");

            out.println("<tr><td>Http Proxy Host</td><td><input type='text' name='proxyhost' value='" + proxyHost + "'></td>");
            out.println("<td>Proxy host. DO NOT start with http://.</td></tr>");

            out.println("<tr><td>Http Proxy Port</td><td><input type='text' name='proxyport' value='" + proxyPort + "'></td>");
            out.println("<td>Proxy port.</td></tr>");

            out.println("<tr><td>Http Proxy Username</td><td><input type='text' name='proxyusername' value='" + proxyUsername + "'></td>");
            out.println("<td>Proxy username.</td></tr>");

            out.println("<tr><td>Http Proxy Password</td><td><input type='password' name='proxypassword' value='" + proxyPassword + "'></td>");
            out.println("<td>Proxy password.</td></tr>");

            out.println("<tr><td>Http Proxy Domain</td><td><input type='text' name='proxydomain' value='" + proxyDomain + "'></td>");
            out.println("<td>Proxy Domain for current user.</td></tr>");
            out.println("</table>");
            out.println("");
            out.println("</div>");

            out.println("&nbsp;<p/>&nbsp;<p/><input type=\"submit\" value=\"Save Properties\">");
            out.println("</form>");
            out.println("");
            out.println("</body>");
            out.println("</html>");

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /** 
     * Handles the HTTP <code>GET</code> method.
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /** 
     * Handles the HTTP <code>POST</code> method.
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /** 
     * Returns a short description of the servlet.
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>
}
