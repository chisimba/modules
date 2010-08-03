<?php
/**
 * This class contains the newsletter form on the right column of the short courses page in CfE website
 * 
 * PHP version 5
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
 */

// security check - must be included in all scripts
if (!/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end of security

class newsletter extends object {

    /**
     * Constructor
     */
    public function init() {
	
    }

    /**
     * Method to create the table for the news letter
     * @return string
     */
	public function buildNewsletter()
	{
		//create a newsletter table
	 $newsletter = '<div id="newsLetter"><table>
					   <tr>
					   <td>

					   </td>
					   <td>

					   <form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post">
					   <input type="text" name="p_firstname" value="Name"  maxlength="500" height="5px"/>
			      		   <input type="text" name="p_email"  maxlength="500" value="Email address" /><br>
					   <a href="http://www.google.co.za"><img src = "http://localhost/framework/app/packages/cfe/resources/latest_newsletter.jpeg"   							id="latestNewButton"/></a>
					   <input type="image" src = "http://localhost/framework/app/packages/cfe/resources/signup.jpeg" id="signButton"><br>
                   			   </form>
					   </td>
					   </tr>
					   </table></div>';
	//return the newsletter table	
	return $newsletter;
     
    }
    

    /**
     * Method to show the newsletter
     * @return string
     */
    public function show() 
	{
		return $this->buildNewsletter();
	}
}
?>
