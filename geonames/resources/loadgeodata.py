
import fileinput
import time
import base64
import xmlrpclib
import os
import random
import sys
import zipfile
import os.path

#Define some variables (Change these to suit your circumstances!)
SERV = 'http://127.0.0.1/cpg/'
UNAME = 'admin'
PWORD = 'a'
APIENDPOINT = '/app/index.php?module=api'
# -- END EDITABLE REGION -- Please do not attempt to edit below this line, you will break the system! 

def unzip_file_into_dir(file, dir):
    if not os.path.exists(dir):
        os.mkdir(dir, 0777)
    zfobj = zipfile.ZipFile(file)
    for name in zfobj.namelist():
        outfile = open(os.path.join(dir, name), 'wb')
        outfile.write(zfobj.read(name))
        outfile.close()

def grabFiles():
    filestoget = ["allCountries.zip", "alternateNames.zip", "userTags.zip", "admin1Codes.txt", "admin1CodesASCII.txt", "admin2Codes.txt", "countryInfo.txt", "featureCodes_en.txt", "iso-languagecodes.txt", "timeZones.txt"]
    
    for item in filestoget:
        print "Downloading: "+item
        os.system("wget http://download.geonames.org/export/dump/"+item)
    print "All files downloaded!... Processing..."
    
    for item in filestoget:
        print "Unzipping "+item
        unzip_file_into_dir(item, 'tmp/')
    print "Done! Uploading data to server..."

def doCountryRPC(line):
    server_url = SERV+APIENDPOINT;
    # Set up the server.
    server = xmlrpclib.Server(server_url);
    try:
        encoded = encoded = base64.b64encode(line)
        result = server.geordf.loaddata(encoded,UNAME, PWORD)
        return result
    except:
        print "RPC FAILED"
        sys.exit()


def main():
    grabFiles()
    count = 0           
    for line in fileinput.input(['tmp/allCountries.txt']):
        count = count+1
        print doCountryRPC(line)+": "+str(count)
    
print "Data upload complete!"

if __name__ == '__main__': main()
