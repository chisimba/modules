<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
* A block to return the last 10 blog posts
*
* @author Megan Watson
* @version 0.1
* @copyright (c) UWC 2007
*
*/

class block_lastten extends object
{
    /**
    * @var string $title The title of the block
    */
    public $title;

    /**
     * @var display
     * Object to display the last ten posts box
     */
    public $display;

    /**
     * Blog operations class
     *
     * @var object
     */
    public $blogOps;

    public $objLanguage;

    /**
    * Standard init function to instantiate language and user objects
    * and create title
    */
    public function init()
    {
		$this->objLanguage = &$this->getObject('language', 'language');
    	$this->blogOps = &$this->getObject('blogops', 'blog');
    	$this->display = $this->blogOps->showLastTenPosts();
        $this->title= $this->objLanguage->languageText("mod_blog_block_lasttenposts", "blog");
    }

    /**
    * Standard block show method. 
    */
    public function show()
    {
        return $this->display;
    }

}
?>