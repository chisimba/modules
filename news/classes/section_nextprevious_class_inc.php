<?php

class section_nextprevious extends object
{

    public function init()
    {
        $this->objStories = $this->getObject('dbnewsstories');
        // Load Menu Tools Class
        $this->objMenuTools =& $this->getObject('tools', 'toolbar');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
    * Method to render the section view of category
    * @param array $category
    * @return string
    */
    public function renderSection($category)
    {
        $firstStory = $this->objStories->getFirstStory($category['id'], str_replace('_', ' ', $category['itemsorder']));

        if ($firstStory == FALSE) {
            $this->setVar('pageTitle', $category['categoryname']);
            $this->objMenuTools->addToBreadCrumbs(array($category['categoryname']));
            return '<h1>'.$category['categoryname'].'</h1>'.
            '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_categorydoesnothavestories', 'news', 'This category does not have any stories yet.').'</div>';
        } else {
            return $this->renderPage($firstStory, $category);
        }
    }

    public function renderPage($story, $category)
    {
        $objDateTime = $this->getObject('dateandtime', 'utilities');

        $categoryLink = new link ($this->uri(array('action'=>'viewcategory', 'id'=>$category['id'])));
        $categoryLink->link = $category['categoryname'];

        $this->objMenuTools->addToBreadCrumbs(array($categoryLink->show(), $story['storytitle']));

        $this->setVar('pageTitle', $story['storytitle']);

        $header = new htmlheading();
        $header->type = 1;
        $header->str = $story['storytitle'];

        $str = $header->show();

        $str .= '<p>'.$objDateTime->formatDate($story['storydate']).'</p>';

        /*
        if ($story['storyimage'] != '') {
            $str .= '<img class="storyimage" src="'.$objThumbnails->getThumbnail($story['storyimage'], $story['filename']).'" alt="'.$story['storytitle'].'" title="'.$story['storytitle'].'" />';
        }
        */

        $objWashOut = $this->getObject('washout', 'utilities');

        $str .= $objWashOut->parseText($story['storytext']);

        if ($story['storysource'] != '') {
            $objUrl = &$this->getObject('url', 'strings');
            $source = $story['storysource'];

            $source = $objUrl->makeClickableLinks(htmlentities($source));


            $str .= '<p><strong>Source:</strong><br />'.$source.'</p>';
        }

        $previousPage = $this->objStories->getPreviousItem($story['id'], $category['id']);
        $nextPage = $this->objStories->getNextItem($story['id'], $category['id']);

        if ($previousPage == FALSE) {
            $prevContent = '';
        } else {
            $prevPageLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$previousPage['id'])));
            $prevPageLink->link = '&laquo; Previous: '.$previousPage['storytitle'];
            $prevContent = $prevPageLink->show();
        }

        if ($nextPage == FALSE) {
            $nextContent = '';
        } else {
            $nextPageLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$nextPage['id'])));
            $nextPageLink->link = 'Next: '.$nextPage['storytitle'].' &raquo;';
            $nextContent = $nextPageLink->show();
        }

        if ($prevContent != '' || $nextContent != '') {
            $table = $this->newObject('htmltable', 'htmlelements');
            $table->width = '99%';
            $table->startRow();
            $table->addCell($prevContent, '50%');
            $table->addCell($nextContent, '50%', NULL, 'right');
            $table->endRow();

            $str .= $table->show();
        }

        return $str;
    }

}
?>