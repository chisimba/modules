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
    * Standard init function to instantiate language and user objects
    * and create title
    */
    public function init()
    {
        $this->objLastBlog = NULL; //& $this->getObject('getlastentry', 'blog');
        $this->title=NULL; //$this->objLastBlog->showTitle();
    }

    /**
    * Standard block show method. It builds the output based
    * on data obtained via the getlast class
    */
    public function show()
    {
        return "Blog"; //$this->objLastBlog->show();
    }

}
?>