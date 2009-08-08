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
    public $objDbBm;
    public $objBmOps;

    public function init() {
        try {
            $this->teeny = $this->getObject ( 'tiny', 'tinyurl' );
            $this->objCurl = $this->getObject('curl', 'utilities');
            $this->objConfig = $this->getObject('altconfig', 'config');
            //Create an instance of the language object
            $this->objLanguage = $this->getObject ( 'language', 'language' );
            $this->objModules = $this->getObject ( 'modules', 'modulecatalogue' );
            $this->objViewer = $this->getObject('viewer');
            $this->objDbBm = $this->getObject('dbbm');
            $this->objBmOps = $this->getObject('bmops');
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
                $path = $this->objConfig->getSiteRootPath()."bmupdate";
                if(!file_exists($path)) {
                    touch($path);
                    chmod($path, 0777);
                }

                $lastupdate = file_get_contents($path);
                $minusurl = "http://search.twitter.com/search.json?q=&ands=BrandMinus&phrase=&ors=&nots=BrandPlus&lang=all&from=&to=&ref=&geocode=-33.55%2C18.22%2C500km&since_id=$lastupdate&rpp=100";
                $plusurl = "http://search.twitter.com/search.json?q=&ands=BrandPlus&phrase=&ors=&nots=BrandMinus&lang=all&from=&to=&ref=&geocode=-33.55%2C18.22%2C500km&since_id=$lastupdate&rpp=100"; //"http://search.twitter.com/search.json?q=%23BrandPlus+AND+%23BrandMonday&lang=all&geocode=-33.55%2C18.22%2C100km";
                $menurl = "http://search.twitter.com/search.json?q=%23BrandMonday&lang=all&geocode=-33.55%2C18.22%2C500km&since_id=$lastupdate";

                $resMinus = $this->objCurl->exec($minusurl);
                $resMinus = json_decode($resMinus);

                $resMentions = $this->objCurl->exec($menurl);
                $resMentions = json_decode($resMentions);

                $resPlus = $this->objCurl->exec($plusurl);
                $resPlus = json_decode($resPlus);

                if(is_object($resMinus) && $lastupdate <= $resMinus->since_id) {
                    // do "smart" update on db, so we only get the tweets that don't yet exist
                    $this->objDbBm->smartUpdate($resMinus, $resPlus, $resMentions);
                }
                
                if(file_exists($path)) {
                    unlink($path);
                    touch($path);
                    chmod($path, 0777);
                    if(is_object($resMinus)) {
                        file_put_contents($path, $resMinus->since_id);
                    }
                }
               
                $resMinus = $this->objDbBm->getRange('tbl_bmminus', 0, 100);
                $resPlus = $this->objDbBm->getRange('tbl_bmplus', 0, 100);
                $resMentions = $this->objDbBm->getRange('tbl_bmmentions', 0, 100);
               
                $this->setVarByRef('resMentions', $resMentions);
                $this->setVarByRef('resMinus', $resMinus);
                $this->setVarByRef('resPlus', $resPlus);

                return 'view_tpl.php';
                break;

            case 'happypeeps':
                echo $this->objBmOps->happyPeepsTagCloud();
                break;

            case 'sadpeeps':
                echo $this->objBmOps->sadPeepsTagCloud();
                break;

            case 'activepeeps':
                echo $this->objBmOps->activePeepsTagCloud();
                break;

            case 'bestserv':
                echo "best service";
                break;

            case 'worstserv':
                echo "worst service";
                break;

            case 'mentions':
                echo $this->objBmOps->mentionsTagCloud();
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