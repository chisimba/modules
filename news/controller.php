<?php

class news extends controller
{
    
    /**
    *
    *
    */
    public function init()
    {
        $this->objNewsCategories = $this->getObject('dbnewscategories');
        $this->objNewsLocations = $this->getObject('dbnewslocations');
        $this->objNewsStories = $this->getObject('dbnewsstories');
		$this->objKeywords = $this->getObject('dbnewskeywords');
		$this->objTags = $this->getObject('dbnewstags');
    }
    
    private function putLayoutTemplate($action)
    {
        $twoCols = array('admin', 'addcategory', 'managelocations', 'addlocation', 'savelocation', 'viewlocation', 'addstory', 'editstory', 'themecloud', 'tagcloud', 'viewtimeline', 'viewbykeyword', 'viewcategory', 'viewlocation', 'viewlocation', 'viewlocation');
        
        if (in_array($action, $twoCols)) {
            $this->setLayoutTemplate('2collayout.php');
        } else {
            $this->setLayoutTemplate('layout.php');
        }
    }
    
    /**
    *
    *
    */
    public function dispatch($action)
    {
        $this->setVar('pageSuppressXML', TRUE);
		
		$this->putLayoutTemplate($action);
        
        switch($action)
        {
            case 'managecategories':
                return $this->manageCategories();
            case 'addcategory':
                return $this->addCategory();
            case 'managelocations':
                return $this->manageLocations();
            case 'addlocation':
                return $this->addLocation();
            case 'savelocation':
                return $this->saveLocation();
            case 'viewlocation':
                return $this->viewLocation($this->getParam('id'));
			case 'addstory':
				return $this->addStory();
			case 'savestory':
				return $this->saveStory();
			case 'viewstories':
				return $this->viewStories();
			case 'viewstory':
                return $this->viewStory($this->getParam('id'));
            case 'editstory':
                return $this->editStory($this->getParam('id'));
            case 'updatestory':
				return $this->updateStory();
			case 'ajaxkeywords':
				return $this->ajaxKeywords();
			case 'themecloud':
				return $this->themeCloud();
			case 'tagcloud':
				return $this->tagCloud();
			case 'viewtimeline':
				return $this->viewTimeline();
			case 'generatetimeline':
				return $this->generateTimeline();
            case 'viewbykeyword':
                return $this->viewByKeyword($this->getParam('id'));
            case 'generatekeywordtimeline':
                return $this->generateKeywordTimeline($this->getParam('id'));
            case 'viewcategory':
                return $this->viewCategory($this->getParam('id'));
			case 'home':
				return $this->home();
            default:
                return $this->newsHome();
        }
    }
    
    /**
    *
    *
    */
    private function newsHome()
    {
        return 'main.php';
    }
	
	private function home()
	{
		$this->setLayoutTemplate('layout.php');
		
		$topStories = $this->objNewsStories->getTopStoriesFormatted();
		$this->setVarByRef('topStories', $topStories['stories']);
		
		$this->setVarByRef('topStoriesId', $topStories['topstoryids']);
		
		$categories = $this->objNewsCategories->getCategoriesWithStories('categoryname');
        $this->setVarByRef('categories', $categories);
		
		return 'home.php';
	}
    
    /**
    *
    *
    */
    private function manageCategories()
    {
        $categories = $this->objNewsCategories->getCategories();
        $this->setVarByRef('categories', $categories);
        
        return 'managecategories.php';
    }
    
    private function addCategory()
    {
        $result = $this->objNewsCategories->addCategory($this->getParam('category'));
        
        if ($result == 'emptystring' || $result == 'categoryexists') {
            return $this->nextAction('managecategories', array('error'=>$result));
        } else {
            return $this->nextAction('managecategories', array('newrecord'=>$result));
        }
    }
    
    private function manageLocations()
    {
        $tree = $this->objNewsLocations->getLocationsTree('id');
        $this->setVarByRef('tree', $tree);
        
        return 'managelocations.php';
    }
    
    private function addLocation()
    {
        $tree = $this->objNewsLocations->getLocationsTree();
        $this->setVarByRef('tree', $tree);
        
        $this->setVar('mode', 'add');
        
        return 'addeditlocation.php';
    }
    
    private function saveLocation()
    {
        $location = $this->getParam('location');
        $parentLocation = $this->getParam('parentlocation');
        $locationType = $this->getParam('locationtype');
        $locationImage = $this->getParam('imageselect');
        $latitude = $this->getParam('latitude');
        $longitude = $this->getParam('longitude');
        $zoomlevel = $this->getParam('zoomlevel');
        $viewbounds = $this->getParam('viewbounds');
        $currentcenter = $this->getParam('currentcenter');
        
        //echo '<pre>';
        //print_r($_POST);
        // To do, check whether item exists on level - avoid duplication
        
        
        $result = $this->objNewsLocations->addLocation($location, $parentLocation, $locationType, $locationImage, $latitude, $longitude, $zoomlevel, $viewbounds, $currentcenter);
        
        if ($result == 'emptystring' || $result == 'parentdoesnotexist'){
            echo $result; // Fix Up Error Message
        } else {
            return $this->nextAction('viewlocation', array('id'=>$result));
        }
    }
    
    private function viewLocation($id)
    {
        $location = $this->objNewsLocations->getLocation($id);
        
        if ($location == FALSE) {
            return $this->nextAction(NULL, array('error'=>'locationdoesnotexist', 'requestedaction'=>'viewlocation', 'requestedid'=>$id));
        }
        
        $this->setVarByRef('location', $location);
        
        return 'viewlocation.php';
    }
	
