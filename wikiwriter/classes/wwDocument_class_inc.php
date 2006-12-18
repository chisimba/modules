<?php
/**
* WikiWriterDocument Object 
*
* This class is a simple object for created a valid DOM Document 
* for the wikiwriter 
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

	//TODO: Declare variables appropriately
	
	/**
	 * @var DOMDocument $dom DOM Document object for building page
	 */
	private $dom;

	/**
	 * @var DOMElement $root Root for the document (<html>) 
	 */
	private $root;

	/**
	 * @var DOMElement $head Header for the document (<head>) 
	 */
	private $head;

	/**
	 * @var DOMElement $root Body for the document (<body>) 
	 */
	private $body;

	/**
	 * Constructor
	 * Creates the basic structure for the Document object  (html, head, body)
 	 */ 
	public function init()
	{
		try
		{
			$this->dom = new DomDocument();

			$this->root = $this->dom->createElement('html');
			$this->root = $this->dom->appendChild($this->root);

			$this->head = $this->dom->createElement('head');
			$this->head = $this->root->appendChild($this->head);

			$this->body = $this->dom->createElement('body');
			$this->body = $this->root->appendChild($this->body);
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
	 * Imports a wwPage object 
	 * 
	 * @access public
	 * @params wwPage $objPage wwPage Object
	 * @returns void
	 */
	public function importwwPage($objPage)
	{
		$this->dbg('class = ' . get_class($objPage));	
		$this->dbg('class = ' . get_class($objPage->getContent()));	

		// First, add the page content to the body object
		$content = $this->dom->importNode($objPage->getContent(), true);
		$this->body->appendChild($content);

		// Now add each stylesheet
		foreach($objPage->getStyleSheets() as $link)
		{
			$tmpLink = $this->dom->importNode($link, true);
			$this->head->appendChild($tmpLink);
		}
	}

	/**
	 * Exports the page as a HTML string 
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
	 * Set the overruling styles for the output by setting the <style> tag
	 * This can be a combination of user submitted and default style sheets per wiki
	 *
	 * @access public
	 * @param string css commands
	 * @returns void
	 */
	public function loadStyle($stylesheet)
	{
		$style = $this->dom->createElement('style');
		$style->setAttribute('type', 'text/css');
		$style->nodeValue = $stylesheet;
		$this->head->appendChild($style);
	 }
}
?>
