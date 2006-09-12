<?php



/**
 * security check - must be included in all scripts
 */
   
if (!$GLOBALS['kewl_entry_point_run'])    // -- CHECK WITH MEGAN, should this syntax remain the same i.e kewl and is it only placed in controller 
{
	die("You cannot view this page directly");
}
// end security check

/**
* Invoice Controller
* This class controls all functionality to run the invoice module.
* @author Colleen Tinker
* @copyright (c) 2004 University of the Western Cape
* @package invoice
* @version 1
*/

class onlineinvoice extends controller
{
    /**
     * declare variable used within class
     */     
           
    /**
      * objUser is an object from the user class, used to hold user information
      * @public 
        
    */
    public  $objUser = null;
    
    /**
      * $objdblodging is an object from the lodging class, used to hold user lodge expense information
      * @public 
        
    */
    
    public $objdblodging = NULL; 
    
    /**
      * $objdbinvoice is an object from the invoice class, used to hold user invoice date information
      * @public 
        
    */    
    public  $objinvdate = NULL;
    
    /**
      * $objdbperdiem is an object from the per diem class, used to hold user per diem information
      * @public 
        
    */ 
    public $objdbperdiem = NULL;
    
    public $total = NULL;
    
    /**
         
	* Constructor method to instantiate objects and get variables
	*/
    function init()
    {
    
        /**
         *create objects of the various classes used within this module
         *author Colleen tinker         
         */
                          
        $this->objLanguage =& $this->getObject('language', 'language');
        
        $this->objUser =& $this->getObject('user', 'security');
        
        $this->objdblodging = & $this->getObject('dblodging','onlineinvoice');
        
        $this->objdbinvoice = & $this->getObject('dbinvoice','onlineinvoice');
        
        $this->objdbtev = & $this->getObject('dbtev','onlineinvoice');
        
        $this->objdbitinerary  = & $this->getObject('dbitinerary','onlineinvoice');
        
        $this->objuploadfile  = & $this->getObject('fileupload','utilities');
        $this->objdbsys = & $this->getObject('altconfig','config');
        $this->objdbperdiem = & $this->getObject('dbperdiem','onlineinvoice');
       
        /**
         *pass variables to the template
         */         
        
        $this->setVarByRef('fullname', $this->objUser->fullname());   //used as an example
        $this->userId = $this->objUser->userId();
	     	$this->getObject('sidemenu','toolbar');
        //$this->setLayoutTemplate('invoice_layout_tpl.php');
       
    }
    
