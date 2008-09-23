/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.server;
/**
 * Holds a delay time in milliseconds and nanoseconds.
 *
 * @author Lars Samuelsson,modified by David Wafula
 */
public class Delay {
    /**
     * The number of nanoseconds in a millisecond.
     */
    public static final int MILLINANO = 1000000;
    private double delay;
    /**
     * Creates a delay of the given milliseconds and 
     * nanoseconds.
     *
     * @param delay The delay given as a double
     */
    public Delay(double delay) {
	this.delay = delay;
    }
    /**
     * Fetches the delay as a double value.
     * 
     * @return The delay given as a double
     */
    public double getDelay() {
	return delay;
    }
    /**
     * Fetches the number of milliseconds in this delay.
     * 
     * @return The number of milliseconds in this delay
     */
    public long getMillis() {
	return (long) delay;
    }
    /**
     * Fetches the number of nanoseconds in this delay.
     * 
     * @return the number of nanoseconds in this delay
     */
    public int getNanos() {
	return (int) (MILLINANO * (delay - getMillis()));
    }
    /**
     * Subtracts a delay given as a double from this delay.
     *
     * @param delay The delay to subtract
     * @return      The result of the subtraction
     */
    public Delay sub(double delay) {
	return new Delay(this.delay - delay);
    }
    /**
     * Adds a delay given as a double to this delay.
     * 
     * @param delay The delay to add
     * @return      The result of the addition
     */
    public Delay add(double delay) {
	return new Delay(this.delay + delay);
    }
    /**
     * Subtracts another delay from this delay.
     *
     * @param delay Another delay
     * @return      The result of the subtraction
     */
    public Delay sub(Delay delay) {
	if(delay == null)
	    return null;
	return new Delay(this.delay - delay.getDelay());
    }
    /**
     * Adds another delay to this delay.
     *
     * @param delay Another delay
     * @return      The result of the addition
     */
    public Delay add(Delay delay) {
	if(delay == null)
	    return null;
	return new Delay(this.delay + delay.getDelay());
    }
    /** 
     * A string representation of this delay.
     *
     * @return A string on the form millis.nanos
     */
    public String toString() {
	return String.valueOf(delay);
    }
}
