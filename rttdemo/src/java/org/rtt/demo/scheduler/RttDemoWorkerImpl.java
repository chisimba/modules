/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.scheduler;


import org.rtt.demo.service.RttDemoService;
import org.springframework.scheduling.annotation.Async;
import org.springframework.stereotype.Component;

/**
 *
 * @author davidwaf
 */
@Component("asyncScheduler")
public class RttDemoWorkerImpl implements RttDemoWorker {

    @Override
    @Async
    public void pollDBToKeepConnectionAlive(RttDemoService rttDemoService) {
      rttDemoService.pollDBToKeepConnectionAlive();
      
    }
}
