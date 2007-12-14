<?php

class dbnewsalbumkeywords extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_albumkeywords');
		$this->objUser = $this->getObject('user', 'security');
    }
    
    public function addKeywords($albumid, $keywords)
    {
        if (is_array($keywords) && count($keywords) > 0) {
            
            $this->delete('albumid', $albumid);
            
            foreach ($keywords as $keyword)
            {
                if (trim($keyword != '')) {
                    $this->addKeyword($albumid, trim($keyword));
                }
            }
            
        }
    }
    
    
    private function addKeyword($albumid, $keyword)
    {
        return $this->insert(array(
				'albumid'=>$albumid,
				'keyword'=>$keyword, 
			));
    }
    
    public function getAlbumKeywords($albumid)
    {
        return $this->getAll(' WHERE albumid=\''.$albumid.'\'');
    }
    
    public function deleteAlbumKeywords($albumid)
    {
        return $this->delete('albumid', $albumid);
    }

}
?>