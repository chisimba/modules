
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

class main_content extends object
{
   /**
    * Constructor. Create all objects and call all the methods of classs main_content
    */
	public function init()
    	{
		//Creates a css layout object of 2 columns
		$this->topLayer = $this->newObject('layer', 'htmlelements');
		$this->bottomLayer = $this->newObject('layer', 'htmlelements');
		$this->cssLayout = $this->newObject('csslayout', 'htmlelements');
		
		// Get the content
		$this->leftColumn = $this->newObject('left_column_content', 'cfe');
		$this->middleColumn = $this->newObject('middle_column_content', 'cfe');

		//Get the news letter
		
		$this->createID();
		$this->buildMainContent();
		$this->divideTopLayer();
		$this->newsletter();
		
        }
    /**
     * Give the layers IDs 
     *
     * @access private.
     */	
	private function createID()
	{
		$this->topLayer->id = "topLayer";
		$this->bottomLayer->id = "bottomLayer";
	}
    /**
     * Make a form and put it in a table 
     *
     * @access public.
     */	
	
	public function newsletter()
	{
		//make a form in the bottom layer and put it in a table
		$this->bottomLayer->str = '<div id="bottomLayerTable"><table>
					   <tr>
					   <td>
					   
					   </td>
					   <td>
					   <form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post"> 
					   <input type="text" name="p_firstname" value="Name"  maxlength="500" height="5px"/>
			      		   <input type="text" name="p_email"  maxlength="500" value="Email address" />
			      		   <input type="image" src = "http://localhost/framework/app/packages/cfe/resources/signup.jpeg" id="signUpButton">
					   <a href="http://www.google.co.za"><img src = "http://localhost/framework/app/packages/cfe/resources/latest_newsletter.jpeg"   							id="latestNewsButton"/></a>
                   			   </form>
					   </td>
					   </tr>
					   </table></div>';          
			
	}//<h3><img src = "http://localhost/framework/app/packages/cfe/resources/bullet.png" height = "10px" />CfE NEWSLETTER</h3>

    /**
     * This method divides the toplayer into two columns
     *
     * @access private.
     */
	private function divideTopLayer()
	{
		$this->cssLayout->setNumColumns(2);		
		$this->topLayer->str = $this->cssLayout->show();
	}

    /**
     * Write on the two colums of the top layer
     *
     * @access private.
     */
	private function buildMainContent()
	{
		$this->cssLayout->setLeftColumnContent($this->leftColumn->show());
		$this->cssLayout->setMiddleColumnContent($this->middleColumn->show());	

	}
    /**
     * A method to show the layers
     *
     * @access public
     */
	public function show()
	{
		return ($this->topLayer->show() . $this->bottomLayer->show());
		//return ($this->bottomLayer->show());
	}

}


/*<span>CfE NEWSLETTER<div id="newsletter">            
			<form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post">
			<input type="text" name="p_firstname" value="Name"  maxlength="500"/>
			<input type="text" name="p_email"  maxlength="500" value="Email address" />
			<input type="image" src = "http://localhost/framework/app/packages/cfe/resources/signup_btn.gif">
			</form>
			</div> </span>*/
?>
