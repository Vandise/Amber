<?php
  
/**
 * Amber Framework Autoloader
 *
 * Autoloads the Amber Framework
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber
 * @since 0.1
 * @version v0.1
 */

 if (RUNNING_TESTS) {
    define('AMBER_ROOT_PATH', dirname(dirname(__FILE__)));
 }

 if (!defined('AMBER_ROOT_PATH')) {
    throw new Exception('Unable to load AMBER_ROOT_PATH');
 }

function load($class) {
  $class = strtolower(preg_replace('/\B([A-Z])/', '_$1', $class));
  $fragments = explode( '\\', $class );
  $file = AMBER_ROOT_PATH.
    implode( '', 
      array_map( 
        function($fragment, $i) use ($class){
          if ($i == 2)
          {
            return "/src/$fragment"; 
          }
          else
          {
            return "/$fragment"; 
          }
        }, $fragments, array_keys($fragments)
      )
    ).'.php';
    if(file_exists($file))
    {
      require_once $file;
      return $file;
    }
    return $file;
}

spl_autoload_register('load');