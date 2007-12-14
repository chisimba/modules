<?php

class dbnewsphotos extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_photo');
		$this->objUser = $this->getObject('user', 'security');

    }
    
    public function addPhotoToAlbum($fileid, $albumid)
    {
        return $this->insert(array(
				'fileid'=>$fileid,
				'albumid'=>$albumid,
				'photoorder'=>$this->getLastPhotoOrder($albumid)+1,
				'creatorid' => $this->objUser->userId(),
				'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
			));
    }
    
    private function getLastPhotoOrder($albumid)
    {
        $result = $this->getAll(' WHERE albumid = \''.$albumid.'\' ORDER BY photoorder DESC LIMIT 1');
        
        if (count($result) == 0) {
            return 0;
        } else {
            return $result[0]['photoorder'];
        }
    }
    
    public function removePhotoFromAlbum($fileid, $albumid)
    {
        $results = $this->getAll(' WHERE fileid = \''.$fileid.'\' AND albumid = \''.$albumid.'\'');
        
        if (count($results) > 0) {
            foreach ($results as $result)
            {
                $this->delete('id', $result['id']);
            }
        }
    }
    
    public function getAlbumPhotos($albumid)
    {
        $sql = 'SELECT tbl_news_photo.*, tbl_files.filename FROM tbl_news_photo
        INNER JOIN tbl_files ON (tbl_news_photo.fileid = tbl_files.id)
        WHERE albumid = \''.$albumid.'\' ORDER BY photoorder';
        return $this->getArray($sql);
    }
    
    public function getFirstAlbumPhoto($albumid)
    {
        $sql = 'SELECT tbl_news_photo.*, tbl_files.filename FROM tbl_news_photo
        INNER JOIN tbl_files ON (tbl_news_photo.fileid = tbl_files.id)
        WHERE albumid = \''.$albumid.'\' ORDER BY photoorder LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    public function getAlbumUsedFiles($albumid)
    {
        $files = $this->getAll(' WHERE albumid = \''.$albumid.'\'');
        $results = array();
        
        if (count($files) > 0) {
            foreach ($files as $file)
            {
                $results[] = $file['fileid'];
            }
        }
        
        return $results;
    }
    
    public function updateCaption($id, $caption)
    {
        return $this->update('id', $id, array('caption'=>$caption));
    }
    
    
    public function removeAllAlbumPhotos($albumid)
    {
        return $this->delete('albumid', $albumid);
    }

}
?>