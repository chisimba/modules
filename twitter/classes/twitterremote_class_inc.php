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
* Class to supply a bunch of remote data grabbing methods for the
* module twitter.
*
* @Todo It needs to be re-factored to separate the data
* grabbing from the rendering logic.
*
* @author Derek Keats
* @package twitter
*
*/
class twitterremote extends object
{
    /**
    *
    * @var string $userName The twitter username of the authenticating user
    * @access public
    *
    */
    public $userName='';
    /**
    *
    * @var string $password The twitter password of the authenticating user
    * @access public
    *
    */
    public $password='';
    /**
    *
    * @var string $userAgent The user agent as presented to Twitter
    * @access public
    *
    */
    public $userAgent='Chisimba';
    /**
    *
    * @var string array $headers The HTML headers to be presented to Twitter
    * @access public
    *
    */
    public $headers=array(
      'X-Twitter-Client: Chisimba',
      'X-Twitter-Client-Version: 2.0',
      'X-Twitter-Client-URL: http://avoir.uwc.ac.za/'
    );

    public $responseInfo=array();
    /**
    *
    * @var string object $oC String to hold the curl wrapper object
    * @access public
    *
    */
    public $oC;

    /**
    *
    * Constructor for the twitterremote class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->oC = $this->getObject("curlwrapper","utilities");
    }

    /**
    *
    * Method to initialize the Twitter username and password for
    * the authenticating user
    * @param string $userName The username of the authenticating user
    * @param string $password The password of the authenticating user
    * @access public
    * @return VOID
    *
    */
    public function initializeConnection($userName, $password)
    {
    	$this->userName = urlencode($userName);
        $this->password = urlencode($password);
        $this->oC->userName = $this->userName;
        $this->oC->password = $this->password;
        $this->oC->user_agent = $this->user_agent;
        $this->oC->headers = $this->headers;
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
        $url = 'http://twitter.com/statuses/update.xml';
        $postargs = 'status='.urlencode($status);
        return $this->process($url, $postargs);
    }

    /**
    *
    * Returns and XML Object the authenticating user's followers,
    * each with current status inline when available.
    *
    * @access public
    * $return object An XML object with followers status info
    */
    public function getFollowers()
    {
        $url = 'http://' . $this->userName . ':'
           . $this->password . '@twitter.com/statuses/followers.xml';
        $this->oC->initializeCurl($url);
        $xmlStr = $this->oC->getUrl();
        return simplexml_load_string($xmlStr);
    }

    /**
    *
    * Returns your followers latest status, for example for use in a block
    * @return string Formatted view of followers' status
    * @access public
    *
    */
    public function showFollowers()
    {
        $xml = $this->getFollowers();
        if ($xml) {
            $ret="<table>";
            $objHumanizeDate = $this->getObject("translatedatedifference", "utilities");
            foreach ($xml->user as $user) {
               $img="";
               $link="";
               $fixedTime = strtotime($user->status->created_at);
               $fixedTime = date('Y-m-d H:i:s', $fixedTime);
               $humanTime = $objHumanizeDate->getDifference($fixedTime);
               $link = "<img src=\"" . $user->profile_image_url
                 . "\" align=\"left\" style=\"margin-bottom: 3px; margin-right: 5px;\"/><a href=\"" . $user->url . "\">"
                 . $user->name ."</a> ";
               $text = $user->status->text ."<br />";
               $ret .="<tr><td>" . $link . $text
               . "<span class=\"minute\">"
               . $humanTime . "<br /><br /></span>"
               . "</td></tr>";
            }
            $ret .= "</table>";
        }
        return $ret;
    }

    /**
    *
    * Returns and XML Object the people being followed by the
    * authenticating user each with current status inline when
    * available.
    *
    * @access public
    * $return object An XML object with followers status info
    *
    */
    public function getFollowed($id=false)
    {
        $prefix = 'http://' . $this->userName . ':'
           . $this->password . '@';
        if ($id===false) {
            $url = $prefix . 'twitter.com/statuses/friends.xml';
        } else {
            $url = $prefix . 'twitter.com/statuses/friends/'.urlencode($id).'.xml';
        }
        $this->oC->initializeCurl($url);
        $xmlStr = $this->oC->getUrl();
        return simplexml_load_string($xmlStr);
    }

