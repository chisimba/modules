/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.jnlp;

/**
 *
 * @author davidwaf
 */
public class JnlpParam {

    private String userid;
    private String key;
    private String value;
    private String createdOn;

    public String getCreatedOn() {
        return createdOn;
    }

    public void setCreatedOn(String createdOn) {
        this.createdOn = createdOn;
    }

    public String getKey() {
        return key;
    }

    public void setKey(String key) {
        this.key = key;
    }

    public String getUserid() {
        return userid;
    }

    public void setUserid(String userid) {
        this.userid = userid;
    }

    public String getValue() {
        return value;
    }

    public void setValue(String value) {
        this.value = value;
    }
}
