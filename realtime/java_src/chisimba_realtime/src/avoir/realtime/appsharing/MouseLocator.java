/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.appsharing;

import java.awt.Point;
import java.lang.reflect.Method;

public class MouseLocator {

    private static Class clazzMouseInfo;
    private static Class clazzPointerInfo;
    private static Method methodGetMouseInfo;
    private static Method methodGetLocation;


    static {
        try {
            clazzMouseInfo = Class.forName("java.awt.MouseInfo");
            clazzPointerInfo = Class.forName("java.awt.PointerInfo");

            methodGetMouseInfo = clazzMouseInfo.getMethod("getPointerInfo", null);
            methodGetLocation = clazzPointerInfo.getMethod("getLocation", null);
        } catch (Exception e) {
            System.out.println("MouseInfo class not found. Skipping");
            e.printStackTrace();
        }
    }

    public static Point getMouseLocation() {

        Point retPoint = null;
        try {
            if (null != methodGetLocation) {
                Object objPointerInfo = methodGetMouseInfo.invoke(null, null);
                Object objPoint = methodGetLocation.invoke(objPointerInfo, null);
                retPoint = (Point) objPoint;
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return retPoint;
    }
}
