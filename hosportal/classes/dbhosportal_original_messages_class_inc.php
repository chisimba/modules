<?php
/* ----------- data class extends dbTable------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/*!  \class dbhosportal_original_messages
*
*  \brief Class that models the hosportal orginal message database.
*  \brief It basically is an interface class between the hosportal module and the hosportal original message database.
*  \brief This class provides functions to insert, sort, search, edit and delete entries in the database.
*  \brief This class is solely used by the dbhosportal_messages and the view_all_messages class.
*  \author Salman Noor
*  \author MIU Intern
*  \author School of Electrical Engineering, WITS Unversity
*  \version 0.68
*  \date    May 3, 2010
* \warning This class's parent is dbTable which is a chisimba core class.
* \warning If the dbTable class is altered in the future. This class may not work.
* \warning Apart from normal PHP. This class uses the mysql language to provide
* the actual functionality. This language is encapsulated with in "double quotes" in a string format.
*/
class dbhosportal_original_messages extends dbTable {

    /*!
* \brief Private data member of class dbhosportal_original_messages that stores an object of another class.
* \brief This class is composed of one object from the user_module class in the utilities module of chisimba.
* \brief This object provides options to manipulate and utilize user data.
    */
    private $objUser;

    /**
     *\brief Constructor that set up this class.
     * \brief It defines the table tbl_hosportal_messages and creates private
     * data members from other classes for this class to use.
     *
     */
    function init() {
///Define the table tbl_hosportal_original_messages. This can only be done if this class
///is inherited by dbTable.
        parent::init('tbl_hosportal_original_messages');

///Instatiate an object user from the class user_module.
        $this->objUser = $this->getObject('user_module','hosportal');

    }

    /**
     *\brief Memeber function that returns all entries in the tbl_hosportal_original_messages database.
     * \return A array with all the entries stored.
     */
    function listAll() {
///Return an array.
        return $this->getAll();
///Notice that this funcion is part of the dbTable parent class.

    }

    /**
     *\brief Member function to search the entire original message database for a certain comment and return it.
     * \param comments A string. The comment could be any small part of the entire comment.
     * Examples choosing the word "can" in a comment that contains  the word "canister".
     * The modulus signs % allows you to do this.
     * \warning Do NOT pass variable types as parameters that are not specified in this member function.
     * \return A string that contains the entire comment.
     */
    function listComment($comments) {
        return $this->getAll("WHERE commenttxt LIKE '%" . $comments . "%'");
    }

    /**
     *\brief Member function to return a single entry with a specific id.
     * \param id A string. This Id is generated by mysql and in unique for all entries.
     * the Id is assigned when you create an entry in the database.
     * \warning Do NOT pass variable types as parameters that are not specified in this member function.
     * \warning Make sure you pass the correct id for the entry you want.
     * \return A single entry with all its fields from the database.
     */
    function listSingle($id) {
        return $this->getAll("WHERE id='" . $id . "'");
    }

    /**
     *\brief Member function to insert a new entry into the original messages database.
     * \param id A string. The id is not unique but the exact same id for the same
     * stored in the messages database
     * \param title A string. It is the subject matter of the entry.
     * \param comments A string. It is the comment of the entry.
     * \param unreplied A Boolean Value. It is to determine whether the entry has
     * been replied to or not.
     * \param noofreplies A Integer. how many replies this entry has.
     * \warning Do NOT pass variable types as parameters that are not specified in this member function.
     * \return An the same id as was passed in.
     */
    function insertSingle($id,$title,$comments,$unreplied,$noofreplies) {

///Get the user's name that is inserting the entry.
        $userid = $this->objUser->getUserFullName();
///Insert the entry into the database with its relevant fields. If successful,
///store the entries id into a temporary variable. The insert function is
///from the parent class dbTable.
        $id = $this->insert(array(
                'id' => $id,
                'userid' => $userid,
                'title' => $title,
                'commenttxt' => $comments,
                'modified'=> $this->now(),
                'commenttxtshort'=> $comments,
                'unreplied'=> $unreplied,
                'replies' => $noofreplies
        ));

///return the same id as was passed in.
        return $id;
    }

    /**
     *\brief Member function to edit an existing entry in the original messages database.
     * \param id A string. Unique caller id for the exsiting entry.
     * \param title A string. It is the edited or the same subject matter for the exisitng entry.
     * \param comments A string. It is the edited or the same comment for the exisitng entry.
     * \param unreplied A Boolean Value. It is to determine whether the entry has
     * been replied to or not.
     * \param noofreplies A Integer. How many replies this entry has.
     * \warning Do NOT pass variable types as parameters that are not specified in this member function.
     * \warning MAKE SURE you have the correct id as the wrong id will edit the wrong entry.
     * \return The same id for that exisiting entry.
     */
    function updateSingle($id, $title, $comments,$unreplied,$noofreplies) {
///Get the user's name that is editing the exisitng entry.
        $userid = $this->objUser->getUserFullName();
///Update the entry into the database with its relevant fields. If successful,
///store the entries id into a temporary variable. The update function is
///from the parent class dbTable.
        $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );

