/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.common;

/**
 *
 * @author developer
 */
public class Constants {

    public static class MIC {

        public final static int MIC_ON = 1;
        public final static int MIC_OFF = 0;
    }

    public static class Proxy {

        public final static int NO_PROXY = 0;
        public final static int HTTP_PROXY = 1;
        public final static int SOCKS_PROXY = 2;
    }

    public static class Whiteboard {

        public final static int MOVE = 0;
        public final static int LINE = 1;
        public final static int DRAW_RECT = 2;
        public final static int FILL_RECT = 3;
        public final static int DRAW_OVAL = 4;
        public final static int FILL_OVAL = 5;
        public final static int PEN = 6;
        public final static int ERASE = 7;
        public final static int TEXT = 8;
        public final static int UNDO = 9;
    }

    public static class Dialogs {

        public final static int REQUEST_DENIED = 0;
        public final static int REQUEST_APPROVED = 1;
    }

    public static class AdminLevels {

        public final static int ADMIN_LEVEL = 1;
        public final static int PARTICIPANT_LEVEL = 3;
        public final static int OWNER_LEVEL = 0;
    }
    public final static int ESSAY_QUESTION = 0;
    public final static int MCQ_QUESTION = 1;
    public final static int TRUE_FALSE_QUESTION = 2;
    public static final String PREFERRED_ENCODING = "UTF-8";
    public static final String HOME = System.getProperty("user.home") + "/avoir-realtime-1.0.2/";
    public static final String REMOVE_ROOM_RESOURCE_CMD = "remove-room-resource";
    public static final String ADD_ROOM_RESOURCE_CMD = "add-room-resource";
}
