<?php
/**
 * tribe controller class
 *
 * Class to control the tribe module
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
 * @category  chisimba
 * @package   brandmonday
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 */

class brandmonday extends controller {

    public $teeny;
    public $objLanguage;
    public $objModules;
    public $objTwitterLib;
    public $objCurl;
    public $objViewer;

    public function init() {
        try {
            $this->teeny = $this->getObject ( 'tiny', 'tinyurl' );
            $this->objCurl = $this->getObject('curl', 'utilities');
            //Create an instance of the language object
            $this->objLanguage = $this->getObject ( 'language', 'language' );
            $this->objModules = $this->getObject ( 'modules', 'modulecatalogue' );
            $this->objViewer = $this->getObject('viewer');
            if ($this->objModules->checkIfRegistered ( 'twitter' )) {
                // Get other places to upstream content to
                $this->objTwitterLib = $this->getObject ( 'twitterlib', 'twitter' );
            }
        } catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method to handle stuff
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {
            case 'main' :
                break;

            default: 
                $this->requiresLogin('default');
                $minusurl = "http://search.twitter.com/search.json?q=+%23BrandMinus&lang=all&geocode=-33.55%2C18.22%2C100km";
                $plusurl = "http://search.twitter.com/search.json?q=+%23BrandPlus&lang=all&geocode=-33.55%2C18.22%2C100km";
                $failurl = "http://search.twitter.com/search.json?q=+%23BrandFail&lang=all&geocode=-33.55%2C18.22%2C100km";

                $resMinus = $this->objCurl->exec($minusurl);
                $resMinus = json_decode($resMinus);
                
                $resFail = $this->objCurl->exec($failurl);
                $resFail = json_decode($resFail);

                $resPlus = $this->objCurl->exec($plusurl);
                $resPlus = json_decode($resPlus);

                $this->setVarByRef('resFail', $resFail);
                $this->setVarByRef('resMinus', $resMinus);
                $this->setVarByRef('resPlus', $resPlus);

                return 'view_tpl.php';
                break;
        }
    }

    /**
     * Overide the login object in the parent class
     *
     * @param  void
     * @return bool
     * @access public
     */
    public function requiresLogin($action) {
        return FALSE;
    }

}
?>