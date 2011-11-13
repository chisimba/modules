/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.scheduler;

import org.rtt.demo.service.RttDemoService;


/**
 *
 * @author davidwaf
 */
public interface RttDemoWorker {
    
    public void pollDBToKeepConnectionAlive(RttDemoService rttDemoService);
}
