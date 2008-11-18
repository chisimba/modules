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

import javax.sound.sampled.*;
import static avoir.realtime.audio.Constants.*;

// base class for stream-based audio i/o
public abstract class AudioBase implements LineListener {

    protected AudioFormat lineFormat;
    protected AudioFormat netFormat;
    protected int formatCode = -1; // force initialization
    protected int bufferSizeMillis;
    protected int bufferSize;
    protected Mixer mixer;
    protected String title;
    protected DataLine line;    // current volume level: 0..128, or -1 for (none)
    protected int lastLevel = -1;
    protected boolean muted = false;
    protected boolean speakerStarted = false;
    protected boolean micStarted=false;

    protected AudioBase(String title, int formatCode, Mixer mixer, int bufferSizeMillis) {
        this.title = title;
        this.bufferSizeMillis = bufferSizeMillis;
        this.mixer = mixer;
        try {
            setFormatCode(formatCode);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void update(LineEvent event) {
        if (DEBUG) {
            if (event.getType().equals(LineEvent.Type.STOP)) {
                out(title + ": Stop");
            } else if (event.getType().equals(LineEvent.Type.START)) {
                out(title + ": Start");
            } else if (event.getType().equals(LineEvent.Type.OPEN)) {
                out(title + ": Open");
            } else if (event.getType().equals(LineEvent.Type.CLOSE)) {
                out(title + ": Close");
            }
        }
    }

    // opens the sound hardware
    public void open() throws Exception {
        closeLine(false);
        destroyLine();
        createLine();
        openLine();
    }

    protected abstract void createLineImpl() throws Exception;

    private void createLine() throws Exception {
        try {
            line = null;
            createLineImpl();
            line.addLineListener(this);
            if (DEBUG) {
                out("Got line for " + title + ": " + line.getClass());
            }
        } catch (LineUnavailableException ex) {
            throw new Exception("Unable to open " + title + ": " + ex.getMessage());
        }
    }

    protected abstract void openLineImpl() throws Exception;

    private void openLine() throws Exception {
        try {
            speakerStarted = false;
            // align to frame size
            bufferSize = (int) AudioUtils.millis2bytes(bufferSizeMillis, lineFormat);
            bufferSize -= bufferSize % lineFormat.getFrameSize();
            openLineImpl();
            if (DEBUG) {
                out(title + ": opened line");
            }
            bufferSize = line.getBufferSize();
            if (VERBOSE) {
                out(title + ": buffersize=" + bufferSize + " bytes.");
            }
            speakerStarted = true;
        } catch (LineUnavailableException ex) {
            speakerStarted = false;
            throw new Exception("Unable to open " + title + ": " + ex.getMessage());
        }
    }

    public void start() throws Exception {
        if (line == null) {
            if (DEBUG) {
                out(title + ": Call to start(), but line not created!");
            }
            throw new Exception(title + ": cannot start");
        }
        line.flush();
        line.start();
        if (DEBUG) {
            out(title + ": started line");
        }
    }

    public void close() {
        close(false);
    }

    public boolean isMicStarted() {
        return micStarted;
    }

    public boolean isSpeakerStarted() {
        return speakerStarted;
    }

    public void close(boolean willReopen) {
        closeLine(willReopen);
        destroyLine();
    }

    protected void closeLine(boolean willReopen) {
        if (!willReopen) {
            lastLevel = -1;
        }
        if (line != null) {
            line.flush();
            line.stop();
            line.close();
            if (DEBUG && title != null) {
                out(title + ": line closed.");
            }
        }
    }

    private void destroyLine() {
        if (line != null) {
            line.removeLineListener(this);
        }
        line = null;
    }

    public boolean isStarted() {
        return (line != null) && (line.isActive());
    }

    public boolean isOpen() {
        return (line != null) && (line.isOpen());
    }

    public int getBufferSize() {
        return bufferSize;
    }

    public int getBufferSizeMillis() {
        return bufferSizeMillis;
    }

    public void setBufferSizeMillis(int bufferSizeMillis) throws Exception {
        if (this.bufferSizeMillis == bufferSizeMillis) {
            return;
        }
        boolean wasOpen = isOpen();
        boolean wasStarted = isStarted();
        closeLine(true);

        this.bufferSizeMillis = bufferSizeMillis;

        if (wasOpen) {
            openLine();
            if (wasStarted) {
                start();
            }
        }
    }

    public int getFormatCode() {
        return formatCode;
    }

    public void setFormatCode(int formatCode) throws Exception {
        if (this.formatCode == formatCode) {
            return;
        }
        boolean wasOpen = isOpen();
        if (wasOpen) {
            throw new Exception("cannot change format while open");
        }
        this.lineFormat = AudioUtils.getLineAudioFormat(formatCode);
        this.netFormat = AudioUtils.getNetAudioFormat(formatCode);
    }

    public void setMixer(Mixer mixer) throws Exception {
        if (this.mixer == mixer) {
            return;
        }
        boolean wasOpen = isOpen();
        boolean wasStarted = isStarted();
        close(true);

        this.mixer = mixer;

        if (wasOpen) {
            createLine();
            openLine();
            if (wasStarted) {
                start();
            }
        }
    }

    public void setMuted(boolean muted) {
        this.muted = muted;
    }

    public boolean isMuted() {
        return this.muted;
    }

    public int getLevel() {
        return lastLevel;
    }

    // find the current playback level: the maximum value of this buffer
    protected void calcCurrVol(byte[] b, int off, int len) {
        int end = off + len;
        int sampleSize = (lineFormat.getSampleSizeInBits() + 7) / 8;
        int max = 0;
        if (sampleSize == 1) {
            // 8-bit
            for (; off < end; off++) {
                int sample = (byte) (b[off] + 128);
                if (sample < 0) {
                    sample = -sample;
                }
                if (sample > max) {
                    max = sample;
                }
            }
            lastLevel = max;
        } else if (sampleSize == 2) {
            if (lineFormat.isBigEndian()) {
                // 16-bit big endian
                for (; off < end; off += 2) {
                    int sample = (short) ((b[off] << 8) | (b[off + 1] & 0xFF));
                    if (sample < 0) {
                        sample = -sample;
                    }
                    if (sample > max) {
                        max = sample;
                    }
                }
            } else {
                // 16-bit little endian
                for (; off < end; off += 2) {
                    int sample = (short) ((b[off + 1] << 8) | (b[off] & 0xFF));
                    if (sample < 0) {
                        sample = -sample;
                    }
                    if (sample > max) {
                        max = sample;
                    }
                }
            }
            //System.out.print("max="+max+" ");
            lastLevel = max >> 8;
        //System.out.print(":"+len+":");
        //System.out.print("[lL="+lastLevel+" "+getClass().toString()+"]");
        } else {
            lastLevel = -1;
        }
    }

    // find the current playback level: the maximum value of this buffer
    protected void muteBuffer(byte[] b, int off, int len) {
        int end = off + len;
        int sampleSize = (lineFormat.getSampleSizeInBits() + 7) / 8;
        byte filler = 0;
        if (sampleSize == 1) {
            filler = -128;
        }
        for (; off < end; off++) {
            b[off] = filler;
        }
    }
}
