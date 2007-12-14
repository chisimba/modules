<?php

if (count($files) > 0) {
    
    $objThumbnails = $this->getObject('thumbnails', 'filemanager');
    
    foreach ($files as $file)
    {
        if (!in_array($file['id'], $usedImages)) {
            echo '<div id="add_'.$file['id'].'" class="photoimage">';
            
            echo '<a href="javascript:addPhoto(\''.$file['id'].'\');">'.$this->objLanguage->languageText('mod_news_addphoto', 'news', 'Add Photo').'</a>';
            
            echo '<div id="contents_'.$file['id'].'">';
            echo '<img src="'.$objThumbnails->getThumbnail($file['id'], $file['filename']).'" />';
            echo '</div>';
            echo '</div>';
        }
    }
}

?>