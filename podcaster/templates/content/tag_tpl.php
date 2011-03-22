<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$heading = new htmlheading();
$heading->str = 'Tag - '.$tag;

$altText = $this->objLanguage->languageText("mod_podcaster_podcast", "podcaster", 'Podcast').' '. $this->objLanguage->languageText("mod_podcaster_tag", "podcaster", 'Tag').': ' . $tag;
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('rss');
$objIcon->alt = $altText;

$rssLink = new link ($this->uri(array('action'=>'tagrss', 'tag'=>$tag)));
$rssLink->link = $objIcon->show();

$heading->str .= ' '.$rssLink->show();

$heading->type = 1;

echo $heading->show();

if (count($files) == 0) {
    echo '<div class="noRecordsMessage">No files matches this tag</div>';
} else {
    $sortOptions = array(
        'datecreated_desc' => 'Newest First',
        'datecreated_asc' => 'Oldest First',
        'title_asc' => 'Alphabetical',
        'creatorname_asc' => 'User'
    );

    echo '<p><strong>Sort By:</strong> ';

    $divider = '';
    foreach ($sortOptions as $sortOption=>$optionText)
    {
        if ($sortOption == $sort)
        {
            echo $divider.$optionText;
        } else {
            $sortLink = new link ($this->uri(array('action'=>'tag', 'tag'=>$tag, 'sort'=>$sortOption)));
            $sortLink->link = $optionText;

            echo $divider.$sortLink->show();
        }

        $divider = ' | ';

    }

    echo '</strong></p>';

    $objViewer = $this->getObject('viewer');
    echo $objViewer->displayAsTable($files);

}

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Back to Home';

echo '<p>'.$homeLink->show().'</p>';

?>