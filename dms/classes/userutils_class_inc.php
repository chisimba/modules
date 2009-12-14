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
class userutils extends object {
    var $heading = "Document Management System";
    var $resourcePath;
    public function init() {
    //instantiate the language object
        $this->loadClass('link', 'htmlelements');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUploadTable = $this->getObject('dbfileuploads');
        $this->objPermittedTypes = $this->getObject('dbpermittedtypes');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');
        $this->objAltConfig = $this->getObject('altconfig','config');
        $this->resourcePath=$this->objAltConfig->getModulePath();
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');

        $replacewith="";
        $docRoot=$_SERVER['DOCUMENT_ROOT'];
        $location = "http://" . $_SERVER['HTTP_HOST'];
        $this->sitePath=$location.'/'. str_replace($docRoot,$replacewith,$this->resourcePath);
    }

    public function showPageHeading($page=null) {
        if($page != null) {
            return $this->heading." - ".ucfirst($page);
        }
        else {
            return $this->heading;
        }
    }

    public function searchFiles($url) {
        $script = "
                var url = '".$url."';
                
                showButtons();
                showSearchForm(url);
        ";
        return $script;
    }

    public function showUploadForm($url=null) {
    //instantiate the file upload object
        $this->objUpload = $this->getObject('upload', 'filemanager');
        $script = '
            var url ="'.$url.'";
            showUploadForm(url);
        ';
        return $script;
    //return $this->objUpload->show();
    }

    public function getRecentFiles($userid) {
        $this->objUploadTable->setUserId($userid);
        $myData = $this->objUploadTable->getAllFiles();
        $count = 1;
        $numRows = count($myData);
        $detailsLink = new link();
        $deleteLink = new link();
        $fileData = "[";
        foreach($myData as $row) {
        // get the description for this file type.
            $name = $this->objPermittedTypes->getFileDesc($row['filetype']);
            $fileData .= "['".$row['filename']."','";
            if($row['shared'] == 1) {
                $fileData .= "public";
            }
            else {
                $fileData .= "private";
            }
            $detailsLink->link($this->uri(array('action'=>'viewFileDetails','id'=>$row['id'])));
            $this->objIcon->setIcon('preview');
            $detailsLink->link=$this->objIcon->show();
            $deleteLink->link($this->uri(array('action'=>'deletefile','id'=>$row['id'])));
            $this->objIcon->setIcon('delete');
            $deleteLink->link=$this->objIcon->show();
            $date = date_create($row['date_forwaded']);
            $fileData .= "','".date_format($date, "m/d/Y")."','".$row['filetype']."','".$name."','".$detailsLink->show().$deleteLink->show()."'";
            $fileData .= "]";
            if($count < $numRows) {
                $fileData .= ",";
            }
            $count++;
        }
        $fileData .= "]";
        $data = "
                showLatestUploads(".$fileData.");";

        return $data;
    }

    public function saveFile($permissions) {
    //declare user object
        $objUser = $this->getObject('user', 'security');
        /* create directory on which to save files */
        $objMkDir = $this->getObject('mkdir', 'files');

        $userid = $objUser->userId();
        $this->objUploadTable->setUserId($userid);
        if($permissions == 1) {
            $destinationDir = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$userid."/shared/".$id;
        }
        else {
            $destinationDir = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$userid."/".$id;
        }
        $objMkDir->mkdirs($destinationDir);

        @chmod($destinationDir, 0777);

        $objFileUpload = $this->getObject('upload');
        $objFileUpload->overWrite = TRUE;
        $objFileUpload->uploadFolder = $destinationDir.'/';

        $result = $objFileUpload->doUpload(TRUE);

        if ($result['success'] == FALSE) {
            return $result['message'];
        }
        else {
            $filename = $result['filename'];
            $ext = $result['extension'];
            $file = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$filename;

            if (is_file($file)) {
                @chmod($file, 0777);
            }

            // save the file information into the database
            $data = array('filename'=>$filename, 'filetype'=>$ext, 'date_uploaded'=>strftime('%Y-%m-%d %H:%M:%S',mktime()),'userid'=>$objUser->userId(), 'shared'=>$permissions);
            $result = $this->objUploadTable->saveFileInfo($data);

            return "success";
        }
    }

