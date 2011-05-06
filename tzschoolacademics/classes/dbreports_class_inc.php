<?php

/**
 * Description of reportDbTable_class_inc
 *  The database access class for reports section of the academic module
 * @author charles mhoja
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class dbreports extends dbTable {

    //constructor
    public function init() {
        parent::init('tbl_academic_year');
        parent::init('tbl_class');
        parent::init('tbl_exam');
        parent::init('tbl_class_subjects');
        parent::init('tbl_grade');
        parent::init('tbl_result');
        parent::init('tbl_student');
        parent::init('tbl_student_class');
        parent::init('tbl_subjects');
        parent::init('tbl_term');
    }

    /* method to get school academic years
     * @returns academic_years  assocuative array containg all academic years
     */

    public function get_academic_year() {
        $this->_tableName = 'tbl_academic_year';
        $acadm_years = $this->getAll();
        if ($acadm_years) {

        }
        return $acadm_years;  //associative array contain all the school academic years
    }

    /* methid to get all classes in a given school in an array
     * @return class_list  an associative array contains all the classes in a school
     *
     */

    public function get_classes() {
        $this->_tableName = 'tbl_class';
        $classes = $this->getAll();
        if ($classes) {
            return $classes;   ///associative array containg all the classes
        }
    }

    /*
     * method to get_all types of exams in an array
     * @return exam_type as associative array
     *
     */

    public function get_exam_type() {
        $this->_tableName = 'tbl_exam';
        $exams = $this->getAll();
        if ($exams) {
            return $exams;
        }
    }

    /*
     * method to check if student is registered to a given class in agiven academic year.
     * @param regno  student registration number
     * @param year   academic year
     * @param class  student class
     * return bolean TRUE if student is registered and FALSE if not registered
     */

    function validate_student($regno, $year, $class) {
        $this->_tableName = 'tbl_student_class';
        $filter = "Where tbl_student_reg_no='$regno' AND tbl_class_id='$class' AND tbl_academic_year_id='$year' ";
        $result = $this->getRecordCount($filter = $filter);
        if ($result == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * method to get all basic information of a student in a given academic year eg. student name,class.
     * @param regno:  student registration number
     * @param year_id:   academic year
     * return $student_info:  associative array with student informations in if student information exists
     */

    function get_students_information($regno, $year_id) {

        $sql = "SELECT `reg_no`,`firstname`, `lastname`, `othernames`, `gender`, `birthdate`, `religion`,`class_name`,`stream`,`year_name`
         FROM `tbl_student`,tbl_class,tbl_student_class, tbl_academic_year
         WHERE tbl_student_class.tbl_student_reg_no='$regno' AND tbl_student_class.`tbl_academic_year_id`=$year_id
             AND tbl_class.`puid`=tbl_student_class.`tbl_class_id` AND tbl_student.reg_no=tbl_student_class.tbl_student_reg_no
             AND tbl_academic_year .`puid`=$year_id  AND tbl_class.puid=tbl_student_class.tbl_class_id ";

        $sql_query = $this->query($sql);

        if ($sql_query) {
            return $sql_query;
        } else {
            return FALSE;
        }
    }

    /*
     * method to get students result marks
     * @param regno:  student registration number
     * @param  exam :  type of examination
     * @param term   academic year term/semmister
     * @param year_id:   academic year
     * return subject_marks:  associative array with student informations in if student information exists
     */

    function get_student_marks($regno, $exam, $term, $year_id) {
        $sql = "SELECT `subject_name`,exam_type,`score`,term_name  FROM `tbl_result`,tbl_subjects,tbl_exam,tbl_term
        WHERE tbl_subjects.`puid`=tbl_result.`tbl_subjects_id` AND tbl_term.puid=tbl_result.tbl_term_id 	
        AND tbl_result.tbl_student_reg_no='$regno' AND tbl_result.tbl_exam_id='$exam' AND tbl_result.tbl_academic_year_id='$year_id' 
        AND tbl_result.tbl_term_id='$term' AND tbl_exam.puid=tbl_result.tbl_exam_id ";

        $subject_marks = $this->query($sql);
        if ($subject_marks) {
            return $subject_marks;
        }
    }

    /*
     * method to calculate grade from the suplied student marks
     * @param marks :student marks to be given the grade
     * @param class: Id of the student's class 
     * return marks_grade  :array containing gade name and remarks for the given marks
     */

    function get_marks_grade($marks, $class) {
        if (!empty($marks) && !empty($class)) {
            $class_level = $this->getRow($pk_field = 'puid', $pk_value = $class, $table = 'tbl_class');
            $c_level = $class_level['level'];  ///students class level

            if($c_level) {
                //getting grade name and ist remarks
                $grade_sql = "SELECT grade_name,remarks
                    FROM WHERE  level='$c_level' AND '$marks' IN BETWEEN('min_value','max_value')  ";
                $marks_grade_infomation = $this->query($grade_sql);
               if($marks_grade_infomation){
                   $marks_grade=$marks_grade_infomation;
                 return $marks_grade;
               }
                
            }
        }
    }



    

////end of class
}

?>
