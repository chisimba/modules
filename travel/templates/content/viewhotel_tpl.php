<?

$hotel = $this->objHotels->getRow('id',$id);
$image = $this->objHotelImages->getImage($hotel['id']);
$hotelName = ucwords(strtolower($hotel['name']));

$objH = $this->getObject('htmlheading','htmlelements');
$objH->type = 3;
$objH->str = $hotelName;

$hotel_location = $hotel['city'];
if ($hotel['stateprovince']) {
    $hotel_location .= ", {$hotel['stateprovince']}";
}
$hotel_location .= ", {$hotel['country']}<br />";

if ($hotel['address1']) {
    $hotel_location .= $hotel['address1']."<br />";
}
//if ($hotel['address2']) {
//    $hotel_location .= $hotel['address2']."<br />";
//}
if ($hotel['address3']) {
    $hotel_location .= $hotel['address3']."<br />";
}
$hotel_location .= "{$hotel['city']} {$hotel['country']} {$hotel['postalcode']}";

$a_images = $this->objHotelImages->getAll("WHERE id = '{$hotel['id']}' ORDER BY url ASC");

$thumbs = "<div class='thumb_block'>";
foreach ($a_images as $thumb) {
    $count++;
    $onClick = "javascript:document.getElementById(\"hotel_img_tag\").setAttribute(\"src\",\"{$thumb['url']}\");";
    
    
    //replaceImage(\"{$thumb['url']}\")"; 
    $thumbs .= "<img src='{$thumb['thumbnail']}' onclick='$onClick' alt='$hotelName - {$thumb['caption']}' />";
}

$thumbs .= "</div>";

$desc = "<div id='hotel_results' class='hotel_match'>
            <div class='star_rating'></div>
            <div class='hotel_name'>$hotelName</div>
            <hr />
            <div class='hotel_thumb'><img src='{$image['thumbnail']}' alt='$hotelName - {$image['caption']}' /></div>
            <div class='hotel_info'>$hotel_location<br />
                vibe
            </div>
            <hr class='top_margin'/>
            <div id='hotel_image'><img id='hotel_img_tag' src='{$image['url']}' width=272 alt='$hotelName - {$image['caption']}' /></div>
            $thumbs
         </div>";

$content = $objH->show().$desc;

echo $content;


?>