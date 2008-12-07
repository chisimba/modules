/**
 * 	$Id: ChatRoom.java,v 1.3 2007/02/02 10:59:15 davidwaf Exp $
 *
 *  Copyright (C) GNU/GPL AVOIR 2007
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.classroom;

import java.io.IOException;
import java.util.Properties;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.File;
import java.io.PrintWriter;
import java.io.FileWriter;

/**
 *
 * @author developer
 */
public class RealtimeOptions {

    public static String AUDIO_MIC_PORT = "AudioMicPort";
    public static String AUDIO_SPEAKER_PORT = "AudioSpeakerPort";
    public static String AUDIO_SERVER_HOST = "AudioServerHost";

    public RealtimeOptions() {

        init();

        loadProperties();
        if (isBrowserUsingProxy()) {
            System.setProperty("network.proxy_host", getBrowserProxyHost());
            System.setProperty("network.proxy_port", getBrowserProxyPort() + "");

        }
    }
    Properties props;
    //user can install the app in any folder..so use the System class to figure out where
    String fileName = avoir.realtime.common.Constants.getRealtimeHome() + "/conf/realtime.properties";
    String defaultFileName = avoir.realtime.common.Constants.getRealtimeHome() + "/conf/default.properties";
    String confDir = avoir.realtime.common.Constants.getRealtimeHome() + "/conf/";
    String binDir = avoir.realtime.common.Constants.getRealtimeHome() + "/bin/";
    String libDir = avoir.realtime.common.Constants.getRealtimeHome() + "/lib/";
    String soundsDir = avoir.realtime.common.Constants.getRealtimeHome() + "/sounds/";
    String logDir = avoir.realtime.common.Constants.getRealtimeHome() + "/log/";
    String iconsDir = avoir.realtime.common.Constants.getRealtimeHome() + "/icons/resources/";
    private String[][] actualProps = {
        {"DirectConnection", "true"},
        {"SystemProxy", "false"},
        {"ManualProxy", "false"},
        {"ProxyHost", ""},
        {"ProxyPort", "0"},
        {"UseOnlineSuperNodes", "true"},
        {"UseCache", "true"},
        {"BrowserConnection", "Direct"},
        {"BrowserProxyHost", ""},
        {"BrowserProxyPort", ""},
        {AUDIO_SERVER_HOST, "localhost"},
        {AUDIO_SPEAKER_PORT, "22224"},
        {AUDIO_MIC_PORT, "4711"},
    };

    /**
     * write the options into a file
     * @param filename
     */
    private void write(String filename) {
        for (int i = 0; i < actualProps.length; i++) {
            write(filename, actualProps[i][0] + "=" + actualProps[i][1]);
        }
    }

    public void init() {
        File confFile = new File(confDir);
        File defaultFile = new File(defaultFileName);
        File file = new File(fileName);
        if (!confFile.exists()) {
            confFile.mkdirs();
        }
        if (!defaultFile.exists()) {
            write(defaultFileName);

        }
        if (!file.exists()) {
            write(fileName);
        }
        File binFile = new File(binDir);
        if (!binFile.exists()) {
            binFile.mkdirs();
        }
        File libFile = new File(libDir);
        if (!libFile.exists()) {
            libFile.mkdirs();
        }
        File soundsFile = new File(soundsDir);
        if (!soundsFile.exists()) {
            soundsFile.mkdirs();
        }
        File logFile = new File(logDir);
        if (!logFile.exists()) {
            logFile.mkdirs();
        }
        File iconsFile = new File(iconsDir);
        if (!iconsFile.exists()) {
            iconsFile.mkdirs();
        }
        loadProperties();
        updateAnyNewProperties();
    }

    private void updateAnyNewProperties() {
        for (int i = 0; i < actualProps.length; i++) {
            String key = actualProps[i][0];
            String val = actualProps[i][1];
            if (getProperty(key) == null) {
                saveProperty(key, val);
            }
        }
    }

