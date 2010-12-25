function stripHTML(source){
	var strippedText = source.replace(/<\/?[^>]+(>|$)/g, "");
	return strippedText;
}

function replaceURLWithHTMLLinks(source) {
  var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
   replaced = source.replace(exp,"<a href='$1' target='_blank'>$1</a>"); 
   return replaced;
}