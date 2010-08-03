
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

class advisoryBoardPage extends object
{
    /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objShortCourses = $this->newObject('short_courses_content', 'cfe');
		$this->objShortCoursesRight = $this->newObject('short_courses_content_right', 'cfe');
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
		//set the layout to two columns
		$this->pageContent->setNumColumns(1);

		$this->pageContent->setMiddleColumnContent('<div id="aboutCFE"><div id="left"><div id="subhead"><div class="txt"><img src = "http://localhost/chisimba/packages/cfe/resources/subhead.gif"></a></div></div><div class="bodytxt"><div id="cat_story"><h3>About CfE</h3>

<div style="float:right; padding: 10px;"></div>

<p><strong>Mission statement</strong>:</p>
<p>To increase the proportion of South Africans engaged in entrepreneurial activity, and to enhance the success potential of those already in business.</p>

<p><strong><em>Overview of the Centre</em></strong></p>

<p><b>Our Purpose</b></p>

<p>
The Centre for Entrepreneurship is an integral part of the University of the Witwatersrand’s Business School. Through a range of clearly focused education and training programmes, backed by a formidable support network, and research activities in this vital field, the centre is actively developing the role of entrepreneurship in the domestic economy.
</p>

<p>
Through the right training and education, we aim to increase the proportion of South Africans engaged in entrepreneurial activity, and to enhance the success potential of those already in business. Skills and information are essential tools in turning any entrepreneurial venture from mere survival – the need for an income – to capitalising on opportunity and building a sustainable business.
</p>

<p><b>Strong partnership network</b></p>

<p>
In addition to the considerable resources of the globally acclaimed Wits Business School, the Centre for Entrepreneurship is building a pan-African network of partnerships and entrenching its global relationships with leaders in the field. Partnerships with other universities will enable the centre to share research and contribute to the development 
of knowledge-based entrepreneurial development programmes.
</p>

<p>
Forging links with national government and local metropolitan authorities as well as established entrepreneurial development NGO’s in promoting entrepreneurial development is equally important to the centre, as these relationships can boost institutional capacity to add value in remote communities that otherwise have little access to entrepreneurial development initiatives
</p>


<br>

<p><b>Skills for sustainable business</b></p>

<p>
The Centre for Entrepreneurship currently offers public programmes at doctoral and master’s levels, as well as a range of certificate programmes that complete the entrepreneurial cycle from conception, through start-up to emerging sustainability and growing venture. Our goal is to encourage the establishment of new ventures and to nurture the growth of existingventures in such a way that wealth is created for individuals and the communities in which they live and work
</p>

<p>
Through extensive practically training, entrepreneurial skills are built incrementally. 
The theoretical component of certificate programmes is complemented by exposure to the problems and opportunities facing real-life entrepreneurs through analysis of case studies and open forum discussions with the entrepreneurs themselves.
</p>

<p>Certificate programmes are supplemented by workshops designed to develop specific skills, build confidence and crystallise plans. These include:<p></p>

<ul>
<li>Business planning for entrepreneurs</li>
<li>Finance for entrepreneurs</li>
<li>Strategy for entrepreneurs</li>
<li>Marketing for entrepreneurs</li>
<li>Selling for entrepreneurs</li>
</ul>

<p><b>Entrepreneurial support programmes: </b></p>

<p>
Beyond skill development, entrepreneurs need ongoing support during their journey from start-up to sustainability. The Centre for Entrepreneurship will offer coaching, mentoring, and peer support programmes through its Business Clinic programme. Entrepreneurs will have regular face-to-face access to relevant information and wise counsel from other established entrepreneurs, experienced executives and experts in each business discipline. 
</p

<p>
This support system is a vital developmental tool for entrepreneurs confronting the myriad of challenges involved in creating a new venture and will ultimately extend to an electronic support network that stretches across the most remote corners of the country and the continent. Such an on-line network will allow new entrepreneurs to tap into the collective wisdom of successful businessmen and women across Africa.
</p>

<p>
The Centre for Entrepreneurship is also a responsible citizen and building social equity is an intrinsic element of its activities. We are working with the public sector at all levels, as well as non-government organisations, in developing and implementing programmes that have the potential to transform the quality of life of communities across the country.
</p>

<p><b>Research for better understanding</b></p>
<p>
Although the subject of entrepreneurship has been fairly extensively studied, much of this research is specific to developed-world economies and opportunities. Programmes built on this research may have less chance of succeeding in the developing world, given the vastly different circumstances, resources and infrastructure available.
</p>

<p>
We believe the only way to overcome this is to have educational programmes developed by empirical research on what entrepreneurs need to succeed in Africa. Our planned programmes of research are therefore all aimed at understanding what constitutes excellent performance in entrepreneurship practice and education in a developing world.
</p>

<p>
Our research programme involves doctoral and masters-level projects, as well as  longitudinal studies of entrepreneurial aspirations, needs and behaviour. We publish a series of working papers on leading edge research, we offer a number of research fellowships each year and host an annual conference designed to draw together the knowledge and insights of entrepreneurship scholars across Africa. 
</p>

<p><b>Building skills through partnerships</b></p>

<p>
Given the low percentage of entrepreneurs in South Africa, the high number of unemployed graduates, and the specific needs of sectors of the community such as women, the Centre for Entrepreneurship has adopted a dynamic business model that provides the flexibility to respond to explicit challenges while working within the disciplines of empirical research.
</p>

<p>
Partnerships with leading corporations, global institutions and the broader academic world are an important element in the centre’s approach to building entrepreneurial skills. By capitalising on the inherent synergies and multiplier effect of these relationships, the Centre for Entrepreneurship is entrenching its role at the forefront of this vital field.
</p>

<div style="clear:both;"></div></div></div></div><!-- end left -->

<!-- START RIGHT -->

<div id="right"><div id="rel_links"><div class="bl"><div class="br"><div class="tl"><div class="tr"><img //src = "http://localhost/chisimba/packages/cfe/resources/subimg.jpg"></a><br />

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/about_the_wbs" target="" class="simple_t2">About the CfE</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/advisory-board" target="" class="simple_t2">Advisory Board</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/contact_us" target="" class="simple_t2">Contact Us</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/directors_blog" target="" class="simple_t2">Directors Blog</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/executive_team" target="" class="simple_t2">Executive Team</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/faculty/" target="" class="simple_t2">Faculty</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/map" target="" class="simple_t2">Map</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/newsletters" target="" class="simple_t2">Newsletters</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/publications" target="" class="simple_t2">Publications</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/about_wbs/virtual_tour" target="" class="simple_t2">Virtual Tour</a></div></li></ul></div>

</div></div></div> </div></div><br />

<div id="newsletter">

<form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post">

<table border="0" cellpadding="0" cellspacing="0">
<tr><td><input type="text" id="q20460" name="p_firstname" value="Name"maxlength="500"  class="input" /></td><td></td></tr>
<tr><td height="8"></td><td></td></tr>
<tr><td><input type="text" id="q20461" name="p_email"  maxlength="500" class="input" value="Email address" /></td><td><input type="image" src="http://localhost/chisimba/packages/cfe/resources/signup_btn.gif" border="0" class="img"></td></tr>
</table>


</form>





<div style="clear:both;"></div></div></div></div></div></div>
<div id="footer"><div class="txt">
');
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



