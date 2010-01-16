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

    /**
     *
     * Default show method to show the Digital Business card
     *
     * @param <type> $userId
     * @return <type>
     *
     */
    public function show($userId)
    {
        $ret = $this->getUserImage($userId);
        $ret .= $this->getFn($userId);
        $ret .= $this->getEmail($userId);
        $ret = "\n\n<div class='vcard'>\n"
          . $ret . "</div>\n\n"
          . '</div>';
        $ret .= $this->getHomePage($userId);
        $ret .= $this->getTwitter($userId);
        $ret .= $this->getDelicious($userId);
        $ret .= $this->getFacebook($userId);
        $ret .= $this->getDigg($userId);
        $ret .= $this->getFlickr($userId);
        $ret .= $this->getYouTube($userId);
        $ret .= $this->getPicasa($userId);
        $ret .= $this->getLatLong($userId);
        return $this->addToOuterContainer($ret);
    }

    public function showBlock($userId)
    {
        $ret = $this->getUserImage($userId);
        $ret .= $this->getFn($userId);
        $ret .= $this->getEmail($userId);
        $ret = "\n\n<div class='vcard'>\n"
          . $ret . "</div>\n\n"
          . '</div>';
        $ret .= $this->getHomePage($userId, TRUE);
        $ret .= $this->getTwitter($userId, TRUE);
        $ret .= $this->getDelicious($userId, TRUE);
        $ret .= $this->getFacebook($userId, TRUE);
        $ret .= $this->getDigg($userId, TRUE);
        $ret .= $this->getFlickr($userId, TRUE);
        $ret .= $this->getYouTube($userId, TRUE);
        $ret .= $this->getPicasa($userId, TRUE);
        $ret .= $this->getLatLong($userId, FALSE);
        return $this->addToOuterContainer($ret);
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

    public function getEmail($userId, $noText=FALSE)
    {
        $email = $this->objUser->email($userId);
        $icon = $this->getLinkIcon("email");
        if ($noText) {
            return "<a class='email' href='mailto:$email'>$icon</a><br />\n";
        } else {
            return "<a class='url' rel='me' href='mailto:$email'>$icon $email</a><br />\n";
        }
    }

    public function getTwitter($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("twitterurl")) {
            $icon = $this->getLinkIcon("twitter");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }

    }

    public function getDelicious($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("deliciousurl")) {
            $icon = $this->getLinkIcon("delicious");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }

    }

    public function getFacebook($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("facebookurl")) {
            $icon = $this->getLinkIcon("facebook");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }

    public function getDigg($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("diggurl")) {
            $icon = $this->getLinkIcon("digg");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }

    public function getFlickr($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("flickrurl")) {
            $icon = $this->getLinkIcon("flickr");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }

    public function getYouTube($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("youtubeurl")) {
            $icon = $this->getLinkIcon("youtube");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }

    public function getPicasa($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("picasaurl")) {
            $icon = $this->getLinkIcon("picasa");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }


    public function getHomePage($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("homepage")) {
            $icon = $this->getLinkIcon("home");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
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

    private function getLatLong($userId, $showMap=TRUE)
    {
        $latitude = $this->objUserParams->getValue("latitude");
        $longitude = $this->objUserParams->getValue("longitude");
        if ($latitude && $longitude) {
            $ret = '<span class="geo">'
              . '<abbr class="latitude" title="' . $latitude
              . '">' . $latitude . "</abbr>\n"
              .  '<abbr class="longitude" title="'
              . $longitude . '">' . $longitude . "</abbr>\n"
              . "</span>\n";
            $ret = $this->getLinkIcon("earth") . $ret;
            if ($showMap) {
                $ret .= $this->getMap($latitude, $longitude);
            }
            return $ret;
        }
    }

    private function getMap($latitude, $longitude)
    {
        $ret = '<br /><iframe width="425" height="350" '
          . 'frameborder="0" scrolling="no" '
          . 'marginheight="0" marginwidth="0" '
          . 'src="http://maps.google.com/maps?f=q&amp;'
          . 'source=s_q&amp;hl=en&amp;geocode=&amp;q='
          . $latitude .',' . $longitude 
          . '&amp;output=embed"></iframe>';
        return $ret;
    }

    public function getUserImage($userId)
    {
        return $this->objUser->getSmallUserImage($userId);
    }

}
?>