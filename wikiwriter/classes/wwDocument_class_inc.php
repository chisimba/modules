<?php
/**
* WikiWriterDocument Object 
*
* The WikiWriterDocument takes in wiki pages and creates
* a master document including all the major elements of the pages 
* (stylesheets, images and actual content) creating a final html
* document to be parsed by whatever selected tool.
*
* @author Ryan Whitney, ryan@greenlikeme.org 
*/
class wwDocument extends object 
{

	// Personal debugger - TODO: REMOVE BEFORE COMMITTING FOR PRODUCTION!
	public function dbg($sErr){
		$handle = fopen('error_log/my_debug.log', 'a');
		fwrite($handle, "[" . strftime("%b %d %Y %H:%M:%S") ."]/classes/wwDocument :: " . $sErr . "\n");
		fclose($handle);
	}

	/**
	 * @var array $stylesheets Reference to stylesheets stored on the system
	 */
	 private $stylesheets;

	/**
	 * @var array $images Reference to images stored on the system
	 */
	 private $images;

	/**
	 * @var array $pages Parsed wiki pages containing only the content
	 */
	 private $pages;

	/**
	* Variable objChisimbaCfg Object for accessing chisimba configuration
	*/
	public $objChisimbaCfg = '';

	/**
	 * Constructor
	 * Creates the basic structure for the Document object  (html, head, body)
 	 */ 
	public function init()
	{
		try
		{
			//Load classes
			$this->loadClass('altconfig', 'config');

			//Instantiate needed objects
			$this->objChisimbaCfg = new altconfig();
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
	 * Takes in the url, saves the images and stylesheets, then parses the html 
	 * for the actual wiki content and saves that.
	 * @access public
	 * @params string $url URL for a selected wiki page
	 * @returns void
	 */
	public function importPage($url)
	{
		// Fetch html content and load into a DOM object for processing
		$objDOM = new DOMDocument();
		$objDOM->loadHTML($this->getFile($url));

		//For every img url that doesn't already exist in $images, load 
		foreach($objDOM->getElementsByTagName('img') as $imgNode)
		{
			$imgURL = $this->getFullURL($imgNode->getAttribute('src'), $url);
			//TODO: Do we determine if image exists here or in the loadImage function?
			$this->loadImage($imgURL);
		}

		// Load all the stylesheets
		// Parse the wiki content and save	
		
	}

	/**
	 * Builds the final page and returns a final html document
	 * with references to files on the local Chisimba system 
	 * 
	 * @access public
	 * @returns $string HTML content 
	 */
	public function toHTML()
	{
		//$this->dbg('HTML output = ' . $this->dom->saveHTML());
		return $this->dom->saveHTML();

	}	

	/**
	 * Retrieves the file from the specified url and returns the content
	 * 
	 * @access private
	 * @params string $url URL of the file to retrieve
	 * @return multi Returns the file format that was requested
	 */
	private function getFile($url)
	{
		$ch = curl_init($url);

		// set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // So cURL returns the file contents 

		//if URL isn't localhost or 127.0.0.1, setup a proxy for retrieval if setting exists
		if( !(preg_match('/http:\/\/localhost/', $url) || preg_match('/http:\/\/127.0.0.1/', $url)) 
			&& $this->objChisimbaCfg->getItem('KEWL_PROXY'))
		{				
			//TODO: Waiting on change to installer BUT in the meantime need to find alternative option
			// Either parsing the proxy line given for an username/password and proxy port or have
			// settings built into config
			curl_setopt_array($ch, array(CURLOPT_PROXY => $this->objChisimbaCfg->getItem('KEWL_PROXY'),
								CURLOPT_PROXYUSERPWD => 'mwatson:schrodinger',
								CURLOPT_PROXYPORT => 80	)	
							); 
		}

		// Grab content and close resource
		$content = curl_exec($ch);
		curl_close($ch);

		return $content;
	}

	/**
	 * Finds all of the images referenced in the file and downloads them
	 *
	 * @access private
	 * @params string $html HTML content to parse for the img
	 */

	/**
	 * Because some urls referenced in html documents aren't full urls,
	 * but rather directory locations, this function takes the original url
	 * and the url used to pull the src document and returns a fully valid URL (http://...)
	 * 
	 * @access private
	 * @params string $url URL to be verified or turned into a fully valid URL
	 * @params string $srcURL URL of the source document
	 * @returns string A fully valid URL
	 */ 
	private function getFullURL($url, $srcURL)
	{
		// if the url starts with http://, then return it, its already fully valid
		if($preg_match('/http:\/\//', $url))
		{
			return $url;
		} else {
			// else grab the base url from $srcURL and prepend it to the $url and return
			//TODO: Consider wrapping this in a try/catch or some other way to confirm that the matches returns correctly
			$preg_match('/(http:)?[a-zA-Z0-9_.-]*\/+/', $url, $matches);
			return $matches[0] . $url;
		}
	}
}
?>
