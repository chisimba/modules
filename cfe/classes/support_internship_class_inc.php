
<?php

/**
 * This is a find your venture class for CfE website.
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


class support_internship extends object
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
    * @access private.
    */

	private function buildBody()
	{
                $right_col = $this->newObject('support_right_column', 'cfe');
	
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		$this->pageContent->setLeftColumnContent($this->buildContent());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($right_col->show());
	}

    /**
     * This method contains the content that goes to the left column of find your venture page.
     * @return string
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="advPage"><h3> INTERNSHIP  </h3>

                <div id="advPageCont"> <h4> Internship programme</h4> 





</div>
</div>';

		//return the content
		return $content;
	}

    /**
     * A method to show the two colums layout with the contents in both columns
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
