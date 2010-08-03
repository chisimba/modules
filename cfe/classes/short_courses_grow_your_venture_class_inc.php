
<?php

/**
 * This is a grow your venture class for CfE website.
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


class short_courses_grow_your_venture extends object
{
    /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objCreateYourVenture = $this->newObject('short_courses_create_your_venture', 'cfe');
 		$this->objDbUtils=$this->getObject("dbutil");

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
          $data=$this->objDbUtil->getNews("shortcourses");
          if(count($data) > 0){ 
            return  '<div id="growyourventure">'.$data[0][0].'</div>';

         }else{
             return "No content found";
         }
        }

/*
		$content = '<div id="shortCoursesCoursesh3"><h3> GROW YOUR VENTURE  </h3>
<div id="shortCoursesCoursesp"><p>Certificate of attendance in new venture growth</p></div></div>
                    <div id="shortCoursesCourses">

<p><b>Are you adequately prepared for growth?</b> For many companies, the time of greatest risk is when the founding CEO seeks to grow or scale the business. All too often, the founder’s ambition outstrips the capabilities and resources available to the developing venture. In this situation growth is not only constrained, but the very existence of the enterprise is threatened. Can you be certain your growth plans won’t be the death of your business? If not, you might want to rethink your future at the Centre for Entrepreneurship’s Grow your Venture programme.   </p>

<p>The Centre for Entrepreneurship at Wits Business School is dedicated to nurturing entrepreneurship education, support and research in Africa, with the goal of becoming the centre of excellence for entrepreneurial development on the continent.  </p>

<h4>Programme objectives</h4>

<p>The overall objectives of the Grow your Venture programme are to:</p><br>
<lu>
<li>Give entrepreneurs an unfair advantage when it comes to growing their venture</li>
<li>Instil the courage and confidence necessary to go for growth</li>
<li>Provide the insights, tools and experience to take on the growth imperative successfully. </li>
</lu>
<p>By the end of the course, entrepreneurs participating in Grow Your Venture will: </p>
<br>
<lu>
<li>Know how to develop a growth strategy that capitalises on existing market structures and characteristics</li>
<li>Learn how to develop organisational capability so that efficiencies are retained and effectiveness maximised</li>
<li>Develop a growth plan that will be evaluated by an expert panel. Participating entrepreneurs will receive invaluable personalised feedback about their growth strategies and capabilities to implement these.</li>

</lu>
 
<p>Entrepreneurs will also build a valuable network of other entrepreneurs and resource holders focused on businesses in the growth phase of their developmen</p>
<p>Programme graduates will have access to a range of advisory and support services offered by the Centre for Entrepreneurship, including mentoring and coaching programmes, peer-to-peer and resource-holder networking opportunities.  </p>

<h4>Who should attend?</h4>

<p>Entrepreneurs from all disciplines who have been running a business for more than three and years and now want to move to the next level of growth.</p>

<h4>Programme structure</h4>

<p>The programme will be offered in modules, involving a minimum of 60 contact hours, with additional independent study, assignments and fieldwork during the programme. Lectures and class discussions will be accompanied by developing practical skills, and the programme further enriched through case studies, guest entrepreneurs, individual assignments and syndicate-based action learning projects.</p>

<h4>Programme content</h4>
<p></p>
<h4><i>1. Strategy development</i></h4>
<p>This module examines the development of growth strategies that incorporate next-generation thinking while taking sector growth pains into account. </p>

<p>Participants will learn how to find uncontested market space, reconfigure the business model and build annuity revenue streams.</p>

<h4><i>2. Capability development</i></h4>
<p>Too often, an aggressive growth strategy fails to be implemented because capabilities do not match ambitions. This module focuses on ensuring that business operations are geared to achieve scaleability by capitalising on network effects, leveraging technology and adding value and innovation to logistics. </p>

<p>Organisation design and development are considered in some detail as is the need for empowerment and flexibility, to ensure that structure, culture and leadership are coherently aligned with strategy. Funding options are fully explored and deal-structuring techniques examined.</p>

<h4><i>3. The growth plan</i></h4>

<p>Finally, as part of the Grow Your Venture programme, entrepreneurs will develop a growth plan that considers internal and external growth options, and ensures resources are available to fully implement strategy. The growth plan also considers the often-overlooked personal vision of the entrepreneur and explores how the transition to ‘life after the harvest’ might best be achieved.</p>


<h4>Assessment</h4>
<p>Individual and syndicate assignments focusing on analysis of real-life South African companies in the growth phase will be part of the learning experience. Participants will also receive detailed feedback on growth plans presented to a panel of experts at the programme’s conclusion. </p>

<h4>Applications</h4>
<p>An application form is available from the Centre for Entrepreneurship reception. Preference will be given to individuals with previous academic or professional qualifications from any discipline but this will not be a prerequisite if experience and motivational criteria are met.  Participants will be selected on the basis of their entrepreneurial experience. </p>

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
	}*/

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
