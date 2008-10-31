<?php
/**
 * dbbestwishes class
 *
 * 
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *
 * @package   bestwishes
 * @author    Emmanuel Natalis  <matnatalis@udsm.ac.tz>
 * @University Computing center
 * @Dar es salaam university of Tanzania
 * @copyright 2008 Emmanuel Natalis
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
 * dbbestwishes  class
 *
 * 
 *
 *
 * @package   bestwishes
 * @author    Emmanuel Natalis<matnatalis@udsm.ac.tz>
 * @copyright 2008 Emmanuel Natalis
 */
 class dbbestwishes extends dbtable
 {
     /**
     *Variable decleration 
     */
     public $objUser;
     public $objLanguage;
     public $userID;
     /**
     * A constructor method
     */
     function init()
         {
             parent::init('tbl_bestwishesbirthdates');
             $this->objUser=$this->getObject('user','security');
              $this->objLanguage=$this->getObject('language','language');
                  $username=$this->objUser->userName();
                    $this->userID=$this->objUser->getUserId($username);
         }
         function insertBirthdate($birthdate)
             {
                 $username=$this->objUser->userName();
                 $userId=$this->objUser->getUserId($username);
                
                 if( $this->ifUserExist($userId)){
                     return $this->objLanguage->languageText('mod_bestwishes_birthdateexist','bestwishes');
                     } else{
                         /**
                         *We insert the birthdate
                         */
                         $stmt="insert into tbl_bestwishesbirthdates(userid,birthdate) values ('".$userId."','".$birthdate."')";
                         $this->query($stmt);
                         return $this->objLanguage->languageText('mod_bestwishes_bdentered','bestwishes');
                     //    if( $this->query($stmt)){
                       //      return $this->objLanguage->languageText('mod_bestwishes_bdentered','bestwishes');
                 //        } else{
                          //   return $this->objLanguage->languageText('mod_bestwishes_problembdenter','bestwishes');
                    //     }
                     }
                
             }
          function removeBirthdate()
              {
                  /*
                  *This checks if the birthdate is available in the system
                  */
                     $username=$this->objUser->userName();
                    $userId=$this->objUser->getUserId($username);
                   
                    if( !$this->ifUserExist($userId)){
                     return $this->objLanguage->languageText('mod_bestwishes_bdnotfound','bestwishes');
                 } else {
                     $stmt="delete from tbl_bestwishesbirthdates where userid='".$userId."'";
                     $this->query($stmt);
                      return  $this->objLanguage->languageText('mod_bestwishes_bdremoved','bestwishes');
                        // if(  $this->query($stmt)){
                          //   return  $this->objLanguage->languageText('mod_bestwishes_bdremoved','bestwishes');
                       //  } else{
                          //   return $this->objLanguage->languageText('mod_bestwishes_problemrmvbd','bestwishes');
                        // }
                 }
                  
              }
              
               public function ifUserExist($userid)
                {
                   $filter="where userid='$userid'";
                   $counts=$this->getRecordCount($filter);
                   if($counts==0) {
                         return false;
                      } else {
                      return true;
                   }
            }
            public function viewBirthdayUsers()
                {
                     $sq="select A.firstname firstname,A.surname surname,B.birthdate birthdate from tbl_users A,tbl_bestwishesbirthdates B where A.userId=B.userid order by A.firstname";
                     $rs=$this->getArray($sq);
                     $current_date=date('Y-m-d');
                     $header=array("<div align='left'>Full Name</div>","<div align='left'>Birthdate</div>","<div align='left'>Day</div>");
                      //Create an instance of the table object
                     $objTable = $this->newObject('htmltable', 'htmlelements');
                     $objTable->addHeader($header);
                     foreach($rs as $names)
                      {
                          $firstname=$names['firstname'];
                          $lastname=$names['surname'];
                         $fullname=$firstname."  ".$lastname;
                         $birthdate=$names['birthdate'];
                         //Checking for the birthdate
                         $status=$this->ifTheSameDayMonth($current_date,$birthdate);
                          if($status=='TRUE')
                      {
                       //Converting into the required format
                        $birthdate=$this->dayMonth($birthdate);
                         //Retriving day of a week
                        $dayWeek=$this->dayOfWeek($birthdate);
                        $names_list=array($fullname,$birthdate,$dayWeek);
                        //adding rows
                        $objTable->addRow($names_list);
          }
    
      
      }
  //Turn the array into a table   
   //$objTable->arrayToTable($rs);
  //Show the table
   return  $objTable->show();
   //echo $rs[1]['username'];
   

   }
                
                
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
  //function that receives a date and returns only day and month
   public function dayMonth($fulldate)
   {
     //Converting the received date into unix time stamp
      $dateInseconds=strtotime($fulldate);
     //Returning only date and month in the required fomart eg 10th of October
      return date("d  F,Y",$dateInseconds);
   }
  //function that receives a date and returns a day of a week
   public function dayOfWeek($fulldate)
   {
     //Converting the received date into unix time stamp
      $dateInseconds=strtotime($fulldate);
     //Returning day of a week
     return date("l",$dateInseconds);
   }
   /**
   *This function returns the full name  of users celebrating their birthdates today or a message if no users
   */
   public function userFullname()
       {
           /**
           *Variable user_name concanates the names of users celebrating their birthdates
           */
            $user_names="";
            $sq="select A.firstname firstname,A.surname surname,B.birthdate birthdate from tbl_users A, tbl_bestwishesbirthdates B where A.userId=B.userid order by A.firstname";
            $rs=$this->getArray($sq);
            $current_date=date('Y-m-d');
           //Create an instance of the table object
           
          
             foreach($rs as $names)
             {
              $firstname=$names['firstname'];
               $surname=$names['surname'];
              $birthdate=$names['birthdate'];
              $fullname=$firstname."  ".$surname;
             //Checking for the birthdate
             $status=$this->ifTheSameDayMonth($current_date,$birthdate);
             if($status=='TRUE')
                  {
                     /**
                     *Concanating the users celebrating their birthdays
                     */
                     $user_names=$user_names.$fullname."<br>";
                   }  
                }
                   if($user_names=="")
                     {
                         return $this->objLanguage->languageText('mod_bestwishes_nousermsg','bestwishes');
                     } else
                     {
                         return $user_names;
                     }
            }
            function saveEvents($title,$description)
                {
                    $date=Date('Y-m-d');
                    $stmt="insert into tbl_bestwishespostevents(eventtitle,eventdescription,datestamp,userid) values ('".$title."','".$description."','".$date."','".$this->userID."')";
                    $this->query($stmt);
                    return 'Your Event is succesifully saved';
                }
                
                public function viewUsersEvents()
                    {
                        $stmt="select A.firstname fname,A.surname lname,B.eventtitle et,B.puid puid  from tbl_users A,tbl_bestwishespostevents B where A.userId=B.userid order by B.datestamp";
                        $rs=$this->getArray($stmt);
                        $header=array("<div align='left'>Firstname</div>","<div align='left'>Surname</div>","<div align='left'>Event Title</div>","<div align='left'>Post message</div>","<div align='left'>Post Card</div>");
                         $objTable = $this->newObject('htmltable', 'htmlelements');
                         $objTable->addHeader($header);
                         foreach($rs as $events)
                             {
                                 $fname=$events['fname'];
                                 $lname=$events['lname'];
                                 $et=$events['et'];
                                 $puid=$events['puid'];
                                 $postmsg="<a href='".$g."'>Post message</a>";
                                 $postcard="<a href='".$g."'>Post card</a>";
                                 $content=array($fname,$lname,$et,$postmsg,$postcard);
                                $objTable->addRow($content);
                            }
                         return $objTable->show();
                        
                    }
 }
?>