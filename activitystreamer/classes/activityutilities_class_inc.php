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
        $this->objDBContext = $this->getObject ( 'dbcontext', 'context' );
        $this->contextCode = $this->objDBContext->getContextCode ();
        $this->objUser = $this->getObject('user', 'security');
        $this->objContextChapters = $this->getObject('db_contextcontent_contextchapter','contextcontent');
        $this->objChapterContent = $this->getObject('db_contextcontent_chaptercontent','contextcontent');
        $this->objContentOrder = $this->getObject('db_contextcontent_order','contextcontent');
        $this->objContentPages = $this->getObject('db_contextcontent_pages','contextcontent');
        $this->objContextStreamer = $this->getObject('db_contextcontent_activitystreamer','contextcontent');
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
    /**
     * Method to fetch the latest context activities
     *
     * @return string
     */    
    public function jsonCourseActivies($start = 0, $limit=8)
    {

    	$start = (empty($start)) ? 0 : $start;
    	$limit = (empty($limit)) ? 25 : $limit;
     if(!empty($this->contextCode)){
    	 $all = $this->objContextStreamer->getArray("SELECT count( id ) as cnt FROM tbl_contextcontent_activitystreamer");
    	 $activities = $this->objContextStreamer->getAll("WHERE contextcode='".$this->contextCode."' ORDER BY datecreated DESC limit ".$start.", ".$limit);
    	 $contextChapters = $this->objContextChapters->getArray("SELECT chapterid FROM tbl_contextcontent_chaptercontext where contextcode='".$this->contextCode."'");
    	 $chapterIds = array();
    	 foreach($contextChapters as $contextChapter){
    	   $chapterIds[] = $contextChapter['chapterid'];
    	 }
    	 $pageOrder = $this->objContentOrder->getArray("SELECT id FROM tbl_contextcontent_order where contextcode='".$this->contextCode."'");
    	 $activityCount = $all[0]['cnt'];
    	 $cnt = 0;
    	 $str = '{"totalCount":"'.count($all).'","activities":[';
    	 if($activityCount > 0)
    	 {
    	 $activitiesArray = array();
    		 foreach($activities as $activity)
    		 {
        //Check if record is a chapter
        if(in_array($activity['contextitemid'],$chapterIds)){
         //Get the chapter title
         $chapterTitle = $this->objContextChapters->getContextChapterTitle($activity['contextitemid']);
         //Check if scorm
         $isChapterScorm = $this->objContextChapters->getArray("SELECT scorm FROM tbl_contextcontent_chaptercontext where chapterid='".$activity['contextitemid']."'");
    			  $arr = array();
    			  $arr['id'] = $activity['id'];
    			  $arr['title'] = $chapterTitle;
    			  $arr['contextcode'] = $activity['contextcode'];
    			  if($isChapterScorm[0]=='Y'){
         $chapterInfo = $this->objChapterContent->getChapterContent($activity['contextitemid']);
    			   $arr['contextpath'] = "module=contextcontent&action=viewscorm&folderId=".$chapterInfo['introduction']."&chapterid=".$activity['contextitemid'];
    			  }else{
    			   $arr['contextpath'] = "module=contextcontent&action=viewchapter&id=".$activity['contextitemid'];
    			  }
    			  $arr['createdby'] = htmlentities($this->objUser->fullname($activity['userid']));
    			  $arr['datecreated'] = $activity['datecreated'];
    		 	 $activitiesArray[] = $arr;
    		 	}else{
         //Get titleid
         $getPageTitleId = $this->objContentOrder->getArray('SELECT titleid FROM tbl_contextcontent_order WHERE id="'.$activity['contextitemid'].'"');
         //Get page title
         $pageTitle = $this->objContentPages->getArray("SELECT menutitle FROM tbl_contextcontent_pages WHERE titleid='".$getPageTitleId[0]['titleid']."'");
    			  $arr = array();
    			  $arr['id'] = $activity['id'];
    			  $arr['title'] = $pageTitle[0]['menutitle'];
    			  $arr['contextcode'] = $activity['contextcode'];
    			  $arr['contextpath'] = "module=contextcontent&action=viewpage&id=".$activity['contextitemid'];
    			  $arr['createdby'] = htmlentities($this->objUser->fullname($activity['userid']));
    			  $arr['datecreated'] = $activity['datecreated'];
    		 	 $activitiesArray[] = $arr;
    		 	}
    		 }
    	 }    	
    	 return json_encode(array('totalCount' => $activityCount, 'activities' =>  $activitiesArray));    	
    	}
    }
}
?>
