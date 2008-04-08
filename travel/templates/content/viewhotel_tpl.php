<?

$hotel = $this->objHotels->getRow('id',$id);

$objH = $this->getObject('htmlheading','htmlelements');
$objH->type = 3;
$objH->str = ucwords(strtolower($hotel['name']));

$desc = "<div id='hotel_results'><div class='hotel_description'>{$hotel['propertydescription']}</div></div>";

$content = $objH->show().$desc;

echo $content;


?>