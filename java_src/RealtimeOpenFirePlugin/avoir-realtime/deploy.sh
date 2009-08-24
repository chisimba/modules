cp ../dist/RealtimeOpenFirePlugin.jar /dwaf/projects/RealtimeOpenFirePlugin/avoir-realtime/lib
jar cvf avoirrealtime.jar .
cp avoirrealtime.jar /dwaf/software/openfire/plugins/
#scp /dwaf/core-software/openfire/plugins/avoirrealtime.jar pscott@kim.wits.ac.za:/home/pscott
