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
                'title' => stripslashes($filename),
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

    public function getByUser($user, $order='dateuploaded DESC')
    {
        return $this->getAll(' WHERE creatorid=\''.$user.'\' ORDER BY '.$order);
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

                echo $file['id'].' - '.$result.'<br />';
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
        return $this->update('id', $id, array('inprocess'=>'Y', 'processstage'=>$step));
    }


    private function convertFile($file)
    {
        //print_r($file);

        $path_parts = pathinfo($file['filename']);

        $ext = $path_parts['extension'];

        //echo $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;

        if ($file['filetype'] != 'powerpoint' || $file['filetype'] != 'openoffice')
        {
            $ext = $this->fixUnknownFileType($file['id'], $file['filename']);
            //echo $ext.'<br />';
        }



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

    private function fixUnknownFileType($id, $existingFilename)
    {
        $objScan = $this->getObject('scanfordelete');

        // Set Directory to Var
        $presentationFolder = $this->objConfig->getcontentBasePath().'webpresent/'.$id;

        // Scan Results
        $results = $objScan->scanDirectory($presentationFolder);

        if (count($results['files']) > 0)
        {
            foreach ($results['files'] as $file)
            {
                //echo $file.'<br />';
                $file = basename($file);

                if (preg_match('/'.$id.'\.(odp|ppt)/', $file)) {
                    $path_parts = pathinfo($file);

                    $ext = $path_parts['extension'];

                    if ($existingFilename == '')
                    {
                        $existingFilename = $file;
                    }


                    if ($ext == 'odp')
                    {
                        $this->update('id', $id, array(
                            'filename' => $existingFilename,
                            'filetype' => 'openoffice',
                        ));
                    } else {
                        $this->update('id', $id, array(
                            'filename' => $existingFilename,
                            'filetype' => 'powerpoint',
                        ));
                    }

                    //echo 'in here'.$ext;
                    // Return Correct Extension
                    return $ext;
                }
            }
        }
        // Unknown Item
        return 'bug';
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

    public function getPresentationThumbnail($id, $title='')
    {
        $source = $this->objConfig->getcontentBasePath().'webpresent_thumbnails/'.$id.'.jpg';
        $relLink = $this->objConfig->getsiteRoot().$this->objConfig->getcontentPath().'webpresent_thumbnails/'.$id.'.jpg';

        if (trim($title) == '')
        {
            $title = '';
        } else {
            $title = ' title="'.htmlentities($title).'" alt="'.htmlentities($title).'"';
        }

        if (file_exists($source)) {

            return '<img src="'.$relLink.'" '.$title.' style="border:1px solid #000;" />';
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

                    return '<img src="'.$imgRel.'" '.$title.' style="border:1px solid #000;" />';
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

    public function getLatestFeed()
    {
        $title = $this->objConfig->getSiteName().' - 10 Newest Uploads';
        $description = 'A List of the Latest Presentations to be uploaded to the '.$this->objConfig->getSiteName().' Site';
        $url = $this->uri(array('action'=>'latestrssfeed'));

        $files = $this->getLatestPresentations();

        return $this->generateFeed($title, $description, $url, $files);
    }

    public function getUserFeed($userId)
    {
        $fullName = $this->objUser->fullName($userId);
        $title = $fullName.'\'s Files';
        $description = 'A List of the Latest Presentations uploaded by '.$fullName;
        $url = $this->uri(array('action'=>'userrss', 'userid'=>$userId));

        $files = $this->getByUser($userId);

        return $this->generateFeed($title, $description, $url, $files);
    }

    public function generateFeed($title, $description, $url, $files)
    {
        $objFeedCreator = $this->getObject('feeder', 'feed');
        $objFeedCreator->setupFeed(TRUE, $title, $description, $this->objConfig->getsiteRoot(), $url);

        if (count($files) > 0)
        {
            $this->loadClass('link', 'htmlelements');
            $objDate = $this->getObject('dateandtime', 'utilities');

            foreach ($files as $file)
            {

                if (trim($file['title']) == '') {
                    $filename = $file['filename'];
                } else {
                    $filename = htmlentities($file['title']);
                }

                $link = str_replace('&amp;', '&', $this->uri(array('action'=>'view', 'id'=>$file['id'])));

                $imgLink = new link($link);
                $imgLink->link = $this->getPresentationThumbnail($file['id'], $filename);

                $date = $objDate->sqlToUnixTime($file['dateuploaded']);


                $objFeedCreator->addItem($filename, $link, $imgLink->show().'<br />'.$file['description'], 'here', $this->objUser->fullName($file['creatorid']), $date);
            }


        }

        return $objFeedCreator->output();
    }

    public function displayAsTable($files)
    {
        if (count($files) == 0) {
            return '';
        } else {
            $table = $this->newObject('htmltable', 'htmlelements');

            $divider = '';

            $objDateTime = $this->getObject('dateandtime', 'utilities');
            $objDisplayLicense = $this->getObject('displaylicense', 'creativecommons');
            $objDisplayLicense->icontype = 'small';

            $counter = 0;
            $inRow = FALSE;

            foreach ($files as $file)
            {
                $counter++;

                if (($counter%2) == 1)
                {
                    $table->startRow();
                }


                $link = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
                $link->link = $this->getPresentationThumbnail($file['id']);

                $table->addCell($link->show(), 120);
                $table->addCell('&nbsp;', 10);

                $rightContent = '';

                if (trim($file['title']) == '') {
                    $filename = $file['filename'];
                } else {
                    $filename = htmlentities($file['title']);
                }

                $link->link = $filename;
                $rightContent .= '<p><strong>'.$link->show().'</strong><br />';

                if (trim($file['description']) == '') {
                    $description = '<em>File has no description</em>';
                } else {
                    $description = nl2br(htmlentities($file['description']));
                }

                $rightContent .= $description.'</p>';

                // Set License to copyright if none is set
                if ($file['cclicense'] == '')
                {
                    $file['cclicense'] = 'copyright';
                }

                $rightContent .= '<p><strong>License:</strong> '.$objDisplayLicense->show($file['cclicense']).'<br />';

                $userLink = new link ($this->uri(array('action'=>'byuser', 'userid'=>$file['creatorid'])));
                $userLink->link = $this->objUser->fullname($file['creatorid']);

                $rightContent .= '<strong>Uploaded By:</strong> '.$userLink->show().'<br />';
                $rightContent .= '<strong>Date Uploaded:</strong> '.$objDateTime->formatDate($file['dateuploaded']).'</p>';

                $table->addCell($rightContent, '40%');


                if (($counter%2) == 0)
                {
                    $table->endRow();
                }

                $divider = 'addrow';
            }

            if (($counter%2) == 1)
            {
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->endRow();
            }

            return $table->show();

        }
    }
}
?>