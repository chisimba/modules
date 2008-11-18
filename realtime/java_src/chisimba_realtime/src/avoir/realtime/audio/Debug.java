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
package avoir.realtime.audio;

import java.io.PrintStream;
import java.util.StringTokenizer;
import java.security.AccessControlException;

public class Debug {

    public static boolean SHOW_ACCESS_CONTROL_EXCEPTIONS = false;
    private static final String PROPERTY_PREFIX = "jsinfo.";
    // The stream we output to
    private static PrintStream sm_printStream = System.out;
    private static String indent = "";    // general
    private static boolean sm_bTraceAllExceptions = getBooleanProperty("TraceAllExceptions");    // specific
    private static boolean sm_bTraceMixerPanel = getBooleanProperty("TraceMixerPanel");
    private static boolean sm_bTraceControlsPanel = getBooleanProperty("TraceControlsPanel");
    private static boolean sm_bTraceControlPropertiesPanel = getBooleanProperty("TraceControlPropertiesPanel");
    private static boolean sm_bTraceLineTableModel = getBooleanProperty("TraceLineTableModel");
    private static boolean sm_bTraceServiceProviderTableModel = getBooleanProperty("TraceServiceProviderTableModel");
    private static boolean sm_bTraceConfigurationFilesTableModel = getBooleanProperty("TraceConfigurationFilesTableModel");

    public static final boolean getTraceAllExceptions() {
        return sm_bTraceAllExceptions;
    }

    public static final boolean getTraceMixerPanel() {
        return sm_bTraceMixerPanel;
    }

    public static final boolean getTraceControlsPanel() {
        return sm_bTraceControlsPanel;
    }

    public static final boolean getTraceControlPropertiesPanel() {
        return sm_bTraceControlPropertiesPanel;
    }

    public static final boolean getTraceLineTableModel() {
        return sm_bTraceLineTableModel;
    }

    public static final boolean getTraceServiceProviderTableModel() {
        return sm_bTraceServiceProviderTableModel;
    }

    public static final boolean getTraceConfigurationFilesTableModel() {
        return sm_bTraceConfigurationFilesTableModel;
    }
    // make this method configurable to write to file, write to stderr,...
    public static void out(String strMessage) {
        if (strMessage.length() > 0 && strMessage.charAt(0) == '<') {
            if (indent.length() > 2) {
                indent = indent.substring(2);
            } else {
                indent = "";
            }
        }
        String newMsg = null;
        if (indent != "" && strMessage.indexOf("\n") >= 0) {
            newMsg = "";
            StringTokenizer tokenizer = new StringTokenizer(strMessage, "\n");
            while (tokenizer.hasMoreTokens()) {
                newMsg += indent + tokenizer.nextToken() + "\n";
            }
        } else {
            newMsg = indent + strMessage;
        }
        sm_printStream.println(newMsg);
        if (strMessage.length() > 0 && strMessage.charAt(0) == '>') {
            indent += "  ";
        }
    }

    public static void out(Throwable throwable) {
        throwable.printStackTrace(sm_printStream);
    }

    private static boolean getBooleanProperty(String strName) {
        String strPropertyName = PROPERTY_PREFIX + strName;
        String strValue = "false";
        try {
            strValue = System.getProperty(strPropertyName, "false");
        } catch (AccessControlException e) {
            if (SHOW_ACCESS_CONTROL_EXCEPTIONS) {
                out(e);
            }
        }
        // Debug.out("property: " + strPropertyName + "=" + strValue);
        boolean bValue = strValue.toLowerCase().equals("true");
        // Debug.out("bValue: " + bValue);
        return bValue;
    }
}
/*** Debug.java ***/
