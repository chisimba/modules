
<?php
/**
 /*This class creates two layers 
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

class footer extends object
{

    /**
     * Holds the text for the footer
     * @var    string
     * @access public
     */
	public $footer_text;
     
     /**
      *Constructor
      */
	public function init()
    	{
		//Create the layers
		$this->layer1 = $this->newObject('layer', 'htmlelements');
		$this->layer2 = $this->newObject('layer', 'htmlelements');
		
		//Create link objects
                $this->ref = $this->newObject('href', 'htmlelements');
		$this->ref1 = $this->newObject('href', 'htmlelements');
		$this->ref2 = $this->newObject('href', 'htmlelements');
		$this->ref3 = $this->newObject('href', 'htmlelements');

		//Create the images
		$this->image1 = $this->newObject('image', 'htmlelements');
		$this->image2 = $this->newObject('image', 'htmlelements');
		$this->image3 = $this->newObject('image', 'htmlelements');

		//Set the ids and content
		$this->setID();
		$this->setContent();
        }
    /**
     * Gives the two layers IDs
     *
     * @access private.
     */
	private function setID()
	{
		//Set the ID's of the layers to be used in the stylesheet
		$this->layer1->id = "footerLayer1";
		$this->layer2->id = "footerLayer2";
	}

    /**
     * A method to to write on the layers
     *
     * @access public.
     */
	public function setContent()
	{

		$footer = '<!-- BOTTOM BANNERS --><div id="bottom_banners"><img src="http://146.141.208.43/chisimba1/packages/cfe/resources//bottom_banners.jpg" border="0" usemap="#Map">
<map name="Map">

<area shape="rect" coords="209,13,302,59" href="http://www.aabschools.com" target="_blank">
<area shape="rect" coords="345,14,465,60" href="http://www.mbaworld.com" target="_blank"><area shape="rect" coords="509,13,619,56" href="http://www.pimnetwork.org" target="_blank">
<area shape="rect" coords="663,16,746,52" href="http://www.sabsa.co.za" target="_blank">
</map>
</div> <!-- end bottom banners -->
<div id="footer"><div class="txt">
© 2009 by JCSE <span style="margin-left:10px;">|</span> <span style="margin-left:10px;"><a href="/about_wbs/terms_of_use/">Terms of Use</a></span>

<span style="margin-left:10px;"></span>

<span style="margin-left:10px;"></span>

<span style="margin-left:10px;"></span>

<span style="margin-left:10px;"></span>

</div>
</div> <!-- end footer -->';
return $footer;
;
		
	}	
    /**
     * A method to show the two layers
     * 
     * @access public
     */
	public function show()
	{
		return ($this->setContent());
	}

}
?>
