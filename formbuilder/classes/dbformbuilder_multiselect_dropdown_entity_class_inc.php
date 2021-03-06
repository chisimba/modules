<?php

//Use Doxygen to extract comments into a technical reference manual.
//Download Doxygen from www.doxygen.org

/* ----------- data class extends dbTable for tbl_formbuilder_multiselect_dropdown_entity------------ */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/*!  \class dbformbuilder_multiselect_dropdown_entity
 *
 *  \brief Class that models the interface for the tbl_formbuilder_multiselect_dropdown_entity database.
 *  \brief It basically is an interface class between the formbuilder module and
 * the tbl_formbuilder_multiselect_dropdown_entity table.
 *  \brief This class provides functions to insert, sort, search, edit and delete entries in the database.
 *  \brief This class is solely used by the form_entity_multiselect_dropdown and form_entity handler class.
 *  \author Salman Noor
 *  \author CNS Intern
 *  \author School of Electrical Engineering, WITS Unversity
 *  \version 1.00
 *  \date    November 3, 2010
 * \warning This class's parent is dbTable which is a chisimba core class.
 * \warning If the dbTable class is altered in the future. This class may not work.
 * \warning Apart from normal PHP. This class uses the mysql language to provide
 * the actual functionality. This language is encapsulated with in "double quotes" in a string format.
 * \note Multi-Selectable Drop Down is abbreviated to "msdd".
 */
class dbformbuilder_multiselect_dropdown_entity extends dbTable {

    /*!
     * \brief Constructor method to define the table
     */
    function init() {
        parent::init('tbl_formbuilder_multiselect_dropdown_entity');
    }

    /*!
     * \brief Return all records in this table
     * \return A mult-dimensional array with all the entries
     */
    function listAll() {
        return $this->getAll();
    }

    /*!
     * \brief This member function returns a single record with a certain id
     * \param id A string.
     * \return An array with a single value
     */
    function listSingle($id) {
        return $this->getAll("WHERE id='" . $id . "'");
    }

    /*!
     * \brief This member function returns a single record with a particular
     * multi-Selectable Drop Down (msdd) name
     * \param msddName A string.
     * \return An array with all the msdd parameters that have a name supplied by the argument
     */
    function listMultiSelectDropdownParameters($formNumber,$msddName) {
        $sql = "select * from tbl_formbuilder_multiselect_dropdown_entity where formnumber like '".$formNumber."' and multiselectdropdownname like '" . $msddName . "'";
        return $this->getArray($sql);
    }

    /*!
     * \brief This member function checks for duplicate msdd form elements
     * \param msddName A string which defines the form element indentifier.
     * \param label A string. This is the actual name of the msdd.
     * \return An boolean value depending on the success or failiure.
     */
    function checkDuplicateMultiSelectDropdownEntry($formNumber,$msddName, $msddValue) {

///Get entries where the msdd form name and the msdd name are like the search
/// parameters.
        $sql = "where formnumber like '".$formNumber."' and multiselectdropdownname like '" . $msddName . "' and msddoptionvalue like '" . $msddValue . "'";

///Return the number of entries. Note that is function in part of the parent class dbTable.
        $numberofDuplicates = $this->getRecordCount($sql);
        if ($numberofDuplicates < 1) {
            return true;
        } else {
            return FALSE;
        }
///Check whether there are multiple entries.
    }

    /*!
     * \brief Insert a record
     * \param  msddName A string. This is form element identifier and the drop down
     * name.
     * \param msddLabel A string. This is the label for the drop down option
     * \param msddValue A string. This is the actual value that gets stored when
     * the user selects this option and submits a form.
     * \param defaultValue A boolean To determine when an option is selected by
     * default.
     * \param msddsize An integer to specify how many options are visible inside
     * the msdd.
     * \param formElementLabel A string that is the label text for the form element.
     * \param labelLayout A string either "top", "bottom","left", "right".
     * \return A newly creating random id that gets saved with the new entry.
     */
    function insertSingle($formNumber,$msddName, $msddLabel, $msddValue, $defaultValue, $msddsize, $formElementLabel, $labelLayout) {
        $id = $this->insert(array(
                    'formnumber' => $formNumber,
                    'multiselectdropdownname' => $msddName,
                    'msddoptionlabel' => $msddLabel,
                    'msddoptionvalue' => $msddValue,
                    'defaultvalue' => $defaultValue,
                    'msddsize' => $msddsize,
                    'label' => $formElementLabel,
                    'labelorientation' => $labelLayout
                ));
        return $id;
    }
    
    function checkIfOptionExists($id){
           $sql = "where id like '".$id."'";

///Return the number of entries. Note that is function in part of the parent class dbTable.
        $numberofDuplicates = $this->getRecordCount($sql);
        if ($numberofDuplicates < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function updateSingle($optionID,$formNumber,$msddName, $msddLabel, $msddValue, $defaultValue,$msddsize, $label, $labelOrientation) {
        $UpdateSQL = "UPDATE tbl_formbuilder_multiselect_dropdown_entity
        SET msddoptionlabel='".$msddLabel."', msddoptionvalue='".$msddValue."', defaultvalue='".$defaultValue."', msddsize='".$msddsize."', label='".$label."', labelorientation='".$labelOrientation."' WHERE multiselectdropdownname='".$msddName."' and formnumber='".$formNumber."' and id='".$optionID."'";
        $this->_execute($UpdateSQL);
    }

    function updateMetaData($formNumber,$formElementName,$formElementLabel,$formElementLabelLayout,$formElementSize){
        $UpdateSQL = "UPDATE tbl_formbuilder_multiselect_dropdown_entity
        SET label='".$formElementLabel."', labelorientation='".$formElementLabelLayout."', msddsize='".$formElementSize."' WHERE multiselectdropdownname='".$formElementName."' and formnumber='".$formNumber."'";
        $this->_execute($UpdateSQL);
    }

    /*!
     * \bried This function updates the menu size of the existing msdd.
     * \param  msddName A string. This is form element identifier and the drop down
     * name.
     * \param msddsize An integer with the desired menu size to update.
     */
    function updateMenuSize($formNumber,$msddName, $msddsize) {
        $UpdateSQL = "UPDATE tbl_formbuilder_multiselect_dropdown_entity
SET msddsize='".$msddsize."' WHERE multiselectdropdownname='".$msddName."' and formnumber='".$formNumber."'";
        $this->_execute($UpdateSQL);
//        $this->update('multiselectdropdownname', $msddName, array(
//            'msddsize' => $msddsize
//        ));
    }

    /*!
     * \brief This member function deletes a msdd element according to its form element
     * indentifier.
     * \param  formElementName A string. This is form element identifier.
     * \return A boolean value whether its was successful or not.
     */
    function deleteFormElement($formNumber,$formElementName) {
        $sql = "where formnumber like '".$formNumber."' and multiselectdropdownname like '" . $formElementName . "'";
        $valueExists = $this->getRecordCount($sql);
        if ($valueExists >= 1) {
            $deleteSQLStatement = "DELETE FROM tbl_formbuilder_multiselect_dropdown_entity WHERE formnumber like '".$formNumber."' AND multiselectdropdownname like '" . $formElementName . "'";
            $this->_execute($deleteSQLStatement);
            //$this->delete("multiselectdropdownname", $formElementName);
            return true;
        } else {
            return false;
        }
    }

    /*!
     * \brief Delete a record according to its id
     * \param id A string.
     * \return Nothing.
     */
    function deleteSingle($id) {
        $this->delete("id", $id);
    }

}

?>
