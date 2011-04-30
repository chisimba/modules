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


    /**method to get students results
     * @access public
     * @param  string $std_regno  student registration no
     * @param  integer $year_id   academic year_id
     * @param  integer $examId    examination type_id
     * @param  integer $class_id  student class id
     *
     */
    public function get_student_internal_results($std_regno,$class_id,$year_id,$exam_id){
     //getting result scores details
 $results_sql="SELECT `tbl_student_reg_no`, `score`,`subject_name`
               FROM `tbl_result`,tbl_subjects  
              WHERE tbl_result.`tbl_student_reg_no`='$std_regno' AND tbl_result.`tbl_academic_year_id`='$year_id'
              AND tbl_result.`tbl_exam_id`='$exam_id' AND tbl_subjects.`puid`=tbl_result.`tbl_subjects_id` ";

        ///getting students,class and academic year details
 $student_sql="SELECT `firstname`, `lastname`, `othernames`,`class_name`,`year_name`
               FROM `tbl_student`,tbl_class,tbl_academic_year,tbl_student_class
               WHERE tbl_student.`reg_no`='$std_regno' AND tbl_academic_year.`puid`='$year_id' AND tbl_class.`puid`=tbl_student_class.`tbl_class_id`
              AND tbl_student_class.`tbl_academic_year_id`='$year_id'  ";

   $results=$this->objDb_student->getArray($results_sql);
   ////extracting results data
   foreach ($results as $result_data) {
       ///format output here


   }

   $std_details=$this->objDb_student->getArray($student_sql);
   ///extracting students information
   foreach ($std_details as $std_data) {
       ///format output
   }


    }



    /////end of class
}
?>
