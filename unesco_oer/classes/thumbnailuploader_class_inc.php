<?php
/* 
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
 */



class thumbnailuploader extends object{

    public $results;

    private $_uploadInput;

    /**
     * Constructor
     */
    public function init() {
        parent::init();
        $this->_uploadInput = $this->getObject('uploadinput','filemanager');
        //define some MIME types for Images
        $this->_uploadInput->restrictFileList = array('jpg','png','gif','tiff','ico');
    }

    public function uploadThumbnail($path) {
        $this->_uploadInput->enableOverwriteIncrement = TRUE;
        $this->_uploadInput->customuploadpath = $path;

        $this->results = $this->_uploadInput->handleUpload($this->getParam('fileupload'));
        //Test if file was successfully uploaded
        // Technically, FALSE can never be returned, this is just a precaution
        // FALSE means there is no fileinput with that name
        if ($this->results == FALSE) {
            //TODO return proper error page
            throw new customException('Upload failed: FATAL <br />');
        } else {
            if (!$this->results['success']) { // upload was unsuccessful
                if ($this->results['reason'] != 'nouploadedfileprovided') {
                    throw new customException('Upload failed: ' . $results['reason']); //TODO return proper error page containing error
                } else {
                    return FALSE;
                }
            }
        }
        return $this->results;
    }

    public function show()
    {
        return $this->_uploadInput->show();
    }
}

?>
