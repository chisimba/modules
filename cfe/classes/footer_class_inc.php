
<?php
/**
 *This class creates two footer layers  
 * 
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @authors  Palesa Mokwena, Thato Selebogo, Mmbudzeni Vhengani
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class footer extends object
{

    /**
     * Holds the text that is written on the footer
     * @var    string
     * @access public
     */
	public $footer_text;
     
     /**
      * Constructor
      */
	public function init()
    	{
		//Create the layers
		$this->layer1 = $this->newObject('layer', 'htmlelements');
		$this->layer2 = $this->newObject('layer', 'htmlelements');
		
		//Create link objects
                $this->ref = $this->newObject('href', 'htmlelements');
		
		//Set the ids and content
		$this->setID();
		$this->setContent();

		
        }
    /**
     * Gives the two layers IDs
     *
     * @access private.
     */
	private function setID()
	{
		//Set the ID's of the layers to be used in the stylesheet
		$this->layer1->id = "footerLayer1";
		$this->layer2->id = "footerLayer2";
	}

    /**
     * A method to to write on the layers
     *
     * @access private.
     */
        private function setContent()
	{
		//Top layer of the footer
		
		$viewer = $this->getObject('viewer','cfe');
		$footerLinks = $viewer->getStory("footer links",0);
             
		$this->loadClass('link','htmlelements');

		$this->layer1->str = $footerLinks;
		//Bottom layer of the footer
		$this->ref->link = $this->uri(array("action"=>"support"));
                $this->ref->text = "Terms of use";
		$footer_text = "2010 by JCSE | ";
		$this->layer2->str = $footer_text . $this->ref->show();	
		
	}

    /**
     * A method to show the two layers
     * 
     * @access public
     */
	public function show()
	{
		return ($this->layer1->show() . $this->layer2->show());
	}

}
?>
