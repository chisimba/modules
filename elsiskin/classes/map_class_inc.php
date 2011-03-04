<?php

/*
 *
 * A class to display the location of eLSI wits using the google maps embed code
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   elsiskin
 * @author    Nguni Phakela nonkululeko.phakela@wits.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: map_class_inc.php,v 1.1 2011-02-22 09:13:27 nguni52 Exp $
 * @link      http://avoir.uwc.ac.za
 *
 *
 *
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class map extends object {
    /*
     * Constructor
     */
    public function init() {}

    /*
     * This method is used to display the map showing the location of the offices
     * of eLSI, using the google map embed code
     * @access public
     * @return string $retstr The embeded google map
     */
    public function show() {
        $retstr = '<br><br><iframe
                        width="425"
                        height="350"
                        frameborder="0"
                        scrolling="no"
                        marginheight="0"
                        marginwidth="0"
                        src="';
        if(array_key_exists('HTTPS', $SERVER)) {
            $retstr .= 'https';
        }
        else {
            $retstr .= 'http';
        }

        $retstr .= '://maps.google.co.za/maps?q=11-17+jorissen+street&amp;hl=en&amp;ie=UTF8&amp;hq=&amp;hnear=17+Jorissen+St,+Johannesburg,+Gauteng+2000&amp;gl=za&amp;ll=-26.192797,28.033247&amp;spn=0.023105,0.025749&amp;z=14&amp;iwloc=A&amp;output=embed">
                    </iframe>
                    <br />
                    <medium>
                        <a
                            href="http://maps.google.co.za/maps?q=11-17+jorissen+street&amp;hl=en&amp;ie=UTF8&amp;hq=&amp;hnear=17+Jorissen+St,+Johannesburg,+Gauteng+2000&amp;gl=za&amp;ll=-26.192797,28.033247&amp;spn=0.023105,0.025749&amp;z=14&amp;iwloc=A&amp;source=embed"
                            style="color:#0000FF;text-align:left">
                            View Larger Map
                        </a>
                    </medium>';
        
        return $retstr;
    }
}