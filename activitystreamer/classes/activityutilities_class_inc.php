<?php

/**
 * activityutilities
 *
 * ActivityStreamer activityutilities
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
 * @package   activitystreamer
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2009 Paul Mungai
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */
/* ----------- data class extends dbTable for tbl_activity------------*/
// security check - must be included in all scripts
if (! /**
 * Description for $GLOBALS
 * @global string $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}

/**
 * Utilities
 *
 * ActivityStreamer activityutilities
 *
 * @category  Chisimba
 * @package   activitystreamer
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2009 Paul Mungai
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */
class activityutilities extends object {

    /**
     * @var object $objDBActivity
     */
    public $objDBActivity;

    /**
     * @var object $objConfig
     */
    public $objConfig;

    /**
     * @var object $objDBContext
     */
    public $contextCode;

    /**
     * Constructor method to define the table
     */
    public function init() {
        $this->objDBActivity = $this->getObject ( 'activitydb', 'activitystreamer' );
        $this->objConfig = $this->getObject ( 'config', 'config' );
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        //$this->contextCode = $this->objDBContext->getContextCode ();
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     * Method to get the json activity list
     *
     * @return string
     */    
    public function jsonListActivity($start = 0, $limit=8)
    {
    	$start = (empty($start)) ? 0 : $start;
    	$limit = (empty($limit)) ? 25 : $limit;

    	$all = $this->objDBActivity->getArray("SELECT count( id ) as cnt FROM tbl_activity");
					//$limit = count($all);
    	$activities = $this->objDBActivity->getAll("ORDER BY createdon DESC limit ".$start.", ".$limit);    	
    	$activityCount = $all[0]['cnt'];
    	$cnt = 0;
    	$str = '{"totalCount":"'.count($all).'","activities":[';
    	if($activityCount > 0)
    	{
    	$activitiesArray = array();
    		foreach($activities as $activity)
    		{
    			$arr = array();
    			$arr['id'] = $activity['id'];
    			$arr['title'] = $activity['title'];
    			$arr['description'] = $activity['description'];
    			$arr['contextcode'] = $activity['contextcode'];
    			$arr['createdby'] = htmlentities($this->objUser->fullname($activity['createdby']));
    			$arr['createdon'] = $activity['createdon'];
    			$activitiesArray[] = $arr;
    		}
    	}    	
    	return json_encode(array('totalCount' => $activityCount, 'activities' =>  $activitiesArray));    	
    }
}
?>
