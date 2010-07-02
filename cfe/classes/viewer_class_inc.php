<?php

class viewer extends object {

    function init() {
        $this->loadClass("link", "htmlelements");
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

    function getLatestBlog($category, $title) {

        $objBlog = $this->getObject('elsiblog');
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $blogArray = $objBlog->getLatestBlog($category);
     
        $content = "No posts";
        $id = "";
        if (count($blogArray) > 0) {
            foreach ($blogArray as $blog) {
                $content = $blog['post_excerpt'];
                $id = $blog['id'];
            }
        }
        $objTrim = $this->getObject('trimstr', 'strings');
        $content=$objTrim->strTrim($content, 45);
       
        $link = new link($this->uri(array("action" => "viewsingle", "postid" => $id)));
        $link->link = $content;
        $content = $link->show();
        $block = "blog" . $index++;
        $hidden = 'default';
        $showToggle = false;
        $showTitle = true;
        $cssClass = "featurebox";
        $result.=$objFeatureBox->show(
                        $title,
                        $content,
                        $block,
                        $hidden,
                        $showToggle,
                        $showTitle,
                        $cssClass, '');
        return $result;
    }

}
?>
