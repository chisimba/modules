/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.chisimba.langtranslator;

/**
 *
 * @author davidwaf
 */
public class Item {

    private String code;
    private String description;
    private String translation;

    public Item(String code, String description, String translation) {
        this.code = code;
        this.description = description;
        this.translation = translation;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getTranslation() {
        return translation;
    }

    public void setTranslation(String translation) {
        this.translation = translation;
    }

    public String getLine(){
        return code+"~"+description+"~"+translation;
    }
    
    @Override
    public String toString() {
        return code;
    }

    @Override
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Item other = (Item) obj;
        if ((this.code == null) ? (other.code != null) : !this.code.equals(other.code)) {
            return false;
        }
        return true;
    }

    @Override
    public int hashCode() {
        int hash = 7;
        hash = 29 * hash + (this.code != null ? this.code.hashCode() : 0);
        return hash;
    }
}
