<?php

class dbnewsstories extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_stories');
        
        $this->objKeywords = $this->getObject('dbnewskeywords');
        
		$this->objUser = $this->getObject('user', 'security');
		$this->objDateTime = $this->getObject('dateandtime', 'utilities');
		$this->loadClass('link', 'htmlelements');
    }
    
    public function addStory($storyTitle, $storyDate, $storyCategory, $storyLocation, $storyText, $storySource, $storyImage, $tags, $keyTags)
    {
		$storyId = $this->insert(array(
			'storytitle' => stripslashes($storyTitle),
			'storydate' => $storyDate,
			'storycategory' => $storyCategory,
			'storylocation' => $storyLocation,
			'storytext' => stripslashes($storyText),
			'storysource' => stripslashes($storySource),
			'storyimage' => $storyImage,
			'creatorid' => $this->objUser->userId(),
            'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
			));
			
		if ($storyId != FALSE) {
			$this->objKeywords->addStoryKeywords($storyId, $keyTags);
			
			$objTags = $this->getObject('dbnewstags');
			$objTags->addStoryTags($storyId, $tags);
            
            return $storyId;
		} else {
            return FALSE;
        }
	}
    
    public function updateStory($id, $storyTitle, $storyDate, $storyCategory, $storyLocation, $storyText, $storySource, $storyImage, $tags, $keyTags)
    {
		$result = $this->update('id', $id, array(
			'storytitle' => stripslashes($storyTitle),
			'storydate' => $storyDate,
			'storycategory' => $storyCategory,
			'storylocation' => $storyLocation,
			'storytext' => stripslashes($storyText),
			'storysource' => stripslashes($storySource),
			'storyimage' => $storyImage,
			'modifierid' => $this->objUser->userId(),
            'datemodified' => strftime('%Y-%m-%d %H:%M:%S', mktime())
			));
			
		if ($result != FALSE) {
			$this->objKeywords->addStoryKeywords($id, $keyTags);
			
			$objTags = $this->getObject('dbnewstags');
			$objTags->addStoryTags($id, $tags);
            
            return $result;
		} else {
            return FALSE;
        }
	}
	
	public function generateTimeline()
	{
		return $this->generateTimelineCode($this->getAll('ORDER BY storydate'));
	}
	
	private function generateTimelineCode($stories)
	{
		$str = '<data date-time-format="iso8601">';
		
        $objTrimString = $this->getObject('trimstr', 'strings');
        
		if (count($stories) > 0) {
			foreach($stories as $story)
			{
				$str .= '<event start="'.$story['storydate'].'" title="'.$story['storytitle'].'">';//image="'.$image.'" // Re add image
				
				$storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$story['id'])));
				$storyLink->link = 'Read More...';
				$storyLink->target = '_top';
				
				$str .= htmlentities($objTrimString->strTrim(strip_tags($story['storytext']), 150, TRUE) . "<br />" . $storyLink->show());
				$str .= "</event>";
			}
		}
		$str .= "</data>";
		
		return $str;
	}
	
	public function getTopStories($limit=2)
	{
		$sql = 'SELECT tbl_news_stories.*, categoryname, location, filename FROM tbl_news_stories 
LEFT JOIN tbl_news_categories ON (tbl_news_stories.storycategory=tbl_news_categories.id) 
LEFT JOIN tbl_news_locations ON (tbl_news_stories.storylocation=tbl_news_locations.id)
LEFT JOIN tbl_files ON (tbl_news_stories.storyimage=tbl_files.id)

 
ORDER BY storydate DESC, datecreated DESC LIMIT '.$limit;

		return $this->getArray($sql);
	}
	
	public function getStory($id)
	{
		$sql = 'SELECT tbl_news_stories.*, categoryname, location, filename FROM tbl_news_stories 
INNER JOIN tbl_news_categories ON (tbl_news_stories.storycategory=tbl_news_categories.id) 
LEFT JOIN tbl_news_locations ON (tbl_news_stories.storylocation=tbl_news_locations.id)
LEFT JOIN tbl_files ON (tbl_news_stories.storyimage=tbl_files.id)
WHERE tbl_news_stories.id = \''.$id.'\'';
		
		$results = $this->getArray($sql);
		
		if (count($results) == 0) {
			return FALSE;
		} else {
			return $results[0];
		}
	}
	
	public function getTopStoriesFormatted()
	{
		$stories = $this->getTopStories();
		
		if (count($stories) == 0) {
			return '';
		} else {
			$output = '';
			
			$objTrimString = $this->getObject('trimstr', 'strings');
			$objThumbnails = $this->getObject('thumbnails', 'filemanager');
			
			$storyIds = array();
			
			foreach ($stories as $story)
			{
				$storyIds[] = $story['id'];
				
				$output .= '<div class="newsstory">';
				
				$storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$story['id'])));
				$storyLink->link = $story['storytitle'];
				
				if ($story['storyimage'] != '') {
					$storyLink->link = '<img class="storyimage" src="'.$objThumbnails->getThumbnail($story['storyimage'], $story['filename']).'" alt="'.$story['storytitle'].'" title="'.$story['storytitle'].'" />';
					
					$output .= $storyLink->show();
				}
				
				$storyLink->link = $story['storytitle'];
				
				$output .= '<h3>'.$storyLink->show().'</h3>';
                
                if ($story['location'] != '') {
                    $locationLink = new link ($this->uri(array('action'=>'viewbylocation', 'id'=>$story['storylocation'])));
                    $locationLink->link = $story['location'];
                    $output .= '[ '.$locationLink->show().'] ';
                }
                
				$output .= $objTrimString->strTrim(strip_tags($story['storytext']), 150, TRUE);
				
				$storyLink->link = 'Read Story';
				$output .= ' ('.$storyLink->show().')';
				
				$output .= '</div><br clear="both" />';
			}
			
			return array('topstoryids'=>$storyIds, 'stories'=>$output);
		}
	}
	
	/**
	* Method to get the list of other stories, that are top stories
	* @param array $storyIds Record Ids of Top Stories to exclude
	* @return array List of Non Top Stories
	*/
	public function getNonTopStories($category, $storyIds, $limit = 5)
	{
		if (!is_array($storyIds)) {
			$storyIds = array($storyIds);
		}
		
		$where = ' WHERE storycategory=\''.$category.'\' ';
		
		if (count($storyIds) > 0) {
			$where .= ' AND (';
			$joiner = '';
			
			foreach ($storyIds as $id)
			{
				$where .= $joiner.' tbl_news_stories.id != \''.$id.'\' ';
				$joiner = ' AND ';
			}
			
			$where .= ')';
		}
		
		$sql = 'SELECT tbl_news_stories.* FROM tbl_news_stories '.$where.' 
ORDER BY storydate DESC LIMIT '.$limit;

		return $this->getArray($sql);
	}
	
	public function getNonTopStoriesFormatted($category, $storyIds)
	{
		$stories = $this->getNonTopStories($category, $storyIds);
		
		if (count($stories) == 0) {
			return '';
		} else {
			$str = '<ul>';
			
			foreach ($stories as $story)
			{
				$storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$story['id'])));
				$storyLink->link = $story['storytitle'];
				$str .= '<li>'.$storyLink->show().'</li>';
			}
			
			$str .= '</ul>';
			
			return $str;
		}
	}
	
	public function getRelatedStories($id, $storyDate)
	{
		$storyKeywords = $this->objKeywords->getStoryKeywords($id);
        
        if (count($storyKeywords) == 0) {
            return $storyKeywords;
        } else {
            $keywordWhere = '(';
            $joiner = '';
            
            foreach ($storyKeywords as $keyword)
            {
                $keywordWhere .= $joiner.' keyword=\''.$keyword.'\'';
                $joiner = ' OR ';
            }
            
            $keywordWhere .= ')';
            
            $sql = 'SELECT tbl_news_stories.id, storytitle, storydate, tbl_news_stories.datecreated FROM tbl_news_stories, tbl_news_keywords WHERE (tbl_news_stories.id = storyid) AND ('.$keywordWhere.') AND (storydate <= \''.$storyDate.'\') AND (tbl_news_stories.id != \''.$id.'\') ORDER BY storydate DESC';
            return $this->getArray($sql);
        }
	}
    
    public function getRelatedStoriesFormatted($id, $storyDate, $dateCreated)
    {
        $stories = $this->getRelatedStories($id, $storyDate);
        
        if (count($stories) == 0) {
            return '';
        } else {
            $str = '<h4>Related Stories</h4><ul>';
            
            $counter = 0;
            foreach ($stories as $story)
            {
                if ($storyDate == $story['storydate']) {
                    if ($this->objDateTime->sqlToUnixTime($story['datecreated']) < $this->objDateTime->sqlToUnixTime($dateCreated)) {
                        $okToDisplay = TRUE;
                    } else {
                        $okToDisplay = FALSE;
                    }
                } else {
                    $okToDisplay = TRUE;
                }
                
                
                if ($okToDisplay) {
                    $counter++;
                    $storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$story['id'])));
                    $storyLink->link = $story['storytitle'];
                    
                    $str .= '<li>'.$storyLink->show().'</li>';
                }
            }
            
            if ($counter == 0) {
                return '';
            } else {
                return $str.'</ul>';
            }
        }
    }
    
    public function getKeywordStories($keyword)
	{
		$sql = 'SELECT tbl_news_stories.*, categoryname, location, filename FROM tbl_news_stories 
INNER JOIN tbl_news_categories ON (tbl_news_stories.storycategory=tbl_news_categories.id) 
INNER JOIN tbl_news_keywords ON (tbl_news_stories.id=tbl_news_keywords.storyid) 
LEFT JOIN tbl_news_locations ON (tbl_news_stories.storylocation=tbl_news_locations.id)
LEFT JOIN tbl_files ON (tbl_news_stories.storyimage=tbl_files.id)
WHERE tbl_news_keywords.keyword = \''.$keyword.'\' ORDER BY storydate DESC';
		
		return $this->getArray($sql);
	}
    
    public function getCategoryStories($category)
	{
		$sql = 'SELECT tbl_news_stories.*, location, filename FROM tbl_news_stories 
LEFT JOIN tbl_news_locations ON (tbl_news_stories.storylocation=tbl_news_locations.id)
LEFT JOIN tbl_files ON (tbl_news_stories.storyimage=tbl_files.id)
WHERE tbl_news_stories.storycategory = \''.$category.'\' ORDER BY storydate DESC';
		
		return $this->getArray($sql);
	}
    
    public function generateKeywordTimeline($keyword)
    {
        $stories = $this->getKeywordStories($keyword);
        
        return $this->generateTimelineCode($stories);
    }

}
?>