    /**
    	* Method to process actions to be taken
      * @param string $action String indicating action to be taken
    	*/
	
  
    function dispatch($action)
    {
        
        $this->setVar('pageSuppressXML',true);
             
        switch($action){
              
             case 'createinvoice':
                  return 'main_tpl.php';            /** display the initial log - in template **/ 
              //  return 'createInvoice_tpl.php';   /*  display initial invoice   */
             break;
            
            case 'submitinvoicedates':
              /** call the function that stores date values entered by user into a session variable -- therefore setting the session
                *  return to the invoice template            
                */
                  $this->getInvoicedates();
                  $this->setLayoutTemplate('invoice_layout_tpl.php');
                  return 'createInvoice_tpl.php';
            break;
          
          case 'createtev':                   /*  display the tev voucher   */
                $this->setLayoutTemplate('invoice_layout_tpl.php'); 
                return 'tev_tpl.php';
                break;        
            
          case  'submitclaimantinfo':
                /** call the function that stores date values entered by user into a session variable
                  *  show the next template
                  */
             
                $this->getClaimantdetails();
                $this->setLayoutTemplate('invoice_layout_tpl.php');
                return 'itenirarymulti_tpl.php';
                //return 'tev_tpl.php';
          break;
/********************************************************************************************************************/            
          case  'submitmultiitinerary':
          /**
           *determines which button to call and which action to perform depending on user selection
           *           
           *$nextsection -- saves the itinerary filled in once / multiple times by user into session variable then goes to per diem template
           *
           *$additinerary -- calls the function to save information for the itinerary into an array 
           *also allows user to add itinerary as many times as needed                                          
           *creates a session variable to hold the information of the itinerary
           *returns back to the itinerary form
           *
           *back -- return to previous template -- tev
           *                      
           *$eixitinerary --  returns the initial template                                             
           */  
              
              $exitinerary  = $this->getParam('exit');
              $additinerary = $this->getParam('add');
              $nextsection  = $this->getParam('next');
              $back         = $this->getParam('back');
              if(isset($nextsection)) {
                    $this->getMultiItinerarydetails();                           
                    $this->setLayoutTemplate('invoice_layout_tpl.php');  
                    //$addmultiitineraryinfo = $this->getSession('addmultiitinerary'); /** get the session with the multi dim array and assign to a variable **/   
                    //var_dump($addmultiitineraryinfo);            
                    return  'expenses_tpl.php';                 
              }elseif(isset($additinerary)){
                    $this->getMultiItinerarydetails();                               /** call the function in which the session variable is set containing the multi dim array  **/
                    //$addmultiitineraryinfo = $this->getSession('addmultiitinerary'); /** get the session with the multi dim array and assign to a variable **/   
                    //var_dump($addmultiitineraryinfo);                                /** dump the info on screen **/
                    $this->setLayoutTemplate('invoice_layout_tpl.php');              
                    return 'itenirarymulti_tpl.php';                                 /** back to this form to fill in another itinerary**/
              }elseif(isset($back)){
                    $this->setLayoutTemplate('invoice_layout_tpl.php');
                    return 'tev_tpl.php';
              }else{
                  $this->setLayoutTemplate('invoice_layout_tpl.php');
                  return 'createInvoice_tpl.php';                                    /** change template to return to post-login? **/ 
              }
          break;                    
/**************************************************************************************************************************/          
          case  'submitexpenses':
             /**
              *determines which button to call depending on user selection
              *$next    --    saves the itinerary filled in once by user into session variable then goes to lodge template
              *$addperdiem -- calls the function to save information for the per diem expenses into an array 
              *also allows user to add per diem as many times as needed                                          
              *creates a session variable to hold the information of the per diem exp selected / entered
              *returns back to the perd diem form
              *$exit    --  returns the initial template                            
              */
              $next = $this->getParam('saveperdiem');
              $addperdiem = $this->getParam('addperdiem');
              $exit = $this->getParam('exit');
              if(isset($next)) {
                  $total  =  $this->objdbperdiem->calucrate($brate,$lrate,$drate);
                  $this->getPerDiemExpenses();
                  
                  //$perdiemdata = $this->getSession('perdiemdetails');
                  //var_dump($perdiemdata);
                  $this->setLayoutTemplate('invoice_layout_tpl.php');
                  return  'lodging_tpl.php';
              }elseif(isset($addperdiem)){
                  $this->getPerDiemExpenses();
                  $this->setLayoutTemplate('invoice_layout_tpl.php');
                  return  'expenses_tpl.php';
              }else{                           
                $this->setLayoutTemplate('invoice_layout_tpl.php');
                return 'createInvoice_tpl.php';
              }  
          break;
          
          case  'submitlodgeexpenses':
            /**
             *determine which button the user clicks
             *next -- calls the function that saves info into a session variable
             *exit -- leave the form and return to original inv template
             *back -- return to the previous template                                         
             */
             $next  = $this->getParam('next');
             $exit  = $this->getParam('exit');
             $back  = $this->getParam('back');
             if(isset($next))  {
                /**
                 *call function to save all lodge info -- date, vendor, currency, exchange rate,  cost....and upload / save files
                 *assign values to a session variable     date, vendor, currency, exchange rate,  cost -- save to session variable ...
                 *show next template -- to upload files                 
                 */
                 $rename = true;
                 $replace = true;
                 $file_max_size = 16;
                 $check_type="";
                 $dir_name  = 'onlineinvoice';
                 
                 
                 $dir_path = $this->objdbsys->getcontentBasePath() . $dir_name.'/';
                 $this->objuploadfile->set_directory($dir_path);
                 $this->objuploadfile->check_for_directory();
                 $this->objuploadfile->upload_file($dir_path,true,true,50);
                 
                 $this->getLodgeexpenses();                 
                 $this->setLayoutTemplate('invoice_layout_tpl.php');
                 return 'lodgereceipt_tpl.php';
             }elseif(isset($back)){
                 $this->setLayoutTemplate('invoice_layout_tpl.php');
                 return  'expenses_tpl.php';                                                                                      
             }else{
                /**
                 *exit the form without submitting the invoice
                 */
                 $this->setLayoutTemplate('invoice_layout_tpl.php');
                 return 'createInvoice_tpl.php';                                              
             }
          break;
/**************************************************************************************************************************************************/          
          case 'submitlodgereceipt':
                $next  = $this->getParam('next');
                $exit  = $this->getParam('exit');
                $back  = $this->getParam('back');
             if(isset($next))  {
                /**
                 *call function to save all lodge info -- date, vendor, currency, exchange rate,  cost....and upload / save files
                 *assign values to a session variable     date, vendor, currency, exchange rate,  cost -- save to session variable ...
                 *show next template -- to upload files                 
                 */
                 $dir_name  = 'onlineinvoice';
                 $dir_path = $this->objdbsys->getcontentBasePath() . $dir_name.'/';
                
                 $this->objuploadfile->set_directory($dir_path);
                 $this->objuploadfile->check_for_directory();
                 $this->objuploadfile->upload_file($dir_path,true,true,50);
                
                 //$this->getLodgeexpenses();                 
                 $this->setLayoutTemplate('invoice_layout_tpl.php');
                 return 'incidentinfo_tpl.php'; 
             }elseif(isset($back)){
                 $this->setLayoutTemplate('invoice_layout_tpl.php');
                 return  'expenses_tpl.php';                                                                                      
             }else{
                /**
                 *exit the form without submitting the invoice
                 */
                 $this->setLayoutTemplate('invoice_layout_tpl.php');
                 return 'createInvoice_tpl.php';                                              
             }
          break;
/**************************************************************************************************************************************************/          
          case  'submitincidentinfo':
                $next  = $this->getParam('next');
                $exit  = $this->getParam('exit');
                $back  = $this->getParam('back');
              
               if(isset($next))  {
                /**
                 *call function to save all lodge info -- date, vendor, currency, exchange rate,  cost....and upload / save files
                 *assign values to a session variable     date, vendor, currency, exchange rate,  cost -- save to session variable ...
                 *show next template -- to upload files                 
                 */
                  $dir_name  = 'onlineinvoice';
                  $dir_path = $this->objdbsys->getcontentBasePath() . $dir_name.'/';
                
                  $this->objuploadfile->set_directory($dir_path);
                  $this->objuploadfile->check_for_directory();
                  $this->objuploadfile->upload_file($dir_path,true,true,50);
                  
                  $this->getIncidentinfo();
                
                 //$this->getLodgeexpenses(); -- getfunction to save all incident information into variable session                 
                   $this->setLayoutTemplate('invoice_layout_tpl.php');
                   return 'incidentfiles_tpl.php';   
               }elseif(isset($exit)){
                  //return the next template to upload the files for incident
                  $this->setLayoutTemplate('invoice_layout_tpl.php');
                  return 'createInvoice_tpl.php';
               }else{
                $this->setLayoutTemplate('invoice_layout_tpl.php');
                return 'lodgereceipt_tpl.php';
               }                                     
           break;
          
           case  'submitincidentreceipt':
                $next  = $this->getParam('next');
                $exit  = $this->getParam('exit');
                $back  = $this->getParam('back');
              
               if(isset($next))  {
                /**
                 *call function to save all lodge info -- date, vendor, currency, exchange rate,  cost....and upload / save files
                 *assign values to a session variable     date, vendor, currency, exchange rate,  cost -- save to session variable ...
                 *show next template -- to upload files                 
                 */
                  $dir_name  = 'onlineinvoice';
                  $dir_path = $this->objdbsys->getcontentBasePath() . $dir_name.'/';
                
                  $this->objuploadfile->set_directory($dir_path);
                  $this->objuploadfile->check_for_directory();
                  $this->objuploadfile->upload_file($dir_path,true,true,50);
                
                 //$this->getLodgeexpenses(); -- getfunction to save all incident information into variable session                 
                   //$this->setLayoutTemplate('invoice_layout_tpl.php');
                   return 'tevoutput_tpl.php';   
               }elseif(isset($exit)){
                  //return the next template to upload the files for incident
                  $this->setLayoutTemplate('invoice_layout_tpl.php');
                  return 'createInvoice_tpl.php';
               }else{
                $this->setLayoutTemplate('invoice_layout_tpl.php');
                return 'incidentfiles_tpl.php';
               }                                     
          break;                                          
          

          case  'savealldetails':
          /**
           *save all claimant information
           */
           $save  = $this->getParam('save');
           $edit  = $this->getParam('edit');
           if(isset($save)) {
              /**
               * save invoice begin and end dates
               * call the session that contains th data selected by the user for intial invoice dates
               * call the function in the dbinvoice class using an object --> objdbinvoice
               * return the template and a msessage indicating that its save                                            
               */
               $invdates  = $this->getSession('invoicedata');
               $this->objdbinvoice->addinvoice($invdates);
              /**
               *save claimant personal details
               * call the session that contains the data entered by the user
               * call the function in the dbtev class using an object --> objdbtev
               * return the template and a msessage indicating that its save                                            
               */
               $claimantdetails = $this->getSession('claimantdata');
               $this->objdbtev->addclaimant($claimantdetails);
              /**
               *save itinerary details
               * call the session that contains the data entered by the user
               * call the function in the dbitinerary class using an object --> objdbitinerary
               * return the template and a msessage indicating that its save                                            
               */
               $itinerarydetails  = $this->getSession('addmultiitinerary');
               $this->objdbitinerary->additinerary($itinerarydetails);
              /**save per diem details
               * call the session that contains the data entered by the user
               * call the function in the dbperdiem class using an object --> objdbperdiem
               * return the template and a msessage indicating that its save                                            
               */
               $perdiemdetails = $this->getSession('perdiemdetails');
               //$this->objdbperdiem->addperdiem($perdiemdetails);
                
               //echo 'claimant info submitted';
               $this->setLayoutTemplate('invoice_layout_tpl.php');
               return 'claimantoutput_tpl.php';
            }else{
              /**
               *return the tev template to allow user to change the values
               */     
             $this->setLayoutTemplate('invoice_layout_tpl.php');                          
             return 'tev_tpl.php';                       
           }
           break;
         
          case 'createservice':
              $this->setLayoutTemplate('invoice_layout_tpl.php');
              return 'service_tpl.php';
          break;
          
          case  'verifylogin':
              $this->setLayoutTemplate('postlogin_layout_tpl.php');
              //return 'createInvoice_tpl.php';
              return 'postlogin_tpl.php';
              break;
          case 'createnewinvoice':
              $this->setLayoutTemplate('invoice_layout_tpl.php');
              return 'createInvoice_tpl.php';
              break;                  
            
          default:
            return $this->nextAction('createinvoice', array(NULL));
                
        }
    }
/*******************************************************************************************************************************************************************/                                          
    private function getInvoicedates()
    {
      /**
       *create an array - $invoicedate to store the invoice dates that the user selects
       *create a session variable to store the array data in       
       */
       $username  = $this->objUser->fullname();
       $invoicedate  = array('createdby'    =>  $username,
                             'datecreated'  =>  date('Y-m-d'),
                             'modifiedby'   =>  $this->objUser->fullname(),
                             'datemodified' =>  date('Y-m-d'),
                             'updated'      =>  date('Y-m-d'),
                             'begindate'    =>  $this->getParam('txtbegindate'),
                             'enddate'      =>  $this->getParam('txtenddate'),
                        );
                        
        $this->setSession('invoicedata',$invoicedate);
    }
/*******************************************************************************************************************************************************************/                            
    private function getClaimantdetails()
    {
      /**
       *create an array claimantdetails to store details user enters
       *create a session variable claimantdata to store the array data
       */
       
       $claimantinfo = array ('createdby'      =>  $this->objUser->fullname(),
                             'datecreated'    =>  date('Y-m-d'),
                             'modifiedby'     =>  $this->objUser->fullname(),
                             'datemodified'   =>  date('Y-m-d'),
                             'updated'        =>  date('Y-m-d'),
                             'name' => $this->getParam('txtClaimantName'),
                             'title'          => $this->getParam('txtTitle'),
                             'address'    => $this->getParam('address'),
                             'city'           => $this->getParam('txtCity'),
                             'province'       => $this->getParam('txtprovince'),
                             'postalcode'     => $this->getParam('txtpostalcode'),
                             'country'        => $this->getParam('txtcountry'),
                             'travelpurpose'  => $this->getParam('travel')
                        );
       $this->setSession('claimantdata',$claimantinfo);
                       
    }
/*******************************************************************************************************************************************************************/                            
  private function getMultiItinerarydetails()
  {
        /**
       *create array to hold the users itinerary information
       *store array data in session variable ititenrarydata
       */
      $itinerarydata['createdby']    =  $this->objUser->fullname();
      $itinerarydata['datecreated']  =  date('Y-m-d');
      $itinerarydata['modifiedby']   =  $this->objUser->fullname();
      $itinerarydata['datemodified'] =  date('Y-m-d');
      $itinerarydata['updated']      =  date('Y-m-d');
      $itinerarydata['departuredate'] = $this->getParam('txtdeptddate');          //get date user selects -->
      $itinerarydata['departuretime'] = $this->getParam('departuretime') .$this->getParam('minutes') . ':00';
      $itinerarydata['departurecity'] = $this->getParam('txtdeptcity');
      $itinerarydata['arrivaledate']  = $this->getParam('txtarraivaldate');
      $itinerarydata['arrivaltime']   = $this->getParam('arrivaltime'). $this->getParam('minutes') . ':00';
      $itinerarydata['arrivalcity']   = $this->getParam('txtarrivcity');
                     
                    
      $itineraryinfo = $this->getSession('addmultiitinerary');  
      $itineraryinfo[] = $itinerarydata;
      $this->setSession('addmultiitinerary',$itineraryinfo);
      
      
/*******************************************************************************************************************************************************************/      
       //get the initial date from 1st array 
          $initial =  $itineraryinfo[0]['departuredate'];     //get the date user selected at initial leg
       //get the last date from the last array added
          $count = count($itineraryinfo);
          $num = $count + 1;
          $last = $itineraryinfo[$num]['departuredate'];
          
          $daterange  = $initial  .  '-'  . $last;
          $this->setSession('daterange',$daterange); 
      /********************************************************************/    
    //display the next day after the initial dept date
    //for($i = $date1; $i <= $date2; $i = $this->nextDay){
    //$i = $this->nextDay;

  }
/*******************************************************************************************************************************************************************/                          
  private function getPerDiemExpenses()
  
