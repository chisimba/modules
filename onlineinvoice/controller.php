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
       
        /**
         *pass variables to the template
         */         
        
        //$this->setVarByRef('fullname', $this->objUser->fullname());   used as an example
        $this->userId = $this->objUser->userId();
	     	$this->getObject('sidemenu','toolbar');
        $this->setLayoutTemplate('invoice_layout_tpl.php');
       
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
               return 'createInvoice_tpl.php';   /*  display initial invoice   */
               break;
            
          case 'submitinvoicedates':
            /** call the function that stores date values entered by user into a session variable -- therefore setting the session
             *  return to the invoice template            
             */
                $this->getInvoicedates();
                return 'createInvoice_tpl.php';
                break;
          
          case 'createtev':                   /*  display the tev voucher   */ 
                return 'tev_tpl.php';
                break;        
            
          case  'submitclaimantinfo':
            /** call the function that stores date values entered by user into a session variable
             *  show the next template
             */
             
            $this->getClaimantdetails();
            return 'itenirarymulti_tpl.php';
            //return 'tev_tpl.php';
            break;
/*******************************************************************************************************************/          
          //case 'createitenirary':
              /** 
                *takes the user to the itenirary template to complete
                */
         //  return 'itenirary_tpl.php';
         //  break;
            
         // case  'submititinerary':
             /**
               *call the function that stores the details of the travelers itinerary
               *create a session varaible to store the itinerary information             
               */
             
         // $itineraryresults  = $this->getItinerarydetails();
        /*$itineraryinfo     =   $this->getSession('itinerarydata');
              var_dump($itineraryinfo);*/
             /* echo '<pre>';
              print_r($_POST);
              echo '<pre>';*/
            //return 'itenirary_tpl.php';
            //break;
/*******************************************************************************************************************/                         
            
         // case  'createmultiitenirary':
          /**
           *takes the user to form to complete itinerary for multiple travel
           */                     
         //   return 'itenirarymulti_tpl.php';
         //   break;          /**link for multi itinerary -- dnt need using buttons**/
/********************************************************************************************************************/            
          case  'submitmultiitinerary':
          /**
           *determines which button to call depending on user selection
           *$nextsection -- saves the itinerary filled in once by user into session variable then goes to per diem template
           *
           *$additinerary -- calls the function to save information for the itinerary into an array 
           *also allows user to add itinerary as many times as needed                                          
           *creates a session variable to hold the information of the itinerary
           *returns back to the itinerary form
           *
           *$eixitinerary --  returns the initial template                                             
           */  
              //$saveitinerary  = $this->getParam('save');
              $exitinerary  = $this->getParam('exit');
              $additinerary     = $this->getParam('add');
              $nextsection  = $this->getParam('next');
             // if(isset($saveitinerary)) {
              if(isset($nextsection)) {
                    $this->getMultiItinerarydetails();                              /** call function setting session **/
              //      $multiitineraryinfo = $this->getSession('addmultiitinerary');   /** getsession plus data  **/
              //    var_dump($multiitineraryinfo);                                  /** dump on screen  **/      
                    return  'expenses_tpl.php';                                     /** show next template **/      
                    //return 'itenirarymulti_tpl.php'; -- used when saving -- not needed tho
              }elseif(isset($additinerary)){
                    $this->getMultiItinerarydetails();                              /** call the function in which the session variable is set containing the multi dim array  **/
                    $addmultiitineraryinfo = $this->getSession('addmultiitinerary');/** get the session with the multi dim array and assign to a variable **/   
                    var_dump($addmultiitineraryinfo);                               /** dump the info on screen **/
                    return 'itenirarymulti_tpl.php';                                /** back to this form to fill in another itinerary**/
              }else{
                  return 'createInvoice_tpl.php';
              }
          break;                    
/**************************************************************************************************************************/            
         // case 'createexpenses':            -- used with link but using buttons therefore dont need anymore --
         //      return  'expenses_tpl.php';
         //   break;
