var count = 0;
var app = {
   url: '/lico0.01/messaging',
  
   listen: function() {
      $('comet-frame').src = app.url + '?' + count;
      count ++;
   },
   initialize: function() {
    
      $('system-message').style.color = '#2d2b3d';
      var query =
	 'action=login';
     
      new Ajax.Request(app.url, {
	 postBody: query,
	 onSuccess: function() {
	    $('system-message').focus();
	 }
      });
   },
   
   update: function(data) {
      $('system-message').innerHTML=data.message;
   }
};

var rules = {
   '#login-name': function(elem) {
      Event.observe(elem, 'keydown', function(e) {
	 if(e.keyCode == 13) {
	    $('login-button').focus();
	 }
      });
   },
   '#login-button': function(elem) {
      elem.onclick = app.login;

   },
   '#message': function(elem) {
      Event.observe(elem, 'keydown', function(e) {
	 if(e.shiftKey && e.keyCode == 13) {
	    $('post-button').focus();
	 }
      });
   },
   '#post-button': function(elem) {
      elem.onclick = app.post;
   }
};
Behaviour.addLoadEvent(app.initialize);
