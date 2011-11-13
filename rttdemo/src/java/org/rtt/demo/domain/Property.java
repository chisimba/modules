/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
package org.rtt.demo.domain;

/**
 *
 * @author davidwaf
 */
public class Property {

    private String propmodule;
    private String propkey;
    private String propvalue;
    private int id;

    public Property() {
    }

    public Property(String propkey, String propvalue) {
        this.propkey = propkey;
        this.propvalue = propvalue;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getPropkey() {
        return propkey;
    }

    public void setPropkey(String propkey) {
        this.propkey = propkey;
    }

    public String getPropmodule() {
        return propmodule;
    }

    public void setPropmodule(String propmodule) {
        this.propmodule = propmodule;
    }

    public String getPropvalue() {
        return propvalue;
    }

    public void setPropvalue(String propvalue) {
        this.propvalue = propvalue;
    }

    @Override
    public String toString() {
        return "Property{" + "propmodule=" + propmodule + "propkey=" + propkey + "propvalue=" + propvalue + "id=" + id + '}';
    }
   
}
