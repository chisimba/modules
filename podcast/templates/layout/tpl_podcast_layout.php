<?php



// Create Side Bar Navigation
$objSideBar = $this->newObject('sidebar', 'navigation');

$menuItems = array();

$podcastHome = array('text'=>$this->objLanguage->languageText('mod_podcast_podcasthome', 'podcast'), 'uri'=>$this->uri(NULL));
$menuItems[] = $podcastHome;

$myPodcast = array('text'=>$this->objLanguage->languageText('mod_podcast_mypodcasts', 'podcast'), 'uri'=>$this->uri(array('action'=>'byuser')));
$menuItems[] = $myPodcast;

$addPodcast = array('text'=>$this->objLanguage->languageText('mod_podcast_addpodcast', 'podcast'), 'uri'=>$this->uri(array('action'=>'addpodcast')));

$menuItems[] = $addPodcast;

// End Side Bar Navigation

//$sidebar = '<br /><p align="center">'.$this->objUser->getUserImage().'</p>';
$sidebar = $objSideBar->show($menuItems);

$podcasters = $this->objPodcast->listPodcasters();
$podcastCourses = $this->objPodcast->listPodcastCourses();

if (count($podcasters) > 0) {
    $this->loadClass('form', 'htmlelements');
    $this->loadClass('button', 'htmlelements');
    $this->loadClass('dropdown', 'htmlelements');
    $this->loadClass('hiddeninput', 'htmlelements');
    $this->loadClass('label', 'htmlelements');
    
    $form = new form ('viewbyuser', $this->uri(array('action' => 'byuser')));
    $form->method = 'get';
    $module = new hiddeninput('module', 'podcast');
    $form->addToForm($module->show());
    
    $action = new hiddeninput('action', 'byuser');
    $form->addToForm($action->show());
    
    $dropdown = new dropdown('id');
    
    foreach ($podcasters as $podcaster)
    {
        if (is_null($podcaster['firstname']))
        {
            $dropdown->addOption($podcaster['userid'], $podcaster['userid']);
        } else {
            $dropdown->addOption($podcaster['userid'], $podcaster['firstname'].' '.$podcaster['surname']);
        }
    }
    
    if ($this->getParam('action') == 'byuser') {
	$username = $this->objUser->userName();
 	
        $dropdown->setSelected($this->getParam($this->objUser->getUserId($username)));
    }
    
    $label = new label ($this->objLanguage->languageText('mod_podcast_viewbypodcaster', 'podcast'), 'input_id');
    
    $button = new button(NULL, $this->objLanguage->languageText('mod_podcast_viewpodcasts', 'podcast'));
    $button->setToSubmit();
    
    $objFeatureBox = $this->newObject('featurebox', 'navigation');
    
    $form->addToForm($dropdown->show().'<br /><br />'.$button->show());
    
    
    $sidebar .= '<br />'.$objFeatureBox->show($label->show(), $form->show());
    
    $objIcon = $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('rss');
    
    
    if ($this->getParam('action') == 'byuser') {
        $numFeeds = $this->objPodcast->getNumFeeds($this->getParam('id', $this->objUser->userId()));
        
        $content = $this->objLanguage->languageText('mod_podcast_rssfeedbyuser', 'podcast').': '.$this->objUser->fullname($this->getParam('id', $this->objUser->userId()));
        $link = new link($this->uri(array('action'=>'rssfeed', 'id'=>$this->getParam('id', $this->objUser->userId()))));
        $link->link = $objIcon->show();
        
        $content .= '<p align="center"><br />'.$link->show().'</p>';
        
        
    } else {
        $numFeeds = $this->objPodcast->getNumFeeds($this->getParam('id', $this->objUser->userId()));
        $content = $this->objLanguage->languageText('mod_podcast_latestpodcastfeeds', 'podcast');
        
        $link = new link($this->uri(array('action'=>'rssfeed')));
        $link->link = $objIcon->show();
        
        $content .= '<p align="center"><br />'.$link->show().'</p>';
    }

if (count($podcastCourses) > 0) {
    $this->loadClass('form', 'htmlelements');
    $this->loadClass('button', 'htmlelements');
    $this->loadClass('dropdown', 'htmlelements');
    $this->loadClass('hiddeninput', 'htmlelements');
    $this->loadClass('label', 'htmlelements');
    
    $form = new form ('viewbycourse', $this->uri(array('action' => 'bycourse')));
    $form->method = 'get';
    $module = new hiddeninput('module', 'podcast');
    $form->addToForm($module->show());
    
    $action = new hiddeninput('action', 'bycourse');
    $form->addToForm($action->show());
    
    $dropdown = new dropdown('contextcode');
    
    foreach ($podcastCourses as $pCourse)
    {
		$dropdown->addOption($pCourse['contextcode'], $pCourse['title']);
		$dropdown->setSelected($this->getParam('contextcode'));
    }
        
    $label = new label ("View by Course", 'input_id');
    
    $button = new button(NULL, $this->objLanguage->languageText('mod_podcast_viewpodcasts', 'podcast'));
    $button->setToSubmit();
    
    $objFeatureBox = $this->newObject('featurebox', 'navigation');
    
    $form->addToForm($dropdown->show().'<br /><br />'.$button->show());
    $sidebar .= '<br />'.$objFeatureBox->show($label->show(), $form->show());

    $objIcon = $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('rss');
    
    
    if ($this->getParam('action') == 'bycourse') {
        $numFeeds = $this->objPodcast->getNumFeedsByCourse($this->getParam('contextcode'));

        $content = $this->objLanguage->languageText('mod_podcast_rssfeedbycourse', 'podcast').': 
        											'.$this->objPodcast->getCourseName($this->getParam('contextcode'));
        $link = new link($this->uri(array('action'=>'rssfeedbycourse', 'contextcode'=>$this->getParam('contextcode'))));
        $link->link = $objIcon->show();

		$content .= '<p align="center"><br />'.$link->show().'</p>';
	}
}
    
    if ($numFeeds > 0) {
        $sidebar .= '<br />'.$objFeatureBox->show($this->objLanguage->languageText('mod_podcast_podcastrssfeed', 'podcast'), $content);
    } 
    
    
}

$cssLayout = $this->newObject('csslayout', 'htmlelements');

$cssLayout->setLeftColumnContent($sidebar);

// Set the Content of middle column
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>
