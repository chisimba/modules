  <?php
   /**
    * converts temperature measurements: Kelvin, celcius, and fahrenheit 
    * 
    * weihfilwe wepofhiopwef wepofjwopef wepofjpwoefuj
    * @author Nazheera Khan <2524939@uwc.ac.za> 
    * @author Ebrahim Vasta <2623441@uwc.ac.za> 
    * @package convertions
    * @copyright UWC 2007
    * @filesource
    */ 
class temp extends object
{
    public $val;

    public function init()
    {
        // $this->val = $val;
    }

    public function setup($val)
    {
      // set some val....
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

    public function convkelToFahren($val)
    {
        $Answer = ($val - 273.15) * 9 / 5 + 32; 
        return $Answer;
    }
    public function convFahrenTokel($val)
    {
        $Answer = (5 / 9) * ($val - 32)+273.15;  
        return $Answer;
    }
    public function convkelToCels($val)
    {
        $Answer = $val-273.15; 
        return $Answer;
    }
    public function convcelTokel($val)
    {
        $Answer = $val+273.15;
        return $Answer;
    }
    
    public function showForm($vals = NULL)
    {
		$valC = NULL;
		if($vals != NULL){
			$valC = $_POST['val'];
		}
		$form ='<table border=0 cellspacing=1 cellpadding=1 style="text-align:right;">';
 		$form .= '<tr><th>VALUE';
        $form .= '</th><th width=100px>CONVERT FROM';
        $form .= '</th><th>TO';
        $form .= '</th></tr>';
        $form .= '<form method="POST" action="'.$_SELF.'">';
        $form .= '</td></tr><tr><td><input type="text" size="12" name="val" value='.$valC.'>';
        $form .= '</td><td><select name="from">';
        $form .= '<option selected value="1">Celsius</option>';
        $form .= '<option value="2">Fahrenheit</option>';
        $form .= '<option value="3">Kelvin</option>';
        $form .= '</select>';
        $form .= '</td><td><select name="to">';
        $form .= '<option selected value="1">Celsius</option>';
        $form .= '<option value="2">Fahrenheit</option>';
        $form .= '<option value="3">Kelvin</option>';
        $form .= '</select>';
        $form .= '</td></tr><tr><td colspan=3><input type="submit" value="submit" name="submit">';
        $form .= '</form>';
        $form .= '</td></tr></table><br />';
        return $form;
    }
    public function showAll()
    {
        $from = $_POST['from'];
		$to = $_POST['to'];
   		if(isset($_POST['val']) == NULL){
            return $this->showForm($_POST['val'])."Insert a value";
        }
		elseif($from == $to && isset($_POST['val']))
		{
            return $this->showForm($_POST['val']).'You cant convert a value to itselsf';
		}
        elseif($from == "1" && $to == "2")
        {
            return  $this->showForm($_POST['val']).$_POST['val']." degrees celsius"." is converted to ".round(($this->convCelsToFahren($_POST['val'])),2)." fahrenheit.";
        }
        elseif($from == "2" && $to == "1"){
            return  $this->showForm($_POST['val']).$_POST['val']." farenheit"." is converted to ".round(($this->convFahrenToCels($_POST['val'])),2)." degrees celsius.";
        }
        elseif($from == "2" && $to == "3")
        {
            return  $this->showForm($_POST['val'])."tr".$_POST['val']." fahreneit"." is converted to ".round(($this->convFahrenTokel($_POST['val'])),2)." kelvin.";
        }
        elseif($from == "3" && $to == "2")
        {
            return $this->showForm($_POST['val']).$_POST['val']." kelvin"." is converted to ".round(($this->convkelToFahren($_POST['val'])),2)." fahrenheit.";
        }
        elseif($from == "1" && $to == "3")
        {
            return  $this->showForm($_POST['val']).$_POST['val']." celsius"." is converted to ".round(($this->convcelTokel($_POST['val'])),2)." kelvin.";
        }
 elseif($from == "3" && $to == "1")
        {
            return  $this->showForm($_POST['val']).$_POST['val']." kelvin"." is converted to ".round(($this->convkelToCels($_POST['val'])),2)." celcius.";
        }
        else{
            return  $this->showForm($_POST['val'])."Sorry an error occured";
        }
    }
}
$final = new tempConv;
echo $final->showAll(); 
?>
