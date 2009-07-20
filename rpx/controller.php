<?php

class rpx extends controller
{
    protected $objAltConfig;
    protected $objSysConfig;

    protected $data;

    public function init()
    {
        $this->objAltConfig = $this->getObject('altconfig', 'config');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    }

    public function dispatch()
    {
        // Define a list of valid actions.
        $actions = array('token');

        // Define the default action.
        $default = 'main';

        // Retrieve the requested action.
        $action = $this->getParam('action');

        // If the requested action is not valid, use the default.
        if (!in_array($action, $actions)) {
            $action = $default;
        }

        // Set the layout template.
        $this->setLayoutTemplate('user_layout_tpl.php');

        // Execute the action and catch whatever it returns.
        $data = $this->$action();

        // Return whatever the action method returned.
        return $data;
    }

    public function main()
    {
        // Call the main template.
        return 'main_tpl.php';
    }

    public function token()
    {
        // Fetch token query string parameter.
        $token = $this->getParam('token');

        // Build RPX auth_info API method URI.
        $uri = 'https://rpxnow.com/api/v2/auth_info';

        // Build RPX auth_info API method parameters.
        $params = array();
        $params['apiKey'] = $this->objSysConfig->getValue('key', 'rpx');
        $params['token'] = $token;

        // Compile the HTTP POST data.
        $post = http_build_query($params);

        // Create context for the HTTP POST request to the RPX API.
        $context = stream_context_create(array('http'=>array('method'=>'POST','content'=>$post)));

        // Perform the HTTP request to the RPX API and capture the result.
        $json = file_get_contents($uri, 0, $context);

        // Decode the JSON response retrieved from the RPX API to an array.
        $this->data = json_decode($json, true);

        // Call the token template.
        return 'token_tpl.php';
    }
}
