/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.server;

/**
 * A very simple thread queue.
 * 
 * Used for managing threads externally rather 
 * than in a specific object. Also allows one
 * object to manage many queues. 
 * 
 * @author Lars Samuelsson,modified by David Wafula
 */
public class ThreadQueue {
    private long fallout;
    /**
     * Creates a thread queue with no specified 
     * fallout delay.
     *
     * The threads will wait until
     * another thread dequeues them.
     */
    public ThreadQueue() {
	fallout = 0;
    }
    /**
     * Creates a thread queue with a specified 
     * fallout delay.
     *
     * The threads will wait until 
     * another thread dequeues them or
     * the fallout delay has passed, 
     * whichever comes first.
     *
     * @param fallout The longest time a thread
     *                will wait before being
     *                released from the queue
     */
    public ThreadQueue(long fallout) {
	this.fallout = fallout;
    }
    /** 
     * Adds a thread to the queue.
     * 
     * The added thread will be put to 
     * wait for release.
     */
    public synchronized void enqueue() {
	try {
	    wait(fallout);
	}
	catch(InterruptedException e) {
	}
    }
    /** 
     * Releases all threads from the queue.
     */
    public synchronized void dequeueAll() {
	notifyAll();
    }
    /** 
     * Releases one thread from the queue.
     */
    public synchronized void dequeue() {
	notify();
    }
}