	private function addStory()
	{
		$this->setVar('mode', 'add');
		
		$tree = $this->objNewsLocations->getLocationsTree('storylocation');
        $this->setVarByRef('tree', $tree);
		
		$categories = $this->objNewsCategories->getCategories('categoryname');
        $this->setVarByRef('categories', $categories);
		
		return 'addeditstory.php';
	}
	
	private function saveStory()
	{
		
		$storyTitle = $this->getParam('storytitle');
		$storyDate = $this->getParam('storydate');
		$storyCategory = $this->getParam('storycategory');
		$storyLocation = $this->getParam('storylocation');
		$storyText = $this->getParam('storytext');
		$storySource = $this->getParam('storysource');
		$storyImage = $this->getParam('imageselect');
		
		$tags = $this->getParam('storytags');
		$keyTags = array($this->getParam('keytag1'), $this->getParam('keytag2'), $this->getParam('keytag3'));
		
		$storyId = $this->objNewsStories->addStory($storyTitle, $storyDate, $storyCategory, $storyLocation, $storyText, $storySource, $storyImage, $tags, $keyTags);
		
		return $this->nextAction('viewstory', array('id'=>$storyId));
	}
	
	private function viewStory($id)
	{
		// Turn off exist Template
		// Content uses 3 col layout
		$this->setLayoutTemplate(NULL);
		
		$story = $this->objNewsStories->getStory($id);
		
		if ($story == FALSE) {
			return $this->nextAction('home', array('error'=>'nostory'));
		} else {
			$this->setVarByRef('story', $story);
			
			return 'viewstory.php';
		}
	}
    
    private function editStory($id)
    {
        $story = $this->objNewsStories->getStory($id);
		
		if ($story == FALSE) {
			return $this->nextAction('home', array('error'=>'nostory'));
		} else {
			$this->setVar('mode', 'edit');
            
            $this->setVarByRef('story', $story);
            
            $tree = $this->objNewsLocations->getLocationsTree('storylocation');
            $this->setVarByRef('tree', $tree);
            
            $categories = $this->objNewsCategories->getCategories('categoryname');
            $this->setVarByRef('categories', $categories);
            
            $keywords = $this->objKeywords->getStoryKeywords($id);
            $this->setVarByRef('keywords', $keywords);
            
            $tags = $this->objTags->getStoryTags($id);
            $this->setVarByRef('tags', $tags);
			
			return 'addeditstory.php';
		}
    }
    
    private function updateStory()
    {
        $id = $this->getParam('id');
        $storyTitle = $this->getParam('storytitle');
		$storyDate = $this->getParam('storydate');
		$storyCategory = $this->getParam('storycategory');
		$storyLocation = $this->getParam('storylocation');
		$storyText = $this->getParam('storytext');
		$storySource = $this->getParam('storysource');
		$storyImage = $this->getParam('imageselect');
		
		$tags = $this->getParam('storytags');
		$keyTags = array($this->getParam('keytag1'), $this->getParam('keytag2'), $this->getParam('keytag3'));
		
		$result = $this->objNewsStories->updateStory($id, $storyTitle, $storyDate, $storyCategory, $storyLocation, $storyText, $storySource, $storyImage, $tags, $keyTags);
        
        return $this->nextAction('viewstory', array('id'=>$id));
    }
	
	function ajaxKeywords()
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
	
	private function viewStories()
	{
		
	}
	
	private function themeCloud()
	{
		echo $this->objKeywords->getKeywordCloud();
	}
	
	private function tagCloud()
	{
		
	}
	
	private function viewTimeline()
	{
		$int = 'WEEK';
		$fdate = "Jan 1 2007 00:00:00 GMT";
		$timeline = $this->uri(array('action'=>'generatetimeline'));
		$timeline = str_replace('&amp;', '&', $timeline);
		$objIframe = $this->getObject('iframe', 'htmlelements');
    	$objIframe->width = "100%";
    	$objIframe->height="300";
     	$ret = $this->uri(array("mode" => "plain",
	          "action" => "viewtimeline", 
			  "timeLine" => ($timeline),
			  //"timeLine" => urlencode($timeline),
			  "intervalUnit" => $int,
			  "focusDate" => $fdate,
			  "tlHeight" => '300'), "timeline");
    	$objIframe->src=$ret;
        echo $objIframe->show();
	}
	
	private function generateTimeline()
	{
		header('Content-type: text/xml');
		echo $this->objNewsStories->generateTimeline();
	}
    
    private function viewByKeyword($keyword)
    {
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
    
    private function generateKeywordTimeline($keyword)
	{
		header('Content-type: text/xml');
		echo $this->objNewsStories->generateKeywordTimeline($keyword);
	}
    
    private function viewCategory($id)
    {
        $this->setLayoutTemplate('2collayout.php');
        
        $categoryName = $this->objNewsCategories->getCategoryName($id);
        
        if ($categoryName == FALSE) {
            return $this->nextAction('home', array('error'=>'nocategory'));
        }
        
        $this->setVarByRef('id', $id);
        
        $this->setVarByRef('categoryName', $categoryName);
        
        $categoryStories = $this->objNewsStories->getCategoryStories($id);
        $this->setVarByRef('categoryStories', $categoryStories);
        
        return 'viewcategory.php';
    }
}

?>