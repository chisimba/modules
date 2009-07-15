/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.plugins;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpMethod;
import org.apache.commons.httpclient.NameValuePair;
import org.apache.commons.httpclient.methods.GetMethod;

import org.dom4j.DocumentHelper;
import org.dom4j.QName;
import org.jivesoftware.util.Base64;
import org.jivesoftware.util.EmailService;

import org.jivesoftware.util.JiveGlobals;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xmpp.packet.IQ;

/**
 *
 * @author developer
 */
public class EmailSender {

    AvoirRealtimePlugin pl;

    public EmailSender(AvoirRealtimePlugin pl) {
        this.pl = pl;
    }

    private String createShortUrl(String url, String roomName) {
        String siteUrl = JiveGlobals.getProperty(Constants.SITE_URL, "");
        int length = 6;
        String id = generateRandomStr(length);
        boolean uniqueId = pl.getRoomResourceManager().saveShortUrl(id, roomName, url);
        int count = 0;
        while (!uniqueId) {
            id = generateRandomStr(length);
            uniqueId = pl.getRoomResourceManager().saveShortUrl(id, roomName, url);
            count++;
            if (count > 1000000) {
                break;
            }
        }
        return uniqueId ? siteUrl + "/" + id : null;
    }

    private boolean writeTextFile(String fileName, String txt) {
        try {
            BufferedWriter out = new BufferedWriter(new FileWriter(fileName));
            out.write(txt);
            out.close();
            return true;
        } catch (IOException e) {
            e.printStackTrace();
        }
        return false;
    }

    private boolean writeItDown(File f, String longUrl) {
        String content = "<?php\n";
        content += "header( 'Location: " + longUrl + "');\n";
        content += "?>\n";
        return writeTextFile(f.getAbsolutePath() + "/index.php", content);
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

    public static String getTinyUrl(String fullUrl) {
        try {
            HttpClient httpclient = new HttpClient();

            // Prepare a request object
            HttpMethod method = new GetMethod("http://tinyurl.com/api-create.php");
            method.setQueryString(new NameValuePair[]{new NameValuePair("url", fullUrl)});
            httpclient.executeMethod(method);
            String tinyUrl = method.getResponseBodyAsString();
            method.releaseConnection();
            return tinyUrl;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return fullUrl;
    }

    public IQ sendEmails(final IQ packet, final Document doc) {
        boolean succeeded = true;
        IQ replyPacket = IQ.createResultIQ(packet);
        try {
            NodeList usersNodeList = doc.getElementsByTagName("user");
            String message = XmlUtils.readString(doc, "message");

            String xurl = XmlUtils.readString(doc, "url");
            String url = new String(Base64.decode(xurl));
            String roomName = XmlUtils.readString(doc, "room-name");
            String shortUrl1 = createShortUrl(url, roomName);

            if (shortUrl1 == null) {
                succeeded = false;
                shortUrl1 = url;
            }
            String xpresenterUrl = XmlUtils.readString(doc, "presenter-url");
            String presenterName = XmlUtils.readString(doc, "presenter-name");
            String presenterUrl = new String(Base64.decode(xpresenterUrl));


            String shortUrl2 = createShortUrl(presenterUrl, roomName);

            if (shortUrl2 == null) {
                succeeded = false;
                shortUrl2 = presenterUrl;
            }


            String startDate = XmlUtils.readString(doc, "start-date");
            String endDate = XmlUtils.readString(doc, "end-date");
            String xroomUrl = XmlUtils.readString(doc, "room-url");
            //String roomUrl = new String(Base64.decode(xroomUrl));
            String jid = packet.getFrom().toBareJID();
            int index = jid.indexOf("@");
            String owner = jid.substring(0, index);
            String meetingType = XmlUtils.readString(doc, "meeting-type");
            if (meetingType.trim().equals("now")) {
                pl.getRoomResourceManager().addUserAsOnline(owner, roomName.toLowerCase());
            }
            if (meetingType.trim().equals("scheduled")) {
              //  pl.getRoomResourceManager().addSchedule(owner, roomUrl, startDate, endDate, roomUrl);
            }
            String ext = ". If you cannnot click on the link, copy and paste it in your browser addressbar.";
            message = message + ". Meeting link is " + shortUrl1 + " starting at  " + startDate + " to " + endDate + ext;
            String presenterMessage = "You have scheduled to start a meeting  at " + shortUrl2 + " from " + startDate + " to " + endDate + ext;
            String subject = XmlUtils.readString(doc, "subject");
            String from = XmlUtils.readString(doc, "email-from");
     

            EmailService service = EmailService.getInstance();
            for (int i = 0; i < usersNodeList.getLength(); i++) {
                try {
                    Node node = usersNodeList.item(i);
                    if (node.getNodeType() == Node.ELEMENT_NODE) {
                        org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                        String email = XmlUtils.readString(element, "email");
                        boolean isPresenter = new Boolean(XmlUtils.readString(element, "is-presenter"));
                        String messageBody = isPresenter ? presenterMessage : message;
                        String messageTitle = isPresenter ? "Chisimba Realtime Tools: You have scheduled a meeting" : subject;
                        service.sendMessage(email, email, presenterName, from, messageTitle, null, messageBody);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }

            }
        } catch (Exception ex) {
            succeeded = false;
            ex.printStackTrace();
        }

        org.dom4j.Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.INVITE_RESULT);
        StringBuilder sb = new StringBuilder();
        sb.append("<success>").append(succeeded + "").append("</success>");
        queryResult.addElement("content").addText(sb.toString());
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }
}