<?php

class dbnewscategories extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_categories');
		$this->loadClass('link', 'htmlelements');
    }
    
    public function addCategory($name)
    {
        $name = trim(stripslashes($name));
        
        if ($name == '') {
            return 'emptystring';
        } else if ($this->categoryExists($name)) {
            return 'categoryexists';
        } else {
            return $this->insert(array('categoryname'=>$name, 'categoryorder'=>($this->getLastOrder()+1)));
        }
    }
    
    public function getCategories($orderBy='categoryorder')
    {
        return $this->getAll(' ORDER BY '.$orderBy);
    }
    
    public function categoryExists($name)
    {
        $count = $this->getRecordCount('WHERE categoryname=\''.$name.'\'');
        
        if ($count == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    private function getLastOrder()
    {
        $result = $this->getAll(' ORDER BY categoryorder DESC LIMIT 1');
        
        if (count($result) == 0) {
            return 0;
        } else {
            return $result[0]['categoryorder'];
        }
    }
	
	public function getCategoriesWithStories($order='categoryorder')
	{
		$sql = 'SELECT tbl_news_categories.id, categoryname FROM tbl_news_categories, tbl_news_stories WHERE (tbl_news_categories.id = tbl_news_stories.storycategory) GROUP BY categoryname ORDER BY '.$order;
		
		return $this->getArray($sql);
	}
	
	public function getCategoriesMenu()
	{
		$results = $this->getCategoriesWithStories();
		
		if (count($results) == 0) {
			return '';
		} else {
			$str = '<div id="ddblueblockmenu"><ul>';
			
			$homeLink = new link ($this->uri(array('action'=>'home')));
			$homeLink->link = 'Home';
			$str .= '<li>'.$homeLink->show().'</li>';
			
			
			foreach ($results as $result)
			{
				$link = new link ($this->uri(array('action'=>'viewcategory', 'id'=>$result['id'])));
				$link->link = $result['categoryname'];
				
				$str .= '<li>'.$link->show().'</li>';
			}
			
			$str .= '</ul></div>';
			
			return $str;
		}
	}
    
    public function getCategoryName($id)
    {
        $category = $this->getRow('id', $id);
        
        return $category['categoryname'];
        
        if (!is_array($category)) {
            return FALSE;
        } else {
            $category['categoryname'];
        }
    }

}
?>