
#!/bin/sh

GetRealPath()
{
	pathName="$1"
	level=1

	while [ -h "$pathName" ] ; do
		level=`/bin/expr $level + 1`
		if [ $level -gt 10 ] ; then
			echo "$pathName: too many levels of symbolic links"
			break;
		fi

		#
		# First, make sure we have an absolute path name.
		#
		if IsRelativePath "$pathName" ; then
			pathName=`/bin/pwd`/"$pathName"
		fi

		#
		# Then determine where the link points (via "ls -l")
		#
		dirName=`/bin/dirname $pathName`
		link=`/bin/ls -l $pathName | sed -e 's,.* -> ,,g'`

		if IsRelativePath "$link" ; then
			pathName="$dirName"/"$link"
		else
			pathName="$link"
		fi
	done

	echo "$pathName"

	return 0
}

#
# Is this a relative path name (i.e., doesn't begin with "/")?
#
IsRelativePath()
{
	pathName="$1"

	if [ `echo "$pathName" | sed -e 's,^/.*,absolute,g'` = "absolute" ] ; then
		return 1
	else
		return 0
	fi
}
PROGPATH=`GetRealPath $0`
TMPDIR=`dirname $PROGPATH`
VHOME=`( cd $TMPDIR/.. && /bin/pwd )`; export VHOME

CLASSPATH=${VHOME}/lib/video.jar:${VHOME}/lib/jmf.jar:${VHOME}/lib:${VHOME}/lib/sound.jar:${CLASSPATH}; export CLASSPATH

LD_LIBRARY_PATH=.:/usr/openwin/lib:${VHOME}/lib:${LD_LIBRARY_PATH}; export LD_LIBRARY_PATH

# Use this to run with native threads:
# THREADS_FLAG=native; export THREADS_FLAG

# If it's running on Solaris 2.6, we are forced to use native threads.
UNAME=`uname -r`
case $UNAME in
5.6) THREADS_FLAG=native; export THREADS_FLAG;;
5.6.*) THREADS_FLAG=native; export THREADS_FLAG;;
5.7) THREADS_FLAG=native; export THREADS_FLAG;;
5.7.*) THREADS_FLAG=native; export THREADS_FLAG;;
5.8) THREADS_FLAG=native; export THREADS_FLAG;;
5.8.*) THREADS_FLAG=native; export THREADS_FLAG;;
esac

if [ $# -gt 0 ]; then
	FILE=$1	
fi

exec java avoir.realtime.video.transmitter.gui.JMFrame              
