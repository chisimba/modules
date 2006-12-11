<?php
/**
* WikiWriterPage Object 
*
* This class is a simple object for storing page information 
* like wiki content, style sheet links, etc
*
* @author Ryan Whitney, ryan@greenlikeme.org 
*/
class wwPage extends object
{

	/**
    * @var DomElement $elmContent DomElement of the Wiki content 
    */
    private $elmContent;

    /**
     * @var array $stylesheets DomElement StyleSheet Links 
    */
    private $stylesheets = array();

	/**
	 * Constructor
 	 */ 
	public function init()
	{
		//$this->stylesheets = new array();
	}

	/**
	 * Set Content
	 * @access public 
	 * @param DomElement $elm 
	 * @return void
	 */
	public function setContent($elm)
	{
		$this->elmContent = $elm;
	}

	/**
	 * Get Content
	 * @access public 
	 * @return DomElement 
	 */
	public function getContent()
	{
		return $this->elmContent;
	}

	/**
	 * add StyleSheet 
	 * @access public 
	 * @param string $link DomElment of a stylesheet link
	 * @return void
	 */
	public function addStyleSheet($link)
	{
		array_push($link, $this->stylesheets);
	}

	/**
	 * Get StyleSheets 
	 * @access public 
	 * @return array CSS URLs 
	 */
	public function getStyleSheets()
	{
		return $this->stylesheets;
	}

}
?>
