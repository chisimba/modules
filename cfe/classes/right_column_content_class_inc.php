
<?php
/**
 /* This class creates four layers and write on them
 * 
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
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


class right_column_content extends object
{

   /**
    * Constructor
    */
	public function init()
    	{
		// Make 4 layers
		$this->layer1 = $this->newObject('layer', 'htmlelements');
		$this->layer2 = $this->newObject('layer', 'htmlelements');
		$this->layer3 = $this->newObject('layer', 'htmlelements');
		$this->layer4 = $this->newObject('layer', 'htmlelements');

		// Set default content of each layer
		$this->layer1->str = "RIGHT BOX 1";
		$this->layer2->str = "RIGHT BOX 2";
		$this->layer3->str = "RIGHT BOX 3";
		$this->layer4->str = "RIGHT BOX 4";

		// Set the layer id's
		$this->setId();

		//build the content of each layer
		$this->buildContent();
        }

    /**
     * Give the layers IDs 
     *
     * @access private.
     */
	private function setId()
	{
		$this->layer1->id = "rightBox1";
		$this->layer2->id = "rightBox2";
		$this->layer3->id = "rightBox3";
		$this->layer4->id = "rightBox4";
	}

    /**
     * This method writes on the two layers
     *
     * @access private.
     */
	private function buildContent()
	{
		$this->layer1->str = ' ';

		//create a table for the links
		$this->layer2->str = '<table border = 0><tr><td> <a href = "http://www.gmail.com">  Vision</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Goals</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Board Members</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Academics</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Short Courses</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Our Partners</a></td></tr>
							<tr><td> <a href = "http://www.gmail.com">  Enterpreneurs</a></td></tr></table>';
		
		//put a picture link on the 3rd layer of the right column
		
		$this->layer3->str = '<a href = "http://www.gmail.com"><img src = "http://localhost/framework/app/packages/cfe/resources/GEW.jpeg"></a>';
		//Content of the 4th layer
		$this->layer4->str = '<div id="partners">yes</div><div id="ourpatner"><p>Our Partners include: </p>
					<ul>
					<li><a href = "www.google.co.za"> Dti</a> </li>
					<li><a href = "www.google.co.za"> Business Partners </a> </li>
					<li><a href = "www.google.co.za"> IDC </a></li>
					<li><a href = "www.google.co.za"> Lamberti Foundation </a> </li>
					<li><a href = "www.google.co.za"> Duke University </a> </li>
					</ul></div>';
	}
    /**
     * A method to show the layers
     *
     * @access public
     */
	public function show()
	{
		return ($this->layer1->show() . $this->layer2->show() . $this->layer3->show() . $this->layer4->show());
	}

}
?>
