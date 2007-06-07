<?php
/* ----------- wikiTextParser class extends object ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class wikiTextParser to abstract the Text_Wiki PEAR package so that it can be used in Chisimba
* 
* @author Kevin Cyster 
* @package wiki2
* @subpackage Text_Wiki PEAR package author Paul M. Jones
*/
class wikiTextParser extends object 
{ 
    /**
    * Method to check if Text_Wiki PEAR package is installed and define the class
    * 
    * @access public
    * @return 
    */
    public function init()
    {
    	$this->objLanguage = $this->getObject('language', 'language');
        $errorLabel = $this->objLanguage->languageText('mod_wiki2_missingpear', 'wiki2');
        
        if (!@include_once('Text/Wiki.php')) {
    		throw new customException($errorLabel);
    		return FALSE;
    	}
        $this->objWiki = new Text_Wiki();
    }

    

    /**
    * Method to transform structured wiki text into xhtml
    * 
    * @access public
    * @param string $text The wiki text to transform
    * @return string $xhtml The transformed text (XHTML)
    */

    public function transform($text)
    {
        // Transform the wiki text into XHTML
        $xhtml = $this->objWiki->transform($text, 'Xhtml');
        return $xhtml;
    }

    /**
    * Method to configure Text_Wiki parser
    * 
    * @access public
    * @return void
    */
     
     public function configure()
     {
	 // Disabling HTML tags	 
	 $this->objWiki->disableRule('Html');
	 	 
     //Set the params for the table rule
     $this->objWiki->setRenderConf('xhtml', 'Table', 'css_table');  //, '"" border = 1');

     //Set the add page url param for the wikilink rule
     $addUrl = $this->objConfig->getsiteRoot().'index.php?module=wiki2&action=add_page&pagename=%s';
     $this->objWiki->setRenderConf("xhtml", "Wikilink", "new_url", "$addUrl");

     //Set the view page url param for the wikilink rule
     $viewUrl = $this->objConfig->getsiteRoot().'index.php?module=wiki&action=wiki_link&pagename=%s';
     $this->objWiki->setRenderConf("xhtml", "Wikilink", "view_url", "$viewUrl");

     //Set the sites array for the interwiki rule
     $pages = "";//$this->objDbWiki->listpagenames();
     $this->objWiki->setRenderConf("xhtml", "Wikilink", "pages", "$pages");
     $sites = array('MeatBall' => 'http://www.usemod.com/cgi-bin/mb.pl?%s',
             'Wiki'       => 'http://c2.com/cgi/wiki?%s',
             'Wikipedia' => 'http://en.wikipedia.org/wiki/%s'
             );
     $this->objWiki->setRenderConf("xhtml", "Interwiki", "", "$sites");
     }

    /**
    * Method to render a title from SmashWords to Smash Words
    * i.e. add spaces between capital letters
    * 
    * @access public
    * @param string $title Wiki page title
    * @return string The rendered page title
    */

    function renderTitle($title)
    {
    	$letters = array();
    	$replacement = array();
    	for ($i=65; $i<=90; $i++) { // Loop from A to Z
    		$letters[] = chr($i);
    		$replacement[] = ' '.chr($i);
    	}
    	$str = ltrim(str_replace($letters, $replacement, $title));
    	return $str;
    }
} 
?>