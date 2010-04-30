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
class dbhosportal_messages extends dbTable
{
    private $objUser;
    private $tablename;
    private $objDBOriginalComments;
    private $objDBReplies;
    /**
     * Constructor method to define the table
     */
    
    function init()
    {
        parent::init('tbl_hosportal_messages');
        $this->objUser = $this->getObject('user_module','hosportal');
        $this->objDBOriginalComments = $this->getObject('dbhosportal_original_messages','hosportal');
        $this->objDBReplies = $this->getObject('dbhosportal_replies','hosportal');
       // $this->replytable = '';
     //   $this->objUser = $this->objUser->createNewObjectFromModule("user" ,"security");
     //   $this->objUser = &$this->getObject('user', 'security');

    }

    /**
     * Return all records
     * @param string $userid The User ID
     * @return array The entries
     */
    function listAll()
    {
        return $this->getAll();

    }
    /**
     * Return all records
     * @param string $comments The comments
     * @return array The entries
     */
    function getNewArray()
    {
      //$data=array("userid","title","commenttxt","modified","commenttxtshort","unreplied","replies");
    return $this->getArray();
      
      
    }
    function getOriginalMessages()
    {
   //   $sql=  $this->getAll();
         //    $sql = "select*from tbl_hosportal_messages";
       // $sql = $this->getArray($sql);
    // $sql= $this->getArray();
       $sql= $this->listAll();
      foreach($sql as $thisComment){
   //Store the values of the array in variables

   $id = $thisComment["id"];
   $userid = $thisComment["userid"];
   $title = $thisComment["title"];
   $commenttxtshort = $thisComment["commenttxtshort"];
   $comments = $thisComment["commenttxt"];
   $modified = $thisComment["modified"];
   $unreplied = $thisComment["unreplied"];
   $no_of_replies = $thisComment["replies"];

if (($no_of_replies ==0 & $unreplied == true)||($no_of_replies>0 & $unreplied == FALSE))
    {
 //   $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$modified,"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $no_of_replies );
    //$no_of_elements++;
    $this->objDBOriginalComments->insertSingle($title,$comments,$unreplied,$noofreplies);
    //$listpagecomments =array_push($listpagecomments, $data);
   // $listpagecomments[] =$data;
 //  $listpagecomments = $this->objDBComments->insertSingle($title,$comments,$unreplied,$noofreplies);

    }



  }
    }
      function listComment($comments)
    {
        return $this->getAll("WHERE commenttxt LIKE '%" . $comments . "%'");
    }

    function sortByLatestModified()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by modified desc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }

        function sortByOldestModified()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by modified asc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    
            function sortByAuthorAtoZ()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by userid asc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
                function sortByAuthorZtoA()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by userid desc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
                    function sortBySubjectMatterAtoZ()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by title asc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
                        function sortBySubjectMatterZtoA()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
                           // $result = $this->getAll('WHERE storycategory = \''.$category.'\' ORDER BY storyorder DESC LIMIT 1');
        $sql = "select*from tbl_hosportal_messages order by title desc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
    function sortByMostReplies()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by replies desc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }
        function sortByLeastReplies()
    {
        // $data=array("userid"=>$userid,"title"=>$title,"commenttxt"=>$comments,"modified"=>$this->now(),"commenttxtshort"=>$comments,"unreplied"=>$unreplied,"replies" => $noofreplies );
//mysql_query("SELECT * FROM Persons ORDER BY age");
        $sql = "select*from tbl_hosportal_messages order by replies asc";
        return $this->getArray($sql);
//return $this->getAll("SELECT modified FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->mysql_query("SELECT * FROM tbl_hosportal_messages ORDER BY modified DESC");
//        return $this->
    }

//    function recordEntryTime()
//    {
//        //Instantiate the user object
//        //$objUser = $this->getObject('user', 'security');
//        //Store the record in the database
//        $this->insert(array(
//          'userid' => $this->objUser->userId(),
//          'modified' => $this->now()));
//    }
    /**
     * Return a single record
     * @param string $id ID
     * @return array The values
     */
    function listForViewing()
    {


    }
    function listSingle($id)
    {
        return $this->getAll("WHERE id='" . $id . "'");
    }
    /**
     * Insert a record
     * @param string $title title
     * @param string $comments comments
     * -- @param string $userId The user ID
     */

    function insertSingle($title,$comments,$unreplied,$noofreplies)
    {
//        $userid = $this->objUser->userId();
        $userid = $this->objUser->getUserFullName();
        $id = $this->insert(array(
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

        function insertSingleOriginalMessage($id,$title,$comments,$unreplied,$noofreplies)
    {

       // $this->objDBOriginalComments->insertSingle($title,$comments,$unreplied,$noofreplies);
        //$userid = $this->objUser->userId();
        $userid = $this->objUser->getUserFullName();
        $id = $this->insert(array(
            'userid' => $userid,
            'title' => $title,
            'commenttxt' => $comments,
            'modified'=> $this->now(),
            'commenttxtshort'=> $comments,
            'unreplied'=> $unreplied,
            'replies' => $noofreplies
        ));
        $this->objDBOriginalComments->insertSingle($id,$title,$comments,$unreplied,$noofreplies);
        return $id;
    }
            function insertSingleReply($id,$title,$comments,$unreplied,$noofreplies)
    {

       // $this->objDBOriginalComments->insertSingle($title,$comments,$unreplied,$noofreplies);
        //$userid = $this->objUser->userId();
        $userid = $this->objUser->getUserFullName();
        $id = $this->insert(array(
            'userid' => $userid,
            'title' => $title,
            'commenttxt' => $comments,
            'modified'=> $this->now(),
            'commenttxtshort'=> $comments,
            'unreplied'=> $unreplied,
            'replies' => $noofreplies
        ));

        $this->objDBReplies->insertSingle($id,$title,$comments,$unreplied,$noofreplies);
        //$this->objDBOriginalComments->insertSingle($id,$title,$comments,$unreplied,$noofreplies);
        return $id;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $category Category
     * -- @param string $userId The user ID
     */
    function updateSingle($id, $title, $comments,$unreplied,$noofreplies)
    {
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

        function updateSingleOriginalMessage($id, $title, $comments,$unreplied,$noofreplies)
    {//$this->objDBComments->updateSingle($id, $title, $comments,$unreplied,$noofreplies);


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
$this->objDBOriginalComments->updateSingle($id, $title, $comments,$unreplied,$noofreplies);
        $result = $this->update('id',$id,$data);
        
        return $result;
    }

        function updateSingleReply($id, $title, $comments,$unreplied,$noofreplies)
    {//$this->objDBComments->updateSingle($id, $title, $comments,$unreplied,$noofreplies);


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
$this->objDBReplies->updateSingle($id, $title, $comments,$unreplied,$noofreplies);
            //   $this->objDBOriginalComments->updateSingle($id, $title, $comments,$unreplied,$noofreplies);
        $result = $this->update('id',$id,$data);

        return $result;
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id)
    {
        $this->delete("id", $id);
    }

        function deleteSingleOriginalMessage($id)
    {

        $this->objDBOriginalComments->deleteSingle($id);
        $this->delete("id", $id);
    }
            function deleteSingleReply($id)
    {
$this->objDBReplies->deleteSingle($id);
        //$this->objDBOriginalComments->deleteSingle($id);
        $this->delete("id", $id);
    }
}
?>
