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


	private $doc;
	private $root;
	private $head;
	private $body;

	/**
	 * Constructor
	 * Creates the basic structure for the Document object  (html, head, body)
 	 */ 
	public function init()
	{
		$this->doc = new DomDocument();

		$this->root = $this->doc->createElement('html');
		$this->root = $this->doc->appendChild($this->root);

		$this->head = $this->doc->createElement('head');
		$this->head = $this->root->appendChild($this->head);

		$this->body = $this->doc->createElement('body');
		$this->body = $this->root->appendChild($this->body);
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

		try
		{

			$this->dbg('class = ' . get_class($objPage));	
			$this->dbg('class = ' . get_class($objPage->getContent()));	

			// First, add the page content to the body object
			$content = $this->doc->createDocumentFragment();
			$content->appendChild($objPage->getContent());
			$this->body->appendChild($content);

			// Now add each stylesheet
			foreach($objPage->getStyleSheets() as $link)
				$doclink = $this->doc->createDocumentFragment();
				$doclink->appendChild($link);
				$this->head->appendChild($doclink);
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
	 * exports the page as a HTML string 
	 * 
	 * @access public
	 * @returns $string HTML content 
	 */
	public function toHTML()
	{
		$this->dbg('HTML output = ' . $this->doc->saveHTML());
		return $this->doc->saveHTML();

	}	
}
?>
