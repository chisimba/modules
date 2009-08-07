<?php
/**
 * BrandMonday dbtable derived class
 *
 * Class to interact with the database for the brandmonday
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
 * @version   $Id: $
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

class dbbm extends dbTable {
    /**
     * Constructor
     *
     */
    public function init() {
        parent::init ( 'tbl_bmmentions' );
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
    }

    public function smartUpdate($resMinus, $resPlus, $resMentions) {
        // first the plus
        foreach($resPlus->results as $res) {
            if(!$this->tweetExists('tbl_bmplus', $res->id)) {
                $this->insert(array('tweet' => $res->text, 'createdat' => $res->created_at, 'from_user' => $res->from_user, 'tweetid' => $res->id, 'lang' => $res->iso_language_code, 'source' => $res->source, 'image' => $res->profile_image_url, 'location' => $res->location), 'tbl_bmplus');
            }
        }
        foreach($resMinus->results as $res) {
            if(!$this->tweetExists('tbl_bmminus', $res->id)) {
                $this->insert(array('tweet' => $res->text, 'createdat' => $res->created_at, 'from_user' => $res->from_user, 'tweetid' => $res->id, 'lang' => $res->iso_language_code, 'source' => $res->source, 'image' => $res->profile_image_url, 'location' => $res->location), 'tbl_bmminus');
            }
        }
        foreach($resMentions->results as $res) {
            if(!isset($res->to_user)) {
                $res->to_user = NULL;
            }
            if(!$this->tweetExists('tbl_bmmentions', $res->id)) {
                $this->insert(array('tweet' => $res->text, 'createdat' => $res->created_at, 'from_user' => $res->from_user, 'to_user' => $res->to_user, 'tweetid' => $res->id, 'lang' => $res->iso_language_code, 'source' => $res->source, 'image' => $res->profile_image_url, 'location' => $res->location), 'tbl_bmmentions');
            }
        }
    }

    public function getMsgRecordCount ($table) {
        parent::init($table);
        return $this->getRecordCount();
    }

    public function tweetExists ($table, $tweetid) {
        parent::init($table);
        $cnt = $this->getRecordCount("WHERE tweetid = '$tweetid'");
        if($cnt > 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }









    public function getRange($start, $num) {
        $range = $this->getAll ( "ORDER BY puid ASC LIMIT {$start}, {$num}" );
        return array_reverse($range);
    }


    
    public function getUserCount () {
        $users = $this->getArray("SELECT DISTINCT screen_name FROM tbl_twitterizer ORDER BY id");
        return count($users);
    }

    public function getAllPosts() {
        return $this->getAll("ORDER BY createdat ASC");
    }

    public function getSingle($id) {
        return $this->getAll("WHERE id = '$id'");
    }

    public function searcTable($keyword) {
        return $this->getAll("WHERE tweet LIKE '%%$keyword%%' OR screen_name LIKE '%%$keyword%%' OR name LIKE '%%$keyword%%' ");
    }

}
?>