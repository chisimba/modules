<?php
/*
 *
 * A class to display the footer of wits in the elsi skin.
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
 * @version   CVS: $Id: witsfooter_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
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

class witsfooter extends object {

    // path to root folder of skin
    private $skinpath;
    // news stories object
    private $objNews;
    // stories for rotating banners at the top
    private $stories;

    /**
     * Constructor
     */
    public function init() {
        $this->objCategory = $this->getObject('dbnewscategories', 'news');
        $this->objNews = $this->getObject('dbnewsstories', 'news');
    }

    /**
     * Method to set the skin path
     * @return none
     * @access public
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /*
     * Method to display the footer of elsiskin
     * @return string $retstr which has the footer for the skin
     * @access public
     */
    public function show() {
        $retstr = '
            <div id="page-bottom"></div><!-- Page bottom -->
            <!-- Start of Footer -->
            <div id="footer">
                <div id="footer-left">
                    <h3>Wits University</h3><br />

                    Tel: +27 (0)11 717 1000<br /><br />

                    1 Jan Smuts Avenue<br />
                    Braamfontein 2000<br />
                    Johannesburg, South Africa
                </div>
                <div id="footer-centre">
                    <span class="leftcol">
                    <a href="http://web.wits.ac.za/Prospective/">Prospective Students</a><br />
                    <a href="http://web.wits.ac.za/Academic/AcademicInformation.htm">Faculties & Schools</a><br />
                    <a href="http://web.wits.ac.za/Students/">Current Students</a><br />
                    <a href="http://web.wits.ac.za/Library/">Research & Libraries</a><br />
                    <a href="http://web.wits.ac.za/PlacesOfInterest/">Visit our Campus</a><br />
                    <a href="http://web.wits.ac.za/NewsRoom/">News Centre</a><br />
                    <a href="http://web.wits.ac.za/Prospective/International/">International</a><br />
                    </span>

                    <span class="rightcol">
                    <a href="http://web.wits.ac.za/Academic/GeneralInfo/Almanac/2011.htm">Term Dates 2010</a><br />
                    <a href="http://hermes.wits.ac.za/Enterprise/">Wits Enterprise</a><br />
                    <a href="http://web.wits.ac.za/Students/Sport/">Sport</a><br />
                    <a href="http://web.wits.ac.za/NewsRoom/Vacancies.htm">Vacancies</a><br />
                    <a href="http://web.wits.ac.za/sitemap/">Sitemap</a><br />
                    </span>

                    <!-- To clear floats -->
                    <div id="clearfix"></div>
                </div>

                <div id="footer-right">
                    <div id="social-links">
                        <span class="share">Share
                            <img src="'.$this->skinpath.'images/stumbleupon_16.png" />
                            <img src="'.$this->skinpath.'images/delicious_16.png" />
                            <img src="'.$this->skinpath.'images/digg_16.png" />
                            <img src="'.$this->skinpath.'images/facebook_16.png" />
                            <img src="'.$this->skinpath.'images/linkedin_16.png" />
                            <img src="'.$this->skinpath.'images/twitter_16.png" />
                            <img src="'.$this->skinpath.'images/rss_16.png" />
                        </span>
                    </div>
                    <!-- To clear floats -->
                    <div id="clearfix"></div>
                    <p>Copyright © 2000-2010<br />
				University of the Witwatersrand, Johannesburg<br /><br />

                <a href="#">Privacy Policy</a>  |  <a href="http://web.wits.ac.za/Admin/Disclaimer.htm">Disclaimer</a>  |  <a href="#">Terms of Use</a></p>

                </div>
            </div>
            <!-- End of Footer -->';

        return $retstr;
    }
}