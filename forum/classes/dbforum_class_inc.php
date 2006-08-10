<?
  // security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
/**
* Forum Table
* This class controls all functionality relating to the tbl_forum table
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package forum
* @version 1
*/
class dbForum extends dbTable
 {
	 /**
     *  $var  Context Code for the current Context
     */
     var $contextCode;
     
     /**
     *  $var  Context Title for the current Context
     */
     var $contextTitle;

	/**
	* Constructor method to define the table(default)
	*/
	function init()
	{
		parent::init('tbl_forum');
		
        // Context Code
        $this->contextObject =& $this->getObject('dbcontext', 'context');
 		$this->contextCode = $this->contextObject->getContextCode();
		
 		$this->contextTitle = $this->contextObject->getTitle();
 		if ($this->contextCode == ''){
 			$this->contextCode = 'root';
			$this->contextTitle = 'Lobby';
 		}
        
        $this->objUser =& $this->getObject('user', 'security');
		$this->objLanguage =& $this->getObject('language', 'language');
    }

    /**
	* Method to get number of forums in a context
	*
	* @param string $context: Context Code
    * @return integer number of forums
	*/
	function getNumForums($context = null)
	{
		$sql='';
		if (isset($context))
		{
			$sql = 'WHERE forum_context = "'.$context.'" AND forum_workgroup= "" AND forum_visible="Y"';
		}
		
		return $this->getRecordCount($sql);
	}

    /**
	* Method to get list of forums in a context
	*
	* @param string $context: Context Code
    * @return array list of forums
	*/
    function getContextForums($context = null)
	{
		$sql='';
		if (isset($context))
		{
			$sql = 'WHERE forum_context = "'.$context.'" AND forum_workgroup= "" AND forum_visible="Y"';
		}
		
		return $this->getAll($sql);
	}
	
	/**
	* Method to get list of all forums in a context whether they are visible or not
	*
	* @param string $context: Context Code
    * @return array list of forums
	*/
    function getAllContextForums($context = null)
	{
		$sql='';
		if (isset($context))
		{
			$sql = 'WHERE forum_context = "'.$context.'" AND forum_workgroup= ""';
		}
		
		return $this->getAll($sql);
	}


    /**
	* Method to get automatically create a forum in a context
	*
	* @param string $context: Context Code
    * @param string $title: Title to create the forum
	*/
	function autoCreateForum($context, $title)
    {
        
        // confirm there is no forums in the context
        if ($this->getNumForums($context) == 0)
		{
			$forum_context = $context;
            $forum_workgroup = '';
            $forum_name = $title.' Forum';
            $forum_description = 'This is the default discussion forum auto-generated for this context.';
            $enableDefaultForum = 'Y'; // Set to YES
            
            $newForumId = $this->insertSingle($forum_context, $forum_workgroup, $forum_name, $forum_description, $enableDefaultForum);
            
		} else {
            $newForumId = NULL;
        }
    	return $newForumId;
    }
    
	
	/**
	* This function provides a forum by providing a context
	*  in the module, it will only be called if a forum exists, so that isn't built in
	*
	* @param string $context: Context Code
    * @return array details of the forum
	*/
	function onlyForum($context)
    {
		 return $this->getRow('forum_context', $context);
    }


    /**
	* This method gets the list of forums in a context, and the amount of topics in the forums
	*
	* @param string $context: Context Code
    * @return array list of forums
	*/
    function showAllForums($context = null)
    {
        $sql = 'SELECT tbl_forum.id AS forum_id, tbl_forum. *,
        count( DISTINCT topicCountLink.id ) AS topics, count( DISTINCT postCountLink.id ) AS posts 
        FROM tbl_forum LEFT JOIN tbl_forum_topic AS topicCountLink ON ( topicCountLink.forum_id = tbl_forum.id ) 
        LEFT JOIN tbl_forum_post AS postCountLink ON ( postCountLink.topic_id = topicCountLink.id )  ';
        
        $sql .= ' WHERE forum_visible="Y" ';
        
        if (isset($context))
        {
            $sql .= '  AND tbl_forum.forum_context = "'.$context.'" AND forum_workgroup= ""';
        }
        
        $sql .= ' GROUP BY tbl_forum.id';
        $sql .= ' ORDER BY defaultforum, topics DESC  ';
        
        return $this->getArray($sql);
    }
    

    /**
	* This method gets a list of forums in a context, other than the forum specified.
    * This is used to jump between forums in a context
	*
	* @param string $forum_id: Record Id of the forum
    * @param string $context: Context Code
    * @return array list of forums
	*/
    function otherForums($forum_id, $context)
    {
        $sql = 'SELECT tbl_forum.id AS forum_id, tbl_forum.*, count(tbl_forum_topic.id) AS topics from tbl_forum_topic RIGHT JOIN tbl_forum ON (tbl_forum_topic.forum_id = tbl_forum.id) WHERE forum_visible="Y" AND forum_workgroup= "" AND tbl_forum.forum_context = "'.$context.'" AND tbl_forum.id != "'.$forum_id.'"';
	 
	 $sql .= ' GROUP BY tbl_forum.id';
     $sql .= ' ORDER BY defaultforum, topics DESC  ';
	 
     return $this->getArray($sql);
    }


    /**
	* This method gets the details of a single forum by providing the forum_id
	*
	* @param string $forum_id: Record Id of the forum
    * @return array list of forums
	*/
    function getForum($forum_id)
    {
        return $this->getRow('id', $forum_id);
    }

    /**
	* This method gets forum_id of the default forum with in a context
    * This method also checks that a forum exists, and creates one if none is present
	*
    * @return string Record Id of the default forum
	*/
	function getContextForum()
    {
		$forumNum = $this->getNumForums($this->contextCode);
		
		if ($forumNum == 0)
		{
			$forum = $this->autoCreateForum($this->contextCode, $this->contextTitle);
			
		} 
		 $forum = $this->getDefaultForum($this->contextCode);
		 
		 return $forum['id'];
		 
    }


    /**
    * Insert a forum into into the database
    *
    * @param string $forum_context:      Context for which this forum applies
    * @param string $forum_workgroup:   Workgroup the forum belongs to
    * @param string $forum_name:          Name of the Forum
    * @param string $forum_description: Description of the forum
    * @param string $forum_visible:       Is the forum visible-
    * @param string $ratingsenabled:      Can users rate posts in the forum
    * @param string $studentstarttopic: Can students start topics in the forum
    * @param string $attachments:         Can users upload attachments in the forum
    * @param string $subscriptions:       Can users subscribe to topics in the forum
    * @param string $moderation:          Can users moderate posts in the forum // Under construction
    * @param string $defaultForum:       Is this the default forum
    */
    function insertSingle($forum_context, $forum_workgroup, $forum_name, $forum_description, $defaultForum = 'N',  $forum_visible='Y', $enablePosts='Y',$ratingsenabled='N', $studentstarttopic='Y', $attachments='Y', $subscriptions='N', $moderation='Y', $forumlocked = 'N')
    {
    	$this->insert(array(
                'forum_context' => $forum_context,
                'forum_workgroup' => $forum_workgroup,
                'forum_name' => $forum_name,
                'forum_description' => $forum_description,
                'forum_visible' => $forum_visible,
                'defaultforum' => $defaultForum,
                'ratingsenabled' => $ratingsenabled,
                'studentstarttopic' => $studentstarttopic,
                'attachments' => $attachments,
                'subscriptions' => $subscriptions,
                'moderation' => $moderation,
                'forumlocked' =>$forumlocked
            ));
            
           
            $newForumId = $this->getLastInsertId();
            
            $userId = $this->objUser->userId();
            $objForumDefaultRatings =& $this->getObject('dbforum_default_ratings', 'forum');
            $objForumRatings =& $this->getObject('dbforum_ratings', 'forum');
            
            $defaultRatings = $objForumDefaultRatings->getDefaultList();
            
            foreach ($defaultRatings as $rating)
            {
                $objForumRatings->insertSingle(
                                $newForumId, 
                                $rating['rating_description'], 
                                $rating['rating_point'], 
                                $userId, 
                                mktime()
                            );
            }
    	
    	return $newForumId;
    }


    /**
	* This method gets forum_id of the default forum by providing a context
	*
    * @param string $context context code of the current context
	*/
    function getDefaultForum($context)
    {
		 $sql = 'SELECT id FROM tbl_forum WHERE forum_context= "'.$context.'"  AND forum_workgroup= "" AND defaultforum="Y"';
         
         $list = $this->getArray($sql);
         
         if (count($list) > 0) {
             return $list[0];
         } else {
             return;
         }
    }


    /**
	* This method sets a forum as the default forum for a context
	*
    * @param string $forum record Id of the forum
    * @param string $context Context Code
	*/
    function setDefaultForum($forum, $context)
    {
        $this->update('forum_context', $context, array(
    		'defaultforum' => 'N'
    	));
        
        $this->update('id', $forum, array(
    		'defaultforum' => 'Y',
            'forum_visible' => 'Y'
    	));
        
        return;
    }

  

    /**
    * Update settings of a forum
    *
    * @param string $forum_id:      Context for which this forum applies
    * @param string $forum_context:      Context for which this forum applies
    * @param string $forum_workgroup:   Workgroup the forum belongs to
    * @param string $forum_name:          Name of the Forum
    * @param string $forum_description: Description of the forum
    * @param string $forum_visible:       Is the forum visible-
    * @param string $ratingsenabled:      Can users rate posts in the forum
    * @param string $studentstarttopic: Can students start topics in the forum
    * @param string $attachments:         Can users upload attachments in the forum
    * @param string $subscriptions:       Can users subscribe to topics in the forum
    * @param string $moderation:          Can users moderate posts in the forum // Under construction
    * @param string $archiveDate:        Date to start Archiving from // NULL if archiving is disabled
    */
    function updateSingle($forum_id, $forum_context, $forum_workgroup, $forum_name, $forum_description,  $forum_visible, $forum_locked, $ratingsenabled, $studentstarttopic, $attachments, $subscriptions, $moderation, $archive)
    {
    	$this->update('id', $forum_id, array(
                'forum_context' => $forum_context,
                'forum_workgroup' => $forum_workgroup,
                'forum_name' => $forum_name,
                'forum_description' => $forum_description,
                'forum_visible' => $forum_visible,
                'forumlocked' => $forum_locked,
                'ratingsenabled' => $ratingsenabled,
                'studentstarttopic' => $studentstarttopic,
                'attachments' => $attachments,
                'subscriptions' => $subscriptions,
                'moderation' => $moderation,
                'archivedate' => $archive
            ));
    	
    	return;
    }
    
    /**
    * Method to update the visibility of a forum
    *
    * @param string $forum_id Record Id of the Forum
    * @param string $forum_visbile Forum Visibility either Y or N
    *
    * @return Result of SQL Update
    */
    function updateForumVisibility($forum_id, $forum_visible)
    {
        $forum_visible = strtoupper($forum_visible);
        
        if ($forum_visible == 'Y' || $forum_visible == 'N') {
            return $this->update('id', $forum_id, array('forum_visible' => $forum_visible));
        } else {
            return FALSE;
        }
    }
    
    /**
    * Method to check if a forum is locked
    * @param string $forumId Record Id of the forum
    * @return boolean True if the forum is unlocked or false
    */
    function checkIfForumLocked($forumId)
    {
        $forum = $this->getForum($forumId);
        
        if (count($forum) > 0 && $forum['forumlocked'] == 'Y') {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
    * Method to update the last posted topic in a forum
    * @param string $forum_id Record Id of the forum
    * @param string $topic_id Record Id of the topic
    */
    function updateLastTopic($forum_id, $topic_id)
    {
        $forum = $this->getRow('id', $forum_id);
        
        $topicsNum = $forum['topics'] + 1;
        
        return $this->update('id', $forum_id, array('lasttopic'=>$topic_id, 'topics'=>$topicsNum));
    }
    
    /**
    * Method to updated the last posted post/reply in a forum
    * @param string $forum_id Record Id of the forum
    * @param string $post-id Record Id of the post
    */
    function updateLastPost($forum_id, $post_id)
    {
        $forum = $this->getRow('id', $forum_id);
        
        $postNum = $forum['post'] + 1;
        
        return $this->update('id', $forum_id, array('lastpost'=>$post_id, 'post'=>$postNum));
    }
    
    /**
    *
    *
    */
    function getWorkgroupForum($context, $workgroup)
    {
        $sql = 'SELECT id FROM tbl_forum WHERE forum_context= "'.$context.'"  AND forum_workgroup= "'.$workgroup.'"';
        
        $list = $this->getArray($sql);
        
        if (count($list) > 0) {
            return $list[0]['id'];
        } else {
            return NULL;
        }
    }
    
    /**
	* Method to get automatically create a forum in a Workgroup
	*
	* @param string $context: Context Code
    * @param string $title: Title to create the forum
	*/
	function autoCreateWorkgroupForum($context, $workgroup, $title)
    {
        
        // confirm there is no forums in the workgroup
        if ($this->getWorkgroupForum($context, $workgroup) == NULL)
		{
			$forum_context = $context;
            $forum_workgroup = $workgroup;
            $forum_name = $title.' Forum';
            $forum_description = 'This is the default discussion forum auto-generated for this workgroup.';
            $defaultForum = 'Y'; // Set to YES
            
            $forum_visible='Y';
            $enablePosts='Y';
            $ratingsenabled='N';
            $studentstarttopic='Y';
            $attachments='Y';
            
            $newForumId = $this->insertSingle($forum_context, $forum_workgroup, $forum_name, $forum_description, $defaultForum,  $forum_visible, $enablePosts,$ratingsenabled, $studentstarttopic, $attachments);
            
		} else {
            $newForumId = NULL;
        }
    	return $newForumId;
    }
    
    /**
    * Method to delete a forum
    * @param string $id Record Id of the Forum
    * @return boolean Result of Delete
    */
    function deleteForum($id)
    {
        // Get List of Attachments
        $objForumAttachments = $this->getObject('dbforumattachments');
        
        $this->beginTransaction();
            $this->delete('id', $id); // Delete the Forum
            $objForumAttachments->deleteForumAttachments($id); // Delete all attachments related to the forum.
            // Additional Cleanup
            // Needed to clean up tbl_forum_attachments as well tbl_filestore.
        $this->commitTransaction();
        
        return ;
    }
    
    /**
    * Method to determine whether the current sort is in ascending or descending
    * @param string $orderParam Parameter to check for
    * @return string Direction for Parameter
    */
    function orderDirection($orderParam)
    {
        if ($this->order == $orderParam)
        {
            if ($this->direction == 'desc')
            {
                $direction = 'asc';
            } else {
                $direction = 'desc';
            }
            return $direction;
        }
    }
    
    /**
    * Method to create a URL to sort by column
    *
    * @param string $forum_id Record Id of the Forum
    * @sort string Column to sort by
    *
    * @return string Completed URI
    */
    function forumSortLink ($forum_id, $sort, $textLink)
    {
        $this->loadClass('link', 'htmlelements');
        
        $icon = $this->getObject('geticon', 'htmlelements');
        
        $direction = $this->orderDirection($sort);
        $link = new link ($this->uri(array('action'=>'forum', 'id'=>$forum_id, 'order'=>$sort, 'direction'=>$direction)));
        
        $link->link = $textLink;
        $link->title = $this->objLanguage->languageText('sort_by', 'Sort by').' '.$textLink;
        
        if ($this->order == $sort) {
            if ($direction == 'asc') {
                $image = 'mvup';
                $alt = $this->objLanguage->languageText('current_sort_descending', 'Current Sort - Descending');
            } else {
                $image = 'mvdown';
                $alt = $this->objLanguage->languageText('current_sort_ascending', 'Current Sort - Ascending');
            }
            
            $icon->setIcon($image);
            $icon->title = $alt;
            $icon->alt = $alt;
            
            return $link->show().' '.$icon->show();
        } else {
            return $link->show();
        }
        
    }
    
    
 }
 ?>