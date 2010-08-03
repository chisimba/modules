
<?php

/**
 * A create your venture class for CfE website. Divides the page into two columns and
 * call the right and the left columns content classes
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


class short_courses_create_your_venture_body extends object
{
   /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objCreateYourVenture = $this->newObject('short_courses_create_your_venture', 'cfe');

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
		$this->pageContent->setLeftColumnContent($this->objCreateYourVenture->show());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($short_courses_links->show());//$this->objShortCoursesRight->show());
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
