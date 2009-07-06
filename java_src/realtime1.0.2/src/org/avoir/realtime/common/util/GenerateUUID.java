/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.common.util;

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
