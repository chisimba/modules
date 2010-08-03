
<?php

/**
 *  This is a grow your venture class for CfE website
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


class short_courses_plan_your_venture extends object
{
    /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objCreateYourVenture = $this->newObject('short_courses_create_your_venture', 'cfe');
		//$this->objShortCoursesRight = $this->newObject('short_courses_content_right', 'cfe');

		//create a layout
		$this->buildBody();
        }
   /**
    * A method to divide the page into two columns
    *
    * @access private.
    */
	private function buildBody()
	{
                $short_courses_links = $this->newObject('short_courses_right_column_content', 'cfe');
	
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		$this->pageContent->setLeftColumnContent($this->buildContent());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($short_courses_links->show());//$this->objShortCoursesRight->show());
	}

    /**
     * This method contains the content that goes to the left column of plan your venture page.
     * @return string.
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="shortCoursesCoursesh3"><h3> PLAN YOUR VENTURE  </h3>
<div id="shortCoursesCoursesp"><p>Certificate of attendance in business planning for entrepreneurs</p></div></div>
<div id="shortCoursesCourses">

<p><b>Struggling to put together a business plan?</b> It is often said that the process of business planning is much more important than the plan itself because the process gives you new insights into your business’s future direction and operations. But knowing how to start a plan and finding time to finish it can be difficult when you are already dealing with the day-to-day challenges of running a business. This practical hands-on workshop will give the prepared entrepreneur the tools and techniques needed to pull together a credible business plan in just six action-packed days.</p>

<p>The Centre for Entrepreneurship at Wits Business School is dedicated to nurturing entrepreneurship education, support and research in Africa, with the goal of becoming the centre of excellence for entrepreneurial development on the continent. </p>

<h4>Programme objectives</h4>

<p>The overall objective of the Plan your Venture programme is to provide:</p><br>
<lu>
<li>The knowledge and skills required to develop a feasible and credible business plan</li>
<li>Practical tools and techniques to assist entrepreneurs in the business planning process</li>
<li>Expert evaluation and feedback on the quality of the completed business plan.</li>
</lu>
<p>By the end of the course, entrepreneurs participating in Plan Your Venture will:</p>
<br>
<lu>
<li>Understand the business planning process in detail</li>
<li>Know how to analyse the competitive environment in which they operate</li>
<li>Define the business model and understand the economics of the business</li>
<li>Develop a marketing, operations, HR and financial plan</li>
<li>Experience ‘learn by doing’ through developing and refining a business plan in ‘real time’</li>
<li>Build a valuable network of other entrepreneurs and resource holders.</li>
</lu>
 
<p>These objectives will be met through a real-time planning process that demonstrates the feasibility of the business, refines the business model, and considers the requirements of every aspect of the business plan, including preparation of relevant financial statements. Class discussion and interaction with other participant entrepreneurs is a characteristic feature of the programme. </p>
<p>Programme graduates will have access to a range of advisory and support services offered by the Centre for Entrepreneurship once they have completed the programme.</p>

<h4>Who should attend?</h4>

<p>Entrepreneurs from all disciplines who have been running a business for one to three years and need to compile a business plan to refine their strategy and attract new resources should attend this workshop. Entrepreneurs who are still refining their business ideas should consider attending the Find your Venture workshop prior to attempting a business plan. </p>

<h4>Programme structure</h4>

<p>The programme will be offered in a single week, involving a minimum of 36 contact hours, with additional fieldwork before and during the programme being required.</p>
<p>The programme will be enriched through case studies, guest entrepreneurs, and evaluation of the completed business plan by an expert panel within a week after the workshop.</p>

<h4>Programme content</h4>

<h4><i>Day one: Preparing to plan</i></h4>
<p>Participants spend the day developing and understanding the planning process, exploring problems associated with planning and how to overcome them. The preparatory work undertaken by each entrepreneur is examined from the perspective of how it fits into the overall planning template. By the end of the first day, participants are ready to plan.</p>

<h4><i>Day two: Analysing the market</i></h4>
<p>The emphasis is on understanding the environment in which the business is operating. Participants consider regulatory, economic, social and technological issues that have the potential to influence the business’s future operations and learn ways to collect information about competitors and customers using practical market research techniques. By the end of day two, participants are ready to complete the SWOT analysis. </p>

<h4><i>Day three: Defining the business model </i></h4>
<p>Participants learn how to define and refine the business model. More importantly, participants develop a deep understanding of the economics of the business, particularly how to determine sales and market share forecasts, margins, profit potential and durability, costs and breakeven. By the end of the day, participants have completed the business model and have a clear understanding of the sustainability of the business.</p>

<h4><i>Day four: Strategy and marketing plan </i></h4>

<p>The emphasis is on how entrepreneurs craft strategy and apply scarce resources to marketing the business. Participants work on vision, mission and values as well as defining a strategic position for the business. Special attention is paid to target market definition and the use of the marketing mix. By the end of day four, entrepreneurs are ready to define the marketing plan.</p>

<h4><i>Day five: Operations and HR plan</i></h4>
<p>The focus is on the processes and policies that will enable the effective and efficient implementation of strategy. Participants also attend to human resource considerations, including assembly of the top management team and recruitment and retention of key staff members. By the end of day five, participants are ready to assemble the HR and operations plans. </p>

<h4><i>Day six: Financial plan</i></h4>

<p>The final day of the workshop begins with a review of the business model described earlier in the week and focuses on conducting key financial analyses such as revenue, expenditure and sensitivity analyses. By the end of day six, entrepreneurs are ready to assemble the financial plan.</p>

<p>Following the completion of the workshop, participants work with Centre for Entrepreneurship advisors to finalise their business plans prior to presenting to an evaluation panel.</p>


<h4>Assessment</h4>
<p>The business plan developed by participants will be assessed by an expert panel one week after completing the workshop. Entrepreneurs will be given detailed verbal and written feedback on the quality and appeal of every aspect of the business plan. An attendance certificate will be issued to each entrepreneur who has completed the programme.</p>

<h4>Applications</h4>
<p>An application form is available from the Centre for Entrepreneurship reception. Preference will be given to individuals with previous academic or professional qualifications from any discipline but this will not be a prerequisite if experience and motivational criteria are met. Sponsorship or motivation by a recommending body will be considered in selecting participants.</p>

<h4>Course fees</h4>
<p>The course fee includes all lectures, notes, course materials and refreshments provided during the programme. A limited number of subsidised places are available for high-performing students from disadvantaged backgrounds.</p>

<h4>Enquiries</h4>
<p><i>Programme Manager</i></p>
<p>Sibongile Msimanga</p>
<p>Telephone: 	+2711 717 3833</p>
<p>Email:	  Sibongile.Msimanga@wits.ac.za</p>

</div>';

		//return the content
		return $content;
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