 // fucntion calucrate($brate,$lrate,$drate)
   
  
  
  {
  
   $brate  = $this->getParam('txtbreakfastRate');
    $lrate  = $this->getParam('txtlunchRate');
    $drate  = $this->getParam('txtdinnerRate');
    
      $perdiemdata = array('createdby'          =>  $this->objUser->fullname(),
                           'datecreated'        =>  date('Y-m-d'),
                           'modifiedby'         =>  $this->objUser->fullname(),
                           'datemodified'       =>  date('Y-m-d'),
                           'updated'            =>  date('Y-m-d'),
                           'date'               =>  $this->getParam('txtexpensesdate'), // change to the date of depature 
                           'bchoice'            =>  $this->getParam('breakfast'),
                           'blocation'          =>  $this->getParam('txtbreakfastLocation'),
                           'btrate'             =>  $brate,
                           'lchoice'            =>  $this->getParam('lunch'),
                           'llocation'          =>  $this->getParam('txtlunchLocation'),
                           'lRate'              =>  $lrate,
                           'dchoice'            =>  $this->getParam('dinner'),
                           'dlocation'          =>  $this->getParam('txtdinnerLocation'),
                           'drrate'             =>  $drate ,
                           'total'              =>  $total,       
                           );               
                           
      $perdieminformation =  $this->getSession('perdiemdetails');
      $perdieminformation []  = $perdiemdata;
      $this->setSession('perdiemdetails',$perdiemdata);                          
      
  }
/*******************************************************************************************************************************************************************/
    /**
     *get all lodge information
     * function to add lodge expenses to a session variable
     * @private
     */
    private function getLodgeexpenses()
    {
       $lodgedata  = array(
                  'createdby'         =>  $this->objUser->fullname(),
                  'datecreated'       =>  date('Y-m-d'),  
                  'modifiedby'        =>  $this->objUser->fullname(),
                  'datemodified'      =>  date('Y-m-d'),
                  'updated'           =>  date('Y-m-d'),
                  'date'              =>  $this->getParam('txtlodgedate'),        //itinerary departure date need to change
                  'vendor'            =>  $this->getParam('txtvendor'),
                  'currency'          =>  $this->getParam('txtcurrency'),
                  'cost'              =>  $this->getParam('txtcost'),
                  'quotesource'       =>  $this->getParam('txtquotesource'),
                  'exchangerate'      =>  $this->getParam('txtexchange'),
                  'exchangefile'      =>  $this->getParam('upload')
                  
   
                );
                
       $this->setSession('lodgedetails',$lodgedata);
    }
/*******************************************************************************************************************************************************************/

   private function getIncidentinfo()
   {
      $incidentdata = array(
                            'createby'      =>  $this->objUser->fullname(),
                            'datecreated'   =>  date('Y-m-d'),
                            'modifiedby'    =>  $this->objUser->fullname(),
                            'datemodified'  =>  date('Y-m-d'),
                            'updated'       =>  date('Y-m-d'),
                            'date'          =>  date('Y-m-d'),
                            'vendor'        =>  $this->getParam('txtvendor'),
                            'description'   =>  $this->getParam('description'),
                            'currency'      =>  $this->getParam('currency'),
                            'cost'          =>  $this->getParam('txtcost'),
                            'quotesource'       =>  $this->getParam('txtquotesource'),  
                            'exchangerate'  =>  $this->getParam('txtexchange'),
                            'incidentratefile'  => $this->getParam('upload')
                    
                           );
      $this->setSession('incidentdetails',$incidentdata);
   
   }
}

?>
