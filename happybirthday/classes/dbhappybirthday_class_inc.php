<?php

/*
*@Author Emmanuel Natalis
*University Computing Center
*University of Dar es salaam
*/
// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check
class dbhappybirthday extends dbtable
{
 //Retriving the user's fullname
 public $fullName;
//variable decleration for the username(retriving the user's logging name
public $username;
public $objUser;

      

 public function init()
{
  // Getting the user object. security is the name of the module and user is the name of the class whose object is gonna be created
           $this->objUser = $this->getObject('user', 'security');
     //We call a fullname function to get the user's fullname
        $this->fullName = $this->objUser->fullname();
     //Retriving the username
  $this->username=$this->objUser->userName();

  //Set the table in the parent class
  parent::init('tbl_birthdates');
}

 public function insert_birthdate($bdate)
 {
 //Checking if the user exist in the system or not
  if(!$this->ifUserExist($this->username))
{
  //Sql query to insert into database
 $sql="insert into tbl_birthdates(username,firstname,birthdate) values ('$this->username','$this->fullName','$bdate')";
 $this->query($sql);
  //Checking if the user is successifully inserted
   if(!$this->ifUserExist($this->username))
  {
    return 'error'; //Means an error has encountered
   }
  else
  {
 return 'inserted'; //Means the record is succesifully inserted
 }
} else
 {
    return 'exist'; //Means the birthdate already exist in the system
 }


  
 } 
//function to determine if user exist in the table or not
//it takes a username as its argument
//returns true if user exist or false if user not exisr
  public function ifUserExist($username)
 {
  $filter="where username='$username'";
  $counts=$this->getRecordCount($filter);
  if($counts==0)
    {
   return false;
   } else 
 {
      return true;
 }
 }
 //This function deletes the user's birthdate in the system
 public function deleteBirthdate()
  {
     //Checking if the userbirthdate exist in the system
       if($this->ifUserExist($this->username))
       {
        //deleting the user
       $sql="delete from tbl_birthdates where username='$this->username'";
       $this->query($sql);
        return 'deleted'; //returns this value if the user's birthdate is successifully deleted in the system
       } else
       {
         return 'not_exist'; //Returns this value is the user doesn't exist in  the system
       }
  }
  //Function to display users celebrating their birthdates today
  public function displayUser()
  {
  
  $sq='select * from tbl_birthdates';
  $rs=$this->getArray($sq);
  $current_date=date('Y-m-d');
  $header=array("<div align='left'>Full Name</div>","<div align='left'>Birthdate</div>");
  //Create an instance of the table object
  $objTable = $this->newObject('htmltable', 'htmlelements');
   $objTable->addHeader($header);
   foreach($rs as $names)
    {
     $fullname=$names['firstname'];
     $birthdate=$names['birthdate'];
      //Checking for the birthdate
     $status=$this->ifTheSameDayMonth($current_date,$birthdate);
    if($status=='TRUE')
    {
     $names_list=array($fullname,$birthdate);
    //adding rows
     $objTable->addRow($names_list);
     }
    
      
   }
  //Turn the array into a table   
   //$objTable->arrayToTable($rs);
  //Show the table
   echo $objTable->show();
   //echo $rs[1]['username'];
   

   }
 //Function to determine if the two dates entered are of the same day and month
 //The dates must be of the form YYYY-MMM-ddd
 
 public function ifTheSameDayMonth($date1,$date2)
  {
     //splitting date1
     $arrayDate1=split("-",$date1);
     //splitting date2
     $arrayDate2=split("-",$date2);
     //Concanating month and date values for date1
     $dayMonth1=$arrayDate1[1].$arrayDate1[2];
     //Concanating month and date values for date2
     $dayMonth2=$arrayDate2[1].$arrayDate2[2];
     //checking if they are the same
     if($dayMonth1==$dayMonth2)
       {
         return 'TRUE';
       } else
       {
         return 'FALSE';
       }
  }
}
?>
