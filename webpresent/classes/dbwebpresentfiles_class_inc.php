<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end of security

/**
* Web Present Fikes Class
*
* This class interacts with the database to store the details of the list
* of
*/
class dbwebpresentfiles extends dbtable
{

    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_webpresent_files');
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    /**
    * Method to get the details of a file
    * @param string $id Record ID of the file
    * @return array Details of the file
    */
    public function getFile($id)
    {
        return $this->getRow('id', $id);
    }

    /**
    * Method to create a record id for the process of uploading
    * This is needed to create a folder for the presentation
    * All different formats of the same presentation are then stored in this folder
    *
    * @param string Record Id
    */
    public function autoCreateTitle()
    {
        return $this->insert(array(
                'processstage'=>'uploading'
            ));
    }

    /**
    * Method to delete a record id autocreated for the process of uploading
    * This is done when an error occurs
    *
    * @param string Record Id
    */
    public function removeAutoCreatedTitle($id)
    {
        $row = $this->getRow('id', $id);

        if ($row['processstage'] == 'uploading') {
            $this->delete('id', $id);
        }
    }

    public function updateReadyForConversion($id, $filename, $mimetype)
    {
        return $this->update('id', $id, array(
                'filename' => stripslashes($filename),
                'mimetype' => $mimetype,
                'filetype' => $this->getFileType($filename),
                'processstage' => 'readyforconversion',
                'creatorid' => $this->objUser->userId(),
                'dateuploaded' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            ));
    }

    public function updateFileDetails($id, $title, $description, $license)
    {
        return $this->update('id', $id, array(
                'title' => stripslashes($title),
                'description' => stripslashes($description),
                'cclicense' => $license
            ));
    }

    public function getLatestPresentations()
    {
        return $this->getAll(' ORDER BY dateuploaded DESC LIMIT 10');
    }

    public function getByUser($user)
    {
        return $this->getAll(' WHERE creatorid=\''.$user.'\' ORDER BY dateuploaded DESC');
    }

    private function getFileType($filename)
    {
        $pathInfo = pathinfo($filename);

        switch ($pathInfo['extension'])
        {
            case 'ppt':
            case 'pps':
                $type = 'powerpoint';
                break;
            case 'odp':
                $type = 'openoffice';
                break;
            default:
                $type = 'unknown';
                break;
        }

        return $type;
    }


    private function getFilesForConversion()
    {
        return $this->getAll(' WHERE processstage != \'finished\' AND inprocess=\'N\'');
    }

    private function isInProcess($id)
    {
        $row = $this->getRow('id', $id);

        return ($row['inprocess'] == 'Y' ? TRUE : FALSE);
    }

    public function convertFiles()
    {
        //echo '<pre>';
        $files = $this->getFilesForConversion();

        if (count($files) == 0) {
            return 'allfilesconverted';
        } else {
            foreach ($files as $file)
            {
                if (!$this->isInProcess($file['id'])) {
                    $result = $this->convertFile($file);
                } else {
                    $result = 'inprocess';
                }
            }

            return $result;
        }
    }

    private function setOutOfProcess($id)
    {
        return $this->update('id', $id, array('inprocess'=>'N'));
    }

    private function setInProcess($id, $step)
    {
        return $this->update('id', $id, array('inprocess'=>'N', 'processstage'=>$step));
    }


    private function convertFile($file)
    {
        //print_r($file);

        $path_parts = pathinfo($file['filename']);

        $ext = $path_parts['extension'];

        //echo $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;


        $step = 'otherconversion';
        $this->setInProcess($file['id'], $step);
        $result = $this->convertAlternative($file['id'], $ext);
        $this->setOutOfProcess($file['id']);

        if ($result) {
            $step = 'flashconversion';
            $this->setInProcess($file['id'], $step);
            $result = $this->convertFileFromFormat($file['id'], 'odp', 'swf');
            $this->setOutOfProcess($file['id']);
        }

        if ($result) {
            $step = 'pdfconversion';
            $this->setInProcess($file['id'], $step);
            $result = $this->convertFileFromFormat($file['id'], 'odp', 'pdf');
            $this->setOutOfProcess($file['id']);
        }

        if ($result) {
            $step = 'htmlconversion';
            $this->setInProcess($file['id'], $step);
            $result = $this->convertFileFromFormat($file['id'], 'odp', 'html');
            $this->setOutOfProcess($file['id']);

            if ($result == TRUE) {
                $this->update('id', $file['id'], array('processstage'=>'finished'));
                $step = 'finishedconversion';
            }
        }

        if ($result == TRUE)
        {
            $objSlides = $this->getObject('dbwebpresentslides');
            $objSlides->scanPresentationDir($file['id']);
        }


        return $step;
    }

