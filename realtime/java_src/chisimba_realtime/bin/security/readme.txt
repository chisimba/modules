Instructions for generating a new keystore:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1. Open a shell/terminal and navigate to this folder
2. Remove the existing "keystore" file
3. Make any necessary adjustments to keystore.conf.
4. Run the following from the command line:

	keytool -genkey -alias realtime -keystore keystore < keystore.conf
	
Note: This creates a self-signed key which can be used for testing and 
demonstration purposes but a proper key issued by a certfication authority 
should be used in production environments.

Example:
keytool -genkey -dname "cn=Presentations, ou=Software Development Unit, o=Univeristy of Western Cape JKUAT, c=KE" -alias realtime -keypass changeme -keystore keystore -storepass changeme -validity 180
