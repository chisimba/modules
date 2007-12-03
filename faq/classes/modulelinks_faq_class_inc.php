<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


class modulelinks_faq extends object
{

    public function init()
    {
        //$this->loadClass('treenode','tree');
      // $this->_objDBFaqEntries =$this->loadClass('dbfaqentries');
       // $this->_objDBCategories =$this->loadClass('dbfaqcategories');      
 $this->_objDBFaqEntries = & $this->newObject('dbfaqentries','faq');
$this->_objDBCategories = & $this->newObject('dbfaqcategories','faq');
    }
    
    public function show()
    {
        
    }
    
    /**
     * 
     *Method to get a set of links for a context
     *@param string $contextCode
     * @return array
     */
    public function getContextLinks($contextCode)
    {

          $cats = $this->_objDBCategories->getCatId($contextCode);
          $faqs=$this->_objDBFaqEntries->getEntries($contextCode,$cats['id']);
   
          $bigArr = array();
        
          foreach ($faqs as $faq)
          {
                $newArr = array();   
              $newArr['menutext'] = $faq['qn'];
              $newArr['description'] = $faq['categoryid'];
              $newArr['itemid'] = $faq['id'];
              $newArr['moduleid'] = 'faq';
              $newArr['params'] = array('id' => $faq['id'],'action' => 'events');
              $bigArr[] = $newArr;
          }
         
          return $bigArr;
        
         }
    
}

?>
