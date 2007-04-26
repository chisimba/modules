<?php
/**
 * Abstract base class bookmark folders.
 * @author James Kariuki Njenga
 * @version $Id$
 * @copyright 2005, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
*/

class dbGroup extends dbTable
{

    /**
    * Initialise objects used in the module
    */

    function init()
    {
        parent::init('tbl_bookmarks_groups');
        //$this->USE_PREPARED_STATEMENTS=True;
        $this->objLanguage = $this->getObject('language', 'language');
    }
    
    /**
    * Function to return the name of a group or folder
    *
    * @param folderId string
    * return name
    */
    
    function folderByName($folderId)
    {
        return $this->getRow('id',$folderId);
    }


    /**
    * Function to return the id of a group or folder
    *
    * @param foldername string
    * return string
    */
    function folderById($folder)
    {
        $row=$this->getRow('title',$folder);
        return $row['id'];
    }
    
    /**
    * Method to insert a single record in the database table
    * @param title string
    * @param description string
    * @param isprivate char, 0 or 1
    * @param datecreated datetime
    * @param datemodified dateteim
    * @param isdefault char
    * @param creatorid string
    *
    */
    function insertSingle($title,$description,
             $isprivate,$datecreated,$isdefault,$creatorId)
    {
        $this->insert(array(
            'title'  		=> $title,
            'description'	=> $description,
            'isprivate' 	=> $isprivate,
            'datecreated'	=> $datecreated,
            'isdefault'     =>$isdefault,
            'creatorid'    =>$creatorId
    	));
    	return;
    }
    
    
    /**
    * Method to create the root folder of a new user.
    *
    */
    function createDefaultFolder($userId)
    {
        $title=$this->objLanguage->LanguageText('mod_bookmark_defaultfolder','kbookmark');
        $description=$this->objLanguage->LanguageText('mod_bookmark_defaultfolder','kbookmark');
        $isprivate='0';
        $datecreated=$this->now();
        $isdefault='1';
        $creatorId=$userId;
        
        $this->insertSingle($title,$description,
        $isprivate,$datecreated,$isdefault,$creatorId);
    }

    /**
    * Function to get the default folder for display
    * checks if the default folder is created, if not creates it
    *
    * returns the folder Id
    */
    function getDefaultId($userId)
    {
        $list = $this->getRow('isdefault','1','creatorid',$userId);
        if ($list['id']==Null) {
            $this->createDefaultFolder($userId);
            $this->getDefaultId($userId);
        }
        return $list['id'];
    }
    
    /**
    * Method to unset the default folder
    *
    *
    */
    function resetDefault()
    {
        return $this->update('isdefault','1',array('isdefault'=>'0'));
    }
    /**
    * Function to set the default folder for display
    *
    */
    
    function setDefault($folderId)
    {
        //unset the default folder
        $this->resetDefault();
        //set the new one
        //return $this->query("update tbl_bookmarks_groups set isdefault=1 where id='$folderId'");
        return $this->update('id', $folderId, array('isdefault'=>'1'));
    }
	
	/**
	* function to get all the shared folders that have bookmarks
	*
	* returns array
	*/
	function getSharedWithBookmarks()
	{
	    $sql="SELECT DISTINCT(tbl_bookmarks_groups.id), tbl_bookmarks_groups.creatorid, tbl_bookmarks_groups.isprivate, tbl_bookmarks_groups.title 
	    FROM tbl_bookmarks_groups 
	    LEFT JOIN tbl_bookmarks ON tbl_bookmarks_groups.id=tbl_bookmarks.groupid 
	    WHERE (tbl_bookmarks_groups.isprivate='0')";
		return $this->getArray($sql);
	}
    
	/**
	* function to get all the users who have shared folders that have bookmarks
	*
	* returns array
	*/
	function getUsersWithSharedBookmarks()
	{
	    $sql="SELECT DISTINCT(tbl_bookmarks_groups.creatorid) FROM tbl_bookmarks_groups 
	    LEFT JOIN tbl_bookmarks ON tbl_bookmarks_groups.id=tbl_bookmarks.groupid 
	    WHERE (tbl_bookmarks_groups.isprivate='0')";
		return $this->getArray($sql);
	}
    
	/**
	* function to get all the users who have shared folders that have bookmarks
	*
	* returns array
	*/
	function getShared4User($userId)
	{
	    $sql="SELECT DISTINCT(tbl_bookmarks_groups.id), tbl_bookmarks_groups.creatorid, tbl_bookmarks_groups.isprivate, tbl_bookmarks_groups.title 
	    FROM tbl_bookmarks_groups 
	    LEFT JOIN tbl_bookmarks ON tbl_bookmarks_groups.id=tbl_bookmarks.groupid 
	    WHERE (tbl_bookmarks_groups.isprivate='0' AND tbl_bookmarks_groups.creatorid='$userId')";
		return $this->getArray($sql);
	}

}
?>