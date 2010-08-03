
<?php
/**
 /* This class creates two layers and write on them
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


class research_links extends object
{


   public $content;
   /**
    * Constructor
    */
	public function init()
    	{

            //Create an object of the link class
            $this->loadClass('link', 'htmlelements');
            $this->pageContent = $this->newObject('csslayout', 'htmlelements');
            //build the content
            $this->buildContent();
            $this->objLinks = $this->newObject('links', 'cfe');

        }


    /**
     * This method writes on the two layers
     *
     * @access private.
     */
	private function buildContent()
	{
            //$this->pageContent->setNumColumns(1);

            $content='<!-- START RIGHT --><div id="research"><div id="right"><div id="rel_links"><div class="bl"><div class="br"><div class="tl"><div class="tr"><img src="http://localhost/chisimba2/packages/cfe/resources/subimg.jpg" ></a><br />
<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/general-research" target="" class="simple_t2">Research Themes</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/general-research" target="" class="simple_t2">Research in Progress</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/faq" target="" class="simple_t2">Research Briefs</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/prevention-and-possibility" target="" class="simple_t2">Working Papers</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/publications" target="" class="simple_t2">Publication</a></div></li></ul></div>

<div class="story"><ul><li><div class="title_lk_cs"><a href="http://www.wbs.ac.za/research/general-research" target="" class="simple_t2">Conference</a></div></li></ul></div>
</div></div></div> </div></div><br />
<div id="newsletter">



<form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post"
onsubmit="return validateForm(this)">
              <input type="hidden" name="p_source" value="WBS">
              <input type="hidden" name="p_redir" value="http://wbs.pl.privatelabel.co.za/newsletter/thankyou.htm">
              <input type="hidden" name="p_list" value="WBS">

<table border="0" cellpadding="0" cellspacing="0">
<tr><td><input type="text" id="q20460" name="p_firstname" value="Name" maxlength="500"  class="input" /></td><td></td></tr>
<tr><td height="8"></td><td></td></tr>
<tr><td><input type="text" id="q20461" name="p_email"  maxlength="500" class="input" value="Email address"  /></td><td><input type="image" src="http://localhost/chisimba2/packages/cfe/resources/signup_btn.gif" border="0" class="img"></td></tr>
</table>
</form>

</div><div id="rss"><a href="/rss"><img src="http://localhost/chisimba2/packages/cfe/resources/rss_300_cat_art.jpg" width="300" height="62" border="0" /></a>
</div></div> <!-- END RIGHT --></div> <!--end research -->


';
		
	return $content;
	}
	public function show()
	{
		return $this->buildContent();
	}

}
?>
