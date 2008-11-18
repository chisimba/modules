/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.classroom.whiteboard;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.classroom.whiteboard.item.Item;
import avoir.realtime.classroom.whiteboard.item.Oval;
import avoir.realtime.classroom.whiteboard.item.Pen;
import avoir.realtime.classroom.whiteboard.item.Rect;
import avoir.realtime.classroom.whiteboard.item.Txt;
import avoir.realtime.classroom.whiteboard.item.WBLine;
import avoir.realtime.common.Constants;

import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.RenderingHints;
import java.awt.font.TextAttribute;
import java.awt.font.TextLayout;
import java.awt.geom.Line2D;
import java.awt.image.BufferedImage;
import java.text.AttributedString;
import java.util.Vector;
import javax.swing.ImageIcon;

/**
 *
 * @author developer
 */
public class WhiteboardUtil {

    private ClassroomMainFrame mf;
    private WhiteboardSurface whiteBoardSurface;
    private int rect_size = 8;

    public WhiteboardUtil(ClassroomMainFrame mf, WhiteboardSurface whiteBoardSurface) {
        this.mf = mf;
        this.whiteBoardSurface = whiteBoardSurface;
    }

    public boolean isPointerInUser() {
        boolean state = false;
        if (whiteBoardSurface.getPointer() == Constants.HAND_LEFT ||
                whiteBoardSurface.getPointer() == Constants.HAND_RIGHT ||
                whiteBoardSurface.getPointer() == Constants.ARROW_SIDE ||
                whiteBoardSurface.getPointer() == Constants.ARROW_UP) {
            state = true;
        }
        return state;
    }

