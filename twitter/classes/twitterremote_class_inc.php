<?php
/**
 *
 * Twitter interface elements
 *
 * Twitter is a module that creates an integration between your Chisimba 
 * site using your Twitter account.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   twitter
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* Database accesss class for Chisimba for the module twitter
*
* @author Derek Keats
* @package twitter
*
*/
class twitterremote extends dbtable
{
    public $userName='';
    public $password='';
    public $userAgent='';
    public $headers=array(
      'X-Twitter-Client: ',
      'X-Twitter-Client-Version: ', 
      'X-Twitter-Client-URL: '
    ); 
    public $responseInfo=array(); 

    /**
    *
    * Constructor for the twitterremote class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        
    }
    
    public function initializeConnection($userName, $password)
    {
    	$this->userName = urlencode($userName);
        $this->password = urlencode($password);
    }
    
    public function login()
    {
        $ch = curl_init($url);

        if($postargs !== false){
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
        }
        
    }
    
    /**
    * 
    * Method to update the authenticating user's status.   
    * 
    * @param string $status The text of the status update for the 
    * identified user.  Must not be more than 160 characters and 
    * should not be more than 140 characters to ensure optimal display.
    * 
    */
    public function updateStatus($status)
    {
        $request = 'http://twitter.com/statuses/update.xml';
        $postargs = 'status='.urlencode($status);
        return $this->process($request, $postargs);
    }
    
    public function getStatus()
    {
    	$request = 'http://' . $this->userName . ':' 
           . $this->password . '@twitter.com/users/show/'
           . $this->userName .'.xml';
        $xmlStr = file_get_contents($request);
        return simplexml_load_string($xmlStr);
    }
    
    /**
    * 
    * Method to show the latest status update of the user (i.e. the
    * last message posted)
    * 
    * @access public
    * @param Boolean $showtime TRUE|FALSE If true it shows the time of the post
    * @param Boolean $showimage TRUE|FALSE if true is shows the image avatar of the user
    * @return String The formatted last posted message
    * 
    */
    public function showStatus($showTime=FALSE, $showimage=FALSE)
    {
    	$xml = $this->getStatus();
        $ret = $xml->status->text;
        if ($showTime) {
        	$ret.= "<br />" . $xml->status->created_at;
        }
        if ($showimage){
        	$ret = "<table class=\"tweets\" id=\"mytweets\"><tr><td class=\"tweetcell\"><img src=\"" 
             . $xml->profile_image_url
             . "\" /></td><td class=\"tweetcell\">" . $ret 
             ."</td></tr></table>";
        }
        return $ret;
    }
    

    
    // internal function where all the juicy curl fun takes place
    // this should not be called by anything external unless you are
    // doing something else completely then knock youself out.
    private function process($url,$postargs=false)
    {
        $ch = curl_init($url);

        if($postargs !== false){
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
        }
        
        if($this->username !== false && $this->password !== false)
            curl_setopt($ch, CURLOPT_USERPWD, $this->userName.':'.$this->password);
        
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        $response = curl_exec($ch);
        
        $this->responseInfo=curl_getinfo($ch);
        curl_close($ch);
        
        
        if(intval($this->responseInfo['http_code'])==200){
            if(class_exists('SimpleXMLElement')){
                $xml = new SimpleXMLElement($response);
                return $xml;
            }else{
                return $response;    
            }
        }else{
            return false;
        }
    } 

}
?>
