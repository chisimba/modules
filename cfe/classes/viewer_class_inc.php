<?php

class viewer extends object {

    function init() {
        $this->loadClass("link", "htmlelements");
    }

    function getStory($categoryName) {
        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();
        $story = "No news has been set up";
        $link = new link($this->uri(array("action" => "featured")));
        foreach ($categories as $cat) {

            if ($cat['categoryname'] == $categoryName) {
                $id = $cat['id'];
                $stories = $news->getCategoryStories($id);
                $story = $stories[0]['storytext'];
            }
        }
        return $story;
    }

}
?>
