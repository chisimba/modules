<?php

/**
*
*
*/
class news extends controller
{

    /**
    * Constructor for the Module
    */
    public function init()
    {
        $this->objNewsCategories = $this->getObject('dbnewscategories');
        $this->objNewsMenu = $this->getObject('dbnewsmenu');
        $this->objNewsStories = $this->getObject('dbnewsstories');
        $this->objKeywords = $this->getObject('dbnewskeywords');
        $this->objTags = $this->getObject('dbnewstags');
        $this->objComments = $this->getObject('dbnewscomments');


        $this->objAlbums = $this->getObject('dbnewsalbums');
        $this->objAlbumKeywords = $this->getObject('dbnewsalbumkeywords');
        $this->objPhotos = $this->getObject('dbnewsphotos');

        $this->objPolls = $this->getObject('dbnewspolls');
        $this->objPollOptions = $this->getObject('dbnewspollsoptions');
        $this->objPollVotes = $this->getObject('dbnewspollsvotes');

        $this->objLanguage = $this->getObject('language', 'language');



        $this->loadClass('link', 'htmlelements');

        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    /**
    * Method to turn off login for selected actions
    *
    * @access public
    * @param string $action Action being run
    * @return boolean Whether the action requires the user to be logged in or not
    */
    function requiresLogin($action='home')
    {
        $allowedActions = array(NULL, 'home', 'storyview', 'showmap', 'viewtimeline', 'generatetimeline', 'themecloud', 'photoalbums', 'generatekml', 'viewcategory', 'viewstory', 'viewbykeyword', 'generatekeywordtimeline', 'viewalbum', 'savevote', 'previouspolls', 'pollimage', 'search', 'topstoriesfeed');

        if (in_array($action, $allowedActions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }




    /**
    * Standard Dispatch Function for Controller
    *
    * @access public
    * @param string $action Action being run
    * @return string Filename of template to be displayed
    */
    public function dispatch($action)
    {
        // Method to set the layout template for the given action
        $this->putLayoutTemplate($action);

        /*
        * Convert the action into a method (alternative to
        * using case selections)
        */
        $method = $this->getMethod($action);
        /*
        * Return the template determined by the method resulting
        * from action
        */
        return $this->$method();
    }

    /**
    * Method to set the layout template for a given action
    *
    * @access private
    * @param string $action Action being run
    */
    private function putLayoutTemplate($action)
    {
        $twoCols = array('admin', 'addcategory', 'managecategories', 'managelocations', 'addlocation', 'savelocation', 'viewlocation', 'addstory', 'editstory', 'themecloud', 'tagcloud', 'viewtimeline', 'viewbykeyword', 'viewcategory', 'viewlocation', 'viewlocation', 'viewlocation', 'viewstories', 'showmap', 'editalbumphotos', 'addalbum', 'savealbum', 'photocaptions', 'editalbum', 'viewalbum', 'previouspolls', 'addpoll', 'editmenuitem', 'liststories', 'photoalbums', 'addmenuitem', 'search', 'deletestory', 'deletecategory');

        if (in_array($action, $twoCols)) {
            $this->setLayoutTemplate('2collayout.php');
        } else {
            $this->setLayoutTemplate('layout.php');
        }
    }



    /**
    *
    * Method to convert the action parameter into the name of
    * a method of this class.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return string the name of the method
    *
    */
    function getMethod(& $action)
    {
        if ($this->validAction($action)) {
            return '__'.$action;
        } else {
            return '__home';
        }
    }

    /**
    *
    * Method to check if a given action is a valid method
    * of this class preceded by double underscore (__). If it __action
    * is not a valid method it returns FALSE, if it is a valid method
    * of this class it returns TRUE.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return boolean TRUE|FALSE
    *
    */
    function validAction(& $action)
    {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    // Beginning of Functions Relating to Actions in the Controller //



    /**
    *
    *
    */
    private function __home()
    {
        $this->setLayoutTemplate('NULL');
        
        $topStories = $this->objNewsStories->getTopStoriesFormatted();
        $this->setVarByRef('topStories', $topStories['stories']);

        $this->setVarByRef('topStoriesId', $topStories['topstoryids']);

        $categories = $this->objNewsCategories->getCategoriesWithStories('categoryname');
        $this->setVarByRef('categories', $categories);

        $albums = $this->objAlbums->getAlbums();

        $this->setVarByRef('albums', $albums);

        return 'home.php';
    }

    /**
    *
    *
    */
    private function __login()
    {
        return $this->nextAction('home');
    }

    /**
    *
    *
    */
    private function __managecategories()
    {
        $categories = $this->objNewsCategories->getCategories();
        $this->setVarByRef('categories', $categories);

        $menuItems = $this->objNewsMenu->getMenuItems();
        $this->setVarByRef('menuItems', $menuItems);

        return 'managecategories.php';
    }

    /**
    *
    *
    */
    private function __addmenuitem()
    {
        return 'addmenuitem.php';
    }

    /**
    *
    *
    */
    private function __adddividertomenu()
    {
        $this->objNewsMenu->addDivider();
        return $this->nextAction('managecategories', array('newrecord'=>'divideradded'));
    }

    /**
    *
    *
    */
    private function __addurltomenu()
    {
        $title = $this->getParam('urlmenutitle');
        $url = $this->getParam('websiteurl');

        if ($title == '' || $url == '' || $url == 'http://') {
            return $this->nextAction('managecategories', array('error'=>'notitleandurlgiven', 'title'=>$title, 'url'=>urlencode($url)));
        }

        $objUrl = $this->getObject('url', 'strings');
        if (!$objUrl->isValidFormedUrl($url)){
            return $this->nextAction('managecategories', array('error'=>'notvalidurl', 'title'=>$title, 'url'=>urlencode($url)));
        }

        $id = $this->objNewsMenu->addWebsite($title, $url);

        return $this->nextAction('managecategories', array('newrecord'=>'urladded', 'id'=>$id));
    }


    /**
    *
    *
    */
    private function __addtexttomenu()
    {
        $text = $this->getParam('text');
        if (trim($text) == ''){
            return $this->nextAction('managecategories', array('error'=>'notext'));
        }

        $id = $this->objNewsMenu->addText($text);

        return $this->nextAction('managecategories', array('newrecord'=>'textadded', 'id'=>$id));
    }

    /**
    *
    *
    */
    private function __addmoduletomenu()
    {
        $module = $this->getParam('themodule');

        if (trim($module) == ''){
            return $this->nextAction('managecategories', array('error'=>'nomodule'));
        }

        $id = $this->objNewsMenu->addModule($module);

        return $this->nextAction('managecategories', array('newrecord'=>'moduleadded', 'id'=>$id));
    }

    /**
    *
    *
    */
    private function __savebasiccategory()
    {
        $categoryId = $this->objNewsCategories->addBasicCategory($this->getParam('basiccategory'), $this->getParam('basiccategorytype'));

        $id = $this->objNewsMenu->addCategory($categoryId, $this->getParam('basiccategory'));

        return $this->nextAction('managecategories', array('newrecord'=>'categoryadded', 'id'=>$id));
    }

    private function __updatebasiccategory()
    {

        $id = $this->getParam('id');

        $item = $this->objNewsMenu->getItem($id);

        $name = $this->getParam('basiccategory');
        $categoryType = $this->getParam('basiccategorytype');

        $this->objNewsMenu->updateCategory($id, $name);

        $function = 'updateBasicCategory_'.$categoryType;
        $this->objNewsCategories->$function($item['itemvalue'], $name);

        $category = $this->objNewsCategories->getCategory($item['itemvalue']);

        if ($category != FALSE) {
            $this->objNewsStories->serializeStoryOrder($item['itemvalue'], str_replace('_', ' ', $category['itemsorder']));
        }

        $returnAction = $this->getParam('returnaction');

        if ($returnAction == 'managecategories') {
            return $this->nextAction('managecategories', array('message'=>'categoryupdated'));
        } else {
            return $this->nextAction('viewcategory', array('id'=>$item['itemvalue'], 'message'=>'categoryupdated'));
        }
    }

    /**
    *
    *
    */
    private function __saveadvancecategory()
    {
        //echo '<pre>';
        //print_r($_POST);


        $name             = $this->getParam('advancecategory');
        $categoryType     = $this->getParam('advancecategorytype');
        $itemsOrder       = $this->getParam('advanceorder');
        $defaultSticky    = $this->getParam('defaultsticky');
        $blockOnFrontPage = $this->getParam('blockonfrontpage');
        $showIntroduction = $this->getParam('showintroduction');
        $introduction     = $this->getParam('introduction');
        $numitems         = $this->getParam('numitems');
        $othernum         = $this->getParam('othernum', 10);
        $rssFeeds         = $this->getParam('rssfeeds');
        $socialBookmarks         = $this->getParam('socialbookmarks');

        if ($numitems == 'other') {
            $numitems = $othernum;
        }

        if (!is_numeric($numitems)) {
            $numitems = 10;
        }

        $categoryId = $this->objNewsCategories->addAdvancedCategory($name, $defaultSticky, $itemsOrder, $categoryType, $introduction, $showIntroduction, $blockOnFrontPage, $numitems, $rssFeeds, $socialBookmarks);

        $id = $this->objNewsMenu->addCategory($categoryId, $name);
        
        //echo $categoryId;

        return $this->nextAction('managecategories', array('newrecord'=>'categoryadded', 'id'=>$id));
    }

    /**
    *
    *
    */
    private function __updateadvancecategory()
    {
        //echo '<pre>';
        //print_r($_POST);
        $id = $this->getParam('id');

        $item = $this->objNewsMenu->getItem($id);

        $name             = $this->getParam('advancecategory');
        $categoryType     = $this->getParam('advancecategorytype');
        $itemsOrder       = $this->getParam('advanceorder');
        $defaultSticky    = $this->getParam('defaultsticky');
        $blockOnFrontPage = $this->getParam('blockonfrontpage');
        $showIntroduction = $this->getParam('showintroduction');
        $introduction     = $this->getParam('introduction');
        $numitems         = $this->getParam('numitems');
        $othernum         = $this->getParam('othernum', 10);
		$rssFeeds         = $this->getParam('rssfeeds');
        $socialBookmarks         = $this->getParam('socialbookmarks');

        if ($numitems == 'other') {
            $numitems = $othernum;
        }

        if (!is_numeric($numitems)) {
            $numitems = 10;
        }

        $this->objNewsCategories->updateAdvancedCategory($item['itemvalue'], $name, $defaultSticky, $itemsOrder, $categoryType, $introduction, $showIntroduction, $blockOnFrontPage, $numitems, $rssFeeds, $socialBookmarks);

        $this->objNewsMenu->updateCategory($id, $name);

        $this->objNewsStories->serializeStoryOrder($item['itemvalue'], str_replace('_', ' ', $itemsOrder));

        $returnAction = $this->getParam('returnaction');

        if ($returnAction == 'managecategories') {
            return $this->nextAction('managecategories', array('message'=>'categoryupdated'));
        } else {
            return $this->nextAction('viewcategory', array('id'=>$item['itemvalue'], 'message'=>'categoryupdated'));
        }
    }


    /**
    *
    *
    */
    private function __addstory()
    {
        $this->setVar('mode', 'add');



        $categories = $this->objNewsCategories->getCategories('categoryname');
        $this->setVarByRef('categories', $categories);

        if (count($categories) == 0) {
            return 'nocategories.php';
        } else {
            return 'addeditstory.php';
        }
    }

    /**
    *
    *
    */
    private function __savestory()
    {
        $storyTitle = $this->getParam('storytitle');
        $storyDate = $this->getParam('storydate');
        $storyCategory = $this->getParam('storycategory');
        $storyLocation = $this->getParam('location');
        $storyText = $this->getParam('storytext');
        $storySource = $this->getParam('storysource');
        $storyImage = $this->getParam('imageselect');

        $tags = $this->getParam('storytags');
        $keyTags = array($this->getParam('keytag1'), $this->getParam('keytag2'), $this->getParam('keytag3'));
		
		$publishdate = $this->getParam('publishon');
		
		if ($publishdate == 'now')  {
			$publishdate = strftime('%Y-%m-%d %H:%M:%S', mktime());
		} else {
			$publishdate = $this->getParam('storydatepublish').' '.$this->getParam('time');
		}
		
		$storyId = $this->objNewsStories->addStory($storyTitle, $storyDate, $storyCategory, $storyLocation, $storyText, $storySource, $storyImage, $tags, $keyTags, $publishdate);


        $category = $this->objNewsCategories->getCategory($storyCategory);

        if ($category != FALSE) {
            $this->objNewsStories->serializeStoryOrder($storyCategory, str_replace('_', ' ', $category['itemsorder']));
        }

        return $this->nextAction('viewstory', array('id'=>$storyId));
    }

    /**
    *
    *
    */
    private function __viewstory()
    {
        $id = $this->getParam('id');

        // Turn off exist Template
        // Content uses 3 col layout
        $this->setLayoutTemplate(NULL);

        $story = $this->objNewsStories->getStory($id);

        if ($story == FALSE) {
            return $this->nextAction('home', array('error'=>'nostory'));
        } else {
            $this->setVarByRef('story', $story);

            $category = $this->objNewsCategories->getCategory($story['storycategory']);

            if ($category == FALSE) {

            } else {
				
				// Check whether story is available to be viewed
				if (($story['dateavailable'] > strftime('%Y-%m-%d %H:%M:%S', mktime())) && !$this->isValid('viewfuturestory')) {
					return $this->nextAction('home', array('error'=>'nostory'));
				} else {
					$sectionLayout = $this->getObject('section_'.$category['itemsview']);

					$this->setVarByRef('content', $sectionLayout->renderPage($story, $category));
					$comments = $this->objComments->getStoryComments($id);
					$this->setVarByRef('comments', $comments);
					$this->setVarByRef('story', $story);
					$this->setVarByRef('category', $category);

					$menuId = $this->objNewsMenu->getIdCategoryItem($story['storycategory']);
					$this->setVarByRef('menuId', $menuId);

					return 'viewstory.php';
				}
            }


        }
    }

    /**
    *
    *
    */
    private function __editstory()
    {
        $id = $this->getParam('id');

        $story = $this->objNewsStories->getStory($id);

        if ($story == FALSE) {
            return $this->nextAction('home', array('error'=>'nostory'));
        } else {
            $this->setVar('mode', 'edit');

            $this->setVarByRef('story', $story);



            $categories = $this->objNewsCategories->getCategories('categoryname');
            $this->setVarByRef('categories', $categories);

            $keywords = $this->objKeywords->getStoryKeywords($id);
            $this->setVarByRef('keywords', $keywords);

            $tags = $this->objTags->getStoryTags($id);
            $this->setVarByRef('tags', $tags);

            return 'addeditstory.php';
        }
    }

    /**
    *
    *
    */
    private function __updatestory()
    {
        //echo '<pre>';
        //print_r($_POST);
        
        $id = $this->getParam('id');
        $storyTitle = $this->getParam('storytitle');
        $storyDate = $this->getParam('storydate');
        $storyCategory = $this->getParam('storycategory');
        $storyLocation = $this->getParam('location');
        $storyText = $this->getParam('storytext');
        $storySource = $this->getParam('storysource');
        $storyImage = $this->getParam('imageselect');
		
		
		$publishdate = $this->getParam('publishon');
		
		if ($publishdate == 'now')  {
			$publishdate = strftime('%Y-%m-%d %H:%M:%S', mktime());
		} else {
			$publishdate = $this->getParam('storydatepublish').' '.$this->getParam('time');
		}

        $tags = $this->getParam('storytags');
        $keyTags = array($this->getParam('keytag1'), $this->getParam('keytag2'), $this->getParam('keytag3'));

        $result = $this->objNewsStories->updateStory($id, $storyTitle, $storyDate, $storyCategory, $storyLocation, $storyText, $storySource, $storyImage, $tags, $keyTags, $publishdate);

        $category = $this->objNewsCategories->getCategory($storyCategory);

        if ($category != FALSE) {
            $this->objNewsStories->serializeStoryOrder($storyCategory, str_replace('_', ' ', $category['itemsorder']));
        }

        return $this->nextAction('viewstory', array('id'=>$id));
    }

    /**
    *
    *
    */
    private function __ajaxkeywords()
    {
        $start = $this->getParam($this->getParam('tag'));

        $keywords = $this->objKeywords->getAjaxKeywords($start);

        if (count($keywords) > 0) {
            echo '<ul>';
            $counter = 1;
            foreach ($keywords as $keyword)
            {
                echo '<li id="'.$counter.'">'.$keyword['keyword'].'</li>';
                $counter++;
            }
            echo '</ul>';
        }
    }


    /**
    *
    *
    */
    private function __themecloud()
    {
        return 'themecloud.php';
    }

    /**
    *
    *
    */
    private function __tagcloud()
    {

    }

    /**
    *
    *
    */
    private function __viewtimeline()
    {
        return 'viewtimeline.php';
    }

    /**
    *
    *
    */
    private function __generatetimeline()
    {
        header('Content-type: text/xml');
        echo $this->objNewsStories->generateTimeline();
    }

    /**
    *
    *
    */
    private function __viewbykeyword()
    {
        $keyword = $this->getParam('id');

        $this->setLayoutTemplate('2collayout.php');

        $stories = $this->objNewsStories->getKeywordStories($keyword);

        if (count($stories) == 0) {
            return $this->nextAction('home');
        } else {
            $this->setVarByRef('keyword', $keyword);
            $this->setVarByRef('stories', $stories);

            return 'viewbykeyword.php';
        }
    }

    /**
    *
    *
    */
    private function __generatekeywordtimeline()
    {
        $keyword = $this->getParam('id');

        header('Content-type: text/xml');
        echo $this->objNewsStories->generateKeywordTimeline($keyword);
    }

    /**
    *
    *
    */
    private function __viewcategory()
    {
        $id = $this->getParam('id');

        $this->setLayoutTemplate('2collayout.php');

        $category = $this->objNewsCategories->getCategory($id);

        if ($category == FALSE) {
            $this->objNewsCategories->deleteCategory($id);
            $menuId = $this->objNewsMenu->getIdCategoryItem($id);
            $this->objNewsMenu->deleteCategory($menuId);

            return $this->nextAction(NULL, array('error'=>'categorydoesnotexist'));
        } else {
            $menuId = $this->objNewsMenu->getIdCategoryItem($id);
            $this->setVarByRef('menuId', $menuId);

            $sectionLayout = $this->getObject('section_'.$category['itemsview']);
            $this->setVarByRef('category', $category);
            $this->setVarByRef('content', $sectionLayout->renderSection($category));
            return 'viewcategory.php';
        }
    }




    /**
    *
    *
    */
    private function __showmap()
    {
        $this->objBuildMap = $this->getObject('simplebuildmap', 'simplemap');

        $bodyParams = "onunload=\"GUnload()\"";
        $this->setVarByRef('bodyParams',$bodyParams);

        //Read the API key from sysconfig
        $apiKey = $this->objBuildMap->getApiKey();

        $hScript = "<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key="
           . $apiKey . "\" type=\"text/javascript\"></script>";
        //Add the local script to the page header
        $this->appendArrayVar('headerParams',$hScript);



        return 'showmap.php';
    }

    /**
    *
    *
    */
    private function __generatekml()
    {
        header('Content-type: text/javascript');

        $items = $this->objNewsStories->generateNewsSmap();

        $objTrimString = $this->getObject('trimstr', 'strings');

        $locations = array();
        foreach ($items as $item)
        {
            if (array_key_exists($item['storylocation'], $locations)) {
                $locations[$item['storylocation']] = $locations[$item['storylocation']] + 1;
            } else {
                $locations[$item['storylocation']] = 1;
            }
        }

        foreach ($locations as $location=>$value)
        {
            if ($value == 1) {
                unset($locations[$location]);
            }
        }

        $groupItems = array();

        foreach ($items as $item)
        {
            $latitude = $item['latitude'];
            $longitude = $item['longitude'];

            if (array_key_exists($item['storylocation'], $locations)) {

                $groupItems[$item['storylocation']]['content'][] = array('id'=>$item['storyid'], 'title'=>$item['storytitle']);
                $groupItems[$item['storylocation']]['latitude'] = $latitude;
                $groupItems[$item['storylocation']]['longitude'] = $longitude;
                $groupItems[$item['storylocation']]['locationname'] = $item['location'];

            } else {
                echo 'var point = new GLatLng('.$latitude.','.$longitude.');'."\r\n";
                $content = '<h3>'.$item['location'].': '.$item['storytitle'].'</h3>';

                $content .= $objTrimString->strTrim(($item['storytext']), 150, TRUE);

                $storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$item['storyid'])));
                $storyLink->link = 'Read Story';

                $link = $storyLink->show();
                $link = str_replace('&amp;', '&', $link);

                $content .= ' ('.$link.')';

                $content = '<div style="width:300px;">'.$content.'</div>';

                $content = stripslashes($content);
                $content = str_replace('"', '\"', $content);

                $content = ereg_replace("[\n\r]", " ", $content);
                //$content = ereg_replace("\t\t+", "\n", $content);

                echo 'var marker = createMarker(point,"'.$content.'");'."\r\n";
                echo 'map.addOverlay(marker);'."\r\n";
            }
        }

        if (count($groupItems) > 0) {
            foreach ($groupItems as $group)
            {
                echo 'var point = new GLatLng('.$group['latitude'].', '.$group['longitude'].');'."\r\n";
                $content = '<h3>'.$group['locationname'].'</h3><ul>';
                foreach ($group['content'] as $item)
                {
                    $storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$item['id'])));
                    $storyLink->link = $item['title'];

                    $content .= '<li>'.$storyLink->show().'</li>';
                }

                $content .= '</ul>';

                $content = '<div style="width:300px;">'.$content.'</div>';

                $content = stripslashes($content);
                $content = str_replace('"', '\"', $content);

                echo 'var marker = createMarker(point,"'.$content.'");'."\r\n";
                echo 'map.addOverlay(marker);'."\r\n";
            }

        }
    }

    /**
    *
    *
    */
    private function __topstoriesfeed()
    {
        $topStories = $this->objNewsStories->getTopStories();
        //print_r($topStories);

        $this->objFeedCreator = $this->getObject('feeder', 'feed');
        $objTrimString = $this->getObject('trimstr', 'strings');

        $this->objFeedCreator->setupFeed(TRUE, 'Muslim Views - Top Stories', 'Summary of Top Stories from Muslim Views', 'http://5ive.uwc.ac.za', 'http://127.0.0.1/chi/5ive/app/index.php?module=feed&action=createfeed');

        foreach ($topStories as $story)
        {
            $title = $story['storytitle'];

            $content = $objTrimString->strTrim(($story['storytext']), 150, TRUE);

            $this->objFeedCreator->addItem($title, $this->uri(array('action'=>'viewstory', 'id'=>$story['id'])), $content, 'here', 'Paul');
        }

        echo $this->objFeedCreator->output();
    }

    /**
    *
    *
    */
    private function __savecomment()
    {
        $storyId = $this->getParam('id');
        $name = $this->getParam('name');
        $email = $this->getParam('email');
        $comment = $this->getParam('comments');

        $this->objComments->addComment($storyId, $name, $email, $comment);

        return $this->nextAction('viewstory', array('id'=>$storyId));
    }

    /**
    *
    *
    */
    private function __checklocation()
    {
        $location = $this->getParam('location');

        $objGeonames = $this->getObject('dbgeonames', 'geonames');
        $objCountries = $this->getObject('countries', 'utilities');

        $results = $objGeonames->getLocation($location);

        $this->loadClass('radio', 'htmlelements');

        echo '<strong>Results:</strong> ';

        if (count($results) > 0) {
            echo '<br />';

            $radio = new radio ('location');
            $radio->setBreakSpace('<br />');


            foreach ($results as $result)
            {
                $locationName = $result['name'];

                if ($result['admin1name'] != '') {
                    $locationName .= ', '.$result['admin1name'];
                }

                $locationName .= ', '.$objCountries->getCountryName($result['countrycode']);

                $radio->addOption($result['geonameid'], $locationName);
            }

            $radio->setSelected($results[0]['geonameid']);

            echo $radio->show();
        } else {
            $objCurl = $this->getObject('curl', 'utilities');
            $data = $objCurl->exec('http://ws.geonames.org/search?name_equals='.urlencode($location).'&style=full&maxRows=20&fclass=P');
            $xml = simplexml_load_string($data);

            if (!$xml) {
                echo 'No results for <em>'.$location.'</em><br />';
            } else {
                echo '<p><span class="confirm">'.$this->objLanguage->languageText('mod_geonames_file', 'geonames', 'Results from Webservice').'</span></p>';

                if (isset($xml->geoname)) {
                    $radio = new radio ('location');
                    $radio->setBreakSpace('<br />');

                    foreach ($xml->geoname as $geoname)
                    {
                        $objGeonames->insertFromXML($geoname);



                        $locationName = $geoname->name;

                        if ($geoname->adminName1 != '') {
                            $locationName .= ', '.$geoname->adminName1;
                        }

                        $locationName .= ', '.$geoname->countryName;

                        $radio->addOption($geoname->geonameId.'', $locationName);

                    }

                    echo $radio->show();
                } else {
                echo '<p class="error">'.$this->objLanguage->languageText('mod_geonames_noresultsfromwebservice', 'geonames', 'No Results from the Geonames Webservice').'<br />'.$this->objLanguage->languageText('mod_geonames_possiblespellingerror', 'geonames', 'Possibly a spelling error. Please try again').'</p>';
            }
            }
            //echo 'No results for <em>'.$location.'</em><br />';
        }
        
        $results2 = $objGeonames->getLocationsStartingWith($location);

        if (count($results2) > 0) {
            echo '<br /><strong>Other Possible Results:</strong><br />';

            $divider = '';

            foreach ($results2 as $result)
            {
                echo $divider.'<a href="javascript:ck(\''.addslashes($result['name']).'\')">'.$result['name'].'</a>';
                $divider = ', ';
            }

        }


    }

    /**
    *
    *
    */
    function __movecategoryup()
    {
        $id = $this->getParam('id');

        $result = $this->objNewsMenu->moveItemUp($id);

        $result = $result ? 1: 0;

        return $this->nextAction('managecategories', array('id'=>$id, 'act'=>'movedup', 'result'=>$result));
    }

    /**
    *
    *
    */
    function __movecategorydown()
    {
        $id = $this->getParam('id');

        $result = $this->objNewsMenu->moveItemDown($id);

        $result = $result ? 1: 0;

        return $this->nextAction('managecategories', array('id'=>$id, 'act'=>'movedup', 'result'=>$result));
    }

    /**
    *
    *
    */
    function __photoalbums()
    {
        $albums = $this->objAlbums->getAlbums();

        $this->setVarByRef('albums', $albums);

        return 'photoalbums.php';
    }

    /**
    *
    *
    */
    function __addalbum()
    {
        $this->setVar('mode', 'add');

        return 'addeditalbum.php';
    }

    /**
    *
    *
    */
    function __savealbum()
    {
        $albumname = $this->getParam('albumname');
        $albumdescription = $this->getParam('albumdescription');
        $albumdate = $this->getParam('albumdate');
        $albumlocation = $this->getParam('location');

        $keywords = array($this->getParam('keytag1'), $this->getParam('keytag2'), $this->getParam('keytag'));


        $albumId = $this->objAlbums->addAlbum($albumname, $albumdescription, $albumdate, $albumlocation);
        $this->objAlbumKeywords->addKeywords($albumId, $keywords);

        return $this->nextAction('editalbumphotos', array('id'=>$albumId));
    }

    /**
    *
    *
    */
    function __viewalbum()
    {
        $id = $this->getParam('id');

        if ($id == '') {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $album = $this->objAlbums->getAlbum($id);

        if ($album == FALSE) {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $this->setVarByRef('album', $album);

        $albumPhotos = $this->objPhotos->getAlbumPhotos($id);

        $this->setVarByRef('albumPhotos', $albumPhotos);

        return 'viewalbum.php';
    }

    function __editalbum()
    {
        $id = $this->getParam('id');

        if ($id == '') {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $album = $this->objAlbums->getAlbum($id);

        if ($album == FALSE) {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $this->setVarByRef('album', $album);

        $keywords = $this->objAlbumKeywords->getAlbumKeywords($id);
        $this->setVarByRef('keywords', $keywords);

        $this->setVar('mode', 'edit');

        return 'addeditalbum.php';
    }

    function __updatealbum()
    {
        $id = $this->getParam('id');
        $albumname = $this->getParam('albumname');
        $albumdescription = $this->getParam('albumdescription');
        $albumdate = $this->getParam('albumdate');
        $albumlocation = $this->getParam('location');

        $keywords = array($this->getParam('keytag1'), $this->getParam('keytag2'), $this->getParam('keytag'));

        $albumId = $this->objAlbums->updateAlbum($id, $albumname, $albumdescription, $albumdate, $albumlocation);
        $this->objAlbumKeywords->addKeywords($id, $keywords);

        return $this->nextAction('viewalbum', array('id'=>$id));
    }

    /**
    *
    *
    */
    function __editalbumphotos()
    {
        $id = $this->getParam('id');

        if ($id == '') {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $album = $this->objAlbums->getAlbum($id);

        if ($album == FALSE) {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $this->setVarByRef('album', $album);

        $albumPhotos = $this->objPhotos->getAlbumPhotos($id);

        $this->setVarByRef('albumPhotos', $albumPhotos);

        $objNewsFiles = $this->getObject('dbnewsfilemanagement');
        $folders = $objNewsFiles->getFolders();

        $this->setVarByRef('folders', $folders);

        return 'editalbumphotos.php';
    }

    /**
    *
    *
    */
    function __loadfolderimages()
    {
        $id = $this->getParam('id');
        $albumid = $this->getParam('albumid');

        $objNewsFiles = $this->getObject('dbnewsfilemanagement');
        $files = $objNewsFiles->getFiles($id);

        $this->setVarByRef('files', $files);

        $usedImages = $this->objPhotos->getAlbumUsedFiles($albumid);
        $this->setVarByRef('usedImages', $usedImages);

        $this->setPageTemplate(NULL);
        $this->setLayoutTemplate(NULL);

        return 'loadfolderimages.php';
    }

    /**
    *
    *
    */
    function __addphototoalbum()
    {
        $id = $this->getParam('id');
        $album = $this->getParam('album');

        $this->objPhotos->addPhotoToAlbum($id, $album);
    }

    /**
    *
    *
    */
    function __removephotofromalbum()
    {
        $id = $this->getParam('id');
        $album = $this->getParam('album');

        $this->objPhotos->removePhotoFromAlbum($id, $album);
    }

    /**
    *
    *
    */
    function __photocaptions()
    {
        $id = $this->getParam('id');

        if ($id == '') {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $album = $this->objAlbums->getAlbum($id);

        if ($album == FALSE) {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $this->setVarByRef('album', $album);

        $albumPhotos = $this->objPhotos->getAlbumPhotos($id);

        $this->setVarByRef('albumPhotos', $albumPhotos);

        return 'photocaptions.php';
    }

    /**
    *
    *
    */
    function __savecaptions()
    {
        $id = $this->getParam('id');

        if ($id == '') {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $album = $this->objAlbums->getAlbum($id);

        if ($album == FALSE) {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $albumPhotos = $this->objPhotos->getAlbumPhotos($id);

        if (count($albumPhotos) > 0) {
            foreach($albumPhotos as $photo)
            {
                $caption = $this->getParam($photo['id']);

                if ($caption != '') {
                    $this->objPhotos->updateCaption($photo['id'], $caption);
                }
            }
        }

        return $this->nextAction('viewalbum', array('id'=>$id));
    }

    /**
    *
    *
    */
    function __previouspolls()
    {
        $polls = $this->objPolls->getPolls();

        $this->setVarByRef('polls', $polls);

        return 'previouspolls.php';
    }

    /**
    *
    *
    */
    function __addpoll()
    {
        $this->setVar('mode', 'add');

        return 'addeditpoll.php';
    }

    /**
    *
    *
    */
    function __savenewpoll()
    {
        $question = stripslashes($this->getParam('question'));
        $options = array();
        for ($i=1; $i<=5; $i++)
        {
            $option = stripslashes($this->getParam('option'.$i));

            if ($option != '') {
                $options[] = $option;
            }
        }

        $poll = $this->objPolls->addPoll($question);


        if ($poll == FALSE) {
            return $this->nextAction('managepolls', array('error'=>'couldnotaddpoll'));
        } else {
            $this->objPollOptions->addOptions($poll, $options);
            return $this->nextAction('managepolls', array('id'=>$poll));
        }

    }

    /**
    *
    *
    */
    function __savevote()
    {
        $vote = $this->getParam('vote');

        if ($vote != '') {

            $poll = $this->objPolls->getPollFromOption($vote);

            if ($poll == FALSE) {
                echo '<div class="noRecordsMessage">Poll does not exist</div>';
            } else if ($poll['pollactive'] == 'Y') {
                $this->objPollVotes->saveVote($vote);

                echo '<p><strong>Poll Results:</strong></p>';

                echo $this->objPolls->showPollMiniResults($poll['id']);


            } else {
                echo '<div class="noRecordsMessage">Poll is no longer active</div>';
            }

        } else {
            echo '<div class="noRecordsMessage">No Vote has been cast</div>';

        }



        /*
        $graph = $this->newObject('graph', 'utilities');
        $graph->setup('180px' , '180px');
        $graph->addSimpleData('sadd', 'asfafs', 300);
        $graph->addSimpleData('aadd', 'aafafs', 200);
        $graph->addPlotArea();
        $graph->labelAxes();
        echo $this->objConfig->getcontentBasePath().'asfas.png';
        echo $graph->show($this->objConfig->getcontentBasePath().'asfas.png');
        */
    }

    /**
    *
    *
    */
    function __deletedivider()
    {
        $id = $this->getParam('id', 'nothing');

        $this->objNewsMenu->deleteDivider($id);

        return $this->nextAction('managecategories');
    }

    /**
    *
    *
    */
    function __deletetext()
    {
        $id = $this->getParam('id', 'nothing');

        $this->objNewsMenu->deleteText($id);

        return $this->nextAction('managecategories');
    }

    /**
    *
    *
    */
    function __deleteurl()
    {
        $id = $this->getParam('id', 'nothing');

        $this->objNewsMenu->deleteUrl($id);

        return $this->nextAction('managecategories');
    }

    /**
    *
    *
    */
    function __deletemodule()
    {
        $id = $this->getParam('id', 'nothing');

        $this->objNewsMenu->deleteModule($id);

        return $this->nextAction('managecategories');
    }

    /**
    *
    *
    */
    function __editmenuitem()
    {
        $id = $this->getParam('id', 'nothing');

        $item = $this->objNewsMenu->getItem($id);

        if ($item == FALSE) {
            return $this->nextAction('managecategories', array('error'=>'unknownitem'));
        } else {

            $this->setVarByRef('item', $item);
            $this->setVarByRef('id', $id);

            switch ($item['itemtype'])
            {
                case 'text':
                    return 'managecategories_text.php';
                case 'category':
                    return 'managecategories_category.php';
                case 'divider':
                    return $this->nextAction('managecategories', array('error'=>'cannoteditdivider'));
                default:
                    return $this->nextAction('managecategories', array('error'=>'unknowntype'));
            }

        }
    }

    /**
    *
    *
    */
    function __updatemenu_text()
    {
        $text = $this->getParam('text');
        $id = $this->getParam('id');

        $this->objNewsMenu->updateText($id, $text);

        return $this->nextAction('managecategories');
    }

    /**
    *
    *
    */
    function __liststories()
    {
        $id = $this->getParam('id');

        $category = $this->objNewsCategories->getCategory($id);

        if ($category == FALSE) {

        } else {
            $this->objNewsStories->serializeCategoryOrder($category['id'], str_replace('_', ' ', $category['itemsorder']));

            $this->setVarByRef('category', $category);
            $stories = $this->objNewsStories->getCategoryStories($category['id'], str_replace('_', ' ', $category['itemsorder']), TRUE);

            $this->setVarByRef('stories', $stories);

            $menuId = $this->objNewsMenu->getIdCategoryItem($id);
            $this->setVarByRef('menuId', $menuId);

            $this->setVarByRef('message', $this->______categoryupdatemessages());

            return 'liststories.php';
        }
    }

    private function ______categoryupdatemessages()
    {
        switch ($this->getParam('result'))
        {
            default: $message = ''; break;
            case 'storydeleted':
                $message = '<p><span class="confirm">'.$this->getParam('title').' story has been deleted</span></p>';
                break;
        }

        return $message;
    }

    /**
    *
    *
    */
    function __movepageup()
    {
        $id = $this->getParam('id');

        $story = $this->objNewsStories->getStory($id);

        if ($story == FALSE) {
            return $this->nextAction('home', array('error'=>'nostory'));
        } else {
            $result = $this->objNewsStories->moveItemUp($id);

            $result = $result ? 1: 0;

            return $this->nextAction('liststories', array('id'=>$story['storycategory'], 'act'=>'movedup', 'result'=>$result));
        }
    }

    /**
    *
    *
    */
    function __movepagedown()
    {
        $id = $this->getParam('id');

        $story = $this->objNewsStories->getStory($id);

        if ($story == FALSE) {
            return $this->nextAction('home', array('error'=>'nostory'));
        } else {
            $result = $this->objNewsStories->moveItemDown($id);

            $result = $result ? 1: 0;

            return $this->nextAction('liststories', array('id'=>$story['storycategory'], 'act'=>'moveddown', 'result'=>$result));
        }
    }

    /**
    *
    *
    */
    function __pollimage()
    {
        $id = $this->getParam('id');
        return $this->objPolls->pollImage($id);
    }

    /**
    *
    *
    */
    function __search()
    {
        $query = $this->getParam('q');


        $objLucene =  $this->newObject('searchresults');
        $searchResults = $objLucene->show($query);
        // echo $searchResults; die();
        $searchResults = str_replace('&','&amp;', $searchResults);
        $searchResults = str_replace(urlencode('[HIGHLIGHT]'), urlencode($query), $searchResults);

        $this->setVarByRef('searchResults', $searchResults);
        $this->setVarByRef('searchQuery', $query);


        return 'searchresults.php';
    }

    /**
    *
    *
    */
    function __deletestory()
    {
        $id = $this->getParam('id');

        $story = $this->objNewsStories->getStory($id);

        if ($story == FALSE) {
            return $this->nextAction('home', array('error'=>'nostorytodelete'));
        } else {
            $this->setVarByRef('story', $story);

            $randomNumber = rand(0, 50000);
            $this->setSession('deletestory_'.$story['id'], $randomNumber);
            $this->setVarByRef('deleteValue', $randomNumber);

            return 'deletestory.php';
        }
    }

    /**
    *
    *
    *
    */
    function __deletestoryconfirm()
    {
        $id = $this->getParam('id');
        $deletevalue = $this->getParam('deletevalue');
        $confirm = $this->getParam('confirm');

        if (($id != '')  && ($deletevalue != '')  && ($confirm == 'yes') && ($deletevalue == $this->getSession('deletestory_'.$this->getParam('id')))) {
            $story = $this->objNewsStories->getStory($id);

            if ($story == FALSE) {
                return $this->nextAction(NULL, array('couldnotdeletestorynotexist'));
            } else {
                $this->objComments->deleteStoryComments($id);

                $this->setSession('deletestory_'.$story['storyid'], NULL);

                $this->objNewsStories->deleteStory($id);

                return $this->nextAction('liststories', array('id'=>$story['storycategory'], 'result'=>'storydeleted', 'title'=>$story['storytitle']));
            }
        } else {
            return $this->nextAction('deletestory', array('id'=>$id, 'error'=>'deletenotconfirmed'));
        }

    }

    function __deletecategory()
    {
        $id = $this->getParam('id');

        $item = $this->objNewsMenu->getItem($id);

        if ($item == FALSE) {
            return $this->nextAction('managecategories', array('error'=>'nocategorytodelete'));
        }

        $category = $this->objNewsCategories->getCategory($item['itemvalue']);

        if ($category == FALSE) {
            return $this->nextAction('managecategories', array('error'=>'nocategorytodelete'));
        } else {

            $numItems = $this->objNewsStories->getNumCategoryStories($item['itemvalue']);

            if ($numItems > 0) {
                return $this->nextAction('liststories', array('id'=>$category['id'], 'error'=>'cannotdeletecategorywithstories'));
            }

            $this->setVarByRef('item', $item);
            $this->setVarByRef('category', $category);

            $randomNumber = rand(0, 50000);
            $this->setSession('deletecategory_'.$category['id'], $randomNumber);
            $this->setVarByRef('deleteValue', $randomNumber);

            return 'deletecategory.php';
        }
    }

    function __deletecategoryconfirm()
    {
        $id = $this->getParam('id');
        $deletevalue = $this->getParam('deletevalue');
        $confirm = $this->getParam('confirm');
        $category = $this->getParam('category');

        $item = $this->objNewsMenu->getItem($id);

        if ($item == FALSE) {
            return $this->nextAction('managecategories', array('error'=>'nocategorytodelete'));
        }

        $category = $this->objNewsCategories->getCategory($item['itemvalue']);

        if ($category == FALSE) {
            return $this->nextAction('managecategories', array('error'=>'nocategorytodelete'));
        }

        if (($id != '')  && ($item != '')  && ($item != '') && ($deletevalue != '')  && ($confirm == 'yes') && ($deletevalue == $this->getSession('deletecategory_'.$this->getParam('category')))) {

            $numItems = $this->objNewsStories->getNumCategoryStories($item['itemvalue']);

            if ($numItems > 0) {
                return $this->nextAction('liststories', array('id'=>$category['id'], 'error'=>'cannotdeletecategorywithstories'));
            } else {
                $this->objNewsMenu->deleteCategory($id);
                $this->objNewsCategories->deleteCategory($category['id']);

                return $this->nextAction('managecategories', array('result'=>'categorydeleted', 'title'=>$category['categoryname']));
            }



        } else {
            return $this->nextAction('deletecategory', array('id'=>$id, 'error'=>'deletenotconfirmed'));
        }
    }


    function __deletealbum()
    {
        $id = $this->getParam('id');

        if ($id == '') {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $album = $this->objAlbums->getAlbum($id);

        if ($album == FALSE) {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }

        $this->setVarByRef('album', $album);

        $this->setVarByRef('id', $id);


        $random = rand(1, 20000);
        $this->setSession('delete_'.$id, $random);
        $this->setVarByRef('random', $random);

        return 'deletealbum.php';
    }

    function __deletealbumconfirm()
    {

        $id = $this->getParam('id');
        $confirm = $this->getParam('confirm');
        $random = $this->getParam('random');

        if ($confirm == 'yes') {
            $session = $this->getSession('delete_'.$id, rand(30000, 40000));

            if ($session == $random) {
                $album = $this->objAlbums->getAlbum($id);

                if ($album == FALSE) {
                    return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
                }

                $result = $this->objAlbums->deleteAlbum($id);

                return $this->nextAction('photoalbums', array('result'=>'albumdeleted', 'title'=>$album['albumname']));

            } else {
                return $this->nextAction('deletealbum', array('id'=>$id, 'error'=>'deleteattemptfailed'));
            }
        } else {
            return $this->nextAction('photoalbums', array('error'=>'unknownalbum', 'id'=>$id));
        }
    }
}

?>