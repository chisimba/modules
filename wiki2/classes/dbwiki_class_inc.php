<?php
/* ------ data class extends dbTable for all wiki version 2 database tables ------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the site permissions database tables
* @author Kevin Cyster
*/

class dbwiki extends dbTable
{
    /**
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;
    /**
    * @var object $objUser: The user class in the security module
    * @access private
    */
    private $objUser;

    /**
    * @var string $userId: The user id of the current user
    * @access private
    */
    private $userId;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }
    
/* ----- Functions for changeing tables ----- */

	/**
	* Method to dynamically switch tables
	*
	* @access private
	* @param string $table: The name of the table
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _changeTable($table)
	{
		try{
			parent::init($table);
			return TRUE;
		}catch(customException $e){
			customException::cleanUp();
			return FALSE;
		}
	}
	
	/**
	* Method to set the wiki table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setWiki()
	{
        return $this->_changeTable('tbl_wiki2_wikis');
    }

	/**
	* Method to set the pages table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setPages()
	{
        return $this->_changeTable('tbl_wiki2_pages');
    }

	/**
	* Method to set the rating table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setRating()
	{
        return $this->_changeTable('tbl_wiki2_rating');
    }

	/**
	* Method to set the watch table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setWatch()
	{
        return $this->_changeTable('tbl_wiki2_watch');
    }

/* ----- Functions for tbl_wiki2_pages ----- */

    /**
    * Method to add a wiki page
    *
    * @access public
    * @param array $data: The array of wiki page data
    * @return string|bool $pageId: The wiki page id |False on failure
    **/
    public function addPage($data)
    {
        if(!is_array($data) || empty($data)){
            return FALSE;
        }
        
        $data['page_version'] = $this->getVersion($data['page_name']);
        $data['page_status'] = 1;
        $data['page_author_id'] = $this->userId;
        $data['date_created'] = date('Y-m-d H:i:s');
        $pageId = $this->insert($data);
        return $pageId;                  
    }
    
    /**
    * Method to get all latest (current version) wiki pages
    *
    * @access public
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getAllCurrentPages()
    {
        $this->_setPages();
        $sql = "WHERE (page_name, page_version)";
        $sql .= " IN (SELECT page_name, MAX(page_version)";
        $sql .= "     FROM tbl_wiki2_pages GROUP BY page_name)";
        $sql .= " AND page_status < 4";
        $sql .= " ORDER BY date_created DESC";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }    

    /**
    * Method to get all wiki pages by name
    *
    * @access public
    * @param string $name: The wiki page name
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getPagesByName($name)
    {
        $this->_setPages();        
        $sql = "WHERE page_name = '".$name."'";
        $sql .= " ORDER BY page_version DESC";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }    

    /**
    * Method to get a wiki page by name
    *
    * @access public
    * @param string $name: The wiki page name
    * @param integer $version: The wiki page version
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getPage($name, $version = NULL)
    {
        $this->_setPages();        
        $sql = "WHERE page_name = '".$name."'";
        if(!empty($version)){
            $sql .= " AND page_version = '".$version."'";
        }
        $sql .= " ORDER BY page_version DESC";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }    

    /**
    * Method to get a wiki page by id
    *
    * @access public
    * @param string $id: The wiki page id
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getPageById($id)
    {
        $this->_setPages();        
        $sql = "WHERE id = '".$id."'";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }    

    /**
    * Method to get a main page
    *
    * @access public
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getMainPage()
    {
        $this->_setPages();        
        $sql = "WHERE main_page = '1'";
        $sql .= " ORDER BY page_version DESC";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }    

    /**
    * Method to get recently added wiki pages
    *
    * @access public
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getRecentlyAdded()
    {
        $this->_setPages(); 
        $sql = "WHERE page_version = 1";
        $sql .= " AND page_status < 5";
        $sql .= " ORDER BY date_created DESC LIMIT 5";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }    

    /**
    * Method to get recently updated wiki pages
    *
    * @access public
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getRecentlyUpdated()
    {
        $this->_setPages();
        $sql = "WHERE (page_name, page_version)";
        $sql .= " IN (SELECT page_name, MAX(page_version)";
        $sql .= "     FROM tbl_wiki2_pages GROUP BY page_name)";
        $sql .= " AND page_version > 1";
        $sql .= " AND page_status < 5";
        $sql .= " ORDER BY date_created DESC LIMIT 5";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }    

    /**
    * Method to edit a field
    *
    * @access public
    * @param string $id: The wiki page to edit
    * @param string $field: The field to be edited
    * @param string $value: The new value of the field
    * @return void
    */
    public function editPageField($id, $field, $name)
    {
        $this->_setPages();        
        $this->update('id', $id, array($field => $value));
    }    
    
    /**
    * Method to mark a wiki page as deleted
    *
    * @access public
    * @param string $name: The name of the page
    * @return void
    */
    public function deletePage($name)
    {
        $this->_setPages();        
        $this->update('page_name', $name, array(
            'page_status' => 6,
        ));
    }
    
    /**
    * Method to restore a wiki page
    * 
    * @access public
    * @param string $name: The page name to restore
    * @param integer $version: The page version to restore
    * @return string $pageId: The id of the resored page
    */
    public function restorePage($name, $version)
    {
        $array = array(
            'num' => $version,
        );
        $restoreLabel = $this->objLanguage->code2Txt('mod_wiki2_restoration', 'wiki2', $array);
        
        $this->_setPages();
        $sql = "WHERE page_name = '".$name."'";
        $sql .= " AND page_version > '".$version."'";
        $overWrittenPages = $this->getAll($sql);
        if(!empty($overWrittenPages)){
            foreach($overWrittenPages as $page){
                $this->update('id', $page['id'], array(
                    'page_status' => 4,
                ));
            }
        }

        $pageToRestore = $this->getPage($name, $version);
        $temp = array_pop($pageToRestore);
        $temp = array_shift($pageToRestore);
        $pageToRestore['page_version'] = $this->getVersion($name);
        $pageToRestore['version_comment'] = $restoreLabel;
        $pageToRestore['page_status'] = 2;
        $pageToRestore['page_author_id'] = $this->userId;
        $pageToRestore['date_created'] = date('Y-m-d H:i:s');
        $pageId = $this->insert($pageToRestore);
        
        return $pageId;        
    }
    
    /**
    * Method to restore a wiki page
    * 
    * @access public
    * @param string $name: The page name to restore
    * @param integer $version: The page version to restore
    * @return string $pageId: The id of the resored page
    */
    public function reinstatePage($name, $version)
    {
        $array = array(
            'num' => $version,
        );
        $reinstateLabel = $this->objLanguage->code2Txt('mod_wiki2_reinstatement', 'wiki2', $array);

        $this->_setPages();
        $sql = "WHERE page_name = '".$name."'";
        $archivedPages = $this->getAll($sql);
        if(!empty($archivedPages)){
            foreach($archivedPages as $page){
                $this->update('id', $page['id'], array(
                    'page_status' => 5,
                ));
            }
        }

        $pageToRestore = $this->getPage($name, $version);
        $temp = array_pop($pageToRestore);
        $temp = array_shift($pageToRestore);
        $pageToRestore['page_version'] = $this->getVersion($name);
        $pageToRestore['version_comment'] = $reinstateLabel;
        $pageToRestore['page_status'] = 3;
        $pageToRestore['page_author_id'] = $this->userId;
        $pageToRestore['date_created'] = date('Y-m-d H:i:s');
        $pageId = $this->insert($pageToRestore);
        
        return $pageId;        
    }
    
    /** 
    * Method to get the page version for entry
    *
    * @access private
    * @param string $name: The name of the page
    * @return integer $version: The page version
    */
    private function getVersion($name)
    {
        $pages = $this->getPagesByName($name);
        if(!empty($pages)){
            $version = count($pages) + 1;
        }else{
            $version = 1;
        }
        return $version;
    }
    
    /**
    * Method to search the wiki
    *
    * @access public
    * @param string $column: The column(s) to search
    * @param string $value: The value to search for
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function searchWiki($column, $value)
    {
        $this->_setPages();
        $smashValue = str_replace(' ', '', ucwords($value));
        $sql = "WHERE (page_name, page_version)";
        $sql .= " IN (SELECT page_name, MAX(page_version)";
        $sql .= "     FROM tbl_wiki2_pages GROUP BY page_name)";
        $sql .= " AND page_status < 4";
        if($column == 'both'){
            $sql .= " AND (page_name = '".$smashValue."'";
            $sql .= " OR page_content LIKE '%".$value."%')";
        }elseif($column == 'page_name'){
            $sql .= " AND ".$column." = '".$smashValue."'";
        }else{
            $sql .= " AND LOWER(".$column.") LIKE '%".strtolower($value)."%'";
        }
        $sql .= " ORDER BY date_created DESC";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }
    
    /**
    * Method to get a list of all authors and their page count
    *
    * @access public
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getAuthors()
    {
        $this->_setPages();
        $sql = "SELECT *, COUNT(page_author_id) AS cnt FROM tbl_wiki2_pages";
        $sql .= " GROUP BY page_author_id";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }
    
    /**
    * Method to get a list of articles by an author
    *
    * @access public
    * @param string $author: The id of the author
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getAuthorArticles($author)
    {
        $this->_setPages();
        $sql = "WHERE (page_name, page_version)";
        $sql .= " IN (SELECT page_name, MAX(page_version)";
        $sql .= "     FROM tbl_wiki2_pages GROUP BY page_name)";
        $sql .= " AND page_status < 4";
        $sql .= " AND page_author_id = '".$author."'";
        $sql .= " ORDER BY date_created DESC";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }    

/* ----- Functions for tbl_wiki2_pages ----- */
    
    /**
    * Method to add a wiki page rating
    *
    * @access public
    * @param string $wikiId: The id of the wiki the page is for
    * @param string $name: The wiki page name
    * @param integer $rating: The rating the page recieved
    * @return string|bool $ratingId: The wiki page rating id |False on failure
    */
    public function addRating($name, $rating)
    {
        $this->_setRating();
        $fields = array();
        $fields['wiki_id'] = 'init_1';
        $fields['page_name'] = $name;
        $fields['page_rating'] = $rating;
        $fields['creator_id'] = $this->userId;
        
        return $ratingId = $this->insert($fields);
    }

    /**
    * Method to get a wiki page rating
    *
    * @access public
    * @param string $wikiId: The id of the wiki the page is for
    * @param string $name: The wiki page name
    * @return array $array: The array of wiki page rating and votes
    */
    public function getRating($name)
    {
        $this->_setRating();
        $sql = "WHERE wiki_id = 'init_1'";
        $sql .= " AND page_name = '".$name."'";
        $data = $this->getAll($sql);
        if(!empty($data)){
            $vote = 0;
            foreach($data as $line){
                $vote = $vote + $line['page_rating'];
            }
            $votes = count($data);
            if($vote > 0){
                $rating = ceil($vote / $votes);
            }else{
                $rating = 0;
            }
            $array = array(
                'rating' => $rating,
                'votes' => $votes,
            );
            return $array;
        }
        $array = array(
            'rating' => 0,
            'votes' => 0,
        );
        return $array;
    }
    
    /**
    * Method to check if a user has rated a page
    *
    * @access public
    * @param string $wikiId: The id of the wiki the page is for
    * @param string $name: The wiki page name
    * @return boolean $rated: TRUE if the user has rated the page | FALSE if not
    */
    public function wasRated($name)
    {
        $this->_setRating();
        $sql = "WHERE wiki_id = 'init_1'";
        $sql .= " AND page_name = '".$name."'";
        $sql .= " AND creator_id = '".$this->userId."'";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return TRUE;
        }
        return FALSE;
    }

    /**
    * Method to add a wiki page ranking
    *
    * @access public
    * @param string $wikiId: The id of the wiki the page is for
    * @param string $name: The wiki page name
    * @return float $ranking: The wiki page ranking
    */
    public function getRanking()
    {
        $this->_setRating();
        $sql = "SELECT *, COUNT(page_name) AS cnt, SUM(page_rating) AS tot";
        $sql .= " FROM tbl_wiki2_rating ";
        $sql .= " WHERE wiki_id = 'init_1'";
        $sql .= " GROUP BY page_name";
        $sql .= " ORDER BY tot DESC, cnt DESC";
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

/* ----- Functions for tbl_wiki2_watch ----- */

    /**
    * Method to add a page to your watchlist
    *
    * @access public
    * @param string $wikiId: The id of the wiki the page falls under
    * @param string $name: The name of the wiki page
    * @return string|bool $watchId: The wiki page watch id |False on failure
    */
    public function addWatch($name)
    {
        $watch = $this->getUserPageWatch($name);
        if(!empty($watch)){
            $watchId = $watch['id'];
        }else{
            $this->_setWatch();
            $fields['wiki_id'] = 'init_1';
            $fields['page_name'] = $name;
            $fields['creator_id'] = $this->userId;
        
            $watchId = $this->insert($fields);                
        }
        return $watchId;
    }

    /**
    * Method to get a page from your watchlist
    *
    * @access public
    * @param string $wikiId: The id of the wiki the page falls under
    * @param string $name: The name of the wiki page
    * @param string $userId: The id of the user with a page watch
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getUserPageWatch($name, $userId = NULL)
    {
        $userId = isset($userId) ? $userId : $this->userId;
        
        $this->_setWatch();
        $sql = "WHERE wiki_id = 'init_1'";
        $sql .= " AND page_name = '".$name."'";
        $sql .= " AND creator_id = '".$userId."'";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to delete a page from your watchlist
    *
    * @access public
    * @param string $id: The id of the watch to delete
    * @return void
    */
    public function deleteWatchById($id)
    {
        $this->_setWatch();
        $this->delete('id', $id);
    }

    /**
    * Method to delete a page from your watchlist
    *
    * @access public
    * @param string $name: The name of the watch to delete
    * @param string $userId: The id of the user who has a page watch
    * @return void
    */
    public function deleteWatchByName($name, $userId = NULL)
    {
        $userId = isset($userId) ? $userId : $this->userId;
        
        $watch = $this->getWatch($name, $userId);
        if(!empty($watch)){
            $this->_setWatch();
            $this->delete('id', $watch['id']);
        }
    }

    /**
    * Method to get pages you are watching
    *
    * @access public
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getAllUserWatches()
    {
        $this->_setWatch();
        $sql = "WHERE creator_id = '".$this->userId."'";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to get all watchers of a page
    *
    * @access public
    * @param string $name: The name of the page
    * @return array|bool $data: Wiki page data on success | False on failure
    */
    public function getPageWatches($name)
    {
        $this->_setWatch();
        $sql = "WHERE wiki_id = 'init_1'";
        $sql .= " AND page_name = '".$name."'";
        $data = $this->getAll($sql);
        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }
}
?>