    private function convertAlternative($id, $ext)
    {
        $other = ($ext == 'odp' ? 'ppt' : 'odp');

        return $this->convertFileFromFormat($id, $ext, $other);
    }

    private function convertFileFromFormat($id, $inputExt, $outputExt)
    {
        $source = $this->objConfig->getcontentBasePath().'webpresent/'.$id.'/'.$id.'.'.$inputExt;
        $conv = $this->objConfig->getcontentBasePath().'webpresent/'.$id.'/'.$id.'.'.$outputExt;


        //echo $source.'<br />'.$conv;

        if (!file_exists($conv)) {
            $objConvertDoc = $this->getObject('convertdoc', 'documentconverter');

            $objConvertDoc->convert($source, $conv);

            if (file_exists($conv)) {
                system ('chmod 777 '.$conv);
                @chmod ($conv, 0777);
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }

    }

    public function getPresentationThumbnail($id)
    {
        $source = $this->objConfig->getcontentBasePath().'webpresent_thumbnails/'.$id.'.jpg';
        $relLink = $this->objConfig->getsiteRoot().$this->objConfig->getcontentPath().'webpresent_thumbnails/'.$id.'.jpg';


        if (file_exists($source)) {

            return '<img src="'.$relLink.'" style="border:1px solid #000;" />';
        } else {
            $source = $this->objConfig->getcontentBasePath().'webpresent/'.$id.'/img0.jpg';
            $relLink = $this->objConfig->getcontentPath().'webpresent/'.$id.'/img0.jpg';

            if (file_exists($source)) {
                $objMkDir = $this->getObject('mkdir', 'files');
                $destinationDir = $this->objConfig->getcontentBasePath().'/webpresent_thumbnails';
                $objMkDir->mkdirs($destinationDir);

                $this->objImageResize = $this->getObject('imageresize', 'files');

                $this->objImageResize->setImg($source);

                // Resize to 100x100 Maintaining Aspect Ratio
                $this->objImageResize->resize(120, 120, TRUE);

                //$this->objImageResize->show(); // Uncomment for testing purposes

                // Determine filename for file
                // If thumbnail can be created, give it a unique file name
                // Else resort to [ext].jpg - prevents clutter, other files with same type can reference this one file
                if ($this->objImageResize->canCreateFromSouce) {
                    $img = $this->objConfig->getcontentBasePath().'/webpresent_thumbnails/'.$id.'.jpg';
                    $imgRel = $this->objConfig->getcontentPath().'/webpresent_thumbnails/'.$id.'.jpg';
                    $this->objImageResize->store($img);

                    return '<img src="'.$imgRel.'" style="border:1px solid #000;" />';
                } else {
                    return 'Unable to generate thumbnail';
                }
            } else {
                return 'No Preview Available';
            }
        }
    }

    public function deleteFile($id)
    {
        // First Step is to scan presentation directory and delete all file in that folder
        // Load Object
        $objScanForDelete = $this->newObject('scanfordelete');

        // Set Directory to Var
        $presentationFolder = $this->objConfig->getcontentBasePath().'webpresent/'.$id;

        // Scan Results
        $results = $objScanForDelete->scanDirectory($presentationFolder);


        // Variable to detect permission issues
        $numNoDeletes = 0;

        // First Delete Files, if they are any
        if (count($results['files']) > 0)
        {
            // Loop through files
            foreach ($results['files'] as $file)
            {
                // Extra Check that file exists
                if (file_exists($file))
                {
                    // Delete File
                    if(!unlink($file))
                    {
                        // If unable to, possible due to permissions
                        // Flag Variable
                        $numNoDeletes++;
                    }
                }
            }
        }

        // IF all files deleted, and there are subdirectories
        // Delete subdirectories
        if ($numNoDeletes != 0 && count($results['folders'] > 0))
        {
            // Loop through array
            foreach ($results['folders'] as $folder)
            {
                // Extra check that folder exists
                if (file_exists($folder))
                {
                    // Delete Folder
                    if(!rmdir($folder))
                    {
                        // If unable to, possible due to permissions
                        // Flag Variable
                        $numNoDeletes++;
                    }
                }
            }
        }

        // Now Delete Folder itself
        if ($numNoDeletes == 0 and file_exists($presentationFolder))
        {
            rmdir($presentationFolder);
        }

        $thumb = $this->objConfig->getcontentBasePath().'webpresent_thumbnails/'.$id.'.jpg';
        if (file_exists($thumb))
        {
            unlink ($thumb);
        }



        $objTags = $this->getObject('dbwebpresenttags');
        $objTags->deleteTags($id);

        $objSlides = $this->getObject('dbwebpresentslides');
        $objSlides->deleteSlides($id);

        $this->delete('id', $id);

    }
}
?>