
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



class growth extends object
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
	
	$linkGrowYourVenture =  'growYourVenture';
	$growYourVenture = 'Grow your venture';
	$linkMasterclass=  'masterclass';
	$masterclass=  'Masterclass';
	$linkCourseSchedule =  'createYourVenture';
	$courseSchedule=  'Course schedule for 2010';
	$mod = 'cfe';
		$content = '<div id="shortCoursesCoursesh3"><h3> GROWTH </h3>
<div id="shortCoursesh4"><h4>Courses</h4></div></div>
                    <div id="shortCoursesCourses">'.'<div id = "shortCoursesContent">'.
$this->objLinks->Link($linkGrowYourVenture, $growYourVenture, $mod) . '<p><b>Are you adequately prepared for growth? </b>For many companies, the time of greatest risk is when the founding CEO seeks to grow or scale the business. All too often, the founder’s ambition outstrips the capabilities and resources available to the developing venture. In this situation growth is not only constrained, but the very existence of the enterprise is threatened. Can you be certain your growth plans won’t be the death of your business?</p>' . '<div id = "more">' . $this->objLinks->Link($linkGrowYourVenture, $more, $mod). '</div>' . '<p></p>' .  $this->objLinks->Link($linkMasterclass, $masterclass, $mod). '<p><b>Struggling to put together a business plan?</b> It is often said that the process of business planning is much more important than the plan itself because the process gives</p>' . '<div id = "more">' .  $this->objLinks->Link($linkMasterclass, $more, $mod) . '</div>' .
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
