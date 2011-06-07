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

    private $_upload;

    private $_restrictedFileList;

    /**
     * Constructor
     */
    public function init() {
        parent::init();
        $this->_uploadInput = $this->getObject('uploadinput','filemanager');
        //define some MIME types for Images
        $this->_restrictedFileList = array('jpg','png','gif','tiff','ico');

        $this->_upload = $this->getObject('upload','filemanager');
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
                    throw new customException('Upload failed: ' . $this->results['reason']); //TODO return proper error page containing error
                } else {
                    return FALSE;
                }
            }
        }
        return $this->results;
    }

    public function show()
    {
        $this->_uploadInput->showTargetDir = false;
        $this->_uploadInput->restrictFileList = $this->_restrictedFileList;
        return $this->_uploadInput->show();
    }

    /**This function determines if the uploaded file is a valid image file
     *
     * @param <type> $fileInfoArray
     * @return <type>
     */
    function isFileValid(&$fileInfoArray){
//        $fileInputName = $this->getParam('fileupload');  //TODO this is returning NULL, find a way around this
        $objFileParts = $this->getObject('fileparts', 'files');
        $fileInputName = 'fileupload';

        // First Check if array key exists
        if (array_key_exists($fileInputName, $_FILES)) {
            $file = $_FILES[$fileInputName];
        } else { // If not, return FALSE
            return FALSE;
        }

        $fileInfoArray = array();

        // Check that file is not forbidden
        if ($this->_upload->isBannedFile($file['name'], $file['type']))
            {
                $fileInfoArray  = array ('success'=>FALSE, 'reason'=>'bannedfile', 'name'=>$file['name'], 'size'=>$file['size'], 'mimetype'=>$file['type'], 'errorcode'=>$file['error']);
            }

        // Check that file was not partially uploaded
        else if ($file['error'] == 3)
            {
                $fileInfoArray  = array ('success'=>FALSE, 'reason'=>'partialuploaded');
            }

        // No File Provided
        else if ($file['error'] == 4)
            {
                $fileInfoArray  = array ('success'=>FALSE, 'reason'=>'nouploadedfileprovided', 'errorcode'=>$file['error']);
                $file['name'] = 'nofileprovided';
            }
            
            //TODO add check for file exstension
//        else if (is_array($ext) && !in_array($objFileParts->getExtension($file['name']), $ext)) {
//            $fileInfoArray  = array ('success'=>FALSE, 'reason'=>'doesnotmeetextension', 'name'=>$file['name'], 'size'=>$file['size'], 'mimetype'=>$file['type'], 'errorcode'=>$file['error']);
//        }

//            $fileInfoArray  = array ('success'=>FALSE, 'errorCode'=>$file['error']);
        if (empty($fileInfoArray))
            {
                return TRUE;
            }
        else
            {
                return FALSE;
            }
    }

    /**This function adds additional image extensions
     *
     * @param <type> $ext
     */
    public function addAccecptableImageExt($ext)
    {
        if ($ext != NULL && !is_array($ext)) {
            $ext = array($ext);
        }

        foreach ($ext as $extension) {
            array_push($this->_restrictedFileList, $extension);
        }

    }
}

?>
