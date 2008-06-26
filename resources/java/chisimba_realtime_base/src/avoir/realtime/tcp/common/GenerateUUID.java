/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common;

import java.util.UUID;

/**
 *
 * @author developer
 */
public class GenerateUUID {

    public static String getId() {
        UUID id = UUID.randomUUID();
        return id.toString();
    }
}
