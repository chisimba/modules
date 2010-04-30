<?php
/* ----------- data class extends dbTable for tbl_helloforms_comments------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_helloforms_comments
 * @author Paul Mungai, Zwelithini, Philani, Thenjiwe
 * @copyright 2010 University of the Western Cape
 */
class dbhosportal_original_messages extends dbTable {
    private $objUser;

    /**
     * Constructor method to define the table
     */
    function init() {
        parent::init('tbl_hosportal_original_messages');
        $this->objUser = $this->getObject('user_module','hosportal');
        // $this->tablename = 'tbl_hosportal_messages';
        // $this->replytable = '';
        //   $this->objUser = $this->objUser->createNewObjectFromModule("user" ,"security");
        //   $this->objUser = &$this->getObject('user', 'security');

    }
    /**
     * Return all records
     * @param string $userid The User ID
     * @return array The entries
     */
    function listAll() {
        return $this->getAll();

    }
    /**
     * Return all records
     * @param string $comments The comments
     * @return array The entries
     */

    function listComment($comments) {
        return $this->getAll("WHERE commenttxt LIKE '%" . $comments . "%'");
    }


    function listSingle($id) {
        return $this->getAll("WHERE id='" . $id . "'");
    }
    /**
     * Insert a record
     * @param string $title title
     * @param string $comments comments
     * -- @param string $userId The user ID
     */

    function insertSingle($id,$title,$comments,$unreplied,$noofreplies) {
//        $userid = $this->objUser->userId();
        $userid = $this->objUser->getUserFullName();
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
        return $id;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $category Category
     * -- @param string $userId The user ID
     */
    function updateSingle($id, $title, $comments,$unreplied,$noofreplies) {
        //$userid = $this->objUser->userId();
//        $userid = $this->objUser->getUserFullName();
//       $result = $this->update("id", $id, array(
//            'userid' => $userid,
//            'title' => $title,
//            'commenttxt' => $comments,
//            'modified' => $this->now(),
//            'commenttxtshort'=> $comments,
//            'original' => $original,
//            'replies' => $noofreplies
//        ));
//       return $result;
        $userid = $this->objUser->getUserFullName();
        $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );

        $result = $this->update('id',$id,$data);
        return $result;
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) {
        $this->delete("id", $id);
    }
    function paginateAll($starting_element,$ending_element) {

        $sql =  "select * from (select @rownum:=@rownum+1 rank, p.* from tbl_hosportal_original_messages p, (SELECT @rownum:=0) r limit 1000) as t where rank between $starting_element and $ending_element";
        return $this->getArray($sql);
    }
        function getNoOfSearchedOriginalMessages($searchValue)
            {
       // "select * from tbl_hosportal_original_messages where commenttxt like "%add%" || title like "%add%" || userid like "%add%"";
     $sql=   "where commenttxt like '%".$searchValue."%' || title like '%$searchValue%' || userid like '%$searchValue%'";
//$sql = "select * from tbl_hosportal_original_messages where commenttxt like "%.$searchValue.%" || title like "%add%" || userid like "%add%"";
//  $sql = "select * from tbl_hosportal_original_messages where commenttxt like '%".$searchValue."%'order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
  return $this->getRecordCount($sql);

            }

    function searchOriginalMessages($searchValue,$number_of_entries_per_page = 5,$starting_element = 0)
            {
       // "select * from tbl_hosportal_original_messages where commenttxt like "%add%" || title like "%add%" || userid like "%add%"";
     $sql=   "select * from tbl_hosportal_original_messages where commenttxt like '%".$searchValue."%' || title like '%$searchValue%' || userid like '%$searchValue%' order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
//$sql = "select * from tbl_hosportal_original_messages where commenttxt like "%.$searchValue.%" || title like "%add%" || userid like "%add%"";
//  $sql = "select * from tbl_hosportal_original_messages where commenttxt like '%".$searchValue."%'order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
  return $this->getArray($sql);   

            }
    function sortByLatestModified($number_of_entries_per_page = 5,$starting_element = 0)
                {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        // $this->commitTransaction($sql);
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }

    function sortByOldestModified($number_of_entries_per_page = 5,$starting_element = 0)
                {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by modified asc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        
        // $sql = "select*from (select @rownum:=@rownum+1 rank, p.* from tbl_hosportal_original_messages p, (SELECT @rownum:=0) r limit 1000) as t order by modified asc where rank between 1 and 2";
       // $this->commitTransaction( $sql);
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }

    function sortByAuthorAtoZ($number_of_entries_per_page = 5,$starting_element = 0)
                {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by userid asc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    function sortByAuthorZtoA($number_of_entries_per_page = 5,$starting_element = 0)
                    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by userid desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    function sortBySubjectMatterAtoZ($number_of_entries_per_page = 5,$starting_element = 0)
                {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by title asc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    function sortBySubjectMatterZtoA($number_of_entries_per_page = 5,$starting_element = 0)
                {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        // $result = $this->getAll('WHERE storycategory = \''.$category.'\' ORDER BY storyorder DESC LIMIT 1');
        $sql = "select*from tbl_hosportal_original_messages order by title desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    function sortByMostReplies($number_of_entries_per_page = 5,$starting_element = 0)
                {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by replies desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    function sortByLeastReplies($number_of_entries_per_page = 5,$starting_element = 0)
                    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_original_messages order by replies asc LIMIT $number_of_entries_per_page OFFSET $starting_element";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
}
?>