    public function showFollowed()
    {
        $xml = $this->getFollowed();
        if ($xml) {
            $ret="<table>";
            $objHumanizeDate = $this->getObject("translatedatedifference", "utilities");
            foreach ($xml->user as $user) {
               $img="";
               $link="";
               $fixedTime = strtotime($user->status->created_at);
               $fixedTime = date('Y-m-d H:i:s', $fixedTime);
               $humanTime = $objHumanizeDate->getDifference($fixedTime);
               $link = "<img src=\"" . $user->profile_image_url
                 . "\" align=\"left\" style=\"margin-bottom: 3px; margin-right: 5px;\"/><a href=\"" . $user->url . "\">"
                 . $user->name ."</a> ";
               $text = $user->status->text ."<br />";
               $ret .="<tr><td>" . $link . $text
               . "<span class=\"minute\">"
               . $humanTime . "<br /><br /></span>"
               . "</td></tr>";
            }
            $ret .= "</table>";
        }
        return $ret;
    }


    /**
    *
    * Method to get the status info of the authenticating user
    * @access public
    * $return object An XML object wit$this->passwordh the user's status info
    *
    */
    public function getStatus()
    {
    	$url = 'http://' . $this->userName . ':'
           . $this->password . '@twitter.com/users/show/'
           . $this->userName .'.xml';
        $this->oC->initializeCurl($url);
        $xmlStr = $this->oC->getUrl();
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
        $ret = "<div name=\"myLastTweet\" id=\"myLastTweet\">" . $xml->status->text;
        if ($showTime) {
            $objHumanizeDate = $this->getObject("translatedatedifference", "utilities");
            $fixedTime = strtotime($xml->status->created_at);
            $fixedTime = date('Y-m-d H:i:s', $fixedTime);
            $humanTime = $objHumanizeDate->getDifference($fixedTime);
        	$ret.= "<br /><span class=\"minute\">"
              . $humanTime . "</span>";
        }
        if ($showimage){
        	$ret = "<table class=\"tweets\" id=\"mytweets\"><tr><td class=\"tweetcell\"><img src=\""
             . $xml->profile_image_url
             . "\" /></td><td class=\"tweetcell\">" . $ret
             ."</td></tr></table>";
        }
        return $ret . "</div>";
    }

    /**
    *
    * Returns the 20 most recent updates from non-protected users who have
    * a custom user icon.  This method does not require authentication.
    *
    * @return object An XML object with the public timeline from Twitter
    *
    */
    function getPublicTimeline($sinceid=false)
    {
        $qs='';
        if($sinceid!==false)
            $qs='?since_id='.intval($sinceid);
        $request = 'http://twitter.com/statuses/public_timeline.xml'.$qs;
        $this->oC->initializeCurl($request);
        $xmlStr = $this->oC->getUrl();
        return simplexml_load_string($xmlStr);
    }

    /**
    *
    * Returns the 20 most recent updates from non-protected users who have
    * a custom user icon and formats it into a table for display.
    *
    * @return string A table with the public timeline listed.
    *
    */
    public function showPublicTimeline($sinceid=false)
    {
        $xml = $this->getPublicTimeline($sinceid);
        if ($xml) {
        	$ret="<table>";
        	$objHumanizeDate = $this->getObject("translatedatedifference", "utilities");
	        foreach ($xml->status as $status) {
	           $img="";
	           $link="";
	           $fixedTime = strtotime($status->created_at);
	           $fixedTime = date('Y-m-d H:i:s', $fixedTime);
	           $humanTime = $objHumanizeDate->getDifference($fixedTime);
	           $link = "<a href=\"" . $status->user->url . "\">";
	           $img = $link . "<img src=\""
	             . $status->user->profile_image_url
	             . "\" /></a>";
	           $text = $status->text ."<br />";
	           $ret .="<tr><td>" . $img
	           . "</td><td valign=\"top\">"
	           . $text . "<span class=\"minute\">"
	           . $humanTime . "</span>"
	           . "</td></tr>";
	        }
	        $ret .= "</table>";
        }
    	return $ret;
    }



    // internal function where all the juicy curl fun takes place
    // this should not be called by anything external unless you are
    // doing something else completely then knock youself out.
    // YES I AM PLANNING TO FIX THIS
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
