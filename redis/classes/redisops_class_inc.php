<?php

class redisops extends object
{
    private $objRedis;

    /**
     * Instance of the dbsysconfig class of the sysconfig module.
     *
     * @access private
     * @var    object
     */
    private $objSysConfig;

    public function init()
    {
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $host = $this->objSysConfig->getValue('host', 'redis');
        $port = $this->objSysConfig->getValue('port', 'redis');
        include $this->getResourcePath('redis.php');
        $this->objRedis = new php_redis($host, $port);
    }

    public function get($key)
    {
        return $this->objRedis->get($key);
    }

    public function delete($key)
    {
        $this->objRedis->delete($key);
    }

    public function set($key, $value)
    {
        $this->objRedis->set($key, $value);
    }
}
