<?php

/**
 /* a class that divides the page into two columns
 * 
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class home_left_column extends object
{
	  
/**
    * Constructor
    */
	public function init()
    	{
		
        }
   /**
     * A method to to divide the page body into two columns 
     *
     * @access public.
     */

	private function buildBody()
	{
		$content='<!-- MAIN CONTAINER --><div id="main_body_container"><!-- LEFT CONTAINER --><div id="main_body_container_left"><div id="left">

<div id="academics">

 <div id="rel_links">

<div class="bl"><div class="br"><div class="tl">
  <div class="tr">
<img src="http://146.141.208.61/chisimba1/packages/cfe/resources/Academics.gif" border="0" /><br /><div class="story">
<div class="title">
<a href="http://www.wbs.ac.za/download_files/about_wbs/about_the_wbs/newsletters/WBS_Newsletter_Feb09.pdf" class="title_lk_cs"></div></a>
<div class="date"></div>
<div class="summary"><p></p>As part of our mission to nurture, promote, and inculcate a culture of entrepreneurship in South Africa, one of three strategic focus areas of the Centre for Entrepreneurship (CfE) at Wits Business School (WBS) is research and thought leadership. </div>
<br>
<div style="float:right; padding: 10px;"></div>
<ul>
<li> <a href = "http://www.gmail.com">  Work Paper 1 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 2 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 3 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 4 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 5 </a></li> 
</ul>
</div> <!-- end story -->

</div>

</div></div></div>

</div>

</div><!-- end academics -->

<!-- start entrepreneurs -->

<div id="entrepreneurs">
 <div id="rel_links">

<div class="bl"><div class="br"><div class="tl">
  <div class="tr">
<img src="http://146.141.208.61/chisimba2/packages/cfe/resources/entrepreneurs.gif" border="0" /><br /><div class="story">
<div class="title">
<a href="http://www.wbs.ac.za/download_files/about_wbs/about_the_wbs/newsletters/WBS_Newsletter_Feb09.pdf" class="title_lk_cs"></div></a>
<div class="date"></div>
<div class="summary"><p></p>The aim of this area is to create a comunity for all entrepreneurs.  It will be measured by the number of log on\'s etc. or by comments made and Q&A\'s participated in. </div>
<p></p>
<a href = "http://www.gmail.com"><img src = "http://146.141.208.61/chisimba2/packages/cfe/resources/read_more.gif" /></a>
<div style="float:right; padding: 10px;"></div>

</div>

</div></div></div>

</div>
</div>
<img src = "http://146.141.208.61/chisimba2/packages/cfe/resources/dotted_entre.gif" />
</div><!-- end story -->



</div><!-- end left -->





<div style="clear:both;"></div></div> <!-- MAIN CONTAINER END -->';

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
		return $this->buildBody();
	}

}
?>

