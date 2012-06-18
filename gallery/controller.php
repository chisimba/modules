<?php
/**
 * 
 * Image gallery
 * 
 * This a place where you can upload your images and share them with your friends
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
 * @package   gallery
 * @author    Kevin Cyster kcyster@gmail.com
 * @copyright 2011 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
* Controller class for Chisimba for the module gallery
*
* @author Kevin Cyster
* @package gallery
*
*/
class gallery extends controller
{
    
    /**
    * 
    * @var string $objConfig String object property for holding the 
    * configuration object
    * @access public;
    * 
    */
    public $objConfig;
    
    /**
    * 
    * @var string $objLanguage String object property for holding the 
    * language object
    * @access public
    * 
    */
    public $objLanguage;
    /**
    *
    * @var string $objLog String object property for holding the 
    * logger object for logging user activity
    * @access public
    * 
    */
    public $objLog;

    /**
    * 
    * Intialiser for the gallery controller
    * @access public
    * 
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        
        $this->objOps = $this->getObject('galleryops', 'gallery');
        
        // Create an instance of the database class
        $this->objDBgalleries = $this->getObject('dbgalleries', 'gallery');
        $this->objDBalbums = $this->getObject('dbalbums', 'gallery');
        $this->objDBimages = $this->getObject('dbimages', 'gallery');
//        $this->objDBcomments = $this->getObject('dbcomments', 'gallery');
        
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('gallery.js', 'gallery'));
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    
    /**
     * 
     * The standard dispatch method for the gallery module.
     * The dispatch method uses methods determined from the action 
     * parameter of the  querystring and executes the appropriate method, 
     * returning its appropriate template. This template contains the code 
     * which renders the module output.
     * 
     */
    public function dispatch()
    {
        //Get action from query string and set default to view
        $action=$this->getParam('action', 'view');
        /*
        * Convert the action into a method (alternative to 
        * using case selections)
        */
        $method = $this->__getMethod($action);
        // Set the layout template to compatible one
        $this->setLayoutTemplate('layout_tpl.php');
        /*
        * Return the template determined by the method resulting 
        * from action
        */
        return $this->$method();
    }
    
    
    /*------------- BEGIN: Set of methods to replace case selection ------------*/

    /**
    * 
    * Method corresponding to the view action. It shows the default
    * dynamic canvas template, showing you how to create block based
    * view templates
    * @access private
    * 
    */
    private function __view()
    {
        // All the action is in the blocks
        return "gallery_tpl.php";
    }
    
    /**
     *
     * Method corresponding to the ajaxSaveAddGallery action
     * 
     * @access private
     * @return VOID 
     */
    private function __ajaxSaveAddGallery()
    {
        $name = $this->getParam('gallery_name');
        $desc = $this->getParam('gallery_description');
        $shared = $this->getParam('gallery_shared');
        
        $this->objDBgalleries->addUserGallery($name, $desc, $shared);
        
        $content = $this->objOps->showUserGalleries();

        echo $content;
        die();
    }
    
    /**
     * 
     * Method corresponding to the ajaxShowEditGallery action
     * 
     * @access private
     * @return VOID 
     */ 
     public function __ajaxShowEditGallery()
    {
        $galleryId = $this->getParam('gallery_id');
        
        $gallery = $this->objDBgalleries->getGallery($galleryId);

        $data = array();
        $data['gallery_id'] = $gallery['id'];
        $data['name'] = $gallery['name'];
        $data['desc'] = $gallery['description'];
        $data['shared'] = ($gallery['is_shared'] == 1) ? 'on' : '';
        
        echo json_encode($data);
        die();
    }
    
    /**
     *
     * Method corresponding to the ajaxSaveGallery action
     * 
     * @access private
     * @return VOID 
     */
    private function __ajaxSaveEditGallery()
    {
        $galleryId = $this->getParam('edit_gallery_id', NULL);
        $name = $this->getParam('edit_gallery_name');
        $desc = $this->getParam('edit_gallery_description');
        $shared = $this->getParam('edit_gallery_shared');

        $shared = ($shared == 'on') ? 1 : 0;
        
        $this->objDBgalleries->updateGallery($galleryId, $name, $desc, $shared);
        $this->objDBalbums->updateGalleryAlbums($galleryId, $shared);
        $this->objDBimages->updateGalleryAlbumImages($galleryId, $shared);
        
        $content = $this->objOps->showUserGalleries();
        
        echo $content;
        die();
    }
    
    /**
     * 
     * Method corresponding to the deletegallery method
     * 
     * @access private
     * @return VOID
     */
    private function __deletegallery()
    {
        $galleryId = $this->getParam('gallery_id');
        
        $this->objDBgalleries->deleteGallery($galleryId);
        $this->objDBalbums->deleteGalleryAlbums($galleryId);
        $this->objDBimages->deleteGalleryImages($galleryId);
        
        return $this->nextAction('view');
    }
    
    /**
     *
     * Method corresponding to the showalbum method
     * 
     * @access private
     * @return VOID 
     */
    private function __showalbums()
    {
        return 'album_tpl.php';
    }
    
    /**
     *
     * Method corresponding to the ajaxShowAlbums method
     * 
     * @access private
     * @return VOID 
     */
    private function __ajaxShowAlbums()
    {
        $galleryId = $this->getParam('gallery_id');
        
        $content = $this->objOps->showUserGalleryAlbums($galleryId);
        
        echo $content;
        die();
    }
    
