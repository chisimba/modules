<?php

class podcast extends controller
{
    
    public function init()
    {
        $this->objPodcast =& $this->getObject('dbpodcast');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objDateTime =& $this->getObject('datetime', 'utilities');
        
        $this->objLanguage =& $this->getObject('language', 'language');
    }
    
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
    
    private function podcastHome()
    {
        $podcasts = $this->objPodcast->getLast10();
        $this->setVar('podcasts', $podcasts);
        
        return 'tpl_listpodcasts.php';
    }
    
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
    
    private function confirmSave()
    {
//        echo '<pre>';
//        print_r($_POST);
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
    
    private function deletePodcast($id)
    {
        if ($id == '') {
            return $this->nextAction(NULL);
        }
        
        $result = $this->objPodcast->deletePodcast($id, $this->objUser->userId());
        
        return $this->nextAction('byuser', array('message'=>$result));
    }
    
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
    
    private function showRssFeed($id='')
    {
        $rssFeed = $this->getObject('itunesrssgenerator');
        
        if ($id == '') {
            $podcasts = $this->objPodcast->getLast10();
            $rssFeed->title = 'Latest Podcasts on [-Config-site-]';
            $rssFeed->rssfeedlink = $this->uri(array('action'=>'rssfeed'));
            $rssFeed->description = 'List of Latest Podcasts uploaded by users on the Chisimba Site at http://dfsdfsd';
            $rssFeed->author = 'The Site';
            $rssFeed->email = 'site@site.com';
        } else {
            $podcasts = $this->objPodcast->getUserPodcasts($id);
            $rssFeed->title = $this->objUser->fullname($id);
            $rssFeed->rssfeedlink = $this->uri(array('action'=>'rssfeed', 'id'=>$id));
            $rssFeed->description = 'List of Latest Podcasts uploaded by '.$this->objUser->fullname($id);
            $rssFeed->author = $this->objUser->email($id);
            $rssFeed->email = $this->objUser->fullname($id);
        }
        
        foreach ($podcasts as $podcast)
        {
            $link = $this->uri(array('action'=>'downloadfile', 'id'=>$podcast['id']));
            $rssFeed->addItem($podcast['title'], $link, $podcast['description'], $podcast['datecreated'], $this->objUser->fullname($podcast['creatorid']), 'audio/mpeg', $podcast['filesize'], $podcast['playtime']);
        }
        
        $this->setVarByRef('feed', $rssFeed->show());
        
        $this->setPageTemplate(NULL);
        $this->setLayoutTemplate(NULL);
        
        return 'tbl_podcastfeed.php';
    }
    
}
?>