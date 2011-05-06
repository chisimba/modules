<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
 * Description of reportDisplay class
 * 
 *  @author charles mhoja
 *  @email charlesmhoja@gmail.com
 */
class reportdisplay extends object {

//object of the reportdb class
    public $objreportDb;

    public function init() {
        $this->objreportDb = $this->newObject('dbreports', 'tzschoolacademics');
    }

    function get_html_elements() {
        ///loading all the basic html elements classes
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
    }

    /* method to create a form used to view student result
     *@return form for specifying result types
     */

    function create_students_result_form() {
        $this->get_html_elements();  //getting html helper classes for bulding form

        $htmlTable=new htmlTable();

        $st_result_form = new form('student_result');
        $st_result_form->action = $this->uri(array('action' => 'StudentResults'), 'tzschoolacademics');  // creating a form action
        $st_result_form->setDisplayType(2);
        ///class drop down
        $class = new dropdown('class');
        $class_label = new label('Student Class');
        $class_array = $this->objreportDb->get_classes();
        $labelField='class_name'.'stream';
        foreach ($class_array as $classdata) {
            $class->addOption($value = $classdata['puid'], $label = $classdata['class_name'] . $classdata['stream']);
        }

        ///academic year dropdown
        $academic_year = new dropdown();
        $academic_year->name = 'year';
        $academic_year_label = new label('Academic Year');
        $academic_year_array = $this->objreportDb->get_academic_year();
        $academic_year->addFromDB($academic_year_array,'year_name','puid' );
        
        //exam type dropdown
        $exam = new dropdown();
        $exam->name = 'exam';
        $exam_label = new label('Exam Type');
        $exam_array = $this->objreportDb->get_exam_type();
        $exam->addFromDB($exam_array, 'exam_type', 'puid');
     

        ////term drop down
        $term = new dropdown('term_id');
        $term_label = new label('Term/Semmister');
        $objTerm=$this->newObject('marksdb', 'tzschoolacademics');
        $term_array = $objTerm->load_term ();
        $term->addFromDB($array=$term_array, $labelField='term_name', $valueField='puid');

        //text box for student reg.no
        $student_reg = new textinput();
        $student_reg->name = 'st_reg';
        $student_reg_label = new label('Student Reg#');

        ////setting submit button
        $submit = new button('View');
        $submit->setToSubmit();
        $submit->value = 'View Student Result';

        ///creating form elements to table
        
        $htmlTable->startRow();
        $htmlTable->addCell($class_label->show());
        $htmlTable->addCell($class->show());
        $htmlTable->endRow();

        $htmlTable->startRow();
        $htmlTable->addCell($academic_year_label->show());
        $htmlTable->addCell($academic_year->show());
        $htmlTable->endRow();

        $htmlTable->startRow();
        $htmlTable->addCell($exam_label->show());
        $htmlTable->addCell($exam->show());
        $htmlTable->endRow();

        $htmlTable->startRow();
        $htmlTable->addCell($term_label->show());
        $htmlTable->addCell($term->show());
        $htmlTable->endRow();

        $htmlTable->startRow();
        $htmlTable->addCell($student_reg_label->show());
        $htmlTable->addCell($student_reg->show());
        $htmlTable->endRow();

        //adding elements to the form
        $st_result_form->addToForm($htmlTable->show());
          $st_result_form->addToForm($submit->show());

        return $st_result_form->show();  ///returning the created form
    }

    /*
     * method to generate student the result view
     * @param regno :student registration nuber
     * @param exam : examination type-id
     * @param year_id: acaemic year id
     * @param term academic year term/semmister
     * @param class:
     */

    function generate_student_resut($regno, $exam, $term, $year_id, $class) {
        $validate_student = $this->objreportDb->validate_student($regno, $year_id, $class);

        //initiating the table for carrying and formating output result
        $data_table = $this->newObject('htmltable', 'htmlelements');
        $data_table->width = '80%';
        $data_table->cellPadding='2px';
        //table header

        if ($validate_student) {
            $student_info = $this->objreportDb->get_students_information($regno, $year_id);
            $student_result = $this->objreportDb->get_student_marks($regno, $exam, $term, $year_id);

            if ($student_info && $student_result) {
                 $data_table->startHeaderRow();
                $data_table->addHeaderCell('Subject');
                $data_table->addHeaderCell('Marks');
                 $data_table->addHeaderCell('Grade');
                $data_table->addHeaderCell('Remarks');
                $data_table->endHeaderRow();

                foreach ($student_info as $student_data) {
                    $st_regno = $student_data['reg_no'];
                    $student_full_name = $student_data['firstname'] . ', ' . $student_data['othernames'] . ' ' . $student_data['lastname'];
                    $class_name = $student_data['class_name'];
                    $year = $student_data['year_name'];
                                    
                }
              
                 foreach ($student_result as $result_value) {
                    $marks = $result_value['score'];
                    $subject = $result_value['subject_name'];
                    $exam_type=$result_value['exam_type'];
                    $term_name=$result_value['term_name'];
                    ////getting grade from marks
                    $marks_grade=$this->objreportDb->get_marks_grade($marks, $class);
                     foreach ($marks_grade as  $grade_data) {
                       $grade=$grade_data['grade_name'];
                       $gr_remarks=$grade_data['remarks'];
                      }


                     // echo $marks_grade['grade_name'];
                     //echo $marks_grade['grade_name'].$marks_grade['remarks'];
                     //exit;
                     ////starting datatable row
                    $data_table->startRow();
                    $data_table->addCell($subject);
                    $data_table->addCell($marks);
                    $data_table->addCell($grade);
                    $data_table->addCell($gr_remarks);
                    $data_table->endRow();
                    
                }
                ///heading for student results
                $result_heading="<h4><u>".$class_name." ". $exam_type." Examination Results ".$year."-".$term_name." </u></h4>";
                $result_heading .="<p>Student Name: ".$student_full_name."</br>";
                $result_heading .="Registration #: ".$st_regno."</p><br>";

                return $result_heading.$data_table->show();
            }
        } else {
            $data_table->startRow();
            $data_table->addCell('No details found');
            $data_table->endRow();

            return $data_table->show();
        }
    }

