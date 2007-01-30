<?php
/**
 * Abstract base class bookmark.
 * @author James Kariuki Njenga
 * @version $Id$
 * @copyright 2005, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
*/

class dbBookmark extends dbTable
{

    /**
    * Initialise objects used in the module
    */

    function init()
    {
        parent::init('tbl_bookmarks');
        //$this->USE_PREPARED_STATEMENTS=True;
    }
    
    /**
    * Method to insert a single record into the database table
    *
    * @param groupid string
    * @param title string
    * @param url string
    * @param description string
    * @param datecreated datetime
    * @param isprivate char
    * @param datelastaccessed Datetime
    * @param creatorid string
    * @param visitcount char
    * @param datemodified datetime
    * @param isdeleted char
    *
    *
    *
    */
    function insertSingle($groupid,$title, $url,
            $description, $datecreated, $isprivate, $datelastaccessed,
            $creatorid, $visitcount, $datemodified)
    {
        $this->insert(array(
             'groupid' =>$groupid,
             'title'   =>$title,
             'url'     =>$url,
             'description'=>$description,
             'datecreated'=>strftime('%Y-%m-%d %H:%M:%S', $datecreated),
             'isprivate'  =>$isprivate,
             'datelastaccessed'=>$datelastaccessed,
             'creatorid'   => $creatorid,
             'visitcount'  =>$visitcount,
             'datemodified'=>$datemodified));
         return;
    }
    
    /**
    * Method to update a bookmark
    *
    * @access public
    */
    function updateBookmark()
    {
            $id=$this->getParam('id');
	    $fields = array();
	    $fields['groupid']=$this->getParam('parent');
            $fields['title']=$this->getParam('title');
            $fields['url']=$this->getParam('url');
            $fields['description']=$this->getParam('description');
            $fields['isprivate']= $this->getParam('private');
            $fields['datemodified']=strftime('%Y-%m-%d %H:%M:%S',mktime());
            $this->update('id', $id, $fields);
    }
    
    /**
    * function to check if a given folder is empty
    * @param folderId
    *
    * return bool
    */
    function isEmpty($folderId)
    {
        $filter="WHERE groupid='$folderId'";
        $rows=$this->getRecordCount($filter);
        if ($rows>0){
            return False;
        } else {
            return True;
        }
    }
    
    /**
    * Method to update the bookmark table on accessing a bookmark
    * Sets the date of access and also increased the hit count
    *
    */
    
    function updateVisitHit($pageId)
    {
        $hitTime=mktime();
		$hitTime=strftime('%Y-%m-%d %H:%M:%S',$hitTime);
		$visitcount=$this->getHits($pageId);

		$visitcount=$visitcount+1;
		return $this->update('id',$pageId, array('datelastaccessed'=>$hitTime,'visitcount'=>$visitcount));
    }
    
    /**
    * Method to get the number of hits a bookmark has
    *
    * @param pageid string
    * return int
    */
    function getHits($pageId)
    {
        $filter="where id='$pageId'";
        $list=$this->getAll($filter);
        foreach ($list as $line)
        {
            $count=$line['visitcount'];
        }
        return $count;
        
    }
    
    /**
    * Function to do a search on the bookmark table
    *
    * @param $searchTerm , the search term
    * return array
    */

    function search($searchTerm,$userId)
    {
       $filter="WHERE (title LIKE '%$searchTerm%' OR description
                LIKE '%$searchTerm%' OR url Like '%$searchTerm%')
                AND (isprivate='0' or creatorid='$userId')";
       $searchResults=$this->getAll($filter);
       return $searchResults;
    }
}
?>
