/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.domain;

/**
 *
 * @author davidwaf
 */
public class DemoUser {
    private String nickName;
    private String id;
    private String dateCreated;
    private String username;

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }
    

    public String getDateCreated() {
        return dateCreated;
    }

    public void setDateCreated(String dateCreated) {
        this.dateCreated = dateCreated;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getNickName() {
        return nickName;
    }

    public void setNickName(String nickName) {
        this.nickName = nickName;
    }
    
    
    
}
