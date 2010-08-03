
<?php
/**
 /* This class creates two layers and write on them
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


class left_column_content extends object
{

   /**
    * Constructor
    */
	public function init()
    	{
		// Make 2 layers
		$this->layer1 = $this->newObject('layer', 'htmlelements');
		$this->layer2 = $this->newObject('layer', 'htmlelements');

		// Set default content
		$this->layer1->str = "TOP LEFT BOX";
		$this->layer2->str = "BOTTOM LEFT BOX";

		// Set the layer id's
		$this->setId();

		//build the content
		$this->buildContent();
        }
    /**
     * Give the layers IDs 
     *
     * @access private.
     */
	private function setId()
	{
		$this->layer1->id = "topLeftBox";
		$this->layer2->id = "bottomLeftBox";
	}
    /**
     * This method writes on the two layers
     *
     * @access private.
     */
	private function buildContent()
	{

		// top left box content
		$this->layer1->str = '<h3> <img src = "http://localhost/framework/app/packages/cfe/resources/bullet.png" height = "10px" /> ACADEMICS </h3> <p> As part of our mission to nurture, promote, and inculcate a culture of entrepreneurship in South Africa, one of three strategic focus areas of the Centre for Entrepreneurship (CfE) at Wits Business School (WBS) is research and thought leadership. </p>
<ul>
<li> <a href = "http://www.gmail.com">  Work Paper 1 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 2 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 3 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 4 </a></li> 
<li> <a href = "http://www.gmail.com">  Work Paper 5 </a></li> 
</ul>';

		//bottom left box content
		$this->layer2->str = '<h3><img src = "http://localhost/framework/app/packages/cfe/resources/bullet.png" height = "10px" /> ENTREPRENEURS </h3> 
		<p> The aim of this area is to create a comunity for all entrepreneurs.  It will be measured by the number of log on\'s etc. or by comments made and Q&A\'s 			participated in. </p>
		<a href = "http://www.gmail.com"><img src = "http://localhost/framework/app/packages/cfe/resources/read_more.gif" /></a>
';
	
    /**
     * A method to show the layers and thier content
     *
     * @access public
     */
	}
	public function show()
	{
		return ($this->layer1->show() . $this->layer2->show());
	}

}
?>
