#/dwaf/core-software/apache-ant-1.7.1/bin/ant deploy
cp ../dist/realtime102.jar .
jarsigner -keystore keystore realtime102.jar  avoir
#cp lib/realtime-xmpp.jar /var/www/chisimba-realtime-1.0.2/
cp realtime102.jar /var/www/chisimba-realtime/
#scp lib/realtime-xmpp.jar  pscott@kim.wits.ac.za:/home/pscott
