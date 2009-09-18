<?php
/**
* Template to display the main page for the new forum
* @access public
*/

//$curr_dir = dirname(__FILE__) . "/";
//$web_root = $_SERVER[REQUEST_URI];

$web_root = '';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Chisimba Ad Banner</title>

<link rel="stylesheet" href="templates/layout/ad_banner_layout_tpl.css">

<script type="text/javascript" src="resources/javascript/jquery.js"></script>
<script type="text/javascript" src="resources/javascript/auto_slide.js"></script>
<script type="text/javascript">

var global_delay = 3000;
var global_banner_counter = 1; //Start the first banner
//Sort Order to come from DB
var banners_arr = Array('box', 'stuff', 'things', 'girl', 'google');

    $(document).ready(function(){

		//setTimeout("nextBanner(banners_arr)",global_delay);	
		setInterval("nextBanner(banners_arr)",global_delay);	
		
	   // anchors (without permalinking) example 1
        $.wiggleSlide({
			sliderNav: '#slider-nav', // [ID OR CLASS] accepts any html element with an id.. "div" is recommended
        	sliderClickLink: 'a', // [ELEMENT] accepts "a","li","div" html element, must be inside 'slider-nav', what you click on to slide
        	hiddenContainer: '#invis-cont', // [ID OR CLASS] accepts any html element with an id.. "div" is recommended
        	sliderItemClassName: 'slider-item', // [CLASS] (FORCED CLASS) the class on your sliding items
        	swingSpeed: "medium", // accepts "slow","medium","fast" or int speed: example 900
			permalinking: false, // ONLY WORKS WITH sliderClickLink set to 'a'. If you want to show page.html#slide in the url bar and have it start on that slide
        	wiggle: true, // do you want it to bounce when you slide
			static_image_id: banners_arr[0]
		});
       
    });

	function nextBanner(banners){
		setBanner(banners[global_banner_counter]);
		
		if (global_banner_counter < banners.length - 1){
			global_banner_counter++;
		} else {
			global_banner_counter = 0;
		}
		
		//alert(global_banner_counter);
	}
	
	function setBanner(image_id){
	
		// anchors (without permalinking) example 1
        $.wiggleSlide({
			sliderNav: '#slider-nav', // [ID OR CLASS] accepts any html element with an id.. "div" is recommended
        	sliderClickLink: 'a', // [ELEMENT] accepts "a","li","div" html element, must be inside 'slider-nav', what you click on to slide
        	hiddenContainer: '#invis-cont', // [ID OR CLASS] accepts any html element with an id.. "div" is recommended
        	sliderItemClassName: 'slider-item', // [CLASS] (FORCED CLASS) the class on your sliding items
        	swingSpeed: "medium", // accepts "slow","medium","fast" or int speed: example 900
			permalinking: false, // ONLY WORKS WITH sliderClickLink set to 'a'. If you want to show page.html#slide in the url bar and have it start on that slide
        	wiggle: true, // do you want it to bounce when you slide
			static_image_id: image_id
		});			
	}
	
</script>
<style type="text/css">

</style></head><body>

	<div id="slider-nav" class="nav">
		<a href="#" id="box">BOX</a>
		<a href="#" id="stuff">STUFF</a>
		<a href="#" id="things">THINGS</a>
		<a href="#" id="girl">GIRL</a>
		<a href="#" id="google">GOOGLE</a>
		<div class="clear"></div>
	</div>
	
	<div class="cont">
		<div style="width: 3120px; left: -2080px;" id="invis-cont" class="invis">
			<div class="slider-item" id="slider-box">
				<img src="resources/images/lbc__main_header.png">
			</div>
			
			<div class="slider-item" id="slider-stuff">
				<img src="resources/images/lbc_ico_32x32.png">
			</div>
			
			<div class="slider-item" id="slider-things">
				<img src="resources/images/artist.gif">
			</div>
			
			<div class="slider-item" id="slider-girl">
				<img src="resources/images/localbands_text_420x155.gif">
			</div>
			<div class="slider-item" id="slider-google">
				<img src="resources/images/speakers_162x151.gif">
			</div>
			
			<div class="clear"></div>
		</div>
	</div>
</body>
</html>
