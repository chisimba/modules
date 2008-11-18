/**
 * 	$Id: UserLevel.java,v 1.1 2007/03/05 15:36:29 adrian Exp $
 * 
 *  Copyright (C) GNU/GPL AVOIR 2007
 * 
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.common.user;

/**
 * Enumeration representing the various levels that a user may have in 
 * the system (maps directly to the groups defined in Chisimba).
 */
public class UserLevel {

    public static final int ADMIN = 0;
    public static final int LECTURER = 1;
    public static final int STUDENT = 2;
    public static final int GUEST = 3;

    public static int getUserLevel(String userLevel) {
       
        if (userLevel.equalsIgnoreCase("admin")) {
            return ADMIN;
        }
        if (userLevel.equalsIgnoreCase("lecturer")) {
            return LECTURER;
        }
        if (userLevel.equalsIgnoreCase("student")) {
            return STUDENT;
        }
        if (userLevel.equalsIgnoreCase("guest")) {
            return GUEST;
        }
        return -1;

    }
}
