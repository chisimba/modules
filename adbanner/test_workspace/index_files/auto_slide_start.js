var global_delay = 3000;
var global_banner_counter = 1; //Start the first banner
//Sort Order to come from DB
var banners_arr = Array('box', 'stuff', 'things', 'girl', 'google');

    $(document).ready(function(){

		//setTimeout("nextBanner(banners_arr)",global_delay);	
		setInterval("nextBanner(banners_arr)",global_delay);	
		
	   // anchors (without permalinking) example 1
        jQuery.wiggleSlide({
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
		
		alert(global_banner_counter);
	
		if (global_banner_counter < banners.length - 1){
			global_banner_counter++;
		} else {
			global_banner_counter = 0;
		}
		
		//alert(global_banner_counter);
	}
	
	function setBanner(image_id){
	
		// anchors (without permalinking) example 1
        jQuery.wiggleSlide({
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
	
