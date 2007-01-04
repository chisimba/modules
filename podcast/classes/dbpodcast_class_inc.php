<?php
/**
 * Class to Control Functionality around tbl_podcast for Podcast Module
 * @author Tohir Solomons
 */
class dbpodcast extends dbTable 
{
    
    /**
     * Constructor
     *
     */
    public function init()
    {
        parent::init('tbl_podcast');
        $this->objFile =& $this->getObject('dbfile', 'filemanager');
        $this->objUser =& $this->getObject('user', 'security');
    }
    
    /**
     * Method to add a podcast
     *
     * @param string $fileId Record Id of the File from File Manager
     * @return string Result, either being last insert id, or flag for error.
     */
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
        
        $podcastId = $this->insert($podcastInfo);
        
        $objFileRegister =& $this->getObject('registerfileusage', 'filemanager');
        $objFileRegister->registerUse($fileId, 'podcast', 'tbl_podcast', $podcastId, 'fileid');
        
        return $podcastId;
    }
    
    /**
     * Method to update a podcast
     *
     * @param string $id Record Id of the Podcast
     * @param string $title Title of Podcast
     * @param string $description Description of Podcast
     * @return boolean result of update
     */
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
    
    /**
     * Method to get the last 5 podcasts
     *
     * @return array
     */
    public function getLast5()
    {
        $sql = 'SELECT tbl_podcast.*, filename, playtime, filesize, license FROM tbl_podcast 
        LEFT JOIN tbl_files ON (tbl_podcast.fileid = tbl_files.id)
        LEFT JOIN tbl_files_metadata_media ON (tbl_podcast.fileid = tbl_files_metadata_media.fileid)
        ORDER BY tbl_podcast.datecreated DESC LIMIT 5';
        
        return $this->getArray($sql);
    }
    
    /**
     * Method to get the last podcast
     *
     * @return array
     */
    public function getLastPodcast()
    {
        $sql = 'SELECT tbl_podcast.*, filename, playtime, filesize, license FROM tbl_podcast 
        LEFT JOIN tbl_files ON (tbl_podcast.fileid = tbl_files.id)
        LEFT JOIN tbl_files_metadata_media ON (tbl_podcast.fileid = tbl_files_metadata_media.fileid)
        ORDER BY tbl_podcast.datecreated DESC LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results)==0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    /**
     * Method to get the podcasts by a particular user
     *
     * @param string $userId User Id of the User
     * @return array
     */
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
    
    /**
     * Method to delete a podcast
     *
     * @param string $id Record Id of Podcast
     * @param string $user User deleting podcast
     * @return string Flag indicating Result
     */
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
    
    /**
     * Method to get a single podcast
     *
     * @param string $id Record Id of Podcast
     * @return array
     */
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
    
    /**
     * Method to list all the podcasts
     *
     * @return array
     */
    public function listPodcasters()
    {
        $sql = 'SELECT DISTINCT tbl_users.id, userid, firstname, surname FROM tbl_podcast 
        INNER JOIN tbl_users ON (tbl_podcast.creatorid = tbl_users.userid)
        ORDER BY firstname, surname ';
        
        return $this->getArray($sql);
    }
    
    /**
     * Method to get the number of podcasts (optionally by user)
     *
     * @param string $userId User Id
     * @return int
     */
    public function getNumFeeds($userId= '')
    {
        if ($userId == '') {
            $where = '';
        } else {
            $where = ' WHERE creatorId=\''.$userId.'\'';
        }
        return $this->getRecordCount($where);
    }
    
    /**
     * Method to determine whether a podcast has been used already
     *
     * @param string $userId
     * @param string $fileId
     * @return int
     */
    public function podcastUsedAlready($userId, $fileId)
    {
        return $this->getRecordCount(' WHERE creatorid=\''.$userId.'\' AND fileid=\''.$fileId.'\'');
    }
    
    /**
     * Method to get a record by providing the fileid, not record id
     *
     * @param string $fileId File Id as per File Manager
     * @param string $userId User Id
     * @return array|false
     */
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