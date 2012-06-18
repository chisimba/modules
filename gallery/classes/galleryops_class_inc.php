<?php
/**
 *
 * The operations class for the image Gallery
 *
 * The operations class for Image gallery. This a place where you can upload your images and share them with your friends.
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
 * @version    0.001
 * @package    gallery
 * @author     Kevin Cyster kcyster@gmail.com
 * @copyright  2010 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 * 
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
// end security check

/**
 * 
 * The operations class for Image gallery.
 *
 * The operations class for Image gallery. This a place where you can upload your images and share them with your friends.
 *
 * @category  Chisimba
 * @package    gallery
 * @author     Kevin Cyster kcyster@gmail.com
 * @version   0.001
 * @copyright 2010 AVOIR
 *
 */
class galleryops extends object
{
    /**
     * 
     * The title of the block
     *
     * @access public
     * @var string
     */
    public $title;
    
    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() 
    {
        try 
        {
            // Load core system objects.
            $this->objUserContext = $this->getObject('usercontext', 'context');
            $this->objUser = $this->getObject('user', 'security');
            $this->userId = $this->objUser->userId();
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfirm = $this->newObject('jqueryconfirm', 'utilities');
            $this->objSvars = $this->getObject('serializevars', 'utilities');
            $this->objModules = $this->getObject('modules', 'modulecatalogue');
            $this->objMime = $this->getObject('mimetypes', 'files');
            $this->objDir = $this->getObject('mkdir', 'files');
            $this->objArchive = $this->getObject('archivefactory', 'archivemanager');
            $this->objFileMan = & $this->getObject('dbfile', 'filemanager');
            $this->objUpload = $this->newObject('upload', 'filemanager');
            $this->objAnalyzeMediaFile = $this->getObject('analyzemediafile', 'filemanager');
            $this->objMediaFileInfo = $this->getObject('dbmediafileinfo', 'filemanager');
            $this->objThumbnails = $this->getObject('thumbnails', 'filemanager');

            $this->leftLabel = $this->objLanguage->languageText('mod_htmlelements_charactersleft', 'htmlelements', 'ERROR: mod_htmlelements_charactersleft');
            
            // Load html elements.
            $this->objIcon = $this->newObject('geticon', 'htmlelements');
            $this->objTable = $this->loadClass('htmltable', 'htmlelements');
            $this->objInput = $this->loadClass('textinput', 'htmlelements');
            $this->objText = $this->loadClass('textarea', 'htmlelements');
            $this->objForm = $this->loadClass('form', 'htmlelements');
            $this->objCheck = $this->loadClass('checkbox', 'htmlelements');
            $this->objLink = $this->loadClass('link', 'htmlelements');
            
            // Load db classes,
            $this->objDBgalleries = $this->getObject('dbgalleries', 'gallery');
            $this->objDBalbums = $this->getObject('dbalbums', 'gallery');
            $this->objDBimages = $this->getObject('dbimages', 'gallery');
            //$this->objDBcomments = $this->getObject('dbcomments', 'gallery');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     *
     * Method to generate an error string for display
     * 
     * @access private
     * @param string $errorText The error string
     * @return string $string The formated error string
     */
    private function error($errorText)
    {
        $error = $this->objLanguage->languageText('word_error', 'system', 'WORD: word_error, not found');
        
        $this->objIcon->title = $error;
        $this->objIcon->alt = $error;
        $this->objIcon->setIcon('exclamation', 'png');
        $errorIcon = $this->objIcon->show();
        
        $string = '<span style="color: red">' . $errorIcon . '&nbsp;<b>' . $errorText . '</b></span>';
        return $string;
    }
    
    /**
     *
     * Method to show the main image gallery display
     * 
     * @access public
     * @return string $string The display string 
     */
    public function showMain()
    {
        $objTooltip = $this->newObject('tooltip', 'jquerycore');

        $content = $this->showUserGalleries();
        
        $string = '<div id="image_gallery">' . $content . '</div>';
        $string .= $this->showAddGallery();
        $string .= $this->showEditGallery();
        $string .= $this->showAddAlbum();
        $string .= $this->showEditAlbum();
        $string .= $this->showUpload();
        
        return $string;
    }
    
    /**
     *
     * Method to show the content for the main diaplay
     * 
     * @access public
    * @return string $string The display string 
     */
    public function showUserGalleries()
    {
        $noGalleriesLabel = $this->objLanguage->languageText('mod_gallery_nogalleries', 'gallery', 'ERROR: mod_gallery_nogalleries');        
        $galleriesLabel = $this->objLanguage->languageText('mod_gallery_mygalleries', 'gallery', 'ERROR: mod_gallery_mygalleries');
        $addGalleryLabel = $this->objLanguage->languageText('mod_gallery_addgallery', 'gallery', 'ERROR: mod_gallery_addgallery');
        $defineLabel = $this->objLanguage->languageText('mod_gallery_definegallery', 'gallery', 'ERROR: mod_gallery_definegallery');
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_system');
        $descLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $emptyLabel = $this->objLanguage->languageText('word_empty', 'system', 'ERROR: word_empty');
        $oneAlbumLabel = $this->objLanguage->languageText('mod_gallery_onealbum', 'gallery', 'ERROR: mod_gallery_onealbum');
        $oneImageLabel = $this->objLanguage->languageText('mod_gallery_oneimage', 'gallery', 'ERROR: mod_gallery_oneimage');
        $sharedLabel = $this->objLanguage->languageText('mod_gallery_shared', 'gallery', 'ERROR: mod_gallery_shared');
        $yesLabel = $this->objLanguage->languageText('word_yes', 'system', 'ERROR: word_yes');
        $noLabel = $this->objLanguage->languageText('word_no', 'system', 'ERROR: word_no');
        $clickLabel = $this->objLanguage->languageText('mod_gallery_galleryclick', 'gallery', 'ERROR: mod_gallery_galleryclick');
        $doubleLabel = $this->objLanguage->languageText('mod_gallery_gallerydoubleclick', 'gallery', 'ERROR: mod_gallery_gallerydoubleclick');

        $galleries = $this->objDBgalleries->getUserGalleries();

        $string = '<h1>' . $galleriesLabel . '</h1>';
        $string .= '<h3><span class="success">' . $defineLabel . '</span></h3>';

        if (empty($galleries))
        {
            $string .= $this->error($noGalleriesLabel);            
        }

        $this->objIcon->setIcon('picture_add', 'png');
        $this->objIcon->title = $addGalleryLabel;
        $this->objIcon->alt = $addGalleryLabel;
        $addGalleryIcon = $this->objIcon->show();

        $string .= '<div><a href="#" id="add_gallery">' . $addGalleryIcon . '&nbsp;' . $addGalleryLabel . '</a></div>';
        
        if (!empty($galleries))
        {
            foreach ($galleries as $gallery)
            {
                $albums = $this->objDBalbums->getUserGalleryAlbums($gallery['id']);
                $images = $this->objDBimages->getUserGalleryImages($gallery['id']);
                
                $string .= '<div class="gallery" id="' . $gallery['id'] . '">';
                
                if (empty($gallery['cover_image_id']))
                {
                    $this->objIcon->setIcon('no_photo', 'png');
                    $this->objIcon->title = '';
                    $this->objIcon->alt = '';
                    $image = $this->objIcon->show();
                }
                else
                {
                    $image = $this->objDBimages->getImage($gallery['cover_image_id']);
                }

                $random = time() . '_' . mt_rand();
                $objTooltip = $this->newObject('tooltip', 'jquerycore');
                $objTooltip->setCssId($random);
                $objTooltip->setAutoAppendScript(FALSE);
                $objTooltip->load();
                $script = $objTooltip->script;
                
                if (count($albums) > 0)
                {
                    $title = $clickLabel . '<br />' . $doubleLabel;
                }
                else
                {
                    $title = $clickLabel;
                }
                
                $string .= $script . '<div id="' . $random . '" title="' . $title . '">' . $image . '</div>';
                
                switch (count($albums))
                {
                    case 0:
                        $string .= '<br /><em class="warning">(' . strtolower($emptyLabel) . ')</em>';
                        break;
                    case 1:
                        $string .= '<br /><em class="warning">' . $oneAlbumLabel . '</em>';
                        break;
                    default:
                        $array = array('count' => count($albums));
                        $string .= '<br /><em class="warning">' . $this->objLanguage->code2Txt('mod_gallery_albumcount', 'gallery', $array, 'ERROR: mod_gallery_albumcount') . '</em>';
                        break;                        
                }
                
                if (count($images) == 1)
                {
                    $string .= '<br /><em class="warning">' . $oneImageLabel . '</em>';
                }
                elseif (count($images) > 1)
                {
                    $array = array('count' => count($images));
                    $string .= '<br /><em class="warning">' . $this->objLanguage->code2Txt('mod_gallery_imagecount', 'gallery', $array, 'ERROR: mod_gallery_imagecount') . '</em>';
                }

                $shared = ($gallery['is_shared'] == 1) ? $yesLabel : $noLabel;
                
                $string .= '<div id="shared_' . $gallery['id'] . '"><b>' . $sharedLabel . ': </b>' . $shared . '</div>';
                $string .= '<div id="name_' . $gallery['id'] . '"><b>' . $nameLabel . ': </b>' . $gallery['name'] . '</div>';
                $string .= '<div id="desc_' . $gallery['id'] . '"><b>' . $descLabel . ': </b>' . $gallery['description'] . '</div>';
                $string .= '<div class="gallery_options" id="gallery_options_' . $gallery['id'] . '" style="display: none;">' . $this->showGalleryOptions($gallery['id']) . '</div>';
                $string .= '</div>';
            }
        }

        return $string;
    }
    
    /**
     *
     * Method to show the add gallery dialog via ajax
     * 
     * @access public 
     * @return string $string The add gallery dialog
     */
    public function showAddGallery()
    {
        $defineLabel = $this->objLanguage->languageText('mod_gallery_definegallery', 'gallery', 'ERROR: mod_gallery_definegallery');
        $newGalleryLabel = $this->objLanguage->languageText('mod_gallery_creategallery', 'gallery', 'ERROR: mod_gallery_creategallery');
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_name');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'ERROR: word_save');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');
        $noNameLabel = $this->objLanguage->languageText('mod_gallery_nogalleryname', 'gallery', 'ERROR: mod_gallery_nogalleryname');
        $noDescLabel = $this->objLanguage->languageText('mod_gallery_nogallerydesc', 'gallery', 'ERROR: mod_gallery_nogallerydesc');
        $shareLabel = $this->objLanguage->languageText('mod_gallery_sharegallery', 'gallery', 'ERROR: mod_gallery_sharegallery');
        
        $arrayVars = array();
        $arrayVars['no_gallery_name'] = $noNameLabel;
        $arrayVars['no_gallery_desc'] = $noDescLabel;
        
        // pass error to javascript.
        $this->objSvars->varsToJs($arrayVars);

        $objInput = new textinput('gallery_name', '', '', '50');
        $nameInput = $objInput->show();
        
        $objText = new textarea('gallery_description', '', '4', '49', '250', $this->leftLabel);
        $descriptionText = $objText->show();
        
        $objCheck = new checkbox('gallery_shared');
        $sharedCheck = $objCheck->show();
        
        $objButton = new button('save', $saveLabel);
        $objButton->setId('gallery_add_save');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('gallery_add_cancel');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell('<span class="success">' . $defineLabel . '</span>', '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $nameLabel . ': </b>', '200px', '', '', '', '', '');
        $objTable->addCell($nameInput, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $descriptionLabel . ': </b>', '', 'top', '', '', '', '');
        $objTable->addCell($descriptionText, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $shareLabel . ': </b>', '', 'top', '', '', '', '');
        $objTable->addCell($sharedCheck, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($saveButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $galleryTable = $objTable->show();

        $objForm = new form('gallery_add', $this->uri(array(
            'action' => 'ajaxSaveAddGallery',
        ), 'gallery'));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($galleryTable);
        $form = $objForm->show();
        
        $dialog = $this->newObject('dialog', 'jquerycore');
        $dialog->setCssId('dialog_gallery_add');
        $dialog->setTitle($newGalleryLabel);
        $dialog->setContent($form);
        $dialog->setWidth(745);
        $dialog->unsetButtons();
        $string = $dialog->show();
        
        return $string;        
    }
    
    /**
     *
     * Method to get the gallery options via ajax
     * 
     * @access public
     * @param string $galleryId The id of the gallery to get options for
     * @return string $string The list of options
     */
    public function showGalleryOptions($galleryId)
    {
        $browseGalleryAlbumsLabel = $this->objLanguage->languageText('mod_gallery_browsegalleryalbums', 'gallery', 'ERROR: mod_gallery_browsegalleryalbums');
        $editGalleryLabel = $this->objLanguage->languageText('mod_gallery_editgallery', 'gallery', 'ERROR: mod_gallery_editgallery');
        $deleteGalleryLabel = $this->objLanguage->languageText('mod_gallery_deletegallery', 'gallery', 'ERROR: mod_gallery_deletegallery');
        $confirmLabel = $this->objLanguage->languageText('mod_gallery_galleryconfirm', 'gallery', 'ERROR: mod_gallery_galleryconfirm');
        $warningLabel = $this->objLanguage->languageText('mod_gallery_gallerywarning', 'gallery', 'ERROR: mod_gallery_gallerywarning');
        $uploadLabel = $this->objLanguage->languageText('mod_gallery_uploadphotos', 'gallery', 'ERROR: mod_gallery_uploadphotos');
        $addAlbumLabel = $this->objLanguage->languageText('mod_gallery_addalbum', 'gallery', 'ERROR: mod_gallery_addalbum');

        $albums = $this->objDBalbums->getUserGalleryAlbums($galleryId);
        
        $string = '';
        
        if (count($albums) > 0)
        {
            $this->objIcon->setIcon('picture_go', 'png');
            $this->objIcon->title = $browseGalleryAlbumsLabel;
            $this->objIcon->alt = $browseGalleryAlbumsLabel;
            $icon = $this->objIcon->show();
            $uri = $this->uri(array('action' => 'showalbums', 'gallery_id' => $galleryId), 'gallery');
            $objLink = new link($uri);
            $objLink->link = $icon . '&nbsp;' . $browseGalleryAlbumsLabel;
            $browseLink = $objLink->show();
            $string .= $browseLink . '<br />';
        }

        $this->objIcon->setIcon('picture_edit', 'png');
        $this->objIcon->title = $editGalleryLabel;
        $this->objIcon->alt = $editGalleryLabel;
        $icon = $this->objIcon->show();
        $editLink = '<a href="#" class="gallery_edit" id="' . $galleryId . '">' . $icon . '&nbsp;' . $editGalleryLabel . '</a>'; 
        $string .= $editLink . '<br />';

        $this->objIcon->setIcon('picture_delete', 'png');
        $this->objIcon->title = $deleteGalleryLabel;
        $this->objIcon->alt = $deleteGalleryLabel;
        $icon = $this->objIcon->show();

        $location = $this->uri(array('action' => 'deletegallery', 'gallery_id' => $galleryId), 'gallery');
        $message = $confirmLabel . '<br />' . $this->error($warningLabel);
        
        $this->objConfirm->setConfirm($icon . '&nbsp;' . $deleteGalleryLabel, $location, $message);
        $deleteIcon = $this->objConfirm->show();
        
        $string .= $deleteIcon . '<br />';
        
        $this->objIcon->setIcon('picture_edit', 'png');
        $this->objIcon->title = $addAlbumLabel;
        $this->objIcon->alt = $addAlbumLabel;
        $icon = $this->objIcon->show();
        $addLink = '<a href="#" class="album_add" id="' . $galleryId . '">' . $icon . '&nbsp;' . $addAlbumLabel . '</a>'; 
        $string .= $addLink . '<br />';

        $this->objIcon->setIcon('picture_edit', 'png');
        $this->objIcon->title = $uploadLabel;
        $this->objIcon->alt = $uploadLabel;
        $icon = $this->objIcon->show();
        $uploadLink = '<a href="#" class="gallery_upload" id="' . $galleryId . '">' . $icon . '&nbsp;' . $uploadLabel . '</a>'; 
        $string .= $uploadLink;

        return $string;
    }
    
    /**
     *
     * Method to show the edit gallery dialog via ajax
     * 
     * @access public 
     * @return string $string The add gallery dialog
     */
    public function showEditGallery()
    {
        $editGalleryLabel = $this->objLanguage->languageText('mod_gallery_editgallery', 'gallery', 'ERROR: mod_gallery_editgallery');
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_name');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'ERROR: word_save');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');
        $shareLabel = $this->objLanguage->languageText('mod_gallery_sharegallery', 'gallery', 'ERROR: mod_gallery_sharegallery');
        
        $objInput = new textinput('edit_gallery_id', '', 'hidden', '50');
        $idInput = $objInput->show();

        $objInput = new textinput('edit_gallery_name', '', '', '50');
        $nameInput = $objInput->show();
        
        $objText = new textarea('edit_gallery_description', '', '4', '49', '250', $this->leftLabel);
        $descriptionText = $objText->show();
        
        $objCheck = new checkbox('edit_gallery_shared');
        $objCheck->setValue('on');
        $sharedCheck = $objCheck->show();
        
        $objButton = new button('save', $saveLabel);
        $objButton->setId('gallery_edit_save');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('gallery_edit_cancel');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell('<b>' . $nameLabel . ': </b>', '200px', '', '', '', '', '');
        $objTable->addCell($nameInput, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $descriptionLabel . ': </b>', '', 'top', '', '', '', '');
        $objTable->addCell($descriptionText, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $shareLabel . ': </b>', '', 'top', '', '', '', '');
        $objTable->addCell($sharedCheck, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $galleryTable = $objTable->show();

        $objForm = new form('gallery_edit', $this->uri(array(
            'action' => 'ajaxSaveEditGallery',
        ), 'gallery'));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($galleryTable);
        $form = $objForm->show();
        
        $dialog = $this->newObject('dialog', 'jquerycore');
        $dialog->setCssId('dialog_gallery_edit');
        $dialog->setTitle($editGalleryLabel);
        $dialog->setContent($form);
        $dialog->setWidth(745);
        $dialog->unsetButtons();
        $string = $dialog->show();
        
        return $string;        
    }    
    
    /**
     *
     * Method to show the add album dialog via ajax
     * 
     * @access public 
     * @param string $galleryId The id of the gallery to ad the album to
     * @return string $string The add album dialog
     */
    public function showAddAlbum()
    {
        $defineLabel = $this->objLanguage->languageText('mod_gallery_definealbum', 'gallery', 'ERROR: mod_gallery_definealbum');
        $newAlbumLabel = $this->objLanguage->languageText('mod_gallery_createalbum', 'gallery', 'ERROR: mod_gallery_createalbum');
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_name');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'ERROR: word_save');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');
        $noNameLabel = $this->objLanguage->languageText('mod_gallery_noalbumname', 'gallery', 'ERROR: mod_gallery_noalbumname');
        $noDescLabel = $this->objLanguage->languageText('mod_gallery_noalbumdesc', 'gallery', 'ERROR: mod_gallery_noalbumdesc');
     
        $arrayVars = array();
        $arrayVars['no_album_name'] = $noNameLabel;
        $arrayVars['no_album_desc'] = $noDescLabel;
        
        // pass error to javascript.
        $this->objSvars->varsToJs($arrayVars);

        $objInput = new textinput('album_gallery_id', '', 'hidden', '');
        $idInput = $objInput->show();
        
        $objInput = new textinput('album_name', '', '', '50');
        $nameInput = $objInput->show();
        
        $objText = new textarea('album_description', '', '4', '49', '250', $this->leftLabel);
        $descriptionText = $objText->show();
        
        $objButton = new button('save', $saveLabel);
        $objButton->setId('album_add_save');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('album_add_cancel');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell('<span class="success">' . $defineLabel . '</span>', '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $nameLabel . ': </b>', '200px', '', '', '', '', '');
        $objTable->addCell($nameInput, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $descriptionLabel . ': </b>', '', 'top', '', '', '', '');
        $objTable->addCell($descriptionText, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $albumTable = $objTable->show();

        $objForm = new form('album_add', $this->uri(array(
            'action' => 'ajaxSaveAddAlbum',
        ), 'gallery'));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($albumTable);
        $form = $objForm->show();
        
        $dialog = $this->newObject('dialog', 'jquerycore');
        $dialog->setCssId('dialog_album_add');
        $dialog->setTitle($newAlbumLabel);
        $dialog->setContent($form);
        $dialog->setWidth(745);
        $dialog->unsetButtons();
        $string = $dialog->show();
        
        return $string;        
    }    
    
    /**
     * Method to show the gallery albums
     * 
     * @accesss publicshowUserGalleryAlbums
     * @param string $galleryId The id of the gallery to show albums
     * @return string $string The display string
     */
    public function showAlbums($galleryId)
    {
        $content = $this->showUserGalleryAlbums($galleryId);
        
        $string = '<div id="image_gallery">' . $content . '</div>';
        $string .= $this->showAddAlbum();
        $string .= $this->showEditAlbum();
        
        return $string;
    }    

    /**
     *
     * Method to show the galfor the main diaplay
     * 
     * @access public
    * @return string $string The display string 
     */
    public function showUserGalleryAlbums($galleryId)
    {
        $noAlbumsLabel = $this->objLanguage->languageText('mod_gallery_noalbums', 'gallery', 'ERROR: mod_gallery_noalbums');        
        $galleriesLabel = $this->objLanguage->languageText('mod_gallery_mygalleries', 'gallery', 'ERROR: mod_gallery_mygalleries');
        $albumsLabel = $this->objLanguage->languageText('mod_gallery_myalbums', 'gallery', 'ERROR: mod_gallery_myalbums');
        $addAlbumLabel = $this->objLanguage->languageText('mod_gallery_addalbum', 'gallery', 'ERROR: mod_gallery_addalbum');
        $defineLabel = $this->objLanguage->languageText('mod_gallery_definealbum', 'gallery', 'ERROR: mod_gallery_definealbum');
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_system');
        $descLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $emptyLabel = $this->objLanguage->languageText('word_empty', 'system', 'ERROR: word_empty');
        $oneLabel = $this->objLanguage->languageText('mod_gallery_oneimage', 'gallery', 'ERROR: mod_gallery_oneimage');
        $sharedLabel = $this->objLanguage->languageText('mod_gallery_shared', 'gallery', 'ERROR: mod_gallery_shared');
        $yesLabel = $this->objLanguage->languageText('word_yes', 'system', 'ERROR: word_yes');
        $noLabel = $this->objLanguage->languageText('word_no', 'system', 'ERROR: word_no');
        $clickLabel = $this->objLanguage->languageText('mod_gallery_albumclick', 'gallery', 'ERROR: mod_gallery_albumclick');
        $doubleLabel = $this->objLanguage->languageText('mod_gallery_albumdoubleclick', 'gallery', 'ERROR: mod_gallery_albumdoubleclick');

        $albums = $this->objDBalbums->getUserGalleryAlbums($galleryId);

        $uri = $this->uri(array('action' => 'view'), 'gallery');
        $objLink = new link($uri);
        $objLink->link = $galleriesLabel;
        $galleriesLink = $objLink->show();
        
        $string = '<h1>' . $galleriesLink . '&nbsp;|&nbsp;' . $albumsLabel . '</h1>';
        $string .= '<h3><span class="success">' . $defineLabel . '</span></h3>';

        if (empty($albums))
        {
            $string .= $this->error($noAlbumsLabel);            
        }

        $this->objIcon->setIcon('picture_add', 'png');
        $this->objIcon->title = $addAlbumLabel;
        $this->objIcon->alt = $addAlbumLabel;
        $addAlbumIcon = $this->objIcon->show();

        $string .= '<div><a href="#" class="album_add" id="' . $galleryId . '">' . $addAlbumIcon . '&nbsp;' . $addAlbumLabel . '</a></div>';
        
        if (!empty($albums))
        {
            foreach ($albums as $album)
            {
                $images = $this->objDBimages->getUserGalleryAlbumImages($album['id']);
                
                $string .= '<div class="album" id="' . $album['id'] . '">';
                
                if (empty($album['cover_image_id']))
                {
                    $this->objIcon->setIcon('no_photo', 'png');
                    $this->objIcon->title = '';
                    $this->objIcon->alt = '';
                    $image = $this->objIcon->show();
                }
                else
                {
                    $image = $this->objDBimages->getImage($album['cover_image_id']);
                }

                $random = time() . '_' . mt_rand();
                $objTooltip = $this->newObject('tooltip', 'jquerycore');
                $objTooltip->setCssId($random);
                $objTooltip->setAutoAppendScript(FALSE);
                $objTooltip->load();
                $script = $objTooltip->script;
                
                if (count($images) > 0)
                {
                    $title = $clickLabel . '<br />' . $doubleLabel;
                }
                else
                {
                    $title = $clickLabel;
                }
                
                $string .= $script . '<div id="' . $random . '" title="' . $title . '">' . $image . '</div>';
                
                switch (count($images))
                {
                    case 0:
                        $string .= '<br /><em class="warning">(' . strtolower($emptyLabel) . ')</em>';
                        break;
                    case 1:
                        $string .= '<br /><em class="warning">' . $oneLabel . '</em>';
                        break;
                    default:
                        $array = array('count' => count($images));
                        $string .= '<br /><em class="warning">' . $this->objLanguage->code2Txt('mod_gallery_imagecount', 'gallery', $array, 'ERROR: mod_gallery_imagecount') . '</em>';
                        break;                        
                }
                
                $shared = ($album['is_shared'] == 1) ? $yesLabel : $noLabel;
                
                $string .= '<div id="shared_' . $album['id'] . '"><b>' . $sharedLabel . ': </b>' . $shared . '</div>';
                $string .= '<div id="name_' . $album['id'] . '"><b>' . $nameLabel . ': </b>' . $album['name'] . '</div>';
                $string .= '<div id="desc_' . $album['id'] . '"><b>' . $descLabel . ': </b>' . $album['description'] . '</div>';
                $string .= '<div class="album_options" id="album_options_' . $album['id'] . '" style="display: none;">' . $this->showAlbumOptions($galleryId, $album['id']) . '</div>';
                $string .= '</div>';
            }
        }

        $objInput = new textinput('gallery', $galleryId, 'hidden', '');
        $idInput = $objInput->show();        

        return $string . $idInput;
    }
    
    /**
     *
     * Method to get the album options via ajax
     * 
     * @access public
     * @param string $galleryId The id of the gallery the album is in
     * @param string $albumId The id of the album to get options for
     * @return string $string The list of options
     */
    public function showAlbumOptions($galleryId, $albumId)
    {
        $browseGalleryAlbumImagesLabel = $this->objLanguage->languageText('mod_gallery_browseimages', 'gallery', 'ERROR: mod_gallery_browsealbumimages');
        $editAlbumLabel = $this->objLanguage->languageText('mod_gallery_editalbum', 'gallery', 'ERROR: mod_gallery_editalbum');
        $deleteAlbumLabel = $this->objLanguage->languageText('mod_gallery_deletealbum', 'gallery', 'ERROR: mod_gallery_deletealbum');
        $confirmLabel = $this->objLanguage->languageText('mod_gallery_albumconfirm', 'gallery', 'ERROR: mod_gallery_albumconfirm');
        $warningLabel = $this->objLanguage->languageText('mod_gallery_albumwarning', 'gallery', 'ERROR: mod_gallery_albumwarning');
        $uploadLabel = $this->objLanguage->languageText('mod_gallery_uploadphotos', 'gallery', 'ERROR: mod_gallery_uploadphotos');

        $images = $this->objDBimages->getUserGalleryAlbumImages($albumId);
        
        $string = '';
        
        if (count($images) > 0)
        {
            $this->objIcon->setIcon('picture_go', 'png');
            $this->objIcon->title = $browseGalleryAlbumImagesLabel;
            $this->objIcon->alt = $browseGalleryAlbumImagesLabel;
            $icon = $this->objIcon->show();
            $uri = $this->uri(array('action' => 'showimages', 'album_id' => $albumId), 'gallery');
            $objLink = new link($uri);
            $objLink->link = $icon . '&nbsp;' . $browseGalleryAlbumImagesLabel;
            $browseLink = $objLink->show();
            $string .= $browseLink . '<br />';
        }

        $this->objIcon->setIcon('picture_edit', 'png');
        $this->objIcon->title = $editAlbumLabel;
        $this->objIcon->alt = $editAlbumLabel;
        $icon = $this->objIcon->show();
        $editLink = '<a href="#" class="album_edit" id="' . $albumId . '">' . $icon . '&nbsp;' . $editAlbumLabel . '</a>'; 
        $string .= $editLink . '<br />';

        $this->objIcon->setIcon('picture_delete', 'png');
        $this->objIcon->title = $deleteAlbumLabel;
        $this->objIcon->alt = $deleteAlbumLabel;
        $icon = $this->objIcon->show();
        
        $message = $confirmLabel . '<br />' . $this->error($warningLabel);

        $location = $this->uri(array('action' => 'deletealbum', 'album_id' => $albumId, 'gallery_id' => $galleryId), 'gallery');

        $this->objConfirm->setConfirm($icon . '&nbsp;' . $deleteAlbumLabel, $location, $message);
        $deleteIcon = $this->objConfirm->show();
        
        $string .= $deleteIcon . '<br />';
        
        $this->objIcon->setIcon('picture_edit', 'png');
        $this->objIcon->title = $uploadLabel;
        $this->objIcon->alt = $uploadLabel;
        $icon = $this->objIcon->show();
        $uploadLink = '<a href="#" class="album_upload" id="' . $albumId . '">' . $icon . '&nbsp;' . $uploadLabel . '</a>'; 
        $string .= $uploadLink;

        return $string;
    }    

    /**
     *
     * Method to show the edit album dialog via ajax
     * 
     * @access public 
     * @return string $string The add gallery dialog
     */
    public function showEditAlbum()
    {
        $editAlbumLabel = $this->objLanguage->languageText('mod_gallery_editalbum', 'gallery', 'ERROR: mod_gallery_editalbum');
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_name');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'ERROR: word_save');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');

        $objInput = new textinput('edit_album_gallery_id', '', 'hidden', '50');
        $galleryIdInput = $objInput->show();

        $objInput = new textinput('edit_album_id', '', 'hidden', '50');
        $albumIdInput = $objInput->show();

        $objInput = new textinput('edit_album_name', '', '', '50');
        $nameInput = $objInput->show();
        
        $objText = new textarea('edit_album_description', '', '4', '49', '250', $this->leftLabel);
        $descriptionText = $objText->show();
        
        $objButton = new button('save', $saveLabel);
        $objButton->setId('album_edit_save');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('album_edit_cancel');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell('<b>' . $nameLabel . ': </b>', '200px', '', '', '', '', '');
        $objTable->addCell($nameInput, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $descriptionLabel . ': </b>', '', 'top', '', '', '', '');
        $objTable->addCell($descriptionText, '', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($galleryIdInput . $albumIdInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $albumTable = $objTable->show();

        $objForm = new form('album_edit', $this->uri(array(
            'action' => 'ajaxSaveEditAlbum',
        ), 'gallery'));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($albumTable );
        $form = $objForm->show();
        
        $dialog = $this->newObject('dialog', 'jquerycore');
        $dialog->setCssId('dialog_album_edit');
        $dialog->setTitle($editAlbumLabel);
        $dialog->setContent($form);
        $dialog->setWidth(745);
        $dialog->unsetButtons();
        $string = $dialog->show();
        
        return $string;        
    }    
    
    /**
     *
     * Method to show the upload files dialog
     * 
     * @access public
     * @return string $string The display string 
     */
    public function showUpload()
    {
        $titleLabel = $this->objLanguage->languageText('mod_gallery_uploadphotos', 'gallery', 'ERROR: mod_gallery_uploadphotos');
        $descLabel = $this->objLanguage->languageText('mod_gallery_uploaddesc', 'gallery', 'ERROR: mod_gallery_uploaddesc');
        $warnLabel = $this->objLanguage->languageText('mod_gallery_uploadwarning', 'gallery', 'ERROR: mod_gallery_uploadwarning');
        $uploadLabel = $this->objLanguage->languageText('word_upload', 'system', 'ERROR: word_upload');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');
        $noPhotoLabel = $this->objLanguage->languageText('mod_gallery_nophotouploaded', 'gallery', 'ERROR: mod_gallery_nophotouploaded');
        $moreLabel = $this->objLanguage->languageText('mod_gallery_addinputs', 'gallery', 'ERROR: mod_gallery_addinputs');
        $lessLabel = $this->objLanguage->languageText('mod_gallery_removeinputs', 'gallery', 'ERROR: mod_gallery_removeinputs');

        $defineLabel = '<span class="success">' . $descLabel . '</span><br />' . $this->error($warnLabel);
        
        $arrayVars = array();
        $arrayVars['no_photo'] = $noPhotoLabel;
        $arrayVars['more_inputs'] = $moreLabel;
        $arrayVars['less_inputs'] = $lessLabel;
        
        // pass error to javascript.
        $this->objSvars->varsToJs($arrayVars);

        $objInput = new textinput('photo_gallery_id', '', 'hidden', '50');
        $idInput = $objInput->show();
        
        $objInput = new textinput('files[]', '', 'file', '50');
        $fileInput = $objInput->show();
        
        $objButton = new button('upload', $uploadLabel);
        $objButton->setId('photo_add_save');
        $objButton->setToSubmit();
        $uploadButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('photo_add_cancel');
        $cancelButton = $objButton->show();

        $moreLink = '<a href="#" id="more_photos"><em class="warning">' . $moreLabel . '<em></a>';
        
        $objTable = new htmltable();
        $objTable->id = "upload_table";
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($defineLabel, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<div id="upload_to"></div>', '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<div id="upload_new">' . $this->ajaxShowNewAlbumInputs(FALSE) . '</div>', '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        for ($i = 1; $i <= 4; $i++)
        {
            $class = ($i <= 2) ? '' : 'more_boxes';
            $objTable->startRow($class);
            $objTable->addCell($fileInput, '', 'top', '', '', '', '');
            $objTable->addCell($fileInput, '', 'top', '', '', '', '');
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell($idInput . $uploadButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($moreLink, '', '', '', '', 'colspan="2"', '');
        $objTable->endRow();
        $albumTable = $objTable->show();

        $objForm = new form('photo_add', $this->uri(array(
            'action' => 'ajaxSavePhotos',
        ), 'gallery'));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($albumTable);
        $form = $objForm->show();
        
        $dialog = $this->newObject('dialog', 'jquerycore');
        $dialog->setCssId('dialog_photo_add');
        $dialog->setTitle($titleLabel);
        $dialog->setContent($form);
        $dialog->setWidth(850);
        $dialog->unsetButtons();
        $string = $dialog->show();
        
        return $string;        
   }

    /**
     *
     * Method to adapt th upload dialog via ajax
     * 
     * @access public 
     * @return VOID 
     */
    public function ajaxShowUploadPhoto()
    {
        $newAlbumLabel = $this->objLanguage->languageText('mod_gallery_newalbum', 'gallery', 'ERROR: mod_gallery_newalbum');
        $uploadToLabel = $this->objLanguage->languageText('mod_gallery_uploadto', 'gallery', 'ERROR: mod_gallery_uploadto');
        
        $galleryId = $this->getParam('gallery_id');
        $albumId = $this->getParam('album_id');
        
        $gallery = $this->objDBgalleries->getGallery($galleryId);
        $albums = $this->objDBalbums->getUserGalleryAlbums($galleryId);

        if (!$albumId)
        {
            if (count($albums) > 0)
            {
                $objDrop = new dropdown('photo_album_id');
                $objDrop->addOption('', $newAlbumLabel);
                $objDrop->addFromDB($albums, 'name', 'id');
                $idDrop = $objDrop->show();

                $string = '<b>' . $uploadToLabel . ':&nbsp;</b>' . $idDrop;
            }
            else
            {
                $string = '<b>' . $uploadToLabel . '&nbsp;' . strtolower($newAlbumLabel) . '</b>';
            }
        }
        else
        {
            foreach($albums as $album)
            {
                if ($album['id'] == $albumId)
                {
                    $name = $album['name'];
                }
            }
            $objInput = new textinput('photo_album_id', $albumId, 'hidden', '');
            $string = $objInput->show();
            $string .= '<b>' . $uploadToLabel . ':&nbsp;</b>' . $name;
        }

        $data = array();
        $data['album_string'] = $string;
        $data['gallery_id'] = $gallery['id'];
        
        echo json_encode($data);
        die();        
    }
    
    /**
     *
     * Method to create the new album inputs
     * 
     * @access public
     * @return VOID
     */
    public function ajaxShowNewAlbumInputs($isAjax = TRUE)
    {
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_name');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        
        $objInput = new textinput('photo_album_name', '', '', '50');
        $nameInput = $objInput->show();
        
        $objText = new textarea('photo_album_description', '', '3', '49', '250', $this->leftLabel);
        $descriptionText = $objText->show();
        
        $string = '<b>' . $nameLabel . ':&nbsp;</b><br />' . $nameInput;
        $string .= '<br /><b>' . $descriptionLabel . ':&nbsp;</b><br />' . $descriptionText;
        
        if ($isAjax)
        {
            echo $string;
            die();
        }
        else
        {
            return $string;
        }
   }
    
    /**
     *
     * Method to do the upload of the images
     * 
     * @access public
     * @return string $albumId The id of the album the images were uploaded to 
     */
    public function doUpload()
    {
        $galleryId = $this->getParam('photo_gallery_id');
        $albumId = $this->getParam('photo_album_id');
        
        $gallery = $this->objDBgalleries->getGallery($galleryId);
        if (empty($albumId))
        {
            $name = $this->getParam('photo_album_name');
            $description = $this->getParam('photo_album_description');
            $albumId  = $this->objDBalbums->addUserGalleryAlbum($galleryId, $name, $description, $gallery['is_shared']);
        }
        
        $results = $this->uploadFiles();

        $this->addImageToTable($galleryId, $albumId, $gallery['is_shared'], $results);

        return $albumId;
    }
    
    /**
     *
     * Method to do the physical upload to the database and file system
     * 
     * @access private
     * @return array $results An array of results from the uploads
     */
    private function uploadFiles()
    {
        $this->objUpload->setUploadFolder('users/' . $this->userId . '/photos/');
        $results = $this->objUpload->uploadFilesArray('files');

        $mimetypes = $this->objArchive->getSupportedTypes();
        if($results)
        {
            foreach($results as $file)
            {                       
                if( in_array($file['mimetype'], $mimetypes) )
                {
                    $filePath = $this->objFileMan->getFilePath($file['fileid']);
                    $zip = $this->objArchive->open($filePath);
                    $filePath = str_replace(".", "_", $filePath);
                    $fullFilePath = $this->objFileMan->getFullFilePath($file['fileid']);
                    $fullFilePath = str_replace(".", "_", $fullFilePath);
                    $this->objDir->mkdirs($fullFilePath);
                    $zip->extractTo($fullFilePath);
                    $this->objFileMan->deleteFile($file['fileid'],TRUE);

                    //list files in
                    $handle = opendir($fullFilePath);
                    if ($handle)
                    {
                        while (false !== ($dirfile = readdir($handle)))
                        {
                            $mimetype = $this->objMime->getMimeType($fullFilePath . "/" . $dirfile);
                            if(strstr($mimetype, "image/"))
                            {
                                $infoArray = array();
                                // 1) Add to Database
                                $fileId = $this->objFileMan->addFile($dirfile, $fullFilePath . "/" . $dirfile, filesize($fullFilePath . "/" . $dirfile),
                                    $mimetype, "images", 1, $this->userId, NULL, $this->getParam('creativecommons_files', ''));
                                // Get Media Info
                                $fileInfo = $this->objAnalyzeMediaFile->analyzeFile($fullFilePath . "/" . $dirfile);
                                // Add Information to Databse
                                $this->objMediaFileInfo->addMediaFileInfo($fileId, $fileInfo[0]);
                                // Check whether mimetype needs to be updated
                                if ($fileInfo[1] != '')
                                {
                                    $this->objFileMan->updateMimeType($fileId, $fileInfo[1]);
                                }
                                $this->objThumbnails->createThumbailFromFile($fullFilePath . "/" . $dirfile, $fileId);                                        
                                // Update Return Array Details                                        
                                $infoArray['fileid'] = $fileId;
                                $infoArray['success'] = TRUE;
                                $infoArray['path'] = $filePath . "_temp";
                                $infoArray['fullpath'] = $fullFilePath . "/";
                                $infoArray['subfolder'] = "images";
                                $infoArray['original_folder'] = "images";
                                $infoArray['name'] = $dirfile;
                                $infoArray['mimetype'] = $mimetype;
                                $infoArray['errorcode'] = 0;
                                $infoArray['size'] = filesize($fullFilePath . "/" . $dirfile);

                                $results[$dirfile] = $infoArray;
                            }
                            else
                            {
                                @unlink($fullFilePath . "/" . $dirfile);
                            }
                        }
                    }
                    closedir($handle);                            
                }
            }
        }
        return $results;
    }
    
    /**
     *
     * Method to add the image to the image table
     * 
     * @access public
     * @param string $galleryId The id of the gallery the image is being added to
     * @param string $albumId The id of the album the image is being adde to
     * @param integer $isShared 1 if the album is shared  if not
     * @param array $results The results of the file upload
     * @return VOID 
     */
    public function addImageToTable($galleryId, $albumId, $isShared, $results)
    {
        if($results == null)
        {
            return FALSE;
        } 
        
        $images = $this->objDBimages->getUserGalleryAlbumImages($albumId);
        $display = count($images);

        foreach($results as $result)
        {
            if (!isset ($result['fileid']))
            {
                $result['fileid'] = '';
            }
            if($result['fileid'] != '')
            {
                $data = array();
                $data['gallery_id'] = $galleryId;
                $data['album_id'] = $albumId;
                $data['user_id'] = $this->userId;
                $data['file_id'] = $result['fileid'];
                $data['display_order'] = $display++;
                $data['is_shared'] = $isShared;
                $data['created_by'] = date('Y-m-d H:i:s');
                $data['date_created'] = $this->userId;

                $this->objDBimages->addUserGalleryAlbumImage($data);                
            }
        }
    }
}
?>