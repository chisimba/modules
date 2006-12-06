<?php
header("Content-Type: text/xml; charset=utf-8"); 

//check if we are dealing with an array
if ( !is_array($myvar)){
 echo $myvar;
}else
{
    foreach ($myvar as $kline => $line)
    {
     foreach($line as $key => $field)
      {
       print $field."|";
      }
//    print ("^$kline\n");
     }
}

?>
