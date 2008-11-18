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

import static avoir.realtime.audio.Constants.*;

public class ConnectionSettings {

    private int m_nPort;
    private int m_nConnectionType;
    private int m_nFormatCode;

    public ConnectionSettings(MasterModel masterModel) {
        setPort(DEFAULT_PORT);
        setFormatCode(DEFAULT_FORMAT_CODE);
        setConnectionType(DEFAULT_CONNECTION_TYPE);
    }

    public void setPort(int nPort) {
        m_nPort = nPort;
    }

    public int getPort() {
        return m_nPort;
    }

    public void setConnectionType(int nConnectionType) {
        m_nConnectionType = nConnectionType;
    }

    public int getConnectionType() {
        return m_nConnectionType;
    }

    public void setFormatCode(int nFormatCode) {
        m_nFormatCode = nFormatCode;
    }

    public int getFormatCode() {
        return m_nFormatCode;
    }
}
