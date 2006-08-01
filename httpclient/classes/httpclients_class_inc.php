<?php

require_once('abhttpclient_class_inc.php');

class httpclients extends abhttpclient
{
    /**
     * Supported HTTP Authentication methods
     *
     */
    const AUTH_BASIC = 'basic';
    //const AUTH_DIGEST = 'digest'; <-- not implemented yet

	/**
     * HTTP proxy settings
     *
     * @var array
     */
    protected $proxy = array('host' => null, 'port' => null, 'user' => null, 'password' => null);

    /**
     * HTTP Authentication settings
     *
     * Expected to be an associative array with this structure:
     * $this->auth = array('user' => 'username', 'password' => 'password', 'type' => 'basic')
     * Where 'type' should be one of the supported authentication types (see the AUTH_*
     * constants), for example 'basic' or 'digest'.
     *
     * If null, no authentication will be used.
     *
     * @var array|null
     */
    protected $auth;

    /**
     * Class Constructor, create and validate Uri object
     *
     * @param  string|Uri|null $uri
     * @param  array $headers
     * @return void
     */
    public function __construct($uri = null, $headers = array())
    {
    	if ($uri !== null) {
    		$this->setUri($uri);
    	}

    	if ($headers !== array()) {
    		$this->setHeaders($headers);
    	}
    }

    /**
     * Set a proxy server for the request
     *
     * @param string|null $host Hostname or null to disable proxy
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function setProxy($host, $port = 8080, $user = null, $password = null)
    {
        $this->proxy = array(
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'password' => $password
        );
    }

    /**
     * Set HTTP authentication parameters
     *
     * $type should be one of the supported types - see the self::AUTH_*
     * constants.
     *
     * To disable authentication:
     *     @example $this->setAuth(false);
     *
     * @see http://www.faqs.org/rfcs/rfc2617.html
     * @param string|false $user User name or false disable authentication
     * @param string $password Password
     * @param string $type Authentication type
     */
    public function setAuth($user, $password = '', $type = self::AUTH_BASIC)
    {
        // If we got false or null, disable authentication
        if ($user === false || $user === null) {
            $this->auth = null;

        // Else, set up authentication
        } else {
            // Check we got a proper authentication type
            if (! defined('self::AUTH_' . strtoupper($type)))
                throw new customException("Invalid or not supported authentication type: '$auth'");

            $this->auth = array(
                'user' => (string) $user,
                'password' => (string) $password,
                'type' => $type
            );
        }
    }

   /**
     * Send a GET HTTP Request
     *
     * @param  int $redirectMax Maximum number of HTTP redirections followed
     * @return httpresponse
     */
    public function get($redirectMax = 5)
    {
        /**
         * @todo Implement ability to send Query Strings
         */

        // Follow HTTP redirections, up to $redirectMax of them
        for ($redirect = 0; $redirect <= $redirectMax; $redirect++) {

            // Build the HTTP request
            $hostHeader = $this->_uri->getHost() . ($this->_uri->getPort() == 80 ? '' : ':' . $this->_uri->getPort());
            $request = array_merge(array('GET ' . $this->_uri->getPath() . '?' . $this->_uri->getQuery() . ' HTTP/1.0',
                                         'Host: ' . $hostHeader,
                                         'Connection: close'),
                                   $this->_headers);

            // Open a TCP connection
            $socket = $this->_openConnection();

            // Make the HTTP request
            fwrite($socket, implode("\r\n", $request) . "\r\n\r\n");

            // Fetch the HTTP response
            $response = $this->_read($socket);

            // If the HTTP response was a redirect, and we are allowed to follow additional redirects
            if ($response->isRedirect() && $redirect < $redirectMax) {

                // Fetch the HTTP response headers
                $headers = $response->getHeaders();

                // Attempt to find the Location header
                foreach ($headers as $headerName => $headerValue) {
                    // If we have a Location header
                    if (strtolower($headerName) == "location") {
                        // Set the URI to the new value
                        if (urihttp::check($headerValue)) {
                        	// If we got a well formed absolute URI, set it
                        	$this->setUri($headerValue);
                        } else {
                        	// Split into path and query and set the query
                    	    list($headerValue, $query) = explode('?', $headerValue, 2);
                    	    $this->_uri->setQueryString($query);

                        	if (strpos($headerValue, '/') === 0) {
                        		// If we got just an absolute path, set it
                          	    $this->_uri->setPath($headerValue);

                        	} else {
                        	    // Else, assume we have a relative path
                        	    $path = dirname($this->_uri->getPath());
                        	    $path .= ($path == '/' ? $headerValue : "/{$headerValue}" );
                        	    $this->_uri->setPath($path);
                        	}
                        }

                        // Continue with the new redirected request
                        continue 2;
                    }
                }
            }

            // No more looping for HTTP redirects
            break;
        }

        // Return the HTTP response
        return $response;
    }


