<?php

/**
 * Class the records the pages a user has visited.
 *
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
 * @package   contextcontent
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright @2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: db_contextcontent_titles_class_inc.php 11385 2008-11-07 00:52:41Z charlvn $
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class the records the pages a user has visited.
 *
 * It doesn't contain the content of pages, just the index to track which pages
 * are translations of each other.
 *
 * @category  Chisimba
 * @package   contextcontent
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright @2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */

class db_learningcontent_activitystreamer extends dbtable
{

    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_learningcontent_activitystreamer');
        $this->objUser =& $this->getObject('user', 'security');
        //Load content pages
        $this->objContentTitles = $this->getObject('db_learningcontent_titles');
        $this->objContentChapter = $this->getObject('db_learningcontent_contextchapter');
        $this->objContentPages = $this->getObject('db_learningcontent_pages');
        $this->objFiles = $this->getObject('dbfile','filemanager');        
    }
    
    /**
     * Method to add a record.
     *
     * @access public
     * @param string $userId User ID
     * @param string $sessionid session ID
     * @param string $contextItemId context Item ID
     * @param string $contextCode context Code
     * @param string $moduleCode module Code
     * @param string $datecreated date created
     * @param string $pageorchapter record whether its a context page or chapter
     * @param string $description description of activity
     * @param string $sessionstarttime session start time
     * @param string $sessionendtime session end time
     */
   public function addRecord($userId, $sessionid, $contextItemId, $contextCode, $modulecode, $datecreated,$pageorchapter=NULL, $description=NULL, $sessionstarttime=NULL, $sessionendtime=NULL)
    {
        $row = array();
        $row['userid'] = $userId;
        $row['contextcode'] = $contextCode;
        $row['contextitemid'] = $contextItemId;
        $row['datecreated'] = strftime('%Y-%m-%d %H:%M:%S', mktime());
        $row['sessionid'] = $sessionid;
        $row['modulecode'] = $modulecode;
        $row['pageorchapter'] = $pageorchapter;
        $row['description'] = $description;
        $row['starttime'] = $sessionstarttime;
        $row['endtime'] = $sessionendtime;

        return $this->insert($row);
    }

    /**
     * Checks if record exists.
     *
     * @access public
     * @param string $id The activitystreamer id.
     * @return boolean
     */
    public function idExists($id)
    {
        return $this->valueExists('id', $id);
    }
    /**
     * Method to check if record exists according to userId, contextItemId and sessionid.
     *
     * @access public
     * @param string $userId User ID
     * @param string $contextItemId Context Item Id
     * @param string $contextCode Context Code
     * @return TRUE
     */
    public function getRecord($userId, $contextItemId, $sessionid)
    {
        $where = "WHERE userid = '$userId' AND contextitemid = '$contextItemId' AND sessionid = '$sessionid' AND endtime = NULL";
        $results = $this->getAll($where);
        if (isset($results[0]['id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**
     * Method to check if record exists according to userId, contextItemId and sessionid.
     *
     * @access public
     * @param string $userId User ID
     * @param string $contextItemId Context Item Id
     * @param string $contextCode Context Code
     * @return TRUE
     */
    public function checkRecord($userId, $contextItemId, $contextCode)
    {
        $where = "WHERE userid = '$userId' AND contextitemid = '$contextItemId' AND contextcode = '$contextCode'";
        $results = $this->getAll($where);
        if (isset($results[0]['id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $start The start date
     * @param string $longdescription The long description
     * -- @param string $userId The user ID
     */
    function updateSingle($id) 
    {
        $userid = $this->objUser->userId();
        $this->update("id", $id, array(
            'endtime' => date('Y-m-d H:i:s')
        ));
    }

    /**
     * Method to retrieve a record id according to userId, contextItemId and sessionid.
     *
     * @access public
     * @param string $userId User ID
     * @param string $contextItemId Context Item Id
     * @param string $contextCode Context Code
     * @return string Record ID
     */
    public function getRecordId($userId, $contextItemId, $sessionid)
    {
        $where = "WHERE userid = '$userId' AND contextitemid = '$contextItemId' AND sessionid = '$sessionid' AND endtime is NULL";
        $results = $this->getAll($where);
        if(!empty($results)){
          if(empty($results[0]['endtime']))
            return $results[0]['id'];
          else 
            return FALSE;
        } else {
          return FALSE;
        }
    }
    /**
    * Method to get a content page
    * @param string $pageId Record Id of the Page
    * @param string $contextCode Context the Page is In
    * @return array Details of the Page, FALSE if does not exist
    * @access public
    */
    public function getPage($pageId, $contextCode)
    {
        $sql = 'SELECT tbl_learningcontent_order.id, tbl_learningcontent_order.chapterid, tbl_learningcontent_order.parentid,tbl_learningcontent_pages.scorm, tbl_learningcontent_pages.menutitle, pagecontent, headerscripts, pagepicture, pageformula, lft, rght, tbl_learningcontent_pages.id as pageid, tbl_learningcontent_order.titleid, isbookmarked
        FROM tbl_learningcontent_order 
        INNER JOIN tbl_learningcontent_titles ON (tbl_learningcontent_order.titleid = tbl_learningcontent_titles.id) 
        INNER JOIN tbl_learningcontent_pages ON (tbl_learningcontent_pages.titleid = tbl_learningcontent_titles.id AND original=\'Y\') 
        WHERE tbl_learningcontent_order.id=\''.$pageId.'\' AND contextcode=\''.$contextCode.'\'
        ORDER BY lft LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    /**
     * Return a single record
     * @param string $contextcode Context Code
     * @return array The values
     */
    function getContextLogs($contextcode, $where ) 
    {
        return $this->getAll("WHERE contextcode='" . $contextcode . "'".$where);
    }
    /**
     * Return json for context logs
     * @param string $contextcode Context Code
     * @return json The logs
     */
    function jsonContextLogs( $contextcode, $start, $limit ) 
    {
        if ( !empty($start) && !empty($limit) ) 
         $where = " LIMIT " . $start . " , " . $limit;
        else
         $where = "";
        $logs = $this->getContextLogs( $contextcode, $where );

        $logCount = (count($logs));
        $str = '{"logcount":"' . $logCount . '","availableLogs":[';
        $logArray = array();
        foreach ( $logs as $log ) {
         if( !empty ( $log['endtime'] ) || $log['endtime'] != NULL ) {
          $infoArray = array();
          $infoArray['id'] = $log['id'];
          $infoArray['userid'] = $log['userid'];
          //Function has bug
          //$userNames = $this->objUser->getTitle( $log['userid'] ).". ";
          //Return empty till its fixed
          $userNames = "";
          $userNames = $userNames.$this->objUser->fullname( $log['userid'] );
          $infoArray['usernames'] = $userNames;
          $infoArray['contextcode'] = $log['contextcode'];
          $infoArray['modulecode'] = $log['modulecode'];
          $infoArray['contextitemid'] = $log['contextitemid'];
          //Get context item title (page or chapter)
          if ( $log['pageorchapter'] == 'page' ) {
           $pageDetails = $this->getPage( $log['contextitemid'], $contextcode );
           $pageInfo = $this->objContentPages->pageInfo( $pageDetails['titleid'] );
           $infoArray['pageorchapter'] = $log['pageorchapter'];
           $infoArray['contextitemtitle'] = $pageInfo['menutitle'];
          } elseif ( $log['pageorchapter'] == 'chapter' ) {
           $chapterTitle = $this->objContentChapter->getContextChapterTitle( $log['contextitemid'] );
           $infoArray['pageorchapter'] = $log['pageorchapter']; 
           $infoArray['contextitemtitle'] = $chapterTitle;
          } elseif ( $log['pageorchapter'] == 'viewPicture' )  {
           $picdesc = $this->objFiles->getFileInfo($log['contextitemid']);
           if(empty($picdesc['filedescription'])){
             $picdescr = $picdesc["filename"];
           }else{
             $picdescr = $picdesc['filedescription'];    
           }

           $infoArray['pageorchapter'] = 'Picture';
           $infoArray['contextitemtitle'] = $picdescr;
          } elseif ( $log['pageorchapter'] == 'viewFormula' )  {
           $fmladesc = $this->objFiles->getFileInfo($log['contextitemid']);
           if(empty($fmladesc['filedescription'])){
             $fmladescr = $fmladesc["filename"];
           }else{
             $fmladescr = $fmladesc['filedescription'];    
           }
           $infoArray['pageorchapter'] = 'Formula';
           $infoArray['contextitemtitle'] = $fmladescr;
          } else {
           $infoArray['pageorchapter'] = $log['pageorchapter'];
           $infoArray['contextitemtitle'] = " ";
          }
          $infoArray['datecreated'] = $log['datecreated'];
          $infoArray['description'] = $log['description'];
          $infoArray['starttime'] = $log['starttime'];
          $infoArray['endtime'] = $log['endtime'];
          $logArray[] = $infoArray;
         }
        }
        return json_encode(array(
            'logcount' => $logCount,
            'contextlogs' => $logArray
        ));
    }
    /**
     * Return comma separated values(CSV) for context logs
     * @param string $contextcode Context Code
     * @return csv of the logs
     */
    function csvContextLogs( $contextcode ) 
    {
        $where = "";
        $logs = $this->getContextLogs( $contextcode, $where );

        $logCount = (count($logs));
        $str = '"id","userid","usernames","contextcode","modulecode","contextitemid","type","contextitemtitle","datecreated","description","starttime","endtime" ';
        $csvFile = "logs.csv"; 
        $Handle = fopen($csvFile, 'w');
        fwrite($Handle, $str);
        foreach ( $logs as $log ) {
         if( !empty ( $log['endtime'] ) || $log['endtime'] != NULL ) {

          $infoArray = array();
          $infoArray['id'] = $log['id'];
          $infoArray['userid'] = $log['userid'];
          //Function has bug
          //$userNames = $this->objUser->getTitle( $log['userid'] ).". ";
          //Return empty till its fixed
          $userNames = "";
          $userNames = $userNames.$this->objUser->fullname( $log['userid'] );
          $infoArray['usernames'] = $userNames;
          $infoArray['contextcode'] = $log['contextcode'];
          $infoArray['modulecode'] = $log['modulecode'];
          $infoArray['contextitemid'] = $log['contextitemid'];
          //Get context item title (page or chapter)
          $title = '';
          $pageorchapter = '';
          if ( $log['pageorchapter'] == 'page' ) {
           $pageDetails = $this->getPage( $log['contextitemid'], $contextcode );
           $pageInfo = $this->objContentPages->pageInfo( $pageDetails['titleid'] );
           $infoArray['pageorchapter'] = $log['pageorchapter'];
           $pageorchapter = $log['pageorchapter'];
           $infoArray['contextitemtitle'] = $pageInfo['menutitle'];
           $title = $pageInfo['menutitle'];
          } elseif ( $log['pageorchapter'] == 'chapter' ) {
           $chapterTitle = $this->objContentChapter->getContextChapterTitle( $log['contextitemid'] );
           $infoArray['pageorchapter'] = $log['pageorchapter']; 
           $pageorchapter = $log['pageorchapter'];
           $infoArray['contextitemtitle'] = $chapterTitle;
           $title = $chapterTitle;
          } elseif ( $log['pageorchapter'] == 'viewPicture' )  {
           $picdesc = $this->objFiles->getFileInfo($log['contextitemid']);
           if(empty($picdesc['filedescription'])){
             $picdescr = $picdesc["filename"];
           }else{
             $picdescr = $picdesc['filedescription'];    
           }

           $infoArray['pageorchapter'] = 'Picture';
           $pageorchapter = 'Picture';
           $infoArray['contextitemtitle'] = $picdescr;
           $title = $picdescr;
          } elseif ( $log['pageorchapter'] == 'viewFormula' )  {
           $fmladesc = $this->objFiles->getFileInfo($log['contextitemid']);
           if(empty($fmladesc['filedescription'])){
             $fmladescr = $fmladesc["filename"];
           }else{
             $fmladescr = $fmladesc['filedescription'];    
           }
           $infoArray['pageorchapter'] = 'Formula';
           $pageorchapter = 'Formula';
           $infoArray['contextitemtitle'] = $fmladescr;
           $title = $fmladescr;
          } else {
           $infoArray['pageorchapter'] = $log['pageorchapter'];
           $pageorchapter = $log['pageorchapter'];
           $infoArray['contextitemtitle'] = " ";
           $title = "";
          }
          $infoArray['datecreated'] = $log['datecreated'];
          $infoArray['description'] = $log['description'];
          $infoArray['starttime'] = $log['starttime'];
          $infoArray['endtime'] = $log['endtime'];
          $logArray[] = $infoArray;
          $str = '"'.$log['id'].'","'.$log['userid'].'","'.$userNames.'","'.$log['contextcode'].'","'.$log['modulecode'].'","'.$log['contextitemid'].'","'.$pageorchapter.'","'.$title.'","'.$log['datecreated'].'","'.$log['description'].'","'.$log['starttime'].'","'.$log['endtime'].'" ';
          fwrite($Handle, $str);
         }
        }
        fclose($Handle);
        return $csvFile;
    }
    /**
     * Method to delete a record
     * @param string $contextItemId Context Item Id
     */
    function deleteRecord($contextItemId)
    {
        // Delete the Record
        $this->delete('contextitemid', $contextItemId);
    }
}
?>
