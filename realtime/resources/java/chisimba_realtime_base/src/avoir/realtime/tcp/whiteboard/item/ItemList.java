/**
 *  $Id: ItemList.java,v 1.6 2007/05/18 10:37:09 davidwaf Exp $
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
package avoir.realtime.tcp.whiteboard.item;

import java.io.Serializable;
import java.util.Enumeration;
import java.util.Vector;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * Stores a List of Items on the whiteboard canavs
 */
@SuppressWarnings("serial")
public class ItemList implements Serializable, Cloneable {

    private static Logger logger = Logger.getLogger(ItemList.class.getName());

    /**
     * Stores the elements (Items) Objects in the ItemList
     */
    protected Vector<Item> list = new Vector<Item>();

    /**
     * Stores the id (IdentityHashCode) of the Items in the ItemList
     */
    protected Vector<Integer> ids = new Vector<Integer>();

    /**
     * Adds a new element to the list and assigns it an id
     * @param element (Item) Object
     * @return the id of the element (Object)
     */
    public synchronized Integer addElement(Item element) {
        Integer id = new Integer(System.identityHashCode(element));
        addElementWithID(id, element);
        return id;
    }

    /**
     * Returns the size of the ItemList
     * @return int size
     */
    public int size() {
        return list.size();
    }

    /**
     * this replaces an item in the list with a specified index
     * @param index int
     * @param element Item
     */
    public void set(int index, Item element) {
        list.set(index, element);
    }

    /**
     * returns an item at a specific index
     * @param index
     * @return
     */
    public Item getItem(int index) {
        return (Item) list.elementAt(index);
    }

    /**
     * Clears the ItemList
     */
    public synchronized void resetList() {
        list.clear();
        ids.clear();
    }

    /**
     * Removes the last Item and its id
     */
    public synchronized void removeLastElement() {
        if (list.size() > 0) {
            int index = list.size() - 1;
            list.remove(index);
            ids.remove(index);
        }
    }

    /**
     * Adds an element that already has an id
     * @param id Object id
     * @param element Object element
     */
    public synchronized void addElementWithID(Integer id, Item element) {
        ids.addElement(id);
        list.addElement(element);
    }

    /**
     * Replaces the element with the specified id
     * @param oldID Id of the element to be replaced
     * @param element new element
     * @return Returns the new id assigned to the object
     */
    public synchronized Integer replaceElement(Integer oldID, Item element) {
        int idx = ids.indexOf(oldID);
        if (idx >= 0) {
            ids.removeElementAt(idx);
            list.removeElementAt(idx);
            return addElement(element);
        } else {
            return null;
        }
    }

    /**
     * Replaces an element with another element
     * @param oldID id of the element to be replaced
     * @param id id to be assigned to the new element
     * @param element the new element
     * @return boolean
     */
    public synchronized boolean replaceElementWithID(Integer oldID, Integer id,
            Item element) {
        int idx = ids.indexOf(oldID);
        if (idx >= 0) {
            ids.removeElementAt(idx);
            list.removeElementAt(idx);
            ids.addElement(id);
            list.addElement(element);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the id of the specified element
     * @param element Item element
     * @return Object id
     */
    public synchronized Object getIDOfElement(Item element) {
        int idx = list.indexOf(element);
        return (idx >= 0) ? ids.elementAt(idx) : null;
    }

    /**
     * Returns an Enumeration of the elements in the ItemList
     * @return Enumeration
     */
    public synchronized Enumeration elements() {
        // return ((Vector<Item>) list.clone()).elements(); // not type safe
        return list.elements();
    }

    /**
     * Calls super.clone()
     * @return Object
     */
    public final synchronized ItemList clone() {
        try {
            ItemList il = (ItemList) super.clone();
            return il;
        } catch (CloneNotSupportedException e) {
            logger.log(Level.SEVERE, "Error during clone", e);
            throw new Error("ItemList clone error");
        }
    }
}
