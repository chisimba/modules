<?php
if (!
/**
 * Description for $GLOBALS
 * @global string $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class upload extends object {
    /**
    * @var array $permittedTypes The permitted file types
    */
    var $permittedTypes = array();
    
    /**
    * @var string $type The mime type of the uploaded file
    */
    var $type;
    
    /**
    * @var boolean $isError TRUE|FALSE
    */
    var $isError;
    
    /**
    * @var string $fileName The name the file is to be stored as
    */
    var $fileName;

    /**
    * @var string $overWrite TRUE|FALSE Whether or not to overwrite existing files
    */
    var $overWrite;

    /**
    * @var string $uploadFolder The folder to upload into. This
    *             can be set before doing the upload to upload to any folder.
    *             This is an absolute filesystem path
    *             (e.g. /var/www/html/nextgen/mypath or
    *             E:\Inetpub\cvsCheckouts\nextgen\mypath\)
    */
    var $uploadFolder;

    public function init() {
        //Instantiate the language object
        $this->objLanguage =  $this->getObject('language', 'language');
        $this->objPermittedTypes = $this->getObject('dbpermittedtypes');
        $this->permittedTypes = $this->getTypes();
        //Set the default overwrite
        $this->overWrite = FALSE;
    }

    function getTypes() {
        $types = array();
        $data = $this->objPermittedTypes->getFileExtensions();
        $count = 1;
        foreach($data as $data) {
            $tmp = array($count=>$data['ext']);
            $types = $types + $tmp;
            $count++;
        }

        return $types;
    }

    function doUpload()
    {
        //Make sure that there are no accidental double slashes
        $this->uploadFolder = str_replace("//", "/", $this->uploadFolder);
        //Check for errors
        $errCode = isset($_FILES[$this->inputname]['error']);
        if ($errCode!=UPLOAD_ERR_OK) { #1
            if ($giveResults) {
                return array(
                    'success' => FALSE,
                    'message' => $this->getErrorCodeMeaning($errCode),
                );
            } else {
                return $this->getErrorCodeMeaning($errCode);
            }
        }
        else {
            //set fileName
            $this->fileName = $_FILES['filename']['name'];
            $this->type = $_FILES['filename']['type'];
            
            if ($this->checkExtension()) {
                if (!$this->overWrite && $this->checkExists()) {
                    $this->isError = TRUE;
                    return array(
                        'success' => FALSE,
                        'message' => $this->objLanguage->languageText("error_UPLOAD_NOOVERWRITE", 'dms'),
                        );
                    
                }
                else {
                    $this->moveUploadedFile();

                    // check if the file actually exists, i.e., it has been uploaded
                    if($this->checkExists()) {
                        return array(
                            'success' => TRUE,
                            'message' => $this->objLanguage->languageText("error_UPLOAD_ERR_OK", 'dms'),
                            'filename' => $this->fileName,
                            'mimetype' => $this->type,
                            'extension' => $this->getFileExtension(),
                        );
                    }
                    else {
                      return array(
                            'success' => FALSE,
                            'message' => $this->objLanguage->languageText("error_UPLOAD_FILENOTUPLOADED", 'dms'),
                      );
                    }
                }
            }
            else {
                return array(
                        'success' => FALSE,
                        'message' => $this->objLanguage->languageText("error_UPLOAD_DISALLOWEDEXTENSION", 'dms'),
                    );
            }
        }
    }

    /**
    *
    * Method to check the extension of the uploaded file to
    * see if it is a permitted extension.
    *
    * @return TRUE|FALSE, TRUE if it is a permitted extension
    *                     and FALSE if not.
    *
    */
    function checkExtension()
    {
        $ext = strtolower($this->getFileExtension());
        //Check against all extensions
        foreach($this->permittedTypes as $type) {
            $type = strtolower($type);
            if ($type=='all') {
                return TRUE;
            } elseif ($type == $ext){
                return TRUE;
            }
        }
        return FALSE;
     }

     /**
    * Method to get the file extension of the uploaded file
    */
    function getFileExtension()
    {
        $pathParts = array();
        $pathParts = pathinfo($_FILES['filename']['name']);
        if (isset($pathParts['extension'])) {
            return $pathParts['extension'];
        } else {
            return NULL;
        }
    }

    /**
    *
    * Method to return PHP errors as meaningful text. Use it to translate
    * the error codes returned by getFileError() into meaningful text
    *
    * Use: $errTxt = $this->getErrorCodeMeaning($this->getFileError());
    *
    * @param string $errCode THe error code to translate
    *
    */
    function getErrorCodeMeaning($errCode)
    {
        switch ($errCode) {

            case 0:
                return $this->objLanguage->languageText("mod_files_err_0", 'dms');
                break;

            case 1:
                return $this->objLanguage->languageText("mod_files_err_1", 'dms');
                break;

            case 2:
                return $this->objLanguage->languageText("mod_files_err_2", 'dms');
                break;

            case 3:
                return $this->objLanguage->languageText("mod_files_err_3", 'dms');
                break;

            case 4:
                return $this->objLanguage->languageText("mod_files_err_4", 'dms');
                break;

            default:
                return $this->objLanguage->languageText("mod_files_err_default", 'dms');
                break;
        }

    }

    /**
    *
    * Method to check if the uploaded file aready exists
    * in the destination folder
    *
    * @return boolean TRUE|FALSE True if file exists, false if not.
    *
    */
    function checkExists()
    {
        if (file_exists($this->uploadFolder . $this->fileName)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    *
    * Method to move the uploaded file into the correct folder
    * as specified by the $this->uploadFolder property. It checks
    * if the file exists and set the upload type property to either
    * replace or new. This can then be accessed by the module
    * doing the upload, for example, to decide whether to do
    * a database update or insert where adding file details
    * to the database.
    *
    * @param string $storedName The name to store the file as (if not the original name)
    */
    function moveUploadedFile()
    {
        $this->fullFilePath = $this->uploadFolder . $this->fileName;
        
        if (file_exists($this->fullFilePath)) {
            $this->uploadType = "replace";
        } else {
            $this->uploadType = "new";
        }
        
        // now verify if file was successfully uploaded
        move_uploaded_file($_FILES['filename']['tmp_name'], $this->fullFilePath);
    }
}
?>