    public function createJSONFileData($userid) {
        $this->objUploadTable->setUserId($userid);
        //get distinct file types
        $distinctFT = $this->objUploadTable->getFileTypes();

        $count = 1;
        $numTypes = count($distinctFT);
        $JSONstr = "[";
        foreach($distinctFT as $data) {
            $docs = $this->objUploadTable->getDocs($data['filetype']);

            // get the file type description from the permittedtypes table
            $fileDesc = $this->objPermittedTypes->getFileDesc($data['filetype']);

            $numRows = count($txtDocs);
            $JSONstr .= "{";

            $numRows = count($docs);
            $JSONstr .= "
    filename:'".$fileDesc."',";
            $JSONstr .= "
    duration:'',
    uiProvider:'col',
    cls:'master-task',
    iconCls:'task-folder',
    children:[";

            $counter = 1;
            foreach($docs as $newdata) {
                $mylink = new link();
                $mylink->link($this->uri(array('action'=>'viewfiledetails', 'id'=>$newdata['id'])));
                $mylink->link = "Click here for details";
                $date = date_create($newdata['date_uploaded']);
                if($newdata['shared'] == '1') {
                    $status = "public";
                }
                else {
                    $status = "private";
                }


                $JSONstr .= "
    {
        filename:'".$newdata['filename']."',
        duration:'".$newdata['filetype']."',
        details: '".str_replace("amp;", "", $mylink->show())."',
        modified: '".date_format($date, 'm/d/Y')."',
        status: '".$status."',
        uiProvider:'col',
        leaf:true,
        iconCls:'task'
    }";
                if($counter < $numRows) {
                    $JSONstr .= ",";
                }
                $counter++;
            }
            $JSONstr .= "
]}";
            if($count < $numTypes) {
                $JSONstr .= ",";
            }
            $count++;

        }
        $JSONstr .= "]";
        echo $JSONstr;
    }

    public function deleteFile($userid, $id) {
        $fileData = $this->objUploadTable->getFileName($id);

        // get the name of the file
        $filename = $fileData['filename'];
        $permission = $fileData['shared'];

        if($permission == 1) {
            $myFile = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$userid.'/shared/'.$filename;
        }
        else {
            $myFile = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$userid.'/'.$filename;
        }

        if(file_exists($myFile) && is_file($myFile)) {
        //unlink($myFile);
            return true;
        }
        else {
            return false;
        }
    }

    function getFiles() {

        $dir = $this->objSysConfig->getValue('FILES_DIR', 'dms');
        $node = isset($_REQUEST['node'])?$_REQUEST['node']:"";
        if(strpos($node, '..') !== false) {
            die('Nice try buddy.');
        }
        $nodes = array();

        $d = dir($dir.$node);
        while($f = $d->read()) {
            $add=true;
            //check permisions here first before adding it to list
            /*
             * ** permision check start ***/



            /*** person check end ***/

            if($add) {
                if($f == '.' || $f == '..' || substr($f, 0, 1) == '.')continue;
                $lastmod = date('M j, Y, g:i a',filemtime($dir.$node.'/'.$f));
                if(is_dir($dir.$node.'/'.$f)) {
                    $qtip = 'Type: Folder<br />Last Modified: '.$lastmod;
                    $nodes[] = array('text'=>$f, 'id'=>$node.'/'.$f, 'cls'=>'folder','lastmodified'=>$lastmod,
                        'size'=>$size,'parent'=>$node);
                }else {
                    $size = $this->formatBytes(filesize($dir.$node.'/'.$f), 2);
                    $downloadurl=$this->objAltConfig->getSiteRoot().'?module=dms&action=downloadfile&filename='.$f;
                    $deleteurl=$this->objAltConfig->getSiteRoot().'?module=dms&action=deletefile&filename='.$f;
                    $nodes[] = array('text'=>$f,
                        'id'=>$node.'/'.$f,
                        'leaf'=>true,
                        'cls'=>'file',
                        'lastmodified'=>$lastmod,
                        'size'=>$size,
                        'parent'=>$node,
                        'downloadurl'=>$downloadurl,
                        'downloadimgurl'=>$this->sitePath.'/dms/resources/images/arrow_down.png',
                        'deleteurl'=>$deleteurl,
                        'deleteimgurl'=>$this->sitePath.'/dms/resources/images/delete.png'
                    );
            }}
        }
        $d->close();
        echo json_encode($nodes);

    }

    // from php manual page
    function formatBytes($val, $digits = 3, $mode = "SI", $bB = "B") { //$mode == "SI"|"IEC", $bB == "b"|"B"
        $si = array("", "K", "M", "G", "T", "P", "E", "Z", "Y");
        $iec = array("", "Ki", "Mi", "Gi", "Ti", "Pi", "Ei", "Zi", "Yi");
        switch(strtoupper($mode)) {
            case "SI" : $factor = 1000; $symbols = $si; break;
            case "IEC" : $factor = 1024; $symbols = $iec; break;
            default : $factor = 1000; $symbols = $si; break;
        }
        switch($bB) {
            case "b" : $val *= 8; break;
            default : $bB = "B"; break;
        }
        for($i=0;$i<count($symbols)-1 && $val>=$factor;$i++)
            $val /= $factor;
        $p = strpos($val, ".");
        if($p !== false && $p > $digits) $val = round($val);
        elseif($p !== false) $val = round($val, $digits-$p);
        return round($val, $digits) . " " . $symbols[$i] . $bB;
    }

    function downloadFile($filename) {
        $download_path= $this->objSysConfig->getValue('FILES_DIR', 'dms');
        // Detect missing filename
        if(!$filename) die("I'm sorry, you must specify a file name to download.");

        // Make sure we can't download files above the current directory location.
        if(eregi("\.\.", $filename)) die("I'm sorry, you may not download that file.");
        $file = str_replace("..", "", $filename);

        // Make sure we can't download .ht control files.
        if(eregi("\.ht.+", $filename)) die("I'm sorry, you may not download that file.");

        // Combine the download path and the filename to create the full path to the file.
        $file = "$download_path/$file";

        // Test to ensure that the file exists.
        if(!file_exists($file)) die("I'm sorry, the file doesn't seem to exist.");

        // Extract the type of file which will be sent to the browser as a header
        $type = filetype($file);

        // Get a date and timestamp
        $today = date("F j, Y, g:i a");
        $time = time();

        // Send file headers
        header("Content-type: $type");
        header("Content-Disposition: attachment;filename=$filename");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Send the file contents.
        readfile($file);
    }

    public function createfolder($folderpath,$foldername) {
        $this->objMkdir = $this->getObject('mkdir', 'files');
        $path =$this->objSysConfig->getValue('FILES_DIR', 'dms').'/'.$folderpath.'/'.$foldername;
        $result = $this->objMkdir->mkdirs($path);
    }

    public function renamefolder($folderpath,$foldername) {
        $folderpath = str_replace("//", "", $folderpath);

        $prevpath = $this->objSysConfig->getValue('FILES_DIR', 'dms').'/'.$folderpath;
        $newpath = $this->objSysConfig->getValue('FILES_DIR', 'dms').'/'.$foldername;

        // do a move using command line interface from previous location to new location.
        $command = "mv ".$prevpath." ".$newpath;
        $res = system($command, $retval);

        if($retval != 0) {
            return "error";
        }
        else {
            return "success";
        }
    }

    public function deleteFolder($folderpath) {
        $folderpath = str_replace("//", "", $folderpath);
        $fullpath = $this->objSysConfig->getValue('FILES_DIR', 'dms').'/'.$folderpath;
        
        if (is_dir($fullpath)) {
            $res = rmdir($fullpath);
        }

        if($res) {
            return "success";
        }
        else {
            return "error";
        }
    }
}
?>