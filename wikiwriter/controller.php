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
     * Method to initialise the wiki object
     * 
     * @access public 
     */
    public function init()
    {
		try{
			$this->objDomPDFWrapper = &$this->getObject('dompdfwrapper', 'wikiwriter');
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
     * @access public
     * @param string $action Action to be performed
     * @return mixed Name of template to be viewed or function to call
     */
	 private function publish($urls, $format)
	 {
		try{
			// First, grab all the URLs
			// TODO: Parse for an array and grab each piece of content
			$sHTML = file_get_contents($urls);
			// Regex the content

			// Get PDF rendering of the content
			//$sPDF = $this->objDomPDFWrapper->generatePDF(&$sHTML); 
			$this->objDomPDFWrapper->generatePDF(&$sHTML); 

			// Prepare for returning the PDF
			//$this->setVarByRef('content', $sPDF);
			//$this->setPageTemplate('downloadwikibook_page_tpl.php');
			//return "downloadwikibook_tpl.php";
		}
		catch(customException $e) {
			//oops, something not there - bail out
			echo customException::cleanUp();
			//we don't want to even attempt anything else right now.
			die();
		}
	 }
} 
	 
?>
