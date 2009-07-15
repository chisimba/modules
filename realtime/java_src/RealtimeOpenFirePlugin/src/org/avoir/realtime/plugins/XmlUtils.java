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
package org.avoir.realtime.plugins;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 *
 * @author developer
 */
public class XmlUtils {

    public static String readString(Element element, String str) {
        String val = null;

        try {
            NodeList bvals = element.getElementsByTagName(str);
            Element bElement = (Element) bvals.item(0);
            NodeList bNodes = bElement.getChildNodes();
            val = ((Node) bNodes.item(0)).getNodeValue();
        } catch (Exception ex) {
            //System.out.println("XmlUtils:readString:Element element: "+str+": "+ex.getMessage());
            // System.out.println("XmlUtils: Error at   " + Thread.currentThread().getStackTrace()[2].getLineNumber());
        }
        return val;
    }

    public static String readString(Document doc, String str) {
        String val = null;
        try {
            NodeList sender = doc.getElementsByTagName(str);
            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    val = ((Node) vals.item(0)).getNodeValue();
                }
            }
        } catch (Exception ex) {
            // System.out.println("XmlUtils: Error at   " + Thread.currentThread().getStackTrace()[2].getLineNumber());
        }

        return val;
    }

    public static int readInt(Document doc, String str) {
        int val = 0;
        try {
            NodeList sender = doc.getElementsByTagName(str);

            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    String s = ((Node) vals.item(0)).getNodeValue();
                    val = Integer.parseInt(s);
                }
            }
        } catch (Exception ex) {
            // ex.printStackTrace();
        }
        return val;
    }

    public static float readFloat(Document doc, String str) {
        float val = 0;
        try {
            NodeList sender = doc.getElementsByTagName(str);

            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    String s = ((Node) vals.item(0)).getNodeValue();
                    val = Float.parseFloat(s);
                }
            }
        } catch (Exception ex) {
            // ex.printStackTrace();
        }
        return val;
    }

    public static int readInt(Element element, String str) {
        int val = 0;

        try {
            NodeList bvals = element.getElementsByTagName(str);
            Element bElement = (Element) bvals.item(0);
            NodeList bNodes = bElement.getChildNodes();
            String bs = ((Node) bNodes.item(0)).getNodeValue();

            val = Integer.parseInt(bs.trim());
        } catch (Exception ex) {
            //  ex.printStackTrace();
        }
        return val;
    }

    public static double readDouble(Element element, String str) {
        double val = 0;

        try {
            NodeList bvals = element.getElementsByTagName(str);
            Element bElement = (Element) bvals.item(0);
            NodeList bNodes = bElement.getChildNodes();
            String bs = ((Node) bNodes.item(0)).getNodeValue();

            val = Double.parseDouble(bs.trim());
        } catch (Exception ex) {
            //  ex.printStackTrace();
        }
        return val;
    }

    public static double readDouble(Document doc, String str) {
        double val = 0;
        try {
            NodeList sender = doc.getElementsByTagName(str);

            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    String s = ((Node) vals.item(0)).getNodeValue();
                    val = Double.parseDouble(s);
                }
            }
        } catch (Exception ex) {
            // ex.printStackTrace();
        }
        return val;
    }
}
