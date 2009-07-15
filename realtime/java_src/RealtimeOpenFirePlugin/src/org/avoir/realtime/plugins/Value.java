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
package org.avoir.realtime.plugins;

import java.io.Serializable;

/**
 *
 * @author developer
 */
public class Value implements Serializable {

    private String option;
    private String value;
    private boolean correctAnswer;
    private boolean selectedByStudentAsAnswer;


    public Value(String option, String value, boolean correctAnswer) {
        this.option = option;
        this.value = value;
        this.correctAnswer=correctAnswer;
    }

    public boolean isCorrectAnswer() {
        return correctAnswer;
    }

    public void setCorrectAnswer(boolean correctAnswer) {
        this.correctAnswer = correctAnswer;
    }

    public boolean isSelectedByStudentAsAnswer() {
        return selectedByStudentAsAnswer;
    }

    public void setSelectedByStudentAsAnswer(boolean selectedByStudentAsAnswer) {
        this.selectedByStudentAsAnswer = selectedByStudentAsAnswer;
    }

    public String getOption() {
        return option;
    }

    public void setOption(String option) {
        this.option = option;
    }

    public String getValue() {
        return value;
    }

    public void setValue(String value) {
        this.value = value;
    }
}
