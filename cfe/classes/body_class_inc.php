
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
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


class body extends object
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
		$cfe_footer=$this->newObject('footer', 'cfe');
		//create objects
		$content = '<!-- MAIN CONTAINER --><div id="main_body_container"><!-- LEFT CONTAINER --><div id="main_body_container_left"><div id="left">

<div id="academics">

 <div id="rel_links">

<div class="bl"><div class="br"><div class="tl">
  <div class="tr">
<img src="http://146.141.208.43/chisimba1/packages/cfe/resources/Academics.gif" border="0" /><br /><div class="story">
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
<img src="http://146.141.208.43/chisimba1/packages/cfe/resources/entrepreneurs.gif" border="0" /><br /><div class="story">
<div class="title">
<a href="http://www.wbs.ac.za/download_files/about_wbs/about_the_wbs/newsletters/WBS_Newsletter_Feb09.pdf" class="title_lk_cs"></div></a>
<div class="date"></div>
<div class="summary"><p></p>The aim of this area is to create a comunity for all entrepreneurs.  It will be measured by the number of log on\'s etc. or by comments made and Q&A\'s participated in. </div>
<p></p>
<a href = "http://www.gmail.com"><img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/read_more.gif" /></a>
<div style="float:right; padding: 10px;"></div>

</div>

</div></div></div>

</div>
</div>
<img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/dotted_entre.gif" />
</div><!-- end story -->



</div><!-- end left -->




</div>



<!-- LEFT CONTAINER END --<!-- RIGHT CONTAINER --><div id="main_body_container_right"><div id="body">
<div id="shortcourses">

<img src="http://146.141.208.43/chisimba1/packages/cfe/resources/shortcourses.gif" border="0" /
<div class="story">
<ul><li>
<div class="title_lk_cs">
<a href="http://www.wbs.ac.za/news/distinguished-lecture-series/539687.htm" class="title_lk_cs">Create Your Venture</div></li></a>
<li><div class="summary">Certificate of competence in entrepreneurship and new venture creation. 
</div></li></ul>
</div> <!-- end story -->

<div class="story">
<ul><li>
<div class="title_lk_cs">
<a href="http://www.wbs.ac.za/news/cfe/503580.htm" class="title_lk_cs">Start Your Venture</div></li></a>
<li><div class="summary">Certificate of attendence in new venture creation.
</div></li></ul>
</div> <!-- end story -->
<div class="story">
<ul><li>
<div class="title_lk_cs">
<a href="http://www.wbs.ac.za/newsletter/WBS-Alumni-Newsletter-May-2010.pdf" class="title_lk_cs">Build Your Venture</div></li></a>
<li><div class="summary"> Certificate of attendence in new venture development.
</div></li></ul>
</div> <!-- end story -->

<div class="story">
<ul><li>
<div class="title_lk_cs">
<a href="http://www.wbs.ac.za/newsletter/WBS-Alumni-Newsletter-May-2010.pdf" class="title_lk_cs">Grow Your Venture</div></li></a>
<li><div class="summary"> Certificate of attendence in new venture growth.
</div></li></ul>
</div> <!-- end story -->

<div class="story">
<ul><li>
<div class="title_lk_cs">
<a href="http://www.wbs.ac.za/newsletter/WBS-Alumni-Newsletter-May-2010.pdf" class="title_lk_cs">Plan Your Venture</div></li></a>
<li><div class="summary"> Certificate of attendence in business planning for entrepreneurs.
</div></li></ul>
<img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/dotted_entre.gif" />
</div> <!-- end story -->

</div> <!-- end short courses -->


<div id="vision">

<img src="http://146.141.208.43/chisimba1/packages/cfe/resources/vision.gif" border="0" /
<div class="date"></div>
<div class="summary"><p></p>The aim of this area is to create a comunity for all entrepreneurs.  It will be measured by the number of log on\'s etc. or by comments made and Q&A\'s participated in. </div>
<p></p>
<br>

<a href = "http://www.gmail.com"><img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/read_more.gif" /></a>
		<a href = "http://www.gmail.com"><img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/board.gif" align="right" /></a>
		<a href = "http://www.gmail.com"><img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/goals.gif" align="right" /></a>
<img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/dotted_vision.gif" />
</div> <!-- end story -->


</div> <!-- end BODY -->
<div id="rhs">


<div id="cfeSideMenu"><div class="rbl"><div class="rbr"><div class="rtl"><div class="rtr">
<img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/CfE_Logo.jpeg" />

<table border = 0><tr><td> <a href = "http://www.gmail.com">  Vision</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Goals</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Board Members</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Academics</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Short Courses</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Our Partners</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Enterpreneurs</a></td></tr>
</table></div></div></div></div>


</div><!-- end cfeSideMenu -->

<div id="gew"><img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/GEW.jpeg" />
</div><!-- end gew -->

<div id="partners"><img src = "http://146.141.208.43/chisimba1/packages/cfe/resources/partners.gif" />
<p> Our partners include: </p>
					<ul>
					<li><a href = "www.google.co.za"> Dti</a> </li>
					<li><a href = "www.google.co.za"> Business Partners </a> </li>
					<li><a href = "www.google.co.za"> IDC </a></li>
					<li><a href = "www.google.co.za"> Lamberti Foundation </a> </li>
					<li><a href = "www.google.co.za"> Duke University </a> </li>
					<ul>

</div><!-- end partners -->


</div> <!-- end rhs -->
</div> <!-- RIGHT CONTAINER END -->

<!-- degrees --><div id="newsletter"><div class="story">

<table>
					   <tr>
					   <td>
					   
					   </td>
					   <td>
					   <form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post"> 
					   <input type="text" name="p_firstname" value="Name"  maxlength="500" height="5px" id="nametext"/>
			      		   <input type="text" name="p_email"  maxlength="500" value="Email address" id="emailtext" />
			      		   <input type="image" src = "http://146.141.208.43/chisimba1/packages/cfe/resources/signup.jpeg" id="signUpButton">
					   <a href="http://www.google.co.za"></a>

					   <input type="image" src = "http://146.141.208.43/chisimba1/packages/cfe/resources/latest_newsletter.jpeg" id="latestNewsButton">
					   <a href="http://www.google.co.za"></a>
                   			   </form>
					   </td>
					   </tr>
					   </table>
</div>

</div> <!-- end degrees -->



<div style="clear:both;"></div></div> <!-- MAIN CONTAINER END -->

<div style="clear:both;">'.$cfe_footer->show() .'</div></div> <!-- end inner wrapper --><div style="clear:both;"></div></div> <!-- end wrapper --><div style="clear:both;"></div></div> <!-- end outer wrapper --></div>';
       
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
		return $this->buildBody();
	}

}
?>
