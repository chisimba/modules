Title:  Editing a table
Author: Jorge Solis - solisarg@flash-db.com

Description of files:

setup.php: run this script in your browser to create needed database and tables
gateway.php: this is the gateway for the remoting services. Change path if needed to match your envinronment
list.sql: if you're creating the database trough phpMyAdmin or some other database manager
edit.fla: the main flash file (Flash MX 2004 Pro needed) for editing and updating data
show.fla: a movie to show updated data (Flash MX 2004 needed)
readme.txt: this file
inside services folder:
inc_sql.php: here you need to put your Database name, Host name , Username and Password
-- > Change: some versions seems to have problems with include, so Database, Host, Username and Pass is now included also in the class. modify to match your needs also in products.php
products.php: this is the base class for the Remoting services


Quick setup:

1. Unzip all the files to some folder under your root web folder, at same level as your flashservices folder
2. Open inc_sql.php and change Database, Host, Username and Password to match your needs
3. Run setup.php to create Database and table (or use list.sql trough phpMyAdmin)
4. Publish the two flash movies

Other resources:
PHP.net - Complete online PHP Documentation
mySQL.com - Information regarding mySQL
amfphp.org - amfphp Homepage

Post your questions on http://www.flash-db.com/Board/