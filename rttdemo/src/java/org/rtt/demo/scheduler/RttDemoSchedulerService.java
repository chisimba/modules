/*
 * To change this template, choose Tools | Templates
 * and open the template in
 * the editor.
 */
package org.rtt.demo.scheduler;


import org.rtt.demo.service.RttDemoService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Service;


/**
 * Scheduler for handling jobs
 */
@Service
public class RttDemoSchedulerService {

    @Qualifier("syncScheduler")
    private RttDemoWorkerImpl worker=new RttDemoWorkerImpl();
    
    @Autowired
    RttDemoService rttDemoService;

    /**
     * You can opt for cron expression or fixedRate or fixedDelay
     * <p>
     * See Spring Framework 3 Reference:
     * Chapter 25.5 Annotation Support for Scheduling and Asynchronous Execution
     */
    
    //once every hour
    //0 */2 * * *
    @Scheduled(cron = "0 */1 * * * ?")
    public void doSchedule() {
        worker.pollDBToKeepConnectionAlive(rttDemoService);
    }
}