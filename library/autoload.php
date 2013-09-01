<?php
/**
 * BirSaat Framework: Autoloader
 *
 * Registers an SPL autoloader for BirSaat controllers, models and
 * library classes.
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

spl_autoload_register(function($class) {
    if (preg_match('#^(\w+)(Controller|Model)$#i', $class, $names)) {
        $file = '../'.strtolower($names[2]).'s/'.strtolower(basename($names[1])).'.php';
    } else if (preg_match('#^bs(\w+)$#i', $class, $names)) {
        $file = '../library/'.strtolower(basename($names[1])).'.php';
    }
    if (isset($file) && $file && file_exists($file)) {
        include_once $file;
    } else {
        throw new Exception('Class '.$class.' not found');
    }
}, true);

