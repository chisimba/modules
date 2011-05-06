<?php

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$products = $this->objDbProducts->getProducts(0, 8);

$newRow = true;
$count = 0;
$noOfAdaptations = 0;
$languages = array();
foreach ($products as $product) {               //populates table
    if ($product['parent_id'] == null) {
        $count++;
        $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);

//        print_r($product['id']);
        $languages = array();
        $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
        
        $theProduct = $product + $languages;
        //print_r($theProduct[0]);
        $theLanguages = array();
        $index = 0;
        foreach ($theProduct as $oneProduct) {
            foreach ($oneProduct as $language) {
                //print_r($language);
                $theLanguages = array_merge($theLanguages, $language);
                $index++;
            }
            //echo "1 language counted";
            //print_r($theLanguages);
            echo $index;
            //echo "\n<br>Languages has " . count($theLanguages);
        }
        
    }
    //echo "\n\n<br>Outside big loop";
}

/* foreach example 3: key and value */

//$a = array(
//    "one" => 1,
//    "two" => 2,
//    "three" => 3,
//    "seventeen" => 17
//);
//
//foreach ($a as $k => $v) {
//    echo "\$a[$k] => $v.\n";
//}

/* foreach example 4: multi-dimensional arrays */
$a = array();
$a[0][0] = "a";
$a[0][1] = "b";
$a[1][0] = "y";
$a[1][1] = "z";

//foreach ($a as $v1) {
//    foreach ($v1 as $v2) {
//        echo "$v2\n";
//    }
//}
///* foreach example 5: dynamic arrays */
//
//foreach (array(1, 2, 3, 4, 5) as $v) {
//    echo "$v\n";
//}
//print_r($languages);
?>