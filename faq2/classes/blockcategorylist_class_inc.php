<?php
/**
* Block for the block_categorylist
*
* @package hivaidsforum
* @author Megan Watson
* @version 0.1
* @copyright (c) 2007 University of the Western Cape
*/

class block_categorylist extends object
{
    /**
    * @var the block title
    */
    public $title;
    
    /**
    * Constructor for the class
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language','language');
        $this->dbFaq2 = $this->getObject('dbfadcategories', 'faq2');
        
        //Set the title
        $this->title = $this->objLanguage->languageText('word_categories');
    }
    
    /**
    * The display method for the block
    */
    public function show()
	{
	    return $this->dbFaq2->showCategoryList('nobox', TRUE);
	}
}