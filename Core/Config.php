<?php
namespace Core;

class Config
{
    const CONFIG_FILE = '/Application/Config/config.json';
    private $config;

    public function __construct()
    {
        $json = file_get_contents(dirname(__DIR__) . self::CONFIG_FILE);
        $this->config = json_decode($json, true);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getConfigDetail($detail)
    {
        if(array_key_exists($detail, $this->config)) {
            return $this->config[$detail];
        }
        return null;
    }
}