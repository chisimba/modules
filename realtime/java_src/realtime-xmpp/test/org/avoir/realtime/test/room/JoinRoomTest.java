/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room;

import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.test.net.ChisimbaRealtimeTest;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;
/**
 *
 * @author david
 */
public class JoinRoomTest extends ChisimbaRealtimeTest {

    public JoinRoomTest() {
        super();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    /**
     * The default room name should be the names of the joiner
     * Test this
     */
    @Test
    public void testDefaultRoomName() {

        assertEquals(GeneralUtil.formatStr(args[9]," "), ConnectionManager.getRoomName());
    }
}