
<?php

/**
 *  This is a grow your venture class for CfE website.
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


class short_courses_start_your_venture extends object
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
     * This method contains the content that goes to the left column of grow your venture page.
     * @return string.
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="shortCoursesCoursesh3"><h3> START YOUR VENTURE  </h3>
<div id="shortCoursesCoursesp"><p>Certificate of attendance in new venture creation</p></div></div>
                    <div id="shortCoursesCourses">

<p><b>Exactly how do you start a business?</b> Sometimes, small businesses are started in a hurry, and the entrepreneur hasn’t always considered every aspect of setting up the new enterprise. In this situation, learning skills you can use right away, with other entrepreneurs who really understand what you’re going through, can help clarify your thinking and get you moving forward in the right direction. </p>

<p>The Centre for Entrepreneurship at Wits Business School is dedicated to nurturing entrepreneurship education, support and research in Africa, with the goal of becoming the centre of excellence for entrepreneurial development on the continent. </p>

<h4>Programme objectives</h4>

<p>The overall objective of the Start your Venture programme is to:</p><br>
<lu>
<li>Develop the knowledge and skills of entrepreneurs starting a business</li>
<li>Inspire and motivate those considering entrepreneurship as a career option.</li>
</lu>
<p>By the end of the course, entrepreneurs participating in Start Your Venture will:</p>
<br>
<lu>
<li>Know how to start a business on the basis of their personal strengths</li>
<li>Understand how to design creativity and innovation into the business blueprint</li>
<li>Learn how to analyse opportunity and assess feasibility</li>
<li>Be exposed to leading-edge strategies and approaches for maximising start-up effectiveness</li>
<li>Understand how entrepreneurs develop viable business models</li>
<li>Learn the essentials of business planning and presentation</li>
<li>Know how to gather the right kind of resources at least cost</li>
<li>Develop key skills for managing people and operations</li>
<li>Build a valuable network of other entrepreneurs and resource holders.</li>
</lu>
 
<p>Programme graduates will have access to a range of advisory and support services offered by the Centre for Entrepreneurship once they have completed the programme. These services include mentoring, coaching, peer support and networking programmes to assist qualifying entrepreneurs throughout the start-up process. </p>

<h4>Who should attend?</h4>

<p>Entrepreneurs from all disciplines who are starting a business, who have been running a business for less than one year or individuals considering entrepreneurship as a career option.</p>

<h4>Programme structure</h4>

<p>The programme will be offered in modules, involving a minimum of 60 contact hours, with additional independent study, assignments and fieldwork during the programme. Lectures and class discussions will be accompanied by developing practical skills, and the programme will be further enriched through case studies, guest entrepreneurs, individual assignments and syndicate-based action learning projects.</p>

<h4>Programme content</h4>
<p>The programme is offered in five basic modules, each of which deals with a distinctive part of the start-up process.</p>
<h4><i>1. You and your start-up</i></h4>
<p>This module considers the foundation of all new businesses – the personal characteristics of the entrepreneur. Participants will gain new insight into their own strengths and how to build on these during the start-up process. A 

special focus will be the creative process and how this translates into innovation for the business.</p>

<h4><i>2. Identifying and evaluating opportunity</i></h4>
<p>Opportunities can be shaded and slippery, hard to see and hard to take advantage of. This module offers practical techniques for identifying and evaluating business opportunity as well as exploring leading-edge thinking about strategy development for start-ups. Practical ideas for marketing the business within the context of limited resources and compressed time-frames are also examined.</p>

<h4><i>3. Devising the business plan</i></h4>
The business plan is a critical first step in starting a business – or is it? This module considers some of the myths around business planning for new businesses and presents the benefits of planning for the entrepreneur. Challenges posed by the business plan are described, as is the way in which the entrepreneur might best overcome them. Detailed examination of the contents of the ideal business plan is included, and methods of using the plan to manage the business are presented.  Participants gain experience in presenting their business plans to a critical audience and gain valuable feedback on the quality of the plans they present. 

<h4><i>4. Gathering resources </i></h4>

<p>Some say that gathering resources required for start-up is the most difficult stage of the venture creation process. What is required to turn your vision into reality? Where do you get it? How much will it cost? What happens when things go wrong? 

This module examines the information, financial, human and physical resources you need for start-up, with special emphasis on where and how to access these resources, approaches to evaluating resource quality and techniques for retaining resources at the lowest possible cost to the business. </p>

<h4><i>5. Managing the business</i></h4>
<p>Once start-up is under way, the entrepreneur becomes a manager – of information, money and people. This module focuses on techniques and processes for managing in the entrepreneurial context. Participants learn the basics of human resource, operations and financial management as well as being introduced to key project management concepts. 

Finally, participant entrepreneurs present their business plans to an expert panel and receive valuable feedback from panel and peers.</p>

<h4>Assessment</h4>
<p>Competence will be assessed through individual assignments, syndicate assignments including the development of a business plan focused on a specific industry, fieldwork and an action learning project.</p>

<h4>Applications</h4>
<p>An application form is available from the Centre for Entrepreneurship reception. Preference will be given to individuals with previous academic or professional qualifications from any discipline but this will not be a prerequisite if experience and motivational criteria are met.  Participants will be selected on the basis of their entrepreneurial experience. Sponsorship or motivation by an employer or recommending body will be considered in selecting participants.</p>

<h4>Course fees</h4>
<p>TCourse fee includes all lectures, notes, course materials and refreshments provided during the programme. A limited number of bursaries are available for high-performing students from disadvantaged backgrounds.</p>

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
