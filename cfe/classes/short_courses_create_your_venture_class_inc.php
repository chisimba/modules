
<?php
/**
 * This class contains the content of the left column of create your venture page
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


class short_courses_create_your_venture extends object
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
		//build the content
		$this->buildContent();
        }
    
	
    /**
     * This method returns the content of the left column in the create your venture page
     * @return string.
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="shortCoursesCoursesh3"><h3> CREATE YOUR VENTURE  </h3>
<div id="shortCoursesCoursesp"><p>Certificate of competence in entrepreneurship and new venture creation</p></div></div>
                    <div id="shortCoursesCourses">

<p><b>So you want to be an entrepreneur?</b> Starting your own business can be an exhilarating experience, but also one fraught with difficulty and frustration. Developing the right kind of skills before you create a business as well as knowing where to go for help once you have, can make the difference between start-up and shut-down.</p>

<p>The Centre for Entrepreneurship at Wits Business School is dedicated to nurturing entrepreneurship education, support and research in Africa, with the goal of becoming the centre of excellence for entrepreneurial development on the continent.</p>

<h4>Programme objectives</h4>

<p>The overall objective of the Create your Business programme is to:</p><br>
<lu>
<li>Equip those with entrepreneurial desire to become ‘job creators’ rather than ‘job seekers’</li>
<li>Prepare individuals to start a new venture or apply entrepreneurial thinking in an organisation by developing a balance of appropriate skills and emotional competencies.</li>
</lu>
<p>By the end of the course, new entrepreneurs participating in Create Your Venture will:</p>
<br>
<lu>
<li>Know how to build a business on the basis of their own strengths</li>
<li>Understand how to apply creativity and innovation in starting a new business or within an existing business</li>
<li>Know how to apply essential business principles to establish and run a new business successfully</li>
<li>Have acquired people management skills and leadership competencies</li>
<li>Built a valuable network of other entrepreneurs and resource holders</li>
<li>Learned ways of dealing with change and planning for the future</li>
<li>Have experienced ‘learning by doing’ through fieldwork opportunities to set up and run a new enterprise.</li>
</lu>
 
<p>Programme graduates will have access to a range of advisory and support services offered by the Centre for Entrepreneurship once they have completed the programme</p>

<h4>Who should attend?</h4>

<p>Potential entrepreneurs from all disciplines who want to start their own business or enhance their entrepreneurial approach in their own organisation will gain most from the programme, as will new graduates starting their careers or looking for employment. Individuals who believe innovation will shape their future careers as employees will also benefit.</p>

<h4>Programme structure</h4>

<p>The programme will be offered in modules, involving a minimum of 120 contact hours, with independent study, assignments and fieldwork during the programme. Lectures and class discussions will be accompanied by the development of practical skills for innovation, negotiation, problem-solving and decision-making, leadership and strategy formulation. The programme will be enriched through case studies, guest entrepreneurs, individual assignments and syndicate-based action learning projects.</p>

<h4>Programme content</h4>

<h4><i>1. Introduction to entrepreneurship and innovation</i></h4>
This module offers insights into the entrepreneurial mindset by examining case studies of real-life entrepreneurial success stories and profiling the attributes of a successful entrepreneur. Participants learn techniques such as full-brain thinking to enhance their creativity and increase their understanding of the process of innovation. 
A special focus of the module is the identification and evaluation of high-potential opportunities.

<h4><i>2. Business essentials for entrepreneurs</i></h4>
At start-up, business knowledge is an essential requirement for success. The entrepreneur must play multiple roles and needs a broad base of skills to set up and run the business effectively. This module equips participants to develop a strategy and business model for start-up, based on thorough analysis of customer needs and the competitive environment. Participants are introduced to methods for low-cost, high-technology marketing of the new business, with emphasis on developing and maintaining customer relationships. New methods of managing operations and technology are considered along with tried and tested processes. Few start-ups can succeed without the entrepreneur having a deep understanding of financial issues, so the module pays particular attention to topics such as: drawing up the budget; accounting and financial management; risk management and raising finance. Need-to-know business and labour law and corporate governance complete the module.

<h4><i>3. People management and leadership</i></h4>
Self-awareness and personal mastery are important precursors to establishing the right start-up team. Entrepreneurs need skills in group dynamics, diversity management and talent management to find (and keep) the right people. This module explores entrepreneurial leadership and offers systems and techniques for managing the performance of others. The effective entrepreneur must also negotiate with and manage personal and business relationships with a wide network of stakeholders, so negotiation and networking skills are incorporated in this module.

<h4><i>4. The future business</i></h4>

<p>By definition, creating a business means thinking about and planning for the future. This module considers trends in the business, operating and technology environment and introduces participants to advanced techniques for leading in a rapidly changing world. Particular attention is paid to formulating the business plan and applying performance metrics and benchmarking to the venture creation process. These help the entrepreneur detect early signs of problems typically encountered in new business development and implement appropriate remedial or turnaround strategies.</p>

<h4>Workload</h4>
<p>In addition to part-time work and syndicate meetings, students will be expected to complete assignments and prepare for classroom sessions as required by the lecturer or course leader. Students will also be required to devote additional hours to syndicate work, assignments and classroom presentations.</p>

<h4>Assessment</h4>
<p>Competence will be assessed through exams, individual assignments, syndicate assignments including the development of a business plan focused on a specific industry, fieldwork and an action learning project.</p>

<h4>Applications</h4>
<p>An application form is available from the Centre for Entrepreneurship reception. Preference will be given to individuals with previous academic or professional qualifications from any discipline but this will not be a prerequisite if experience and motivational criteria are met.  Participants will be selected on the basis of their entrepreneurial interest and employment status. Sponsorship or motivation by an employer or recommending body will be considered in selecting participants.</p>

<h4>Course fees</h4>
<p>The course fee includes all lectures, notes, course materials and refreshments provided during the programme. A limited number of bursaries are available for high-performing students from disadvantaged backgrounds.</p>

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
     * A method to show the left column content of create your venture page
     *
     * @return string
     * @access public
     */
	public function show()
	{
		return $this->buildContent();
	}

}
?>
