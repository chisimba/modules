<?php
/* -------------------- blowcryptclient class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This class provides encryption using Blowfish for strings and arrays using session keys, which
* are regenerated daily. The primary purpose of the encryption is to encrypt
* @package blowcrypt
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Serge Meunier
*/
class blowcryptclient extends object
{
	/**
     * The initialization vector for the encryption engine
     *
     * @access private
     * @var object
    */
    private $iv;

	/**
     * The encryption method to use
     *
     * @access private
     * @var object
    */
    private $encryptMethod = MCRYPT_BLOWFISH;

	/**
     * The encryption mode to use
     *
     * @access private
     * @var object
    */
    private $encryptMode = MCRYPT_MODE_ECB;

    /**
     * The path of the master key file
     *
     * @access private
     * @var object
    */
    private $masterKeyPath;

    /**
     * The path of the session key file
     *
     * @access private
     * @var object
    */
    private $sessionKeyPath;

    /**
     * The path of the check key file
     *
     * @access private
     * @var object
    */
    private $checkKeyPath;

    /**
     * The URL of the webservice to obtain the session key
     *
     * @access private
     * @var object
    */
    private $sessionSoapServer;

    /**
     * The soap client object
     *
     * @access private
     * @var object
    */
    private $objSoapClient;

	/**
	 * Constructor
	 */
    public function init()
    {
        $objSysconfig = $this->newObject('dbsysconfig', 'sysconfig');

        $this->masterKeyPath = $objSysconfig->getValue('MASTERKEYPATH', 'blowcrypt');
        $this->checkKeyPath = $objSysconfig->getValue('CHECKKEYPATH', 'blowcrypt');
        $this->sessionKeyPath = $objSysconfig->getValue('SESSIONKEYPATH', 'blowcrypt');
        $this->sessionSoapServer = 'http://'.$objSysconfig->getValue('CRYPTSESSIONWS', 'blowcrypt');
        $this->objSoapClient = new SoapClient($this->sessionSoapServer);

        $iv_size = mcrypt_get_iv_size($this->encryptMethod, $this->encryptMode);
        $this->iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    }

 	/**
	 * Method to encrypt the specified string using the specified key
     *
	 * @param string $data - The string containing the data to encrypt
     * @param string $key - The key to encrypt the data with
	 * @return string - The encrypted data
	 * @access public
     *
	 */
    public function encrypt($data, $key)
    {
        $encrypted_data = mcrypt_encrypt($this->encryptMethod, $key, $data, $this->encryptMode, $this->iv);
        $encrypted_data = bin2hex($encrypted_data);
        return $encrypted_data;
    }

 	/**
	 * Method to decrypt the specified string using the specified key
     *
	 * @param string $data - The string containing the encrypted data
     * @param string $key - The key to decrypt the data with
	 * @return string - The decrypted data
	 * @access public
     *
	 */
    public function decrypt($data, $key)
    {
        $data = pack("H*", $data);
        $decrypted_data = mcrypt_decrypt($this->encryptMethod, $key, $data, $this->encryptMode, $this->iv);
        $decrypted_data = rtrim($decrypted_data, "\0");
        return $decrypted_data;
    }

