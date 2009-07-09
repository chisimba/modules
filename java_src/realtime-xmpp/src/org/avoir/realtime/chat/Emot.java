/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.avoir.realtime.chat;


import javax.swing.ImageIcon;

public class Emot {

    private ImageIcon icon;
    private String symbol;
    private String desc;

    public Emot(ImageIcon icon, String symbol, String desc) {
        this.icon = icon;
        this.symbol = symbol;
        this.desc = desc;
    }

    public String getDesc() {
        return desc;
    }

    public void setDesc(String desc) {
        this.desc = desc;
    }

    public ImageIcon getIcon() {
        return icon;
    }

    public void setIcon(ImageIcon icon) {
        this.icon = icon;
    }

    public String getSymbol() {
        return symbol;
    }

    public void setSymbol(String symbol) {
        this.symbol = symbol;
    }
}