        $result = $this->update('id',$id,$data);
///return the same id for that exisitng entry. The id does not change.
        return $result;
    }

    /**
     *\brief Member function to delete an existing entry in the original messages database.
     * \param id A string. Unique caller id for the exsiting entry.
     * \warning MAKE SURE you are passing the right id as the wrong id will
     * delete the wrong entry.
     */
    function deleteSingle($id) {
///Delete the entry which that specific id.
        $this->delete("id", $id);
///The delete function is from the parent class dbTable.
    }

    /**
     *\brief Member function to output a specfic group of entries.
     *\brief This function is not used in the module but can be potentially useful in the future.
     */
    function paginateAll($starting_element,$ending_element) {

        $sql =  "select * from (select @rownum:=@rownum+1 rank, p.* from tbl_hosportal_original_messages p, (SELECT @rownum:=0) r limit 1000) as t where rank between $starting_element and $ending_element";
        return $this->getArray($sql);
    }

    /**
     *\brief Member function to get the no of messages that contain the search parameter.
     * \param searchValue A string. The string could be any small part of the entire comment.
     * Examples choosing the word "can" in a comment that contains  the word "canister".
     *\return An integer of the number of hits made with that specific search parameter.
     */
    function getNoOfSearchedOriginalMessages($searchValue) {

///Get entries where the comments or the subject matter or the author is like the search parameter and store
///these entries in a temporary variable.
        $sql=   "where commenttxt like '%".$searchValue."%' || title like '%$searchValue%' || userid like '%$searchValue%'";
///Return the number of entries. Note that is function in part of the parent class dbTable.
        return $this->getRecordCount($sql);

    }
    /**
     *\brief Member function to get all entries that contain the search parameter.
     * \param searchValue A string. The string could be any small part of the entire comment.
     * Examples choosing the word "can" in a comment that contains  the word "canister".
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     *\return An array of paginated searched entries.
     */
    function searchOriginalMessages($searchValue,$number_of_entries_per_page = 5,$starting_element = 0) {
///Select all for the original messages table in which the search parameter is like the comments or subject matter or
///or author and order them by latest modified and paginate them and store the result in a temporary variable sql.
        $sql=   "select * from tbl_hosportal_original_messages where commenttxt like '%".$searchValue."%' || title like '%$searchValue%' || userid like '%$searchValue%' order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Return the array of entries. Note that is function in part of the parent class dbTable.
        return $this->getArray($sql);

    }

    /**
     *\brief Member function to sort all entries by latest modified and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by latest modified.
     */
    function sortByLatestModified($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field modified
///and store the result in a temporary variable. The language MYSQL is used to do this. It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);

    }

    /**
     *\brief Member function to sort all entries by oldest modified and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by oldest modified.
     */
    function sortByOldestModified($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field modified
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by modified asc LIMIT $number_of_entries_per_page OFFSET $starting_element";

///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);
    }

    /**
     *\brief Member function to sort all entries by author A to Z and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by by author A to Z
     */
    function sortByAuthorAtoZ($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field author asc
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by userid asc LIMIT $number_of_entries_per_page OFFSET $starting_element";

///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);
    }

    /**
     *\brief Member function to sort all entries by author Z to A and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by by author Z to A.
     */
    function sortByAuthorZtoA($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field author in desc order
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by userid desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);

    }

    /**
     *\brief Member function to sort all entries by subject matter A to Z and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by by subject matter A to Z.
     */
    function sortBySubjectMatterAtoZ($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field title in asc order
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by title asc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);
    }

    /**
     *\brief Memeber function to sort all entries by subject matter Z to A and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by by subject matter Z to A.
     */
    function sortBySubjectMatterZtoA($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field title in dessc order
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by title desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);
    }

    /**
     *\brief Member function to sort all entries by most replies and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by by most replies.
     */
    function sortByMostReplies($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field replies in desc order
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by replies desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);
    }

    /**
     *\brief Member function to sort all entries by least replies and return that result as an array.
     * \param number_of_entries_per_page An Integer. This is to paginate all the entries
     * so it can be  view in batches with the default being to view 5 messages at a time.
     * \param starting_element An Integer. This integer allow you to paginate. This integer
     * will force the result to start from a specific place in the database.
     * \note In essence, if you want view entries 13 to 15. Then the starting_element will be
     * 13 and the number_of_entries_per_page will be 2.
     * \return An array with the all messages sorted by by least replies.
     */
    function sortByLeastReplies($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field replies in asc order
///and store the result in a temporary variable. The language MYSQL is used to do this.It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_hosportal_original_messages order by replies asc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);
    }
}
?>