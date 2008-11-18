/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.audio;

import java.io.*;
import javax.sound.sampled.*;
import static avoir.realtime.audio.Constants.*;

// Class that reads its audio from an AudioInputStream
public class AudioPlayback extends AudioBase {

    private static final boolean DEBUG_TRANSPORT = false;
    protected AudioInputStream ais;
    private PlayThread thread;

    public AudioPlayback(int formatCode, Mixer mixer, int bufferSizeMillis) {
        super("Speaker", formatCode, mixer, bufferSizeMillis);
    }

    protected void createLineImpl() throws Exception {
        DataLine.Info info = new DataLine.Info(SourceDataLine.class, lineFormat);

        // get the playback data line for capture.
        if (mixer != null) {
            line = (SourceDataLine) mixer.getLine(info);
        } else {
            line = AudioSystem.getSourceDataLine(lineFormat);
        }
    }

    protected void openLineImpl() throws Exception {
        SourceDataLine sdl = (SourceDataLine) line;
        sdl.open(lineFormat, bufferSize);
    }

    public synchronized void start() throws Exception {
        boolean needStartThread = false;
        if (thread != null && thread.isTerminating()) {
            thread.terminate();
            needStartThread = true;
        }
        if (thread == null || needStartThread) {
            // start thread
            thread = new PlayThread();
            thread.start();
        }
        super.start();
    }

    protected void closeLine(boolean willReopen) {
        PlayThread oldThread = null;
        synchronized (this) {
            if (!willReopen && thread != null) {
                thread.terminate();
            }
            super.closeLine(willReopen);
            if (!willReopen && thread != null) {
                oldThread = thread;
                thread = null;
            }
        }
        if (oldThread != null) {
            if (VERBOSE) {
                out("AudioPlayback.closeLine(): closing thread, waiting for it to die");
            }
            oldThread.waitFor();
            if (VERBOSE) {
                out("AudioPlayback.closeLine(): thread closed");
            }
        }
    }

    // in network format
    public void setAudioInputStream(AudioInputStream ais) {
        this.ais = AudioSystem.getAudioInputStream(lineFormat, ais);
    }

    class PlayThread extends Thread {

        private boolean doTerminate = false;
        private boolean terminated = false;
        // for debugging
        private boolean printedBytes = false;

        public void run() {
            if (VERBOSE) {
                out("Start AudioPlayback pull thread");
            }
            byte[] buffer = new byte[getBufferSize()];
            try {
                SourceDataLine sdl = (SourceDataLine) line;
                while (!doTerminate) {

                    if (ais != null) {
                        int r = ais.read(buffer, 0, buffer.length);
                        if (r > 50 && DEBUG_TRANSPORT && !printedBytes) {
                            printedBytes = true;
                            out("AudioPlayback: first bytes being played:");
                            String s = "";
                            for (int i = 0; i < 50; i++) {
                                s += " " + buffer[i];
                            }
                            out(s);
                        }
                        if (r > 0) {
                            if (isMuted()) {
                                muteBuffer(buffer, 0, r);
                            }
                            // run some simple analysis
                            calcCurrVol(buffer, 0, r);
                            if (sdl != null) {

                                sdl.write(buffer, 0, r);
                            }
                        } else {
                            if (r == 0) {
                                synchronized (this) {
                                    this.wait(40);
                                }
                            }
                        }
                    } else {
                        synchronized (this) {
                            this.wait(50);
                        }
                    }
                }
            } catch (IOException ioe) {
                //if (DEBUG) ioe.printStackTrace();
            } catch (InterruptedException ie) {
                if (DEBUG) {
                    ie.printStackTrace();
                }
            }
            if (VERBOSE) {
                out("Stop AudioPlayback pull thread");
            }
            terminated = true;
        }

        public synchronized void terminate() {
            doTerminate = true;
            this.notifyAll();
        }

        public synchronized boolean isTerminating() {
            return doTerminate || terminated;
        }

        public synchronized void waitFor() {
            if (!terminated) {
                try {
                    this.join();
                } catch (InterruptedException ie) {
                    if (DEBUG) {
                        ie.printStackTrace();
                    }
                }
            }
        }
    }
}

