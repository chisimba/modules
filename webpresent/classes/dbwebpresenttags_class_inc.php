<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end of security

class dbwebpresenttags extends dbtable
{
    
    public function init()
    {
        parent::init('tbl_webpresent_tags');
    }
    
    public function getTags($fileId)
    {
        return $this->getAll(' WHERE fileid=\''.$fileId.'\'');
    }
    
    public function getAllTags()
    {
        $sql = 'SELECT tag, count( tag ) AS tagcount FROM tbl_webpresent_tags GROUP BY tag ORDER BY tag';
        return $this->getArray($sql);
    }
    
    public function getTagCloud()
    {
        $tags = $this->getAllTags();
        
        if (count($tags) == 0) {
            return '<div class="noRecordsMessage">Tag Cloud Goes Here</div>';
        } else {
            $objTagCloud = $this->newObject('tagcloud', 'utilities');
            
            foreach ($tags as $tag)
            {
                $uri = $this->uri(array('action'=>'tag', 'tag'=>$tag['tag']));
                
                $objTagCloud->addElement($tag['tag'], $uri, $tag['tagcount']*6, strtotime('-1 day'));
            }
            
            return $objTagCloud->biuldAll();
        }
    }
    
    public function addTags($fileId, $tags)
    {
        if (is_array($tags) && count($tags) > 0) {
            
            $this->delete('fileid', $fileId);
            
            foreach ($tags as $tag)
            {
                if (trim($tag != '')) {
                    $this->addTag($fileId, trim($tag));
                }
            }
            
        }
    }
    
    public function getFilesWithTag($tag)
    {
        $sql = 'SELECT tbl_webpresent_files.* FROM tbl_webpresent_files, tbl_webpresent_tags 
        WHERE (tbl_webpresent_tags.fileid = tbl_webpresent_files.id) AND tbl_webpresent_tags.tag LIKE \''.$tag.'\' GROUP BY tbl_webpresent_files.id';
        
        return $this->getArray($sql);
    }
    
    
    private function addTag($fileid, $tag)
    {
        return $this->insert(array(
				'fileid'=> $fileid,
				'tag'=> stripslashes($tag), 
			));
    }
}
?>
