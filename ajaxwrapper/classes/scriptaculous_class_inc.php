<?php

/**
* Class to load the Prototype and Scriptaculous JavaScript
*
* This class merely loads the JavaScript for Prototype and Scriptaculous effects
* It is not a wrapper. Developers still need to code their own JS functions
*
* @author Tohir Solomons
* @package ajaxwrapper
*/
class scriptaculous extends object
{
	/**
	* Constructor
	*/
	public function init() {}
	
	/**
	* Method to load the Prototype JavaScript
	*
	* This function locates the Prototype JS and adds it to the headerParams of a page template
	*/
	public function loadPrototype()
	{
		// Suppress application/xhtml+xml mimetype
		$this->setVar('pageSuppressXML', TRUE);
		
		// Load JS
		return $this->appendArrayVar('headerParams', $this->getJavascriptFile('prototype/1.5.0_rc1/prototype.js', 'ajaxwrapper'));
	}
	
	/**
	* Method to load the Scriptaculous JavaScript
	*
	* This function locates the Scriptaculous JS and adds it to the headerParams of a page template
	* It also loads the Prototype JS, since it depends on it.
	*/
	public function show()
	{
		// Add Scriptaculous to Header
		return $this->appendArrayVar('headerParams', $this->putScriptaculous());
	}
    
    /**
    * Method to return the JavaScript Libraries
    */
    public function putScriptaculous()
    {
        $this->setVar('pageSuppressXML', TRUE);
        return $this->getJavascriptFile('prototype/1.5.0_rc1/prototype.js', 'ajaxwrapper').$this->getJavascriptFile('scriptaculous/1.6.5/scriptaculous.js', 'ajaxwrapper');
    }
}

?>