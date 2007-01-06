<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
} 
// end security check

/**
* htmldoc Object 
*
* This is merely a chisimba class encapsulating the htmldoc tool (http://htmldoc.org) so other modules
* can utilize it.  From the website: HTMLDOC converts Hyper-Text Markup Language ("HTML") input files 
* into indexed HTML, Adobe¨ PostScript¨, or Adobe Portable Document Format ("PDF") files.
*
* @author Ryan Whitney, ryan@greenlikeme.org 
*/
class htmldoc extends object 
{

	// Personal debugger - TODO: REMOVE BEFORE COMMITTING FOR PRODUCTION!
	public function dbg($sErr){
		$handle = fopen('error_log/my_debug.log', 'a');
		fwrite($handle, "[" . strftime("%b %d %Y %H:%M:%S") ."]/classes/htmldoc:: " . $sErr . "\n");
		fclose($handle);
	}


	/**
	 * Variable htmldocPath String identifying the path to the htmldoc binary 
	 */
	 private $htmldocPath = ''; 

	/**
	 * Variable objSysConfig Object for accessing wikiwriter module configuration 
	 */
	public $objSysConfig = '';

	/**
	 * Constructor
 	 */ 
	public function init()
	{
		try
		{
			$this->objSysConfig = & $this->getObject('dbsysconfig', 'sysconfig');

			// First check that we can utilize htmldoc
			// Check configuration file
			if($this->objSysConfig->checkIfSet('HTMLDOC_PATH', 'htmldoc')){
				$this->htmldocPath = $this->objSysConfig->getValue('HTMLDOC_PATH', 'htmldoc');
			}
			// TODO: If that doesn't work, identify the os and check common paths
			// TODO: Add executables
			// TODO: This should be run once and these things checked or checked everytime?
			// TODO: Check that you can run the script
			// If no executables are found or we are unable to run the script, throw an exception
		}
		catch(customException $e)
		{
			echo customException::cleanUp();
			die();
		}
	}


	/**
	 * Renders the pdf from the given html source 
	 * 
	 * @access public
	 * @param string $path path to the html source
	 * @return mixed the pdf content
	 */ 
	public function render( $path)
	{
		//TODO: High risk for failure, should wrap in try/catch
		$this->dbg(' path for running htmldoc = ' .  $this->htmldocPath . 'htmldoc --book -t pdf14 ' . $path);
		return shell_exec($this->htmldocPath . 'htmldoc --book -t pdf14 ' . $path);

	}

}
?>
