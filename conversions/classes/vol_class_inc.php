<?php
/**
   * This is an exercise demonstrating the use of forms with php
   * <longer description>
   * @author Nonhlanhla Gangeni <2539399@uwc.ac.za>
   * @package Demo
   * @copyright UWC 2007
   * @filesource
   */

function showForm()
{
    $form  = '<form method="post" action="'.$_SELF.'">';
    $form .= 'Enter value to be converted: <input type="text" size="12" maxlength="12" name="num"> ';
    $form .= '<br />';
    $form .= "CONVERSION: <Select name='list1'>";
    $form .="<option selected value='1'>Litres To millilitres</option>";
    $form .="<option value='2'>Millilitres To Litres</option>";
    $form .="<option value='3'>Cubic Meter To Cubic Decimeter</option>";
    $form .="<option value='4'>Cubic Decimeter To Cubic Meter</option>";
    $form .="<option value='5'>Cubic Centimeter To Cubic Meter</option>";
    $form .="<option value='6'>Cubic Meter To Cubic Centimeter</option>";
    $form .= "</select><br />";
    $form .= '<input type="submit" value="submit" name="submit">';
    $form .= '<br />';
    $form .= '</form>';
    $form .= '<br />';
    return $form;
}
$num = $_POST['num'];
$listOption = $_POST['list1'];
$calc = new volConversion($num);
if(empty($num)){
    echo showForm();
    echo "Please enter a value to be converted.";
}
elseif($listOption == "1"){
    echo showForm();
    echo $calc->convLitresTomillilitres($num);
}
elseif($listOption == "2"){
    echo showForm();
    echo $calc->convmillilitresToLitres($num);
}
elseif($listOption == "3"){
    echo showForm();
    echo $calc->convCubicDecimeterToCubicMeter($num);
}
elseif($listOption == "4"){
    echo showForm();
    echo $calc->convCubicMeterToCubicDecimeter($num);
}
elseif($listOption == "5"){
    echo showForm();
    echo $calc->convCubicCentimeterTocubicMeter($num);
}
elseif($listOption == "6"){
    echo showForm();
    echo $calc->convCubicMeterToCubicCentimeter($num);
}
else{
    echo showForm();
    echo "Sorry, an error has occured.";
}
class volConversion
{
    public $num;
    public function __construct($num)
    {
        $this->num = $num;     
    }    
    public function convLitresTomillilitres($num)
    {
        $milli_litres = $num*1000;       
        return $num."l"." "."="." ".$milli_litres."ml"."<br />";      
    }
     public function convmillilitresToLitres($num)
    {
        $litres = $num/1000;       
        return $num."ml"." "."="." ".$litres."l"."<br />";      
    }
    public function convCubicDecimeterToCubicMeter($num)
    {
        $cubic_meter = $num/1000;
        return $num."dm"."<sup>3</sup>"." "."="." ".$cubic_meter."m"."<sup>3</sup>"."<br />";      
    }
    public function convCubicMeterToCubicDecimeter($num)
    {
        $cubic_dec = $num*1000;                     
    return $num."m"."<sup>3</sup>"." "."="." ".$cubic_dec."dm"."<sup>3</sup>"."<br />";            
    }   
    public function convCubicCentimeterTocubicMeter($num)
    {
        $cubic_meter = $num/1000000;               
        return $num."cm"."<sup>3</sup>"." "."="." ".$cubic_meter."m"."<sup>3</sup>"."<br />";      
    }
    public function convCubicMeterToCubicCentimeter($num)
    {
        $cubic_cent = $num*1000000;
        return $num."m"."<sup>3</sup>"." "."="." ".$cubic_cent."cm"."<sup>3</sup>";
    }
}
?>


