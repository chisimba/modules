#!/bin/bash
# This script will clone a Chisimba site to create an new 
# installable copy  without requiring another copy of the
# code.

# Setup the first line of output
echo "---------------------------------------------------"
echo "Cloning a Chisimba installation using command: "
echo "$0 $*"
echo
echo

# CLONEBASE is the directory into which to create the site
# This is supplied as the first parameter 
ClONEBASE=$1

# CHISIMBA_FRAMEWORK is the location of the Chisimba framework app directory
# e.g. /home/dkeats/eclipse-workspace/Chisimba/chisimba_framework/app/
# This is supplied as the second parameter
CHISIMBA_FRAMEWORK=$2

# CHISIMBA_MODULES is the location of the Chisimba modules directory (chisimba_modules)
# e.g. /home/dkeats/eclipse-workspace/Chisimba/chisimba_modules/
# This is supplied as the third parameter
CHISIMBA_MODULES=$3

# Retrieve the site code for the site to be created
CLONENAME=$4

# Go into the directory identified by CLONEBASE
echo "Change directory to ${ClONEBASE}"
# bash check if directory exists
if [ -d $ClONEBASE ]; then
	cd $ClONEBASE
	# Create the directory required for the clone
	if [ -d $CLONENAME ]; then
        echo "A directory called ${CLONENAME} already exists. Stopping creating this site for security reasons..."
        exit
    else
        echo "Create directory called ${CLONENAME}"
        mkdir $CLONENAME
        chmod 777 $CLONENAME -R    
    fi
else 
	echo "Directory ${ClONEBASE} does not exists. Aborting..."
	exit
fi

# Go into the directory
echo "Going into directory ${CLONENAME}"
cd $CLONENAME

# Create the directories needed by the new site
echo "Creating the directories required by the new site"
echo "Creating skins"
mkdir skins
chmod 777 skins -R

# Create the symlinks
echo "Creating symbolic links for the new site"
echo
ln -s ${CHISIMBA_FRAMEWORK}classes classes
ln -s ${CHISIMBA_FRAMEWORK}core_modules core_modules
ln -s ${CHISIMBA_FRAMEWORK}cron cron
ln -s ${CHISIMBA_FRAMEWORK}installer installer
ln -s ${CHISIMBA_FRAMEWORK}lib lib
ln -s ${CHISIMBA_FRAMEWORK}index.php index.php
ln -s ${CHISIMBA_FRAMEWORK}gateway.php gateway.php
ln -s ${CHISIMBA_FRAMEWORK}_htaccess _htaccess
ln -s ${CHISIMBA_FRAMEWORK}robots.txt robots.txt
# Link in the packages
ln -s ${CHISIMBA_MODULES} packages

echo "Creating the skins"
cd skins
ln -s ${CHISIMBA_FRAMEWORK}skins/_common _common
ln -s ${CHISIMBA_FRAMEWORK}skins/echo echo

echo
chmod 777  ${ClONEBASE}${CLONENAME} -R
echo
echo "Site creation completed."
