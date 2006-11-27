<?php

/**
 * Controller for the podcast module
 * @author Tohir Solomons
 */
class podcast extends controller
{
    
    /**
     * Constructor
     *
     */
    public function init()
    {
        $this->objPodcast =& $this->getObject('dbpodcast');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objDateTime =& $this->getObject('datetime', 'utilities');
        
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objConfig =& $this->getObject('altconfig', 'config');
    }
    
    /**
     * Dispatch method to indicate action to be taken
     *
     * @param string $action Action to take
     * @return string Template
     */
    public function dispatch($action)
    {
        $this->setLayoutTemplate('tpl_podcast_layout.php');
        
        switch ($action)
        {
            case 'addpodcast':
                return 'tpl_addeditpodcast.php';
            case 'savenewpodcast':
                return $this->saveNewPodcast();
            case 'confirmadd':
                return $this->confirmAdd();
            case 'confirmsave':
                return $this->confirmSave();
            case 'editpodcast':
                return $this->editPodcast($this->getParam('id'));
            case 'playpodcast':
                return $this->playPodcast($this->getParam('id'));
            case 'deletepodcast':
                return $this->deletePodcast($this->getParam('id'));
            case 'downloadfile':
                return $this->downloadFile($this->getParam('id'));
            case 'byuser':
                return $this->showUserPodcasts($this->getParam('id'));
            case 'rssfeed':
                return $this->showRssFeed($this->getParam('id'));
            default:
                return $this->podcastHome();
        }
        
    }
    
    /**
     * Method to show the podcast home page
     *
     * @return string Template
     */
    private function podcastHome()
    {
        $podcasts = $this->objPodcast->getLast5();
        $this->setVar('podcasts', $podcasts);
        
        return 'tpl_listpodcasts.php';
    }
    
    /**
     * Method to show the podcasts of a particular user
     *
     * @param string $id user Id
     * @return string Template
     */
    private function showUserPodcasts($id='')
    {
        if ($id == '') {
            $id = $this->objUser->userId();
        }
        
        $this->setVar('id', $id);
        
        $podcasts = $this->objPodcast->getUserPodcasts($id);
        $this->setVar('podcasts', $podcasts);
        
        return 'tpl_listpodcasts.php';
    }
    
    /**
     * Method to add a new podcast
     */
    private function saveNewPodCast()
    {
        if ($this->getParam('podcast') == '') {
            return $this->nextAction(NULL, array('error'=>'nofileselected'));
        }
        
        $result = $this->objPodcast->addPodcast($this->getParam('podcast'));
        
        if ($result == 'nofile') {
            return $this->nextAction(NULL, array('error'=>'podcastcouldnotbeadded'));
        } else if ($result == 'fileusedalready') {
            return $this->nextAction('confirmadd', array('fileid'=>$this->getParam('podcast')));
        } else {
            return $this->nextAction('confirmadd', array('id'=>$result));
        }
    }
    
    /**
     * Method to confirm podcast, after user has checked details
     */
    private function confirmAdd()
    {
        $id = $this->getParam('id', 'noid');
        $fileid = $this->getParam('fileid', 'noid');
        
        if ($id != 'noid') {
            $podcast = $this->objPodcast->getPodcast($id);
            
            if ($podcast == FALSE) {
                return $this->nextAction('byuser');
            }
            
            if ($podcast['creatorid'] != $this->objUser->userId()) {
                return $this->nextAction('byuser', array('message'=>'notyourpodcast'));
            }
            $this->setVarByRef('podcast', $podcast);
            $this->setVar('mode', 'confirm');
            return 'tpl_confirmadd.php';
        }
        
        if ($fileid != 'noid') {
            
            $podcast = $this->objPodcast->getPodcastByFileId($fileid, $this->objUser->userId());
            
            if ($podcast == FALSE) {
                return $this->nextAction(NULL);
            } else {
                $this->setVarByRef('podcast', $podcast);
                $this->setVar('mode', 'alreadyused');
                return 'tpl_confirmadd.php';    
            }
        }
        
        return $this->nextAction(NULL);
        
    }
    
    /**
     * Method to update a podcast
     */
    private function confirmSave()
    {
        $id = $this->getParam('id');
        $title = $this->getParam('title');
        $description = $this->getParam('description');
        $process = $this->getParam('process');
        
        if ($id == '') {
            return $this->nextAction(NULL);
        } else {
            $podcast = $this->objPodcast->getPodcast($id);
            
            if ($podcast == FALSE) {
                return $this->nextAction('byuser');
            }
            
            if ($podcast['creatorid'] != $this->objUser->userId()) {
                return $this->nextAction('byuser', array('message'=>'notyourpodcast'));
            }
            
            $this->objPodcast->updatePodcast ($id, $title, $description);
            
            return $this->nextAction('byuser', array('message'=>$process, 'podcast'=>$id));
        }
    }
    
