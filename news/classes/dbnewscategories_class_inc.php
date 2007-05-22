<?php

class dbnewscategories extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_categories');
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
    
    public function getCategories()
    {
        return $this->getAll(' ORDER BY categoryorder');
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

}
?>