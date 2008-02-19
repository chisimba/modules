<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


/**
* Model class for the table tbl_faq_categories
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbFaqCategories extends dbTable
{
    /**
    * Constructor method to define the table
    */
  public function init() 
    {
        parent::init('tbl_faq2_categories');
        //$this->USE_PREPARED_STATEMENTS=True;
    } 

    /**
    * Get category id. (called in getContextLinks() function in modulelinks class)
    * @author Nonhlanhla Gangeni <noegang@gmail.com>
    */
  public function getCatId($pkId)
    {   
    return $this->getRow("id", $pkId);
    }

    /**
    * Return all records
	* @param string $contextId The context ID

	* @return array The categories
    */
   public function listAll()
	{
		
		return $this->getAll("ORDER BY datelastupdated");
	}

	/**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
   public function getCategory($categoryId)
	{
		$sql = "SELECT * FROM tbl_faq2_categories_lang 
		WHERE categoryid='" . $categoryId . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
	/**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
   public function listSingle($categoryid)
	{
		$sql = "SELECT * FROM tbl_faq2_categories_lang 
		WHERE categoryid='" . $categoryid . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/**
	* Return a single record from the id
	* @param string $id The ID
	* @return array The category
	*/	
   public function listSingleId($id)
	{
		$sql = "SELECT * FROM tbl_faq2_categories 
		WHERE id = '" . $id . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
    function getLicenseCode($id)
	{
		$sql="select * from tbl_faq2_categories c";
		$sql.=" where c.id='$id'";
		
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
/**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	
 
 public	function insertCategory($license,$userid,$datelastupdated)
	{
		$id = $this->insert(array(
			'license' => $license, 
			'userid' => $userid,
			'datelastupdated' => $datelastupdated
			));
                $id=$this->getLastInsertId();
		return $id;    
	}
        public	function insertIntoContext($categoryid,$contextid,$userid,$datelastupdated)
	{
		$id = $this->insert(array(
			'categoryid' => $categoryid,
                        'contextid' => $contextid, 
			'userid' => $userid,
			'datelastupdated' => $datelastupdated
			),"tbl_faq2_categories_context");
		return $id;    
	}
	/**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
	
 
 public	function insertSingle($categoryid,$categoryname,$language,$isdefaultlang,$userid,$datelastupdated)
	{ 
		$id = $this->insert(array(
			'categoryid' => $categoryid, 
			'categoryname' => $categoryname, 
			'language' => $language,
                        'isdefaultlang'=>$isdefaultlang,
			'userid' => $userid,
			'datelastupdated' => $datelastupdated
			),"tbl_faq2_categories_lang");
		return $id;     
	}
	/**
	* Update a record
	* @param string $id ID
	* @param string $categoryId The category
	* @param string $userId The user ID
	* @param string $dateLastUpdated Date last updated
	*/
  public function updateCat($id,$license,$datelastpdated)
	{
		$this->update("id", $id, 
			array(
				'license' => $license,
				'datelastupdated' =>$datelastpdated
			),"tbl_faq2_categories"
		);
	}
        public function updateCatLang($id,$categoryname,$language,$isdefaultlang,$datelastpdated)
	{
		$this->update("categoryid", $id, 
			array(
        		'categoryname' => $categoryname,
				'language' => $language,
                                'isdefaultlang' => $isdefaultlang,
				'datelastupdated' =>$datelastpdated
			),"tbl_faq2_categories_lang"
		);
	}
	
	/**
	* Delete a record
	* @param string $id ID
	*/

 function deleteSingle($col,$value,$table)
	{
	$this->delete($col,$value,$table);
		
	}


///from FAQ

  /**
	* Return a single record
	* @param string $contextId The context ID
	* @param string $categoryId The category ID
	* @return array The category
	*/	
 public	function getNotCategorisedId($contextId)
	{
		$sql = 'SELECT * FROM tbl_faq_categories WHERE categoryname = "Not Categorised" AND userid = "admin" AND contextid = "'. $contextId.'"';
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return NULL;
        } else {
            return $results[0]['id'];
        }
	}
	
  /**
	* Return the latest Category created or updated
	* @param string $contextId The context ID
	* @return array The category id
	*/		
 public function getLastestCategory($contextId)
 {
 	$sql = 'SELECT id FROM tbl_faq2_categories WHERE contextid = "'. $contextId.'" AND datelastupdated = (SELECT MAX(datelastupdated) FROM tbl_faq_categories)';
 	$results = $this->getArray($sql);
 	$latest = current($results);
 	return $latest['id'];
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
       // $this->objHelp =& $this->getObject('link','htmlentities');
        $topicId = $this->getSession('topicId');
        $lbTopics = $this->objLanguage->languageText('phrase_topicsincategory');
        $list = $this->getTopicList();

        $str = '';
        if(!empty($list)){
            foreach($list as $item){
                if($item['topicid'] == $topicId AND !$linkAll){
                    //$lnTopic = $item['post_title'];
                    $objLink = new link($this->uri(array('action' => 'showtopic', 'topicId' => $item['topicid'])));
                    $objLink->link = $item['post_title'];
                    $objLink->style = "color: #0000BB;";
                    $lnTopic = $objLink->show();
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
        return $this->objFeatureBox->show($lbTopics, $str, NULL, 'default', TRUE, TRUE);
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
            AND posttext.post_id = post.id
            ORDER BY topic.datelastupdated";

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
    public function showCategoryList($contextid = '', $linkAll = FALSE)
    {
        // Get the current faqcategory
        //$forumId = $this->getSession('forumId');
        $this->objLanguage = $this->newObject('language', 'language');
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
       $lbCats = $this->objLanguage->languageText('mod_faq2_categories');
        $list = $this->getCategoryList();
        $this->loadClass('link', 'htmlelements');

        $str = '';
        if(!empty($list)){
            foreach($list as $item){

                    $lncat = $item['categoryname'];
                    $objLink = new link($this->uri(array('action' => 'showcat', 'catid' => $item['catid'])));
                    $objLink->link = $item['name'];
                    $objLink->style = "color: #0000BB;";
                    $lncat = $objLink->show();
                
                $str .= '<p style="margin: 0px;">'.$lncat.'</p>';
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
    public function getCategoryList()
    {
        $this->objDbContext = &$this->getObject('dbcontext','context');
        $this->contextCode = $this->objDbContext->getContextCode();
	$contextid=trim($this->contextCode);
        if(!empty($contextid))
        {
	   
        $sql = "SELECT tbl_faq2_categories_lang.categoryname as name,tbl_faq2_categories_lang.categoryid as catid FROM tbl_faq2_categories_lang,tbl_faq2_categories_context
	    WHERE tbl_faq2_categories_lang.categoryid=tbl_faq2_categories_context.categoryid AND tbl_faq2_categories_context.contextid ='$contextid'";
        }
        else
        {
	//check if there are entries in the context table
	if($this->getCount("tbl_faq2_categories_context")!=0){
	//show only categories that do not belong to a context
          $sql = "SELECT cl.categoryname as name,cl.categoryid as catid FROM tbl_faq2_categories_lang  cl,tbl_faq2_categories_context cc WHERE cc.categoryid<>cl.categoryid";   
        
	   }//others wise show all categories
	   else
	  $sql = "SELECT cl.categoryname as name,cl.categoryid as catid FROM tbl_faq2_categories_lang  cl";   
        }
        $data = $this->getArray($sql);
        return $data;
    }

	


    public function getFirstRow($contextid){
    $contextid=trim($contextid);
    if(empty($contextid))
    {
        //check if there are entries in the context table
	if($this->getCount("tbl_faq2_categories_context")!=0){
            $sql="SELECT c.id FROM tbl_faq2_categories c,tbl_faq2_categories_context cc WHERE (SELECT MIN(c.puid) FROM tbl_faq2_categories) AND (c.id!=cc.categoryid)";
        }else
            $sql="SELECT id FROM tbl_faq2_categories WHERE (SELECT MIN(puid) FROM tbl_faq2_categories)";
        
    
    }else
    {
     $sql="SELECT c.id FROM tbl_faq2_categories c,tbl_faq2_categories_context cc WHERE (SELECT MIN(c.puid) FROM tbl_faq2_categories) AND (cc.categoryid=c.id AND cc.contextid='$contextid')";	
	
    }
    $id = $this->getArray($sql);
    return $id;
    
    }
    public function getLastRow($contextid){
    $contextid=trim($contextid);

    if(empty($contextid))
    {
        //check if there are entries in the context table
	if($this->getCount("tbl_faq2_categories_context")!=0){
            $sql="SELECT c.id FROM tbl_faq2_categories c,tbl_faq2_categories_context cc WHERE (SELECT MAX(c.puid) FROM tbl_faq2_categories) AND c.id!=cc.categoryid";
        }else
            $sql="SELECT id FROM tbl_faq2_categories WHERE (SELECT MAX(puid) FROM tbl_faq2_categories)";
        
    
    }else
    {
     $sql="SELECT c.id FROM tbl_faq2_categories c,tbl_faq2_categories_context cc WHERE (SELECT MAX(c.puid) FROM tbl_faq2_categories) AND (cc.categoryid=c.id AND cc.contextid='$contextid')";	
	
    }
    
 
    $id = $this->getArray($sql);
    return $id;
    
    }
    public function getCount($tablename)
    {
	$sql="select COUNT(*) as rc from $tablename order by id";
	$result=mysql_query($sql);
	$count=mysql_fetch_object($result);
	return $count->rc;
	
    }
}

?>
