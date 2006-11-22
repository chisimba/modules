<?php

class dbpodcast extends dbTable 
{
    
    public function init()
    {
        parent::init('tbl_podcast');
        $this->objFile =& $this->getObject('dbfile', 'filemanager');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    public function addPodcast($fileId)
    {
        $file = $this->objFile->getFileInfo($fileId);
        
        if ($file == FALSE) {
            return 'nofile';
        }
        
        if ($this->podcastUsedAlready($this->objUser->userId(), $fileId) > 0) {
            return 'fileusedalready';
        }
        
        $podcastInfo = array();
        
        $podcastInfo['title'] = isset($file['title']) ? $file['title'] : '[- No Title -]';
        $podcastInfo['description'] = isset($file['description']) ? $file['description'] : '[- No Description -]';

        $podcastInfo['fileid'] = $fileId;
        $podcastInfo['creatorid'] = $this->objUser->userId();
        $podcastInfo['datecreated'] = strftime('%Y-%m-%d %H:%M:%S', mktime());
        
        return $this->insert($podcastInfo);
    }
    
    public function updatePodcast ($id, $title, $description)
    {
        return $this->update('id', $id, 
            array(
                'title' => $title, 
                'description' => $description,
                'modifierid' => $this->objUser->userId(),
                'datemodified' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    
    
    public function getLast10()
    {
        //return $this->getAll('ORDER BY datecreated LIMIT 10');
        $sql = 'SELECT tbl_podcast.*, filename, playtime, filesize, license FROM tbl_podcast 
        LEFT JOIN tbl_files ON (tbl_podcast.fileid = tbl_files.id)
        LEFT JOIN tbl_files_metadata_media ON (tbl_podcast.fileid = tbl_files_metadata_media.fileid)
        ORDER BY tbl_podcast.datecreated DESC LIMIT 5';
        
        return $this->getArray($sql);
    }
    
    public function getUserPodcasts($userId)
    {
        //return $this->getAll('ORDER BY datecreated LIMIT 10');
        $sql = 'SELECT tbl_podcast.*, filename, playtime, filesize, license FROM tbl_podcast 
        LEFT JOIN tbl_files ON (tbl_podcast.fileid = tbl_files.id)
        LEFT JOIN tbl_files_metadata_media ON (tbl_podcast.fileid = tbl_files_metadata_media.fileid)
        WHERE tbl_podcast.creatorid = \''.$userId.'\'
        ORDER BY tbl_podcast.datecreated DESC LIMIT 5';
        
        return $this->getArray($sql);
    }
    
    public function deletePodcast($id, $user)
    {
        $podcast = $this->getRow('id', $id);
        
        if ($podcast == FALSE) {
            return 'norecord';
        } else if ($podcast['creatorid'] != $user) {
            return 'deleteothers';
        } else {
            $this->delete('id', $id);
            return 'podcastdeleted';
        }
    }
    
    public function getPodcast($id)
    {
         $sql = 'SELECT tbl_podcast.*, filename, playtime, filesize, license FROM tbl_podcast 
        LEFT JOIN tbl_files ON (tbl_podcast.fileid = tbl_files.id)
        LEFT JOIN tbl_files_metadata_media ON (tbl_podcast.fileid = tbl_files_metadata_media.fileid)
        WHERE tbl_podcast.id = \''.$id.'\' LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    public function listPodcasters()
    {
        $sql = 'SELECT tbl_users.userid, firstname, surname FROM tbl_podcast 
        LEFT JOIN tbl_users ON (tbl_podcast.creatorid = tbl_users.userid)
        GROUP BY tbl_podcast.creatorid ORDER BY firstname, surname ';
        
        return $this->getArray($sql);
    }
    
    public function getNumFeeds($userId= '')
    {
        if ($userId == '') {
            $where = '';
        } else {
            $where = ' WHERE creatorId=\''.$userId.'\'';
        }
        return $this->getRecordCount($where);
    }
    
    public function podcastUsedAlready($userId, $fileId)
    {
        return $this->getRecordCount(' WHERE creatorid=\''.$userId.'\' AND fileid=\''.$fileId.'\'');
    }
    
    public function getPodcastByFileId($fileId, $userId)
    {
        $result = $this->getAll(' WHERE creatorid=\''.$userId.'\' AND fileid=\''.$fileId.'\' LIMIT 1');
        
        if (count($result) == 0) {
            return FALSE;
        } else {
            return $this->getPodcast($result[0]['id']);
        }
    }

}


?>