    /**
     * 
     * Method corresponding to the ajaxShowAddAlbum action
     * 
     * @access private
     * @return VOID 
     */ 
     public function __ajaxShowAddAlbum()
    {
        $galleryId = $this->getParam('gallery_id');        
        
        echo $galleryId;
        die();
    }
    
    /**
     *
     * Method corresponding to the ajaxSaveAddAlbum action
     * 
     * @access private
     * @return VOID 
     */
    private function __ajaxSaveAddAlbum()
    {
        $galleryId = $this->getParam('album_gallery_id');
        $name = $this->getParam('album_name');
        $desc = $this->getParam('album_description');
        $from = $this->getParam('from');

        $gallery = $this->objDBgalleries->getGallery($galleryId);
        
        $this->objDBalbums->addUserGalleryAlbum($galleryId, $name, $desc, $gallery['is_shared']);
        
        if ($from == 'album')
        {
            $content = $this->objOps->showUserGalleryAlbums($galleryId);            
        }
        else
        {
            $content = $this->objOps->showUserGalleries();
        }
        
        echo $content;
        die();
    }
    
    /**
     * 
     * Method corresponding to the ajaxShowEditAlbum action
     * 
     * @access private
     * @return VOID 
     */ 
     public function __ajaxShowEditAlbum()
    {
        $albumId = $this->getParam('album_id');
        
        $album = $this->objDBalbums->getAlbum($albumId);

        $data = array();
        $data['album_id'] = $album['id'];
        $data['gallery_id'] = $album['gallery_id'];
        $data['name'] = $album['name'];
        $data['desc'] = $album['description'];
        
        echo json_encode($data);
        die();
    }
    
    /**
     *
     * Method corresponding to the ajaxSaveEditAlbum action
     * 
     * @access private
     * @return VOID 
     */
    private function __ajaxSaveEditAlbum()
    {
        $galleryId = $this->getParam('edit_album_gallery_id');
        $albumId = $this->getParam('edit_album_id');
        $name = $this->getParam('edit_album_name');
        $desc = $this->getParam('edit_album_description');
        
        $this->objDBalbums->updateAlbum($albumId, $name, $desc);
        
        $content = $this->objOps->showUserGalleryAlbums($galleryId);            
        
        echo $content;
        die();
    }

    /**
     * 
     * Method corresponding to the deletealbum method
     * 
     * @access private
     * @return VOID
     */
    private function __deletealbum()
    {
        $galleryId = $this->getParam('gallery_id');
        $albumId = $this->getParam('album_id');
        
        $this->objDBalbums->deleteAlbum($albumId);
        $this->objDBimages->deleteAlbumImages($albumId);
        
        return $this->nextAction('showalbums', array('gallery_id' => $galleryId));
    }
    
    /**
     *
     * Method corresponsing to the ajaxShowAddPhoto action
     *  
     * @access private
     * @return VOID
     */
    private function __ajaxShowUploadPhoto()
    {
        return $this->objOps->ajaxShowUploadPhoto();
    }
    
    /**
     *
     * Method corresponding to the ajaxShowNewAlbumInputs action 
     * 
     * @access private
     * @return VOID
     */
    private function __ajaxShowNewAlbumInputs()
    {
        return $this->objOps->ajaxShowNewAlbumInputs();
    }
    
    /**
     *
     * Method corresponding to the ajaxSavePhoto method
     * 
     * @access private
     * @return VOID 
     */
    private function __ajaxSavePhotos()
    {
        $result = $this->objOps->doUpload();
        
        return $this->nextAction('showimages', array('album_id' => $result));
    }
    
    /**
     *
     * Method correspondind to the showimages method
     * 
     * @access private
     * @return template The template to display 
     */
    private function __showimages()
    {
        return 'images_tpl.php';
    }
    
    /**
     * 
     * Method to return an error when the action is not a valid 
     * action method
     * 
     * @access private
     * @return string The dump template populated with the error message
     * 
     */
    private function __actionError()
    {
        return 'dump_tpl.php';
    }
    
    /**
    * 
    * Method to check if a given action is a valid method
    * of this class preceded by double underscore (__). If it __action 
    * is not a valid method it returns FALSE, if it is a valid method
    * of this class it returns TRUE.
    * 
    * @access private
    * @param string $action The action parameter passed byref
    * @return boolean TRUE|FALSE
    * 
    */
    private function __validAction(& $action)
    {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 
     * Method to convert the action parameter into the name of 
     * a method of this class.
     * 
     * @access private
     * @param string $action The action parameter passed byref
     * @return stromg the name of the method
     * 
     */
    private function __getMethod(& $action)
    {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }
    
    /*------------- END: Set of methods to replace case selection ------------*/
    


    /**
     *
     * This is a method to determine if the user has to 
     * be logged in or not. Note that this is an example, 
     * and if you use it view will be visible to non-logged in 
     * users. Delete it if you do not want to allow annonymous access.
     * It overides that in the parent class
     *
     * @return boolean TRUE|FALSE
     *
     */
    public function requiresLogin()
    {
        $action=$this->getParam('action','NULL');
        switch ($action)
        {
            default:
                return TRUE;
                break;
        }
     }
}
?>
