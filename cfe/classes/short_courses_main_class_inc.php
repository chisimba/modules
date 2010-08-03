
<?php

/**
 * This is the class of the main short courses page. It contains all the links to other pages
 * of short courses
 *
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}



class short_courses_main extends object
{
   /**
    * Description for public
    * @var    unknown
    * @access public
    */

    public $content;
   /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->loadClass('link', 'htmlelements');
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objShortCourses = $this->newObject('short_courses_content', 'cfe');

		//create a layout
		$this->buildBody();
        }
    /**
     * A method to to divide the page body into two columns 
     *
     * @access public.
     */
	private function buildBody()
	{		
		$short_courses_right = $this->newObject('short_courses_right_column_content', 'cfe');
		
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		$this->pageContent->setLeftColumnContent($this->objShortCourses->show());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($short_courses_right->show());
	}

    /**
     * A method to show the two colums layout
     * 
     * @return string $result The rendered object in HTML code
     * @access public
     */
	public function show()
	{
		//return the two column layout page
		return $this->pageContent->show();
	}

}
?>
