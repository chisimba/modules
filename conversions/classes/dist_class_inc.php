<?php
class dist extends object
{

 public $string ;

 public function init()
         {

 $this->string = $string;
         }

public function inchesTocentimeters($conv)
    {
    $string = ($conv*2.54000) ;
   return $string;

   }

public function feetToyard($conv)
    {
   $string = ($conv*0.33330) ;
   return $string;

    }
public function yardTofeet($conv)
   {
   $string = ($conv*3) ;
   return $string;
   }
public function metersTochains($conv)
   {
    $string = ($conv*0.4971) ;
   return $string;
    }
public function chainsTometers($conv)
  {
    $string = ($conv*20.12000) ;
   return $string;
   }
 public function metersTokilometers ($conv)
   {
    $string = ($conv*0.00100) ;
   return $string;

   }

public function kilometersTometers($conv)
   {
    $string = ($conv*1000) ;
   return $string;

   }

public function kilometersTomiles($conv)
  {
    $string = ($conv*0.62140) ;
   return $string;
   }

public function milesTokilometers($conv)
  {
    $string = ($conv*1.60900) ;
   return $string;
    }


/**
 public function showForm()
  {
 $form  = '<form method="post" action="'.$_SELF.'">';
 

$form .=  ' <select name="numbers">';
$form .=  '<option value="1">Centimeters To Inches</option>';
$form .= '<option value="2">Inches to centimeters</option>';
$form .= '<option value="3">Feet To Yard</option>';
$form .=  '<option value="4">yards To Feet</option>';  
$form .= '<option value="5">Meters To chains</option>';
$form .= '<option value="6">Chains To Meters</option>';
$form .= '<option value="7">Kilometrs To Miles</option>';
$form .= '<option value="8">Miles To Kilometers</option>';
$form .= '<option value="9">Meters To Kilometers</option>';
$form .= '<option value="10">Kilometers To Meters</option>';
$form .= '</select>';
$form .= '<input type="text" size="10" maxlength="45" name="convert">';            
 $form .= '<br />';
 $form .= '<input type="submit" value="submit" name="submit">';
 $form .= '<br />';
 $form .= '</form>';
 $form .= '<br />';

 return $form;
   }
 


        $conv    = $_POST['convert'];
        $numbers = $_POST['numbers'];
        


        
   if(empty($conv)){
        
       


        echo "Insert a value to be converted";
        
            }elseif($numbers == "1" )
           {
            echo $conv." " .$numbers." "."is:"." ".$conv." ".$number;
            }
    elseif($numbers == "2" )
            {
            echo $conv." " ."Inches Equals"." ".round(inchesTocentimeters($conv))." "."Centimeters";
            }
elseif($numbers == "3" )
            {
            echo $conv." " ."Feet Equals"." ".round(feetToyard($conv))." "."Yards";
            }
elseif($numbers == "4" )
            {
            echo $conv." " ."Yards Equals"." ".round(yardTofeet($conv))." "."Feet";
            }
elseif($numbers == "5" )
            {
            echo $conv." " ."Meters Equals"." ".round(metersTochains($conv))." "."Chains";
            }
elseif($numbers == "6" )
            {
            echo $conv." " ."Chains Equals"." ".round(chainsTometers($conv))." "."Meters";
            }
elseif($numbers == "7" )
            {
            echo $conv." " ."Kilometers Equals"." ".round(kilometersTomiles($conv))." "."Miles";
            }
elseif($numbers == "8" )
            {
            echo $conv." " ."Miles Equals"." ".round(milesTokilometers($conv))." "."Kilometers";
            }
elseif($numbers == "9" )
            {
            echo $conv." " ."Meters Equals"." ".round(metersTokilometers ($conv))." "."Kilometers";
            }
elseif($numbers == "10" )
            {
            echo $conv." " ."Kilometers Equals"." ".round(kilometersTometers($conv))." "."Meters";
            }
        else{
      
        echo "Sorry, an error has occured.";
        return false;
         }*/
}


?>
