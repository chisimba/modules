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
* @package twitter
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

    }


    /**
    *
    * Method to extract the components of the provided URL and set them
    * as class properties
    * 
    * @access public
    * @return TRUE | FALSE True if the image URL is valid, false if not.
    *
    */
    public function extractComponents($imageUrl)
    {
        if (!$this->testHttp($imageUrl)) {
            return FALSE;
        } else {
            $ar = explode("/", $imageUrl);
            $objFileDb = $this->getObject('dbfile', 'filemanager');
            $fileName =  $this->getFileName($ar);
            $fileInfoAr = $objFileDb->getRow('filename', $fileName);
            $fileId = $fileInfoAr['id'];
            $fileOwner = $fileInfoAr['userid'];
            $this->title = $fileInfoAr['description'];
            unset($objFileDb);
            $objUser = $this->getObject('user', 'security');
            $fileOwner = $objUser->fullName($fileOwner);
            $mediaInfo = $this->getObject("dbmediafileinfo", "filemanager");
            $mediaInfoAr = $mediaInfo->getRow('fileid', $fileId);
            $author_url = "";

            $this->type = "photo";
            $this->version = "1.0";
            $this->author_name = $objUser->fullName($fileOwner);
            $objConfig = $this->getObject('altconfig', 'config');
            $this->provider_name = $objConfig->getSiteName();
            $siteRoot = $objConfig->getsiteRoot();
            $siteRootPath = $objConfig->getsiteRootPath();
            unset($objConfig);
            $thumbFile = "usrfiles/filemanager_thumbnails/"
              . $fileId . ".jpg";
            $size = getimagesize($siteRootPath . $thumbFile);
            $this->thumbnail_url = $siteRoot . $thumbFile;
            $this->thumbnail_width = $size[0];
            $this->thumbnail_height = $size[1];
            $this->provider_url = $this->uri(array());
            $this->author_url = $this->provider_url;
            $this->cache_age = 600;
            $this->url = $imageUrl;
            $this->width = $mediaInfoAr['width'];
            $this->height = $mediaInfoAr['height'];
            $ar = $this->createArray();
            //$objJson = $this->getObject('jsonutils', 'utilities');
            header("Content-Type: application/json");
            $json = json_encode($ar);
            echo $json; die(); // This is crude. I need to make a template.
        }
    }

    private function testHttp($imageUrl)
    {
         return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $imageUrl);
    }

    private function getFileName($ar)
    {
        return $ar[count($ar)-1];
    }

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
}
?>