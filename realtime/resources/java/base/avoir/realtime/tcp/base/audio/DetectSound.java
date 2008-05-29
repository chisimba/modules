/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.audio;

import java.awt.*;
import javax.swing.*;
import javax.swing.event.*;

/**
 * <p>Class for detection of audio data (e.g. someone talking) in a byte array.
 * The data in the array must be PCM format (linear sampling).
 * The visual component for this class is a slider for setting the
 * detection threshold.</p>
 */
public class DetectSound extends JPanel {

    int wordSize = 8;
    int threshold = 20;
    int thresholdMax = 100;
    JSlider volumeSlider = new JSlider();
    JSlider thresholdSlider = new JSlider(0, thresholdMax, threshold);
    BorderLayout borderLayout1 = new BorderLayout();
    JProgressBar dataBar = new JProgressBar();

    /**
     * Initialize the sound detection panel.
     * @param sampleSize number of bits per audio sample
     */
    public DetectSound(int sampleSize) {
        wordSize = sampleSize;
        try {
            jbInit();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    /**
     * Initialize the visual component - JPanel containing JSlider.
     */
    private void jbInit() throws Exception {
        this.setLayout(borderLayout1);
        thresholdSlider.setToolTipText("Threshold of sampled sound data to capture");
        thresholdSlider.addChangeListener(new javax.swing.event.ChangeListener() {

            public void stateChanged(ChangeEvent e) {
                thresholdSlider_stateChanged(e);
            }
        });

        dataBar.setToolTipText("Data > threshold indication");
        dataBar.setMinimum(0);
        dataBar.setMaximum(200);
        this.setBorder(BorderFactory.createTitledBorder("Microphone Detection Threshold: 20"));
        this.setMaximumSize(new Dimension(300, 60));
        this.add(thresholdSlider, BorderLayout.CENTER);
        this.add(dataBar, BorderLayout.SOUTH);

    }

    /**
     * Set the sound data sample size.
     * @param sampleSize number of bits per audio sample
     */
    public void setSampleSize(int sampleSize) {
        wordSize = sampleSize;
        if (wordSize == 8) {
            thresholdMax = 100;
        } else {
            thresholdMax = 10000;
        }
    }

    /**
     * Set the threshold for detecting sound.
     * @param threshold value for detection algorithm
     */
    public void setThreshold(int threshold) {
        this.threshold = threshold;
    }

    /**
     * Determine if there are enough data points above the threshold to be
     * counted as valid sound data (e.g. someone talking).
     * @param soundbytes array of sampled audio data for test
     * @return true if the number of data points above the threshold is greater
     * than 10
     */
    public boolean isThereSound(byte[] soundbytes) {

        int i;
        int test;
        int max = 0;
        int nmax = 0;
        if (wordSize == 8) {
            for (i = 0; i < soundbytes.length; i++) {
                test = Math.abs(soundbytes[i]);
                if (test > max) {
                    max = test;
                }
                if (test > threshold) {
                    nmax++;
                }
            }
        } else {
            short[] data = new short[soundbytes.length / 2];
            int x = 0;
            for (i = 0; i < data.length; i++) {
                data[i] = (short) (((soundbytes[x] & 0xff) << 8) + (soundbytes[x + 1] & 0xff) & 0xffff);
                x += 2;
                test = Math.abs(data[i]);
                if (test > max) {
                    max = test;
                }
                if (test > threshold) {
                    nmax++;
                }
            }
        }
       
        dataBar.setValue(nmax / 10);
        if (max > threshold && nmax > 10) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Callback for when the user has chaned the detection threshold with
     * the visual component.
     * @param ChangeEvent event describing the slider's change in position
     */
    void thresholdSlider_stateChanged(ChangeEvent e) {
        threshold = (int) ((double) thresholdSlider.getValue() / 100.0 * thresholdMax);
        this.setBorder(BorderFactory.createTitledBorder(
                "Microphone Detection Threshold: " + String.valueOf(threshold)));
    }
}
