<?php
/**
 *
 *  Twitterizer operations class
 *
 * PHP version 5.1.0+
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
 * @package   Twitterizer
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (! /**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 *
 * Twitterizer operations class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package twitterizer
 *
 */
class tweetops extends object {

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;
    public $objConfig;
    public $objSysConfig;

    /**
     * Constructor
     *
     * @access public
     *
     */
    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
    }

    /**
     *
     */
    public function createTrackFile() {
        $terms = $this->objSysConfig->getValue('trackterms', 'twitterizer');
        $seed = "track=";
        $seed = $seed.$terms;
        $filename = $this->objConfig->getSiteRootPath()."tracking";
        file_put_contents($filename, $seed);

        return TRUE;
    }

    /**
     * go persistent connection to twitter API method 
     */
     public function getData() {
         $newTime = null;
         $this->objDbTweets = $this->getObject('dbtweets');
         $params = $this->objSysConfig->getValue('trackterms', 'twitterizer');
         $user = $this->objSysConfig->getValue('twitteruser', 'twitterizer');
         $pass = $this->objSysConfig->getValue('twitterpass', 'twitterizer');
         $terms = urlencode($params);
         $fp = fopen("http://".$user.":".$pass."@stream.twitter.com/track.json?track=$terms","r");  
         while($data = fgets($fp))  
         {  
             $time = date("YmdHi");
             $file = $this->objConfig->getSiteRootPath().$time;  
             if ($newTime!=$time)  
             {  
                 @fclose($fp2);
                 chdir($file);
                 foreach(glob("*.txt") as $prev) { 
                     $records = file($prev);
                     foreach($records as $record) {
                         $record = json_decode($record);
                         // parse out the important bits
                         $text = $record->text;
                         $created = $record->created_at;
                         $userdata = $record->user;
                         $screen_name = $userdata->screen_name;
                         $name = $userdata->name;
                         $image = $userdata->profile_image_url;
                         $location = $userdata->location;
                         // make an array for db record insert
                         $insarr = array('tweet' => $text, 'createdat' => strtotime($created), 'screen_name' => $screen_name, 'name' => $name, 'image' => $image, 'location' => $location);
                         $this->objDbTweets->addRec($insarr);
                     }
                     unlink($prev);
                 }
                 $fp2 = fopen("{$file}.txt","a");
             }
             fputs($fp2,$data);  
             $newTime = $time; //date("YmdHi");
          }
     }

    public function renderOutputForBrowser($msgs) {
    }

    public function renderTopBoxen($userid = NULL) {
    }
    
    public function renderLeftBoxen($userid = NULL) {
    }

    public function renderRightBoxen($userid = NULL) {
    }

}
?>