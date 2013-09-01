<?php
/**
 * BirSaat Framework: Configuration reader
 *
 * Reads all the configuration files in config/ and holds the defined
 * configuration values for read-only access.
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class bsConfig {
    private $config = array();

    /**
     * Class constructor
     */
    public function __construct()
    {
        foreach (glob('../config/*.ini') as $ini) {
            $map = parse_ini_file($ini);
            if ($map && is_array($map) && count($map)) {
                $this->config = array_merge($this->config, $map);
            }
        }
    }

    /**
     * Configuration value getter
     * @param key string Configuration key to fetch
     * @return mixed Value of the requested configuration key
     * @throws bsException if the requested key is not set
     */
    public function __get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            throw new bsException('Configuration key not found: '.$key);
        }
    }
}

