
<?php

/**
 * This is the class of the main short courses page. It contains all the links to other pages
 * of short courses
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



class start_up extends object
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
		//create objects
		$this->loadClass('link', 'htmlelements');
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objShortCourses = $this->newObject('short_courses_content', 'cfe');
		$this->objLinks = $this->newObject('links', 'cfe');
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
	$linking =  'createYourVenture';
	$texting = 'create your venture';
	$more = '...Click here for more';
	$linkFindYourVenture =  'findYourVenture';
	$findYourVenture = 'Find your venture';
	$linkStartYourVenture =  'startYourVenture';
	$startYourVenture = 'Start your venture';
	$linkPlanYourVenture =  'planYourVenture';
	$planYourVenture = 'Plan your venture';
	
	$mod = 'cfe';
		$content = '<div id="shortCoursesCoursesh3"><h3> PRE STARTUP  </h3>
<div id="shortCoursesh4"><h4>Courses</h4></div></div>
                    <div id="shortCoursesCourses">'.'<div id = "shortCoursesContent">'.
 $this->objLinks->Link($linkStartYourVenture, $startYourVenture, $mod) . '<p><b>Exactly how do you start a business?</b> Sometimes, small businesses are started in a hurry, and the entrepreneur hasn’t always considered every aspect of setting up the new enterprise. In this situation, learning skills you can use right away, with other entrepreneurs who really understand what you’re going through, can help clarify your thinking and get you moving forward in the right direction.  </p>' . '<div id = "more">' . $this->objLinks->Link($linkStartYourVenture, $more, $mod) . '</div>' . '<p></p>' . $this->objLinks->Link($linkPlanYourVenture, $planYourVenture, $mod). '<p><b>Struggling to put together a business plan? </b>It is often said that the process of business planning is much more important than the plan itself because the process gives you new insights into your business’s future direction and operations. But knowing how to start a plan and finding time to finish it can be difficult when you are already dealing with the day-to-day challenges of running a business.</p>' . '<div id = "more">' . $this->objLinks->Link($linkPlanYourVenture, $more, $mod) . '</div>' .
'<h3>Enquiries</h3>
<p><i>Programme Manager</i></p>
<p>Sibongile Msimanga</p>
<p>Telephone: 	+2711 717 3833</p>
<p>Email:	  Sibongile.Msimanga@wits.ac.za</p>

</div></div>';

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