    /**
     * Resizes an image using a Graphics2D object backed by a BufferedImage.
     * @param srcImg - source image to scale
     * @param w - desired width
     * @param h - desired height
     * @return - the new resized image
     */
    public Image getScaledImage(Image srcImg) {
        int w = 60;
        int h = 40;
        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public Image getScaledImage(Image srcImg, int w, int h) {

        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    private void doActualItemPainting(
            Item temp,
            Graphics2D g2,
            Rectangle pointerSurface,
            Vector<ImageIcon> imgs,
            Rectangle currentSelectionArea,
            Item selectedItem,
            Vector<Item> selectedItems,
            BasicStroke dashed) {
        if (temp instanceof Rect) {
            Rect r = (Rect) temp;
            r.getBounds().x += (int) pointerSurface.x;
            r.getBounds().y += (int) pointerSurface.y;
            g2.setStroke(new BasicStroke(r.getStroke()));
            g2.setColor(r.getCol());

            if (r.isFilled()) {
                g2.fill(r.getRect());
            } else {
                g2.draw(r.getRect());
            }

        }


        if (temp instanceof WBLine) {
            WBLine l = (WBLine) temp;
            g2.setStroke(new BasicStroke(l.getStroke()));
            g2.setColor(l.getCol());
            Line2D line = l.getLine();

            g2.draw(line);
        }

        if (temp instanceof Pen) {
            Pen p = (Pen) temp;
            g2.setStroke(new BasicStroke(p.getStroke()));

            g2.setColor(p.getCol());
            Vector<WBLine> v = p.getPoints();
            for (int k = 0; k <
                    v.size(); k++) {
                WBLine l = v.elementAt(k);
                g2.drawLine(l.x1 + pointerSurface.x, l.y1 + pointerSurface.y, l.x2, l.y2);
            }

        }
        if (temp instanceof Oval) {
            Oval o = (Oval) temp;
            g2.setStroke(new BasicStroke(o.getStroke()));
            g2.setColor(o.getCol());
            if (o.isFilled()) {
                Rectangle r = o.getBounds();
                r.x += (int) pointerSurface.x;
                r.y += (int) pointerSurface.y;
                g2.fillOval(r.x, r.y, r.width, r.height);
            } else {
                Rectangle r = o.getRect();
                g2.drawOval(r.x, r.y, r.width, r.height);
            }

        }
        if (temp instanceof Img) {
            Img img = (Img) temp;
            Rectangle bounds = img.getBounds();

            if (imgs.size() > img.getImageIndex()) {
                g2.drawImage(imgs.elementAt(img.getImageIndex()).getImage(), bounds.x, bounds.y,
                        bounds.width, bounds.height, whiteBoardSurface);
            }
        }

        if (temp instanceof Txt) {
            Txt t = (Txt) temp;
            g2.setColor(t.getCol());

            String txt = t.getContent();
            Font font = t.getFont();
            boolean underLine = t.isUnderlined();
            Point point = t.getPoint();
            point.x += pointerSurface.x;
            point.y += pointerSurface.y;
            if (txt.length() > 0) {
                AttributedString as = new AttributedString(txt);
                as.addAttribute(TextAttribute.FONT, font);
                if (underLine) {
                    as.addAttribute(TextAttribute.UNDERLINE,
                            TextAttribute.UNDERLINE_ON);
                }

                TextLayout tl = new TextLayout(as.getIterator(), g2.getFontRenderContext());
                tl.draw(g2, point.x, point.y);
            }

        }



        if (currentSelectionArea.contains(temp.getBounds()) && !currentSelectionArea.isEmpty()) {
            /*
            if (selectedItem == null) {
            selectItem(temp, selectedItems);
            }
            g2.setColor(Color.RED);
            Rectangle r = temp.getBounds();
            r.x += (int) pointerSurface.x;
            r.y += (int) pointerSurface.y;
            g2.setStroke(dashed);
            g2.draw(temp.getBounds());
            g2.setStroke(new BasicStroke());
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
            rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
            rect_size);
             */
        }
        g2.setColor(Color.black);
    }

    public void selectItem(Item item, Vector<Item> selectedItems) {
        for (int i = 0; i < selectedItems.size(); i++) {
            if (selectedItems.elementAt(i).getId().equals(item.getId())) {
                return;
            }
        }

        selectedItems.add(item);

    }

    public void paintItems(
            Graphics2D g2,
            Vector<Item> items,
            BasicStroke dashed,
            boolean dragging,
            Item selectedItem,
            Rectangle pointerSurface,
            Vector<ImageIcon> imgs,
            Rectangle ovalRect1,
            Rectangle ovalRect2,
            Rectangle ovalRect3,
            Rectangle ovalRect4) {

        for (int i = 0; i <
                items.size(); i++) {
            Item temp = items.elementAt(i);
            if (dragging && selectedItem != null && temp != null) {
                if (!temp.getId().equals(selectedItem.getId())) {
                    this.doActualItemPainting(temp, g2, pointerSurface, imgs, pointerSurface, selectedItem, items, dashed);
                }
            } else {
                this.doActualItemPainting(temp, g2, pointerSurface, imgs, pointerSurface, selectedItem, items, dashed);
            }
        }

        g2.setStroke(dashed);
        g2.setColor(Color.red);

        //draw red rectange around selected item
        if (selectedItem instanceof Txt) {
            Txt txt = (Txt) selectedItem;
            Rectangle r = txt.getRect(g2);
            r.x += (int) pointerSurface.x;
            r.y += (int) pointerSurface.y;
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
        }

        if (selectedItem instanceof Pen) {
            Pen pen = (Pen) selectedItem;
            Rectangle r = getPenRect(pen.getPoints());
            r.x += (int) pointerSurface.x;
            r.y += (int) pointerSurface.y;
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
            g2.draw(r);
        }
        if (selectedItem instanceof Rect) {
            Rect rr = (Rect) selectedItem;
            Rectangle r = rr.getRect();
            r.x += (int) pointerSurface.x;
            r.y += (int) pointerSurface.y;
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
            g2.draw(r);
        }

        if (selectedItem instanceof Img) {
            // System.out.println("In paint: Red rect "+selectedItem);
            Img img = (Img) selectedItem;
            Rectangle r = img.getBounds();
            r.x += (int) pointerSurface.x;
            r.y += (int) pointerSurface.y;
            g2.fillRect(r.x, r.y, rect_size, rect_size);
            g2.fillRect((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fillRect((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fillRect(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
        }

        if (selectedItem instanceof Oval) {
            Oval oo = (Oval) selectedItem;
            Rectangle r = oo.getRect();
            r.x += (int) pointerSurface.x;
            r.y += (int) pointerSurface.y;
            ovalRect1 = new Rectangle(r.x, r.y, rect_size, rect_size);
            g2.fill(ovalRect1);
            ovalRect2 = new Rectangle((r.x + r.width) - rect_size, r.y, rect_size,
                    rect_size);
            g2.fill(ovalRect2);
            ovalRect3 = new Rectangle((r.x + r.width) - rect_size, (r.y + r.height) - rect_size, rect_size, rect_size);
            g2.fill(ovalRect3);
            ovalRect4 = new Rectangle(r.x, (r.y + r.height) - rect_size, rect_size,
                    rect_size);
            g2.fill(ovalRect4);
        }

        if (selectedItem instanceof WBLine) {

            WBLine line = (WBLine) selectedItem;
            line.x1 += pointerSurface.x;
            line.y1 += pointerSurface.y;
            g2.fillRect(line.x1 - (rect_size / 2), line.y1 - (rect_size / 2), rect_size, rect_size);
            g2.fillRect(line.x2 - (rect_size / 2), line.y2 - (rect_size / 2), rect_size, rect_size);

        }


    }

    private Rectangle getPenRect(Vector<WBLine> vector) {
        int minX = 0;
        int minY = 0;
        int maxX = 0;
        int maxY = 0;
        for (int i = 0; i < vector.size(); i++) {
            WBLine line = vector.elementAt(i);
            if (i == 0) {
                minX = line.x1;
                minY = line.y1;
            } else {
                if (line.x1 < minX) {
                    minX = line.x1;
                }
                if (line.y1 < minY) {
                    minY = line.y1;
                }
                if (line.x2 > maxX) {
                    maxX = line.x2;
                }
                if (line.y2 > maxY) {
                    maxY = line.y2;
                }
            }
        }
        return new Rectangle(minX, minY, maxX - minX, maxY - minY);
    }

    //paints the current itme
    public void paintCurrentItem(Graphics2D g2, Item selectedItem, Vector<ImageIcon> imgs) {
        Item temp = selectedItem;
        if (temp instanceof Rect) {
            Rect r = (Rect) temp;
            g2.setStroke(new BasicStroke(r.getStroke()));
            g2.setColor(r.getCol());

            if (r.isFilled()) {
                g2.fill(r.getRect());
            } else {
                g2.draw(r.getRect());
            }

        }
        if (temp instanceof WBLine) {
            WBLine l = (WBLine) temp;
            g2.setStroke(new BasicStroke(l.getStroke()));
            g2.setColor(l.getCol());
            Line2D line = l.getLine();
            g2.draw(line);
        }

        if (temp instanceof Pen) {
            Pen p = (Pen) temp;
            g2.setStroke(new BasicStroke(p.getStroke()));
            g2.setColor(p.getCol());
            Vector<WBLine> v = p.getPoints();
            for (int k = 0; k <
                    v.size(); k++) {
                WBLine l = v.elementAt(k);
                g2.drawLine(l.x1, l.y1, l.x2, l.y2);
            }

        }
        if (temp instanceof Oval) {
            Oval o = (Oval) temp;
            g2.setStroke(new BasicStroke(o.getStroke()));
            g2.setColor(o.getCol());
            if (o.isFilled()) {
                Rectangle r = o.getBounds();
                g2.fillOval(r.x, r.y, r.width, r.height);
            } else {
                Rectangle r = o.getRect();
                g2.drawOval(r.x, r.y, r.width, r.height);
            }

        }
        if (temp instanceof Img) {
            Img img = (Img) temp;
            Rectangle bounds = img.getBounds();
            if (imgs.size() > img.getImageIndex()) {

                g2.drawImage(imgs.elementAt(img.getImageIndex()).getImage(), bounds.x, bounds.y,
                        bounds.width, bounds.height, whiteBoardSurface);
            }
        }

        if (temp instanceof Txt) {
            Txt t = (Txt) temp;
            g2.setColor(t.getCol());

            String txt = t.getContent();
            Font font = t.getFont();
            boolean underLine = t.isUnderlined();
            Point point = t.getPoint();

            if (txt.length() > 0) {
                AttributedString as = new AttributedString(txt);
                as.addAttribute(TextAttribute.FONT, font);
                if (underLine) {
                    as.addAttribute(TextAttribute.UNDERLINE,
                            TextAttribute.UNDERLINE_ON);
                }

                TextLayout tl = new TextLayout(as.getIterator(), g2.getFontRenderContext());
                tl.draw(g2, point.x, point.y);
            }

        }


    }
}
