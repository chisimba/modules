<?php
/* 
 * class for the report section of SMIS-Academic module
 *
 *  @author charles mhoja , 2011
 *   @email charlesmdack@gmail.com
 *
 */

///getting helper classes
$this->loadClass('dropdown','htmlelements');


class academics_report extends object{
   var $objDb_student;

     public function init()
    {
       $this->objDb_student = $this->newObject('');
    }




///function to get school academic years in a html drop down menu format
    public function get_academic_year(){
        $this->objDb_student->_tableName='tbl_academic_year';

       $academic_years=$this->objDb_student->getAll();

       $ac_year=new dropdown('academic_year');      ///getting dropdown html class
        $output='';
       foreach ($academic_years as $data) {
         $id=$data['pud'];
         $y_name=$dat['year_name'];
         $output .=$ac_year->addOption($value=$id, $label=$y_name);
        }
return $output;
 }


/////function to get all classes of a given school in html drop down menus
    public  function get_classes(){
    $this->objDb_student->_tableName='tbl_classes';
     $classes=$this->objDb_student->getAll();

     $class_dropdown=new dropdown('class');
     $class_output='';
     foreach ($classes as $value) {
       $class_id=$value['puid'];
       $class_name=$value['class_name'];
       $class_output .=$class_dropdown->addOption($value=$class_id, $label=$class_name);
     }
    return $class_dropdown;
    }

 
}
?>
