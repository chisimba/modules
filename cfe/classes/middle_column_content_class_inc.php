
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

class middle_column_content extends object
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
		$this->layer1->str = "TOP MIDDLE BOX";
		$this->layer2->str = "BOTTOM MIDDLE BOX";

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
		$this->layer1->id = "topMiddleBox";
		$this->layer2->id = "bottomMiddleBox";
	}
    /**
     * This method writes on the layers
     *
     * @access private.
     */
	private function buildContent()
	{
		// TOP MIDDLE CONTENT BOX
		$this->layer1->str = '<h3><img src = "http://localhost/framework/app/packages/cfe/resources/bullet.png" height = "10px" /> SHORT COURSES </h3> 
<ul>
<li> <a href = "http://localhost/framework/app/index.php?module=cfe_content">  Create your Venture </a> <p> Certificate of competence in Entrepreneurship and new venture creation. </p> </li> 
<li> <a href = "http://www.gmail.com">  Start your Venture </a> <p> Certificate of attendance in new venture creation. </p> </li>
<li> <a href = "http://www.gmail.com">  Build your Venture </a> <p> Certificate of attendance in new venture development. </p> </li>
<li> <a href = "http://www.gmail.com">  Grow your Venture </a> <p> Certificate of attendance in new venture growth. </p> </li>
<li> <a href = "http://www.gmail.com">  Plan your Venture </a> <p> Certificate of attendance in business planning for entrepreneurs. </p> </li>
</ul>
';

		// BOTTOM MIDDLE CONTENT BOX
		$this->layer2->str = '<h3><img src = "http://localhost/framework/app/packages/cfe/resources/bullet.png" height = "10px" /> VISION </h3> 
		<p> The vision of the Centre for Entrepreneurship is to become the centre of excellence in enrepreneurship development in the developing world.  In doing  			so, we contribute to the attainment of the University\'s vision and contribute directly to the national goal of wealth and job creation. </p>
		<a href = "http://www.gmail.com"><img src = "http://localhost/framework/app/packages/cfe/resources/read_more.gif" /></a>
		<a href = "http://www.gmail.com"><img src = "http://localhost/framework/app/packages/cfe/resources/board.gif" align="right" /></a>
		<a href = "http://www.gmail.com"><img src = "http://localhost/framework/app/packages/cfe/resources/goals.gif" align="right" /></a>';
	}
    /**
     * A method to show the layers and thier content
     *
     * @access public
     */
	public function show()
	{
		return ($this->layer1->show() . $this->layer2->show());
	}

}
?>
