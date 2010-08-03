
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


class support_mentoring extends object
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
		$content = '<div id="advPage"><h3> MENTORING & COACHING  </h3>

                <div id="advPageCont"> <h4> Mentoring and coaching programme</h4> 


<p>A mentoring programme has been established to assist entrepreneurs throughout the different stages of business development. Each qualifying entrepreneur is allocated a mentor, who guides and supports the entrepreneur on a one-to-one basis.</p>

<p>This mentorship function is performed by volunteers from established entrepreneurial/business support organisations and WBS and University Alumni Associations. A mentor database of contact details, skill base, characteristics and availability has been developed. Similarly, entrepreneurs invited to participate in the mentoring programme are required to submit details of their own, including specifying their needs with respect to a mentoring relationship. This information ensures that careful matching of mentor and entrepreneur in terms of experience, skills and personality can take place. </p> 

<p>Each mentor and protégé attends a mentoring training programme developed and implemented by the Leadership Development Unit at WBS, prior to being matched with an appropriate entrepreneur. </p>

<p>It is important to note that the mentor is NOT expected to provide financial support directly to the entrepreneur or to invest financially in the entrepreneur’s business during the course of the mentoring relationship, since the CfE believes this might lead to conflicts of interest as the relationship (and the entrepreneur’s business) develops.</p>

<p>The mentor is expected to commit to the relationship for a period of one year at a time. During this time, the mentor and the entrepreneur will be expected to meet at least once a month for a structured one hour face-to-face session, possibly supplemented by email and telephonic interaction. The mentor/entrepreneur contract will be for 10 such sessions over a 12 month period.</p>

<p>The coaching programme is run in conjunction with the WBS Business and Executive Coaching Certificate (BECC) run by the WBS Leadership Development Unit. Participants on that programme are allocated to CfE entrepreneurs for a series of five one hour professional business and personal coaching sessions.</p>

<p>At the commencement of the first session, the coach administers a series of assessments and then together with the CfE entrepreneur, sets the agenda for the remainder of the series. Professional coaching techniques and tools are used to assist the entrepreneur in solving business problems during the series. During this process, coaches are supervised by members of the BECC faculty.</p>

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
