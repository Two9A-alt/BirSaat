<?php
/**
 * BirSaat Framework: Singleton accessor factory
 *
 * The factory can be used for accessing classes that should be built once,
 * such as the configuration handler.
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class bsFactory {
    static private $storage = array();

    /**
     * Retrieve an instance of a given class
     * @param name string Truncated name of a class (eg. "config")
     * @return Singleton instance of the requested class
     */
    static public function get($name)
    {
        if (!isset(self::$storage[$name])) {
            $class = 'bs'.$name;
            self::$storage[$name] = new $class;
        }
        return self::$storage[$name];
    }
}

