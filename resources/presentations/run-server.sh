#! /bin/sh

#   Copyright (c) 2007 AVOIR 

# load system-wide realtime configuration
if [ -z "$JAVACMD" ] ; then
  if [ -n "$JAVA_HOME"  ] ; then
    if [ -x "$JAVA_HOME/jre/sh/java" ] ; then
      # IBM's JDK on AIX uses strange locations for the executables
      JAVACMD="$JAVA_HOME/jre/sh/java"
    else
      JAVACMD="$JAVA_HOME/bin/java"
    fi
  else
    JAVACMD=`which java 2> /dev/null `
    if [ -z "$JAVACMD" ] ; then
        JAVACMD=java
    fi
  fi
fi

if [ ! -x "$JAVACMD" ] ; then
  echo "Error: JAVA_HOME is not defined correctly."
  echo "  We cannot execute $JAVACMD"
  exit 1
fi
  # exec "$JAVACMD"  -classpath forms-1.1.0.jar:resolver.jar:xercesImpl.jar:presentations-server.jar:serializer.jar:xml-apis.jar  avoir.realtime.presentations.server.Server
exec java  -classpath forms-1.1.0.jar:resolver.jar:xercesImpl.jar:presentations-server.jar:serializer.jar:xml-apis.jar  avoir.realtime.presentations.server.Server
