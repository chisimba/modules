<?php

class viewer extends object {

    function init() {
        $this->loadClass("link", "htmlelements");
        $this->loadClass("fieldset", "htmlelements");
    }

    function getFeaturedNews() {
        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();
        $currentShow = "No news has been set up";
        $link = new link($this->uri(array("action" => "featured")));
        foreach ($categories as $cat) {

            if ($cat['categoryname'] == 'featured') {
                $onAirNowId = $cat['id'];
                $onAirNowStories = $news->getCategoryStories($onAirNowId);
                $currentShow = $onAirNowStories[0]['storytext'];
            }
        }
        $link->link = $currentShow;
        //return $link->show();
        return $currentShow;
    }

    function getLatestNews() {
        $topStories = $this->getTopStoriesFormatted();
        $result = "";
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $content = $topStories["stories"];
        $index = 0;
        //  $link = new link($this->uri(array("action" => "viewsingle", "postid" => $id), "blog"));
        //  $link->link = $content;
        //$content = $link->show();
        $block = "blog" . $index++;
        $hidden = 'default';
        $showToggle = false;
        $showTitle = true;
        $cssClass = "featureboxhome";
        $result.=$objFeatureBox->show(
                        "Latest News",
                        $content,
                        $block,
                        $hidden,
                        $showToggle,
                        $showTitle,
                        $cssClass, '');
        return $result;
    }

    public function getTopStoriesFormatted() {
        $objNewsStories = $this->getObject('dbnewsstories', 'news');
        $result = "";
        $index = 0;
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $numTopStoriesValue = $objSysConfig->getValue('NUMFRONTPAGETOPICS', 'news');
        $trimsize = $objSysConfig->getValue('TRIMSIZE', 'news');
        $objWashout = $this->getObject('washout', 'utilities');

        $stories = $objNewsStories->getTopStories($numTopStoriesValue);

        if (count($stories) == 0) {
            return array('topstoryids' => array(), 'stories' => '');
        } else {
            $output = '';

            $objTrimString = $this->getObject('trimstr', 'strings');
            $objThumbnails = $this->getObject('thumbnails', 'filemanager');

            $storyIds = array();

            foreach ($stories as $story) {
                $storyIds[] = $story['id'];

                $output .= '<div class="newsstory">';

                $storyLink = new link($this->uri(array('action' => 'viewstory', 'id' => $story['id']), "news"));
                $storyLink->link = $story['storytitle'];

                if ($story['storyimage'] != '') {
                    $storyLink->link = '<img width="32" height="32" class="storyimage" src="' . $objThumbnails->getThumbnail($story['storyimage'], $story['filename']) . '" alt="' . $story['storytitle'] . '" title="' . $story['storytitle'] . '" />';

                    $output .= '<div class="storyimagewrapper">' . $storyLink->show() . '</div>';
                }

                $storyLink->link = $story['storytitle'];
                $output .= '<div id="newsstorytext-header"><h3>' . $storyLink->show() . '</h3></div>';
                $output.='<div id="storydate">' . $story['storydate'] . '<div>';
                $output .= '</div>';
            }

            return array('topstoryids' => $storyIds, 'stories' => $output);
        }
    }

    function getLatestBlogs() {
        $alllink = new link($this->uri(array("action"=>"allblogs"), "blog"));
        $alllink->link = 'View all blogs';
        $index = 0;
        $result = "";
        $blogPosts = $this->getObject('blogposts', 'blog');
        $display = $blogPosts->showLastTenPosts(3);
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $content = $alllink->show().$display;
        $block = "blog" . $index++;
        $hidden = 'default';
        $showToggle = false;
        $showTitle = true;
        $cssClass = "featureboxhome";
        $result.=$objFeatureBox->show(
                        "Blogs",
                        $content,
                        $block,
                        $hidden,
                        $showToggle,
                        $showTitle,
                        $cssClass, '');
        return $result;
    }

    function getEvents() {
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $objCategories = $this->getObject("dbnewscategories", "news");
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $news = $this->getObject("dbnewsstories", "news");
        $objTrimString = $this->getObject('trimstr', 'strings');
        $categories = $objCategories->getCategories();
        $currentShow = "No events have been set up";

        foreach ($categories as $cat) {

            if ($cat['categoryname'] == 'events') {
                $onAirNowId = $cat['id'];
                $onAirNowStories = $news->getCategoryStories($onAirNowId);
                $currentShow = $onAirNowStories[0]['storytitle'];
            }
        }
        $storyLink = new link($this->uri(array('action' => 'viewstory', 'id' => $onAirNowStories[0]['id']), "news"));
        $storyLink->link = '<h1>' . $onAirNowStories[0]['storytitle'] . '</h1>' . $objTrimString->strTrim($onAirNowStories[0]['storytext']);
        $content = $storyLink->show();
        $block = "events";
        $hidden = 'default';
        $showToggle = false;
        $showTitle = true;
        $result = "";
        $cssClass = "featureboxhome";
        $result.=$objFeatureBox->show(
                        "Events",
                        $content,
                        $block,
                        $hidden,
                        $showToggle,
                        $showTitle,
                        $cssClass, '');
        return $result;
    }

    function getDocumentation() {
        $objConfig = $this->getObject("altconfig", "config");
        $siteRoot = $objConfig->getSiteRoot();


        $index = 0;
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();
        $documentation = "No documents have been set up";
        $link = new link($this->uri(array("action" => "featured")));
        foreach ($categories as $cat) {

            if ($cat['categoryname'] == 'documentation') {
                $documentationId = $cat['id'];
                $documentationStories = $news->getCategoryStories($documentationId);
                $documentation = $documentationStories[0]['storytext'];
            }
        }

        $content = $documentation;
        $block = "blog" . $index++;
        $hidden = 'default';
        $showToggle = false;
        $showTitle = true;
        $result = "";
        $cssClass = "featureboxhome";
        $result.=$objFeatureBox->show(
                        "Documentation",
                        $content,
                        $block,
                        $hidden,
                        $showToggle,
                        $showTitle,
                        $cssClass, '');
        return $result;
    }

    function getTweets() {
        $index = 0;
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();
        $tweets = "No tweets has been set up";
        $link = new link($this->uri(array("action" => "featured")));
        foreach ($categories as $cat) {

            if ($cat['categoryname'] == 'tweet') {
                $tweetsId = $cat['id'];
                $tweetsStories = $news->getCategoryStories($tweetsId);
                $tweets = $tweetsStories[0]['storytext'];
            }
        }
        $objWashOut = $this->getObject("washout", "utilities");
        $content = $objWashOut->parseText($tweets);

        $block = "blog" . $index++;
        $hidden = 'default';
        $showToggle = false;
        $showTitle = true;
        $result = "";
        $cssClass = "featurebox";
        $result.=$objFeatureBox->show(
                        "eLearning Tweets",
                        $content,
                        $block,
                        $hidden,
                        $showToggle,
                        $showTitle,
                        $cssClass, '');
        return $result;
        //
        //return $content;
        // $fieldsetobj=new fieldset();
        //$fieldsetobj->setLegend("eLearning Tweets");
        // $fieldsetobj->addContent('eLearning Tweets'.$content);
        //  return $fieldsetobj->show();
    }

}

?>
