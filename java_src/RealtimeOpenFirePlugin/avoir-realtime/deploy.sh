cp /dwaf/core-projects/realtime-xmpp/dist/realtime-xmpp.jar /dwaf/core-projects/RealtimeOpenFirePlugin/avoir-realtime/lib
cp ../dist/RealtimeOpenFirePlugin.jar /dwaf/core-projects/RealtimeOpenFirePlugin/avoir-realtime/lib
jar cvf avoirrealtime.jar .
cp avoirrealtime.jar /dwaf/core-software/openfire/plugins/
#scp /dwaf/core-software/openfire/plugins/avoirrealtime.jar pscott@kim.wits.ac.za:/home/pscott