    /**
     * Send a POST HTTP Request
     *
     * @param string $data Data to send in the request
     * @return httpresponse
     */
    public function post($data)
    {
        $socket = $this->_openConnection();

        $hostHeader = $this->_uri->getHost() . ($this->_uri->getPort() == 80 ? '' : ':' . $this->_uri->getPort());
        $request = array_merge(array('POST ' . $this->_uri->getPath() . ' HTTP/1.0',
                                     'Host: ' . $hostHeader,
                                     'Connection: close',
                                     'Content-length: ' . strlen($data)),
                               $this->_headers);

        fwrite($socket, implode("\r\n", $request) . "\r\n\r\n" . $data . "\r\n");

        return $this->_read($socket);
    }


    /**
     * Send a PUT HTTP Request
     *
     * @param string $data Data to send in the request
     * @return httresponse
     */
    public function put($data)
    {
        $socket = $this->_openConnection();

        $hostHeader = $this->_uri->getHost() . ($this->_uri->getPort() == 80 ? '' : ':' . $this->_uri->getPort());
        $request = array_merge(array('PUT ' . $this->_uri->getPath() . ' HTTP/1.0',
                                     'Host: ' . $hostHeader,
                                     'Connection: close',
                                     'Content-length: ' . strlen($data)),
                               $this->_headers);

        fwrite($socket, implode("\r\n", $request) . "\r\n\r\n" . $data . "\r\n");

        return $this->_read($socket);
    }


    /**
     * Send a DELETE HTTP Request
     *
     * @return httpresponse
     */
    public function delete()
    {
        $socket = $this->_openConnection();

        $hostHeader = $this->_uri->getHost() . ($this->_uri->getPort() == 80 ? '' : ':' . $this->_uri->getPort());
        $request = array_merge(array('DELETE ' . $this->_uri->getPath() . ' HTTP/1.0',
                                     'Host: ' . $hostHeader,
                                     'Connection: close'),
                               $this->_headers);

        fwrite($socket, implode("\r\n", $request) . "\r\n\r\n");

        return $this->_read($socket);
    }


    /**
     * Open a TCP connection for our HTTP/SSL request.
     *
     * @throws customException
     * @return resource Socket Resource
     */
    protected function _openConnection()
    {
    	if (!$this->_uri instanceof aburi) {
    		throw new customException('URI must be set before performing remote operations');
    	}

        // If the URI should be accessed via SSL, prepend the Hostname with ssl://
        $host = ($this->_uri->getScheme() == 'https') ? 'ssl://' . $this->_uri->getHost() : $this->_uri->getHost();
        $socket = @fsockopen($host, $this->_uri->getPort(), $errno, $errstr, $this->_timeout);
        if (!$socket) {
            // Added more to the exception message, $errstr is not always populated and the message means nothing then.
            throw new customException('Unable to Connect to ' . $this->_uri->getHost() . ': ' . $errstr .
                                                ' (Error Number: ' . $errno . ')');
        }
        return $socket;
    }


    /**
     * Read Data from the Socket
     *
     * @param Resource $socket Socket returned
     * @return httpresponse
     */
    protected function _read($socket)
    {
    	$responseCode    = null;
    	$responseHeaders = array();
    	$responseBody    = null;

		$hdr = null;
        while (strlen($header = rtrim(fgets($socket, 8192)))) {
            if (preg_match('|HTTP/\d.\d (\d+) (\w+)|', $header, $matches)) {
                $responseCode = (int) $matches[1];
            } else if (preg_match('|^\s|', $header)) {
                if ($hdr !== null) {
	                $responseHeaders[$hdr] .= ' ' . trim($header);
                }
            } else {
                $pieces = explode(': ', $header, 2);
                $responseHeaders[$pieces[0]] = isset($pieces[1]) ? $pieces[1] : null;
            }
        }

        while (!feof($socket)) {
            $responseBody .= fgets($socket, 8192);
        }

        fclose($socket);

        return new httpresponse($responseCode, $responseHeaders, $responseBody);
    }

    /**
     * Create a HTTP authentication "Authorization:" header according to the
     * specified user, password and authentication method.
     *
     * @see http://www.faqs.org/rfcs/rfc2617.html
     * @param string $user
     * @param string $password
     * @param string $type
     * @return string
     */
    static public function encodeAuthHeader($user, $password, $type = self::AUTH_BASIC)
    {
        $authHeader = null;

        switch ($type) {
            case self::AUTH_BASIC:
                // In basic authentication, the user name cannot contain ":"
                if (strpos($user, ':') !== false)
                    throw new customException("The user name cannot contain ':' in 'Basic' HTTP authentication");

                $authHeader = 'Basic ' . base64_encode($user . ':' . $password);
                break;

            //case self::AUTH_DIGEST:
                /**
                 * @todo Implement digest authentication
                 */
            //    break;

            default:
                throw new customException("Not a supported HTTP authentication type: '$type'");
        }

        return $authHeader;
    }
}
?>