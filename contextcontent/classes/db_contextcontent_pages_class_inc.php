<?php
/**
* class that contains the content of pages in the contextcontent module
* @author Tohir Solomons
*/
class db_contextcontent_pages extends dbtable
{

    /**
	* Constructor
	*/
	public function init()
    {
        parent::init('tbl_contextcontent_pages');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    /**
	* Method to add a Page
	*
	* @param string $titleId Record Id of the Title
	* @param string $menutitle Title of the Page
	* @param string $content Content of the Page
	* @param string $language Language of the Page
	* @param string $headerScript Header JS of the Page
	* @return boolean Result of Insert
	*/
    public function addPage($titleId, $menutitle, $content, $language, $headerScript)
    {
        if (!$this->checkPageExists($titleId, $language)) {
            return $this->insert(array(
                    'titleid' => $titleId,
                    'menutitle' => $menutitle,
                    'pagecontent' => $content,
                    'headerscripts' => $headerScript,
                    'language' => $language,
                    'original' => 'Y',
                    'creatorid' => $this->objUser->userId(),
                    'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
                ));
        } else {
            return FALSE;
        }
    }
    
    /**
	* Method to Check whether a Page exists for a title
	*
	* @param string $titleId Record Id of the Title
	* @param string $language Requested language
	* @return boolean
	*/
    public function checkPageExists($titleId, $language)
    {
        $recordCount = $this->getRecordCount('WHERE titleid=\''.$titleId.'\' AND language=\''.$language.'\'');
        
        if ($recordCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /**
    * Method to Update the Content of a Page
    *
    * @param string id Record Id of the Page
    * @param string $menutitle Title of the Page
    * @param string $content Content of the Page
    * @param string $headerScript Header JS of the Page
    * @return boolean
     */
    public function updatePage($id, $title, $content, $headerScripts)
    {
        return $this->update('id', $id, array(
        		'menutitle'=>addslashes(stripslashes($title)), 
        		'pagecontent'=>addslashes(stripslashes($content)), 
        		'headerscripts'=>addslashes(stripslashes($headerScripts)),
                'creatorid' => $this->objUser->userId(),
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
    		));
    }

}


?>