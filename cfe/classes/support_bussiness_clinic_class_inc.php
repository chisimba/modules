
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


class support_bussiness_clinic extends object
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
                $short_courses_links = $this->newObject('support_right_column', 'cfe');
	
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		$this->pageContent->setLeftColumnContent($this->buildContent());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($short_courses_links->show());
	}

    /**
     * This method contains the content that goes to the left column of find your venture page.
     * @return string
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="advPage"><h3> BUSSINESS CLINIC  </h3>

                <div id="advPageCont"> <h4> ‘Business Clinic’ programme</h4> 


<p>Entrepreneurs in the CfE ‘ecosystem’ require access to a range of experts in different disciplines and/or industries, and this expertise may be beyond the scope of either the expertise of WBS/CfE staff or the individual entrepreneur’s mentor. </p>

<p>With this in mind, the Business Clinic programme ensures entrepreneurs have easy access to the expertise and advice they require, across the broadest possible range of business disciplines and industries. This is achieved through a quarterly series of events at which entrepreneurs are invited to consult with experts face to face for brief, focused personalised input</p>

<p>Experts, both online and face to face, are volunteers sourced from amongst WBS/CfE staff, alumni and MBA students, as well as consulting firms  and other entrepreneurial support organizations. </p>

<p>An online version of Business Clinic events will also be established. Entrepreneurs will post queries on a CfE web-site, which will then be answered by an appropriately qualified expert or another experienced entrepreneur in the CfE ecosystem. The response could be rated by the online community of entrepreneurs for its usefulness and archived for future consultation by other entrepreneurs.</p>

<p>The Business Clinic Programme operates on an ad hoc problem-specific basis, rather than involving the development of holistic longer-term relationships between advisors and mentors, as is the case in the Mentorship Programme. The Business Clinic is predominantly aimed at entrepreneurs already running businesses.</p>


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
