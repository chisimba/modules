package zyh.util;

import java.util.LinkedList;

/**
 *
 * ThreadPool implements a thread pool.
 *
 */
public final class ThreadPool extends ThreadGroup {

    private static int groupNumber = 0;
    private int maximumPoolSize;
    private int currentPoolSize;
    private LinkedList waitingRunnableObjectList = new LinkedList();

    public ThreadPool(int initialPoolSize, int maximumPoolSize) {
        super("ThreadPool-" + (groupNumber++));
        this.maximumPoolSize = (maximumPoolSize <= 0) ? 10000 : maximumPoolSize;

        initialPoolSize = (initialPoolSize <= this.maximumPoolSize) ? initialPoolSize : this.maximumPoolSize;
        this.currentPoolSize = initialPoolSize;
        for (currentPoolSize = 0; currentPoolSize < initialPoolSize; currentPoolSize++) {
            (new PooledThread(this, "PooledThread-" + currentPoolSize)).start();
        }
    }

    public ThreadPool() {
        this(0, 0);
    }

    public synchronized void close() {
        idleThreadCount = 0;
        waitingRunnableObjectList = null;
        notifyAll();
    }

    public boolean isClosed() {
        return waitingRunnableObjectList == null;
    }
    private int idleThreadCount = 0;

    public boolean isAllIdle() {
        return currentPoolSize <= idleThreadCount && waitingRunnableObjectList.size() <= 0;
    }

    protected synchronized Runnable getWaitingRunnableObject() {
        idleThreadCount++;

        while (!isClosed() && waitingRunnableObjectList.size() <= 0) {
            try {
                wait();
            } catch (InterruptedException ie) {
            }
        }

        idleThreadCount--;

        if (isClosed()) {
            return null;
        } else {
            return (Runnable) waitingRunnableObjectList.removeFirst();
        }
    }

    public synchronized void run(Runnable runnableObject) {
        if (isClosed()) {
            throw new RuntimeException("Tried to access a closed thread pool");
        }
        if (idleThreadCount <= 0 && currentPoolSize < maximumPoolSize) {
            currentPoolSize++;
            (new PooledThread(this, "PooledThread-" + currentPoolSize)).start();
        }
        waitingRunnableObjectList.addLast(runnableObject);
        notify();
    }

    /**
     * PooledThread class is used to perform a Runnable object's run() method
     */
    class PooledThread extends Thread {

        private ThreadPool threadPool;

        PooledThread(ThreadPool threadPool, String name) {
            super(threadPool, name);
            this.threadPool = threadPool;
        }

        public void run() {
            while (!threadPool.isClosed()) {
                Runnable obj = threadPool.getWaitingRunnableObject();
                if (obj != null) {
                    obj.run();
                }
            }
        }
    }
}
