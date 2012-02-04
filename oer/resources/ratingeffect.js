		$(function(){
			$("#rat").children().not(":radio").hide();
			
			// Create stars
			$("#rat").stars({
				// starWidth: 28, // only needed in "split" mode
				cancelShow: false,
				callback: function(ui, type, value)
				{
					// Hide Stars while AJAX connection is active
					$("#rat").hide();
					$("#loader").show();
					// Send request to the server using POST method
					/* NOTE: 
						The same PHP script is used for the FORM submission when Javascript is not available.
						The only difference in script execution is the returned value. 
						For AJAX call we expect an JSON object to be returned. 
						The JSON object contains additional data we can use to update other elements on the page.
						To distinguish the AJAX request in PHP script, check if the $_SERVER['HTTP_X_REQUESTED_WITH'] header variable is set.
						(see: demo6.php)
					*/ 
					$.post("demo6.php", {rate: value}, function(db)
					{
						// Select stars to match "Average" value
						ui.select(Math.round(db.avg));
						
						// Update other text controls...
						$("#avg").text(db.avg);
						$("#votes").text(db.votes);
						
						// Show Stars
						$("#loader").hide();
						$("#rat").show();

					}, "json");
				}
			});
		});
