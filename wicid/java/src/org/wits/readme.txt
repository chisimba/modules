configuration steps
==========

1. Copy package/wicid/java/src to the project dir
2. copy Wicid.html and Wicid.css into war dir
3. Change the script links to css and extjs in Wicid.html to the correct one
4. Copy web.xml into war/WEB-INF
5. Copy gxt.jar into war/WEB-INF/lib
6.Change gwt.sdk property in build.xml to correct path
7 change ChisimbaServlet.java line 28 to reflect correct Chisimba path
8. Change constants MAIN_URL patter to correcr path

compile: ant gwtc
