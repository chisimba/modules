/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.audio;

import java.nio.ByteBuffer;

/**
 *  FIFO buffer.
 *  @author ravi
 *  @modified 2005-23-03 jamoore add dynamic buffer resize
 *  @modified 2005-23-03 jamoore add capacity accessor, audioresource and res name
 *  @modified 2008-15-05 david add removed logs, resource names and single param construtctor
 */
public final class VoiceDataBuffer {

    private ByteBuffer buffer = null;
    private int length;
    private int current;
    private boolean bufferOverFlow = false;

    /**
     *
     */
    public VoiceDataBuffer(int size) {
        super();
        buffer = ByteBuffer.allocate(size);
        length = 0;
        current = 0;
    }

    public int getCapacity() {

        return buffer.capacity();
    }

    /**
     *  Returns if there has been a buffer overflow.
     *  Not sync'd
     */
    public boolean isBufferOverFlow() {

        boolean rtn = bufferOverFlow;

        if (bufferOverFlow == true) {

            bufferOverFlow = false;
        }

        return rtn;

    }

    public void clear() {
        //buffer.clear ();
        buffer = ByteBuffer.allocate(buffer.capacity());
        length = 0;
        current = 0;
    }

    public byte[] get(int len) {
        synchronized (buffer) {
            if (len > length) {
                System.err.println("error: len larger than buffer content length");
                return null;
            }
            byte[] buf = new byte[len];
            byte[] rem = new byte[length - len];
            //			System.out.println("---popping len: " + len + " remlen: " +
            // rem.length);
            buffer.position(0);
            buffer.get(buf, 0, len);
            //			System.out.println("---got buf: " + arrayToString(buf));
            length -= len; // size of contents in buffer is minus len
            //			System.out.println("---length: " + length + " position: " +
            // buffer.position());

            current = buffer.position();
            //buffer.get(rem, current, length); // get remainder of buffer
            // contents
            for (int i = 0; i < length; i++) {
                rem[i] = buffer.get();
            }
            //			System.out.println("---got rem: " + arrayToString(rem));
            buffer.position(0); // reset buffer position to front of buffer

            buffer.put(rem); // shift remainder to beginning of buffer eg.
            // buffer.put(rem, 0, length);

            current = buffer.position();
            //			System.out.println("---current: " + current);
            //clear(buffer.position()); // may not be necessary
            return buf;
        }

    }

    /**
     *  Appends the given byte array to the end of this buffer. If a buffer
     *  overflow occurs a new buffer is allocated and it's size is increased
     *  acording to the audio resource's (speaker, mic) current buffer spec.
     *  @modified 2005-23-03 jamoore add dynamic buffer increase
     */
    public void append(byte[] array) {

        synchronized (buffer) {

            if (array.length + length > buffer.capacity()) {

                synchronized (buffer) {
                    bufferOverFlow = true; // only used for debugging, no sync needed

                    ByteBuffer tmpBuffer = ByteBuffer.allocate(buffer.capacity() +
                            AudioResource.LINE_BUFFER_SIZE);

                    tmpBuffer.put(buffer);

                    buffer = null;

                    buffer = tmpBuffer;

                    tmpBuffer = null;

                }


            }

            buffer.put(array);

            length += array.length;

            current = buffer.position();
        }
    }

    /**
     *  Appends a single byte onto the buffer. Behaves exactly as if a call to
     *  append(byte[]) with a byte[1] arg
     *  @modified 2005-23-03 jamoore this method now calls append(byte[])
     */
    public void append(byte b) {

        byte[] buff = new byte[1];

        buff[0] = b;

        append(buff);

    }

    public int size() {
        synchronized (buffer) {
            return length;
        }
    }

    /*
     * must be called in synchronized block
     */
    private void clear(int offset) {
        for (int i = offset; i < buffer.capacity(); i++) {
            buffer.put(i, (byte) 0);
        }
        buffer.position(offset); // reset position to offset index value

    }

    private void reset() {
        buffer.position(current);
    }

    public static String arrayToString(byte[] array) {
        String string = "";
        if (array.length <= 0) {
            return string;
        }
        for (int i = 0; i < array.length; i++) {
            string += (i == 0) ? "[" : ", ";
            string += (char) array[i];
        }
        string += "]";
        return string;
    }
}