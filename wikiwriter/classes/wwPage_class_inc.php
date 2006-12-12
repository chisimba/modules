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

	// Personal debugger - TODO: REMOVE BEFORE COMMITTING FOR PRODUCTION!
	public function dbg($sErr){
		$handle = fopen('error_log/my_debug.log', 'a');
		fwrite($handle, "[" . strftime("%b %d %Y %H:%M:%S") ."]/classes/wwPage :: " . $sErr . "\n");
		fclose($handle);
	}

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
	}

	/**
	 * Set Content
	 * @access public 
	 * @param DomElement $elm 
	 * @return void
	 */
	public function setContent($elm)
	{
		$this->dbg('Setting content, check for children = ' . $elm->hasChildNodes());
		$this->elmContent = $elm;
	}

	/**
	 * Get Content
	 * @access public 
	 * @return DomElement 
	 */
	public function getContent()
	{
		$this->dbg('getting content, check for children = ' . $this->elmContent->hasChildNodes());
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
		
		$this->dbg(' link class = ' . get_class($link) . ", and " . $link->getAttribute('rel'));
		array_push($this->stylesheets, $link);
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
