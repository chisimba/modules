<?
/*
* Class for manipulating tables
* @author James Scoble
* @version $Id$
* @copyright 2004
* @license GNU GPL
*/

class tableinfo extends dbtable
{
    function init()
    {
        parent::init('tbl_users');
    }
                            
    var $tables; //array

    /**
    * This is a method to return a list of the tables in the database, by using 'show tables' and putting the result in an array.
    * @author James Scoble
    * @returns array $list
    */
    function tablelist()
    {
        $sql='show tables';
        $tbl=$this->getArray($sql);
        $list=array();
        foreach ($tbl as $line)
        {
            foreach ($line as $key=>$value)
            {
                $list[]=$value;
            }
        }
        $this->tables=$list;
        return $list;
    }

    /**
    * This is a method to check if a given table's name is in an array - by default the array used is class variable $tables
    * @author James Scoble
    * @param string $name
    * @param array $list (optional)
    * @returns TRUE or FALSE
    */
    function checktable($name,$list=NULL)
    {
        if ($list==NULL){
            $list=&$this->tables;
        }
        if (in_array($name,$list)){
            return TRUE;
        } else {
            return FALSE;
        }
    }



}
?>
