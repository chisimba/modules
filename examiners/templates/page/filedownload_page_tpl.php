<?php
// This page does not output text, but a binary file - it calls the BLOB class for this.
    $name = $this->filePath.$id.'.csv';
    $file = fopen($name, 'r');
    $contents = fread($file, filesize($name));
    fclose($file);

    header('Content-length: '.filesize($name));
    header('Content-type: application/csv');
    header('Content-disposition: attachment; filename="'.basename($name).'"');
    header("Content-Description: PHP Generated Data");
    
    echo $contents;
?>