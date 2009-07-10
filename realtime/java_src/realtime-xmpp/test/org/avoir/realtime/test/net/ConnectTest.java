/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.net;

import org.avoir.realtime.net.ConnectionManager;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;

/**
 *
 * @author david
 */
public class ConnectTest extends  ChisimbaRealtimeTest {

    public ConnectTest() {
        super();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Test
    public void testServerConnection() {
        System.out.println("Testing direct connection:");
        String host = "localhost";
        int port = 5222;
        String audioVideoUrl = "localhost:7070/red5";
        assertTrue(ConnectionManager.init(host, port, audioVideoUrl) == true);
        
    }
}