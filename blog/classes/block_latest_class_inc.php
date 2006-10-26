<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
* A block to return the last blog entry
*
* @author Paul Scott based on a block by Derek Keats

*
* $Id$
*
*/

class block_latest extends object
{
    /**
    * @var string $title The title of the block
    */
    public $title;

    /**
    * @var object $objLastBlog String to hold the lastblog object
    */
    public $objLastBlog;

    /**
     * @var quickBlog
     * Object to display the quick blog box
     */
    public $quickBlog;

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
    	$this->objUser = &$this->getObject('user', 'security');
    	$userid = $this->objUser->userid();
    	$this->blogOps = &$this->getObject('blogops', 'blog');
    	$this->quickBlog = $this->blogOps->quickPost($userid, FALSE);
    	$this->objLastBlog = NULL; //& $this->getObject('getlastentry', 'blog');
        $this->title= $this->objLanguage->languageText("mod_blog_block_quickpost", "blog");
    }

    /**
    * Standard block show method. It builds the output based
    * on data obtained via the getlast class
    */
    public function show()
    {
        return $this->quickBlog;
    }

}
?>