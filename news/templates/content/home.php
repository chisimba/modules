<?php

echo '<pre>';
echo '$topStories::';
var_dump($topStories);
echo '</pre>';

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');



$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_latestnews', 'news', 'Latest News');

$middle = $this->objNewsMenu->toolbar('home');

$middle .= $header->show();

if ($this->objSysConfig->getValue('mod_news_homeintroduction', 'news')) {
    foreach ($categories as $category) {
        if ($category['showintroduction'] == 'Y') {
            $middle .= $this->objWashOut->parseText($category['introduction']).'<br /><br />';
        }
    }
}

$middle .= $topStories;

if (count($categories) > 0) {

    $table = $this->newObject('htmltable', 'htmlelements');
    //print_r($topStoriesId);
    $counter = 0;
    foreach ($categories as $category)
    {
        if ($category['blockonfrontpage'] == 'Y') {
            $nonTopStories = $this->objNewsStories->getNonTopStoriesFormatted($category['id'], $topStoriesId);
            echo '<pre>';
            echo '$nonTopStories::';
            var_dump($nonTopStories);
            echo '</pre>';
            if ($nonTopStories != '') {

                if ($counter%2 == 0) {
                    $middle .= '<br clear="all" />';
                }
                $middle .= '<div style="width:50%; float:left; "><h3>'.$category['categoryname'].'</h3>';
                $middle .= $nonTopStories.'</div>';

                $counter++;
            }
        }

    }
}

$middle .= '<br clear="both" />';

echo $middle;

?>
