<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'Category: '.$categoryName;
echo $header->show();

$objTrimString = $this->getObject('trimstr', 'strings');
$objThumbnails = $this->getObject('thumbnails', 'filemanager');
$objDateTime = $this->getObject('dateandtime', 'utilities');

$output = '';

foreach ($categoryStories as $story)
{
    
    $output .= '<div class="newsstory">';
    
    $storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$story['id'])));
    $storyLink->link = $story['storytitle'];
    
    if ($story['storyimage'] != '') {
        $storyLink->link = '<img class="storyimage" src="'.$objThumbnails->getThumbnail($story['storyimage'], $story['filename']).'" alt="'.$story['storytitle'].'" title="'.$story['storytitle'].'" />';
        
        $output .= $storyLink->show();
    }
    
    $storyLink->link = $story['storytitle'];
    
    $output .= '<h3>'.$objDateTime->formatDate($story['storydate']).' - '.$storyLink->show().'</h3>';
    
    if ($story['location'] != '') {
        $locationLink = new link ($this->uri(array('action'=>'viewbylocation', 'id'=>$story['storylocation'])));
        $locationLink->link = $story['location'];
        $output .= '[ '.$locationLink->show().'] ';
    }
    
    $output .= $objTrimString->strTrim(strip_tags($story['storytext']), 150, TRUE);
    
    $storyLink->link = 'Read Story';
    $output .= ' ('.$storyLink->show().')';
    
    $output .= '</div><br clear="both" />';
}

echo $output;
?>