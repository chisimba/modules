<?php
/**
 *
 * An image provider for oembed
 *
 * An image provider for oembed. oEmbed is an open format designed to allow
 * embedding content from a website into another page. This content is of the
 * types photo, video, link or rich. An oEmbed exchange occurs between a
 * consumer and a provider. A consumer wishes to show an embedded representation
 * of a third-party resource on their own website, such as a photo or an
 * embedded video. A provider implements the oEmbed API to allow consumers to
 * fetch that representation. This is a provider for images stored in Chisimba
 * using Chisimba's file manager.
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
* An image provider for oembed
*
* An image provider for oembed. oEmbed is an open format designed to allow
* embedding content from a website into another page. This content is of the
* types photo, video, link or rich. An oEmbed exchange occurs between a
* consumer and a provider. A consumer wishes to show an embedded representation
* of a third-party resource on their own website, such as a photo or an
* embedded video. A provider implements the oEmbed API to allow consumers to
* fetch that representation. This is a provider for images stored in Chisimba
* using Chisimba's file manager.
*
* @author Derek Keats
* @package oembed
*
*/
class imageprovider extends object
{
    // Note that these properties violate naming standards in Chisimba
    // but that is necessary for the oembed naming standards.
   
    public $type;
    public $version;
    public $title;
    public $author_name;
    public $author_url;
    public $provider_name;
    public $provider_url;
    public $cache_age;
    public $thumbnail_url;
    public $thumbnail_width;
    public $thumbnail_height;
    public $url;
    public $width;
    public $height;

    /**
    *
    * Constructor for the imageprovider class
    *
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }


    /**
    *
    * Method to extract the components of the provided URL and set them
    * as class properties. It also sets an err property if it fails to
    * generate the JSON for any reason.
    *
    * @param string $imageUrl The URL for the image to provide.
    * @access public
    *
    * @return boolean TRUE|FALSE True if the image URL produces valid JSON,
    *   false if not
    *
    */
    public function extractComponents($imageUrl)
    {
        if (!$this->testHttp($imageUrl)) {
            $this->err = "404 Not Found";
            return FALSE;
        } else {
            // Instantiate the configuration object.
            $objConfig = $this->getObject('altconfig', 'config');
            // Get the site root as a URL.
            $siteRoot = $objConfig->getsiteRoot();
            // Get the site root as a file system path.
            $siteRootPath = $objConfig->getsiteRootPath();
            // Get the site name to make it the provider_name.
            $this->provider_name = $objConfig->getSiteName();
            // Unset the config object as we don't need it any more.
            unset($objConfig);
            // Take off the siteRoot from the image URL.
            $imageForExplode = str_replace($siteRoot, NULL, $imageUrl, $count);
            // If we didn't remove the siteRoot then it can't be a local image
            if ($count == 0) {
                $this->err = "404 Not Found";
                return FALSE;
            }
            // Verify that the file exists.
            if (!file_exists($siteRootPath . $imageForExplode)) {
                $this->err = "404 Not Found";
                return FALSE;
            }
            // Explode it into an array, the easiest way to get the file name
            $ar = explode("/", $imageForExplode);
            // Get an insteance of the filemanager database connector.
            $objFileDb = $this->getObject('dbfile', 'filemanager');
            // Get the filename from the exploded array
            $fileName =  $this->getFileName($ar);
            // Do a reverse lookup on the filename from the filemanager data.
            $fileInfoAr = $objFileDb->getRow('filename', $fileName);
            $fileId = $fileInfoAr['id'];
            $fileUser = $fileInfoAr['userid'];
            $this->title = $fileInfoAr['description'];
            unset($objFileDb);
            // Get an instance of the user object to look up the file owner
            $objUser = $this->getObject('user', 'security');
            $fileOwner = $objUser->fullName($fileUser);
            // Get an instane of the media metadata information object from filemanager.
            $mediaInfo = $this->getObject("dbmediafileinfo", "filemanager");
            $mediaInfoAr = $mediaInfo->getRow('fileid', $fileId);
            // Start setting the properties that will generate the json.
            $this->type = "photo";
            $this->version = "1.0";
            $this->author_name = $objUser->fullName($fileUser);
            // Get the thumbnail from standard location.
            $thumbFile = "usrfiles/filemanager_thumbnails/"
              . $fileId . ".jpg";
            $size = getimagesize($siteRootPath . $thumbFile);
            $this->thumbnail_url = $siteRoot . $thumbFile;
            $this->thumbnail_width = $size[0];
            $this->thumbnail_height = $size[1];
            $this->provider_url = $siteRoot;
            $this->author_url = $this->provider_url;
            $this->cache_age = 600;
            $this->url = $imageUrl;
            $this->width = $mediaInfoAr['width'];
            $this->height = $mediaInfoAr['height'];
            // Turn the appropriate class properties into an array.
            $ar = $this->createArray();
            // Set either XML or json with the default being json.
            if ($this->asXml()) {
                $this->json = NULL;
                $this->xml = $this->makeXml($ar);
            } else {
                $this->xml = NULL;
                $this->json = $this->makeJson($ar);
            }
            return TRUE;
        }
    }

    /**
    * Method to test if the link is valid HTML
    *
    * @param string $imageUrl The URL for the image being tested
    * @return boolean TRUE | FALSE
    * @access private
    *
    */
    private function testHttp($imageUrl)
    {
         return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $imageUrl);
    }

    /**
    * Method to get the filename from the exploded array
    *
    * @param string array $ar The exploded array from the URL
    * @return string The filename for the image
    * @access private
    * 
    */
    private function getFileName($ar)
    {
        return $ar[count($ar)-1];
    }

    /**
    * Method to create an array from the class properties
    *
    * @return sting array THe array of keys and values
    * @access private
    *
    */
    private function createArray()
    {
        $ar = array(
          'type' => $this->type,
          'version' => $this->version,
          'title' => $this->title,
          'author_name' => $this->author_name,
          'author_url' => $this->author_url,
          'provider_name' => $this->provider_name,
          'provider_url' => $this->provider_url,
          'cache_age' => $this->cache_age,
          'thumbnail_url' => $this->thumbnail_url,
          'thumbnail_width' => $this->thumbnail_width,
          'thumbnail_height' => $this->thumbnail_height,
          'url' => $this->url,
          'width' => $this->width,
          'height' => $this->height);
        return $ar;
    }

    /**
    *
    * Make json from the array
    *
    * @access private
    * @param string $ar The array of keys/values to make into json
    * @return string the JSON code
    * 
    */
    private function makeJson($ar)
    {
        $weirdThingy = $this->getParam("callback", NULL);
        if ($weirdThingy) {
            return $weirdThingy . "(" . $this->json = json_encode($ar) . ")";
        } else {
            return $this->json = json_encode($ar);
        }
        
    }

    /**
    *
    * Make XML from the array
    *
    * @access private
    * @param string $ar The array of keys/values to make into XML
    * @return string The XML as a string
    * 
    */
    private function makeXml($ar)
    {
        $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<oembed>\n";
        foreach ($ar as $key=>$value) {
            $key = strtolower($key);
            $xml .= "    <$key>$value</$key>\n";
        }
        $xml .= "</oembed>\n";
        return $xml;
    }

    /**
    * Determine from the querystring if they want XML or JSON.
    *
    * @access private
    * @return boolean TRUE|FALSE
    */
    private function asXml()
    {
        if ($this->getParam('format', 'json') == 'xml') {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>