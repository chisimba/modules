<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!
/**
 * Description for $GLOBALS
 * @global string $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class viewerutils extends object {

    public function init() {
        $this->objDbSysconfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->loadClass('link', 'htmlelements');
        $this->objWashout = $this->getObject('washout', 'utilities');
        $this->storyparser=$this->getObject('storyparser');
    }

    public function getHomePageContent() {

        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');
        $id=$this->objDbSysconfig->getValue('DEFAULT_STORY_ID','jukskei');
        $data= $objStories->getStory($id);
        $content='';
        $topicsnavid=$this->objDbSysconfig->getValue('TOPICS_NAV_CATEGORY','jukskei');
        $topicnavs=$this->storyparser->getStoryByCategory($topicsnavid);
        $navbar='';
        foreach($topicnavs as $nav) {
        // $menuOptions[]=    array('action'=>'viewstory','storyid'=>$nav['id'], 'text'=>$nav['title'], 'actioncheck'=>array(), 'module'=>'jukskei', 'status'=>'both');
            $link = new link ($this->uri(array('action'=>'viewstory','storyid'=>$nav['id'])));
            $link->link = $nav['title'];
            $navbar.=$link->show().'&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $content='

<div id="contentwrapper" class="subcolumns">

       '.$this->getHomePageMedia().'

</div>
<div id="contentwrapper" class="subcolumns">

     <br/><font style="font-family:Arial;font-size:24;">  '.$navbar.'</font><br/><br/><br/>'.$this->objWashout->parseText($data['maintext']).'


</div>';

        return $content;
    }

    public function getHomePageMedia() {

        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');
        $id=$this->objDbSysconfig->getValue('DEFAULT_MEDIA_ID','jukskei');
        $data= $objStories->getStory($id);
        $content='';

        $content='

            <ul class="paneltabs">

            '.$this->objWashout->parseText($data['maintext']).'</ul>';

        return $content;
    }

    public function getTopicsContent($parentid) {




        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');

        if($parentid == '') {
            $category=$this->objDbSysconfig->getValue('TOPIC_CATEGORY','jukskei');
            $data= $objStories->getHomePageContent($category);
            $parentid=$data[0]['id'];
        }
        $topics=$objStories->getTopics($parentid);
        $content='';
        $homepagetitlelinks='';
        foreach($topics as $topic) {
            $link=new link($this->uri(array("id"=>$topic['id'],'action'=>'viewtopic')));
            $link->link=$topic['title'];
            $homepagetitlelinks.=$link->show().'&nbsp;|&nbsp;';
        }
        $defaulttopicid=$topics[0]['id'];

        $defaulttopiccontent=$objStories->getTopic($defaulttopicid);
        ;

        $content='
            <ul class="paneltabs">

            '.$defaulttopiccontent[0]['description'].'</ul>';

        return $content;
    }
    public function getTopicContent($id) {


        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');
        $topics=$objStories->getTopics($id);
        $content='';
        $navlinks='';
        foreach($topics as $topic) {
            $link=new link($this->uri(array("id"=>$topic['id'],'action'=>'viewtopic')));
            $link->link=$topic['title'];
            $navlinks.=$link->show().'&nbsp;|&nbsp;';
        }
        $defaulttopicid=$topics[0]['id'];

        if(count($topics) < 1) {
            $defaulttopicid=$id;
        }
        $parentdata=$objStories->getParent($defaulttopicid);
        $grandparentdata=$objStories->getParent($parentdata[0]['id']);
        $parentlink=new link($this->uri(array("parentid"=>$grandparentdata[0]['id'],'action'=>'viewtopics')));
        $parentlink->link=$parentdata[0]['title'];
        $defaulttopiccontent=$objStories->getTopic($defaulttopicid);


        $content='
            <h2>'.$parentlink->show().'</h2><h4>'.$navlinks.'</h4>

            <ul class="paneltabs">

            '.$this->objWashout->parseText($defaulttopiccontent[0]['description']).'

            </ul>
            <br/>
              ';
        $content.='<div class="sectionstats">';
        $content.='<div class="subcolumns">';
        $content.='</div">';
        $content.='</div">';
        return $content;
    }

    public function getArticlesContent($topic, $title) {

        $this->loadClass('link', 'htmlelements');
        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');

        $title == '' ?'Living off the river':$title;
        $articlecontent=$objStories->getStoryByTitle($title,'juk_articles');
        $content='';
        $topiclink1=new link($this->uri(array("title"=>'Living off the river','action'=>'viewtopic')));
        $topiclink1->link='Living off the river';

        $topiclink2=new link($this->uri(array("title"=>"Jukskeis past and present",'action'=>'viewtopic')));
        $topiclink2->link="Jukskei's past and present";

        $topiclink3=new link($this->uri(array("title"=>'Environment and water','action'=>'viewtopic')));
        $topiclink3->link='Environment and water';



        $homepagetitle=$link1->show().'&nbsp;|&nbsp;'.$link2->show().'&nbsp;|&nbsp;'.$link3->show();
        $content='
            <h4>'.$homepagetitle.'</h4>

            <ul class="paneltabs">

            '.$articlecontent.'

            </ul>
            <br/>
              ';
        $content.='<div class="sectionstats">';
        $content.='<div class="subcolumns">';
        $content.='</div">';
        $content.='</div">';
        return $content;
    }
    public function createCell($colType) {
        $str='<div class="'.$colType.'">
              <div class="subcl">
              <div class="sectionstats_content">

              <div class="statslistcontainer">

              <ul class="statslist">

              <li class="sectionstats_first">
              cell content can go in here
            cell content can go in here
cell content can go in here
cell content can go in here
cell content can go in here
 <br/><br/></br><br/><br/><br/><br/><br/><br/>
              </li>

              </ul>
 <div class="clear"></div>

              </div>
              </div>
              </div>
              </div>';
        return $str;
    }
    public function getTopic($id) {

        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');
        $data= $objStories->getStory($id);
        $topicsnavid=$this->objDbSysconfig->getValue('TOPICS_NAV_CATEGORY','jukskei');
        $topicnavs=$this->storyparser->getStoryByCategory($topicsnavid);
        $link = new link ($this->uri(array('action'=>'home')));
        $link->link = 'HOME&nbsp;&nbsp;|&nbsp;&nbsp;';
        $navbar=$link->show();
        foreach($topicnavs as $nav) {
            $link = new link ($this->uri(array('action'=>'viewstory','storyid'=>$nav['id'])));
            $link->link = $nav['title'];
            $navbar.=$link->show().'&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        $articlenav='';
        $articles=$this->storyparser->getArticleByAbstract($data['title']);
         foreach($articles as $nav) {
            $link = new link ($this->uri(array('action'=>'viewarticle','storyid'=>$id,'articleid'=>$nav['id'])));
            $link->link = $nav['title'];
            $articlenav.=$link->show().'&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        $content='';
        $content='

            <ul class="paneltabs">

             <font style="font-family:Arial;font-size:24;">  '.$navbar.'</font><hr>
             <b style="font-family:Arial;font-size:24;">'.$data['title'].'&nbsp; |&nbsp;</b><br/>
             <font style="font-family:Arial;font-size:18;">  '.$articlenav.'</font>
             '.$this->objWashout->parseText($data['maintext']).'

            </ul>
            <br/>
              ';

        return $content;
    }
   public function getArticleContent($storyid,$articleid) {

        $objTrim = $this->getObject('trimstr', 'strings');
        $objStories=$this->getObject('storyparser');
        $data= $objStories->getStory($articleid);
        $storydata= $objStories->getStory($storyid);
        $storylink=new link ($this->uri(array('action'=>'viewstory','storyid'=>$storyid)));
        $storylink->link=$storydata['title'];
        $topicsnavid=$this->objDbSysconfig->getValue('TOPICS_NAV_CATEGORY','jukskei');
        $topicnavs=$this->storyparser->getStoryByCategory($topicsnavid);
        $link = new link ($this->uri(array('action'=>'home')));
        $link->link = 'HOME&nbsp;&nbsp;|&nbsp;&nbsp;';
        $navbar=$link->show();
        foreach($topicnavs as $nav) {
            $link = new link ($this->uri(array('action'=>'viewstory','storyid'=>$nav['id'])));
            $link->link = $nav['title'];
            $navbar.=$link->show().'&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        $articlenav='';
        $articles=$this->storyparser->getArticleByAbstract($storydata['title']);
         foreach($articles as $nav) {
            $link = new link ($this->uri(array('action'=>'viewarticle','storyid'=>$storyid,'articleid'=>$nav['id'])));
            $link->link = $nav['title'];
            $articlenav.=$link->show().'&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        $content='';
        $content='

            <ul class="paneltabs">

             <font style="font-family:Arial;font-size:24;">  '.$navbar.'</font><hr>
             <b style="font-family:Arial;font-size:24;">'.$storylink->show().'&nbsp; |&nbsp;</b><br/>
             <b style="font-family:Arial;font-size:24;">'.$data['title'].'&nbsp; |&nbsp;</b>
             <font style="font-family:Arial;font-size:18;">  '.$articlenav.'</font>
             '.$this->objWashout->parseText($data['maintext']).'

            </ul>
            <br/>
              ';

        return $content;
    }

}

?>
