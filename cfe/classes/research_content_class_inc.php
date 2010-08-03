
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


class research_content extends object
{

   
   /**
    * Constructor
    */
	public function init()
    	{
            //create objects
            $this->pageContent = $this->newObject('csslayout', 'htmlelements');
            $this->objLinks = $this->newObject('links', 'cfe');
            //create a layout
            $this->buildBody();
        }


    /**
     * This method writes on the two layers
     *
     * @access private.
     */
	private function buildBody()
	{
            $research_links = $this->newObject('research_links', 'cfe');

            //set the layout to one column
            $this->pageContent->setNumColumns(2);

           $this->pageContent->setLeftColumnContent('<div id="research"><div id="left"><div id="subhead"><div class="txt"><img src="http://localhost/chisimba2/packages/cfe/resources/research.gif"></a></div></div><div class="bodytxt"><div id="cat_story"><h3>
Research Programme</h3>
<div style="float:right; padding: 10px;"></div><p></p>


<strong>Research Programme</strong>

<p>As part of our mission to nurture, promote and inculcate a culture of entrepreneurship in South Africa, one of three strategic focus areas of the Centre for Entrepreneurship (CfE) at Wits Business School (WBS) is research and thought leadership. </p>
<h4>Purpose of the research programme</h4><p>The primary purpose of this research activity is to inform the development and implementation of educational and support programmes offered by the CfE, as well as to capture the aspirations, needs, and development of ‘eco-system’ participants. The research programme is intended to:
<p></p>
<ul>
<li>Increase awareness of and support for entrepreneurship in Africa</li>
<li>Explore best practice in new venture creation regionally</li>
<li>Establish strategically significant databases that will enhance understanding and enable more effective planning for and evaluation of entrepreneurial development programmes </li>
<li>Act as a catalyst for sharing of knowledge and experience between all stakeholders in the entrepreneurial development arena and to position CfE as a thought leader  </li>
</ul> </p> <p></p>

<strong>Research themes 2010 - 2011</strong>

    <p>The research focuses on delivering a deep understanding in areas critical to new venture creation and sustainability as well as entrepreneurial capacity building.  Research is focused along several tracks within two main focus areas:</p>
    <h5>New venture creation and sustainability</h5>
    <p>This research focuses on opportunity entrepreneurs at each stage of development, namely pre-startup, startup, emerging and growth.<p {line-height= "0px"}></p>

    At pre-start-up stage, research emphasises high school learner, student and graduate perceptions of entrepreneurship and their aspirations to new venture creation.  Women entrepreneurs are also a subject of study through the CfE developed ‘South African Women’s Entrepreneurial Development Index’ (SAWEDI).

    <p></p>A long term in-depth study of entrepreneurial behaviour in the early stages of business development will enhance understanding of the venture creation process and the needs of entrepreneurs, while a study of high performing African businesses will focus on understanding entrepreneurial best practice in the business’ growth phase.
    </p>

    <h5> Entrepreneurial capacity building </h5>
    <p>The CfE research programme also seeks to explore best practice in the education and training of entrepreneurs as well as considering how best to evaluate and enhance entrepreneurial support in general. A special focus of this research will be exploring business planning, new financial models and considering best practice in BBBEE- lead enterprise development programmes.</p>

    <strong><p>Finally, the Centre is engaged in measuring the effectiveness of its own programmes. Research theme definition is reviewed every two years to ensure that the focus of the Centre for Entrepreneurship remains relevant. </p>
    </strong>
<br>



<div style="clear:both;"></div></div></div></div> <!-- end left --><div style="clear:both;"></div></div><!--end research -->
');
            $this->pageContent->setMiddleColumnContent($research_links->show());

	}
	public function show()
	{
		//return the column layout page
		return $this->pageContent->show();
	}
}
?>
