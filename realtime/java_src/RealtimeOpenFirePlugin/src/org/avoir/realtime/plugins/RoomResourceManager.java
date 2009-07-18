/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

 */
package org.avoir.realtime.plugins;

import java.io.File;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Date;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.database.DbConnectionManager;
import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.openfire.XMPPServer;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class RoomResourceManager {

    private Connection con = null;
    private PreparedStatement ps = null;
    private AvoirRealtimePlugin pl;
    private PacketRouter packetRouter;
    private XMPPServer server = XMPPServer.getInstance();

    public RoomResourceManager(AvoirRealtimePlugin pl) {
        this.pl = pl;
        packetRouter = pl.getPacketRouter();
    }

    public ArrayList<JID> getOnlineUsers(String room) {
        ArrayList<JID> onlineUsers = new ArrayList<JID>();

        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "select * from  ofAvoirRealtime_OnlineUsers where " +
                    " room ='" + room + "'";
            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                String jidStr = rs.getString("jid");
                //jidStr=jidStr.substring(0,jidStr.indexOf("@"));
                JID jid = server.createJID(jidStr, pl.getResoureName());
                onlineUsers.add(jid);
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return onlineUsers;
    }

    public String[] getEC2Property(String propKey, String propDomain) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "select roomname,property_value from ofAvoirRealtime_EC2Instances where property_key" +
                    " = '" + propKey + "' and property_domain = '" + propDomain + "'";
            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {

                String val1 = rs.getString("property_value");
                String val2 = rs.getString("roomname");
                String[] vals = new String[2];
                vals[0] = val1;
                vals[1] = val2;
                return vals;
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        return null;
    }

    public String[] getLongUrl(String id) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "select * from ofAvoirRealtime_ShortUrls where id " +
                    " = '" + id + "'";
            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {

                String val1 = rs.getString("longurl");
                String val2 = rs.getString("roomname");
                String[] vals = new String[2];
                vals[0] = val1;
                vals[1] = val2;
                return vals;
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        return null;
    }

    public boolean terminateInstance(String propDomain) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "select username from ofAvoirRealtime_EC2Instances where  property_domain = '" + propDomain + "'";
            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            int count = 0;
            while (rs.next()) {
                count++;
            }
            return !(count > 0);
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        return true;
    }

    public void addEC2Url(String username, String roomName, String propKey, String propVal, String domain) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "insert into  ofAvoirRealtime_EC2Instances values " +
                    "('" + username + "','" + roomName + "','" + propKey + "','" + propVal + "','" + domain + "')";
            Statement st = con.createStatement();
            st.addBatch(sql);

            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public boolean saveShortUrl(String id, String roomname, String longUrl) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "insert into  ofAvoirRealtime_ShortUrls values " +
                    "('" + id + "','" + roomname + "','" + longUrl + "')";
            Statement st = con.createStatement();
            st.addBatch(sql);

            st.executeBatch();
            return true;
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return false;
    }

    public void deleteEC2Url(String username) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "delete from  ofAvoirRealtime_EC2Instances where " +
                    " username = '" + username + "'";
            Statement st = con.createStatement();
            st.addBatch(sql);

            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void addRoom(String roomName, String roomDesc, String roomOwner, String roomType) {

        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "insert into  ofAvoirRealtime_Rooms(room_name," +
                    "room_owner,room_desc,room_type) values " +
                    "('" + roomName + "','" + roomOwner + "','" + roomDesc + "','" + roomType + "')";
            Statement st = con.createStatement();
            st.addBatch(sql);

            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void addRoomMember(String roomName, String username, String names, String owner) {
        String[] desc = getRoomDetails(roomName);
        String roomDesc = "Unknown";
        String roomType = "Unknown";
        if (desc != null) {
            roomDesc = desc[0];
            roomType = desc[1];
        }
        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "delete from ofAvoirRealtime_Rooms where room_member_username = '" + username.trim() + "'";
            String sql2 =
                    "insert into  ofAvoirRealtime_Rooms(room_name," +
                    "room_member_username,room_member_name,room_desc,room_type,room_owner) values " +
                    "('" + roomName + "','" + username + "','" + names + "','" +
                    roomDesc + "','" + roomType + "','" + owner + "')";
            Statement st = con.createStatement();
            st.addBatch(sql);
            st.addBatch(sql2);

            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }
//return roomResourceManager.deleteRoomMember(packet, roomName, userName, roomOwner);

    public IQ deleteRoomMember(IQ packet, String roomName, String username, String roomOwner) {
        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "delete from ofAvoirRealtime_Rooms where " +
                    " room_member_username = '" + username.trim() + "' and" +
                    " room_owner = '" + roomOwner.trim() + "' and " +
                    " room_name = '" + roomName.trim() + "'";

            Statement st = con.createStatement();
            st.addBatch(sql);

            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return getRoomMemberList(packet, roomName);
    }

    public void addUserAsOnline(String jid, String room) {

        try {
            con = DbConnectionManager.getConnection();
            String sql1 =
                    "delete from  ofAvoirRealtime_OnlineUsers where " +
                    "jid = '" + jid + "'";

            String sql2 =
                    "insert into  ofAvoirRealtime_OnlineUsers values " +
                    "('" + jid + "','" + room + "',0)";
            Statement st = con.createStatement();
            st.addBatch(sql1);
            st.addBatch(sql2);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void addSchedule(String owner, String room, String startDate, String endDate, String roomUrl) {
        try {
            con = DbConnectionManager.getConnection();
            String sql =
                    "insert into  ofAvoirRealtime_ScheduledSessions values " +
                    "('" + owner + "','" + room + "','" + startDate + "','" + endDate + "','" + roomUrl + "')";
            Statement st = con.createStatement();
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void removeUserAsOnline(String jid, String room) {

        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "delete from  ofAvoirRealtime_OnlineUsers where " +
                    "jid = '" + jid + "'";

            Statement st = con.createStatement();
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void addItem(String roomName, String itemId, String content) {
        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "insert into ofAvoirRealtime_Classroom_LastWB values " +
                    "('" + roomName + "','" + itemId + "','<item-id>" + itemId + "</item-id>" + content + "')";
            Statement st = con.createStatement();

            // st.addBatch("delete from ofAvoirRealtime_Classroom_LastWB where owner = '" + roomName + "' and item_id ='" + itemId + "'");
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void updateItem(String roomName, String itemId, String content) {
        try {
            con = DbConnectionManager.getConnection();
            content = "<item-id>" + itemId + "</item-id>" + content;
            String sql =
                    "update ofAvoirRealtime_Classroom_LastWB set " +
                    "content ='" + content + "' where item_id ='" + itemId + "' and owner = '" + roomName + "'";
            System.out.println(sql);
            Statement st = con.createStatement();
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void deleteItem(String roomName, String itemId) {
        try {
            con = DbConnectionManager.getConnection();

            Statement st = con.createStatement();
            st.addBatch("delete from ofAvoirRealtime_Classroom_LastWB where owner = '" + roomName + "' and item_id ='" + itemId + "'");

            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void storeLastSlide(String presentationName, String slideName, String roomName) {
        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "insert into ofAvoirRealtime_Classroom_LastSlide values " +
                    "('" + roomName + "','" + presentationName + "','" + slideName + "')";
            Statement st = con.createStatement();
            st.addBatch("delete from ofAvoirRealtime_Classroom_LastSlide where owner = '" + roomName + "'");
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public void clearLastSession(String roomName) {
        try {
            con = DbConnectionManager.getConnection();
            Statement st = con.createStatement();
            st.addBatch("delete from ofAvoirRealtime_Classroom_LastSlide where owner = '" + roomName + "'");
            st.addBatch("delete from ofAvoirRealtime_Classroom_LastWB where owner = '" + roomName + "'");
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public String getAllSlideShows(String roomName) {
        String sql =
                "select * from ofAvoirRealtime_SlideShows where" +
                " roomname = '" + roomName + "'";
        return getRoomResourcesContent(sql);
    }

    public String getActiveSlideShows(String roomName) {
        String sql =
                "select * from ofAvoirRealtime_SlideShows where" +
                " roomname = '" + roomName + "' and active='true'";
        return getRoomResourcesContent(sql);
    }

    private String getRoomResourcesContent(String resultSQL) {


        StringBuilder sb = new StringBuilder();
        sb.append("<fileview>");
        try {
            con = DbConnectionManager.getConnection();

            ps = con.prepareStatement(resultSQL);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                sb.append("<file>");
                String fPath = rs.getString("filename");
                int ver = rs.getInt("version");
                String access = rs.getString("access_mode");
                sb.append("<file-name>").append(new File(fPath).getName()).append("</file-name>");
                sb.append("<file-path>").append(fPath).append("</file-path>");
                sb.append("<is-directory>").append("false").append("</is-directory>");
                sb.append("<access>").append(access).append("</access>");
                sb.append("<version>").append(ver).append("</version>");
                sb.append("</file>");
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        sb.append("</fileview>");

        return sb.toString();
    }

    public IQ getRoomOwner(IQ packet, String roomName) {
        String roomOnwer = null;
        String sql =
                "select * from ofAvoirRealtime_Rooms where room_name = '" + roomName.trim() + "'";
        try {

            con = DbConnectionManager.getConnection();

            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                roomOnwer = rs.getString("room_owner");
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        StringBuilder sb = new StringBuilder();
        sb.append("<room-owner>").append(roomOnwer).append("</room-owner>");
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.ROOM_OWNER_REPLY);
        queryResult.addElement("content").addText(sb.toString());
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

    public String[] getRoomDetails(String roomName) {

        String sql = "select * from ofAvoirRealtime_Rooms where room_name = '" + roomName.trim() + "'";

        StringBuilder sb = new StringBuilder();
        sb.append("<room-list>");
        try {
            con = DbConnectionManager.getConnection();

            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                String[] desc = new String[2];
                String roomDesc = rs.getString("room_desc");
                String roomType = rs.getString("room_type");
                desc[0] = roomDesc;
                desc[1] = roomType;
                return desc;
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return null;
    }

    public IQ getRoomList(IQ packet, String user) {

        String sql = "select distinct room_name, room_desc,room_type from ofAvoirRealtime_Rooms where room_owner = '" + user.trim() + "'" +
                " or room_member_username ='" + user.trim() + "'";

        StringBuilder sb = new StringBuilder();
        sb.append("<room-list>");
        try {
            con = DbConnectionManager.getConnection();

            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {

                String roomName = rs.getString("room_name");
                String roomDesc = rs.getString("room_desc");
                String roomType = rs.getString("room_type");
                sb.append("<room>");
                sb.append("<room-name>").append(roomName).append("</room-name>");
                sb.append("<room-desc>").append(roomDesc).append("</room-desc>");
                sb.append("<room-type>").append(roomType).append("</room-type>");
                sb.append("</room>");
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        sb.append("</room-list>");

        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.ROOM_LIST);
        queryResult.addElement("content").addText(sb.toString());
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

    public IQ getRoomMemberList(IQ packet, String roomName) {

        String sql = "select * from ofAvoirRealtime_Rooms where room_name = '" + roomName.trim() + "'";

        StringBuilder sb = new StringBuilder();
        sb.append("<room-member-list>");
        try {
            con = DbConnectionManager.getConnection();

            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {


                String username = rs.getString("room_member_username");
                String name = rs.getString("room_member_name");
                sb.append("<member>");
                sb.append("<member-username>").append(username).append("</member-username>");
                sb.append("<member-name>").append(name).append("</member-name>");

                sb.append("</member>");
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        sb.append("</room-member-list>");

        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.ROOM_MEMBERS);
        queryResult.addElement("content").addText(sb.toString());
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

    private String getLastSlideContent(String roomName) {

        StringBuilder sb = new StringBuilder();

        try {
            con = DbConnectionManager.getConnection();

            String sql = "select * from ofAvoirRealtime_Classroom_LastSlide where owner = '" + roomName + "'";
            Statement st = con.createStatement();
            ResultSet rs2 = st.executeQuery(sql);

            while (rs2.next()) {

                String presentationName = rs2.getString("presentation_name");
                String slideName = rs2.getString("slide_name");

                sb.append("<last-slide>");
                sb.append("<presentation-name>").append(presentationName).append("</presentation-name>");
                sb.append("<presentation-id>").append(presentationName).append("</presentation-id>");
                sb.append("<slide-name>").append(slideName).append("</slide-name>");
                sb.append("</last-slide>");
            }

        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return sb.toString();
    }

    private String getScheduledSessions(String owner) {

        StringBuilder sb = new StringBuilder();
        sb.append("<schedules>");
        try {
            con = DbConnectionManager.getConnection();
            String sql = "select * from ofAvoirRealtime_ScheduledSessions where owner = '" + owner + "'";
            Statement st = con.createStatement();
            ResultSet rs2 = st.executeQuery(sql);

            while (rs2.next()) {
                String roomOwner = rs2.getString("owner");
                String roomName = rs2.getString("room_name");
                Date startDate = rs2.getDate("start_date");
                Date endDate = rs2.getDate("end_date");
                String roomUrl = rs2.getString("room_url");
                sb.append("<schedule>");
                sb.append("<room-owner>").append(roomOwner).append("</room-owner>");
                sb.append("<room-name>").append(roomName).append("</room-name>");
                sb.append("<start-date>").append(startDate + "").append("</start-date>");
                sb.append("<end-date>").append(endDate + "").append("</end-date>");
                sb.append("<room-url>").append(roomUrl).append("</room-url>");
                sb.append("</schedule>");
            }

        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        sb.append("</schedules>");
        return sb.toString();
    }

    private void sendLastWBContent(IQ packet, String roomName) {

        try {
            con = DbConnectionManager.getConnection();

            String sql = "select * from ofAvoirRealtime_Classroom_LastWB where owner = '" + roomName + "'";
            Statement st = con.createStatement();
            ResultSet rs2 = st.executeQuery(sql);

            while (rs2.next()) {


                IQ replyPacket = IQ.createResultIQ(packet);
                Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                queryResult.addElement("mode").addText(Mode.ITEM_HISTORY_FROM_SERVER);
                String content = rs2.getString("content");
                queryResult.addElement("content").addText(content);
                replyPacket.setChildElement(queryResult);
                replyPacket.setTo(packet.getFrom());
                packetRouter.route(replyPacket);
            }

        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

    }

    /**
     * this sends back a list of people with MIC in requested room
     * @param packet
     * @param roomName Room to filter the mic holders
     */
    private void sendMICHolders(IQ packet, String roomName) {

        try {
            con = DbConnectionManager.getConnection();

            String sql = "select ofUser.name as name from ofUser,ofAvoirRealtime_OnlineUsers " +
                    " where ofAvoirRealtime_OnlineUsers.room = '" + roomName + "' and " +
                    " ofAvoirRealtime_OnlineUsers.jid =ofUser.username";// and  has_mic = 1";
            Statement st = con.createStatement();
            ResultSet rs2 = st.executeQuery(sql);

            while (rs2.next()) {
                IQ replyPacket = IQ.createResultIQ(packet);
                Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                queryResult.addElement("mode").addText(Mode.MIC_HOLDER);
                String jid = rs2.getString("name");
                RealtimePacketContent content = new RealtimePacketContent();
                content.addTag("nickname", jid);
                queryResult.addElement("content").addText(content.toString());
                replyPacket.setChildElement(queryResult);
                replyPacket.setTo(packet.getFrom());
                packetRouter.route(replyPacket);
            }

        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

    }

    public int getRoomResourceRoomVersion(String filePath) {

        int version = 0;
        try {
            con = DbConnectionManager.getConnection();
            String resultSQL =
                    "select version from ofAvoirRealtime_SlideShows where" +
                    " filename = '" + filePath + "'";
            ps = con.prepareStatement(resultSQL);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                version = rs.getInt("version");
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return version;
    }

    private String getRoomResourceRoomName(String filePath) {
        String roomName = null;
        try {
            con = DbConnectionManager.getConnection();
            String resultSQL =
                    "select room_name from ofAvoirRealtime_Classroom_SlideShows where" +
                    " file_path = '" + filePath + "'";
            ps = con.prepareStatement(resultSQL);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                roomName = rs.getString("room_name");
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return roomName;
    }

    public boolean addSlide(
            int slideshow,
            String title,
            String text,
            int slide_index,
            int text_color_r,
            int text_color_g,
            int text_color_b,
            int text_size,
            String question_path,
            String url,
            String imagePath,
            String status,
            int ver) {
        boolean done = false;
        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "insert into ofAvoirRealtime_Slides(slideshow,title,slide_text,slide_index,text_color_r," +
                    "text_color_g,text_color_b,text_size,question_path,url,image_path,status,version) values " +
                    "(" + slideshow + ",'" + title + "','" + text + "'," + slide_index + "," + text_color_r + "," + text_color_g + "," + text_color_b + "," +
                    "" + text_size + ",'" + question_path + "','" + url + "','" + imagePath + "','" + status.substring(0, 3) + "',0)";
            Statement st = con.createStatement();
            st.addBatch(sql);
            st.executeBatch();
            done = true;
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return done;
    }

    public void addSlideShowAsRoomResource(IQ packet, String roomName, String filename) {
        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "update ofAvoirRealtime_SlideShows set  active='true' where" +
                    " filename='" + filename + "' and roomname = '" + roomName + "'";
            Statement st = con.createStatement();
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        broadcastRoomResources(packet, roomName);
    }

    public void deleteRoom(String roomName) {
        try {
            con = DbConnectionManager.getConnection();

            String sql1 =
                    "delete from ofAvoirRealtime_Rooms where room_name = '" + roomName.trim() + "'";
            String sql2 = "delete from ofAvoirRealtime_SlideShows where roomname = '" + roomName.trim() + "'";
            String sql3 = "delete from ofAvoirRealtime_Classroom_LastSlide where owner = '" + roomName.trim() + "'";
            String sql4 = "delete from ofAvoirRealtime_Classroom_LastWB where owner = '" + roomName.trim() + "'";
            String sql5 = "delete from ofAvoirRealtime_ScheduledSessions where room_name = '" + roomName.trim() + "'";
            Statement st = con.createStatement();
            st.addBatch(sql1);
            st.addBatch(sql2);
            st.addBatch(sql3);
            st.addBatch(sql4);
            st.addBatch(sql5);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

    }

    public int getPresentationId() {
        return -1;
    }

    public int addPresentationName(String roomname, String filename) {

        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "insert into ofAvoirRealtime_SlideShows(roomname,filename,version,active,access_mode) values " +
                    "('" + roomname + "','" + filename + "',0,'false','private')";
            Statement st = con.createStatement();
            st.addBatch(sql);
            st.executeBatch();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return getPresentationId(filename);
    }

    public int getPresentationId(String presentationName) {
        int presentationId = -1;
        try {
            con = DbConnectionManager.getConnection();
            String resultSQL =
                    "select id from ofAvoirRealtime_SlideShows where" +
                    " filename = '" + presentationName + "'";
            ps = con.prepareStatement(resultSQL);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                presentationId = rs.getInt("id");
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return presentationId;
    }

    public boolean roomResourceExists(String filePath) {
        boolean exists = false;
        try {
            con = DbConnectionManager.getConnection();
            String resultSQL =
                    "select room_name from ofAvoirRealtime_Classroom_SlideShows where" +
                    " file_path = '" + filePath + "'";
            ps = con.prepareStatement(resultSQL);

            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                exists = true;
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return exists;
    }

    public IQ deleteRoomResource(IQ packet, String serverFilePath, String localFilePath) {

        try {
            con = DbConnectionManager.getConnection();
            String resultSQL =
                    "update ofAvoirRealtime_SlideShows set active='false' where" +
                    " filename = '" + serverFilePath.trim() + "'";

            ps = con.prepareStatement(resultSQL);
            ps.executeUpdate();
            IQ replyPacket = IQ.createResultIQ(packet);
            Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
            queryResult.addElement("mode").addText(Mode.DELETE_ROOM_RESOURCE_SUCCESS);
            queryResult.addElement("content").addText("<file-path>" + localFilePath + "</file-path>");
            replyPacket.setChildElement(queryResult);
            return replyPacket;

        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        return null;
    }

    public void broadcastRoomResources(IQ packet, String roomName) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.ROOM_RESOURCES);
        queryResult.addElement("content").addText(getActiveSlideShows(roomName));
        replyPacket.setChildElement(queryResult);

        ArrayList<JID> jids = RUserManager.users.get(roomName.toLowerCase());
        if (jids == null) {

            jids = RUserManager.users.get(roomName.toLowerCase());
            if (jids == null) {

                return;
            }
        }
        for (int i = 0; i < jids.size(); i++) {

            JID jid = jids.get(i);

            replyPacket.setTo(jid);
            replyPacket.setFrom(packet.getFrom());
            packetRouter.route(replyPacket);
        }
    }

    /* public void addSlideShowAsRoomResource(IQ packet, String roomName, String filePath, boolean broadcastResult) {
    try {
    con = DbConnectionManager.getConnection();
    String sql =
    "insert into ofAvoirRealtime_Classroom_SlideShows values " +
    "('" + filePath + "','" + roomName + "',0,'false','private')";

    ps = con.prepareStatement(sql);
    ps.executeUpdate();
    } catch (SQLException ex) {
    ex.printStackTrace();
    } finally {
    try {
    con.close();
    } catch (Exception ex) {
    ex.printStackTrace();
    }
    }
    if (broadcastResult) {
    broadcastRoomResources(packet, roomName);
    }
    }*/
    public IQ getRoomResources(IQ packet, String roomName) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.ROOM_RESOURCES);
        String username = packet.getFrom().toBareJID();
        username = username.substring(0, username.indexOf("@"));
        String content = getActiveSlideShows(roomName) + getLastSlideContent(roomName) +
                getScheduledSessions(username);

        queryResult.addElement("content").addText(content);
        replyPacket.setChildElement(queryResult);
        sendLastWBContent(packet, roomName);
        sendMICHolders(packet, roomName);
        return replyPacket;
    }

    public void updateSlideShowRoomResourcePath(String oldPath, String newFilePath) {
        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "update ofAvoirRealtime_Classroom_SlideShows set file_path ='" + newFilePath + "'" +
                    " where file_path = '" + oldPath + "'";


            ps = con.prepareStatement(sql);
            ps.executeUpdate();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }


    }

    public void increaseSlideShowRoomResourceVersion(IQ packet, String filePath) {
        try {
            con = DbConnectionManager.getConnection();

            String sql =
                    "select * from ofAvoirRealtime_Classroom_SlideShows where" +
                    " file_path = '" + filePath + "'";

            ps = con.prepareStatement(sql);

            ResultSet rs = ps.executeQuery();
            int existingVer = 0;
            while (rs.next()) {
                existingVer = rs.getInt("version");
            }

            sql =
                    "update ofAvoirRealtime_Classroom_SlideShows set version =" + (existingVer + 1) + "" +
                    " where file_path = '" + filePath + "'";


            ps = con.prepareStatement(sql);
            ps.executeUpdate();
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        broadcastRoomResources(packet, filePath);
    }
}
