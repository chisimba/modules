<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check


/**
* 
* Class to render the wizard links on a page
* 
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class wizlinks extends object
{
    /**
    * 
    * @var string object $objLanguage A string to hold the language object
    * 
    */
    public $objLanguage;
    /**
    * 
    * @var string $page The page that we are on in the wizard templates
    * 
    */
    private $page;
    
    /**
    * 
    * Constructor class to initialize language and load form elements
    * 
    */
    public function init() 
    {
        //Create an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
		//Load the link object
		$this->objLink = $this->getObject('link', 'htmlelements');
		// Add the heading to the content
		$this->objH =& $this->getObject('htmlheading', 'htmlelements');
    }
    
    /**
    * 
    * Show method to render the output of the links menu to the
    * template
    * 
    * @return string The formatted menu of links
    * 
    */
    public function show()
    {
    	//Let the methods know what page we are on
    	$this->page=$this->getParam('page', 'start');
        //Variable for the rightside column wizard heading text
		$this->objH->str=$this->objLanguage->languageText("mod_generator_start_rt", "generator");
		$retstr = $this->objH->show();
		$retstr .= $this->startLink() . "<br />";
		$retstr .= $this->page2Link() . "<br />";
		$retstr .= $this->page3Link() . "<br />";
		$retstr .= $this->page4Link() . "<br />";
		return $retstr;
    }
    
    /**
    * 
    * Method to generate a link to the start template. It is not active 
    * if we are on the start page.
    * 
    * @return string The link to the start page
    * 
    */
    private function startLink()
    {

        if ($this->page == 'start') {
            $ret =  "1. " . $this->objLanguage->languageText("mod_generator_restartwiz", "generator");
        } else {
        	$href = $this->uri(array(
			  'action'=>'start',
              'page'=>'start'));
          	$this->objLink->href = $href;
          	$this->objLink->link = $this->objLanguage->languageText("mod_generator_restartwiz", "generator");
        	$ret = "1. " . $this->objLink->show();
        }
        return $ret;
    }
    
    /**
    * 
    * Method to generate a link to the page2 template. It is not active 
    * if we are on the page2 page.
    * 
    * @return string The link to the page2 page
    * 
    */
    private function page2Link()
    {
        if ($this->page == 'page2') {
            $ret =   "2. " . $this->objLanguage->languageText("mod_generator_gencontrreg", "generator");
        } else {
        	$href = $this->uri(array('action' => 'page2', 'page' => 'page2'));
			$this->objLink->href =  $href;
			$this->objLink->link = $this->objLanguage->languageText("mod_generator_gencontrreg", "generator");
			$ret = "2. " . $this->objLink->show();
        }
        return $ret;
    }

    /**
    * 
    * Method to generate a link to the page3 template. It is not active 
    * if we are on the page3 page.
    * 
    * @return string The link to the page3 page
    * 
    */
    private function page3Link()
    {
        if ($this->page == 'page3') {
            $ret =   "3. " . $this->objLanguage->languageText("mod_generator_blddatafmdb", "generator");
        } else {
        	$href = $this->uri(array('action' => 'page3', 'page' => 'page3'));
			$this->objLink->href =  $href;
			$this->objLink->link = $this->objLanguage->languageText("mod_generator_blddatafmdb", "generator");
			$ret = "3. " . $this->objLink->show();
        }
        return $ret;
    }
    
    /**
    * 
    * Method to generate a link to the page4 template. It is not active 
    * if we are on the page4 page.
    * 
    * @return string The link to the page4 page
    * 
    */
    private function page4Link()
    {
        if ($this->page == 'page4') {
            $ret =   "4. " . $this->objLanguage->languageText("mod_generator_bldtmplatefmdb", "generator");
        } else {
        	$href = $this->uri(array('action' => 'page4', 'page' => 'page4'));
			$this->objLink->href =  $href;
			$this->objLink->link = $this->objLanguage->languageText("mod_generator_bldtmplatefmdb", "generator");
			$ret ="4. " . $this->objLink->show();
        }
        return $ret;
    }
    
    /**
    * 
    * Method to return standard text for the left column in the 3 column
    * layout of the wizards.
    * 
    * @return string The standard left column text
    * 
    */
    function putStandardLeftTxt()
    {
        // Add the heading to the content of the left column
		$this->objH->str=$this->objLanguage->languageText("mod_generator_name", "generator");
		$ret = $this->objH->show();
		return $ret;
    }

}
?>