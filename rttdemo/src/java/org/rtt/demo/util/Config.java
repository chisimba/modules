/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.util;

import java.util.Properties;

/**
 *
 * @author davidwaf
 */
public class Config {

    private Properties theProperties;

    public Properties getTheProperties() {
        return theProperties;
    }

    public void setTheProperties(Properties theProperties) {
        this.theProperties = theProperties;
    }

    public String getProperty(String key) {

        return theProperties.getProperty(key);
    }
}