/**************************************************************************************************************************/            
          //case  'createlodging':
          //return  'lodging_tpl.php';
          //break;
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
                  $this->getPerDiemExpenses();
                  //$perdiemdata = $this->getSession('perdiemdetails');
                  //var_dump($perdiemdata);
                  return  'lodging_tpl.php';
              }elseif(isset($addperdiem)){
                  $this->getPerDiemExpenses();
                  return  'expenses_tpl.php';
              }else{                           
                return 'createInvoice_tpl.php';
              }  
          break;
          
          case  'submitlodgeexpenses':
            /**
             *determine which button the user clicks
             *next -- calls the function that saves info into a session variable
             *exit                           
             */
             $next  = $this->getParam('next');
             $exit  = $this->getParam('exit');
             if(isset($next))  {
                /**
                 *call function to save all lodge info -- date, vendor, currency, exchange rate,  cost....
                 *assign values to a session variable
                 *show next template -- to upload files                 
                 */
                 $this->getLodgeexpenses();
                 return 'uploadlodgefiles_tpl.php';                                                                                  
             }else{
                /**
                 *exit the form without submitting the invoice
                 */
                 return 'createInvoice_tpl.php';                                              
             }
          break;
          
          case 'submitlodgefiles':
              /**
               *determine which button is clicked
               *next - show incident form
               *exit - leave the form
               *upload submit the form -- all files 
               */                                                                          
              $uploadfiles  = $this->getParam('uploadfiles');
              $next  = $this->getParam('next');
              $exit  = $this->getParam('exit');
              if(isset($uploadfiles)){
               /*upload submit the form -- all files*/ 
              }
              if(isset($next)){
                  return 'incidentinfo_tpl.php';      // should show incident files template -- therefore change details
              }else{
                  return 'createInvoice_tpl.php'; 
              }
          break;
             
          case  'submitincidentinfo':
              /**
               *determine which button is selected
               *next -- save info into session variable by calling thefunction and jump to next template
               *exit leave the form
               */ 
               $exit  = $this->getParam('exit');
               $next  = $this->getParam('next');
               if(isset($next)){
                  //save info and take to nect template
                  return 'incidentfiles_tpl.php';
               }else{
                  //return the next template to upload the files for incident
                  return 'createInvoice_tpl.php';
               }                                     
          break;
          case  'submitincidentfiles':
                /**
                 *next -- show output form
                 *exit -- leave the form
                 *upload -- uploade files
                 */
               $exit  = $this->getParam('exit');
               $next  = $this->getParam('next');
               $uploadfiles  = $this->getParam('uploadfiles');
               if(isset($next)){ 
                return 'claimantoutput_tpl.php';
               }elseif($uploadfiles){
               //submit uploaded files
               }else{
                  //return the next template to upload the files for incident
                  return 'createInvoice_tpl.php';
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
               return 'claimantoutput_tpl.php';
            }else{
              /**
               *return the tev template to allow user to change the values
               */                             
             return 'tev_tpl.php';                       
           }
           break;
         
          case 'createservice';
              return 'service_tpl.php';
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
       //sytax as to how to insert current date
       $invoicedate  = array('createdby'    =>  $username,
                             'datecreated'  =>  '2006-09-05',
                             'modifiedby'   =>  $this->objUser->fullname(),
                             'datemodified' =>  '2006-09-05',
                             'updated'      =>  '2006-09-05',
                             'begindate'  =>  $this->getParam('txtbegindate'),
                             'enddate'    =>  $this->getParam('txtenddate'),
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
       
       $claimantinfo = array('createdby'    =>  $this->objUser->fullname(),
                             'datecreated'  =>  '2006-09-04',
                             'modifiedby'   =>  $this->objUser->fullname(),
                             'datemodified' =>  '2006-09-04',
                             'updated'      =>  '2006-09-04',
                             'claimanantname' => $this->getParam('txtClaimantName'),
                             'title'          => $this->getParam('txtTitle'),
                             'mailaddress'    => $this->getParam('txtAddress'),
                             'city'           => $this->getParam('txtCity'),
                             'province'       => $this->getParam('txtprovince'),
                             'postalcode'     => $this->getParam('txtpostalcode'),
                             'country'        => $this->getParam('txtcountry'),
                             'travelpurpose'  => $this->getParam('travel')
                        );
       $this->setSession('claimantdata',$claimantinfo);
                       
    }
