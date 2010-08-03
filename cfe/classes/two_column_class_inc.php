
<?php

/**
 /* a class that divides the page into two columns
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


class two_column extends object
{
    /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		//$this->objShortCourses = $this->newObject('short_courses_content', 'cfe');
		//$this->objShortCoursesRight = $this->newObject('short_courses_content_right', 'cfe');

                $this->objResearch = $this->newObject('research_content','cfe');
                $this->objResearchLinks = $this->newObject('research_links','cfe');
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
		$short_courses_links = $this->newObject('short_courses_right_column_content', 'cfe');		
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		//$this->pageContent->setLeftColumnContent($this->objShortCourses->show());
		//write on the right column
		//$this->pageContent->setMiddleColumnContent($this->objShortCoursesRight->show());

                //write on the left column
		$this->pageContent->setLeftColumnContent($this->objResearch->show());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($short_courses_links->show());


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
