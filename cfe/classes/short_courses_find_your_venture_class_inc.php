
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


class short_courses_find_your_venture extends object
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
		$content = '<div id="shortCoursesCoursesh3"><h3> FIND YOUR VENTURE  </h3>
<div id="shortCoursesCoursesp"><p>Find your venture</p></div></div>
                    <div id="shortCoursesCourses">

<p><b></b> </p>

<p> </p>

<h4>Programme objectives</h4>

<p></p><br>
<lu>
<li></li>
<li></li>
</lu>
<p></p>
<br>
<lu>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
</lu>
 
<p></p>

<h4>Who should attend?</h4>

<p></p>

<h4>Programme structure</h4>

<p></p>

<h4>Programme content</h4>
<p></p>
<h4><i>1. You and your start-up</i></h4>
<p></p>

<h4><i>2. Identifying and evaluating opportunity</i></h4>
<p></p>

<h4><i>3. Devising the business plan</i></h4>


<h4><i>4. Gathering resources </i></h4>

<p>
</p>

<h4><i>5. Managing the business</i></h4>
<p></p>

<h4>Assessment</h4>
<p></p>

<h4>Applications</h4>
<p></p>

<h4>Course fees</h4>
<p></p>

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
