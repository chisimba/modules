<?
$fileId=$this->getParam('fileId');
$data=$this->objAttach->getArray("select * from tbl_email_files where fileId='$fileId'");
$name=$data[0]['filename'];
$size=$data[0]['size'];
$type=$data[0]['filetype']; 
$list=$this->objAttach->getArray("select id from tbl_email_blob where fileId='$fileId' order by segment");

header("Content-type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename=$name");
header("Content-Description: PHP Generated Data");

foreach ($list as $line)
{
    $id=$line['id'];
    $filedata=$this->objAttach->getArray("select * from tbl_email_blob where id='$id'");
    echo $filedata[0]['filedata'];
}


?>