    /**
     * Method to edit a podcast
     *
     * @param string $id Record Id of the Podcast
     */
    private function editPodcast($id)
    {
        if ($id == '') {
            return $this->nextAction(NULL);
        } else {
            $podcast = $this->objPodcast->getPodcast($id);
            
            if ($podcast == FALSE) {
                return $this->nextAction('byuser');
            }
            
            if ($podcast['creatorid'] != $this->objUser->userId()) {
                return $this->nextAction('byuser', array('message'=>'notyourpodcast'));
            }
            
            $this->setVarByRef('podcast', $podcast);
            $this->setVar('mode', 'editpodcast');
            return 'tpl_confirmadd.php';  
        }
    }
    
    /**
     * Method to delete a podcast
     *
     * @param string $id Record id of the podcast
     */
    private function deletePodcast($id)
    {
        if ($id == '') {
            return $this->nextAction(NULL);
        }
        
        $result = $this->objPodcast->deletePodcast($id, $this->objUser->userId());
        
        return $this->nextAction('byuser', array('message'=>$result));
    }
    
    /**
     * Method to download a podcast
     *
     * @param string $id Record Id of the podcast
     */
    private function downloadFile($id)
    {
        if ($id == '') {
            return $this->nextAction(NULL, array('message'=>'nopodcastprovided'));
        }
        
        $podcast = $this->objPodcast->getPodcast($id);
        
        if ($podcast == FALSE) {
            return $this->nextAction(NULL, array('message'=>'podcastdoesnotexist'));
        } else {
            return $this->nextAction('file', array('id'=>$podcast['fileid'], 'filename' => $podcast['filename']), 'filemanager');
        }
    }
    
    /**
     * Method to show the RSS Feed
     *
     * @param string $id User Id of the Feed
     */
    private function showRssFeed($id='')
    {
        $rssFeed = $this->getObject('itunesrssgenerator');
        
        if ($id == '') {
            $podcasts = $this->objPodcast->getLast10();
            $rssFeed->title = $this->objLanguage->languageText('mod_podcast_latestpodcastson', 'podcast').' '.$this->objConfig->getinstitutionName();
            $rssFeed->rssfeedlink = $this->uri(array('action'=>'rssfeed'));
            $rssFeed->description = $this->objLanguage->languageText('mod_podcast_latestpodcastdescription', 'podcast').' '.$this->objConfig->getinstitutionName().' - '.$this->objConfig->getsiteRoot();
            $rssFeed->author = $this->objConfig->getItem('KEWL_SYSTEM_OWNER');
            $rssFeed->email = $this->objConfig->getsiteEmail();
        } else {
            $podcasts = $this->objPodcast->getUserPodcasts($id);
            $rssFeed->title = $this->objUser->fullname($id);
            $rssFeed->rssfeedlink = $this->uri(array('action'=>'rssfeed', 'id'=>$id));
            $rssFeed->description = $this->objLanguage->languageText('mod_podcast_latestpodcastsuploadedbyuser', 'podcast').' '.$this->objUser->fullname($id);
            $rssFeed->author = $this->objUser->email($id);
            $rssFeed->email = $this->objUser->fullname($id);
        }
        
        foreach ($podcasts as $podcast)
        {
            $link = $this->uri(array('action'=>'downloadfile', 'id'=>$podcast['id']));
            $rssFeed->addItem(htmlentities($podcast['title']), $link, htmlentities($podcast['description']), $podcast['datecreated'], $this->objUser->fullname($podcast['creatorid']), 'audio/mpeg', $podcast['filesize'], $podcast['playtime']);
        }
        
        $this->setVarByRef('feed', $rssFeed->show());
        
        $this->setPageTemplate(NULL);
        $this->setLayoutTemplate(NULL);
        
        return 'tbl_podcastfeed.php';
    }
    
    /**
     * Method to list to a podcast online
     *
     * @param string $id Record Id of the podcast
     */
    private function playPodcast($id)
    {
        $podcast = $this->objPodcast->getPodcast($id);
        
        $objFile = $this->getObject('dbfile', 'filemanager');
        
        if ($podcast == FALSE) {
            $this->setVar('content', '&nbsp;');
            $this->appendArrayVar('bodyOnLoad', 'window.close();');
        } else {
            $objSoundPlayer = $this->getObject('buildplayer', 'soundplayer');
            $objSoundPlayer->setSoundFile(str_replace('&', '&amp;', $this->objConfig->getsiteRoot().$objFile->getFilePath($podcast['fileid'])));
            $this->setVarByRef('content', $objSoundPlayer->show());
        }
        
        $this->setVar('pageSuppressContainer', TRUE);
        $this->setVar('suppressFooter', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setLayoutTemplate(NULL);
        
        return 'tpl_listenonline.php';
    }
}
?>