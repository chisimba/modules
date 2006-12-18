<?php
/**
* Wrapper to dompdf
* http://www.digitaljunkies.ca/dompdf/ 
*
* This class is a wrapper to the dompdf script.  Script takes in a
* standard html file and then returns a pdf document
*
* @author Ryan Whitney, ryan@greenlikeme.org 
*/
require_once('modules/wikiwriter/resources/dompdf/dompdf_config.inc.php');
class dompdfwrapper extends object
{

	// Personal debugger - TODO: REMOVE BEFORE COMMITTING FOR PRODUCTION!
	public function dbg($sErr){
		$handle = fopen('error_log/my_debug.log', 'a');
		fwrite($handle, "[" . strftime("%b %d %Y %H:%M:%S") ."]/classes/wwDocument :: " . $sErr . "\n");
		fclose($handle);
	}
	
    
    /**
    * Constructor
    */
    public function init()
    {
        
    }

	/**
	* Takes in a string as HTML and returns a pdf document 
	*
	* @access public
	* @param string $html HTML content 
	* @return void 
	*/
	public function generatePDF($html)
	{
		try{
			// Load the html, render, and return pdf
			$dompdf = new DOMPDF();
			$dompdf->load_html($html);	
			$dompdf->render();
			//$this->dbg('pdf output: ' . $dompdf->output());
			$dompdf->stream();
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
