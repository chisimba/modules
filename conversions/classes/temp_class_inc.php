<?php
 /*** converts temperature measurements: Kelvin, celcius, and fahrenheit 
    * <longer description>
    * @author Nazheera Khan <2524939@uwc.ac.za>, Ebrahim Vasta <2623441@uwc.ac.za> 
    * @package convertions
    * copyright UWC 2007
    * @filesource
    */ 
class temperature
{
    public $val;

    public function __construct()
    {
        $this->val = $val;
    }
   
    public function convCelsToFahren($val)
    {
        $Answer = ((9/5) * ($val)+ 32) ;
        return $Answer;
    }
    public function convFahrenToCels($val)
    {
        $Answer = (5 / 9) * ($val - 32);
        return $Answer;
    }

    public function convKelToFahren($val)
    {
        $Answer = ($val - 273.15) * 9 / 5 + 32; 
        return $Answer;
    }
    public function convFahrenToKel($val)
    {
        $Answer = (5 / 9) * ($val - 32)+273.15;  
        return $Answer;
    }
    public function convKelToCels($val)
    {
        $Answer = $val - 273.15; 
        return $Answer;
    }
    public function convcelToKel($val)
    {
        $Answer = $val + 273.15;
        return $Answer;
    }
    
    public function showForm()
    {  
        $form  = '<form method="POST" action="'.$_SELF.'">';
        $form .= 'Temperature to be converted: <input type="text" size="12" name="val">';
        $form .= '<Select name="temp">';
        $form .= '<option selected value="1">Celsius To Fahrenheit</option>';
        $form .= '<option value="2">Fahrenheit To Celsius</option>';
        $form .= '<option value="3">Fahrenheit To Kelvin</option>';
        $form .= '<option value="4">Kelvin to Fahrenheit</option>';
        $form .= '<option value="5">Celsius to Kelvin</option>';
        $form .= '<option value="6">Kelvin to Celsius</option>';
        $form .= '</select><br />';
        $form .= '<input type="submit" value="submit" name="submit">';
        $form .= '<br />';
        $form .= '</form>';
        echo $form;
    }
    public function showAll()
    {
        $val = $_POST['val'];
        $temp = $_POST['temp'];
    if(empty($val)){
            echo $this->showForm();
            echo "Insert a value to be converted";
            return false;
        }
        elseif($temp == "1")
        {
            echo  $this->showForm();
            echo $val." degrees celsius"." is converted to ".round(($this->convCelsToFahren($val)),2)." fahrenheit.";
            return false;
        }
        elseif($temp == "2"){
            echo  $this->showForm();
            echo $val." farenheit"." is converted to ".round(($this->convFahrenToCels($val)),2)." degrees celsius.";
            return false;
        }
        elseif($temp == "3")
        {
            echo  $this->showForm();
            echo $val." fahreneit"." is converted to ".round(($this->convFahrenToKel($val)),2)." Kelvin.";
            return false;
        }
        elseif($temp == "4")
        {
            echo  $this->showForm();
            echo $val." Kelvin"." is converted to ".round(($this->convKelToFahren($val)),2)." fahrenheit.";
            return false;
        }
        elseif($temp == "5")
        {
            echo  $this->showForm();
            echo $val." celsius"." is converted to ".round(($this->convcelToKel($val)),2)." Kelvin.";
            return false;
        }
 elseif($temp == "6")
        {
            echo  $this->showForm();
            echo $val." Kelvin"." is converted to ".round(($this->convKelToCels($val)),2)." celcius.";
            return false;
        }
        else{
            echo  $this->showForm();
            echo "Sorry, an error has occured.";
            return false;
        }
    }
}
$final = new tempConv;
echo $final->showAll(); 
?>
