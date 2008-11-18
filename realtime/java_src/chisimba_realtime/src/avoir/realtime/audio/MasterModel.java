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

/** Holds pointers to all other model classes.
 */
public class MasterModel {

    private ConnectionSettings m_connectionSettings;
    private AudioSettings m_audioSettings;
    private AudioManager audioManager;
    private String mode;

    public MasterModel(String mode) {
        this.mode = mode;
        m_connectionSettings = new ConnectionSettings(this);
        m_audioSettings = new AudioSettings(this);
        audioManager = new AudioManager(this);
    }

    public String getMode() {
        return mode;
    }

    public AudioManager getAudioManager() {
        return audioManager;
    }

    public ConnectionSettings getConnectionSettings() {
        return m_connectionSettings;
    }

    public AudioSettings getAudioSettings() {
        return m_audioSettings;
    }
}
/*** MasterModel.java ***/
