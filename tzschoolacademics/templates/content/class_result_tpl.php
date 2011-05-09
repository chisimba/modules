<?php
/* 
 *
 *   @author charles mhoja
 *   @email charlesmdack@gmail.com
 */
$displayObj=$this->newObject('reportdisplay', 'tzschoolacademics');
if(strcmp($option,'sub_result')==0){

echo $displayObj->generate_class_result($exam_type, $term_id, $year_id, $class_id);
}
else{
echo $displayObj->create_class_result_form();
}

?>
