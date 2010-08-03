<?php

class dbutil extends dbtable{

public init(){
}

function getNews($category) {

        $objCategories=$this->getObject("dbnewscategories","news");
        $news=$this->getObject("dbnewsstories","news");
        $categories=$objCategories->getCategories();
        foreach ($categories as $cat) {

            if($cat['categoryname'] == $category) {
                $catId=$cat['id'];
                return $newsStories=$news->getCategoryStories($catId);

            }
        }

        return array();
    }

/*function getStory($category){

$sql=
"select ********* where category =$category";
$data=$this->getArray($sql);

return $data;
}*/

}

?>




