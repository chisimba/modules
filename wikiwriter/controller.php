<?php 

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
} 
// end security check

/**
* Class wikiwriter - the controller class for the wikiwriter module
* 
* @author Ryan Whitney, ryan@greenlikeme.org 
* @package wikiwriter
*/
class wikiwriter extends controller 
{
	//TODO: Make objDomPDFWrapper a declared var

    /**
     * Method to initialise the wiki object
     * 
     * @access public 
     */
    public function init()
    {
		try{
			$this->loadClass('dompdfwrapper', 'wikiwriter');
			$this->loadClass('wwPage', 'wikiwriter');
			$this->loadClass('wwDocument', 'wikiwriter');
		}
		catch(customException $e) {
			//oops, something not there - bail out
			echo customException::cleanUp();
			//we don't want to even attempt anything else right now.
			die();
		}
    } 
    

    /**
     * Method to handle actions from templates
     * 
     * @access public
     * @param string $action Action to be performed
     * @return mixed Name of template to be viewed or function to call
     */
    public function dispatch($action)
    {
		try{
			switch($action){
				default: 
					return 'default_tpl.php';
					break;
				case "publish":
					$urls = $this->getParam('URLList');
					$format = 'pdf'; // Hard coding for now, eventually will be taken from a getParam
					return $this->publish($urls, $format);
				break;

			}
		}
		catch(customException $e) {
			//oops, something not there - bail out
			echo customException::cleanUp();
			//we don't want to even attempt anything else right now.
			die();
		}
    }
    
    /**
     * Grabs the URLs passed in, parse the wiki content and return a Document in the chosen format
     * 
     * @access private 
     * @param string $action Action to be performed
     * @return mixed Name of template to be viewed or function to call
     **************************************************/
	private function publish($urllist, $format)
	{
		try{
			// First, grab all the URLs and parse into an array
			$urls = explode(',', $urllist);

			// Now grab every url and load into a new array
			$arrPages = array();
			foreach($urls as $k => $v)
				$arrPages[$k] = file_get_contents($v);
			throw(new Exception($arrPages.toString));
			// Build HTML pages for rendering 
			$page = $this->buildPage($arrPages);

			// Get PDF rendering of the content
			//$sPDF = $this->objDomPDFWrapper->generatePDF(&$sHTML); 
			$pdfwriter = new DomPDFWrapper();
			$pdfwriter->generatePDF($page); 

			// Prepare for returning the PDF
			//$this->setVarByRef('content', $sPDF);
			//$this->setPageTemplate('downloadwikibook_page_tpl.php');
			//return "downloadwikibook_tpl.php";
		}
		catch(customException $e)
		{
			//oops, something not there - bail out
			echo customException::cleanUp();
			//we don't want to even attempt anything else right now.
			die();
		}
	}

	/**
	 * Converts all the html pages and creates one file
     *
	 * @access private
	 * @params array $aPages HTML Page Content
	 * @return string A single HTML page
	 **************************************************/
	private function buildPage($aPages)
	{
		try{

			// For each page: 
			foreach($aPages as $k => $sPage)
			{
				//Identify for which wiki
				$pType = $this->getWikiType($sPage);

				// Now grab the Page Object for the given wiki page and store back in the array
				switch($pType){
					case "chisimba":
						$aPages[$k] = parseWiki_Chisimba($sPage);
					break;
				}

			}

			// Create a new wwDocument
			$doc = new wwDocument();

			// import all pages 
			foreach($aPages as $k)
				$doc->importwwPage($k);

			// Return page
			return $doc->toHTML();
		}
		catch(customException $e)
		{
			//oops, something not there - bail out
			echo customException::cleanUp();
			//we don't want to even attempt anything else right now.
			die();
		}

	}

	/*
     * Returns type of wiki from the html passed in
	 * 
	 * @access private
 	 * @params string $sHTML Page Content
 	 * @return string wiki type identifier (chisimba, mediawiki, etc)
	 **************************************************/
	private function getWikiType($sHTML)
	{
		try
		{
			// Scour file for identifier for each type
			$oDOM = new DomDocument();
			$oDOM->loadHTML($sHTML);

			//TODO: Replace with RegEx if determined to be faster!
			// First we try for Chisimba
			$oNode = $oDOM->getElementById('breadcrumbs');
			$sTmp = $oNode->textContent;
			if(strpos($sTmp, 'module=wiki') && strpos($sTMP, '>> wikilink')){
				return 'chisimba';
			}
			
			return 'unsupported';
		}
		catch(customException $e)
		{
			//oops, something not there - bail out
			echo customException::cleanUp();
			//we don't want to even attempt anything else right now.
			die();
		}


	}

	/****** All Function underneath here are specific parsers per the Wiki page type *********/
	/*
	 * Breaks down a Chisimba Wiki page into the necessary components
  	 * 
	 * @access private
	 * @params string $sHTML Page Content
 	 * @return wwPage	Returns a wwPage Object
	 **************************************************/
	private function parseWiki_Chisimba($sHTML)
	{
		// first create an object for storing the data
		$oPage = new wwPage();

		// Load contents into DOM Object
		$oDOM = new DomDocument();
		$oDOM->loadHTML($sHTML);

		// Grab every stylesheet
		//TODO: Grab and save styles set in the page
		$aLinks = $oDOM->getElementsByTagName('link');
		foreach($aLinks as $link)
		{
			//For each one, add to the Page Object
			if($link->getAttribute('rel') == 'stylesheet')
				$oPage->addStyleSheet($link);
		}

		// Take out just the wiki content and store in the Page Object
		$oPage->setContent($oDOM->getElementsById('contentcontent'));

		return $oPage;
	}

} 
?>
