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
package avoir.realtime.classroom;

import java.awt.*;
import java.awt.image.*;
import java.util.*;

public class BlurShape {

    ConvolveOp op;
    int radius;
    static RenderingHints HINTS;


    static {
        // Define static rendering hints
        HashMap hintMap = new HashMap();
        hintMap.put(RenderingHints.KEY_ALPHA_INTERPOLATION,
                RenderingHints.VALUE_ALPHA_INTERPOLATION_QUALITY);
        hintMap.put(RenderingHints.KEY_ANTIALIASING,
                RenderingHints.VALUE_ANTIALIAS_ON);
        hintMap.put(RenderingHints.KEY_RENDERING,
                RenderingHints.VALUE_RENDER_QUALITY);
        hintMap.put(RenderingHints.KEY_FRACTIONALMETRICS,
                RenderingHints.VALUE_FRACTIONALMETRICS_ON);
        HINTS = new RenderingHints(hintMap);
    }

    public BlurShape(int radius) {
        // Constructor defining convolution kernel for low-pass filter.
        // Depending on the application, a Gaussian filter may work better.
        if (radius < 1) {
            radius = 1;
        }
        int n = radius * 2 + 1;
        int m = n * n;
        float[] v = new float[m];
        float a = 1f / m;  // simple averaging kernel
        for (int j = 0; j < m; j++) {
            v[j] = a;
        }
        Kernel kernel = new Kernel(n, n, v);
        op = new ConvolveOp(kernel, ConvolveOp.EDGE_ZERO_FILL, HINTS);
        this.radius = radius;
    }

    public void draw(Graphics2D g, Shape s) {
        // Draw unfilled shape.
        // Use stroked shape so accurate bounds can be obtained
        Stroke stroke = g.getStroke();
        Shape newShape = stroke.createStrokedShape(s);
        fill(g, newShape);
    }

    public void fill(Graphics2D g, Shape s) {
        // Draw filled shape
        Rectangle bounds = s.getBounds();
        int offset = radius + 2;  // expansion for convolution at edge
        int w = bounds.width + 2 * offset;  // size of temp image
        int h = bounds.height + 2 * offset;
        int x = -bounds.x + offset;  // offset of shape within image
        int y = -bounds.y + offset;
        BufferedImage im = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D gi = im.createGraphics();
        gi.setRenderingHints(HINTS);
        gi.translate(x, y);  // offset to draw shape fully within image
        gi.fill(s);  // draw shape
        gi.dispose();
        im = op.filter(im, null);  // apply blur filter
        g.drawImage(im, -x, -y, null);  // draw final result
    }
}

