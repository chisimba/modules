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

import java.awt.Image;
import java.awt.geom.RoundRectangle2D;

public class RButton {

    private Image image;
    private String actionCommand;
    private String tooltiptext;
    private int width,  height,  x,  y;
    private RoundRectangle2D rect;
    private boolean clicked = false;
    private String type;
    private boolean aButtonGroup = true;
    private boolean toggleButton;

    public RButton(
            Image image,
            String actionCommand,
            String tooltiptext,
            int x,
            int y,
            int width,
            int height,
            RoundRectangle2D rect, String type) {
        this.image = image;
        this.actionCommand = actionCommand;
        this.tooltiptext = tooltiptext;
        this.width = width;
        this.height = height;
        this.rect = rect;
        this.x = x;
        this.y = y;
        this.type = type;
    }

    public boolean isToggleButton() {
        return toggleButton;
    }

    public void setToggleButton(boolean toggleButton) {
        this.toggleButton = toggleButton;
    }

    public boolean isAButtonGroup() {
        return aButtonGroup;
    }

    public void setAButtonGroup(boolean aButtonGroup) {
        this.aButtonGroup = aButtonGroup;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public boolean isClicked() {
        return clicked;
    }

    public void setClicked(boolean clicked) {
        this.clicked = clicked;
    }

    public RoundRectangle2D getRect() {
        return rect;
    }

    public void setRect(RoundRectangle2D rect) {
        this.rect = rect;
    }

    public int getX() {
        return x;
    }

    public void setX(int x) {
        this.x = x;
    }

    public int getY() {
        return y;
    }

    public void setY(int y) {
        this.y = y;
    }

    public String getActionCommand() {
        return actionCommand;
    }

    public void setActionCommand(String actionCommand) {
        this.actionCommand = actionCommand;
    }

    public String getTooltiptext() {
        return tooltiptext;
    }

    public void setTooltiptext(String tooltiptext) {
        this.tooltiptext = tooltiptext;
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public int getWidth() {
        return width;
    }

    public void setWidth(int width) {
        this.width = width;
    }

    public Image getImage() {
        return image;
    }

    public void setImage(Image image) {
        this.image = image;
    }
}
