<?php
/**
* dbhivforum class extends dbtable
* @package hivaidsforum
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbhivforum class
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class dbhivforum extends dbtable
{
    /**
    * @var string $context The current context - set it to root/lobby
    * @access private
    */
    private $context = 'root';
    
    /**
    * Constructor method
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDate = $this->getObject('dateandtime', 'utilities');
        
        $this->dbForum = $this->getObject('dbforum', 'forum');
        $this->dbTopic = $this->getObject('dbtopic', 'forum');
        $this->dbForumPost = $this->getObject('dbpost', 'forum');
        $this->dbForumPostText = $this->getObject('dbposttext', 'forum');
        
        $this->objFeatureBox = $this->getObject('featurebox', 'navigation');
        $this->objRound = $this->newObject('roundcorners', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
    }
    
    /**
	* Method to dynamically switch tables
	*
	* @access private
	* @param string $table: The name of the table
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _changeTable($table)
	{
		try {
			parent::init($table);
			return TRUE;
		}catch (Exception $e){
			throw customException($e->getMessage());
			exit();
		}
	}
	
	/**
	* Method to display the topic post and the posted responses in round divs
	*
	* @access public
	* @return string html
	*/
	public function showPosts()
	{
	    $topicId = $this->getSession('topicId');
	    $data = $this->getPosts();
	    $lbPosted = $this->objLanguage->languageText('word_posted');
	    $lbReply = $this->objLanguage->languageText('phrase_replytotopic');
	    
	    $topStr = '';
	    $replyStr = '';
	    
	    // Create reply link
	    $objLink = new link($this->uri(array('action' => 'showreply')));
	    $objLink->link = $lbReply;
	    $lnReply = '<br />'.$objLink->show();
	    
	    if(!empty($data)){
	        foreach($data as $item){
	            $name = ''; $posted = '';
	            
	            // check if post is the topic parent
	            if($item['post_parent'] == '0'){
    	            $objHead = new htmlheading();
    	            $objHead->str = $item['post_title'];
    	            $objHead->type = 4;
    	            
    	            $inStr = $objHead->show();
    	            $inStr .= $item['post_text'];
	           
	                $topStr = $this->objRound->show($inStr.$lnReply);
	            }else{
	                if(!empty($item['userid'])){
	                    $name = ' ... '.$this->objUser->fullname($item['userid']);
	                }
	                if(!empty($item['datelastupdated'])){
	                    $date = $this->objDate->formatDateOnly($item['datelastupdated']);
	                    $posted = $lbPosted.': '.$date;
	                }
	                
	                $objTable = new htmltable();
	                $objTable->startRow();
	                $objTable->addCell('<b>'.$item['post_title'].'</b>');
	                $objTable->addCell($posted, '', '', 'right');
	                $objTable->endRow();
    	            $inStr = $objTable->show();
    	            $inStr .= $item['post_text'];
    	            
	                $replyStr .= $this->objRound->show($inStr.$lnReply);
	            }
	        }
	    }
	    return $topStr.$replyStr;
	}
	
	/**
	* Method to get a topic post and responses
	*
	* @access private
	* @return array $data The posts
	*/
	private function getPosts()
	{
	    $topicId = $this->getSession('topicId');
	    
        // Get the posts from the DB
        $this->_changeTable('tbl_forum_post');
        $sql = "SELECT * FROM tbl_forum_post AS post, tbl_forum_post_text AS posttext 
            WHERE post.topic_id = '{$topicId}' AND posttext.post_id = post.id 
            ORDER BY post.datelastupdated ASC ";
            
        $data = $this->getArray($sql);
        return $data;
	}
	
	/**
	* Method to display the top post
	*
	* @access public
	* @param string $data
	* @return string html
	*/
	public function displayTopPost($data)
	{	    
	    $objHead = new htmlheading();
    	$objHead->str = $data['post_title'];
    	$objHead->type = 4;
        $inStr = $objHead->show();
        $inStr .= $data['post_text'];
	           
        return $this->objRound->show($inStr);
	}
	
	/**
	* Method to display the forum details
	*
	* @access public
	* @param string $data
	* @return string html
	*/
	public function displayForum($data)
	{	    
	    $objHead = new htmlheading();
    	$objHead->str = $data['forum_name'];
    	$objHead->type = 4;
        $inStr = $objHead->show();
        $inStr .= $data['forum_description'];
	           
        return $this->objRound->show($inStr);
	}
	
	/**
	* Method to show a post
	*
	* @access public
	* @param string $postId
	* @return string html
	*/
	public function showSinglePost($postId)
	{
	    $data = $this->getPost($postId);
	    $lbPosted = $this->objLanguage->languageText('word_posted');
	    
	    if(!empty($data)){
	        if(!empty($data['userid'])){
	            $name = ' ... '.$this->objUser->fullname($data['userid']);
	        }
	        if(!empty($data['datelastupdated'])){
	            $date = $this->objDate->formatDateOnly($data['datelastupdated']);
	            $posted = $lbPosted.': '.$date;
	        }
	        
	        $objTable = new htmltable();
	        $objTable->startRow();
	        $objTable->addCell('<b>'.$data['post_title'].'</b>');
	        $objTable->addCell($posted, '', '', 'right');
	        $objTable->endRow();
    	    $inStr = $objTable->show();
            $inStr .= $data['post_text'];
    	            
	        return $this->objRound->show($inStr);
	    }
	}
	
	/**
	* Method to get a topic post and responses
	*
	* @access private
	* @param string $postId
	* @return array $data The posts
	*/
	private function getPost($postId)
	{   
        // Get the posts from the DB
        $this->_changeTable('tbl_forum_post');
        $sql = "SELECT * FROM tbl_forum_post AS post, tbl_forum_post_text AS posttext 
            WHERE post.id = '{$postId}' AND posttext.post_id = post.id ";
            
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return array();
	}
	
	/**
	* Method to list the topics for display in a box / block
	*
	* @access public
	* @param string $dispType The format of the display - featurebox or div
	* @return string html
    */
    public function showTopicList($dispType = 'box', $linkAll = FALSE)
    {
        $topicId = $this->getSession('topicId');
        $lbTopics = $this->objLanguage->languageText('word_topics');
        $list = $this->getTopicList();
        
        $str = '';
        if(!empty($list)){
            foreach($list as $item){
                if($item['topicid'] == $topicId AND !$linkAll){
                    $lnTopic = $item['post_title'];
                }else{
                    $objLink = new link($this->uri(array('action' => 'showtopic', 'topicId' => $item['topicid'])));
                    $objLink->link = $item['post_title'];
                    $lnTopic = $objLink->show();
                }
                $str .= '<p style="margin: 0px;">'.$lnTopic.'</p>';
            }
        }
        if($dispType == 'round'){
            $objHead = new htmlheading();
            $objHead->str = $lbTopics;
            $objHead->type = '4';
            return $this->objRound->show($objHead->show().$str);
        }
        if($dispType == 'nobox'){
            return $str;
        }
        return $this->objFeatureBox->show($lbTopics, $str);
    }
    
    /**
    * Method to get the list of topics
    *
    * @access private
    * @return array $data the topic list
    */
    private function getTopicList()
    {
        // Get the current forum
        $forumId = $this->getSession('forumId');
        
        // Get the list from the DB
        $this->_changeTable('tbl_forum_topic');
        $sql = "SELECT *, topic.id as topicid FROM tbl_forum_topic AS topic, tbl_forum_post AS post, tbl_forum_post_text AS posttext ";
        
        // Use the current forum, if not set then find the default
        if(isset($forumId) && !empty($forumId)){
            $sql .= "WHERE topic.forum_id = '{$forumId}' ";
        }else{
            $sql .= " WHERE topic.forum_id = 
            (SELECT id FROM tbl_forum AS forum WHERE forum.defaultforum = 'Y' AND forum.forum_context = '$this->context') ";
        }
        
        // Get the topic text
        $sql .= "AND topic.first_post = post.id AND post.post_parent = '0' 
            AND posttext.post_id = post.id ";
        
        $data = $this->getArray($sql);
        
        return $data;
    }
    
    /**
    * Method to get the topic post
    *
    * @access public
    * @return array $data the topic
    */
    public function getTopicPost()
    {
        // Get the current forum
        $topicId = $this->getSession('topicId');
        
        // Get the list from the DB
        $this->_changeTable('tbl_forum_post');
        $sql = "SELECT *, post.id as postid FROM tbl_forum_post AS post, tbl_forum_post_text AS posttext 
            WHERE post.topic_id = '{$topicId}' AND post.post_parent = '0' AND posttext.post_id = post.id ";
        
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return array();
    }
    
	/**
	* Method to list the categories for display in a box / block
	*
	* @access public
	* @return string html
    */
    public function showCategoryList($dispType = '', $linkAll = FALSE)
    {
        // Get the current forum
        $forumId = $this->getSession('forumId');
        
        $lbCats = $this->objLanguage->languageText('word_categories');
        $list = $this->getCategoryList();
        
        $str = '';
        if(!empty($list)){
            foreach($list as $item){
                if($item['id'] == $forumId AND !$linkAll){
                    $lnForum = $item['forum_name'];
                }else{
                    $objLink = new link($this->uri(array('action' => 'showcat', 'catId' => $item['id'])));
                    $objLink->link = $item['forum_name'];
                    $lnForum = $objLink->show();
                }
                $str .= '<p style="margin: 0px;">'.$lnForum.'</p>';
            }
        }
        
        if($dispType == 'nobox'){
            return $str;
        }
        return $this->objFeatureBox->show($lbCats, $str);
    }

    /**
    * Method to get the list of categories / forums
    *
    * @access private
    * @return array $data The categories
    */
    private function getCategoryList()
    {   
        // Get the list from the DB
        $this->_changeTable('tbl_forum');
        $sql = "SELECT * FROM tbl_forum AS forum
            WHERE forum.forum_context = '{$this->context}' AND forum.forum_visible = 'Y' ";
            
        $data = $this->getArray($sql);
        return $data;
    }
    
	/**
	* Method to display the recent posts in a box / block
	*
	* @access public
	* @return string html
    */
    public function showRecentPosts()
    {
        $lbPosts = $this->objLanguage->languageText('phrase_recentposts');
        $list = $this->getRecentPosts();
        
        $str = '';
        if(!empty($list)){
            foreach($list as $item){
                $objLink = new link($this->uri(array('action' => 'showpost', 'postId' => $item['postid'])));
                $objLink->link = $item['post_title'];
                $lnPost = $objLink->show();
                $str .= '<p style="margin: 0px;">'.$lnPost.'</p>';
            }
        }
        return $this->objFeatureBox->show($lbPosts, $str);
    }

    /**
    * Method to get the recent posts
    *
    * @access private
    * @return array $data The categories
    */
    private function getRecentPosts()
    {   
        // Get the list from the DB
        $this->_changeTable('tbl_forum_post');
        $sql = "SELECT *, post.id as postid FROM tbl_forum_post AS post, tbl_forum AS forum, tbl_forum_topic AS topic, tbl_forum_post_text AS posttext 
            WHERE forum.forum_context = '{$this->context}' AND topic.forum_id = forum.id 
            AND post.topic_id = topic.id AND posttext.post_id = post.id
            ORDER BY post.datelastupdated DESC LIMIT 5";
            
        $data = $this->getArray($sql);
        return $data;
    }
    
    /**
    * Method to get the forum details - if the forum is not set, then use the default
    *
    * @access public
    * @return array $data
    */
    public function getForumDetails()
    {
        $forumId = $this->getSession('forumId');
        
        $this->_changeTable('tbl_forum');
        $sql = "SELECT id, forum_name, forum_description FROM tbl_forum  ";
        
        // Use the current forum, if not set then find the default
        if(isset($forumId) && !empty($forumId)){
            $sql .= "WHERE id = '{$forumId}' ";
        }else{
            $sql .= " WHERE defaultforum = 'Y' AND forum_context = '$this->context' ";
        }
        
        $data = $this->getArray($sql);
        if(!empty($data)){
            $this->setSession('forumId', $data[0]['id']);
            return $data[0];
        }
        return array();
    }
    
    /**
    * Method to save a new topic
    *
    * @access public
    * @return void
    */
    public function saveTopic()
    {
        $forumId = $this->getSession('forumId');
        $userId = $this->objUser->userId();
        $typeId = 'init_1';
        
        $topicId = $this->dbTopic->insertSingle($forumId, $typeId, '0', $userId);
        $this->setSession('topicId', $topicId);
        $postId = $this->saveTopicPost();
        $this->dbTopic->updateFirstPost($topicId, $postId);
        return $topicId;
    }
    
    /**
    * Method to save a reply to a topic
    *
    * @access public
    * @return void
    */
    public function saveTopicPost()
    {
        $userId = $this->objUser->userId();
        $topicId = $this->getSession('topicId');
        $forumId = $this->getSession('forumId');
        $postParent = $this->getParam('postid', '0');
        $subject = $this->getParam('subject');
        $message = $this->getParam('message');
        
        $postId = $this->dbForumPost->insertSingle($postParent, '0', $forumId, $topicId,  $userId);
        $this->dbForumPostText->insertSingle($postId, $subject, $message,  'en', '1', $userId);
        $this->dbTopic->updateLastPost($topicId, $postId);
        return $postId;
    }
    
    /**
    * Method to save a category or forum
    *
    * @access public
    * @return void
    */
    public function saveCategory()
    {
        $forum = $this->getParam('forum');
        $description = $this->getParam('description');
        $visible = $this->getParam('visible');
        
        return $this->dbForum->insertSingle($this->context, '', $forum, $description, 'N',  $visible);
    }
}
?>