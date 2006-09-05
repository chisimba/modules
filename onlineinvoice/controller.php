<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Calendar Controller
* This class controls all functionality to run the calendar module. It now integrates user calendar and contextcalendar
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package calendar
* @version 2
*/
class onlineinvoice extends controller
{
    //declare variable
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
         *create an instance of classes
         */         
        
        
        //$this->setVarByRef('objLanguage', $this->objLanguage);
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objdblodging = & $this->getObject('dblodging','onlineinvoice');
        $this->objdbinvoice = & $this->getObject('dbinvoice','onlineinvoice');
        $this->objdbtev = & $this->getObject('dbtev','onlineinvoice');
        $this->objdbitinerary  = & $this->getObject('dbitinerary','onlineinvoice');
      //  $this->objdbperdiem  = & $this->getObject('dbperdiem','onlineinvoice'); 
        /**
         *pass variables to the template
         */         
        
        $this->setVarByRef('fullname', $this->objUser->fullname());
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
            return 'createInvoice_tpl.php';
            break;
          
          case 'createtev':
            return 'tev_tpl.php';
            break;
            
          case 'submitinvoicedates':
            /** call the function that stores date values entered by user into a session variable
             *  show the template            
              */
            $this->getInvoicedates();
            return 'createInvoice_tpl.php';
            break;
            
          case  'submitclaimantinfo':
            /** call the function that stores date values entered by user into a session variable
             *  show the next template
             */
             
            $this->getClaimantdetails();
            return 'tev_tpl.php';
            break;
          
          case 'createitenirary':
          /** 
           *takes the user to the itenirary template to complete
           */
            return 'itenirary_tpl.php';
            break;
            
          case  'submititinerary':
            /**
             *call the function that stores the details of the travelers itinerary
             *create a session varaible to store the itinerary information             
             */
             
             $itineraryresults  = $this->getItinerarydetails();
             /*$itineraryinfo     =   $this->getSession('itinerarydata');
             var_dump($itineraryinfo);*/
               /* echo '<pre>';
                print_r($_POST);
                echo '<pre>';*/
                         
            return 'itenirary_tpl.php';
            break;
            
          case  'createmultiitenirary':
          /**
           *takes the user to form to complete itinerary for multiple travel
           */                     
            return 'itenirarymulti_tpl.php';
            break;
            
          case  'submitmultiitinerary':
          /**
           *calls the function to save information for the itinerary into an array
           *creates a session variable to hold the information of the itinerary
           *returns back to the itinerary form                       
           */  
              $saveitinerary  = $this->getParam('save');
              $additinerary     = $this->getParam('add');
              if(isset($saveitinerary)) {
              $addmultiitineraryinfo = $this->getMultiItinerarydetails();
              $multiitineraryinfo = $this->getSession('addmultiitinerary');
              var_dump($multiitineraryinfo);
              return 'itenirarymulti_tpl.php';
           }else{
              $this->getMultiItinerarydetails();
              $multiitineraryinfo = $this->getSession('addmultiitinerary');
              var_dump($multiitineraryinfo); 
              
            //$addmultiitineraryinfo = $this->getSession('addmultiitinerary');
            //var_dump($addmultiitineraryinfo);
            return 'itenirarymulti_tpl.php';
           }                   
            break;
          case 'createexpenses':
            return  'expenses_tpl.php';
            break;
            
          case  'createlodging':
          return  'lodging_tpl.php';
          break;
          
          case  'submitexpenses':
             /**
              *determines which button is clicked and then perform related action
              *if the saveperdiem - clicked then save values into a session variable
              *else if the add another perdiem expense clicked -- add data to a multi-dim array                            
              */
              $saveperdiem = $this->getParam('saveperdiem');
              $addperdiem = $this->getParam('addperdiem');
              if(isset($saveperdiem)) {
                  $this->getPerDiemExpenses();
                  //$perdiemdata = $this->getSession('perdiemdetails');
                  //var_dump($perdiemdata);
                  return  'expenses_tpl.php';
              }else{
                  $this->getPerDiemExpenses();
                  return  'expenses_tpl.php';
              }                           
             $this->getPerDiemExpenses();
             //$perdiemdata = $this->getSession('perdiemdetails');
             var_dump($perdiemdata);
             return  'expenses_tpl.php';
             break;
             
          case  'submitlodgeexpenses':
            /*code to return to the lodgeexpenses form and to submit values into db and display msg box when sucessfully submitted*/
            //$display = $this->objdbinvoice->getinvoicedates(); 
            //var_dump($display);
            //die;
            //$this->addLodgeexpenses();
            return  'lodging_tpl.php';
            //return 'tev_tpl.php';
            //return 'createInvoice_tpl.php';
            break;
          case  'showclaimantoutput':
            return 'claimantoutput_tpl.php';
            break;
          case 'createservice';
            return 'service_tpl.php';
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
            
          default:
            return $this->nextAction('createinvoice', array(NULL));
                
        }
    }
/*******************************************************************************************************************************************************************/                        
    /**
     * function to add lodge expenses to the database table
     * @private
     * @return true if sucessfull
     */
    private function addLodgeexpenses()
    {
       $data  = array('date'     =>  $this->getParam('txtdate'),
                  'vendor'        =>  $this->getParam('txtvendor'),
                  'currency'      =>  $this->getParam('txtcurrency'),
                  'cost'          =>  $this->getParam('txtcost'),
                  'exchangerate'  =>  $this->getParam('txtexchange')
                  
   
                );
                
               $expensedetails = $this->objdblodging->add($data);
                return $expensedetails;
    }
/*******************************************************************************************************************************************************************/                                          
    private function getInvoicedates()
    {
      /**
       *create an array - $invoicedate to store the invoice dates that the user selects
       *create a session variable to store the array data in       
       */
       
       $invoicedate  = array('createdby'    =>  $this->objUser->fullname(),
                             'datecreated'  =>  '2006-09-04',
                             'modifiedby'   =>  $this->objUser->fullname(),
                             'datemodified' =>  '2006-09-04',
                             'updated'      =>  '2006-09-04',
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
      $itinerarydata = array('createdby'    =>  $this->objUser->fullname(),
                             'datecreated'  =>  '2006-09-04',
                             'modifiedby'   =>  $this->objUser->fullname(),
                             'datemodified' =>  '2006-09-04',
                             'updated'      =>  '2006-09-04',
                             'departuredate' => $this->getParam('txtdeptddate'),
                             'departuretime' => $this->getParam('departuretime') .$this->getParam('minutes') . ':00',
                             'departurecity' => $this->getParam('txtdeptcity'),
                             'arrivaledate'  => $this->getParam('txtarraivaldate'),
                             'arrivaltime'   => $this->getParam('arrivaltime'). $this->getParam('minutes') . ':00',
                             'arrivalcity'   => $this->getParam('txtarrivcity')
                            );
                    
        
      //$itineraryinfo[] = $itinerarydata;
      $this->setSession('addmultiitinerary',$itinerarydata);
  }
/*******************************************************************************************************************************************************************/                          
  private function getPerDiemExpenses()
  {
      $perdiemdata = array('date'             =>  $this->getParam('txtexpensesdate'),
                           'breakfastchoice'  =>  $this->getParam('breakfast'),
                           'breakfastlocation' => $this->getParam('txtbreakfastLocation'),
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
  

}

?>