    /*
     * method to generate form_for class result
    */

   public function create_class_result_form(){
       $this->get_html_elements();  ///loading html helper classes

       $table=new htmlTable();

        $form = new form('class_result_form');
        $form->action = $this->uri(array('action' => 'ClassResult'), 'tzschoolacademics');  // creating a form action
        $form->setDisplayType(2);

     ///class drop down
        $class = new dropdown('class');
        $c_label = new label('Student Class');
        $c_array = $this->objreportDb->get_classes();
           foreach ($c_array as $classdata) {
            $class->addOption($value = $classdata['puid'], $label = $classdata['class_name'] . $classdata['stream']);
        }
    //semester_dropdown
      $semester=new dropdown('semester');
      $semester_label=new label('Term/semester');
      $objTerm=$this->newObject('marksdb', 'tzschoolacademics');
      $semesters=$objTerm->load_term();
      $semester->addFromDB($array=$semesters, $labelField='term_name', $valueField='puid');

      //exam type dropdown
      $exam=new dropdown('exam_type');
      $exam_label=new label('Exam Type');
      $exams=$this->objreportDb->get_exam_type();
      $exam->addFromDB($exams,'exam_type', 'puid');

      //academic year dropdown
      $academic_year = new dropdown('academic_year');
      $academic_year_label = new label('Academic Year');
      $academic_year_array = $this->objreportDb->get_academic_year();
      $academic_year->addFromDB($academic_year_array,'year_name','puid' );

      $submit = new button('View');
      $submit->setToSubmit();
      $submit->value = 'View Class Result';

      $table->startRow();
      $table->addCell($c_label->show());
      $table->addCell($class->show());
      $table->endRow();

      $table->startRow();
      $table->addCell($exam_label->show());
      $table->addCell($exam->show());
      $table->endRow();

      $table->startRow();
      $table->addCell($academic_year_label->show());
      $table->addCell($academic_year->show());
      $table->endRow();

      $table->startRow();
      $table->addCell($semester_label->show());
      $table->addCell($semester->show());
      $table->endRow();

      //adding elements to form
      $form->addToForm($table->show());
      $form->addToForm($submit->show());
      return $form->show();  ///returning the form object

   }

     /*
      * method to generate specified results category for a particular class in a give academic year and semester
      * @param class_id   the id of the class
      * @param exam_type  type of examination eg anual, semester
      * @param year_id    academic year id
      * @param term_id    academic year semester/term id
      */

     public function generate_class_result($exam_type, $term_id, $year_id, $class_id){
      if(!empty ($exam_type) && !empty ($term_id) && !empty ($year_id) && !empty ($class_id)){
       $student_in_class=$this->objreportDb->get_class_student($class_id, $year_id);
       if($student_in_class){
           ////getting all subjects i  given class
          $class_subjects=$this->objreportDb->get_class_subject($class_id);
        
         if($class_subjects){
         $dataTable=new htmlTable();
         $dataTable->width='90%';
         $dataTable->startHeaderRow();
         foreach ($class_subjects as $cl_subject) {
             $subj_name=$cl_subject['subject_name'];
            
             $dataTable->addHeaderCell($subj_name);
         }
         $dataTable->endHeaderRow();
          }

          return $dataTable->show();  //under test not yet finished
          exit;
          
          //continue from here  still under construction


          ///extracting all students
          foreach($student_in_class as $students){
            $st_regno=$students['reg_no'];
            ///retrieving student results bases on received $st_regno
         $student_result=$this->objreportDb->get_student_marks($st_regno, $exam_type, $term_id, $year_id);
         foreach($student_result as $results){
          $score=$results['score'];    ///result score
          $subject=$results['subject_name'];  ///subject name
          //format row for each student  here
         }
        ///format total output here


          }
        }
       else{
         $data="<p>No student found in the class in the specified year</p>";
         return $data;
       }
          
      }

     }










     ///end class
}

?>
