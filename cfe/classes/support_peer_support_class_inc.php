
<?php

/**
 * This is a find your venture class for CfE website.
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


class support_peer_support extends object
{
    /**
    * Constructor
    */
	public function init()
    	{
		//create objects
		$this->pageContent = $this->newObject('csslayout', 'htmlelements');
		$this->objCreateYourVenture = $this->newObject('short_courses_create_your_venture', 'cfe');

		//create a layout
		$this->buildBody();
        }
   /**
    * A method to to divide the page body into two columns 
    *
    * @access private.
    */

	private function buildBody()
	{
                $right_col = $this->newObject('support_right_column', 'cfe');
	
		//set the layout to two columns
		$this->pageContent->setNumColumns(2);
		//write on the left column 
		$this->pageContent->setLeftColumnContent($this->buildContent());
		//write on the right column
		$this->pageContent->setMiddleColumnContent($right_col->show());
	}

    /**
     * This method contains the content that goes to the left column of find your venture page.
     * @return string
     * @access private.
     */
	private function buildContent()
	{
		$content = '<div id="advPage"><h3> PEER SUPPORT  </h3>

                <div id="advPageCont"> <h4> Peer support programme</h4> 


<p>On the grounds that entrepreneurs tend to place highest value on the learning they gain from other entrepreneurs, a self-managed peer support programme has been established for entrepreneurs in the CfE entrepreneurial ecosystem. Peer support programmes represent a framework for personal and business development, providing each group member with:</p>
<lu>
<li>A confidential, safe context for sharing ideas, skills and information</li>
<li>A platform for growth through exploring and integrating business and personal issues</li>
<li>Practical input from like-minded entrepreneurs from the same background who nevertheless might be operating in a diverse range of industries</li>
<li>Mechanisms for personal growth and the development of team-based interactive skills</li>
</lu>
<p>Members learn from each other and exchange information about the entrepreneurial process – in effect they help each other become more effective in their business and personal lives. </p>

<p>Each month, a group of eight to ten entrepreneurs meet to share knowledge, skills and experiences, using a set of procedures that have proven effective in thousands of self-managed peer support groups around the world. Each meeting is of approximately two hours’ duration and follows an established agenda.</p>

<p>Implementing the peer support programme requires that entrepreneurs be assigned to groups, that they be trained in appropriate procedures and processes and that the effectiveness of the groups be measured annually. At first, each group is managed by a trained moderator, to ensure that group protocols are followed and that group members are fully and appropriately trained in dealing with group processes.  As soon as possible, the group becomes managed by its own members and is expected to operate independently of further external support.</p>


</div>
</div>';

		//return the content
		return $content;
	}

    /**
     * A method to show the two colums layout with the contents in both columns
     * 
     * @return string $result The rendered object in HTML code
     * @access public
     */
	public function show()
	{
		//return the two column layout page
		return $this->pageContent->show();
	}

}
?>
