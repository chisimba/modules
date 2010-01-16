<?php
/**
 *
 * Generate outputs for the business cards
 *
 * Create a digital business card that uses microformats and web standards,
 * and display it in a nice, CSS based layout. This class generates output
 * in microformat.
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
 * @package   oembed
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: imageprovider_class_inc.php 1 2010-01-01 16:48:15Z dkeats $
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
* Generate outputs for the business cards
*
* Create a digital business card that uses microformats and web standards,
* and display it in a nice, CSS based layout. This class generates output
* in microformat.
*
* @author Derek Keats
* @package oembed
*
*/
class buscard extends object
{
    /**
    *
    * Constructor for the provider class
    *
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the user object to look up the file owner.
        $this->objUser = $this->getObject('user', 'security');
        // Get an instance of the userparams object to look up additional info.
        $this->objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
        $this->objUserParams->readConfig();
        // Load the style sheet
        $this->loadCss();
    }

    public function show($userId)
    {
        $ret = $this->getFn($userId);
        $ret .= $this->getEmail($userId);
        $ret = "\n\n<div class='vcard'>\n"
          . $ret . "</div>\n\n"
          . '</div>';
        $ret .= $this->getHomePage($userId);
        $ret .= $this->getTwitter($userId);
        $ret .= $this->getDelicious($userId);
        $ret .= $this->getFacebook($userId);
        return $this->addToOuterContainer($ret);
    }

    public function showBlock($userId)
    {

    }

    /**
    *
    * Add the content to an outer DIV layer
    *
    * @param string $ret The content to add to the layer
    * @return string The content inside the layer tags
    *
    */
    private function addToOuterContainer($ret)
    {
        return "<div class='hcardOuter'>$ret</div>";
    }

    /**
    *
    * 
    * @param string $userFullName The full name of the card owner
    * @return string The formatted card heading
    * @access public
    *
    */
    public function getUserWithTagLine($userFullName, $tagLine)
    {
        $ret = "<div class=\"about clearfix\"><h1>$fullname</h1></div>\n"
          . "<p>$tagLine</p>\n";
        return $ret;
    }

    public function getFn($userId)
    {
        $givenName = '<span class="name-wrapper"><span class="given-name">'
          . $this->objUser->getFirstname($userId) . '</span>';
        $surName = '<span class="family-name">'
          . $this->objUser->getSurName($userId) . '</span></span>';
        return '<div class="vcard"><span class="fn n">'
          . $givenName . ' ' . $surName . '</span></div>'
          . "\n\n";
    }

    public function getEmail($userId)
    {
        $email = $this->objUser->email($userId);
         $ret = $this->getLinkIcon("email")
           . '<a class="email" href="mailto:'
           . $email . '">' . $email . '</a><br />';
         return $ret;
    }

    public function getTwitter($userId)
    {
        if ($twit = $this->objUserParams->getValue("twitterurl")) {
            return $this->getLinkIcon("twitter")
              . "<a rel='me' href='$twit' target='_blank'>$twit</a><br />\n";
        }

    }

    public function getDelicious($userId)
    {
        if ($url = $this->objUserParams->getValue("deliciousurl")) {
            return $this->getLinkIcon("delicious")
              . "<a rel='me' href='$url' target='_blank'>$url</a><br />\n";
        }

    }

    public function getFacebook($userId)
    {
        if ($url = $this->objUserParams->getValue("facebookurl")) {
            return $this->getLinkIcon("facebook")
              . "<a rel='me' href='$url' target='_blank'>$url</a><br />\n";
        }

    }

    public function getHomePage($userId)
    {
        if ($url = $this->objUserParams->getValue("homepage")) {
            return $this->getLinkIcon("home")
              . "<a rel='home me' href='$url' target='_blank'>$url</a><br />\n";
        }
    }


    private function getLinkIcon($network)
    {
        $img = $this->getResourceUri("icons/$network.png", "digitalbusinesscard");
        return "<img style='border: 0px none; vertical-align:middle' class='snicon' src='$img' /> ";
    }

    private function loadCss()
    {
        $css = "<link rel=\"stylesheet\" type=\"text/css\" href=\""
          . $this->getResourceUri("css/vcard.css", "digitalbusinesscard")
          . "\" />";
        $this->appendArrayVar('headerParams', $css);
    }

}
?>