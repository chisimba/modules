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

    /* method to create student result form
     *
     *
     */

    function create_students_result_form() {
        $this->get_html_elements();

        $st_result_form = new form('student_result');
        $st_result_form->action = $this->uri(array('action' => 'StudentResults'), 'tzschoolacademics');  // creating a form action
        ///class drop down
        $class = new dropdown('class');
        $class_label = new label('Class');
        $class_array = $this->objreportDb->get_classes();
        foreach ($class_array as $classdata) {

            $class->addOption($value = $classdata['puid'], $label = $classdata['class_name'] . $classdata['stream']);
        }

        ///academic year dropdown
        $academic_year = new dropdown();
        $academic_year->name = 'year';
        $academic_year_label = new label('Year');
        $academic_year_array = $this->objreportDb->get_academic_year();
        foreach ($academic_year_array as $data) {
            $academic_year->addOption($value = $data['puid'], $label = $data['year_name']);
        }


        //exam type dropdown
        $exam = new dropdown();
        $exam->name = 'exam';
        $exam_label = new label('Exam');
        $exam_array = $this->objreportDb->get_exam_type();
        foreach ($exam_array as $array) {
            $exam->addOption($value = $array['puid'], $label = $array['exam_type']);
        }

        ////term drop down
        $term = new dropdown('term_id');
        $term_label = new label('Term/Semmister');
        $term_array = $this->objreportDb->get_exam_type();
        foreach ($term_array as $array) {
            $term->addOption($value = $array['puid'], $label = $array['exam_type']);
        }


        //text box for student reg.no
        $student_reg = new textinput();
        $student_reg->name = 'st_reg';
        $student_reg_label = new label('Reg#');

        ////setting submit button
        $submit = new button('View');
        $submit->setToSubmit();
        $submit->value = 'View';

        ////adding elements to the form
        $st_result_form->addToForm($class_label->show() . $class->show());
        $st_result_form->addToForm($academic_year_label->show() . $academic_year->show());
        $st_result_form->addToForm($exam_label->show() . $exam->show());
        $st_result_form->addToForm($term_label->show() . $term->show());
        $st_result_form->addToForm($student_reg_label->show() . $student_reg->show());
        $st_result_form->addToForm($submit->show());

        //echo $st_result_form->show();

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
        //table header

        if ($validate_student) {
            $student_info = $this->objreportDb->get_students_information($regno, $year_id);
            $student_result = $this->objreportDb->get_student_marks($regno, $exam, $term, $year_id);

            if ($student_info && $student_result) {
                $data_table->startHeaderRow();
                $data_table->addHeaderCell('Subject');
                $data_table->addHeaderCell('Marks');
                $data_table->addHeaderCell('Remarks');
                $data_table->endHeaderRow();

                foreach ($student_info as $student_data) {
                    $st_regno = $student_data['reg_no'];
                    $stuent_full_name = $student_data['firstname'] . ', ' . $student_data['othernames'] . ' ' . $student_data['lastname'];
                    $class_name = $student_data['class_name'];
                    $year = $student_data['year_name'];
                }

                foreach ($student_result as $result_value) {
                    $marks = $result_value['score'];
                    $subject = $result_value['subject_name'];

                    $data_table->startRow();
                    $data_table->addCell($marks);
                    $data_table->addCell($subject);
                    $data_table->addCell('');
                    $data_table->endRow();
                }
                return $data_table->show();
            }
        } else {
            $data_table->startRow();
            $data_table->addCell('No details found');
            $data_table->endRow();

            return $data_table->show();
        }
    }

    ///end class
}

?>