    private void write(String filename, String txt) {
        try {
            FileWriter outFile = new FileWriter(filename, true);
            PrintWriter printWriter = new PrintWriter(outFile);
            printWriter.println(txt);
            printWriter.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public String getAudioServerHost() {
        return getProperty(AUDIO_SERVER_HOST);
    }

    public int getAudioMicPort() {
        return Integer.parseInt(getProperty(AUDIO_MIC_PORT));
    }

    public void setAudioMicPort(int port) {
        saveProperty(AUDIO_MIC_PORT, port + "");
    }

    public void setAudioSpeakerPort(int port) {
        saveProperty(AUDIO_SPEAKER_PORT, port + "");
    }

    public void setAudioServerHost(String host) {
        saveProperty(AUDIO_SERVER_HOST, host);
    }

    public int getAudioSpeakerPort() {
        return Integer.parseInt(getProperty(AUDIO_SPEAKER_PORT));
    }

    public void saveProperty(String prop, String value) {
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

    /**
     * returns true if direct connection is used
     * @return
     */
    public boolean useDirectConnection() {
        return new Boolean(getProperty("DirectConnection")).booleanValue();
    }

    public boolean isBrowserUsingProxy() {
        String type = getProperty("BrowserConnection");
        if (type == null) {
            return false;
        }
        return type.equalsIgnoreCase("Direct") ? false : true;
    }

    public String getBrowserProxyHost() {
        return getProperty("BrowserProxyHost");
    }

    public int getBrowserProxyPort() {
        String port = getProperty("BrowserProxyPort");
        if (port == null) {
            return 0;
        }
        int x = 0;
        try {
            x = Integer.parseInt(port.trim());

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return x;
    }

    /**
     * Returns true if system proxy is used
     * @return
     */
    public boolean useSystemProxy() {
        return new Boolean(getProperty("SystemProxy")).booleanValue();
    }

    public boolean useCache() {
        String useCache = getProperty("UseCache");
        if (useCache == null) {
            return true;
        }
        if (useCache.equals("")) {
            return true;
        }
        return new Boolean(useCache).booleanValue();
    }

    /**
     * Returns true if manual proxy is used
     * @return
     */
    public boolean useManualProxy() {
        return new Boolean(getProperty("ManualProxy")).booleanValue();
    }

    /**
     * returns true if configured to use online super nodes
     * @return
     */
    public boolean useOnlineSuperNodes() {
        return new Boolean(getProperty("UseOnlineSuperNodes")).booleanValue();
    }

    /**
     * 
     * @param status
     */
    public void setUseOnlineSuperNodes(boolean status) {
        saveProperty("UseOnlineSuperNodes", status + "");
    }

    /**
     * Gets current internet proxy host value
     * @return
     */
    public String getProxyHost() {
        return getProperty("ProxyHost");
    }

    public void setProxyHost(String host) {
        saveProperty("ProxyHost", host);
    }

    public String getProxyPort() {
        return getProperty("ProxyPort");
    }

    public void setRealtimeProxyPort(String port) {
        saveProperty("RealtimeProxyPort", port);
    }

    public String getRealtimeProxyPort() {
        return getProperty("RealtimeProxyPort");
    }

    public void setProxyPort(String port) {
        saveProperty("ProxyPort", port);
    }

    public String getProperty(String prop) {
        return props.getProperty(prop);
    }

    public void setUseDirectConnection(boolean use) {
        saveProperty("DirectConnection", use + "");
    }

    public void setUseManualProxy(boolean use) {
        saveProperty("ManualProxy", use + "");
    }

    public void setUseSystemProxy(boolean use) {
        saveProperty("SystemProxy", use + "");
    }

    public void setUseCache(boolean use) {
        saveProperty("UseCache", use + "");
    }

    public String getConnectionMode() {
        if (useDirectConnection()) {
            return "Direct Connection";
        } else if (useSystemProxy()) {
            return "System Proxy";
        } else {
            return "Manual Proxy Configuration";
        }
    }

    public void loadProperties() {
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
}

