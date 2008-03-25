<?php
/**
* @package forum
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* The forum block class displays the last post
* @author Megan Watson
*/

class dynamicblocks_faq extends object
{
    /**
    * Constructor
    */
    function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objCategory = $this->getObject('dbfaqcategories');
        $this->objEntries = $this->getObject('dbfaqentries');
        
        $this->loadClass('link', 'htmlelements');
    }
    
    function renderCategory($id)
    {
        return $id;
    }
}
?>