 	/**
	 * Method to recursively encrypt or decrypt the specified array using the specified key
     *
	 * @param array $data - The array containing the data to encrypt
     * @param string $key - The key to encrypt the data with
     * @param boolean $decrypt - encrypt if TRUE, decrypt if FALSE
	 * @return string - The encrypted/decrypted array
	 * @access public
     *
	 */
    public function encryptArray($data, $key, $decrypt = FALSE)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $element) {
                $result[] = $this->encryptArray($element, $key, $decrypt);
            }
        } else {
            if ($decrypt) {
                $result = $this->decrypt($data, $key);
            } else {
                $result = $this->encrypt($data, $key);
            }
        }
        return $result;
    }

    /**
	 * Method to generate a random session key
     *
	 * @return string - The encrypted session key
	 * @access public
     *
	 */
    private function generateSessionKey()
    {
        $sessionKey = '';
        $keyLen = 50;
        $salt = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";  // salt to select chars from
        srand((double)microtime()*1000000); // start the random generator
        for ($i = 0; $i < $keyLen; $i++)
        {
            $sessionKey .= substr($salt, rand() % strlen($salt), 1);
        }
        $sessionKey = $this->encryptSessionKey($sessionKey);
        return $sessionKey;
    }

 	/**
	 * Method to encrypt the session key with the added key check
     *
     * @param string $key - The key to encrypt
	 * @return string - The encrypted key
	 * @access public
     *
	 */
    private function encryptSessionKey($key)
    {
        $sessionKey = $this->encrypt($key, $this->getMasterKey());
        $sessionKey .= $this->generateSessionCheck($key);
        return $sessionKey;
    }

 	/**
	 * Method to decrypt the session key and check against the check key
     *
     * @param string $sessionKey - The key to decrypt
	 * @return string - The decrypted key
	 * @access public
     *
	 */
    private function decryptSessionKey($sessionKey)
    {

        $key = substr($sessionKey, 0, strlen($sessionKey) - 8);
        $check = substr($sessionKey, strlen($sessionKey)-8);

        $key = $this->decrypt($key, $this->getMasterKey());

        if ($this->validSessionCheck($check, $key)) {
            return $key;
        } else {
            return null;
        }
    }

 	/**
	 * Method to get a session key from a server using webservices
     *
	 * @return string - The session key
	 * @access public
     *
	 */
    private function getSessionKeyFromServer()
    {
        $sessionKey = $this->objSoapClient->getSessionkey();

        $sessionKey = $this->decryptSessionKey($sessionKey);
        if (!is_null($sessionKey)) {
            $this->writeSessionKeyToFile($sessionKey);
        }
        return $sessionKey;
    }

 	/**
	 * Method to get the current session key
     *
	 * @return string - The session key
	 * @access public
     *
	 */
    public function getSessionKey()
    {
        $sessionKey = $this->getSessionKeyFromFile();
        if (is_null($sessionKey)) {
            $sessionKey = $this->getSessionKeyFromServer();
        }
        return $sessionKey;
    }

 	/**
	 * Method to write the current session key to the local file system for further reference
     *
     * @param string $sessionKey - The key to write
	 * @return boolean - FALSE if failed, TUE if successful
	 * @access private
     *
	 */
    private function writeSessionKeyToFile($sessionKey)
    {
        if (!$handle = fopen($this->sessionKeyPath, "w+")) {
            return FALSE;
        }
        if (fwrite($handle, $sessionKey) == FALSE) {
            return FALSE;
        }
        fclose($handle);
        return TRUE;
    }

 	/**
	 * Method to read a session key from local file
     *
	 * @return string - The session key
	 * @access private
     *
	 */
    private function getSessionKeyFromFile()
    {
        $sessionKey = null;

        if (file_exists($this->sessionKeyPath)) {
            $handle = fopen($this->sessionKeyPath, "r");
            $fileStats = fstat($handle);
            $fileStats = array_slice($fileStats, 13);
            if (date("d m Y") == date("d m Y", $fileStats['mtime'])) {
                $sessionKey = fgets($handle);
            }
            fclose($handle);
            if (strlen($sessionKey) == 0) {
                $sessionKey = null;
            }
        }
        return $sessionKey;
    }

  	/**
	 * Method to create the check string for a session key
     *
     * @param string $key - The key to use to create check
	 * @return string - The check string
	 * @access private
     *
	 */
   private function generateSessionCheck($key)
    {
        $checkStr = $this->getCheckKeyFromFile();
        $check = $this->encrypt($checkStr, $key);
        $check = substr($check, 0, 8);
        return $check;
    }

 	/**
	 * Method to check that a check for a key is valid
     *
     * @param string $checkStr - The check string to decrypt
     * @param string $key - The key to check
	 * @return boolean - FALSE if failed, TRUE if valid
	 * @access private
     *
	 */
    private function validSessionCheck($checkStr, $key)
    {
        $sessionCheck = $this->generateSessionCheck($key);

        if ($sessionCheck == $checkStr) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

  	/**
	 * Method to get the master key
     *
	 * @return string - The master key
	 * @access private
     *
	 */
    private function getMasterKey()
    {
        $masterKey = $this->getMasterKeyFromFile();
        return $masterKey;
    }

  	/**
	 * Method to get the master key from the local file system
     *
	 * @return string - The master key
	 * @access private
     *
	 */
    private function getMasterKeyFromFile()
    {
        $masterKey = null;

        if (file_exists($this->masterKeyPath)) {
            $handle = fopen($this->masterKeyPath, "r");
            $masterKey = fgets($handle);
            fclose($handle);
            if (strlen($masterKey) == 0) {
                $masterKey = null;
            }
        }
        return $masterKey;

    }

  	/**
	 * Method to get the check key from the local file system
     *
	 * @return string - The check key
	 * @access private
     *
	 */
    private function getCheckKeyFromFile()
    {
        $checkKey = null;

        if (file_exists($this->checkKeyPath)) {
            $handle = fopen($this->checkKeyPath, "r");
            $checkKey = fgets($handle);
            fclose($handle);
            if (strlen($checkKey) == 0) {
                $checkKey = null;
            }
        }
        return $checkKey;

    }
}

?>