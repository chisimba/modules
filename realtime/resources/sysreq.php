<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html> 
  <head>
    <applet 
      CODE="SystemProperties.class"
      NAME="SysProp" 
      ID="SysProp"
      WIDTH    = 1
      HEIGHT   = 1
      HSPACE   = 0
      VSPACE   = 0
      ALIGN    = "top">
      Java not enabled. Information will not be enabled.
    </applet>
    <title>System Test</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="info.css" />
    <script type="text/javascript">
    <!--
      function getStyleObject(objectId) {
        if(document.getElementById && document.getElementById(objectId)) {
      	  return document.getElementById(objectId).style;
        }
        else if (document.all && document.all(objectId)) {  
      	  return document.all(objectId).style;
        } 
        else if (document.layers && document.layers[objectId]) { 
      	  return document.layers[objectId];
        } 
        else {
      	  return false;
        }
      }
                
      function getObject(objectId) {
        if(document.getElementById && document.getElementById(objectId)) {
      	  return document.getElementById(objectId);
        }
        else if (document.all && document.all(objectId)) {  
      	  return document.all(objectId);
        } 
        else if (document.layers && document.layers[objectId]) { 
      	  return document.layers[objectId];
        } 
        else {
      	  return false;
        }
      }
               
      function changeObjectVisibility(objectId, newVisibility) {
        var styleObject = getStyleObject(objectId);
        if (styleObject) {
          styleObject.visibility = newVisibility;
    	  return true;
        } 
        else {
      	  return false;
        }
      }

      function ShowWarningLayer(LayerVisible, ContentsText) {
        var LayerID = "warning";
        changeObjectVisibility(LayerID, LayerVisible);
        var aLayer = getObject(LayerID);
        aLayer.innerHTML = "<table class=\"noborders\"><tr><td><img src=\"img/error.gif\" alt=\"error\" /></td><td>" + ContentsText + "</td></tr></table>";
      } 

      function enlight_popup(url,windowname) {
        width = 200;
        height = 100;
        var screenX = 0;
        var screenY = 0;        
        var features = "width=" + width + ",height=" + height;
        features += ",screenX=" + screenX + ",left=" + screenX;
        features += ",screenY=" + screenY  +",top=" + screenY;
        var mywin = window.open(url, windowname, features);
        if (mywin) { 
          mywin.moveTo(screenX, screenY);
          mywin.focus();
        }
        return mywin;
      }

    -->   
    </script> 

    <script type="text/javascript">
    <!--      
      var agt=navigator.userAgent.toLowerCase();
      var is_major = parseInt(navigator.appVersion);
      var is_minor = parseFloat(navigator.appVersion);
      var is_nav = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1) && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1)  && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
      var is_nav2 = (is_nav && (is_major == 2));
      var is_nav3 = (is_nav && (is_major == 3));
      var is_nav4 = (is_nav && (is_major == 4));
      var is_nav4up = (is_nav && (is_major >= 4));
      var is_navonly = (is_nav && ((agt.indexOf(";nav") != -1) || (agt.indexOf("; nav") != -1)));
      var is_nav6 = (is_nav && (is_major == 5));
      var is_nav6up = (is_nav && (is_major >= 5));
      var is_gecko = (agt.indexOf('gecko') != -1);
      var is_ie = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
      var is_ie3 = (is_ie && (is_major < 4));
      var is_ie4 = (is_ie && (is_major == 4) && (agt.indexOf("msie 4")!=-1));
      var is_ie4up = (is_ie && (is_major >= 4));
      var is_ie5 = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.0")!=-1));
      var is_ie5_5 = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.5") !=-1));
      var is_ie5up = (is_ie && !is_ie3 && !is_ie4);
      var is_ie5_5up =(is_ie && !is_ie3 && !is_ie4 && !is_ie5);
      var is_ie6 = (is_ie && (is_major == 4) && (agt.indexOf("msie 6.")!=-1));
      var is_ie6up = (is_ie && !is_ie3 && !is_ie4 && !is_ie5 && !is_ie5_5);
      var is_ie7 = (is_ie && (is_major == 4) && (agt.indexOf("msie 7.")!=-1));
      var is_aol = (agt.indexOf("aol") != -1);
      var is_aol3 = (is_aol && is_ie3);
      var is_aol4 = (is_aol && is_ie4);
      var is_aol5 = (agt.indexOf("aol 5") != -1);
      var is_aol6 = (agt.indexOf("aol 6") != -1);
      var is_opera = (agt.indexOf("opera") != -1);
      var is_opera2 = (agt.indexOf("opera 2") != -1 || agt.indexOf("opera/2") != -1);
      var is_opera3 = (agt.indexOf("opera 3") != -1 || agt.indexOf("opera/3") != -1);
      var is_opera4 = (agt.indexOf("opera 4") != -1 || agt.indexOf("opera/4") != -1);
      var is_opera5 = (agt.indexOf("opera 5") != -1 || agt.indexOf("opera/5") != -1);
      var is_opera5up = (is_opera && !is_opera2 && !is_opera3 && !is_opera4);
      var is_webtv = (agt.indexOf("webtv") != -1); 
      var is_TVNavigator = ((agt.indexOf("navio") != -1) || (agt.indexOf("navio_aoltv") != -1)); 
      var is_AOLTV = is_TVNavigator;
      var is_hotjava = (agt.indexOf("hotjava") != -1);
      var is_hotjava3 = (is_hotjava && (is_major == 3));
      var is_hotjava3up = (is_hotjava && (is_major >= 3));
      var is_ff = (agt.indexOf("firefox") != -1);
      var is_safari = (agt.indexOf("safari") != -1);
      var is_js;
      if (is_nav2 || is_ie3) is_js = 1.0;
      else if (is_nav3) is_js = 1.1;
      else if (is_opera5up) is_js = 1.3;
      else if (is_opera) is_js = 1.1;
      else if ((is_nav4 && (is_minor <= 4.05)) || is_ie4) is_js = 1.2;
      else if ((is_nav4 && (is_minor > 4.05)) || is_ie5) is_js = 1.3;
      else if (is_hotjava3up) is_js = 1.4;
      else if (is_nav6 || is_gecko) is_js = 1.5;
      else if (is_nav6up) is_js = 1.5;
      else if (is_ie5up) is_js = 1.3
      else is_js = 0.0;
      var is_win = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
      var is_win95 = ((agt.indexOf("win95")!=-1) || (agt.indexOf("windows 95")!=-1));
      var is_win16 = ((agt.indexOf("win16")!=-1) || (agt.indexOf("16bit")!=-1) || (agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("windows 16-bit")!=-1) );  
      var is_win31 = ((agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("win16")!=-1) || (agt.indexOf("windows 16-bit")!=-1));
      var is_winme = ((agt.indexOf("win 9x 4.90")!=-1));
      var is_win2k = ((agt.indexOf("windows nt 5.0")!=-1));
      var is_winxp = ((agt.indexOf("windows nt 5.1")!=-1));
      var is_win98 = ((agt.indexOf("win98")!=-1) || (agt.indexOf("windows 98")!=-1));
      var is_winnt = ((agt.indexOf("winnt")!=-1) || (agt.indexOf("windows nt")!=-1));
      var is_win32 = (is_win95 || is_winnt || is_win98 || ((is_major >= 4) && (navigator.platform == "Win32")) || (agt.indexOf("win32")!=-1) || (agt.indexOf("32bit")!=-1));
      var is_os2 = ((agt.indexOf("os/2")!=-1) || (navigator.appVersion.indexOf("OS/2")!=-1) || (agt.indexOf("ibm-webexplorer")!=-1));
      var is_mac = (agt.indexOf("mac")!=-1);
      if (is_mac && is_ie5up) is_js = 1.4;
      var is_mac68k = (is_mac && ((agt.indexOf("68k")!=-1) || (agt.indexOf("68000")!=-1)));
      var is_macppc = (is_mac && ((agt.indexOf("ppc")!=-1) || (agt.indexOf("powerpc")!=-1)));
      var is_mac_osx = (is_mac && ((agt.indexOf("os x")!=1)));
      var is_sun = (agt.indexOf("sunos")!=-1);
      var is_sun4 = (agt.indexOf("sunos 4")!=-1);
      var is_sun5 = (agt.indexOf("sunos 5")!=-1);
      var is_suni86= (is_sun && (agt.indexOf("i86")!=-1));
      var is_irix = (agt.indexOf("irix") !=-1);    // SGI
      var is_irix5 = (agt.indexOf("irix 5") !=-1);
      var is_irix6 = ((agt.indexOf("irix 6") !=-1) || (agt.indexOf("irix6") !=-1));
      var is_hpux = (agt.indexOf("hp-ux")!=-1);
      var is_hpux9 = (is_hpux && (agt.indexOf("09.")!=-1));
      var is_hpux10= (is_hpux && (agt.indexOf("10.")!=-1));
      var is_aix = (agt.indexOf("aix") !=-1);      // IBM
      var is_aix1 = (agt.indexOf("aix 1") !=-1);    
      var is_aix2 = (agt.indexOf("aix 2") !=-1);    
      var is_aix3 = (agt.indexOf("aix 3") !=-1);    
      var is_aix4 = (agt.indexOf("aix 4") !=-1);    
      var is_linux = (agt.indexOf("inux")!=-1);
      var is_sco = (agt.indexOf("sco")!=-1) || (agt.indexOf("unix_sv")!=-1);
      var is_unixware = (agt.indexOf("unix_system_v")!=-1); 
      var is_mpras = (agt.indexOf("ncr")!=-1); 
      var is_reliant = (agt.indexOf("reliantunix")!=-1);
      var is_dec = ((agt.indexOf("dec")!=-1) || (agt.indexOf("osf1")!=-1) || (agt.indexOf("dec_alpha")!=-1) || (agt.indexOf("alphaserver")!=-1) || (agt.indexOf("ultrix")!=-1) || (agt.indexOf("alphastation")!=-1)); 
      var is_sinix = (agt.indexOf("sinix")!=-1);
      var is_freebsd = (agt.indexOf("freebsd")!=-1);
      var is_bsd = (agt.indexOf("bsd")!=-1);
      var is_unix = ((agt.indexOf("x11")!=-1) || is_sun || is_irix || is_hpux || is_sco ||is_unixware || is_mpras || is_reliant || is_dec || is_sinix || is_aix || is_linux || is_bsd || is_freebsd);
      var is_vms = ((agt.indexOf("vax")!=-1) || (agt.indexOf("openvms")!=-1));
      var screenHeight = screen.height;
      var screenWidth = screen.width;
      var colorResolution = screen.colorDepth;
      //document.write(agt);
    -->   
    </script> 
  </head>

  <body>
    <div class="headerbg">
    </div>
    <div>    
    <div id="warning"><h1>System Requirements Check</h1>
    </div>
    
    <script type="text/javascript">
      <!--
        var myTestWin = enlight_popup('test.htm','test');
        var applet = document.applets["SysProp"];
	var javaVersion = undefined; 
        
	var osVersion;
	var osName;
	var attempt = 0;	
        while (attempt < 10) {
          getProps();
          attempt++;
        }
        function getProps() {
          try {
	    if (applet != undefined) {
              javaVersion=applet.getJavaVersion();
              osVersion=applet.getOsVersion();
              osName=applet.getOsName();
	    }
          }
          catch(err) {

          }
        } 
      -->
    </script>

    <p>
    The following shows whether your computer is able to run the realtime virtual classroom.
    </p>

    <div id="info-table">

      <table id="mainbody">
  
        <!--******************************************************************-->
        <!--**************************Operating System************************-->
        <!--******************************************************************--> 
        <tr valign=top>
          <td width="200" class="column1-text">
            Operating System:
          </td>
          <td width="300"  class="column2-text">
            <script type="text/javascript">
              if (is_win95) {
                document.write("Windows 95");
              }
              else if (is_winme) {
                document.write("Windows ME");
              }
              else if (is_win2k) {
                document.write("Windows 2000");
              }
              else if (is_winxp) {
                document.write("Windows XP");
              }
              else if (is_win98) {
                document.write("Windows 98");
              }
              else if (is_winnt) {
                document.write("Windows NT");
              }
              else if (is_os2) {
                document.write("OS2");
              }
              else if (is_mac) {
                document.write("Mac");
		if (is_mac_osx) {
		  document.write(" OS X");
		}
              }
              else if (is_sun) {
                document.write("SUN");
              }
              else if (is_irix) {
                document.write("Irix");
              }
              else if (is_hpux) {
                document.write("HPUX");
              }
              else if (is_aix) {
                document.write("AIX");
              }
              else if (is_linux) {
                document.write("Linux");
              }
              else if (is_sco) {
                document.write("SCO");
              }
              else if (is_freebsd) {
                document.write("FreeBSD");
              }
              else if (is_bsd) {
                document.write("BSD");
              }
              else if (is_unix) {
                document.write("Unix");
              }
              else if (is_vms) {
                document.write("VMS");
              }
            </script>

          </td>
          <td width="40"  class="column3">
            <script type="text/javascript">
              if (is_win2k || is_winxp || is_win98) {
                document.write('Supported');
              }

              else if (is_sun) {
                document.write('Supported');
              }

              else if (is_freebsd) {
                document.write('Supported');
              }
              else if (is_linux) {
                document.write('Supported');
              }
              else if (is_mac_osx) {
                document.write('Supported');
              }

              else {
                document.write("Not supported")
              }
            </script>
          </td>


        </tr>
  
        <!--******************************************************************--> 
        <!--*****************************Resolution***************************-->
        <!--******************************************************************--> 
        <tr valign=top>
          <td width="200" class="column1-text">
              Screen Resolution:
          </td>
          <td width="300"  class="column2-text">
            <script type="text/javascript">
              document.write(screenWidth + "x" + screenHeight);
            </script>

          </td>
          <td width="40"  class="column3">
            <script type="text/javascript">
              if ((screenWidth >= 800) && (screenHeight >= 600)) {
                document.write("Supported");
              }
              else {
                document.write("Please set your screen resolution to at least 800x600");
              }    
            </script>
          </td>
        </tr>

  
        <!--**************************************************************-->
        <!--**************************Browser Name************************-->
        <!--**************************************************************-->
        <tr valign=top>
          <td width="200" class="column1-text">
            Browser name:
          </td>
          <td width="300"  class="column2-text">
            <script type="text/javascript">
              if (navigator.appName == "Netscape") {
                if (is_ff) {
                  document.write("Firefox");                       
                }
                else if (is_safari) {
		  document.write("Safari");
                } 
              }
	      else {    
                document.write(navigator.appName);
              }
            </script>
          </td>

          <td width="40"  class="column3">
            <script type="text/javascript">
              if (is_ie) {
                document.write("Supported");
              }
                else if (is_ff) {
                document.write("Supported");
              }
                  else if (is_opera) {
                document.write("Supported");
              }
                    
              else if (is_safari) {
                document.write("Supported");
              }
              else {
                document.write("The web browser in use is not supported. Please use Firefox, Opera, Safari or Internet Explorer");
              }
            </script>
          </td>
          </td>
        </tr>
  
        <!--*****************************************************************-->

        <!--**************************browser version************************-->
        <!--*****************************************************************-->
        <tr valign=top>
          <td width="200" class="column1-text">
            Browser Version:
          </td>
          <td width="300"  class="column2-text">
            <script type="text/javascript">
  	      if (is_ie3) {
                document.write('3.0');
              }
              else if (is_ie4) {
                document.write('4.0');
              }                 
              else if (is_ie5) {
                document.write('5.0');
              }                 
              else if (is_ie5_5) {
                document.write('5.5');
              }                 
              else if (is_ie6) {
                document.write('6.0');
              }                 
              else if (is_ie7) {
                document.write('7.0');
              }
	      else if (is_ff) {
		var pos = agt.indexOf('firefox');
		var ver = "unknown";
		if (pos != -1) {
		  ver = agt.substr(pos+8);
		  document.write(ver);
		}
	      }
	      else if (is_safari) {
		var pos = agt.indexOf('safari');
		var ver = "unknown";
		if (pos != -1) {
		  ver = agt.substr(pos+7);
		  document.write(ver);
		}
	      }
              else {
		document.write(is_major);
                document.write('&nbsp;'); 
              }            
            </script>
          </td>

          <td width="40"  class="column3">
            <script type="text/javascript">
              if (is_ie5_5up) {
	        document.write("Supported");
              }
              else if (is_safari) {
                document.write("Supported");
              }
          
    else if (is_ff) {
                document.write("Supported");
              }
              else {
                document.write("The web browser in use is not supported. Please use Firefox, Opera, Safari or Internet Explorer");
              }
            </script>
          </td>
        </tr>
  
        <!--**************************************************************-->

        <!--**************************java version************************-->
        <!--**************************************************************-->
        <tr valign=top>
          <td width="200" class="column1-text">
            Java Version:
          </td>
          <td width="300"  class="column2-text">
            <script type="text/javascript">
	      var javaOk = 0;
	      if (javaVersion) {
		document.write(javaVersion);
	      }
	      else {
		document.write("No Java was found");
	      }
            </script>
          </td>

          <td width="40"  class="column3">
            <script type="text/javascript">
              var supportedJavaVersionsTxt = 'The Java version in use is not supported. Please use Sun Java 1.5 or 1.6';
              if (javaVersion) {
		if (javaVersion == "1.1.4") {
                  document.write(supportedJavaVersionsTxt);
		}
                else if ((javaVersion.substring(0,5) == "1.5.0") || 
		         (javaVersion.substring(0,5) == "1.6.0")) {
                  var subver = "";
		  if (javaVersion.length > 5)
		      subver = javaVersion.substring(6,8);
                  var myInt = 0;
                  try {
		    /* cannot use parseInt because 09 is not an octal number and will fail. */
		    var i;
                    for (i = 0; i < subver.length; i++) {
		        myInt += myInt*10+subver.charCodeAt(i) - '0'.charCodeAt(0);
		    }
                  } 
                  catch (err) {
		    document.write(err);
                  }

                  if (javaVersion.substring(0,5) == "1.4.2") {
		    javaOk = 0;
                      document.write(supportedJavaVersionsTxt);
                    
                  }
                  else if (javaVersion.substring(0,5) == "1.5.0") {
		    javaOk = 1;
                    if ((myInt<=3) || (myInt>10)) {
                      document.write(supportedJavaVersionsTxt);
                    }
                  }
		  else if (javaVersion.substring(0,5) == "1.6.0") {
		    javaOk = 1;
		  }
                }
              }
	      if (javaOk) {
		document.write("Supported");
              }
              else {
                document.write("<a href=\"java.com\">Get Java</a>");
              }                
            </script>
          </td>
          <td width="240"  class="column4">
            <script type="text/javascript">
	      if (javaOk) {
                document.write('&nbsp;');                                                 
              } 
              else {
                document.write(supportedJavaVersionsTxt);
              }                
            </script>
          </td>
        </tr>
  
        <!--*********************************************************************-->

        <!--**************************java script enabled************************-->
        <!--*********************************************************************-->
        <tr valign=top>
          <td width="200" class="column1-text">
            Javascript enabled:
          </td>
          <td width="300" class="column2-text">
            <script type="text/javascript">document.write('Yes');</script>                                                   
            <noscript>
              No
            </noscript>

          </td>
          <td width="40"  class="column3">
            <script type="text/javascript">
              document.write("Supported");
            </script>                     
          </td>

        </tr>

        <!--*********************************************************************-->
        <!--*****************************Pop-up enabled**************************-->
        <!--*********************************************************************-->
        <tr valign=top>
          <td width="200" class="column1-text">
            Pop-ups enabled:
          </td>

          <td width="300" class="column2-text">
            <script type="text/javascript">              
              if (myTestWin != null) {
                document.write('Yes');
              }
              else {
                document.write('No');
              }
            </script>                                                   
          </td>
          <td width="40"  class="column3">
            <script type="text/javascript">
              if (myTestWin != null) {
                document.write("Supported");
              }
              else {
                document.write("Enable popus");
              }             
            </script>                                                   
          </td>
         

        </tr>
      </table>
      <br />
      <table class="noborders">
      <tr><td class="noborders">
      
      <script>
 
        if (javaOk) {
		document.write("<b><font color=\"green\">Your system has PASSED minimum requirements test and can run the virtual classroom.<br>");
	        document.write("For 100% support, please make sure all the above are Supported</font>");
                
             
      }
              else {
                document.write("<font color=\"red\">Your system has FAILED minimum requirements test and CANNOT run the virtual classroom.<font\><font color=\"green\"> <a href=\"java.com\">Get Java</a></font>");
              }                
     </script>
<br><br>
<?
  // <a href="#">Back</a>
    $ref = getenv("HTTP_REFERER");
    echo '<a href="'.$ref.'">Back</a>';
  ?>    </td></tr>
      </table>
    </div>
    </div>
    <script type="text/javascript">
      try {
//        myTestWin.close();
      }
      catch (err) {

      }
    </script> 
  </body>

</html>

