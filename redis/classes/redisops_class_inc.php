<?php

class redisops extends object
{
    private $objRedis;

    public function init()
    {
        include $this->getResourcePath('redis.php');
        $this->objRedis = new php_redis('127.0.0.1', 6379);
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
