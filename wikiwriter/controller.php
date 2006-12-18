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

	/**
	* Variable objConfig Object for accessing chisimba configuration
	*/
	public $objConfig = '';

	// Personal debugger - TODO: REMOVE BEFORE COMMITTING FOR PRODUCTION!
	public function dbg($sErr){
		$handle = fopen('error_log/my_debug.log', 'a');
		fwrite($handle, "[" . strftime("%b %d %Y %H:%M:%S") ."]/controller.php :: " . $sErr . "\n");
		fclose($handle);
	}

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
			$this->loadClass('altconfig', 'config');
			
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
					$this->dbg('######## Begin Publishing ############################');
					$this->dbg('URLLIST = ' . $urls);
					$format = 'odt'; // Hard coding for now, eventually will be taken from a getParam
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
		// First, grab all the URLs and parse into an array
		$urls = explode(',', $urllist);

		// Now grab every url and load into a new array
		// TODO: Try/Catch for page retrieval
		$arrPages = array();
		foreach($urls as $k => $v)
		{
			$arrPages[$k] = $this->getPage($v);
		}

		// Build HTML page for rendering 
		$page = $this->buildPage($arrPages);
		$this->dbg('HTML Contents = ' . $page);

		//Default format is pdf
		switch($format){
			case 'odt':
				// A nice idea but this really doesn't work, just opens up OpenOffice Web Writer
				// Still, worth some more investigation.
				header("Content-type: application/vnd.oasis.opendocument.text");
				header("Content-Disposition: attachment; filename='test.odt'");
				header("Content-Description: PHP Generated Data");
				echo $page;
			break;
			default:
				// Get PDF rendering of the content
				$pdfwriter = new DomPDFWrapper();
				$pdfwriter->generatePDF($page); 
			break;
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
		// For each page: 
		foreach($aPages as $k => $sPage)
		{
			//Identify for which wiki
			$pType = $this->getWikiType($sPage);
			// Now grab the Page Object for the given wiki page and store back in the array
			switch($pType){
				case "chisimba":
					$aPages[$k] = $this->parseWiki_Chisimba($sPage);
				break;
				case "mediawiki":
					$aPages[$k] = $this->parseWiki_MediaWiki($sPage);
				break;
				// TODO: Throw an error that the wikitype cannot be identified...what do we do here?
			}

		}

		// Create a new wwDocument
		$doc = new wwDocument();

		// import all pages 
		foreach($aPages as $k)
		{
			$doc->importwwPage($k);
		}

		// Add the stylesheet definitions
		// Pull the type 
		$stylesheet = file_get_contents('modules/wikiwriter/resources/wikiwriter.css');
		$doc->loadStyle($stylesheet);		

		// Return page
		return $doc->toHTML();
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
		if(preg_match('/<div id="leftcontent">\s+<h1>Wiki/', $sHTML))
		{
			$this->dbg('found chisimba!');
			return 'chisimba';
		} else if(preg_match('/MediaWiki/', $sHTML))
		{
			$this->dbg('found mediawiki!');
			return 'mediawiki';	
		}
		return 'unsupported';
	}

	/* 
	 * Retrieves the url requested and returns the html
	 * @access private
	 * @params string URL
	 * @return string HTML content
	 */
	private function getPage($url)
	{
		$ch = curl_init($url);

		// if objConfig hasn't been instantiated, do so
		if($this->objConfig == ''){
			$this->objConfig = new altconfig();
		}

		// set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // So cURL returns a string

		//if URL isn't localhost or 127.0.0.1, setup a proxy for retrieval if setting exists
		if( !(preg_match('/http:\/\/localhost/', $url) || preg_match('/http:\/\/127.0.0.1/', $url)) 
			&& $this->objConfig->getItem('KEWL_PROXY'))
		{				
			curl_setopt_array($ch, array(CURLOPT_PROXY => $this->objConfig->getItem('KEWL_PROXY'),
								CURLOPT_PROXYUSERPWD => 'mwatson:schrodinger',
								CURLOPT_PROXYPORT => 80	)	
							); 
		}

		// Grab content and close resource
		$content = curl_exec($ch);
		//$this->dbg("################## HTML ######## \n" . $content);
		curl_close($ch);

		return $content;
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
		try
		{
			// first create an object for storing the data
			$objPage = new wwPage();

			// Load contents into DOM Object
			$objDOM = new DomDocument();
			$objDOM->loadHTML($sHTML);

			// Grab every stylesheet
			$aLinks = $objDOM->getElementsByTagName('link');
			foreach($aLinks as $link)
			{
				//For each one, add to the Page Object if its a stylesheet
				if($link->getAttribute('rel') == 'stylesheet')
				{
					$objPage->addStyleSheet($link);
				}
			}

			// Take out just the wiki content and store in the Page Object
			$objPage->setContent($objDOM->getElementById('contentcontent'));
			return $objPage;
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
	 * Breaks down a MediaWiki Wiki page into the necessary components
  	 * 
	 * @access private
	 * @params string $sHTML Page Content
 	 * @return wwPage Returns a wwPage Object
	 **************************************************/
	private function parseWiki_MediaWiki($sHTML)
	{
		$this->dbg("HTML = \n " . $sHTML);
		
		// first create an object for storing the data
		$objPage = new wwPage();

		// Load contents into DOM Object
		$objDOM = new DomDocument();
		$objDOM->loadHTML($sHTML);

		// Grab every stylesheet
		$aLinks = $objDOM->getElementsByTagName('link');
		foreach($aLinks as $link)
		{
			//For each one, add to the Page Object if its a stylesheet
			if($link->getAttribute('rel') == 'stylesheet')
			{
				$objPage->addStyleSheet($link);
			}
		}

		// First we grab the content
		$tmpNode = $objDOM->getElementById('content');
		// Check to see if siteNotice div is there, if so, remove
		if($objDOM->getElementById('siteNotice'))
		{
			$tmpNode->removeChild($objDOM->getElementById('siteNotice'));
		}

		// now load into the page object 
		$objPage->setContent($tmpNode);
		$this->dbg('Object Type = ' . get_class($objPage));	

		return $objPage;
	}



} 
?>
