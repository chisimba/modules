<?php

class dbnewsalbums extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_albums');
		$this->objUser = $this->getObject('user', 'security');
    }
    
    public function addAlbum($name, $description, $date, $locationlocation)
    {
        return $this->insert(array(
				'albumname'=>$name,
				'albumdescription'=>$description, 
				'albumdate'=>$date, 
				'albumlocation'=>$locationlocation, 
				'creatorid' => $this->objUser->userId(),
				'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
			));
    }
    
    public function updateAlbum($id, $name, $description, $date, $locationlocation)
    {
        return $this->update('id', $id, array(
				'albumname'=>$name,
				'albumdescription'=>$description, 
				'albumdate'=>$date, 
				'albumlocation'=>$locationlocation
			));
    }
    
    public function getAlbum($id)
    {
        $sql = 'SELECT tbl_news_albums.*, tbl_geonames.name FROM tbl_news_albums
        LEFT JOIN tbl_geonames ON (tbl_news_albums.albumlocation = tbl_geonames.geonameid)
        WHERE tbl_news_albums.id=\''.$id.'\' LIMIT 1';
        
        $results = $this->getArray($sql);
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    public function getAlbums()
    {
        return $this->getAll(' ORDER BY albumdate DESC');
    }
    
    public function deleteAlbum($id)
    {
        $objAlbumKeyword = $this->getObject('dbnewsalbumkeywords');
        $objAlbumKeyword->deleteAlbumKeywords($id);
        
        $objAlbumPhotos = $this->getObject('dbnewsphotos');
        $objAlbumPhotos->removeAllAlbumPhotos($id);
        
        return $this->delete('id', $id);
    }

}
?>