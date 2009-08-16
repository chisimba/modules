/dwaf/core-software/apache-ant-1.7.1/bin/ant -f deploy.xml deploy

jarsigner -keystore keystore deploy/realtime-xmpp.jar  avoir
#cp lib/realtime-xmpp.jar /var/www/chisimba-realtime-1.0.2/
cp deploy/realtime-xmpp.jar /var/www/chisimba/packages/realtime/resources/
#scp deploy/realtime-xmpp.jar  pscott@kim.wits.ac.za:/home/pscott
