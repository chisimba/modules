/*----------------
 WIGGLE SLIDE v1.0
 By Jordan Boesch
 www.boedesign.com
 
 Modified by Charl Joseph Mert (For Chisimba Ad Banner Module):
 -> Added Auto Updating
 -> Added Explicit Image Set
 
-----------------*/

(function($) {
      
	$.wiggleSlide = function(opt) {
		
		// get the options
        var sliderNav = opt.sliderNav; 
        var sliderClickLink = opt.sliderClickLink; 
        var hiddenContainer = opt.hiddenContainer;
        var sliderItemClassName = opt.sliderItemClassName; 
        var swingSpeed = opt.swingSpeed; 
        var wiggle = opt.wiggle;
		var permalinking = opt.permalinking;
		var static_image_id = opt.static_image_id;
       
	   	// defaults
	   	if(sliderNav == null || sliderNav == '') sliderNav = '#slider-nav';
		if(sliderClickLink == null || sliderClickLink == '') sliderClickLink = 'a';
		if(hiddenContainer == null || hiddenContainer == '') hiddenContainer = '#invis-cont';
		if(sliderItemClassName == null || sliderItemClassName == '') sliderItemClassName = 'slider-item';
		if(swingSpeed == null || swingSpeed == '') swingSpeed = "medium";
		if(wiggle == null || wiggle == '') wiggle = true;
		if((permalinking == null || permalinking == '') && permalinking != false){
			(sliderClickLink == 'a') ? permalinking = true : permalinking = false;
		}
		
        // don't touch below this line unless you are a true john resig
        var sliderItemWidth = getSliderWidth(sliderItemClassName);
        var sliderContainerWidth = (sliderItemWidth) * $("."+sliderItemClassName).length;
      	$(hiddenContainer).css('width',sliderContainerWidth);

        var relTitle;
        (sliderClickLink == 'a') ? relTitle = 'rel' : relTitle = 'title';
       
        // if they've entered something into the #, they want to see something specific
        if(window.location.hash && permalinking){
       
           var a = window.location.hash.slice(1);
           var countLink = 0;
           
		   $("."+sliderItemClassName).each(function(){
				countLink++;									
				if($(this).attr('id') == 'slider-'+a){
					var sliderContStart = (sliderItemWidth) * countLink - sliderItemWidth;
                    $(hiddenContainer).css('left',-sliderContStart+'px');	
				}
			});
           
        }
	   
        // lets calculate the padding/margin offsets (if applicable)
        function getSliderWidth(sliderItemClassName){
           
            var sliderClass = $("."+sliderItemClassName);
            var sliderClassWidth = parseInt(sliderClass.width());
            var paddingLeft = sliderClass.css('padding-left').split('px')[0];
            var paddingRight = sliderClass.css('padding-right').split('px')[0];
            var marginLeft = sliderClass.css('margin-left');
            var marginRight = sliderClass.css('margin-right');
           
            // IE6/7 are silly about this
            (marginLeft == 'auto') ? marginLeft = 0 : marginRight = marginRight.split('px')[0];
            (marginRight == 'auto') ? marginRight = 0 : marginRight = marginRight.split('px')[0];
           
            var marginsPaddings = parseInt(marginLeft) + parseInt(marginRight) + parseInt(paddingLeft) + parseInt(paddingRight);
            var totalUp = marginsPaddings + sliderClassWidth;
            return totalUp;
           
        }
       
        function bobble(e,lastPos,newPos){
           
            if(wiggle){
           
                var wobbleOffset = 5;
               
                if(lastPos > newPos){
                    $(e).animate({left:(newPos+wobbleOffset)+'px'},{duration: "fast", easing: 'swing'});
                    $(e).animate({left:(newPos)+'px'},{duration: "fast", easing: 'swing'});
                }
                else {
                    $(e).animate({left:(newPos-wobbleOffset)+'px'},{duration: "fast", easing: 'swing'});
                    $(e).animate({left:(newPos)+'px'},{duration: "fast", easing: 'swing'});
                }
               
            }
           
        }
		
		function getSlidePos(slideId){
			
			var numberPos = 0;
			var ret;
			
			$("."+sliderItemClassName).each(function(){
				numberPos++;
				if($(this).attr('id') == slideId){
					ret = numberPos;
				}
			});
			
			return ret;
			
		}
       
        $(sliderNav+" "+sliderClickLink).each(function(){

			if(permalinking) $(this).attr('href','#'+$(this).attr('id'));
			
			if (static_image_id == '' || static_image_id == null){
				$(this).click(function(){
					var sliderId = 'slider-'+$(this).attr('id');
					var slidePosition = getSlidePos(sliderId);
					// if they're on the current slide and they click it, don't move
						var lastPos = $(hiddenContainer).css('left').split('px')[0];
						newSlideNum = parseInt(slidePosition) * sliderItemWidth - sliderItemWidth;
						var newPos = -newSlideNum;
						$(hiddenContainer).animate({left: newPos+'px'},{duration: swingSpeed, easing: 'swing', complete: function(){ bobble(this,lastPos,newPos) } });
						if(!permalinking) return false;
					
				});
			} else {
					var sliderId = 'slider-'+static_image_id;
					var slidePosition = getSlidePos(sliderId);
					// if they're on the current slide and they click it, don't move
						var lastPos = $(hiddenContainer).css('left').split('px')[0];
						newSlideNum = parseInt(slidePosition) * sliderItemWidth - sliderItemWidth;
						var newPos = -newSlideNum;
						$(hiddenContainer).animate({left: newPos+'px'},{duration: swingSpeed, easing: 'swing', complete: function(){ bobble(this,lastPos,newPos) } });
						if(!permalinking) return false;
			}
        });
		
		//var global_delay = 2000;
		//setInterval("nextBanner('girl')",global_delay);
		//setTimeout("nextBanner('girl')",global_delay);
		//nextBanner('girl');

		function nextBanner(image_id){
		
			//Force to slide to specified image
			var sliderId = 'slider-'+image_id;
			var slidePosition = getSlidePos(sliderId);
			// if they're on the current slide and they click it, don't move
			var lastPos = $(hiddenContainer).css('left').split('px')[0];
			newSlideNum = parseInt(slidePosition) * sliderItemWidth - sliderItemWidth;
			var newPos = -newSlideNum;
			$(hiddenContainer).animate({left: newPos+'px'},{duration: swingSpeed, easing: 'swing', complete: function(){ bobble(this,lastPos,newPos) } });
			if(!permalinking) return false;
		}
		
		
	}
  
})(jQuery);