/*******************************************************************************************************************************************************************/                            
  //private function getItinerarydetails()
  //{
      /**
       *create array to hold the users itinerary information
       *store array data in session variable ititenrarydata
       */
       
      /*$itineraryinfo[0]  = $this->getParam('txtdeptddate');
      $itineraryinfo[1]  = $this->getParam('departuretime');
      $itineraryinfo[2]  = $this->getParam('txttxtdeptcity');
      $itineraryinfo[3]  = $this->getParam('txtarraivaldate');
      $itineraryinfo[4]  = $this->getParam('arrivaltime');
      $itineraryinfo[5]  = $this->getParam('txtarrivcity');*/   
      
      //$this->setSession('itinerarydata',$itineraryinfo);                   
  //}
/*******************************************************************************************************************************************************************/                          
  private function getMultiItinerarydetails()
  {
        /**
       *create array to hold the users itinerary information
       *store array data in session variable ititenrarydata
       */
      $itinerarydata['createdby']    =  $this->objUser->fullname();
      $itinerarydata['datecreated']  =  '2006-09-04';
      $itinerarydata['modifiedby']   =  $this->objUser->fullname();
      $itinerarydata['datemodified'] =  '2006-09-04';
      $itinerarydata['updated']      =  '2006-09-04';
      $itinerarydata['departuredate'] = $this->getParam('txtdeptddate');
      $itinerarydata['departuretime'] = $this->getParam('departuretime') .$this->getParam('minutes') . ':00';
      $itinerarydata['departurecity'] = $this->getParam('txtdeptcity');
      $itinerarydata['arrivaledate']  = $this->getParam('txtarraivaldate');
      $itinerarydata['arrivaltime']   = $this->getParam('arrivaltime'). $this->getParam('minutes') . ':00';
      $itinerarydata['arrivalcity']   = $this->getParam('txtarrivcity');
                     //       );
                    
        
      $itineraryinfo[] = $itinerarydata;
      $this->setSession('addmultiitinerary',$itineraryinfo);
  }
/*******************************************************************************************************************************************************************/                          
  private function getPerDiemExpenses()
  {
      $perdiemdata = array('createdby'          =>  $this->objUser->fullname(),
                           'datecreated'        =>  '2006-09-04',
                           'modifiedby'         =>  $this->objUser->fullname(),
                           'datemodified'       =>  '2006-09-04',
                           'updated'            =>  '2006-09-04',
                           'date'               =>  $this->getParam('txtexpensesdate'),
                           'breakfastchoice'    =>  $this->getParam('breakfast'),
                           'breakfastlocation'  =>  $this->getParam('txtbreakfastLocation'),
                           'breakfastrate'      =>  $this->getParam('txtbreakfastRate'),
                           'lunchchoice'        =>  $this->getParam('lunch'),
                           'lunchlocation'      =>  $this->getParam('txtlunchLocation'),
                           'txtlunchRate'       =>  $this->getParam('txtlunchRate'),
                           'dinnerchoice'       =>  $this->getParam('dinner'),
                           'dinnerlocation'     =>  $this->getParam('txtdinnerLocation'),
                           'dinnerrate'         =>  $this->getParam('txtdinnerRate')
                           );
                           
      //$perdieminformation[] =  $perdiemdata;
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
                  'datecreated'       =>  '2006-09-04',
                  'modifiedby'        =>  $this->objUser->fullname(),
                  'datemodified'      =>  '2006-09-04',
                  'updated'           =>  '2006-09-04',
                  'date'              =>  $this->getParam('txtlodgedate'),
                  'vendor'            =>  $this->getParam('txtvendor'),
                  'currency'          =>  $this->getParam('txtcurrency'),
                  'cost'              =>  $this->getParam('txtcost'),
                  'exchangerate'      =>  $this->getParam('txtexchange')
                  
   
                );
                
       $this->setSession('lodgedetails',$lodgedata);
    }
}

?>
