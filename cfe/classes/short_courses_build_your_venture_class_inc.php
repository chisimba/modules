
<?php

/**
 * This is a build your venture class for CfE website.
 * 
 * PHP version 5
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

/*Contains the content of build your venture course ofered by CfE*/
class short_courses_build_your_venture extends object
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
     * A method to to divide the page body into two columns and calls the content of each column.
     *
     * @access public.
     */

	public function buildBody()
	{
		//Create an object of right column content of the short courses pages                
		$short_courses_links = $this->newObject('short_courses_right_column_content', 'cfe');
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		$this->pageContent->setLeftColumnContent($this->buildContent());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($short_courses_links->show());	}

    /**
     * This method contains the content that goes to the left column of build your venture page.
     * @return string
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="shortCoursesCoursesh3"><h3> BUILD YOUR VENTURE  </h3>
<div id="shortCoursesCoursesp"><p>Certificate of attendance in new venture development</p></div></div>
<div id="shortCoursesCourses">

<p><b>Did you think you would be further ahead by now?</b> Your business might be up and running, but you probably find yourself dealing with challenges you never imagined when you started. Now is the time to sharpen your skills and refresh your thinking so that when times get tough, you will be ready to take your business from shaky start-up to seriously sustainable.
</p>

<p> The Centre for Entrepreneurship at Wits Business School is dedicated to nurturing entrepreneurship education, support and research in Africa, with the goal of becoming the centre of excellence for entrepreneurial development on the continent. </p>

<h4>Programme objectives</h4>

<p>The overall objective of the Build your Venture programme is to:</p><br>
<lu>
<li>Develop the knowledge and skills of entrepreneurs whose businesses are still in the early stages of development to enable them to achieve sustainability</li>
<li>Prepare individuals to apply entrepreneurial thinking within an organisation by developing a balance of appropriate skills and emotional competencies.</li>
</lu>
<p>By the end of the course, entrepreneurs participating in Build Your Venture will:</p>
<br>
<lu>
<li>Know how to build a business on the basis of their own strengths</li>
<li>Understand how to apply principles of creativity and innovation in an existing business</li>
<li>Learn new skills to help refine the business model for future development</li>
<li>Develop their ability to choose the right business partners and manage business relationships</li>
<li>Have acquired additional people management skills and leadership competencies</li>
<li>Know how to apply essential business principles to run a new business successfully</li>
<li>Build a valuable network of other entrepreneurs and resource holders.</li>
</lu>
 
<p>These objectives will be met through class discussion and interaction with other participant entrepreneurs. </p>
<p>Programme graduates will have access to a range of advisory and support services offered by the Centre for Entrepreneurship once they have completed the programme.</p>

<h4>Who should attend?</h4>

<p>Entrepreneurs from all disciplines who have been running a business for between one and three years and now wish to develop beyond the start-up stage.</p>

<h4>Programme structure</h4>

<p>The programme will be offered in modules, involving a minimum of 60 contact hours, with additional independent study, assignments and fieldwork during the programme. Lectures and class discussions will be accompanied by developing practical skills, and the programme will be enriched through case studies, guest entrepreneurs, individual assignments and syndicate-based action learning projects.</p>

<h4>Programme content</h4>

<h4><i>1. Being entrepreneurial</i></h4>
<p>This module considers the nature of the new venture development task from the perspective of the individual entrepreneur. Participants examine the desirable and acquirable attributes of the lead entrepreneur and spend time exploring relevant personal strengths and 

how best to apply them to the business. A focus of the module is how entrepreneurs inspire others through metaphor and story-telling as mechanisms for attracting support and resources to the developing business. </p>

<h4><i>2. Strategy for new businesses</i></h4>
<p>Participants learn how to model the business and gain insight into the way in which the existing business model can be refined to achieve more robust sustainability. The module reconsiders marketing the new venture, especially in the context of technologies available to maximise marketing effectiveness. Special attention is paid to the innovation imperative, and how entrepreneurs can apply innovative thinking to business strategy and enhanced operational effectiveness. </p>

<h4><i>3. Developing partnerships </i></h4>

<p>Few new businesses can survive without a series of powerful strategic partnerships that act to enhance the entrepreneur’s knowledge base and offer access to scarce resources. This module emphasises methods for choosing the right business partners as well as offering insights into the way in which partnerships can be managed over the longer term.</p>

<h4><i>4. Managing people </i></h4>

<p>In South Africa, competition for skilled people is fierce, and even more so for the new business. This module explores mechanisms for recruiting, selecting, motivating and remunerating employees in these difficult conditions. Particular attention is paid to managing employee performance when job descriptions are vague or non-existent and the lead entrepreneur’s time is constrained by the need to fulfil multiple roles.
</p>

<h4><i>5. Improving operations</i></h4>
<p>This module focuses on improving operational efficiency and effectiveness to lower cost and increase quality. Different production systems are examined and production planning techniques discussed, as are quality assurance and control methods. Real life case studies are examined in detail during the module.</p>

<h4><i>6. Financial management</i></h4>
<p>This module reviews the financial management of the new business, and offers the entrepreneur new insights into analysing financial statements. Methods of assessing the viability of new projects are considered and different approaches to managing cash flow explored. Entrepreneurs also learn how to determine the capital requirements of a new project and consider the relative strengths and weaknesses of different sources of finance.
</p>

<h4><i>7. Planning for sustainability</i></h4>
<p>Business planning takes on a different role in the established business. No longer is the primary concern assessing the business’ viability, and presenting this to key resource holders. Instead, the business plan becomes a tool for active management against targeted objectives.</p>

<h4>Assessment</h4>
<p>Competence will be assessed through individual assignments, syndicate assignments including the development of a business plan focused on a specific industry, fieldwork and an action learning project.</p>

<h4>Applications</h4>
<p>An application form is available from the Centre for Entrepreneurship reception. Preference will be given to individuals with previous academic or professional qualifications from any discipline but this will not be a prerequisite if experience and motivational criteria are met.  Participants will be selected on the basis of their entrepreneurial experience. Sponsorship or motivation by an employer or recommending body will be considered in selecting participants.
</p>

<h4>Course fees</h4>
<p>The course fee includes all lectures, notes, course materials and refreshments provided during the programme. A limited number of bursaries are available for high-performing students from disadvantaged backgrounds.</p>

<h4>Enquiries</h4>
<p><i>Programme Manager</i></p>
<p>Sibongile Msimanga</p>
<p>Telephone: 	+2711 717 3833</p>
<p>Email: Sibongile.Msimanga@wits.ac.za</p>

</div>';

		//Return the content
		return $content;
	}

    /**
     * A method to show the two colums layout
     * 
     * @return string
     * @access public
     */
	public function show()
	{
		//return the two column layout page
		return $this->pageContent->show();
	}

}
?>
