<?php

/**
* Class to manage import of users from other systems
* @author James Scoble
*/
class importuserdata extends object
{

    // Class properties
    var $userfields;
    var $fieldcount;

    // Class propery handles for objects instantiated in this class 
    var $objUser;
    var $objUserAdmin;
    var $objPassword;
    var $objConfig;
    
    function init()
    {
        // Populate the userfields and fieldcount properties.
        // This info is stored here so it can be changed easily.
        $this->userfields=array('userId','username','firstname','surname','title','sex','emailAddress');
        $this->fieldcount=count($this->userfields);

        // Create or get instances of the user, sqlusers and passwords classes
        $this->objConfig=&$this->getObject('config','config');
        $this->objUser=&$this->getObject('user','security');
        $this->objUserAdmin=&$this->getObject('sqlusers','security');
        $this->objPassword=&$this->getObject('passwords','useradmin');
    }

    /**
    * This is a method to read data from a comma-delimited file
    * and parse it into an array for adding new users
    * @param string $file the filename and path to load
    * returns array $info
    */
    function readCSV($file)
    {
        $info=array();
        // Open the file
        $fp=fopen($file,'r');
        // Parse the file line-by-line, as a Comma Seperated Variable file
        while ($line = fgetcsv($fp, 1024, ","))
        {
            if (count($line)==$this->fieldcount){
                $newline=array();
                $num=0;
                //Build an array from the CSV text
                foreach ($this->userfields as $key)
                {
                    $newline[$key]=$line[$num];
                    $num++;
                }
                $info[]=$newline;
            }
        }
        // close the file
        fclose($fp);
        // return the array
        return $info;
    }


    /**
    * This is a method to read data from an XML file
    * and parse it into an array for adding new users
    * This function is a "wrapper" for the xmlserial class in the Utilities module.
    * @param string $file the filename and path to load
    * returns array $info
    */
    function readXML($file)
    {
        $info=array();
        // Open the file
        //$fp=fopen($file,'r');

        $objXML=&$this->getObject('xmlserial','utilities');
        $info=$objXML->readXML($file,TRUE);
        
        // Here we check for a bug - the XML class returns the
        // data differently if there was only one student
        // So we apply a quick fix
        if ((isset($info['student']['userId']))||(isset($info['student']['firstname']))||(isset($info['student']['surname']))){
            $student=array();
            $student[]=$info['student'];
            $info['student']=$student;
        }
        
        // close the file
        //fclose($fp);
        // return the array
        return $info;
    }

    
    /**
    * This method imports data from a file to the database
    * It calls readCSV() or readXML() to parse the file, then
    * importUser() to load in each new user
    * Finally it returns an array of the users it has added.
    *
    * @author James Scoble
    * @param string $file the location on the system of the file.
    * @param string $method the type of file
    * @returns array $data
    */
    function batchImport($file,$method='CSV')
    {
        $data=array();
        // Switch structure to select import method.
        switch ($method)
        {
        case 'CSV':
            $info['student']=$this->readCSV($file);
            break;
        case 'XML':
            $info=$this->readXML($file);
            break;
        default:
            die("Unknown import method!");
        }
        if (!is_array($info)){
            return $data;
        }
        if (!isset($info['batchcode'])){
            $info['batchcode']='auto';
        }
        $data['batchCode']=$info['batchcode'];
        // Loop through the array of users, adding each one.
        foreach($info['student'] as $line)
        {
            $rstring=$this->importUser($line);
            if ($rstring!='exists'){
                list($id,$userId,$username,$firstname,$surname)=explode('|',$rstring);
                $data['student'][]=array('id'=>$id,'userId'=>$userId,'username'=>$username,'firstname'=>$firstname,'surname'=>$surname);
            }
        }
        return $data;
    }
    
    /**
    * Method to add a user to the database with info from the CSV
    * It calls the AddUser method in the sqlusers class
    * @param array $line
    * @returns string $id the new id field of the user
    */
    function importUser($line)
    {
        // copy the array to local variables
        $userId=$line['userId'];
        $username=$line['username'];
        $surname=$line['surname'];
        $firstname=$line['firstname'];
        $email=$line['emailAddress'];
        

        // Now some checks - some of the fields could be blank,
        // and then we must fill them with info from the other fields
        if ($username==''){
            $username=str_replace(' ','',strtolower($firstname[0].$surname));
            $genUserFlag=1;
        } else {
            $genuserFlag=0;
        }
        if ($userId==''){
            $userId=rand(1000,9999).date('Ydi');
            $genIdFlag=1;
        } else {
            $genIdFlag=0;
        }
        
        // If this user is already in the database we don't add a duplicate
        if ($genIdFlag==1){
            $currentId=$this->checkForUser($username,$firstname,$surname,$email);
        } else {
            // Insertion here for defined userId, ignore other values.
            // This is here due to UWC's elearning needs.
            if ($this->objUserAdmin->valueExists('userId',$userId))
            {
                $currentId=$userId;
            } else {
                $currentId=FALSE;
            }
        }
        if ($currentId!=FALSE){
            $userId=$currentId;
            $id=$this->objUser->PKId($userId);
            //return 'exists';
            // We want to add the user to courses, etc, so we still pass the info along.
        } else {
            // Check to see that the username is unique
            // If its already taken, we try another one
            if ($this->objUserAdmin->valueExists('username',$username)){
                $username=str_replace(' ','',strtolower($firstname[0].$firstname[1].$surname));
                if ($this->objUserAdmin->valueExists('username',$username)){
                    $username=str_replace(' ','',strtolower($firstname[0].$surname.rand(100,999)));
                }
            }
        
            // Now we get the array ready to pass through to the security class
            // where the new account will be created.
            $line['userId']=$userId;
            $line['firstName']=$line['firstname'];
            $username=str_replace(' ','',strtolower($username));
            $line['username']=$username;
        
            if (isset($line['cryptpassword'])){
                $line['password']='replace';
            } else {
            // If there's no password set, we call the password class to create one.
                if ((!isset($line['password']))||($line['password']=='')){
                    $line['password']=$this->objPassword->createPassword();
                    $newPassWordFlag=1;
                } else {
                    $newPassWordFlag=0;
                }
            }
            // The country field must exist, even if blank
            if (!isset($line['country'])){
                $line['country']='';
            }
            // The howCreated field
            $line['howCreated']='import';

            // Add this user by calling the useradmin object.
            $id=$this->objUserAdmin->addUser($line);
                                                                                                    
            if (isset($line['cryptpassword'])){
                $this->objUserAdmin->update('id',$id,array('PASSWORD',$line['cryptpassword']));
            } else {
                if ($newPassWordFlag==1){
                    // email the user creation email with the password
                    // only if the password was generated by the system
                    $this->objUserAdmin->emailPassword($userId,$username,$firstname,$surname,$email,$line['password']);
                }
            }
        }
        // Finally, send back the data about the new user
        return $id.'|'.$userId.'|'.$username.'|'.$firstname.'|'.$surname;
    }
    
    /**
    * This method checks if a specific user is already in the database
    * It returns TRUE only if all the fields match
    * @param string $userId
    * @param string $username
    * @param string $firstname
    * @param string $surname
    * @param string $email
    * @returns Boolena TRUE|FALSE
    */
    function checkForUser($username,$firstname,$surname,$email)
    {
        $data=$this->objUser->getAll("where username='$username' and firstname='$firstname' and surname='$surname' and emailAddress='$email'");
        $count=count($data);
        if ($count==0){
            return FALSE;
        } else {
            return $data[0]['userId'];
        }
    }

}// end of class importuserdata
?>
