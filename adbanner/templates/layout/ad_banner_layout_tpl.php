body{
	font-family: Vrinda, Arial;
	font-size:20px;
}

#root{
	background-color: #FFFFFF;

	display:block;
	width: 800px;
	height:600px;
	padding:5px;
	color: #333;
	font-family: Vrinda, Arial;
	font-size:20px;
}

#top_layer{
	/*background-color: #AAAAFF;*/
	width:100%;
	height:87px;
	font-family: Vrinda, Arial;
	font-size:20px;
/*	background-image:url('./images/speakers.png') no-repeat;*/
}

	#top_block{   /* X3 of these one top :-) */
		background-color: white;
		
		width:180px;
		height:100%;
		float:left;
	}

		#top_block_header{  
			/*background-color: black;*/
			
			width:100%;
			height:30px;
			float:left;
			text-align:left;
		}

		#top_block_item{
			background-color: white;
			color:white;
			
			width:100%;
			height:57px;
			float:left;
			
			text-align:left;
		}

	#top_block_right{
		background-color: #EEEEEE;
		
		width:258px;
		height:100%;
		float:right;
	}

		#signup_top_right{   
			/*background-color: #123456;*/
			
			position:relative;
			top:-23px;
			right:-73px;
			width:95px;
			height:23px;
			
			/*background-image: url('images/signup_1.gif');*/
			/*cursor:pointer;*/
		}

#header_layer{
	/*background-color: #FAAAAA;*/
	
	width:100%;
	height:155px;
	font-family: Vrinda, Arial;
	font-size:20px;
	padding-top:3px;
}

	#header_speakers{
		/*background-color: blue;*/
		width:162px;
		height:151px;
		float:left;
		background-image:url('images/speakers_162x151.gif');
	}

	#header_text{
		/*background-color: #2EEC33;*/
		width:420px;
		height:155px;
		float:left;
		background-image:url('images/localbands_text_420x155.gif');
	}

	#header_artist{
		/*background-color: black;*/
		width:218px;
		height:155px;
		float:right;
		background-image:url('images/artist.gif');
	}

#search_layer{
	background-color: #FFFFFF;
	
	width:100%;
	height:30px;
	font-family: Vrinda, Arial;
	font-size:20px;
	
}

#ad_layer{
	background-color: #AACCFF;
	
	width:100%;
	height:84px;
	font-family: Vrinda, Arial;
	font-size:20px;
	
	clear:right;
}

		#ad_item{   /* X3 of these one top :-) */
			background-color: #AACCFF;
			color:white;
			
			position:relative;
			top:-27px;
			left:2px;
			width:782px;
			height:70px;
			float:left;
			margin-left:5px;
			
			text-align:left;
			z-index:1;
		}


#content_container{
	position:relative;
	top:-15px;
	
	width:100%;
	height:100%;
}
		
#content_left{
	background-color: #864FFE;
	
	background-image:url('./images/lbc_news_1.png') no-repeat; 
	font-family: Vrinda, Arial;
	font-size:20px;
	text-align:left;
	width:533px;
	height:100%;
	padding-top:10px;
	padding-left:6px;
	padding-right:6px;
	float:left;
}

	#content_left_header{
		background-color: #55DDDD;
		
		width:100%;
		height:201px;
	}

	#content_left_item{
		background-color: #DDDDDD;
		
		width:100%;
		height:86px;
		
		padding-top:10px;
	}


#content_right{
	background-color: #81B00E;
	
	background-image:url('./images/lbc_news_1.png') no-repeat; 
	font-family: Vrinda, Arial;
	font-size:20px;
	text-align:left;
	width:245px;
	height:100%;
	padding-top:10px;
	padding-left:5px;
	padding-right:5px;
	float:right;
}

	#content_right_header{
		background-color: #55DDDD;
		
		width:100%;
		height:78px;
	}

	#content_right_item{
		background-color: #DDDDDD;
		
		width:100%;
		height:72px;
		padding-top:5px;
	}


#amp{
	/*background-color: #DD2A2A;*/
	width:215px;
	height:178px;
	border-bottom: 0px;
	font-family: Vrinda, Arial;
	float:right;
}


#h_layer3{
	background-color: #EA2EEC;
	width:100%;
	height:10px;
	font-family:  Vrinda, Arial;
	font-size: 20px;
}

#footer{
   background-color: #FFFFFF;
   color: #333;
   float:left;
   padding-left:4em;
   z-index:1;
}

#footer a{
   font-size:19px;
   color: #333;
   z-index:1;
}

#footer a:hover {
	background-color: #FFFFFF;
	color:#0C74BE;
}

#content{
	background-color: #00FF00;
	margin-left: 180px;
	border-left: 0px;
	margin-right: 0px;
	border-right: 10px;
	padding: 1em;
	width: 400;
	z-index:0;
}

#left_menu{
	float: left;
	width: 120px;
	margin: 0;
	padding: 1em;
	background-color: #0000FF;
}

#right_menu{
	float: right;
	width: 160px;
	margin: 0;
	padding: 1em;
	background-color: #FF0000;
}

#log{
	float: right;
}

#fl_right{
	float: right;
}

#v_spacer{
	height:3px;
	width:100%;
}