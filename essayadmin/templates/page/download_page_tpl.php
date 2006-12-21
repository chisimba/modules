<?
/*
* Download page for essay.
* @package essay
*/

/**
* Page to display dialog for downloading a file
* @author James Scoble
*/

$fileId=$this->getParam('fileid');
$data=$this->objFile->getArray("select * from tbl_essay_filestore where fileId='$fileId'");
if (count($data)==0){ // if the file has been deleted
    header("Status: 404 Not Found");
} else {
    $name=$data[0]['filename'];
    $size=$data[0]['size'];
    $type=$data[0]['filetype']; 
    $fileId2=$data[0]['fileId']; 
    $list=$this->objFile->getArray("select id from tbl_essay_blob where fileId='$fileId2' order by segment");

    header("Content-type: $type");
    header("Content-length: $size");
    header("Content-Disposition: attachment; filename=$name");
    header("Content-Description: PHP Generated Data");

    foreach ($list as $line)
    {
        $id=$line['id'];
        $filedata=$this->objFile->getArray("select * from tbl_essay_blob where id='$id'");
        echo $filedata[0]['filedata'];
    }
}
?>