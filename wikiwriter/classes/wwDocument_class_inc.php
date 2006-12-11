<?php
/**
* WikiWriterDocument Object 
*
* This class is a simple object for created a valid DOM Document 
* for the wikiwriter 
*
* @author Ryan Whitney, ryan@greenlikeme.org 
*/
class wwDocument extends DOMDocument 
{

	private $root;
	private $head;
	private $body;

	/**
	 * Constructor
	 * Creates the root part for the Document object 
 	 */ 
	public function init()
	{
		$this->root = $this->createElement('html');
		$this->root = $this->appendChild($this->root);

		$this->head = $this->createElement('head');
		//$head = $root->appendChild($head);
		$this->root->appendChild($this->head);

		$this->body = $this->createElement('body');
		//$body = $root->appendChild($body);
		$this->root->appendChild($this->body);
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

		// First, add the page content to the body object
		$this->body->appendChild($objPage->getContent);

		// Now add each stylesheet
		foreach($objPage->getStyleSheets() as $link)
			$this->head->appendChild($link);
	}

	/**
	 * exports the page as a HTML string 
	 * 
	 * @access public
	 * @returns $string HTML content 
	 */
	public function toHTML()
	{
		return $this->saveHTML();

	}	
}
?>
