<?php
$target_path = $_POST['contentBasePath'];
echo 'content path: '.$target_path;
$target_path = $target_path . '/'.basename( $_FILES['File0']['name']); 

if(move_uploaded_file($_FILES['File0']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['filename']['name']). 
    " has been uploaded";
    @chmod($target_path, 0777);
} else{
    echo "There was an error uploading the file, please try again!";
}
?>