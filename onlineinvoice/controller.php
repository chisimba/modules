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
        $this->objdblodging = & $this->getObject('dbLodging','onlineinvoice');
        $this->objdbinvoice = & $this->getObject('dbinvoice','onlineinvoice');
        $this->objdbtev = & $this->getObject('dbtev','onlineinvoice');
        $this->objdbitinerary  = & $this->getObject('dbitinerary','onlineinvoice');
        
        /**
         *pass variables to the template
         */         
        
        $this->setVarByRef('fullname', $this->objUser->fullname());
        $this->userId = $this->objUser->userId();
	     	$this->getObject('sidemenu','toolbar');
        $this->setLayoutTemplate('calendar_layout_tpl.php');
       
        
        
       
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
           $saveitinerary  = $this->getParam('saveitinerary');
           $add     = $this->getParam('add');
           if(isset($submit)) {
            $this->getMultiItinerarydetails();
            $addmultiitineraryinfo = $this->getSession('addmultiitinerary');
            var_dump($addmultiitineraryinfo);
            return 'itenirarymulti_tpl.php';
           }else{
            //$addmultiitinerary  =   $this->getMultiItinerarydetails();
            $this->getMultiItinerarydetails();
            $addmultiitineraryinfo = $this->getSession('addmultiitinerary');
            var_dump($addmultiitineraryinfo);
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
             /*code to return to the expenses form and show msg box for submit sucessfull*/
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
               /*PROBLEM SYNTAX*///$itinerarydetails  = $this->getSession('addmultiitinerary');
               //var_dump($itinerarydetails);
               /*PROBLEM SYNTAX*///$this->objdbitinerary->additinerary($itinerarydetails); 
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
       $invoicedate  = array('begindate'  =>  $this->getParam('txtbegindate'),
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
       
       $claimantinfo = array('claimanantname' => $this->getParam('txtClaimantName'),
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
      
      $itinerarydata = array('departuredate' => $this->getParam('txtdeptddate'),
                             'departuretime' => $strhrs = $this->getParam('departuretime') .$this->getParam('minutes'),
                             'departurecity' => $this->getParam('txttxtdeptcity'),
                             'arrivaledate'  => $this->getParam('txtarraivaldate'),
                             'arrivaltime'   => $this->getParam('arrivaltime'). $this->getParam('minutes'),
                             'arrivalcity'   => $this->getParam('txtarrivcity')
                    );
                    
        
      $itineraryinfo[] = $itinerarydata;
      $this->setSession('addmultiitinerary',$itineraryinfo);
  }
/*******************************************************************************************************************************************************************/                          
  private function getPerDiemExpenses()
  {
      $perdiemdata[0] = $this->getParam('txtexpensesdate');
      $perdiemdata[1] = $this->getParam('b');
      //$perdiemdata
      $perdiemdata[2] = $this->getParam('l');
      $perdiemdata[3] = $this->getParam('d');
      
  }
/*******************************************************************************************************************************************************************/                          
  

}

?>
