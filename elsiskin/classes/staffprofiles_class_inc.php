<?php
/*
 *
 * A class to display the staff profiles of elsi.
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
 * @version   CVS: $Id: stafprofiles_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
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

class staffprofiles extends object {
    // base skin path
    private $skinpath;

    public function init() {}

    /**
     * Method to set the skin path
     * @return none
     * @access public
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /*
     * Metho to display the staff profiles before they are dynamically added to the
     * database later.
     * @param none
     * @access public
     * @return string $retstr Contains all the staff profiles information already formatted.
     */
    public function show() {

        $profiles = array(
            array("href"=>"16", "name"=>"Zaahirah Bhamjee", "image"=>"zaahirahbhamjee.jpg", "jobtitle"=>"Assistant Instructional Designer","ext"=>"77164", "email"=>"zaahirahbhamjee@gmail.com"),
            array("href"=>"11", "name"=>"Agnes Chigona", "image"=>"AgnesChigona.jpg", "jobtitle"=>"Research Fellow", "ext"=>"7181","email"=>"agnes.chigona@wits.ac.za"),
            array("href"=>"6", "name"=>"Shakira Choonara", "image"=>"schoonara.jpg", "jobtitle"=>"Office Administrator", "ext"=>"7161", "email"=>"shakira.choonara2@wits.ac.za"),
            array("href"=>"2", "name"=>"Rabelani Dagada", "image"=>"RDagada.jpg", "jobtitle"=>"Head: eLearning Support and Innovation Unit", "ext"=>"7162", "email"=>"rabelani.dagada@wits.ac.za"),
            array("href"=>"17", "name"=>"Shailin Govender", "image"=>"shailingovender.png", "jobtitle"=>"Systems Analyst","ext"=>"77181", "email"=>"Shailin.Govender@wits.ac.za"),
            array("href"=>"1", "name"=>"Taurai Hungwe", "image"=>"THungwe.jpg", "jobtitle"=>"Instructional Designer","ext"=>"7164", "email"=>"taurai.hungwe@wits.ac.za"),
            array("href"=>"15", "name"=>"Noxolo Mbana ", "image"=>"noxolombana.jpg", "jobtitle"=>"Researcher","ext"=>"7164", "email"=>"noxolo.mbana@wits.ac.za"),
            array("href"=>"14", "name"=>"Reginald Moledi", "image"=>"regimoledi.jpg", "jobtitle"=>"Instructional Developer","ext"=>"7170", "email"=>"reginald.moledi@wits.ac.za"),
            array("href"=>"4", "name"=>"Derek Moore", "image"=>"dmoore.jpg", "jobtitle"=>"Content Developer","ext"=>"77171", "email"=>"derek.moore@wits.ac.za"),
            array("href"=>"13", "name"=>"Paul Mungai", "image"=>"PaulMungai.jpg", "jobtitle"=>"Software Developer","ext"=>"77166", "email"=>"paul.mungai@wits.ac.za"),
            array("href"=>"10", "name"=>"Tessa Murray", "image"=>"tessa_murry.jpg", "jobtitle"=>"Team leader: Content developer","ext"=>"77178", "email"=>"tessa.murray@wits.ac.za"),
            array("href"=>"12", "name"=>"Neo Petlele", "image"=>"Neo.Petlele.jpg", "jobtitle"=>"Research Assistant","ext"=>"77176", "email"=>"neo.petlele@wits.ac.za"),
            array("href"=>"3", "name"=>"Nkululeko (Nguni) Phakela", "image"=>"NkululekoPhakela.jpg", "jobtitle"=>"Software Developer","ext"=>"77184", "email"=>"nonkululeko.phakela@wits.ac.za"),
            array("href"=>"7", "name"=>"Fatima Rahiman", "image"=>"frahiman.jpg", "jobtitle"=>"Team leader: Instructional designer","ext"=>"77174", "email"=>"fatima.rahiman@wits.ac.za"),
            array("href"=>"5", "name"=>"James Smurthwaite", "image"=>"Profile_Pic_James.gif", "jobtitle"=>"Content Developer","ext"=>"77196", "email"=>"James.Smurthwaite@wits.ac.za"),
            array("href"=>"8", "name"=>"Ofentse Tabane", "image"=>"otabane.jpg", "jobtitle"=>"Instructional designer","ext"=>"77172", "email"=>"ofentse.tabane@wits.ac.za"),
            array("href"=>"9", "name"=>"David Wafula", "image"=>"DavidWafula.jpg", "jobtitle"=>"Team Leader Software Development","ext"=>"77180", "email"=>"david.wafula@wits.ac.za")
        );

        $retstr = '<ul class="business_cards">';
                        foreach($profiles as $row) {
                            $retstr .= '
                        <li>
                            <a name="modal" href="#dialog'.$row['href'].'">
                            <h3 class="name">'.$row['name'].'</h3>
                            <img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/staff/'.$row['image'].'">
                            <p class="jobtitle">'.$row['jobtitle'].'</p>
                            <span class="phone">+ 27 11 717 '.$row['ext'].'</span><br>
                            <span class="email">'.$row['email'].'</span><br>
                            </a>
                        </li>';
                        }
        $retstr .= '</ul';
            
        return $retstr;
    }
}

?>